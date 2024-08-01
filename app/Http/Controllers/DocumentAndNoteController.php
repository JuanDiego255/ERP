<?php

namespace App\Http\Controllers;

use App\Models\DocumentAndNote;
use App\Models\EmployeeRubros;
use App\Models\Media;
use App\Models\RubrosPlanilla;
use App\Models\User;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DocumentAndNoteController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $moduleUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {

            $business_id = request()->session()->get('user.business_id');
            $employee_id = request()->get('employee_id');

            $rubros_employees = EmployeeRubros::where('employee_rubros.business_id', $business_id)
                ->where('employee_rubros.employee_id', $employee_id)
                ->join('rubros_planillas', 'employee_rubros.rubro_id', 'rubros_planillas.id')
                ->select(
                    'employee_rubros.id',
                    'employee_rubros.status',
                    'employee_rubros.tipo',
                    'employee_rubros.valor',
                    'rubros_planillas.name'
                );

            return Datatables::of($rubros_employees)
                ->addColumn('action', function ($row) {
                    // return '';
                    $html = '<div class="btn-group">
                    <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                    ' . __("messages.action") . '
                    <span class="caret"></span>
                    <span class="sr-only">
                    ' . __("messages.action") . '
                    </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">
                    ';


                    $html .= '<li>
                        <a data-href="' . action('DocumentAndNoteController@edit', [$row->id, 'id' => $row->id, 'employee_id' => $row->employee_id]) . '"  class="cursor-pointer docs_and_notes_btn">
                        <i class="fa fa-edit"></i>
                        ' . __("messages.edit") . '
                        </a>
                        </li>';


                    $html .= '<li>
                        <a data-href="' . action('EmployeeController@destroyRubro', [$row->id]) . '"  id="delete_docus_note" class="cursor-pointer">
                        <i class="fas fa-trash"></i>
                        ' . __("messages.delete") . '
                        </a>
                        </li>';


                    $html .= '</ul>
                    </div>';

                    return $html;
                })
                ->editColumn('tipo', function ($data) {
                    return $data->tipo == 'quincenal' ? 'Quincenal' : 'Mensual';
                })
                ->editColumn('status', function ($data) {
                    return $data->status == '1' ? 'Activo' : 'Inactivo';
                })
                ->removeColumn('id')
                ->rawColumns(['action', 'tipo', 'rubro'])
                ->make(true);
        }
    }

    /**
     * Returns the array of permission for the notable.
     *
     * @return array of permissions
     */
    private function __getPermission($business_id, $notable_id, $notable_type)
    {
        $permissions = [];

        //Define all notable for main app.
        $app_notable = [
            'App\Models\User' => [
                'permissions' => ['view', 'create', 'delete']
            ],
            'App\Models\Contact' => [
                'permissions' => ['view', 'create', 'delete']
            ]
        ];

        if (isset($app_notable[$notable_type])) {
            return $app_notable[$notable_type]['permissions'];
        } else {
            //If not found in main app, get from modules.
            $module_parameters = [
                'business_id' => $business_id,
                'notable_id' => $notable_id,
                'notable_type' => $notable_type
            ];
            $module_data = $this->moduleUtil->getModuleData('addDocumentAndNotes', $module_parameters);

            foreach ($module_data as $module => $data) {
                if (isset($data[$notable_type])) {
                    return $data[$notable_type]['permissions'];
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //model id like project_id, user_id
        $employee_id = request()->get('employee_id');
        $rubros = RubrosPlanilla::where('status', 1)->get()->pluck('name', 'id');
        return view('admin.rubros.tab_rubros.create')
            ->with(compact('employee_id', 'rubros'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            //model id like project_id, user_id
            $notable_id = request()->get('notable_id');
            //model name like App\User
            $notable_type = request()->get('notable_type');

            $input = $request->only('heading', 'description', 'is_private');
            $input['business_id'] = request()->session()->get('user.business_id');
            $input['created_by'] = request()->session()->get('user.id');

            DB::beginTransaction();

            if (!empty($input['is_private'])) {
                activity()->disableLogging();
            }

            //find model to which document is to be added
            $model = $notable_type::where('business_id', $input['business_id'])
                ->findOrFail($notable_id);

            $model_note = $model->documentsAndnote()->create($input);

            if (!empty($request->get('file_name')[0])) {
                $file_names = explode(',', $request->get('file_name')[0]);
                $business_id = request()->session()->get('user.business_id');
                Media::attachMediaToModel($model_note, $business_id, $file_names);
            }

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success')
            ];
        } catch (Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //model id like project_id, user_id
        $notable_id = request()->get('notable_id');
        //model name like App\User
        $notable_type = request()->get('notable_type');

        $business_id = request()->session()->get('user.business_id');
        $document_note = DocumentAndNote::where('business_id', $business_id)
            ->where('notable_id', $notable_id)
            ->where('notable_type', $notable_type)
            ->with('media', 'createdBy')
            ->findOrFail($id);

        return view('documents_and_notes.show')
            ->with(compact('document_note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $rubros = RubrosPlanilla::where('status', 1)->get()->pluck('name', 'id');
        $employee_rubros = EmployeeRubros::where('business_id', $business_id)
            ->findOrFail($id);

        return view('admin.rubros.tab_rubros.edit')
            ->with(compact('employee_rubros','rubros'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            //model id like project_id, user_id
            $notable_id = request()->get('notable_id');
            //model name like App\User
            $notable_type = request()->get('notable_type');

            $business_id = request()->session()->get('user.business_id');

            $input = $request->only('heading', 'description');
            $input['is_private'] = !empty($request->get('is_private')) ? 1 : 0;

            $document_note = DocumentAndNote::where('business_id', $business_id)
                ->where('notable_id', $notable_id)
                ->where('notable_type', $notable_type)
                ->findOrFail($id);

            DB::beginTransaction();

            if ($input['is_private']) {
                $document_note->disableLogging();
            }

            $document_note->heading = $input['heading'];
            $document_note->description = $input['description'];
            $document_note->is_private = $input['is_private'];
            $document_note->save();

            if (!empty($request->get('file_name')[0])) {
                $file_names = explode(',', $request->get('file_name')[0]);
                $business_id = request()->session()->get('user.business_id');
                Media::attachMediaToModel($document_note, $business_id, $file_names);
            }

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success')
            ];
        } catch (Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $business_id = request()->session()->get('user.business_id');
            //model id like project_id, user_id
            $notable_id = request()->get('notable_id');
            //model name like App\User
            $notable_type = request()->get('notable_type');

            $document_note = DocumentAndNote::where('business_id', $business_id)
                ->where('notable_id', $notable_id)
                ->where('notable_type', $notable_type)
                ->findOrFail($id);

            DB::beginTransaction();

            $document_note->delete();
            $document_note->media()->delete();

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success')
            ];
        } catch (Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * upload documents in app
     *
     * @return \Illuminate\Http\Response
     */
    public function postMedia(Request $request)
    {
        try {
            $file = $request->file('file')[0];

            $file_name = Media::uploadFile($file);

            $output = [
                'success' => true,
                'file_name' => $file_name,
                'msg' => __('lang_v1.success')
            ];
        } catch (Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * get docus & note index page
     * through ajax
     * @return \Illuminate\Http\Response
     */
    public function getRubrosEmployeePage(Request $request)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $employee_id = $request->get('employee_id');

            return view('admin.rubros.tab_rubros.index')
                ->with(compact('employee_id'));
        }
    }
}

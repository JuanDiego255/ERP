<?php

namespace App\Http\Controllers\Install;

use \Module;
use App\Http\Controllers\Controller;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\Artisan;
use App\Models\System;
use Illuminate\Support\Facades\DB;

class ModulesController extends Controller
{
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
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
        if (!auth()->user()->can('manage_modules')) {
            abort(403, 'Unauthorized action.');
        }

        //Get list of all modules.
        $modules = Module::toCollection()->toArray();

        foreach ($modules as $module => $details) {

            $modules[$module]['is_installed'] = $this->moduleUtil->isModuleInstalled($details['name']) ? true : false;

            //Get version information.
            if ($modules[$module]['is_installed']) {
                $modules[$module]['version'] = $this->moduleUtil->getModuleVersionInfo($details['name']);
            }

            //Install Link.
            try {
                $modules[$module]['install_link'] = action('\Modules\\' . $details['name'] . '\Http\Controllers\InstallController@index');
            } catch (\Exception $e) {
                $modules[$module]['install_link'] = "#";
            }

            //Update Link.
            try {
                $modules[$module]['update_link'] = action('\Modules\\' . $details['name'] . '\Http\Controllers\InstallController@update');
            } catch (\Exception $e) {
                $modules[$module]['update_link'] = "#";
            }

            //Uninstall Link.
            try {
                $modules[$module]['uninstall_link'] = action('\Modules\\' . $details['name'] . '\Http\Controllers\InstallController@uninstall');
            } catch (\Exception $e) {
                $modules[$module]['uninstall_link'] = "#";
            }
        }

        $is_demo = (config('app.env') == 'demo');
        $mods = $this->__available_modules();

        return view('install.modules.index')
        ->with(compact('modules', 'is_demo', 'mods'));


        //Option to uninstall

        //Option to activate/deactivate

        //Upload module.
    }

    public function instalSuper(){
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->installSettings();
        
        //Check if installed or not.

        $sys = System::all();
        // echo $sys;
        // die;
        $is_installed = System::getProperty('superadmin'. '_version');
        if (empty($is_installed)) {

            DB::statement('SET default_storage_engine=INNODB;');
            Artisan::call('module:migrate', ['module' => "Superadmin"]);
            System::addProperty('superadmin' . '_version', '2.2');
        }

        DB::statement('SET default_storage_engine=INNODB;');
        Artisan::call('module:migrate', ['module' => "Superadmin"]);
        System::addProperty('superadmin' . '_version', '2.2');

        $output = ['success' => 1,
        'msg' => 'Superadmin module installed succesfully'
    ];

    return redirect()
    ->action('\App\Http\Controllers\Install\ModulesController@index')
    ->with('status', $output);
}

private function installSettings()
{
    config(['app.debug' => true]);
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Activate/Deaactivate the specified module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $module_name)
    {
        if (!auth()->user()->can('manage_modules')) {
            abort(403, 'Unauthorized action.');
        }
        
        $notAllowed = $this->moduleUtil->notAllowedInDemo();
        if (!empty($notAllowed)) {
            return $notAllowed;
        }

        try {
            $module = Module::find($module_name);

            //php artisan module:disable Blog
            if ($request->action_type == 'activate') {
                $module->enable();
            } elseif ($request->action_type == 'deactivate') {
                $module->disable();
            }

            $output = ['success' => true,
            'msg' => __("lang_v1.success")
        ];
    } catch (\Exception $e) {
        $output = ['success' => false,
        'msg' => $e->getMessage()
    ];
}

return redirect()->back()->with(['status' => $output]);
}

    /**
     * Deletes the module.
     *
     * @param  string  $module_name
     * @return \Illuminate\Http\Response
     */
    public function destroy($module_name)
    {
        if (!auth()->user()->can('manage_modules')) {
            abort(403, 'Unauthorized action.');
        }

        $notAllowed = $this->moduleUtil->notAllowedInDemo();
        if (!empty($notAllowed)) {
            return $notAllowed;
        }

        try {
            $module = Module::find($module_name);
            $module->delete();

            $output = ['success' => true,
            'msg' => __("lang_v1.success")
        ];
    } catch (\Exception $e) {
        $output = ['success' => false,
        'msg' => $e->getMessage()
    ];
}

return redirect()->back()->with(['status' => $output]);
}

    /**
     * Upload the module.
     *
     */
    public function uploadModule(Request $request)
    {
        $notAllowed = $this->moduleUtil->notAllowedInDemo();
        if (!empty($notAllowed)) {
            return $notAllowed;
        }

        try {
            //get zipped file
            $module = $request->file('module');

            //check if uploaded file is valid or not and and if not redirect back
            if ($module->getMimeType() != 'application/zip') {
                $output = ['success' => false,
                'msg' => __('lang_v1.pls_upload_valid_zip_file')
            ];

            return redirect()->back()->with(['status' => $output]);
        }

            //check if 'Modules' folder exist or not, if not exist create
        $path = '../Modules';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

            //extract the zipped file in given path
        $zip = new ZipArchive();
        if ($zip->open($module) === true) {
            $zip->extractTo($path .'/');
            $zip->close();
        }

        $output = ['success' => true,
        'msg' => "Módulo instalado"
    ];
} catch (Exception $e) {
    $output = ['success' => false,
    'msg' => __('messages.something_went_wrong')
];
}

return redirect()->back()->with(['status' => $output]);
}

private function __available_modules()
{
    return 'a:5:{i:0;a:4:{s:1:"n";s:10:"Essentials";s:2:"dn";s:17:"Essentials Module";s:1:"u";s:53:"https://ultimatefosters.com/recommends/essential-app/";s:1:"d";s:49:"Essentials features for every growing businesses.";}i:1;a:4:{s:1:"n";s:10:"Superadmin";s:2:"dn";s:17:"Superadmin Module";s:1:"u";s:54:"https://ultimatefosters.com/recommends/superadmin-app/";s:1:"d";s:76:"Turn your POS to SaaS application and start earning by selling subscriptions";}i:2;a:4:{s:1:"n";s:11:"Woocommerce";s:2:"dn";s:18:"Woocommerce Module";s:1:"u";s:55:"https://ultimatefosters.com/recommends/woocommerce-app/";s:1:"d";s:36:"Sync your Woocommerce store with POS";}i:3;a:4:{s:1:"n";s:13:"Manufacturing";s:2:"dn";s:20:"Manufacturing Module";s:1:"u";s:57:"https://ultimatefosters.com/recommends/manufacturing-app/";s:1:"d";s:70:"Manufacture products from raw materials, organise recipe & ingredients";}i:4;a:4:{s:1:"n";s:7:"Project";s:2:"dn";s:31:"Project Module (Releasing Soon)";s:1:"u";s:51:"https://ultimatefosters.com/recommends/project-app/";s:1:"d";s:66:"Manage Projects, tasks, tasks time logs, activities and much more.";}}';
}
}

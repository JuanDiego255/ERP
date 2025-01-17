<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use App\Pais;

class Contact extends Authenticatable
{
    use Notifiable;

    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */


    /**
     * Get the business that owns the user.
     */
    public function business()
    {
        return $this->belongsTo(\App\Models\Business::class);
    }

    public function cidade()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }
    public function cidade_entrega()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id_entrega');
    }

    public function scopeActive($query)
    {
        return $query->where('contacts.contact_status', 'active');
    }

    public function scopeOnlySuppliers($query)
    {
        return $query->whereIn('contacts.type', ['supplier', 'both']);
    }

    public function scopeOnlyCustomers($query)
    {
        return $query->whereIn('contacts.type', ['customer', 'both']);
    }
    public function scopeOnlyGuarantor($query)
    {
        return $query->whereIn('contacts.type', ['guarantor', 'both']);
    }

    /**
     * Get all of the contacts's notes & documents.
     */
    public function documentsAndnote()
    {
        return $this->morphMany('App\Models\DocumentAndNote', 'notable');
    }

    /**
     * Return list of contact dropdown for a business
     *
     * @param $business_id int
     * @param $exclude_default = false (boolean)
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function contactDropdown($business_id, $exclude_default = false, $prepend_none = true, $append_id = true)
    {
        $query = Contact::where('business_id', $business_id)
            ->where('type', '!=', 'lead')
            ->active();

        if ($exclude_default) {
            $query->where('is_default', 0);
        }

        if ($append_id) {
            $query->select(
                DB::raw("IF(contact_id IS NULL OR contact_id='', name, CONCAT(name, ' - ', COALESCE(supplier_business_name, ''), '(', contact_id, ')')) AS supplier"),
                'id'
            );
        } else {
            $query->select(
                'id',
                DB::raw("IF (supplier_business_name IS not null, CONCAT(name, ' (', supplier_business_name, ')'), name) as supplier")
            );
        }

        $contacts = $query->pluck('supplier', 'id');

        //Prepend none
        if ($prepend_none) {
            $contacts = $contacts->prepend(__('lang_v1.none'), '');
        }

        return $contacts;
    }
    /**
     * Return list of contact dropdown for a business
     *
     * @param $business_id int
     * @param $exclude_default = false (boolean)
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function contactDropdownCustomer($business_id, $revenue = false, $prepend_none = true, $id = null, $rev_id = null)
    {
        $query = Contact::where('contacts.business_id', $business_id)
            ->where('type', 'customer')
            ->active();

        // Si $revenue es true, agregar los joins con revenues y plan_ventas
        if ($revenue) {
            $query->join('revenues as rev', function ($join) use ($rev_id, $id) {
                $join->on('contacts.id', '=', 'rev.contact_id')
                    ->where('rev.status', 0)
                    ->where('rev.id', '!=', $rev_id);
            })
                ->join('plan_ventas as pv', 'rev.plan_venta_id', '=', 'pv.id');
        }

        // Seleccionar las columnas
        $query->select(
            DB::raw("IF(contacts.contact_id IS NULL OR contacts.contact_id='', 
            name, 
            CONCAT(name, ' (', contacts.contact_id, ') - (#PV ', pv.numero, ')')
        ) AS contact"),
            $revenue ? 'rev.id as rev_id' : DB::raw('NULL as rev_id'), // Seleccionar rev_id si $revenue es true
            'contacts.id as contact_id'
        );

        // Obtener los resultados
        $contacts = $query->get();

        // Convertir a una estructura de datos con contacto y rev_id
        $result = $contacts->mapWithKeys(function ($item) {
            return [$item->contact_id => [
                'contact' => $item->contact,
                'rev_id' => $item->contact_id . '/' . $item->rev_id
            ]];
        });

        // Prepend none
        if ($prepend_none) {
            $result = collect(['' => ['contact' => __('lang_v1.none'), 'rev_id' => null]])->merge($result);
        }

        return $result;
    }



    /**
     * Return list of suppliers dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function suppliersDropdown($business_id, $prepend_none = true, $append_id = true)
    {
        $all_contacts = Contact::where('business_id', $business_id)
            ->whereIn('type', ['supplier', 'both'])
            ->active();

        if ($append_id) {
            $all_contacts->select(
                DB::raw("IF(contact_id IS NULL OR contact_id='', name, CONCAT(name, ' - ', COALESCE(supplier_business_name, ''), '(', contact_id, ')')) AS supplier"),
                'id'
            );
        } else {
            $all_contacts->select(
                'id',
                DB::raw("CONCAT(name, ' (', supplier_business_name, ')') as supplier")
            );
        }

        $suppliers = $all_contacts->pluck('supplier', 'id');

        //Prepend none
        if ($prepend_none) {
            $suppliers = $suppliers->prepend(__('lang_v1.none'), '');
        }

        return $suppliers;
    }

    /**
     * Return list of customers dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function customersDropdown($business_id, $prepend_none = true, $append_id = true)
    {
        $all_contacts = Contact::where('business_id', $business_id)
            ->whereIn('type', ['customer', 'both'])
            ->active();

        if ($append_id) {
            $all_contacts->select(
                DB::raw("IF(contact_id IS NULL OR contact_id='', name, CONCAT(name, ' (', contact_id, ')')) AS customer"),
                'id'
            );
        } else {
            $all_contacts->select('id', DB::raw("name as customer"));
        }

        $customers = $all_contacts->pluck('customer', 'id');

        //Prepend none
        if ($prepend_none) {
            $customers = $customers->prepend(__('lang_v1.none'), '');
        }

        return $customers;
    }
    /**
     * Return list of guarantor dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function guarantorsDropdown($business_id, $prepend_none = true, $append_id = true)
    {
        $all_contacts = Contact::where('business_id', $business_id)
            ->whereIn('type', ['guarantor', 'both'])
            ->active();

        if ($append_id) {
            $all_contacts->select(
                DB::raw("IF(contact_id IS NULL OR contact_id='', name, CONCAT(name, ' - ', COALESCE(guarantor_business_name, ''), '(', contact_id, ')')) AS guarantor"),
                'id'
            );
        } else {
            $all_contacts->select(
                'id',
                DB::raw("CONCAT(name, ' (', guarantor_business_name, ')') as guarantor")
            );
        }

        $guarantors = $all_contacts->pluck('guarantor', 'id');

        // Prepend none
        if ($prepend_none) {
            $guarantors = $guarantors->prepend(__('lang_v1.none'), '');
        }

        return $guarantors;
    }


    /**
     * Return list of contact type.
     *
     * @param $prepend_all = false (boolean)
     * @return array
     */
    public static function typeDropdown($prepend_all = false)
    {
        $types = [];

        if ($prepend_all) {
            $types[''] = __('lang_v1.all');
        }

        $types['customer'] = __('report.customer');
        $types['supplier'] = __('report.supplier');
        $types['guarantor'] = __('report.guarantor');
        $types['both'] = __('lang_v1.both_supplier_customer');

        return $types;
    }

    /**
     * Return list of contact type by permissions.
     *
     * @return array
     */
    public static function getContactTypes()
    {
        $types = [];
        if (auth()->user()->can('supplier.create')) {
            $types['supplier'] = __('report.supplier');
        }
        if (auth()->user()->can('customer.create')) {
            $types['customer'] = __('report.customer');
        }
        if (auth()->user()->can('guarantor.create')) {
            $types['guarantor'] = __('report.guarantor');
        }
        if (auth()->user()->can('supplier.create') && auth()->user()->can('customer.create')) {
            $types['both'] = __('lang_v1.both_supplier_customer');
        }

        return $types;
    }

    public function getContactAddressAttribute()
    {
        $address = $this->city .
            ', ' . $this->landmark . ', ' . $this->state . '<br>' . $this->country;

        return $address;
    }

    public function getPais()
    {
        $pais = Pais::where('codigo', $this->cod_pais)->first();
        return $pais->nome;
    }
}

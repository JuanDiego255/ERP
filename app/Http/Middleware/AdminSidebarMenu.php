<?php

namespace App\Http\Middleware;

use App\Utils\ModuleUtil;
use Closure;
use Menu;

class AdminSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        Menu::create('admin-sidebar-menu', function ($menu) {
            $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            //Home
            $menu->url(action('HomeController@index'), __('home.home'), ['icon' => 'fa fas fa-tachometer-alt', 'active' => request()->segment(1) == 'home'])->order(5);
            //User management dropdown
            if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {
                $menu
                    ->dropdown(
                        __('user.user_management'),
                        function ($sub) {
                            if (auth()->user()->can('user.view')) {
                                $sub->url(action('ManageUserController@index'), __('user.users'), ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'users']);
                            }
                            if (auth()->user()->can('roles.view')) {
                                $sub->url(action('RoleController@index'), 'Controles de acceso', ['icon' => 'fa fas fa-briefcase', 'active' => request()->segment(1) == 'roles']);
                            }
                            /* if (auth()->user()->can('user.create')) {
                            $sub->url(
                                action('SalesCommissionAgentController@index'),
                                __('lang_v1.sales_commission_agents'),
                                ['icon' => 'fa fas fa-handshake', 'active' => request()->segment(1) == 'sales-commission-agents']
                            );
                        } */
                        },
                        ['icon' => 'fa fas fa-users'],
                    )
                    ->order(10);
            }

            //Contacts dropdown
            if (auth()->user()->can('supplier.view') || auth()->user()->can('customer.view')) {
                $menu
                    ->dropdown(
                        __('contact.contacts'),
                        function ($sub) {
                            if (auth()->user()->can('supplier.view')) {
                                $sub->url(action('ContactController@index', ['type' => 'supplier']), __('report.supplier'), ['icon' => 'fa fas fa-star', 'active' => request()->input('type') == 'supplier']);
                            }
                            if (auth()->user()->can('customer.view')) {
                                $sub->url(action('ContactController@index', ['type' => 'customer']), __('report.customer'), ['icon' => 'fa fas fa-star', 'active' => request()->input('type') == 'customer']);
                                $sub->url(action('ContactController@index', ['type' => 'guarantor']), __('report.guarantor'), ['icon' => 'fa fas fa-star', 'active' => request()->input('type') == 'guarantor']);
                                /* $sub->url(
                                action('CustomerGroupController@index'),
                                __('lang_v1.customer_groups'),
                                ['icon' => 'fa fas fa-users', 'active' => request()->segment(1) == 'customer-group']
                            ); */
                            }
                            if (auth()->user()->can('supplier.create') || auth()->user()->can('customer.create') || auth()->user()->can('guarantor.create')) {
                                //$sub->url(action('ContactController@getImportContacts'), __('lang_v1.import_contacts'), ['icon' => 'fa fas fa-download', 'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'import']);
                            }

                            if (!empty(env('GOOGLE_MAP_API_KEY'))) {
                                $sub->url(action('ContactController@contactMap'), __('lang_v1.map'), ['icon' => 'fa fas fa-map-marker-alt', 'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'map']);
                            }

                            /* $sub->url(
                            '/transportadoras',
                            'Transportadoras',
                            ['icon' => 'fa fas fa-truck', 'active' => request()->segment(1) == 'transportadoras' && request()->segment(2) == 'import']
                        ); */
                        },
                        ['icon' => 'fa fas fa-address-book', 'id' => 'tour_step4'],
                    )
                    ->order(15);
            }
            //Nomina dropdown
            if (auth()->user()->can('planilla.view')) {
                $menu
                    ->dropdown(
                        __('Gestión administrativa'),
                        function ($sub) {
                            if (auth()->user()->can('employee.view')) {
                                $sub->url(action('EmployeeController@index'), __('Empleados'), ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'employees']);
                            }/* 
                            if (auth()->user()->can('rubros.view')) {
                                $sub->url(action('RubrosController@index'), __('Rubros planilla'), ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'rubros']);
                            } */
                            $sub->url(action('PlanillaController@indexTipoPlanilla'), __('Tipos de planilla'), ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'tipo-planilla-index']);
                            $sub->url(action('PlanillaController@index'), __('Gestionar planillas'), ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'planilla-index']);
                        },
                        ['icon' => 'fa fas fa-address-book', 'id' => 'tour_step4'],
                    )
                    ->order(15);
            }

            //Products dropdown
            if (auth()->user()->can('product.view') || auth()->user()->can('product.create') || auth()->user()->can('brand.view') || auth()->user()->can('unit.view') || auth()->user()->can('category.view') || auth()->user()->can('brand.create') || auth()->user()->can('unit.create') || auth()->user()->can('category.create')) {
                $menu
                    ->dropdown(
                        __('Vehículos'),
                        function ($sub) {
                            if (auth()->user()->can('product.view')) {
                                $sub->url(action('ProductController@index'), __('Lista de vehículos'), ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'products' && request()->segment(2) == '']);
                            }
                            if (auth()->user()->can('product.create')) {
                                $sub->url(action('ProductController@create'), __('vehiculos.add_product'), ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'products' && request()->segment(2) == 'create']);
                            }
                            /* if (auth()->user()->can('product.view')) {
                            $sub->url(
                                action('LabelsController@show'),
                                __('barcode.print_labels'),
                                ['icon' => 'fa fas fa-barcode', 'active' => request()->segment(1) == 'labels' && request()->segment(2) == 'show']
                            );
                        } */
                            if (auth()->user()->can('product.create')) {
                                /*  $sub->url(
                                action('VariationTemplateController@index'),
                                __('product.variations'),
                                ['icon' => 'fa fas fa-circle', 'active' => request()->segment(1) == 'variation-templates']
                            ); */
                                //$sub->url(action('ImportProductsController@index'), __('vehiculos.import_product'), ['icon' => 'fa fas fa-download', 'active' => request()->segment(1) == 'import-products']);
                            }
                            /* if (auth()->user()->can('product.opening_stock')) {
                            $sub->url(
                                action('ImportOpeningStockController@index'),
                                __('lang_v1.import_opening_stock'),
                                ['icon' => 'fa fas fa-download', 'active' => request()->segment(1) == 'import-opening-stock']
                            );
                        } */
                            /* if (auth()->user()->can('product.create')) {
                            $sub->url(
                                action('SellingPriceGroupController@index'),
                                __('lang_v1.selling_price_group'),
                                ['icon' => 'fa fas fa-circle', 'active' => request()->segment(1) == 'selling-price-group']
                            );
                        } */
                            /*  if (auth()->user()->can('unit.view') || auth()->user()->can('unit.create')) {
                            $sub->url(
                                action('UnitController@index'),
                                'Unidades',
                                ['icon' => 'fa fas fa-balance-scale', 'active' => request()->segment(1) == 'units']
                            );
                        } */


                            if (auth()->user()->can('category.view') || auth()->user()->can('category.create')) {
                                $sub->url(action('TaxonomyController@index') . '?type=product', __('category.categories'), ['icon' => 'fa fas fa-tags', 'active' => request()->segment(1) == 'taxonomies' && request()->get('type') == 'product']);
                            }
                            if (auth()->user()->can('brand.view') || auth()->user()->can('brand.create')) {
                                $sub->url(action('BrandController@index'), __('brand.brands'), ['icon' => 'fa fas fa-gem', 'active' => request()->segment(1) == 'brands']);
                            }

                            /* $sub->url(
                            action('WarrantyController@index'),
                            __('lang_v1.warranties'),
                            ['icon' => 'fa fas fa-shield-alt', 'active' => request()->segment(1) == 'warranties']
                        ); */
                        },
                        ['icon' => 'fa fas fa-cubes', 'id' => 'tour_step5'],
                    )
                    ->order(20);
            }

            //Purchase dropdown
            /* if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update'))) {
                $menu
                    ->dropdown(
                        __('purchase.purchases'),
                        function ($sub) {
                            if (auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase')) {
                                $sub->url(action('PurchaseController@index'), __('purchase.list_purchase'), ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == null]);
                            }
                            if (auth()->user()->can('purchase.create')) {
                                $sub->url(action('PurchaseController@create'), __('purchase.add_purchase'), ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'purchases' && request()->segment(2) == 'create']);
                            }
                            if (auth()->user()->can('purchase.update')) {
                                $sub->url(action('PurchaseReturnController@index'), __('lang_v1.list_purchase_return'), ['icon' => 'fa fas fa-undo', 'active' => request()->segment(1) == 'purchase-return']);
                            }

                            $sub->url('/purchase-xml', 'Importar XML', ['icon' => 'fa fa-file-excel', 'active' => request()->segment(1) == 'purchase-xml']);

                            $sub->url('/devolucao/lista', 'NFe de Devolução', ['icon' => 'fa fas fa-exchange-alt', 'active' => request()->segment(1) == 'purchase-xml']);

                            $sub->url('/manifesto', 'Manifiesto Fiscal', ['icon' => 'fa fas fa-file', 'active' => request()->segment(1) == 'purchase-xml']);
                        },
                        ['icon' => 'fa fas fa-arrow-circle-down', 'id' => 'tour_step6'],
                    )
                    ->order(25);
            } */
            //Sell dropdown
            /*  if (auth()->user()->can('sell.view') || auth()->user()->can('sell.create') || auth()->user()->can('direct_sell.access') || auth()->user()->can('view_own_sell_only')) {
                $menu
                    ->dropdown(
                        'Ventas',

                        function ($sub) use ($enabled_modules) {
                            if (auth()->user()->can('direct_sell.access') || auth()->user()->can('view_own_sell_only')) {
                                $sub->url(action('SellController@index'), __('lang_v1.all_sales'), ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == null]);
                            }
                            if (in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                                $sub->url(action('SellController@create'), __('sale.add_sale'), ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'create']);
                            }
                            if (auth()->user()->can('sell.view')) {
                                $sub->url(action('SellPosController@index'), 'Lista de PDV', ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == null]);
                            }
                            if (auth()->user()->can('sell.create')) {
                                if (in_array('pos_sale', $enabled_modules)) {
                                    $sub->url(action('SellPosController@create'), 'PDV', ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'pos' && request()->segment(2) == 'create']);
                                }
                                $sub->url(action('SellController@getDrafts'), __('lang_v1.list_drafts'), ['icon' => 'fa fas fa-pen-square', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'drafts']);
                                $sub->url(action('SellController@getQuotations'), 'Lista de cotações', ['icon' => 'fa fas fa-pen-square', 'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'quotations']);
                            }

                            if (auth()->user()->can('sell.view')) {
                                $sub->url(action('SellReturnController@index'), __('lang_v1.list_sell_return'), ['icon' => 'fa fas fa-undo', 'active' => request()->segment(1) == 'sell-return' && request()->segment(2) == null]);
                            }

                            if (auth()->user()->can('access_shipping')) {
                                $sub->url(action('SellController@shipments'), __('lang_v1.shipments'), ['icon' => 'fa fas fa-truck', 'active' => request()->segment(1) == 'shipments']);
                            }

                            if (auth()->user()->can('discount.access')) {
                                $sub->url(action('DiscountController@index'), __('lang_v1.discounts'), ['icon' => 'fa fas fa-percent', 'active' => request()->segment(1) == 'discount']);
                            }
                            if (in_array('subscription', $enabled_modules) && auth()->user()->can('direct_sell.access')) {
                                $sub->url(action('SellPosController@listSubscriptions'), __('lang_v1.subscriptions'), ['icon' => 'fa fas fa-recycle', 'active' => request()->segment(1) == 'subscriptions']);
                            }

                            // if (auth()->user()->can('sell.create')) {
                            //     $sub->url(
                            //         action('ImportSalesController@index'),
                            //         __('lang_v1.import_sales'),
                            //         ['icon' => 'fa fas fa-file-import', 'active' => request()->segment(1) == 'sells']
                            //     );
                            // }

                            $sub->url('/nfelista', 'Lista de NFe', ['icon' => 'fa fas fa-tasks', 'active' => request()->segment(1) == 'nfelista' && request()->segment(2) == null]);

                            $sub->url('/nfcelista', 'Lista de NFCe', ['icon' => 'fa fas fa-tasks', 'active' => request()->segment(1) == 'nfcelista' && request()->segment(2) == null]);
                        },
                        ['icon' => 'fa fas fa-arrow-circle-up', 'id' => 'tour_step7'],
                    )
                    ->order(30);
            } */

            /*             if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))) {
                $menu->dropdown(
                    __('lang_v1.stock_transfers'),
                    function ($sub) {
                        if (auth()->user()->can('purchase.view')) {
                            $sub->url(
                                action('StockTransferController@index'),
                                __('lang_v1.list_stock_transfers'),
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $sub->url(
                                action('StockTransferController@create'),
                                __('lang_v1.add_stock_transfer'),
                                ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == 'create']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-random']
                )->order(35);
            }

            //stock adjustment dropdown
            if (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))) {
                $menu->dropdown(
                    __('stock_adjustment.stock_adjustment'),
                    function ($sub) {
                        if (auth()->user()->can('purchase.view')) {
                            $sub->url(
                                action('StockAdjustmentController@index'),
                                __('stock_adjustment.list'),
                                ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == null]
                            );
                        }
                        if (auth()->user()->can('purchase.create')) {
                            $sub->url(
                                action('StockAdjustmentController@create'),
                                __('stock_adjustment.add'),
                                ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == 'create']
                            );
                        }
                    },
                    ['icon' => 'fa fas fa-database']
                )->order(40);
            } */

            //Expense dropdown
            if (auth()->user()->can('cxp.view')) {
                if (in_array('expenses', $enabled_modules) && (auth()->user()->can('expense.access') || auth()->user()->can('view_own_expense'))) {
                    $menu
                        ->dropdown(
                            'Cuentas por pagar',
                            function ($sub) {

                                $sub->url(action('ExpenseController@index'), 'Cuentas por pagar', ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == null]);
                                if (auth()->user()->can('cxp.create')) {
                                    $sub->url(action('ExpenseController@create'), 'Agregar cuenta por pagar', ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == 'create']);
                                }
                            },
                            ['icon' => 'fa fas fa-minus-circle'],
                        )
                        ->order(45);
                }
            }
            $menu
                ->dropdown(
                    'Gestión de ventas',
                    function ($sub) {
                        if (auth()->user()->can('plan_venta.view')) {
                            $sub->url(action('PlanVentaController@index'), 'Plan de ventas', ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'plan-ventas-index' && request()->segment(2) == null]);
                        }
                        if (auth()->user()->can('cxc.view')) {
                            $sub->url(action('RevenueController@index'), 'Cuentas por cobrar', ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'revenues' && request()->segment(2) == null]);
                        }
                    },
                    ['icon' => 'fa fas fa-plus-circle'],
                )
                ->order(45);

            //Accounts dropdown
            if (auth()->user()->can('account.access') && in_array('account', $enabled_modules)) {
                $menu
                    ->dropdown(
                        __('lang_v1.payment_accounts'),
                        function ($sub) {
                            $sub->url(action('AccountController@index'), __('account.list_accounts'), ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account']);
                            $sub->url(action('AccountReportsController@balanceSheet'), __('account.balance_sheet'), ['icon' => 'fa fas fa-book', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet']);
                            $sub->url(action('AccountReportsController@trialBalance'), __('account.trial_balance'), ['icon' => 'fa fas fa-balance-scale', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance']);
                            $sub->url(action('AccountController@cashFlow'), __('lang_v1.cash_flow'), ['icon' => 'fa fas fa-exchange-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow']);
                            $sub->url(action('AccountReportsController@paymentAccountReport'), __('account.payment_account_report'), ['icon' => 'fa fas fa-file-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report']);
                        },
                        ['icon' => 'fa fas fa-money-check-alt'],
                    )
                    ->order(50);
            }

            //Reports dropdown
            if (auth()->user()->can('report.view')) {
                $menu
                    ->dropdown(
                        __('Reportes'),
                        function ($sub) use ($enabled_modules) {
                            $sub->url('expense-report', __('Cuentas Por Pagar'), ['icon' => 'fa fas fa-file-invoice-dollar', 'active' => request()->path() == 'expense-report']);
                            $sub->url(action('ReportController@getProfitLoss'), __('Gastos de vehículos'), ['icon' => 'fa fas fa-file-invoice-dollar', 'active' => request()->segment(2) == 'profit-loss']);
                            if (auth()->user()->can('report.audit')) {
                                $sub->url('audits', __('Auditorias'), ['icon' => 'fa fas fa-file-invoice-dollar', 'active' => request()->path() == 'audits']);
                            }
                        },
                        ['icon' => 'fa fas fa-chart-bar', 'id' => 'tour_step8'],
                    )
                    ->order(55);
            }

            //Backup menu
            // if (auth()->user()->can('backup')) {
            //     $menu->url(action('BackUpController@index'), 'Backup', ['icon' => 'fa fas fa-hdd', 'active' => request()->segment(1) == 'backup'])->order(60);
            // }

            //Modules menu
            // if (auth()->user()->can('manage_modules')) {
            //     $menu->url(action('Install\ModulesController@index'), __('lang_v1.modules'), ['icon' => 'fa fas fa-plug', 'active' => request()->segment(1) == 'manage-modules'])->order(60);
            // }

            //Booking menu
            if (in_array('booking', $enabled_modules) && (auth()->user()->can('crud_all_bookings') || auth()->user()->can('crud_own_bookings'))) {
                $menu->url(action('Restaurant\BookingController@index'), __('restaurant.bookings'), ['icon' => 'fas fa fa-calendar-check', 'active' => request()->segment(1) == 'bookings'])->order(65);
            }

            //Kitchen menu
            if (in_array('kitchen', $enabled_modules)) {
                $menu->url(action('Restaurant\KitchenController@index'), 'Cozinha', ['icon' => 'fa fas fa-fire', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen'])->order(70);
            }

            //Service Staff menu
            if (in_array('service_staff', $enabled_modules)) {
                $menu->url(action('Restaurant\OrderController@index'), __('restaurant.orders'), ['icon' => 'fa fas fa-list-alt', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'orders'])->order(75);
            }

            //Notification template menu
            // if (auth()->user()->can('send_notifications')) {
            //     $menu->url(action('NotificationTemplateController@index'), __('lang_v1.notification_templates'), ['icon' => 'fa fas fa-envelope', 'active' => request()->segment(1) == 'notification-templates'])->order(80);
            // }

            //Settings Dropdown
            if (auth()->user()->can('business_settings.access') || auth()->user()->can('barcode_settings.access') || auth()->user()->can('invoice_settings.access') || auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create') || auth()->user()->can('access_package_subscriptions')) {
                $menu
                    ->dropdown(
                        __('business.settings'),
                        function ($sub) use ($enabled_modules) {
                            if (auth()->user()->can('business_settings.access')) {
                                $sub->url(action('BusinessController@getBusinessSettings'), __('business.business_settings'), ['icon' => 'fa fas fa-cogs', 'active' => request()->segment(1) == 'business', 'id' => 'tour_step2']);

                                // $sub->url(
                                //     '/cities',
                                //     'Cidades',
                                //     ['icon' => 'fa fa-map-signs', 'active' => request()->segment(1) == 'cities', 'id' => "cities"]
                                // );
                                /* $sub->url(action('BusinessLocationController@index'), __('business.business_locations'), ['icon' => 'fa fas fa-map-marker', 'active' => request()->segment(1) == 'business-location']); */
                            }

                            //natureza operacao
                            /* if (auth()->user()->can('access_shipping')) {
                                $sub->url('/naturezas', 'Naturezas de operação', ['icon' => 'fa fas fa-list', 'active' => request()->segment(1) == 'naturezas' && request()->segment(2) == null]);
                            } */

                            $sub->url(action('InvoiceSchemeController@index'), __('Consec. Referencia'), ['icon' => 'fa fas fa-file', 'active' => in_array(request()->segment(1), ['invoice-schemes', 'invoice-layouts'])]);

                            /* if (auth()->user()->can('barcode_settings.access')) {
                                $sub->url(action('BarcodeController@index'), __('barcode.barcode_settings'), ['icon' => 'fa fas fa-barcode', 'active' => request()->segment(1) == 'barcodes']);
                            } */
                            if (auth()->user()->can('access_printers')) {
                                $sub->url(action('PrinterController@index'), 'Impresoras', ['icon' => 'fa fas fa-share-alt', 'active' => request()->segment(1) == 'printers']);
                            }

                            /*  if (auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create')) {
                                $sub->url(action('TaxRateController@index'), __('tax_rate.tax_rates'), ['icon' => 'fa fas fa-bolt', 'active' => request()->segment(1) == 'tax-rates']);
                            } */

                            if (in_array('tables', $enabled_modules) && auth()->user()->can('access_tables')) {
                                $sub->url(action('Restaurant\TableController@index'), __('restaurant.tables'), ['icon' => 'fa fas fa-table', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'tables']);
                            }

                            if (in_array('modifiers', $enabled_modules) && (auth()->user()->can('product.view') || auth()->user()->can('product.create'))) {
                                $sub->url(action('Restaurant\ModifierSetsController@index'), __('restaurant.modifiers'), ['icon' => 'fa fas fa-pizza-slice', 'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'modifiers']);
                            }

                            if (in_array('types_of_service', $enabled_modules) && auth()->user()->can('access_types_of_service')) {
                                $sub->url(action('TypesOfServiceController@index'), 'Tipos de serviço', ['icon' => 'fa fas fa-user-circle', 'active' => request()->segment(1) == 'types-of-service']);
                            }
                        },
                        ['icon' => 'fa fas fa-cog', 'id' => 'tour_step3'],
                    )
                    ->order(85);
            }
        });

        //Add menus from modules
        $moduleUtil = new ModuleUtil();

        $moduleUtil->getModuleData('modifyAdminMenu');

        return $next($request);
    }
}

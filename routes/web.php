<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

include_once('install_r.php');

use App\Http\Controllers\Admin\BillVehicleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Models\Transaction;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['authh', 'language'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/business/register', 'BusinessController@getRegister')->name('business.getRegister');
    Route::post('/business/register', 'BusinessController@postRegister')->name('business.postRegister');
    Route::post('/business/register/check-username', 'BusinessController@postCheckUsername')->name('business.postCheckUsername');
    Route::post('/business/register/check-email', 'BusinessController@postCheckEmail')->name('business.postCheckEmail');

    Route::get('/invoice/{token}', 'SellPosController@showInvoice')
        ->name('show_invoice');
});

Route::get('/payment', 'PaymentController@index')->name('payment.index');
Route::post('/paymentPix', 'PaymentController@paymentPix')->name('payment.pix');
Route::post('/paymentBoleto', 'PaymentController@paymentBoleto')->name('payment.boleto');
Route::post('/paymentCartao', 'PaymentController@paymentCartao')->name('payment.cartao');

Route::get('/payment/finish/{transaction_id}', 'PaymentController@finish')->name('payment.finish');


//Routes for authenticated users only
Route::middleware(['authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin', 'CheckPayment'])->group(function () {

    Route::group(['prefix' => '/naturezas'], function () {
        Route::get('/', 'NaturezaController@index');
        Route::get('/new', 'NaturezaController@new');
        Route::post('/save', 'NaturezaController@save');
        Route::get('/delete/{id}', 'NaturezaController@delete');
        Route::get('/edit/{id}', 'NaturezaController@edit');
        Route::post('/update', 'NaturezaController@update');
    });

    Route::group(['prefix' => '/cities'], function () {
        Route::get('/', 'CityController@index');
        Route::get('/new', 'CityController@new');
        Route::post('/save', 'CityController@save');

        Route::get('/edit/{id}', 'CityController@edit');
        Route::post('/update', 'CityController@update');
    });

    Route::group(['prefix' => '/veiculos'], function () {
        Route::get('/', 'VeiculoController@index');
        Route::get('/new', 'VeiculoController@new');
        Route::post('/save', 'VeiculoController@save');
        Route::get('/delete/{id}', 'VeiculoController@delete');
        Route::get('/edit/{id}', 'VeiculoController@edit');
        Route::post('/update', 'VeiculoController@update');
    });

    Route::group(['prefix' => '/ibpt'], function () {
        Route::get('/', 'IbptController@index');
        Route::get('/new', 'IbptController@new');
        Route::post('/save', 'IbptController@save');
        Route::get('/delete/{id}', 'IbptController@delete');
        Route::get('/list/{id}', 'IbptController@list');
        Route::get('/edit/{id}', 'IbptController@edit');
    });

    Route::group(['prefix' => '/ecommerce'], function () {
        Route::get('/config', 'EcommerceController@config');
        Route::post('/save', 'EcommerceController@save');
    });

    Route::group(['prefix' => '/carrosselEcommerce'], function () {
        Route::get('/', 'CarrosselController@index');
        Route::get('/new', 'CarrosselController@create');
        Route::get('/edit/{id}', 'CarrosselController@edit');
        Route::post('/store', 'CarrosselController@store');
        Route::put('/update/{id}', 'CarrosselController@update');
        Route::delete('/delete/{id}', 'CarrosselController@delete');
    });

    Route::group(['prefix' => '/clienteEcommerce'], function () {
        Route::get('/', 'ClienteEcommerceController@index');
        Route::get('/new', 'ClienteEcommerceController@create');
        Route::get('/edit/{id}', 'ClienteEcommerceController@edit');
        Route::post('/save', 'ClienteEcommerceController@save');
        Route::put('/update/{id}', 'ClienteEcommerceController@update');
        Route::delete('/delete/{id}', 'ClienteEcommerceController@delete');

        Route::get('/pedidos/{id}', 'ClienteEcommerceController@pedidos');
    });

    Route::group(['prefix' => 'enderecosEcommerce'], function () {
        Route::get('/{cliente_id}', 'EnderecoEcommerceController@index');
        Route::get('/edit/{id}', 'EnderecoEcommerceController@edit');
        Route::post('/update', 'EnderecoEcommerceController@update');
    });

    Route::group(['prefix' => '/pedidosEcommerce'], function () {
        Route::get('/', 'PedidoEcommerceController@index');
        Route::get('/ver/{id}', 'PedidoEcommerceController@ver');
        Route::post('/salvarCodigo', 'PedidoEcommerceController@salvarCodigo');
        Route::get('/gerarNFe/{id}', 'PedidoEcommerceController@gerarNFe');
        Route::post('/salvarVenda', 'PedidoEcommerceController@salvarVenda');
        Route::get('/consultarPagamentos', 'PedidoEcommerceController@consultarPagamentos');
    });

    Route::group(['prefix' => '/contatoEcommerce'], function () {
        Route::get('/', 'ContatoController@index');
    });

    Route::group(['prefix' => '/informativoEcommerce'], function () {
        Route::get('/', 'InformativoController@index');
    });

    Route::group(['prefix' => '/freteGratis'], function () {
        Route::get('/', 'CidadeFreteGratisController@index');
        Route::get('/new', 'CidadeFreteGratisController@new');
        Route::post('/save', 'CidadeFreteGratisController@save');
        Route::get('/delete/{id}', 'CidadeFreteGratisController@delete');
        Route::get('/edit/{id}', 'CidadeFreteGratisController@edit');
        Route::post('/update', 'CidadeFreteGratisController@update');
    });

    Route::group(['prefix' => '/cupom'], function () {
        Route::get('/', 'CupomController@index');
        Route::get('/new', 'CupomController@new');
        Route::post('/save', 'CupomController@save');
        Route::get('/delete/{id}', 'CupomController@delete');
        Route::get('/edit/{id}', 'CupomController@edit');
        Route::post('/update', 'CupomController@update');
    });

    Route::group(['prefix' => '/cte'], function () {
        Route::get('/', 'CteController@index');
        Route::get('/new', 'CteController@new');
        Route::post('/save', 'CteController@save');
        Route::get('/delete/{id}', 'CteController@delete');
        Route::get('/edit/{id}', 'CteController@edit');
        Route::get('/gerar/{id}', 'CteController@gerar');
        Route::get('/renderizar/{id}', 'CteController@renderizar');
        Route::get('/gerarXml/{id}', 'CteController@gerarXml');
        Route::post('/update', 'CteController@update');

        Route::post('/transmitir', 'CteController@transmitir');
        Route::get('/imprimirCancelamento/{id}', 'CteController@imprimirCancelamento');
        Route::get('/imprimir/{id}', 'CteController@imprimir');
        Route::get('/ver/{id}', 'CteController@ver');
        Route::get('/baixarXml/{id}', 'CteController@baixarXml');
        Route::get('/baixarXmlCancelado/{id}', 'CteController@baixarXmlCancelado');
        Route::post('/cancelar', 'CteController@cancelar');
        Route::post('/corrigir', 'CteController@corrigir');
        Route::post('/consultar', 'CteController@consultar');

        Route::get('/xmls', 'CteController@xmls');
        Route::get('/filtroXml', 'CteController@filtroXml');

        Route::get('/baixarZipXmlAprovado', 'CteController@baixarZipXmlAprovado');
        Route::get('/baixarZipXmlReprovado', 'CteController@baixarZipXmlReprovado');

        Route::post('/importarXml', 'CteController@importarXml');
    });

    Route::group(['prefix' => '/mdfe'], function () {
        Route::get('/', 'MdfeController@index');
        Route::get('/new', 'MdfeController@new');
        Route::post('/save', 'MdfeController@save');
        Route::post('/update', 'MdfeController@update');
        Route::get('/delete/{id}', 'MdfeController@delete');
        Route::get('/edit/{id}', 'MdfeController@edit');
        Route::get('/gerar/{id}', 'MdfeController@gerar');
        Route::get('/renderizar/{id}', 'MdfeController@renderizar');
        Route::get('/gerarXml/{id}', 'MdfeController@gerarXml');
        Route::post('/update', 'MdfeController@update');

        Route::post('/transmitir', 'MdfeController@transmitir');
        Route::get('/imprimirCancelamento/{id}', 'MdfeController@imprimirCancelamento');
        Route::get('/imprimir/{id}', 'MdfeController@imprimir');
        Route::get('/ver/{id}', 'MdfeController@ver');
        Route::get('/baixarXml/{id}', 'MdfeController@baixarXml');
        Route::get('/baixarXmlCancelado/{id}', 'MdfeController@baixarXmlCancelado');
        Route::post('/cancelar', 'MdfeController@cancelar');
        Route::post('/corrigir', 'MdfeController@corrigir');
        Route::post('/consultar', 'MdfeController@consultar');

        Route::get('/xmls', 'MdfeController@xmls');
        Route::get('/filtroXml', 'MdfeController@filtroXml');

        Route::get('/baixarZipXmlAprovado', 'MdfeController@baixarZipXmlAprovado');
        Route::get('/baixarZipXmlReprovado', 'MdfeController@baixarZipXmlReprovado');


        Route::get('/naoencerrados', 'MdfeController@naoencerrados');
        Route::get('/encerrar/{chave}/{protocolo}/{location_id}', 'MdfeController@encerrar');
    });

    Route::group(['prefix' => '/manifesto'], function () {
        Route::get('/', 'ManifestoController@index');
        Route::get('/byLocation/{location_id}', 'ManifestoController@getByLocation');
        Route::get('/buscarNovosDocumentos', 'ManifestoController@buscarNovosDocumentos');
        Route::get('/getDocumentosNovos', 'ManifestoController@getDocumentosNovos');
        Route::get(
            '/getDocumentosNovosLocation',
            'ManifestoController@getDocumentosNovosLocation'
        );

        Route::get('/manifestar', 'ManifestoController@manifestar');
        Route::get('/imprimirDanfe/{id}', 'ManifestoController@imprimirDanfe');
        Route::get('/download/{id}/{location_id?}', 'ManifestoController@download');
        Route::get('/baixarXml/{id}', 'ManifestoController@baixarXml');
        Route::get('/cadProd', 'ManifestoController@cadProd');
        Route::get('/atribuirEstoque', 'ManifestoController@atribuirEstoque');
        Route::post('/salvarFornecedor', 'ManifestoController@salvarFornecedor');
        Route::post('/salvarFatura', 'ManifestoController@salvarFatura');
    });

    Route::group(['prefix' => '/transportadoras'], function () {
        Route::get('/', 'TransportadoraController@index');
        Route::get('/new', 'TransportadoraController@new');
        Route::post('/save', 'TransportadoraController@save');
        Route::get('/delete/{id}', 'TransportadoraController@delete');
        Route::get('/edit/{id}', 'TransportadoraController@edit');
        Route::post('/update', 'TransportadoraController@update');
    });

    Route::group(['prefix' => '/nfe'], function () {
        Route::get('/novo/{id}', 'NfeController@novo');
        Route::get('/renderizar/{id}', 'NfeController@renderizarDanfe');
        Route::get('/gerarXml/{id}', 'NfeController@gerarXml');
        Route::post('/transmtir', 'NfeController@transmtir');

        Route::get('/ver/{id}', 'NfeController@ver');
        Route::get('/baixarXml/{id}', 'NfeController@baixarXml');
        Route::get('/baixarXmlCancelado/{id}', 'NfeController@baixarXmlCancelado');

        Route::get('/imprimir/{id}', 'NfeController@imprimir');
        Route::get('/imprimirCorrecao/{id}', 'NfeController@imprimirCorrecao');
        Route::get('/imprimirCancelamento/{id}', 'NfeController@imprimirCancelamento');
        Route::post('/cancelar', 'NfeController@cancelar');
        Route::post('/corrigir', 'NfeController@corrigir');
        Route::post('/consultar', 'NfeController@consultar');
        Route::get('/filtro', 'NfeController@filtro');

        Route::get('/baixarZipXmlAprovado', 'NfeController@baixarZipXmlAprovado');
        Route::get('/baixarZipXmlReprovado', 'NfeController@baixarZipXmlReprovado');
        Route::get('/consultaCadastro', 'NfeController@consultaCadastro');

        Route::get('/findCidade', 'NfeController@findCidade');
        Route::get('/enviarEmail/{id}', 'NfeController@enviarEmail');
    });

    Route::group(['prefix' => '/nfelista'], function () {
        Route::get('/', 'NfeController@lista');
    });

    Route::group(['prefix' => '/nfcelista'], function () {
        Route::get('/', 'NfceController@lista');
    });

    Route::group(['prefix' => '/nfce'], function () {
        Route::post('/transmitir', 'NfceController@transmtir');
        Route::get('/gerar/{id}', 'NfceController@gerar');
        Route::get('/gerarXml/{id}', 'NfceController@gerarXml');
        Route::get('/renderizar/{id}', 'NfceController@renderizarDanfce');
        Route::get('/imprimir/{id}', 'NfceController@imprimir');
        Route::get('/imprimirNaoFiscal/{id}', 'NfceController@imprimirNaoFiscal');

        Route::get('/ver/{id}', 'NfceController@ver');
        Route::get('/baixarXml/{id}', 'NfceController@baixarXml');
        Route::post('/cancelar', 'NfceController@cancelar');

        Route::get('/filtro', 'NfceController@filtro');
        Route::post('/consultar', 'NfceController@consultar');

        Route::get('/baixarZipXmlAprovado', 'NfceController@baixarZipXmlAprovado');
        Route::get('/baixarZipXmlReprovado', 'NfceController@baixarZipXmlReprovado');
    });

    Route::group(['prefix' => '/purchase-xml'], function () {
        Route::get('/', 'PurchaseXmlController@index');
        Route::post('/', 'PurchaseXmlController@verXml');
        Route::post('/save', 'PurchaseXmlController@save');
        Route::get('/baixarXml/{id}', 'PurchaseXmlController@baixarXml');
        Route::get('/baixarXmlEntrada/{id}', 'PurchaseXmlController@baixarXmlEntrada');
    });

    Route::group(['prefix' => '/nfeEntrada'], function () {
        Route::get('/novo/{id}', 'NfeEntradaController@novo');
        Route::get('/gerarXml', 'NfeEntradaController@gerarXml');
        Route::get('/renderizarDanfe', 'NfeEntradaController@renderizarDanfe');
        Route::post('/transmitir', 'NfeEntradaController@transmitir');
        Route::get('/imprimir/{id}', 'NfeEntradaController@imprimir');
        Route::get('/ver/{id}', 'NfeEntradaController@ver');
        Route::get('/baixarXml/{id}', 'NfeEntradaController@baixarXml');
        Route::post('/cancelar', 'NfeEntradaController@cancelar');
        Route::get('/imprimirCancelamento/{id}', 'NfeEntradaController@imprimirCancelamento');
    });

    Route::group(['prefix' => '/devolucao'], function () {
        Route::get('/', 'DevolucaoController@index');
        Route::post('/', 'DevolucaoController@verXml');
        Route::get('/lista', 'DevolucaoController@lista');
        Route::post('/save', 'DevolucaoController@save');
        Route::get('/baixarXml/{id}', 'DevolucaoController@baixarXml');
        Route::get('/baixarXmlCancelamento/{id}', 'DevolucaoController@baixarXmlCancelamento');
        Route::get('/filtro', 'DevolucaoController@filtro');
        Route::get('/ver/{id}', 'DevolucaoController@ver');
        Route::get('/renderizar/{id}', 'DevolucaoController@renderizarDanfe');
        Route::get('/gerarXml/{id}', 'DevolucaoController@gerarXml');
        Route::get('/imprimir/{id}', 'DevolucaoController@imprimir');
        Route::get('/imprimirCancelamento/{id}', 'DevolucaoController@imprimirCancelamento');
        Route::get('/imprimirCorrecao/{id}', 'DevolucaoController@imprimirCorrecao');

        Route::get('/delete/{id}', 'DevolucaoController@delete');
        Route::post('/transmitir', 'DevolucaoController@transmitir');
        Route::post('/cancelar', 'DevolucaoController@cancelar');

        Route::post('/corrigir', 'DevolucaoController@corrigir');
    });

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/get-totals', 'HomeController@getTotals');
    Route::get('/home/product-stock-alert', 'HomeController@getProductStockAlert');
    Route::get('/home/purchase-payment-dues', 'HomeController@getPurchasePaymentDues');
    Route::get('/home/sales-payment-dues', 'HomeController@getSalesPaymentDues');

    Route::post('/test-email', 'BusinessController@testEmailConfiguration');
    Route::post('/test-sms', 'BusinessController@testSmsConfiguration');
    Route::get('/business/settings', 'BusinessController@getBusinessSettings')->name('business.getBusinessSettings');
    Route::post('/business/update', 'BusinessController@postBusinessSettings')->name('business.postBusinessSettings')
        ->middleware('csv');
    Route::get('/user/profile', 'UserController@getProfile')->name('user.getProfile');
    Route::post('/user/update', 'UserController@updateProfile')->name('user.updateProfile');
    Route::post('/user/update-password', 'UserController@updatePassword')->name('user.updatePassword');

    Route::resource('brands', 'BrandController');
    Route::resource('bank', 'BankController');

    Route::resource('payment-account', 'PaymentAccountController');

    Route::resource('tax-rates', 'TaxRateController');

    Route::resource('units', 'UnitController');

    Route::get('/contacts/map', 'ContactController@contactMap');
    Route::get('/contacts/update-status/{id}', 'ContactController@updateStatus');
    Route::get('/contacts/stock-report/{supplier_id}', 'ContactController@getSupplierStockReport');
    Route::get('/contacts/ledger', 'ContactController@getLedger');
    Route::post('/contacts/send-ledger', 'ContactController@sendLedger');
    Route::get('/contacts/import', 'ContactController@getImportContacts')->name('contacts.import');
    Route::post('/contacts/import', 'ContactController@postImportContacts');
    Route::post('/contacts/check-contact-id', 'ContactController@checkContactId');
    Route::post('/expense/check-ref_no', 'ExpenseController@checkFacId');
    Route::post('/modal-car/', 'ProductController@store');
    Route::post('/vehicle-update-price/{id}', 'ProductController@updatePrice');
    Route::post('/vehicle-update-state/{id}', 'ProductController@updateState');
    Route::get('/contacts/customers', 'ContactController@getCustomers');
    Route::get('/contacts/guarantor', 'ContactController@getGuarantor');
    Route::get('/employees/vendedor', 'EmployeeController@getVendedores');
    Route::get('/get/vehicles/{type}', 'ProductController@getVehicles');
    Route::resource('contacts', 'ContactController');

    Route::get('taxonomies-ajax-index-page', 'TaxonomyController@getTaxonomyIndexPage');
    Route::resource('taxonomies', 'TaxonomyController');

    Route::resource('variation-templates', 'VariationTemplateController');

    Route::get('/delete-media/{media_id}', 'ProductController@deleteMedia');
    Route::post('/products/mass-deactivate', 'ProductController@massDeactivate');
    Route::get('/products/activate/{id}', 'ProductController@activate');
    Route::get('/products/show-by-item/{type}', 'ProductController@showByItem');
    Route::get('/products/get-by-item/{type}', 'ProductController@getCartsByItem');
    Route::get('/products/galery/{id}', 'ProductController@galery');

    Route::post('/products/galerySave', 'ProductController@galerySave');
    Route::get('/products/galeryDelete/{id}', 'ProductController@galeryDelete');

    Route::get('/products/view-product-group-price/{id}', 'ProductController@viewGroupPrice');
    Route::get('/products/add-selling-prices/{id}', 'ProductController@addSellingPrices');
    Route::post('/products/save-selling-prices', 'ProductController@saveSellingPrices');
    Route::post('/products/mass-delete', 'ProductController@massDestroy');
    Route::get('/products/view/{id}', 'ProductController@view');
    Route::get('/products/list', 'ProductController@getProducts');
    Route::get('/products/list-no-variation', 'ProductController@getProductsWithoutVariations');
    Route::post('/products/bulk-edit', 'ProductController@bulkEdit');
    Route::post('/products/bulk-update', 'ProductController@bulkUpdate');
    Route::post('/products/bulk-update-location', 'ProductController@updateProductLocation');
    Route::get('/products/get-product-to-edit/{product_id}', 'ProductController@getProductToEdit');

    Route::post('/products/get_sub_categories', 'ProductController@getSubCategories');
    Route::get('/products/get_sub_units', 'ProductController@getSubUnits');

    Route::post('/products/product_form_part', 'ProductController@getProductVariationFormPart');
    Route::post('/products/get_product_variation_row', 'ProductController@getProductVariationRow');
    Route::post('/products/get_variation_template', 'ProductController@getVariationTemplate');
    Route::get('/products/get_variation_value_row', 'ProductController@getVariationValueRow');
    Route::post('/products/check_product_sku', 'ProductController@checkProductSku');
    Route::get('/products/quick_add', 'ProductController@quickAdd');
    Route::post('/products/save_quick_product', 'ProductController@saveQuickProduct');
    Route::get('/products/get-combo-product-entry-row', 'ProductController@getComboProductEntryRow');

    Route::resource('products', 'ProductController');

    //Rutas para gastos de vehiculos
    Route::get('/products/bills/{id}/{type}', 'Admin\BillVehicleController@indexBill');
    Route::get('/bill/create/{id}', 'Admin\BillVehicleController@create');
    Route::get('/bill/edit/{id}', 'Admin\BillVehicleController@edit');
    Route::post('/bill/store', 'Admin\BillVehicleController@store');
    Route::put('/bill/update/{id}', 'Admin\BillVehicleController@update');
    Route::delete('/bill/delete/{id}', [BillVehicleController::class, 'destroy'])->name('bill.delete');
    //Rutas para gastos de vehiculos
    Route::post('/expenses/generate-report', [ExpenseController::class, 'generateReport'])->name('expenses.generateReport');
    Route::post('/contacts/generate/customer/excel', [ContactController::class, 'generateReportExc'])->name('contact.generateReport');
    Route::post('/bills/generate-report', [BillVehicleController::class, 'generateReport'])->name('expenses.generateReport');
    Route::post('/expenses/check-update', [ExpenseController::class, 'updateCheckReport'])->name('expenses.check_update');
    Route::post('/expenses/generate-report-detail', [ExpenseController::class, 'generateReportDetail'])->name('expenses.generateReportDetail');
    Route::post('/purchases/update-status', 'PurchaseController@updateStatus');
    Route::get('/purchases/get_products', 'PurchaseController@getProducts');
    Route::get('/purchases/get_suppliers', 'PurchaseController@getSuppliers');
    Route::post('/purchases/get_purchase_entry_row', 'PurchaseController@getPurchaseEntryRow');
    Route::post('/purchases/check_ref_number', 'PurchaseController@checkRefNumber');
    Route::resource('purchases', 'PurchaseController')->except(['show']);

    Route::get('/toggle-subscription/{id}', 'SellPosController@toggleRecurringInvoices');
    Route::post('/sells/pos/get-types-of-service-details', 'SellPosController@getTypesOfServiceDetails');
    Route::get('/sells/subscriptions', 'SellPosController@listSubscriptions');
    Route::get('/sells/duplicate/{id}', 'SellController@duplicateSell');
    Route::get('/sells/drafts', 'SellController@getDrafts');
    Route::get('/sells/quotations', 'SellController@getQuotations');
    Route::get('/sells/draft-dt', 'SellController@getDraftDatables');
    Route::resource('sells', 'SellController')->except(['show']);



    Route::get('/import-sales', 'ImportSalesController@index');
    Route::post('/import-sales/preview', 'ImportSalesController@preview');
    Route::post('/import-sales', 'ImportSalesController@import');
    Route::get('/revert-sale-import/{batch}', 'ImportSalesController@revertSaleImport');

    Route::get('/sells/pos/get_product_row/{variation_id}/{location_id}', 'SellPosController@getProductRow');
    Route::post('/sells/pos/get_payment_row', 'SellPosController@getPaymentRow');
    Route::post('/sells/pos/get-reward-details', 'SellPosController@getRewardDetails');
    Route::get('/sells/pos/get-recent-transactions', 'SellPosController@getRecentTransactions');
    Route::get('/sells/pos/get-product-suggestion', 'SellPosController@getProductSuggestion');
    Route::resource('pos', 'SellPosController');

    Route::resource('roles', 'RoleController');

    Route::resource('users', 'ManageUserController');
    Route::resource('employees', 'EmployeeController');
    Route::post('/rubros/store-employee-rubro', [EmployeeController::class, 'storeRubro'])->name('rubros.store');
    Route::put('/rubros/edit-employee-rubro', [EmployeeController::class, 'updateRubro'])->name('rubros.update');
    Route::post('/rubros/delete-employee-rubro/{id}', [EmployeeController::class, 'destroyRubro'])->name('rubros.delete');
    Route::post('/employee/store-action', [EmployeeController::class, 'storeAction'])->name('rubros.store');



    Route::resource('rubros', 'RubrosController');
    //Rutas para los tipo de planillas
    Route::get('/tipo-planilla-index', 'PlanillaController@indexTipoPlanilla');
    Route::get('/tipo-planilla-create', 'PlanillaController@createTipoPlanilla');
    Route::get('/tipo-planilla-edit/{id}', 'PlanillaController@editTipoPlanilla');
    Route::post('/tipo-planilla-store', 'PlanillaController@storeTipoPlanilla');
    Route::put('/tipo-planilla-update/{id}', 'PlanillaController@updateTipoPlanilla');
    Route::delete('/tipo-planilla-delete/{id}', 'PlanillaController@destroyTipoPlanilla');
    //Rutas para los tipo de planillas
    //Rutas para los tipo de planillas
    Route::get('/planilla-index', 'PlanillaController@index');
    Route::get('/planilla-create', 'PlanillaController@create');    
    Route::post('/planilla-store', 'PlanillaController@store');
    Route::post('/planilla-update/{id}', 'PlanillaController@update');
    Route::post('/planilla-store-detalle/{id}', 'PlanillaController@createPlanillaDetalle');
    Route::delete('/planilla-delete/{id}', 'PlanillaController@destroy');
    Route::post('/planilla-update-approve/{id}', 'PlanillaController@updateApprove');
    //Rutas para los tipo de planillas
    //Rutas para los detalles de planillas
    Route::get('/planilla-detalle-index/{id}', 'PlanillaController@indexDetallePlanilla');
    Route::post('/calc/aguinaldo/{id}/{emp_id}', 'PlanillaController@aguinaldoCalc');
    Route::get('/planilla-detalle-view/{id}', 'PlanillaController@viewPayment');
    Route::get('/planilla-send-payments/{id}', 'PlanillaController@sendPaymentsEmail');
    Route::get('/planilla-send-payments-id/{id}', 'PlanillaController@sendPaymentsEmailDetallado');
    Route::post('/planilla-detalle-update/{id}', 'PlanillaController@updatePlanillaDetalle');
    //Rutas para los detalles de planillas
    //Rutas para los planes de ventas
    Route::get('/plan-ventas-index', 'PlanVentaController@index');
    Route::get('/expense-report', 'ExpenseController@index');
    Route::get('/audits', 'ReportController@indexAudit');
    Route::get('/plan-ventas-create', 'PlanVentaController@create');
    Route::get('/plan-ventas-edit/{id}', 'PlanVentaController@edit');
    Route::post('/plan-ventas-store', 'PlanVentaController@store');
    Route::get('/plan-ventas-view/{id}', 'PlanVentaController@viewPlan');
    Route::put('/plan-ventas-update/{id}', 'PlanVentaController@update');
    Route::delete('/plan-ventas-delete/{id}', 'PlanVentaController@destroy');
    Route::get('/report-destroy-audit/', 'ReportController@destroyAudit');
    Route::get('/payments/revenues/{id}/{rev_id}', 'RevenueController@receive');
    Route::post('/payment-revenue-update/{id}/{revenue_id}', 'RevenueController@updatePayment');
    Route::post('/payment-calc-update/{id}/', 'RevenueController@updateCalc');
    Route::post('/payment-add-row/{id}', 'RevenueController@storeRow');
    Route::post('/send-payment-report', 'RevenueController@sendReportToClient');
    Route::delete('/payment-row-delete/{id}', 'RevenueController@destroyRow');
    Route::get('/payment-row-view/{id}/{revenue_id}', 'RevenueController@viewPayment');
    Route::get('/payment-send-whats-id/{id}/{revenue_id}/{type}/{email}', 'RevenueController@sendPaymentsWhatsDetallado');
    //Rutas para los planes de ventas

    Route::resource('group-taxes', 'GroupTaxController');

    Route::get('/barcodes/set_default/{id}', 'BarcodeController@setDefault');
    Route::resource('barcodes', 'BarcodeController');

    //Invoice schemes..
    Route::get('/invoice-schemes/set_default/{id}', 'InvoiceSchemeController@setDefault');
    Route::resource('invoice-schemes', 'InvoiceSchemeController');

    //Print Labels
    Route::get('/labels/show', 'LabelsController@show');
    Route::get('/labels/add-product-row', 'LabelsController@addProductRow');
    Route::get('/labels/preview', 'LabelsController@preview');

    //Reports...
    Route::get('/reports/purchase-report', 'ReportController@purchaseReport');
    Route::get('/reports/sale-report', 'ReportController@saleReport');
    Route::get('/reports/service-staff-report', 'ReportController@getServiceStaffReport');
    Route::get('/reports/service-staff-line-orders', 'ReportController@serviceStaffLineOrders');
    Route::get('/reports/table-report', 'ReportController@getTableReport');
    Route::get('/reports/profit-loss', 'ReportController@getProfitLoss');
    Route::get('/reports/get-opening-stock', 'ReportController@getOpeningStock');
    Route::get('/reports/purchase-sell', 'ReportController@getPurchaseSell');
    Route::get('/reports/customer-supplier', 'ReportController@getCustomerSuppliers');
    Route::get('/reports/stock-report', 'ReportController@getStockReport');
    Route::get('/reports/stock-details', 'ReportController@getStockDetails');
    Route::get('/reports/tax-report', 'ReportController@getTaxReport');
    Route::get('/reports/trending-products', 'ReportController@getTrendingProducts');
    Route::get('/reports/expense-report', 'ReportController@getExpenseReport');
    Route::get('/reports/stock-adjustment-report', 'ReportController@getStockAdjustmentReport');
    Route::get('/reports/register-report', 'ReportController@getRegisterReport');
    Route::get('/reports/sales-representative-report', 'ReportController@getSalesRepresentativeReport');
    Route::get('/reports/sales-representative-total-expense', 'ReportController@getSalesRepresentativeTotalExpense');
    Route::get('/reports/sales-representative-total-sell', 'ReportController@getSalesRepresentativeTotalSell');
    Route::get('/reports/sales-representative-total-commission', 'ReportController@getSalesRepresentativeTotalCommission');
    Route::get('/reports/stock-expiry', 'ReportController@getStockExpiryReport');
    Route::get('/reports/stock-expiry-edit-modal/{purchase_line_id}', 'ReportController@getStockExpiryReportEditModal');
    Route::post('/reports/stock-expiry-update', 'ReportController@updateStockExpiryReport')->name('updateStockExpiryReport');
    Route::get('/reports/customer-group', 'ReportController@getCustomerGroup');
    Route::get('/reports/product-purchase-report', 'ReportController@getproductPurchaseReport');
    Route::get('/reports/product-sell-report', 'ReportController@getproductSellReport');
    Route::get('/reports/product-sell-report-with-purchase', 'ReportController@getproductSellReportWithPurchase');
    Route::get('/reports/product-sell-grouped-report', 'ReportController@getproductSellGroupedReport');
    Route::get('/reports/lot-report', 'ReportController@getLotReport');
    Route::get('/reports/purchase-payment-report', 'ReportController@purchasePaymentReport');
    Route::get('/reports/sell-payment-report', 'ReportController@sellPaymentReport');
    Route::get('/reports/product-stock-details', 'ReportController@productStockDetails');
    Route::get('/reports/adjust-product-stock', 'ReportController@adjustProductStock');
    Route::get('/reports/get-profit/{by?}', 'ReportController@getProfit');
    Route::get('/reports/items-report', 'ReportController@itemsReport');
    Route::get('/reports/get-stock-value', 'ReportController@getStockValue');

    Route::get('business-location/activate-deactivate/{location_id}', 'BusinessLocationController@activateDeactivateLocation');

    //Business Location Settings...
    Route::prefix('business-location/{location_id}')->name('location.')->group(function () {
        Route::get('settings', 'LocationSettingsController@index')->name('settings');
        Route::get('settingsAjax', 'LocationSettingsController@settingsAjax');
        Route::post('settings', 'LocationSettingsController@updateSettings')->name('settings_update');
        Route::post('updateSettingsCertificado', 'LocationSettingsController@updateSettingsCertificado')->name('settings_update_certificado');
    });

    //Business Locations...
    Route::post('business-location/check-location-id', 'BusinessLocationController@checkLocationId');
    Route::resource('business-location', 'BusinessLocationController');

    //Invoice layouts..
    Route::resource('invoice-layouts', 'InvoiceLayoutController');

    //Expense Categories...
    Route::resource('expense-categories', 'ExpenseCategoryController');

    //Expenses...
    Route::resource('expenses', 'ExpenseController');
    Route::resource('revenues', 'RevenueController');

    Route::get('/revenues/receive/{id}/{rev_id}', 'RevenueController@receive')->name('revenue.receive');
    Route::put('/revenues/{id}/receivePut', 'RevenueController@receivePut')->name('revenue.receivePut');


    //Transaction payments...
    // Route::get('/payments/opening-balance/{contact_id}', 'TransactionPaymentController@getOpeningBalancePayments');
    Route::get('/payments/show-child-payments/{payment_id}', 'TransactionPaymentController@showChildPayments');
    Route::get('/payments/view-payment/{payment_id}', 'TransactionPaymentController@viewPayment');
    Route::get('/payments/add_payment/{transaction_id}', 'TransactionPaymentController@addPayment');
    Route::get('/payments/pay-contact-due/{contact_id}', 'TransactionPaymentController@getPayContactDue');
    Route::post('/payments/pay-contact-due', 'TransactionPaymentController@postPayContactDue');
    Route::resource('payments', 'TransactionPaymentController');

    //Printers...
    Route::resource('printers', 'PrinterController');

    Route::get('/stock-adjustments/remove-expired-stock/{purchase_line_id}', 'StockAdjustmentController@removeExpiredStock');
    Route::post('/stock-adjustments/get_product_row', 'StockAdjustmentController@getProductRow');
    Route::resource('stock-adjustments', 'StockAdjustmentController');

    Route::get('/cash-register/register-details', 'CashRegisterController@getRegisterDetails');
    Route::get('/cash-register/close-register', 'CashRegisterController@getCloseRegister');
    Route::post('/cash-register/close-register', 'CashRegisterController@postCloseRegister');
    Route::resource('cash-register', 'CashRegisterController');

    //Import products
    Route::get('/import-products', 'ImportProductsController@index');
    Route::post('/import-products/store', 'ImportProductsController@store');

    //Sales Commission Agent
    Route::resource('sales-commission-agents', 'SalesCommissionAgentController');

    //Stock Transfer
    Route::get('stock-transfers/print/{id}', 'StockTransferController@printInvoice');
    Route::resource('stock-transfers', 'StockTransferController');

    Route::get('/opening-stock/add/{product_id}', 'OpeningStockController@add');
    Route::post('/opening-stock/save', 'OpeningStockController@save');

    //Customer Groups
    Route::resource('customer-group', 'CustomerGroupController');

    //Import opening stock
    Route::get('/import-opening-stock', 'ImportOpeningStockController@index');
    Route::post('/import-opening-stock/store', 'ImportOpeningStockController@store');

    //Sell return
    Route::resource('sell-return', 'SellReturnController');
    Route::get('sell-return/get-product-row', 'SellReturnController@getProductRow');
    Route::get('/sell-return/print/{id}', 'SellReturnController@printInvoice');
    Route::get('/sell-return/add/{id}', 'SellReturnController@add');

    //Backup
    Route::get('backup/download/{file_name}', 'BackUpController@download');
    Route::get('backup/delete/{file_name}', 'BackUpController@delete');
    Route::resource('backup', 'BackUpController', ['only' => [
        'index',
        'create',
        'store'
    ]]);

    Route::get('selling-price-group/activate-deactivate/{id}', 'SellingPriceGroupController@activateDeactivate');
    Route::get('export-selling-price-group', 'SellingPriceGroupController@export');
    Route::post('import-selling-price-group', 'SellingPriceGroupController@import');

    Route::resource('selling-price-group', 'SellingPriceGroupController');

    Route::resource('notification-templates', 'NotificationTemplateController')->only(['index', 'store']);
    Route::get('notification/get-template/{transaction_id}/{template_for}', 'NotificationController@getTemplate');
    Route::post('notification/send', 'NotificationController@send');

    Route::post('/purchase-return/update', 'CombinedPurchaseReturnController@update');
    Route::get('/purchase-return/edit/{id}', 'CombinedPurchaseReturnController@edit');
    Route::post('/purchase-return/save', 'CombinedPurchaseReturnController@save');
    Route::post('/purchase-return/get_product_row', 'CombinedPurchaseReturnController@getProductRow');
    Route::get('/purchase-return/create', 'CombinedPurchaseReturnController@create');
    Route::get('/purchase-return/add/{id}', 'PurchaseReturnController@add');
    Route::resource('/purchase-return', 'PurchaseReturnController', ['except' => ['create']]);

    Route::get('/discount/activate/{id}', 'DiscountController@activate');
    Route::post('/discount/mass-deactivate', 'DiscountController@massDeactivate');
    Route::resource('discount', 'DiscountController');

    Route::group(['prefix' => 'account'], function () {
        Route::resource('/account', 'AccountController');
        Route::get('/fund-transfer/{id}', 'AccountController@getFundTransfer');
        Route::post('/fund-transfer', 'AccountController@postFundTransfer');
        Route::get('/deposit/{id}', 'AccountController@getDeposit');
        Route::post('/deposit', 'AccountController@postDeposit');
        Route::get('/close/{id}', 'AccountController@close');
        Route::get('/activate/{id}', 'AccountController@activate');
        Route::get('/delete-account-transaction/{id}', 'AccountController@destroyAccountTransaction');
        Route::get('/get-account-balance/{id}', 'AccountController@getAccountBalance');
        Route::get('/balance-sheet', 'AccountReportsController@balanceSheet');
        Route::get('/trial-balance', 'AccountReportsController@trialBalance');
        Route::get('/payment-account-report', 'AccountReportsController@paymentAccountReport');
        Route::get('/link-account/{id}', 'AccountReportsController@getLinkAccount');
        Route::post('/link-account', 'AccountReportsController@postLinkAccount');
        Route::get('/cash-flow', 'AccountController@cashFlow');
    });

    Route::resource('account-types', 'AccountTypeController');

    //Restaurant module
    Route::group(['prefix' => 'modules'], function () {
        Route::resource('tables', 'Restaurant\TableController');
        Route::resource('modifiers', 'Restaurant\ModifierSetsController');

        //Map modifier to products
        Route::get('/product-modifiers/{id}/edit', 'Restaurant\ProductModifierSetController@edit');
        Route::post('/product-modifiers/{id}/update', 'Restaurant\ProductModifierSetController@update');
        Route::get('/product-modifiers/product-row/{product_id}', 'Restaurant\ProductModifierSetController@product_row');

        Route::get('/add-selected-modifiers', 'Restaurant\ProductModifierSetController@add_selected_modifiers');

        Route::get('/kitchen', 'Restaurant\KitchenController@index');
        Route::get('/kitchen/mark-as-cooked/{id}', 'Restaurant\KitchenController@markAsCooked');
        Route::post('/refresh-orders-list', 'Restaurant\KitchenController@refreshOrdersList');
        Route::post('/refresh-line-orders-list', 'Restaurant\KitchenController@refreshLineOrdersList');

        Route::get('/orders', 'Restaurant\OrderController@index');
        Route::get('/orders/mark-as-served/{id}', 'Restaurant\OrderController@markAsServed');
        Route::get('/data/get-pos-details', 'Restaurant\DataController@getPosDetails');
        Route::get('/orders/mark-line-order-as-served/{id}', 'Restaurant\OrderController@markLineOrderAsServed');
    });

    Route::get('bookings/get-todays-bookings', 'Restaurant\BookingController@getTodaysBookings');
    Route::resource('bookings', 'Restaurant\BookingController');

    Route::resource('types-of-service', 'TypesOfServiceController');
    Route::get('sells/edit-shipping/{id}', 'SellController@editShipping');
    Route::put('sells/update-shipping/{id}', 'SellController@updateShipping');
    Route::get('shipments', 'SellController@shipments');

    Route::post('upload-module', 'Install\ModulesController@uploadModule');
    Route::get('install-module', 'Install\InstallController@index');
    Route::get('instalSuper', 'Install\ModulesController@instalSuper');
    Route::resource('manage-modules', 'Install\ModulesController')
        ->only(['index', 'destroy', 'update']);
    Route::resource('warranties', 'WarrantyController');

    Route::resource('dashboard-configurator', 'DashboardConfiguratorController')
        ->only(['edit', 'update']);

    //common controller for document & note
    Route::get('get-rubros-employee-page', 'DocumentAndNoteController@getRubrosEmployeePage');
    Route::post('post-document-upload', 'DocumentAndNoteController@postMedia');
    Route::resource('rubros-employee', 'DocumentAndNoteController');
});


Route::middleware(['EcomApi'])->prefix('api/ecom')->group(function () {
    Route::get('products/{id?}', 'ProductController@getProductsApi');
    Route::get('categories', 'CategoryController@getCategoriesApi');
    Route::get('brands', 'BrandController@getBrandsApi');
    Route::post('customers', 'ContactController@postCustomersApi');
    Route::get('settings', 'BusinessController@getEcomSettings');
    Route::get('variations', 'ProductController@getVariationsApi');
    Route::post('orders', 'SellPosController@placeOrdersApi');
});

//common route
Route::middleware(['auth', 'language'])->group(function () {
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});

Route::middleware(['authh', 'auth', 'SetSessionData', 'language', 'timezone'])->group(function () {
    Route::get('/load-more-notifications', 'HomeController@loadMoreNotifications');
    Route::get('/get-total-unread', 'HomeController@getTotalUnreadNotifications');
    Route::get('/purchases/print/{id}', 'PurchaseController@printInvoice');
    Route::get('/purchases/{id}', 'PurchaseController@show');
    Route::get('/sells/{id}', 'SellController@show');
    Route::get('/sells/{transaction_id}/print', 'SellPosController@printInvoice')->name('sell.printInvoice');
    Route::get('/sells/invoice-url/{id}', 'SellPosController@showInvoiceUrl');
});

Route::get('/cidades', 'CidadeController@lista');


Route::get('/source', function () {
    return view('source');
});

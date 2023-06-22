<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin'], function () {
    Route::get('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('logout', [App\Http\Controllers\Dashboard\LogoutController::class, 'logout'])->name('admin.logout');
    Route::get('/Panel_Settings', [App\Http\Controllers\Dashboard\Admin_Panel_SettingController::class, 'index'])->name('settings');
    Route::get('/Panel_Settings_edit/{id}', [App\Http\Controllers\Dashboard\Admin_Panel_SettingController::class, 'edit'])->name('AdminPanelEdit');
    Route::post('/Panel_Settings_update', [App\Http\Controllers\Dashboard\Admin_Panel_SettingController::class, 'update'])->name('AdminPanelupdate');

      /*  start Admin_accounts */
    Route::get('/Admin_accounts/details/{id}', [App\Http\Controllers\Dashboard\admincontroller::class, 'details'])->name('Admin_details');
    Route::post('/Admin_accounts/add_treasury/{id}', [App\Http\Controllers\Dashboard\admincontroller::class, 'Add_treasury_To_Admin'])->name('Admin_accounts.add_treasure_To_Admin');
    Route::resource('/Admin_accounts', App\Http\Controllers\Dashboard\admincontroller::class);

    /*   end Admin_accounts */

    /*   start treasuries */
    Route::get('/Treasuries/index', [App\Http\Controllers\Dashboard\TreasureController::class, 'index'])->name('Treasureies_index');
    Route::get('/Treasuries/create', [App\Http\Controllers\Dashboard\TreasureController::class, 'create'])->name('Treasureies_create');
    Route::post('/Treasuries/store', [App\Http\Controllers\Dashboard\TreasureController::class, 'store'])->name('Treasureies_store');
    Route::get('/Treasuries/edit/{id}', [App\Http\Controllers\Dashboard\TreasureController::class, 'edit'])->name('Treasureies_edit');
    Route::post('/Treasuries/update/{id}', [App\Http\Controllers\Dashboard\TreasureController::class, 'update'])->name('Treasureies_update');
    Route::post('/Treasuries/Ajax_search', [App\Http\Controllers\Dashboard\TreasureController::class, 'Ajax_Search_ByName'])->name('Ajax_search1');
    Route::get('/Treasuries/Details/{id}', [App\Http\Controllers\Dashboard\TreasureController::class, 'Show_Details'])->name('Treasureies_Details');
    Route::get('/Treasuries/delete/{id}', [App\Http\Controllers\Dashboard\TreasureController::class, 'delete_treasuries_delivery'])->name('Treasure_delete');
    Route::get('/add_new_sub_treasure/{id}', [App\Http\Controllers\Dashboard\TreasureController::class, 'add_sub_treasure'])->name('add_sub_treasure');
    Route::post('/Sub_Treasure/store/{id}', [App\Http\Controllers\Dashboard\TreasureController::class, 'Sub_Treasure_store'])->name('sub_treasure_store');
    /*   end treasuries*/

    /*   start treasuries */
    Route::get('/Sales_Materials_Types/index', [App\Http\Controllers\Dashboard\SalesMaterialsTypesController::class, 'index'])->name('SalesMaterialsTypesindex');
    Route::get('/Sales_Materials_Types/create', [App\Http\Controllers\Dashboard\SalesMaterialsTypesController::class, 'create'])->name('Sales_Materials_Types_create');
    Route::post('/Sales_Materials_Types/store', [App\Http\Controllers\Dashboard\SalesMaterialsTypesController::class, 'store'])->name('Sales_Materials_Types_store');
    Route::get('/Sales_Materials_Types/edit/{id}', [App\Http\Controllers\Dashboard\SalesMaterialsTypesController::class, 'edit'])->name('Sales_Materials_Types_edit');
    Route::post('/Sales_Materials_Types/update/{id}', [App\Http\Controllers\Dashboard\SalesMaterialsTypesController::class, 'update'])->name('Sales_Materials_Types_update');
    Route::get('/Sales_Materials_Types/delete/{id}', [App\Http\Controllers\Dashboard\SalesMaterialsTypesController::class, 'delete'])->name('Sales_Materials_Types_delete');
    /*   end treasuries*/

    /*   start Storse */
    Route::get('/Stores/index', [App\Http\Controllers\Dashboard\StoreController::class, 'index'])->name('admin.stores.index');
    Route::get('/Stores/create', [App\Http\Controllers\Dashboard\StoreController::class, 'create'])->name('admin.stores.create');
    Route::post('/Stores/store', [App\Http\Controllers\Dashboard\StoreController::class, 'store'])->name('admin.stores.store');
    Route::get('/Stores/edit/{id}', [App\Http\Controllers\Dashboard\StoreController::class, 'edit'])->name('admin.stores.edit');
    Route::post('/Stores/update/{id}', [App\Http\Controllers\Dashboard\StoreController::class, 'update'])->name('admin.stores.update');
    Route::get('/Stores/delete/{id}', [App\Http\Controllers\Dashboard\StoreController::class, 'delete'])->name('admin.stores.delete');
    /*   end Stores*/

    /*   start Uoms */
    Route::get('/Measures_Units/index', [App\Http\Controllers\Dashboard\UomsController::class, 'index'])->name('admin.uoms.index');
    Route::get('/Measures_Units/create', [App\Http\Controllers\Dashboard\UomsController::class, 'create'])->name('admin.uoms.create');
    Route::post('/Measures_Units/store', [App\Http\Controllers\Dashboard\UomsController::class, 'store'])->name('admin.uoms.store');
    Route::get('/Measures_Units/edit/{id}', [App\Http\Controllers\Dashboard\UomsController::class, 'edit'])->name('admin.uoms.edit');
    Route::post('/Measures_Units/update/{id}', [App\Http\Controllers\Dashboard\UomsController::class, 'update'])->name('admin.uoms.update');
    Route::get('/Measures_Units/delete/{id}', [App\Http\Controllers\Dashboard\UomsController::class, 'delete'])->name('admin.uoms.delete');
    Route::POST('/Measures_Units/Ajax_search', [App\Http\Controllers\Dashboard\UomsController::class, 'ajax_search'])->name('admin.uoms.ajax_search');
    /*   end Uoms*/


    /*   start itemcard categories */
    Route::post('/ItemCard_Categories/search', [App\Http\Controllers\Dashboard\Inv_itemcard_categories::class, 'Ajax_Search_ByName'])->name('Ajax_search2');
    Route::get('/ItemCard_Categories/delete/{id}', [App\Http\Controllers\Dashboard\Inv_itemcard_categories::class, 'delete'])->name('admin.itemcard_categories.delete');
    Route::resource('/ItemCard_Categories', App\Http\Controllers\Dashboard\Inv_itemcard_categories::class);

    /*   end itemcard categories */


    /*   start itemcard */   
    Route::post('/ItemCard/search', [App\Http\Controllers\Dashboard\ItemcardController::class, 'search'])->name('item_card_Ajax_search');
    Route::get('/ItemCard/delete/{id}', [App\Http\Controllers\Dashboard\ItemcardController::class, 'delete'])->name('admin.itemcard_categories.delete');
    Route::resource('/ItemCard', App\Http\Controllers\Dashboard\ItemcardController::class);

    /*   end itemcard categories */

        /*   start accounts_types */   

    Route::get('/Accounts_types/index', [App\Http\Controllers\Dashboard\Accounts_Types::class, 'index'])->name('admin.accountTypes.index');

    /*   end accounts_types */

        /*   start customer */ 
        Route::get('/Customer/delete/{id}', [App\Http\Controllers\Dashboard\CustomerController::class, 'delete'])->name('customer.delete');  
        Route::post('/Customer/search', [App\Http\Controllers\Dashboard\CustomerController::class,'search'])->name('Customer_Ajax_search');
        Route::resource('/Customer',App\Http\Controllers\Dashboard\CustomerController::class);

        /*   end customer */

        /*   start SuppliersCategories */ 
        Route::get('/SuppliersCategories/delete/{id}', [App\Http\Controllers\Dashboard\SupplierCategoryController::class, 'delete'])->name('Suppliers_Categories.delete');  

        Route::resource('/SuppliersCategories',App\Http\Controllers\Dashboard\SupplierCategoryController::class);
        /*   end SuppliersCategories */

        /*   start suppliers */ 
        Route::get('/Suppliers/delete/{id}', [App\Http\Controllers\Dashboard\SupplierController::class, 'delete'])->name('Suppliers.delete');  
        Route::post('/Suppliers/search', [App\Http\Controllers\Dashboard\SupplierController::class,'search'])->name('supplier_ajax_search');
        Route::resource('/Suppliers',App\Http\Controllers\Dashboard\SupplierController::class);
        /*   end suppliers */
        /*   start Supplier_with_orders  المشتريات*/  
        Route::post('/Supplier_with_orders/get_item_uoms', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'get_item_uoms'])->name('Supplier_with_orders.get_uom');
        Route::post('/Supplier_with_orders/load_modal_add_details', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'load_modal_add_details'])->name('Supplier_with_orders_load_modal_add_details');
        Route::post('/Supplier_with_orders/add_new_details', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'add_new_details'])->name('Supplier_with_orders.add_new_details');
        Route::post('/Supplier_with_orders/reload_items', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'reload_items'])->name('Supplier_with_orders_reload_items');
        Route::post('/Supplier_with_orders/reload_bill', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'reload_parent_bill'])->name('Supplier_with_orders_reload_parent_bill');
        Route::post('/Supplier_with_orders/update_bill_items', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'update_bill_items'])->name('Supplier_with_orders_load_edit_item_details');
        Route::get('/Supplier_with_orders/delete/{id}', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'delete_bill'])->name('Supplier_with_orders_delete_bill');
        Route::post('/Supplier_with_orders/edit_item_details', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'edit_item_details'])->name('admin.suppliers_orders.edit_item_details');
        Route::post('/Supplier_with_orders/approve/{auto_serial}', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'do_approve'])->name('admin.suppliers_orders.do_approve');
        Route::post('/Supplier_with_orders/load_modal_approve_invoice', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'load_modal_approve_invoice'])->name('Supplier_with_orders.load_modal_approve_invoice');
        Route::post('/Supplier_with_orders/load_userShift', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'load_userShift'])->name('Supplier_with_orders.load_userShift');
        Route::get('/Supplier_with_orders/delete_details/{id}/{id_parent}', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'delete_details'])->name('Supplier_with_orders_delete_details');
        Route::post('/Supplier_with_orders/search', [App\Http\Controllers\Dashboard\Supplier_with_orderController::class,'search'])->name('suppliers_orders_Ajax_search');
        Route::resource('/Supplier_with_orders',App\Http\Controllers\Dashboard\Supplier_with_orderController::class);
        /*   end Supplier_with_orders */

     /*  start sales */
     Route::post('/Sales/get_item_uoms', [App\Http\Controllers\Dashboard\SalesController::class,'get_item_uoms'])->name('Sales_get_uom');
     Route::post('/Sales/get_item_batches', [App\Http\Controllers\Dashboard\SalesController::class,'get_item_batches'])->name('get_item_batches');
     Route::post('/Sales/add_new_item_row', [App\Http\Controllers\Dashboard\SalesController::class,'add_new_item_row'])->name('add_new_item_row');        
     Route::post('/Sales/get_item_unit_price', [App\Http\Controllers\Dashboard\SalesController::class,'get_item_unit_price'])->name('get_item_unit_price');

     Route::resource('/Sales',App\Http\Controllers\Dashboard\SalesController::class);


     /* end sales * /
        
       /*   start accounts */
       Route::get('/Accounts/index', [App\Http\Controllers\Dashboard\AccountController::class, 'index'])->name('admin.accounts.index');
       Route::get('/Accounts/create', [App\Http\Controllers\Dashboard\AccountController::class, 'create'])->name('admin.account.create');
       Route::post('/Accounts/store', [App\Http\Controllers\Dashboard\AccountController::class, 'store'])->name('admin.accounts.store');
       Route::get('/Accounts/edit/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'edit'])->name('admin.accounts.edit');
       Route::get('/Accounts/show/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'show'])->name('admin.accounts.show');
       Route::post('/Accounts/update/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'update'])->name('admin.accounts.update');
       Route::get('/Accounts/delete/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'delete'])->name('admin.accounts.delete');
       Route::POST('/Accounts/Ajax_search', [App\Http\Controllers\Dashboard\AccountController::class, 'ajax_search'])->name('admin.accounts.ajax_search');
       /*   end accounts*/


       /* start Admin shifts */
       Route::resource('/shifts',App\Http\Controllers\Dashboard\Admin_shiftsController::class);
       /* end Admin shifts*/


        /* start treasuries_transactions*/
        Route::resource('/collect_transaction',App\Http\Controllers\Dashboard\CollectController::class);
        /* endtreasuries_transactions*/


         /* start treasuries_transactions*/
         Route::resource('/Exchange_transaction',App\Http\Controllers\Dashboard\ExchangeController::class);
         /* endtreasuries_transactions*/

 

});
Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin', 'prefix' => 'admin'], function () {
    Route::get('/login', [App\Http\Controllers\Dashboard\LoginController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\Dashboard\LoginController::class, 'postlogin'])->name('postlogin');

});
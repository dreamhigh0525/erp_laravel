<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('country','ResCountryController@index');
Route::get('country','ResCountryController@index');
Route::get('state','ResCountryStatesController@index');
Route::get('state/search','ResCountryStatesController@search');
Route::get('currency','ResCurrencyController@index');
Route::get('industry','ResPartnerIndustryController@index');
Route::get('lang','ResLangController@index');
Route::get('tz','ResTimeZoneController@index');
Route::get('company','ResCompaniesController@companies');
Route::post('/leave', 'ManageSalaryController@getcount');
Route::post('employee/search', 'HrEmployeesController@searchapi');
Route::get('employee/list', 'HrEmployeesController@fetchEmployees');

// ==== Inventory ==== 
Route::group(['namespace' => '\App\Addons\Inventory\Controllers'], function()
{
    Route::get('Products','ProductController@Products');
    Route::post('Product/store','ProductController@store');
    Route::post('Product/update','ProductController@update');
    Route::get('Products/sale','ProductController@ProductSale');
    Route::get('getProduct','ProductController@getProduct');
    Route::get('getProduct/id','ProductController@getProductById');

    Route::get('Products/category','CategoryController@fetchCategory');
    Route::post('Product/category/store','CategoryController@store');
    Route::post('Product/category/update','CategoryController@update');
    Route::get('Product/Category/{id}','CategoryController@getCategory');

    Route::get('warehouse','ProductWarehouseController@FetchWarehouse');
    Route::get('warehouse/{id}','ProductWarehouseController@getWarehouse');
    Route::post('warehouse/store','ProductWarehouseController@store');
    Route::post('warehouse/update','ProductWarehouseController@update');

    Route::get('Removal/Strategy','ProductRemovalController@fetchRemovalStrategy');
    
});

Route::get('/chart', 'HomeController@getChart');

// ==== Sales ====
Route::group(['namespace' => '\App\Addons\Accounting\Controllers'], function()
{
    Route::get('/fetchaccount/accounts', 'AccountAccountController@fetchAccountAccounts');
    Route::get('/fetchaccount/journal', 'AccountJournalController@fetchAccountJournals');
});
// ==== Sales ====
Route::group(['namespace' => '\App\Addons\Sales\Controllers'], function()
{
    Route::get('/sale/list', 'SalesOrdersController@fetchSalesOrder');
});
// ==== Contact ====
Route::group(['namespace' => '\App\Addons\Contact\Controllers'], function()
{
    Route::post('/customer/list', 'ResCustomersController@fetchCustomer');
    Route::post('/customer/company/list', 'ResCustomersController@fetchCompany');
    Route::post('/customer/search/{id}', 'ResCustomersController@searchapi');
    Route::post('/partner/search', 'ResPartnersController@searchapi');
});

// ==== UOM ====
Route::group(['namespace' => '\App\Addons\Uom\Controllers'], function()
{
    Route::get('/uom/list', 'UomController@fetchUom');
    Route::get('/uom/category/list','UomController@fetchUomCategory');
    Route::get('/uom/type/list','UomController@fetchUomType');
    Route::get('/uom/get_uom/{id}','UomController@getUom');
    Route::get('/uom/get_uom/category/{id}','UomController@fetchUomByCategory');
    Route::Post('/uom/store','UomController@store');
    Route::Post('/uom/update','UomController@update');
});

// get Access Rights
Route::get('/user/Access/{id}','UserController@getAccessRight');
Route::get('/user/{id}','UserController@getUser');

// Addons
Route::get('/Addons/Check/{id}','AppsController@check_installed');


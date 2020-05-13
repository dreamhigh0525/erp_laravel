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

Route::get('/', function () {
    return view('index');
});
Route::group(['middleware' => 'auth'], function (){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('ECommerce', 'ECommerceController@index')->name('ECommerce.index');
    // ==== Account Account ====
    Route::get('Account','AccountAccountController@index')->name('account.index');
    Route::get('Account/create','AccountAccountController@create')->name('account.create');
    Route::post('Account/store','AccountAccountController@store')->name('account.store');
    Route::get('Account/edit/{id}','AccountAccountController@edit')->name('account.edit');
    Route::put('Account/update','AccountAccountController@update')->name('account.update');
    Route::get('Account/destroy/{id}','AccountAccountController@destroy')->name('account.destroy');
    Route::get('Account/filter','AccountAccountController@search')->name('account.filter');

    // ==== Account Journal ====
    Route::get('Account/Journal','AccountJournalController@index')->name('journal.index');
    Route::get('Account/Journal/create','AccountJournalController@create')->name('journal.create');
    Route::post('Account/Journal/store','AccountJournalController@store')->name('journal.store');
    Route::get('Account/Journal/edit/{id}','AccountJournalController@edit')->name('journal.edit');
    Route::put('Account/Journal/update/{id}','AccountJournalController@update')->name('journal.update');
    Route::get('Account/Journal/destroy/{id}','AccountJournalController@destroy')->name('journal.destroy');
    Route::get('Account/Journal/filter','AccountJournalController@search')->name('journal.filter');
    
    // ==== Attendance ====
    Route::post('/checkin{id}', 'HrAttendanceController@store')->name('checkin');
    Route::post('/checkout{id}', 'HrAttendanceController@update')->name('checkout');
    Route::get('/attendance', 'HrAttendanceController@index')->name('attendance');
    Route::get('/attendance/filter', 'HrAttendanceController@search')->name('attendance.filter');
    
    // ==== Customer ====
    Route::get('/customer','ResCustomersController@index')->name('customer');
    Route::get('/customer/new','ResCustomersController@create')->name('customer.new');
    Route::post('/customer/store','ResCustomersController@store')->name('customer.store');
    Route::get('/customer/show/{res_customer}','ResCustomersController@show')->name('customer.show');
    Route::post('/customer/update','ResCustomersController@update')->name('customer.update');
    Route::get('/customer/destroy/{res_customer}','ResCustomersController@destroy')->name('customer.destroy');
    Route::get('/customer/filter','ResCustomersController@search')->name('customer.filter');
    
    // ==== Customer Payment ====
    Route::get('/CustomerDebt','CustomerDeptController@index')->name('CustomerDebt');
    Route::get('/CustomerDebt/show/{id}','CustomerDeptController@show')->name('CustomerDebt.show');
    Route::get('/CustomerDebt/edit/{id}','CustomerDeptController@edit')->name('CustomerDebt.edit');
    Route::post('/CustomerDebt/update','CustomerDeptController@update')->name('CustomerDebt.update');
    
    // ==== Delivery ====
    Route::get('Delivere', 'DelivereProductController@index')->name('Delivere.index');
    Route::get('Delivere/store/{id}', 'DelivereProductController@store')->name('Delivere.store');
    Route::get('Delivere/show/{id}', 'DelivereProductController@show')->name('Delivere.show');
    Route::get('Delivere/validate/{id}', 'DelivereProductController@validation')->name('Delivere.validate');
    Route::get('Delivere/return/{id}', 'DelivereProductController@return')->name('Delivere.return');
    
    // ==== Employee ====
    Route::get('employee','HrEmployeesController@index')->name('employee');
    Route::get('employee/create','HrEmployeesController@create')->name('employee.create');
    Route::post('employee/store','HrEmployeesController@store')->name('employee.store');
    Route::get('employee/edit/{hr_employee}','HrEmployeesController@edit')->name('employee.edit');
    Route::post('employee/update','HrEmployeesController@update')->name('employee.update');
    Route::get('employee/delete/{id}','HrEmployeesController@destroy')->name('employee.delete');
    Route::get('employee/filter','HrEmployeesController@search')->name('employee.filter');
    
    // ==== HR Department ====
    Route::get('department','HrDepartmentController@index')->name('department');
    Route::get('department/create','HrDepartmentController@create')->name('department.create');
    Route::post('department/store', 'HrDepartmentController@store')->name('department.store');
    Route::get('department/edit/{id}','HrDepartmentController@edit')->name('department.edit');
    Route::post('department/update/{id}','HrDepartmentController@update')->name('department.update');
    Route::get('department/delete/{id}','HrDepartmentController@destroy')->name('department.delete');
    
    // ==== HR Department ====
    Route::get('jobs','HrJobsController@index')->name('jobs');
    Route::get('jobs/create','HrJobsController@create')->name('jobs.create');
    Route::post('jobs/store', 'HrJobsController@store')->name('jobs.store');
    Route::get('jobs/edit/{id}','HrJobsController@edit')->name('jobs.edit');
    Route::post('jobs/update/{id}','HrJobsController@update')->name('jobs.update');
    Route::get('jobs/delete/{id}','HrJobsController@destroy')->name('jobs.delete');
    
    // ==== HR PaySlip ====
    Route::get('payslip','ManageSalaryController@index')->name('payslip');
    Route::get('payslip/payment/{id}','ManageSalaryController@payment')->name('payslip.payment');
    Route::get('payslip/filter','ManageSalaryController@search')->name('payslip.filter');
    Route::get('payslip/create','ManageSalaryController@create')->name('payslip.create');
    Route::post('payslip/store','ManageSalaryController@store')->name('payslip.store');
    
    // ==== Leave ====
    Route::get('leave','LeaveController@index')->name('leave');
    Route::post('leave/store','LeaveController@store')->name('leave.store');
    Route::post('leave/approve/{id}','LeaveController@approve')->name('leave.approve');
    Route::post('leave/paid/{id}','LeaveController@paid')->name('leave.paid');
    Route::get('leave/filter','LeaveController@search')->name('leave.filter');

    // ==== Internal User ====
    Route::get('User/InternalUser','AccessRightsController@internalUser')->name('internaluser.index');
    Route::get('User/InternalUser/Detail/{id}','AccessRightsController@show')->name('internaluser.show');
    Route::post('User/update/{id}','AccessRightsController@update')->name('user_setting.update');
    
    // ==== Invoices ====
    Route::get('invoices', 'InvoiceController@index')->name('invoices');
    Route::get('invoices/create', 'InvoiceController@create')->name('invoices.create');
    Route::post('invoices', 'InvoiceController@store')->name('invoices.store');
    Route::get('invoices/show/{id}', 'InvoiceController@show')->name('invoices.show');
    Route::get('invoices/edit/{id}', 'InvoiceController@edit')->name('invoices.edit');
    Route::put('invoices/update/{id}', 'InvoiceController@update')->name('invoices.update');
    Route::get('invoices/destroy', 'InvoiceController@destroy')->name('invoices.destroy');
    Route::get('invoices/filter', 'InvoiceController@search')->name('invoices.filter');
    Route::get('invoices/print{id}', 'InvoiceController@print_pdf')->name('invoices.print');
    Route::get('invoices/approved/{id}', 'InvoiceController@approved')->name('invoices.approved');
    
    // ==== Purchase =====
    Route::get('partner', 'ResPartnersController@index')->name('partner');
    Route::get('partner/new', 'ResPartnersController@create')->name('partner.new');
    Route::post('partner/store','ResPartnersController@store')->name('partner.store');
    Route::get('partner/show/{res_partner}','ResPartnersController@show')->name('partner.show');
    Route::post('partner/update','ResPartnersController@update')->name('partner.update');
    Route::get('partner/destroy/{res_partner}','ResPartnersController@destroy')->name('partner.destroy');
    Route::get('partner/filter', 'ResPartnersController@search')->name('partner.filter');
    
    
    // ==== PartnerCredit=
    Route::get('/PartnerDebt','PartnerCreditController@index')->name('PartnerDebt');
    Route::get('/PartnerDebt/show/{id}','PartnerCreditController@show')->name('PartnerDebt.show');
    Route::get('/PartnerDebt/edit/{id}','PartnerCreditController@edit')->name('PartnerDebt.edit');
    Route::post('/PartnerDebt/update','PartnerCreditController@update')->name('PartnerDebt.update');
    
    // ==== Portal User ====
    Route::get('User/Portal','AccessRightsController@portal')->name('portal.index');

    // ==== purchases ====
    Route::get('purchases', 'PurchaseController@index')->name('purchases');
    Route::get('purchases/create', 'PurchaseController@create')->name('purchases.create');
    Route::post('purchases', 'PurchaseController@store')->name('purchases.store');
    Route::get('purchases/show{id}', 'PurchaseController@show')->name('purchases.show');
    Route::get('purchases/edit{id}', 'PurchaseController@edit')->name('purchases.edit');
    Route::put('purchases/update/{id}', 'PurchaseController@update')->name('purchases.update');
    Route::get('purchases/destroy', 'PurchaseController@destroy')->name('purchases.destroy');
    Route::get('purchases/filter', 'PurchaseController@search')->name('purchases.filter');
    Route::get('purchases/approved/{id}', 'PurchaseController@approved')->name('purchases.approved');
    Route::get('purchases/print{id}', 'PurchaseController@print_pdf')->name('purchases.print');
    
    // ==== Receipt ====
    Route::get('receipt', 'ReceiptProductController@index')->name('receipt.index');
    Route::get('receipt/store/{id}', 'ReceiptProductController@store')->name('receipt.store');
    Route::get('receipt/show/{id}', 'ReceiptProductController@show')->name('receipt.show');
    Route::get('receipt/validate/{id}', 'ReceiptProductController@validation')->name('receipt.validate');
    Route::get('receipt/return/{id}', 'ReceiptProductController@return')->name('receipt.return');

    // ==== Receivable Account ====
    Route::get('ReceivableAccount','ReceivableAccountController@index')->name('ReceivableAccount.index');
    Route::get('ReceivableAccount/print','ReceivableAccountController@print')->name('ReceivableAccount.Print');

    // ==== Retur Invoice ====
    Route::get('Report/Return/SalesOrder/','ReturnInvoiceController@index')->name('return-invoice.index');
    Route::post('Return/SalesOrder/', 'ReturnInvoiceController@store')->name('return-invoice.store');
    Route::get('Report/Return/SalesOrder/{id}','ReturnInvoiceController@show')->name('return-invoice.show');

    // ==== Retur Purchase ====
    Route::get('Report/Return/Purchase/','ReturnPurchaseController@index')->name('return-po.index');
    Route::post('Return/Purchase/', 'ReturnPurchaseController@store')->name('return-po.store');
    Route::get('Report/Return/Purchase/{id}','ReturnPurchaseController@show')->name('return-po.show');

    // ==== Recruitment ====
    Route::get('recruitment','HrRecruitmentController@index')->name('recruitment');

    // ==== Payable Account ====
    Route::get('PayableAccount','PayableController@index')->name('PayableAccount.index');
    Route::get('PayableAccount/print','PayableController@print')->name('PayableAccount.Print');

    // ==== Profile ====
    Route::get('profile','ProfileController@index')->name('profile');
    Route::post('profile.update','ProfileController@update')->name('profile.update');

    // ==== Product =====
    Route::get('product', 'ProductController@index')->name('product');
    Route::get('product/create', 'ProductController@create')->name('product.create');
    Route::post('product/store', 'ProductController@store')->name('product.store');
    Route::get('product/edit', 'ProductController@edit')->name('product.edit');
    Route::get('product/edit/{id}', 'ProductController@edit')->name('product.edit');
    Route::get('product/filter', 'ProductController@search')->name('product.filter');
    Route::get('product/destroy{id}', 'ProductController@destroy')->name('product.destroy');
    Route::put('product/update', 'ProductController@update')->name('product.update');

    // ==== Product Categories ==== 
    Route::get('/product-categories', 'CategoryController@index')->name('product-categories');
    Route::post('/product-categories/destroy', 'CategoryController@destroy')->name('product-categories.destroy');
    Route::post('/product-categories/store', 'CategoryController@store')->name('product-categories.store');
    Route::get('/product-categories/edit', 'CategoryController@edit')->name('product-categories.edit');
    Route::get('/product-categories/edit{id}', 'CategoryController@edit')->name('product-categories.edit');
    Route::put('/product-categories/update', 'CategoryController@update')->name('product-categories.update');
    
    // ==== Point Of Sale ====
    Route::get('PointOfSale', 'OrderController@index')->name('pos');
    Route::post('PointOfSale/store', 'OrderController@store')->name('pos.store');
    Route::get('PointOfSale/Transaction', 'OrderController@create')->name('pos.create');
    Route::get('PointOfSale/Transaction/View/{id}', 'OrderController@view')->name('pos.view');
    Route::get('PointOfSale/Search', 'OrderController@search')->name('pos.filter');
    Route::get('/checkout', 'OrdrController@checkout')->name('order.checkout');
    Route::post('/checkout', 'OrderController@storeOrder')->name('order.storeOrder');

});  
Auth::routes(['verify' => true]);



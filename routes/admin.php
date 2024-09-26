<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\Axios\AxiosController;
use App\Http\Controllers\Admin\Customer\CustomersController;

Route::namespace('Admin')->prefix('admin')->as('admin.')->middleware(['auth','isInstalled'])->group(function () {

    Route::get('set-lang', 'DashboardController@setLang')->name('set-lang');
    // DASHBOARD
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    // USER
    Route::resource('users', Administration\UsersController::class);
    // ROLE
    Route::resource('roles', Administration\RolesController::class);
    // PERMISSION
    Route::resource('permissions', Administration\PermissionsController::class);

    // staff
    Route::resource('staff', Staff\StaffController::class);
    // staffSalary
    Route::get('/staff/list/staff-salary', 'Staff\StaffController@staffSalaryList')->name('staff.salary.list');
    Route::get('/staff/list/staff-salary/create', 'Staff\StaffController@staffSalaryCreate')->name('staff.salary.create');
    Route::post('/staff/list/staff-salary/store', 'Staff\StaffController@staffSalaryStore')->name('staffSalary.store');
    Route::get('/staff/list/salary/edit/{id}', 'Staff\StaffController@staffSalaryEdit')->name('staff.salary.edit');
    Route::match(['put','patch'],'/staff/list/salary/update/{id}', 'Staff\StaffController@staffSalaryUpdate')->name('staff.salary.update');
    Route::get('/staff/list/staff-salary/getSalary/{id}', 'Staff\StaffController@getStaffSalary')->name('staff.getSalary');
    Route::delete('/staff/list/staff-salary/delete/{id}', 'Staff\StaffController@staffSalaryDestroy')->name('staff.salary.destroy');
    
    // Group
    Route::resource('group', Group\GroupController::class);
    Route::post('group/data/update', 'Group\GroupController@update')->name('group.data.update');
    // Brand
    Route::resource('brand', Brand\BrandController::class);
    Route::post('brand/data/update', 'Brand\BrandController@update')->name('brand.data.update');
    // Type
    Route::resource('type', Type\TypeController::class);
    Route::post('type/data/update', 'Type\TypeController@update')->name('type.data.update');
    // Suplier
    Route::resource('suplier', Suplier\SuplierController::class);
    Route::post('suplier/data/update', 'Suplier\SuplierController@update')->name('suplier.data.update');
    // Medicine Add
    Route::resource('medicine', Medicine\MedicineController::class);

    // account
    Route::get('account/credited', 'Account\AccountController@credited')->name('account.credited');
    Route::post('account/credited-data', 'Account\AccountController@creditedData')->name('account.creditedData');
    Route::get('account/dedited', 'Account\AccountController@debited')->name('account.dedited');
    Route::post('account/dedited-data', 'Account\AccountController@deditedData')->name('account.deditedData');
    Route::get('account/balance', 'Account\AccountController@balance')->name('account.balance');
    // report
    Route::resource('report', Item\ItemController::class);
    Route::get('reports/daywise', 'Report\ReportController@dayWise')->name('reports.daywise');
    Route::post('/reports/data', 'Report\ReportController@getReportData')->name('reports.data');
    Route::get('reports/monthwise', 'Report\ReportController@monthWise')->name('reports.monthwise');
    Route::post('/reports/monnth-data', 'Report\ReportController@getMonthReportData')->name('reports.month.data');

    // category
    Route::resource('category', Category\CategoryController::class);
    // Route::get('category/edit/{id}', 'Category\CategoryController@edit')->name('category.edit');
    Route::post('category/data/update', 'Category\CategoryController@update')->name('category.data.update');
    // order
    Route::resource('order', Order\OrderController::class);
    Route::get('/new/order/create', 'Order\OrderController@newOrderCreate')->name('new.order.create');
    Route::get('/parcel/order/create', 'Order\OrderController@parcelOrderCreate')->name('parcel.order.create');
    Route::get('/online-delivery/order/create', 'Order\OrderController@deliveryOrderCreate')->name('online-delivery.order.create');

    Route::get('manual/order/create', 'Order\OrderController@create')->name('manual.order.create');
    Route::get('order/get/product/{id}', 'Order\OrderController@getProduct')->name('order.get.product');
    Route::get('order/get/product/price/{id}', 'Order\OrderController@getProductPrice')->name('order.get.product.price');
    Route::get('manual/order/get/phone-number', 'Order\OrderController@getPhoneNumber')->name('manual.order.get.phone-number');
    Route::get('manual/order/get/customer-details', 'Order\OrderController@getCustomerDetails')->name('manual.order.get.customer-details');
    Route::get('order/get/item/{id}', 'Order\OrderController@getItem')->name('order.get.item');
    Route::get('order/payment/add/{id}', 'Order\OrderController@paymentAdd')->name('order.payment.add');
    Route::post('order/payment/store', 'Order\OrderController@paymentStore')->name('order.payment.store');
    Route::get('order/payment/history/show/{id}', 'Order\OrderController@paymentHistory')->name('order.paymentHistory.show');

    Route::get('/dashboard/payment/add/{id}', 'Order\OrderController@paymentAdd')->name('dashboard.order.payment.add');

    Route::post('order/status/update', 'Order\OrderController@statusUpdate')->name('order.status.update');
    Route::get('order/status/edit/{id}', 'Order\OrderController@statusEdit')->name('order.status.edit');
    // Route::get('order/show/{id}', 'Order\OrderController@show')->name('order.show');
    Route::get('order/print/{id}', 'Order\OrderController@printData')->name('order.print');
    Route::get('order/kitchen/print/{id}', 'Order\OrderController@kitchenPrintData')->name('order.kitchen.print');
    Route::get('order/due/list', 'Order\OrderController@dueList')->name('order.due-list');
    Route::post('order/item/cancel', 'Order\OrderController@itemCancel')->name('item.cancel');
    
    // CUSTOMER
    Route::resource('customers', Customer\CustomersController::class);
    Route::get('customers/verify/{id}', [CustomersController::class, 'verifyUnverify'])->name('customers.verify');
    Route::get('customers/data/export', [CustomersController::class, 'export'])->name('customers.export');

    // EXPENSES
    Route::resource('expense', Expense\ExpenseController::class);

    // INVOICE
    Route::resource('invoices', 'Invoice\InvoicesController');
    Route::get('invoices/download/{id}', 'Invoice\InvoicesController@download')->name('invoices.download');
    Route::get('invoices/delivered/{id}/{status}', 'Invoice\InvoicesController@deliveryStatusChange')->name('invoices.delivery.status.change');
    Route::post('invoices/payments', 'Invoice\InvoicesController@addPayment')->name('invoices.add_payment');
    Route::get('invoices/payments/{invoice_id}', 'Invoice\InvoicesController@getPayments')->name('invoices.get_payments');
    Route::post('invoices/payments/send', 'Invoice\InvoicesController@sendInvoice')->name('invoices.sendInvoice');
    Route::get('invoices/payments/delete/{id}', 'Invoice\InvoicesController@deletePayment')->name('invoices.delete_payment');
    Route::get('invoices/make-payment/{id}', 'Invoice\InvoicesController@makePayment')->name('invoices.makePayment');
    Route::post('invoices/make-payment/{id}', 'Invoice\InvoicesController@makePaymentPost')->name('invoices.makePaymentPost');
    Route::get('invoices/customer-email/{id}', 'Invoice\InvoicesController@invoiceCustomerEmail');
    Route::get('invoices-print/{id}', 'Invoice\InvoicesController@print')->name('invoice.print');

    // SYSTEM SETTINGS
    Route::get('system-settings', 'Settings\SystemSettingsController@edit')->name('system-settings.edit');
    Route::post('system-settings', 'Settings\SystemSettingsController@update')->name('system-settings.update');

    // PROFILE
    Route::get('profile', 'Administration\UsersController@profile')->name('user.profile');
    Route::put('profile/{profile}', 'Administration\UsersController@updateProfile')->name('user.profile.update');

});

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
Route::get('terms', 'WelcomeController@terms');
Route::get('privacy', 'WelcomeController@privacy');
Route::get('createWebPage', 'WelcomeController@createWeb');
Route::post('wc', 'WelcomeController@wc');
Route::get('voice/{resource}', 'WelcomeController@voiceReminder');

Route::get('home', 'HomeController@index');

Route::get('editProfile', 'UserController@edit');
Route::get('editFiscalInfo', 'UserController@editFiscal');
Route::get('editFile', 'UserController@editFiles');
Route::get('changePassword', 'UserController@editPwd');
Route::patch('updateProfile', 'UserController@update');
Route::patch('updateFiscal', 'UserController@updateFiscal');
Route::patch('updateFiles', 'UserController@updateFiles');
Route::patch('users', 'UserController@updatePwd');
Route::get('successPassword', 'UserController@confirm');

Route::post('submitQuiz', 'ClientController@saveQuiz');
Route::resource('clients', 'ClientController');

Route::get('invoices/showFormats', 'InvoiceController@showFormats');
Route::get('invoices/createReturn', 'InvoiceController@createReturn');
Route::post('invoices/updateFormat', 'InvoiceController@updateFormat');
Route::post('invoices/create', 'InvoiceController@create');
Route::post('invoices/addAddenda', 'InvoiceController@addAddenda');
Route::post('invoices/storeaddenda', 'InvoiceController@storeAddenda');
Route::post('invoices/storeReturn', 'InvoiceController@storeReturn');
Route::resource('invoices', 'InvoiceController');
Route::get('invoices/{id}/cancel', 'InvoiceController@cancel');
Route::get('invoices/{id}/printCancelXML', 'InvoiceController@printCancelXML');
Route::get('invoices/{id}/printCancelPDF', 'InvoiceController@printCancelPDF');
Route::get('invoices/{id}/printXML', 'InvoiceController@printXML');
Route::get('invoices/{id}/printPDF', 'InvoiceController@printPDF');
Route::get('invoices/{id}/addenda', 'InvoiceController@createAddenda');

Route::resource('articles', 'ArticleController');


Route::get('reminders/birthday', 'ReminderController@createBirthday');
Route::post('reminders/storeBirthday', 'ReminderController@storeBirthdayReminder');

Route::get('reminders/open', 'ReminderController@createOpen');
Route::post('reminders/storeOpen', 'ReminderController@storeOpen');

Route::get('reminders/voice', 'ReminderController@createVoice');
Route::post('reminders/storeVoice', 'ReminderController@storeVoiceReminder');

Route::resource('reminders', 'ReminderController');
Route::get('reminders/{id}/cancel', 'ReminderController@cancel');

Route::get('shop/buyinvoice','BuysController@buyInvoice');
Route::get('shop/show','BuysController@showBuys');
Route::post('shop/manageInvoice','BuysController@manageInvoiceBuy');
Route::post('shop/manageReminder','BuysController@manageReminderBuy');
Route::get('shop/{id}/invoice','BuysController@createInvoice');
Route::get('shop/{id}/printxml','BuysController@printXML');
Route::get('shop/{id}/printpdf','BuysController@printPDF');
Route::post('shop/buyFormat','BuysController@buyFormats');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

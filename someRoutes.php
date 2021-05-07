<?php
Route::group(['middleware' => ['auth']], function () {
   
  
   Route::get('/reg', 'HomeController@reg')->name('reg');
 
   Route::group(['prefix' => 'user'], function(){
     Route::get('/index', 'UserController@index')->name('user.index');
       Route::post('/store', 'UserController@store')->name('user.store');
       Route::post('/storeCN', 'UserController@storeCN')->name('user.storeCN');
       Route::post('/storeLogo', 'UserController@storeLogo')->name('user.storeLogo');
       Route::get('/adduserinfo', 'UserController@adduserinfo')->name('user.adduserinfo');
       Route::post('/storeEmail', 'UserController@storeEmail')->name('user.storeEmail');
       Route::post('/deleteContact/{Contact_person}', 'UserController@deleteContact')->name('user.deleteContact');
       Route::post('/deleteUrl/{User_url}', 'UserController@deleteUrl')->name('user.deleteUrl');
       Route::post('/deleteSoc/{Facebook}', 'UserController@deleteSoc')->name('user.deleteSoc');
       Route::post('/storeUrl', 'UserController@storeUrl')->name('user.storeUrl');
       Route::post('/storeFb', 'UserController@storeSocial')->name('user.storeSoc');
       Route::post('/update', 'UserController@update')->name('user.update');
      
       Route::get('/registrations', 'UserController@registrations')->name('user.registrations');
       Route::post('/registrationsLink', 'UserController@registrationsLink')->name('user.registrationsLink');
     });
 
   });
   ?>
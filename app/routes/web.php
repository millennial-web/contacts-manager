<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('import-contacts',       'ContactsController@importContacts');
Route::post('process-contacts',     'ContactsController@processContacts');
Route::post('save-contacts',        'ContactsController@saveContacts');

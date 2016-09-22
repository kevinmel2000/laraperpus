<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Route untuk GuestController
Route::get('/', 'GuestController@index' );

// Route untuk autentikasi
Auth::routes();

// Route untuk Dashboard Login
Route::get('/home', 'HomeController@index');

// Route Group admin
Route::group( [ 'prefix' => 'admin', 'middleware' => [ 'auth', 'role:admin' ] ], function() {
    // Route untuk AuthorsController
    Route::resource( 'authors', 'AuthorsController' );
    // Route untuk BooksController
    Route::resource( 'books', 'BooksController' );
    // Route untuk MembersController
    Route::resource( 'members', 'MembersController' );
    // Route untuk menampilkan daftar peminjam
    Route::get( 'statistics', [
        'as' => 'statistics.index',
        'uses' => 'StatisticsController@index'
    ]);
    // Route untuk export excel
    Route::get( 'export/books', [
        'as' => 'export.books',
        'uses' => 'BooksController@export'
    ]);
    Route::post( 'export/books', [
        'as' => 'export.books.post',
        'uses' => 'BooksController@exportPost'
    ]);
    // Route untuk Import Excel
    Route::get('template/books', [
        'as' => 'template.books',
        'uses' => 'BooksController@generateExcelTemplate'
        ]);
        Route::post('import/books', [
        'as' => 'import.books',
        'uses' => 'BooksController@importExcel'
    ]);
});

// Route untuk melakukan peminjaman oleh member
Route::get( 'books/{book}/borrow', [
    'middleware' => [ 'auth', 'role:member' ],
    'as'         => 'guest.books.borrow',
    'uses'       => 'BooksController@borrow'
]);

// Route untuk melakukan pengembalina oleh member
Route::put( 'book/{book}/return', [
    'middleware' => [ 'auth', 'role:member' ],
    'as'         => 'member.books.return',
    'uses'       => 'BooksController@returnBack'
]);

// Route untuk verifikasi user is_verified
Route::get( 'auth/verify/{token}', 'Auth\RegisterController@verify' );

// Route untuk mengirim ulang verifikasi
Route::get( 'auth/send-verification', 'Auth\RegisterController@sendVerification' );

// Route untuk halaman profil user
Route::get( 'settings/profile', 'SettingsController@profile' );
// Route untuk mengubah data profil
Route::get( 'settings/profile/edit', 'SettingsController@editProfile' );
// Route untuk post data profil
Route::post( 'settings/profile', 'SettingsController@updateProfile' );

// Route untuk ubah password
Route::get( 'settings/password', 'SettingsController@editPassword' );
Route::post( 'settings/password', 'SettingsController@updatePassword' );

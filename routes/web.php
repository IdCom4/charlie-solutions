<?php

use Illuminate\Support\Facades\Route;

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
    return view('home');
});

// Route pour acceder à la liste des matériels
Route::get('materials', 'MaterialsController@list');

// Route pour supprimer un matériel
Route::post('destroy/{filiale}/{id}', 'MaterialsController@destroy');

// Route pour acceder à la page d'édition de matériel
Route::get('edit', 'MaterialsController@edit');

// Route pour envoyer et soumettre les modifications
Route::post('update/{filiale}/{id}', 'MaterialsController@update');



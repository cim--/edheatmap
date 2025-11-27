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

Route::get('/', 'HeatmapController@index');
Route::get('/history', 'HeatmapController@history');

Route::get('/powers', 'PowerController@index')->name('power.index');
Route::get('/powers/hip', 'PowerController@hipIndex')->name('power.hipindex');
Route::post('/powers/control', 'PowerController@control')->name('power.control');
Route::get('/powers/refcard', 'PowerController@refcard')->name('power.refcard');
Route::get('/powers/loose', 'PowerController@looseSystems')->name('power.loose');
Route::get('/powers/dataage', 'PowerController@dataAge')->name('power.dataage');
Route::get('/powers/week/{week}/{power}', 'PowerController@maps')->name('power.week');

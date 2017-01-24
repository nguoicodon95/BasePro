<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group([ 'prefix' => 'dev/crud' ], function () {
    Route::get('generator-builder', 'Dev\\CRUD\\GeneratorBuilderController@builder');
    Route::get('field-template', 'Dev\\CRUD\\GeneratorBuilderController@fieldTemplate');
    Route::post('generator-builder/generate', 'Dev\\CRUD\\GeneratorBuilderController@generate');
    Route::post('generator-builder/column-table', 'Dev\\CRUD\\GeneratorBuilderController@_columns_in_table');
});

Route::resource('admin/categories', 'Admin\\CategoriesController');
Route::resource('admin/articles', 'Admin\\ArticlesController');
Route::resource('admin/post', 'Admin\\PostController');
Route::resource('admin/test', 'Admin\\TestController');
Route::resource('admin/dm', 'Admin\\DmController');
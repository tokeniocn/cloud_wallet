<?php

use App\Http\Controllers\Admin\Module\ModuleController;

// Module Management
Route::group([
    'namespace'  => 'Module',
    'as'         => 'module.',
    'prefix'     => 'modules',
    'middleware' => ['admin', 'role:admin'],
], function () {

    Route::get('/', [ModuleController::class, 'index'])->name('modules');

});

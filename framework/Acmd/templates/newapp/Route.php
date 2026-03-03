<?php

namespace App;

use Atusan\Route\Route;

Route::get('/', modAcceso::class);
Route::get('/login', modAcceso::class);


Route::ajax('/login', modAcceso::class, 'login');

Route::middleware('auth', function () {
  Route::post('/tabs', modTabsMain::class);
  Route::nested('/moduloA', modModuleA::class);

  Route::get('/main', modMain::class);
  Route::post('/main', modMain::class);
  Route::ajax('/main/testing', modMain::class, 'testing');

  Route::ajax('/close', modMain::class, 'close');
}, '/login');

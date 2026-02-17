<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::view('/', 'app');

Route::view('/{any}', 'app')->where('any', '^(?!api).*$');

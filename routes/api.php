<?php

declare(strict_types=1);

use App\Actions\CalculateExpression;
use App\Actions\ClearCalculationHistory;
use App\Actions\DeleteCalculationHistory;
use App\Actions\GetCalculationHistory;
use Illuminate\Support\Facades\Route;

Route::post('/calculate', CalculateExpression::class);
Route::get('/history', GetCalculationHistory::class);
Route::delete('/history/{calculation}', DeleteCalculationHistory::class);
Route::delete('/history', ClearCalculationHistory::class);

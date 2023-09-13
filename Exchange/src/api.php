<?php

use Illuminate\Support\Facades\Route;


Route::get('api/v1/exchange/currency','Exchange\Http\Controllers\CurrencyExchangeController@exchange');
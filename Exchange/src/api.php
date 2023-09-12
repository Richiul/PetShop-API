<?php

use Illuminate\Support\Facades\Route;


Route::get('exchange/currency','Exchange\Http\Controllers\CurrencyExchangeController@exchange');
<?php

namespace Tests;

use Exchange\Http\Controllers\CurrencyExchangeController;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CurrencyExchangeTest extends TestCase
{
    public function testExchangeWithValidRequest()
    {
        $this->withoutExceptionHandling();

        $request = new Request([
            'amount' => 100,
            'currency' => 'RON',
        ]);

        $controller = new CurrencyExchangeController();

        try {
            $response = $controller->exchange($request);
            $this->assertIsFloat($response);
            $this->assertGreaterThanOrEqual(0, $response);
        } catch (ValidationException $e) {
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }
}





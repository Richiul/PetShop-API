<?php

namespace Exchange\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SimpleXMLElement;

class CurrencyExchangeController extends Controller
{
    public function exchange(Request $request)
    {

        $validator = Validator::make(
            $request->toArray(),
            [
                'amount' => ['integer', 'required'],
                'currency' => ['string', 'required']
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $response = Http::get('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

        // Check if the request was successful (status code 200)
        if ($response->ok()) {
            $xmlContent = $response->body();
            // Process the API response data here
            $xml = new SimpleXMLElement($xmlContent);

            $exchangeRates = $xml->Cube->Cube;
            foreach ($exchangeRates->Cube as $CubeCurrency) {
                if ($CubeCurrency['currency'] == $request->currency) {
                    $result = round($request->amount * $CubeCurrency['rate'], 2);
                }
            }
            return $result;
        } else {
            // Handle the case where the API request failed
            return response('API request failed', $response->status());
        }
    }
}

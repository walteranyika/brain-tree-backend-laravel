<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrainyPaymentController extends Controller
{
    //https://www.youtube.com/watch?v=8LQlfkN-Y84
    public function getToken()
    {
        $gateway = new \Braintree_Gateway([
            'environment' => env('BRAIN_ENVIRONMENT'),
            'merchantId' => env('BRAIN_MERCHANT_ID'),
            'publicKey' => env('BRAIN_PUBLIC_KEY'),
            'privateKey' => env('BRAIN_PRIVATE_KEY')
        ]);
        $client_token = $gateway->clientToken()->generate();
        return response()->json(["success" => true, "token" => $client_token]);
    }
    public function checkout(Request $request)
    {
        $nonceFromTheClient = $request->nonce;
        $amount = $request->amount;
        $gateway = new \Braintree_Gateway([
            'environment' => env('BRAIN_ENVIRONMENT'),
            'merchantId' => env('BRAIN_MERCHANT_ID'),
            'publicKey' => env('BRAIN_PUBLIC_KEY'),
            'privateKey' => env('BRAIN_PRIVATE_KEY')
        ]);
        $result = $gateway->transaction()->sale([
            'amount' =>$amount,
            'paymentMethodNonce' => $nonceFromTheClient,
            'options' => [
                'submitForSettlement' => True
            ]
        ]);
        if ($result->success){
            //success
            return response()->json(["success" => true, "message" => "Payment was successful."]);
        }else{
            //failed
            return response()->json(["success" => false, "message" => "Error occurred during payment."]);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class EpsPaymentController extends Controller
{
    private function generateHash($data)
    {
        $hashKey = env('EPS_HASH_KEY');

        return base64_encode(
            hash_hmac('sha512', $data, $hashKey, true)
        );
    }

    private function getToken()
    {
        $username = env('EPS_USERNAME');
        $password = env('EPS_PASSWORD');

        $hash = $this->generateHash($username);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://pgapi.eps.com.bd/v1/Auth/GetToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-hash: ".$hash
            ],
            CURLOPT_POSTFIELDS => json_encode([
                "userName" => $username,
                "password" => $password
            ])
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response,true);
    }

    public function redirectToEPS($order)
    {
        $tokenData = $this->getToken();

        if(!isset($tokenData['token'])){
            dd($tokenData);
        }

        $merchantTransactionId = time().rand(1000,9999);

        $hash = $this->generateHash($merchantTransactionId);
        $customer_info = json_decode($order->shipping_info);

        $body = [
            "merchantId" => env('EPS_MERCHANT_ID'),
            "storeId" => env('EPS_STORE_ID'),
            "CustomerOrderId" => 'ORD-'.time().$order->id,
            "merchantTransactionId" => $merchantTransactionId,
            "transactionTypeId" => 1,
            "financialEntityId" => 0,
            "transitionStatusId" => 0,
            "totalAmount" => $order->total,
            "ipAddress" => request()->ip(),
            "version" => "1",

            "successUrl" => route('eps.success'),
            "failUrl" => route('eps.fail'),
            "cancelUrl" => route('eps.cancel'),

            "customerName" => $customer_info->name,
            "customerEmail" => $customer_info->email ?? null,
            "customerAddress" => $customer_info->address,
            "customerAddress2" => "",
            "customerCity" => "Null",
            "customerState" => "Null",
            "customerPostcode" => "Null",
            "customerCountry" => "BD",
            "customerPhone" => $customer_info->phone,

            "shipmentName" => "",
            "shipmentAddress" => "",
            "shipmentAddress2" => "",
            "shipmentCity" => "",
            "shipmentState" => "",
            "shipmentPostcode" => "",
            "shipmentCountry" => "",

            "valueA" => "",
            "valueB" => "",
            "valueC" => "",
            "valueD" => "",

            "shippingMethod" => $order->get_shipping_method->type,
            "noOfItem" => "1",
            "productName" => "Order Product",
            "productProfile" => "general",
            "productCategory" => "General"
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://pgapi.eps.com.bd/v1/EPSEngine/InitializeEPS",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer ".$tokenData['token'],
                "x-hash: ".$hash
            ],
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($response,true);

        return $result['RedirectURL'];

        return redirect()->away($result['RedirectURL']);

        // return redirect($result['RedirectURL']);
    }
    public function epsSuccess(Request $request)
    {

        $merchantTransactionId = trim($request->get('MerchantTransactionId'));
        $epsTransactionId = $request->get('EPSTransactionId_');

        $tokenData = $this->getToken();

        $hash = $this->generateHash($merchantTransactionId);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://pgapi.eps.com.bd/v1/EPSEngine/CheckMerchantTransactionStatus?merchantTransactionId=".$merchantTransactionId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".$tokenData['token'],
                "x-hash: ".$hash
            ]
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        $verify = json_decode($response,true);


        if($verify['Status']=="Success"){
            $order = Order::find(session('order_id'));
            if ($order) {
                $order->update([
                    'payment_status' => 'success',
                    'is_paid' => 1,
                    'payment_transaction_id' => $epsTransactionId,
                ]);
            }
            return redirect()->route('order.confirmed');
        }

        return "Payment Failed";
    }
    public function epsFail(Request $request)
    {
        $order = Order::find(session('order_id'));
        if($order)
        {
            $order->update([
                'payment_status'=>'failed'
            ]);

            session()->put('order_id',$order->id);
        }

        return redirect()->route('order.confirmed');
    }
    public function epsCancel(Request $request)
    {
        $order = Order::find(session('order_id'));
        if($order)
        {
            $order->update([
                'payment_status'=>'canceled'
            ]);

            session()->put('order_id',$order->id);
        }

        return redirect()->route('order.confirmed');
    }

    public function retryPayment($id)
    {
        $order = Order::findOrFail($id);
        return redirect($this->redirectToEPS($order));
    }

}

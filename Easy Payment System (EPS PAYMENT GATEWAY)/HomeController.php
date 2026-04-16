
    public function orderPlace(Request $request)
    {

        $carts = \Cart::getContent();
        //dd($carts);
        if (count($carts) > 0) {
            //invoice id generate
            $invoice_id = Carbon::now()->format('dmy') . strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5)); //specifier + date + random 5 digit

            //check for duplication, if match then generate new
            $chck_invoice = DB::table('orders')->where('order_no', $invoice_id)->first();
            if ($chck_invoice) {
                $invoice_id = Carbon::now()->format('dmy') . strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5)); //specifier + date + random 5 digit
            }

            $shipping_cost = DB::table('shipping_methods')->where('id', session('shipping_method'))->first()->amount;
            $payment_method = DB::table('payment_methods')->where('id', $request->payment_method_id)->first();
            // dd($payment_method->discount);
            $total = \Cart::getTotal() + $shipping_cost + (session()->has('gift_wrapper') ? 100 : 0);

            if ($payment_method->discount_type == 0) {
                $discount = ($total * $payment_method->discount) / 100;
                $final = $total - $discount;
            } else {
                $discount = $payment_method->discount;
                $final = $total - $discount;
            }

            if ($payment_method->extra_charge_type == 0) {
                $extra = ($total * $payment_method->extra_charge) / 100;
                $final = $final + $extra;
            } else {
                $extra = $payment_method->extra_charge;
                $final = $final + $extra;
            }


            $is_verified = 0;
            if ($request->payment_method_id == 2) { //if bkash then is_verified will be 1
                $is_verified = 1;
            }
            if ($request->payment_method_id == 3) { //if Eps then is_verified will be 1
                $is_verified = 1;
            }

            $inputs = array_merge($request->all(), [
                'order_no' => $invoice_id,
                'order_date' => Carbon::now(),
                'user_id' => $request->user_id,
                'shipping_info' => session('shipping_info'),
                'payment_method' => 'cod',
                'shipping_method' => session('shipping_method'),
                'subtotal' => \Cart::getSubTotal(),
                'shipping_cost' => $shipping_cost,
                'discount' => $discount,
                'payment_extra_charge' => $extra,
                'total' => $final,
                'is_gift_wrapper' => session()->has('gift_wrapper') ? 1 : 0,
                'otp' => rand(1234, 9999),
                'is_verified' => $is_verified,
                'courier_id' => 1,
            ]);

            if (session()->has('gift_info')) {
                $inputs = array_merge($inputs, session()->get('gift_info'));
            }

            $order = Order::create($inputs);

            //create order items
            foreach ($carts as $cart) {
                $product_id = explode('_', $cart->associatedModel->id);
                $product_id = end($product_id);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product_id,
                    'quantity' => $cart->quantity,
                    'unit_price' => $cart->price,
                    'attributes' => count($cart->attributes) > 0 ? $cart->attributes[0] : null,
                ]);

                $product = Product::with('get_product_attributes')->find($product_id);
                if ($product) {
                    $product->update([
                        'total_stock' => $product->total_stock - $cart->quantity
                    ]);
                    if (count($cart->attributes) > 0) {
                        $product_attr = $product->get_product_attributes()->where('variant', $cart->attributes[0])->first();
                        if ($product_attr) {
                            $product_attr->update([
                                'stock' => $product_attr->stock - $cart->quantity
                            ]);
                        }
                    }
                }

            }

            //assign employee
            $employees = Admin::with('get_orders')->where([['role_id', 4], ['status', 1]])->get();
            if (count($employees) > 0) {
                $a = [];
                foreach ($employees as $employee) {
                    $a[$employee->id] = count($employee->get_orders);
                }
                //$a = ['1'/*staff_id*/ => 536/*order_count*/, '2' => 452, '3' => 515, '4' => 452];
                $b = array_keys($a, min($a));
                foreach ($b as $bb) {
                    $c[$bb] = $bb;
                }
                $employee_id = array_rand($c);
                OrderAssign::create([
                    'order_id' => $order->id,
                    'user_id' => $employee_id
                ]);
            }

            Session::forget('shipping_method');
            Session::forget('amount');
            Session::forget('total');
            Session::forget('user_id');
            Session::forget('shipping_info');
            Session::forget('gift_info');
            Session::forget('gift_wrapper');
            Session::forget('is_verified');

            Session::put('order_id', $order->id);

            if (!in_array($request->payment_method_id, [2, 3])) { //if bkash then no need verification and OTP

                $sms_setting = SMSSetting::first();
                if ($sms_setting->is_order_placed == 1) {
                    Session::put('is_verified', $order->is_verified);

                    $mgs_body = strtr($sms_setting->order_placed, [
                        '{$customer_name}' => $order->shipping_info ? json_decode($order->shipping_info)->name : "",
                        '{$otp}' => $order->otp ?? null,
                    ]);

                    $to = json_decode($order->shipping_info)->phone;
                    $token = $sms_setting->sms_api_key;
                    $message = $mgs_body;

                    $url = "http://api.greenweb.com.bd/api.php?json";

                    $data = array(
                        'to' => "$to",
                        'message' => "$message",
                        'token' => "$token"
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $smsresult = curl_exec($ch);

                    // $url = 'https://api.mimsms.com/api/SmsSending/SMS';

                    // // Headers for the request
                    // $headers = [
                    //     'Content-Type: application/json',
                    //     'Accept: application/json',
                    // ];

                    // $mgs_body = strtr($sms_setting->order_placed, [
                    //     '{$customer_name}' => $order->shipping_info ? json_decode($order->shipping_info)->name : "",
                    //     '{$otp}' => $order->otp ?? null,
                    // ]);

                    // $to = ltrim(json_decode($order->shipping_info)->phone, '+88');

                    // // SMS payload
                    // $postData = json_encode([
                    //     "UserName" => "anwar@khapsu.com",
                    //     "Apikey" => '988TJC6SI30KW3R',
                    //     "MobileNumber" => "88" . $to,
                    //     "CampaignId" => "null",
                    //     "SenderName" => '01896050634',
                    //     "TransactionType" => "T",
                    //     "Message" => $mgs_body
                    // ]);

                    // $curl = curl_init();

                    // curl_setopt_array($curl, [
                    //     CURLOPT_URL => $url,
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_POST => true,
                    //     CURLOPT_HTTPHEADER => $headers,
                    //     CURLOPT_POSTFIELDS => $postData,
                    // ]);

                    // $response = curl_exec($curl);
                    // curl_close($curl);
                    // // dd($response);
                }
            }

            //for conversion api
            foreach ($order->get_order_items as $key => $get_product) {
                $order_prod[$key] = [
                    'index' => $key,
                    'item_id' => (string)$get_product->get_product->id,
                    'item_name' => $get_product->get_product->name,
                    'price' => $get_product->get_product->discount_price ? floatval(number_format($get_product->get_product->discount_price, 2, '.', '')) : floatval(number_format($get_product->get_product->price, 2, '.', '')),
                    'quantity' => $get_product->quantity
                ];
            }
            $customer_info = json_decode($order->shipping_info);
            $api_data = [
                'customer_id' => $order->user_id,
                'full_name' => $customer_info->name,
                'phone' => $customer_info->phone,
                'email' => $customer_info->email ?? null,
                'address_summary' => $customer_info->address,
                'invoice_id' => $order->order_no,
                'sub_total' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'products' => json_encode($order_prod)
            ];

            session()->put('api_purchase_data', $api_data);

            return $order;
            //return response()->json(['success' => 'Ik']);
            //return redirect()->route('order.confirmed');
        } else {
            return redirect()->route('cart');
        }
    }

    
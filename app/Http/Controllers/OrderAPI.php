<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class OrderAPI extends Controller
{

    public function orderCreate(Request $request){

        $api_token = $request['api_token'];

        //Customer details
        $phone = $request['phone'];

        //Order details
        $delivery_date = $request['delivery_date'];
        $order_paid = $request['order_paid'];
        $order_amount = $request['order_amount'];
        $order_discount = $request['order_discount'];
        $notes = $request['notes'];

        //Item details
        $item_type = $request['item_type'];
        $shoulder = $request['shoulder'];
        $arm_hole = $request['arm_hole'];
        $chest1 = $request['chest_1'];
        $chest2 = $request['chest_2'];
        $waist = $request['waist'];
        $hip = $request['hip'];
        $slit = $request['slit'];
        $top_length = $request['top_length'];
        $f_neck = $request['f_neck'];
        $b_neck = $request['b_neck'];
        $sleeve_length = $request['sleeve_length'];
        $sleeve_breadth = $request['sleeve_breadth'];
        $sleeve_type = $request['sleeve_type'];
        $hip_size = $request['hip_size'];
        $ankle = $request['ankle'];
        $bottom_length = $request['bottom_length'];
        $bottom_breadth = $request['bottom_breadth'];
        $knee = $request['knee'];
        $thigh = $request['thigh'];
        $description = $request['description'];
        $amount = $request['amount'];

        $tokenCheck = App\User::where('api_token', $api_token)->first();//check if input api_token belongs to the assigned user.

        if ($tokenCheck) {
            $store_fetch = App\User::where('api_token', $api_token)->pluck('store_id');
            $store_id =  $store_fetch[0];

            /**
            Create Orders table
             */
            $order = new App\Order;
            $order->store_id = $store_id;
            $order->delivery_date = $delivery_date;
            $order->order_paid = $order_paid;
            $order->order_amount = $order_amount;
            $order->discount = $order_discount;
            $order->notes = $notes;

            $item = new App\Item;
            $item->item_type = $item_type;
            $item->shoulder = $shoulder;
            $item->arm_hole = $arm_hole;
            $item->chest_1 = $chest1;
            $item->chest_2 = $chest2;
            $item->waist = $waist;
            $item->hip = $hip;
            $item->slit = $slit;
            $item->top_length = $top_length;
            $item->f_neck = $f_neck;
            $item->b_neck = $b_neck;
            $item->sleeve_length = $sleeve_length;
            $item->sleeve_breadth = $sleeve_breadth;
            $item->sleeve_type = $sleeve_type;
            $item->hip_size = $hip_size;
            $item->ankle = $ankle;
            $item->bottom_length = $bottom_length;
            $item->bottom_breadth = $bottom_breadth;
            $item->knee = $knee;
            $item->thigh = $thigh;
            $item->description = $description;
            $item->amount = $amount;

            $customer = App\Customer::where('phone', $phone)->first();

            $customer->orders()->save($order);
            $order->items()->save($item);
            //$customerCheck->stores()->attach($store_id);

            $customer->items()->attach($item['id']);


            $response["verification"] = true;

        } else {
            $response["verification"] = false;
        }
        return json_encode($response);
    }

    public function orderFetch(Request $request){

        //parseHeaderToRetrieveAuthorizationHeader
        $allHeaders = getallheaders();
        $api_token = $allHeaders['Authorization'];

        $orderListArray = [];

        $user = App\User::where('api_token', $api_token)->first();
        $store_id = $user['store_id'];

        $orderCount = App\Order::where('store_id', $store_id)->count();
        $orderArray = App\Order::where('store_id', $store_id)->orderBy('delivery_date', 'asc')->get();


        for ($i = 1; $i <= $orderCount; $i++)
        {
            $allOrders = $orderArray->shift();
            $orderID = $allOrders['id'];
            $orderAmt = $allOrders['order_amount'];
            $orderPaid = $allOrders['order_paid'];
            $orderDeliveryDate = $allOrders['delivery_date'];
            $orderCreateDate = $allOrders->created_at->format('Y-m-d');

            //add customer info
            $customer = App\Customer::where('id', $allOrders['customer_id'])->get();
            $customer_name = $customer[0]['name'];
            $customer_phone = $customer[0]['phone'];

            $orderListOne = ['id' => $orderID, 'delivery_date' => $orderDeliveryDate, 'created_at' => $orderCreateDate, 'customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'order_amount' => $orderAmt, 'order_paid' => $orderPaid];
            array_push($orderListArray, $orderListOne);

        }

        return $orderListArray;

    }

    public function customerOrderFetch(){

        $allHeaders = getallheaders();
        $api_token = $allHeaders['Authorization'];
        $customer = $allHeaders['X-Request-ID'];

        $orderListArray = [];

        $user = App\User::where('api_token', $api_token)->first();
        $store_id = $user['store_id'];

        $orderCount = App\Order::where('store_id', $store_id && 'customer_id', $customer)->count();
        $orderArray = App\Order::where('store_id', $store_id && 'customer_id', $customer)->orderBy('delivery_date', 'asc')->get();

        for ($i = 1; $i <= $orderCount; $i++)
        {
            $allOrders = $orderArray->shift();
            $orderID = $allOrders['id'];
            $orderAmt = $allOrders['order_amount'];
            $orderPaid = $allOrders['order_paid'];
            $orderDeliveryDate = $allOrders['delivery_date'];
            $orderCreateDate = $allOrders->created_at->format('Y-m-d');

            //add customer info
            $customer = App\Customer::where('id', $allOrders['customer_id'])->get();
            $customer_name = $customer[0]['name'];
            $customer_phone = $customer[0]['phone'];

            $orderListOne = ['id' => $orderID, 'delivery_date' => $orderDeliveryDate, 'created_at' => $orderCreateDate, 'customer_name' => $customer_name, 'customer_phone' => $customer_phone, 'order_amount' => $orderAmt, 'order_paid' => $orderPaid];
            array_push($orderListArray, $orderListOne);

        }

        return $orderListArray;

    }
}

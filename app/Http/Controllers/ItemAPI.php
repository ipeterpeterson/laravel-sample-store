<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\DB;

class ItemAPI extends Controller
{

    /**
     * @param Request $request (<HEADER>Order ID)
     * @return JSON Array of Items
     */
    public function itemFetchAll (Request $request) {

        //parseHeaderToRetrieveAuthorizationHeader
        $allHeaders = getallheaders();
        $order_id = $allHeaders['X-Request-ID'];

        //Fetch All Items of that Order
        $itemArray = App\Item::where('order_id', $order_id)->get();
        return $itemArray;

    }

    /**
     * @param Request $request (API Token, Item ID, Employee ID)
     * @return JSON Object of verification token
     */
    public function itemEmployeeUpdate(Request $request) {

        $api_token = $request['api_token'];
        $itemID = $request['item_id'];
        $employeeID = $request['employee_id'];

        $user = App\User::where('api_token', $api_token)->first();

        if($user){

            //Checking whether employeeID, itemID & API Token belong to the same store
            //Loading Store ID from Employee ID
            $employeeCheck = App\Employee::where('id', $employeeID)->first();
            $employee_store_verification = $employeeCheck['store_id'];

            //Loading StoreID from ItemID
            $itemCheck = App\Item::where('id', $itemID)->first();
            $orderCheck = App\Order::where('id', $itemCheck['order_id'])->first();
            $order_StoreID_Check = $orderCheck['store_id'];

            //Loading StoreID from API Token
            $user_store_verification = $user['store_id'];

            //if both store_ids are the same, then update the item with the new employee ID
            if ($employee_store_verification == $user_store_verification && $order_StoreID_Check == $user_store_verification) {

                //fetch item
                $item = App\Item::where('id', $itemID)->first();
                if ($item) {
                    //update employee ID
                    $item->employee_id = $employeeID;
                    //save item
                    $item->save();
                    //set verification token
                    $response["verification"] = true;
                } else {
                    //failed item exists check
                    $response["verification"] = false;
                }


            } else {
                //failed store ID of employee / item / api token check
                $response["verification"] = false;
            }

        } else {
            //failed API Token check
            $response["verification"] = false;
        }

        //return verification token
        return $response;

    }


    /**
     * @param Request $request (API Token, Customer ID, Item Type)
     * @return JSON Object of Item
     * @todo Need to revamp SQL Query to fetch last order from a particular store belonging to a specific item_type
     */
    public function itemCustomerMeasurements(Request $request) {

        $allHeaders = getallheaders();
        $api_token = $allHeaders['Authorization'];
        $customerID = $allHeaders['X-Request-ID'];
        $itemType = $allHeaders['X-Requested-With'];

        $tokenCheck = App\User::where('api_token', $api_token)->first();

        if($tokenCheck){

            $storeID = $tokenCheck['store_id'];

            $dbQuery = "SELECT * FROM items AS i INNER JOIN customer_item AS ci ON i.id = ci.item_id WHERE customer_id = " . $customerID . " AND item_type = " . $itemType . " ORDER BY i.id DESC LIMIT 1;";
            $previousItem = DB::select($dbQuery);

            return $previousItem;

        }



    }
}

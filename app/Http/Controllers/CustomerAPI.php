<?php

namespace App\Http\Controllers;

use App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAPI extends Controller
{
    /**
     * @param Request $request (API Token, Customer Name / Phone / Email<optional> / Location)
     * @return JSON Object of verification token
     */
    public function customerCreate(Request $request)
    {
        $name = $request['name'];
        $phone = $request['phone'];
        $email = $request['email'];
        $location = $request['location'];
        $api_token = $request['api_token'];

        //check if input api_token belongs to the assigned user
        $tokenCheck = App\User::where('api_token', $api_token)->first();

        if ($tokenCheck) {
            //token is correct, extract the storeID and then check if the customer exists
            $storeID = $tokenCheck['store_id'];
            $phonecheck = App\Customer::where('phone', $phone)->first();

            //check if phone exists
            if ($phonecheck) {
                //customer exists, so return verification false
                $response["verification"] = false;
            } else {
                //create the new customer
                $newCustomer = App\Customer::create([
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'location' => $location,
                ]);
                //Add the new customer to the pivot table
                $newCustomer->stores()->attach($storeID);
                //set verification response
                $response["verification"] = true;
            }
            return json_encode($response);

        }
        else{
            $response["verification"] = false;
            return json_encode($response);
        }
    }

    /**
     * @param Request $request (API Token, Customer Name / New Phone / Old Phone / Email / Location)
     * @return JSON Object of verification token
     * @todo customerEdit is currently disabled from the app. Need to think about this section.
     */
    public function customerEdit(Request $request)
    {
        $name = $request['name'];
        $phonenew = $request['phonenew'];
        $phone = $request['phone'];
        $email = $request['email'];
        $location = $request['location'];
        $id = App\Customer::where('phone', $phone)->value('id');
        $api_token = $request['api_token'];

        $tokenCheck = App\User::where('api_token', $api_token)->first();//check if input api_token belongs to the assigned user.

        if($tokenCheck) {
        $customer = App\Customer::where('id', $id)->update([
                'name' => $name,
                'phone' => $phonenew,
                'email' => $email,
                'location' => $location,
            ]);
            $response["verification"] = true;
            return json_encode($response);
        }
        else{
            echo "token mismatch";
        }
    }


    /**
     * @param Request $request (API Token)
     * @return JSON Array of All Customers of a particular store
     * @todo Need to rewrite this method using Eloquent
     */
    public function customerFetchAll(Request $request){

        //Get all headers
        $allHeaders = getallheaders();
        $api_token = $allHeaders['Authorization'];

        //check if input api_token belongs to the assigned user.
        $tokenCheck = App\User::where('api_token', $api_token)->first();

        if($tokenCheck){

            $storeID = $tokenCheck['store_id'];

            /*//Stack Overflow Answer: AddWeb
            $customer = App\Customer::leftJoin('customer_store', function ($join) use ($storeID){
                        $join->on('customers.id', '=', 'customer_store.customer_id')
                            ->where('customer_store.store_id', '=', $storeID);
                    })->whereNotNull('customer_store.store_id')->get();

            //Stack Overflow Answer: kapil.dev
            $customer = App\Customer::with('stores')->get();*/

            //Using Raw DB Query (Pete's Old Method)
            //$dbQuery = "SELECT customers.id, customers.name, customers.phone, customers.email, customers.location FROM customers LEFT JOIN customer_store on customers.id = customer_store.customer_id WHERE customer_store.store_id = " . $storeID . ";";
            //$customer = DB::select($dbQuery);

            $customer = App\Customer::whereHas('stores', function ($query) use($storeID) {
                $query->where('stores.id', $storeID);
            })->get();

            return $customer;

        } else {
            $response['verification'] = false;
            return $response;
        }

    }

    /**
     * @param Request $request, (API Token / Customer's phone number)
     * @return JSON Object of a single customer belonging to that store
     */
    public function customerFetch(Request $request){

        $api_token = $request['api_token'];
        $phone = $request['phone'];

        $tokenCheck = App\User::where('api_token', $api_token)->first();//check if input api_token belongs to the assigned user.

        //Verify API Token starts here
        if($tokenCheck) {

            $customerCheck = App\Customer::where('phone', $phone)->first();
            $store_id = $tokenCheck['store_id'];

            //API Token has passed and phone verification starts here
            if ($customerCheck) {

                $customer_pivot_check = $customerCheck->stores()->where('store_id', $store_id)->exists();

                //checking if customer exists in the pivot table
                if ($customer_pivot_check == true) {
                    //customer exists, return the customer object. Also, set verification = true
                    $customerCheck['verification'] = true;
                    return $customerCheck;
                } else {
                    //customer exists, so add the customer to that store in the pivot table
                    //then return the customer object
                    $customerCheck->stores()->attach($store_id);
                    return $customerCheck;
                }

            }

            else{
                //Create new customer on Android
                $response["verification"] = false;
            }
            //API Token has passed and phone verification ends here
        }
        else{
            $response["verification"] = false;
        }

        //Verify API Token ends here
        return json_encode($response);

    }
}

<?php


namespace App\Http\Controllers;

use App;
use App\User;
use Validator;
use App\Http\Controllers;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;

class RegisterAPI extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    // Create a new User
    public function userCreate (Request $request)
    {

        /**
         * Storing HTTP requests to a variable
         */
        $store_name = $request['store_name'];
        $address = $request['store_address'];
        $zip = $request['store_zip'];
        $phone = $request['store_phone'];
        $name = $request['user_name'];
        $email = $request['user_email'];
        $password = $request['user_password'];

        /**
         * Checking if user already exists
         */
        $userCheck = App\User::where('email', $email)->first();

        if($userCheck){
            //Does not run the insert queries on Store and User
            $response["verification"] = false;
            $response["code"] = 1;
            return json_encode($response);

        } else {

            /**
             * Setting up create query for creating a new store
             */
            $storeCreate = new App\Store;
            $storeCreate->name = $store_name;
            $storeCreate->address = $address;
            $storeCreate->zip = $zip;
            $storeCreate->phone = $phone;

            /**
             * Setting up create query for creating a new customer
             */
            $userCreate = new App\User;
            $userCreate->name = $name;
            $userCreate->email = $email;
            $userCreate->password = bcrypt($password);

            /**
             * Saving App\Store & App\User
             */
            $storeCreate->save();
            $storeCreate->user()->save($userCreate);

            //return response for mobile
            $response["verification"] = true;
            return json_encode($response);

        }

    }
}

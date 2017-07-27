<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class EmployeeAPI extends Controller
{

    /**
     * @param Request $request (API Token, Employee Name / Email / Type)
     * @return JSON Object of verification token
     */
    public function employeeCreate(Request $request) {

        $api_token = $request['api_token'];

        $employeeName = $request['name'];
        $employeeEmail = $request['email'];
        $employeeType = $request['itemtype'];

        $user = App\User::where('api_token', $api_token)->first();

        if($user){

            //Fetch Store ID
            $storeID = $user['store_id'];

            //Create Employee
            $employee = new App\Employee;
            $employee->store_id = $storeID;
            $employee->name = $employeeName;
            $employee->email = $employeeEmail;
            $employee->type = $employeeType;

            $employee->save();

            $response['verification'] = true;
            return $response;

        } else {

            $response['verification'] = false;
            return $response;

        }

    }

    /**
     * @param Request $request
     * @return string JSON Array of Employees or Verification Token
     * @todo Return Employees who have not resigned (where 'employee_status' = 1)
     */
    public function employeeFetchAll (Request $request) {

        $allHeaders = getallheaders();

        $api_token = $allHeaders['Authorization'];

        $user = App\User::where('api_token', $api_token)->first();

        //check if user exists
        if ($user){

            $store_id = $user['store_id'];

            $employees = App\Employee::where('store_id', $store_id)->get();

            return $employees;

        } else {

            $response[0]['verification'] = false;
            return json_encode($response);

        }
    }

    public function employeeAssign(Request $request) {

        $api_token = $request['api_token'];
        $employeeID = $request['employee_id'];
        $itemID = $request['item_id'];

        $token_check = App\User::where('api_token', $api_token)->first();
        //return $token_check;

        if($token_check){

            $item = App\Item::where('id', $itemID)->update([
                'employee_id' => $employeeID
            ]);

            $response["verification"] = true;

        } else {

            $response["verification"] = false;

        }
        return json_encode($response);
    }
}

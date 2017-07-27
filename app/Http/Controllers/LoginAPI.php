<?php


namespace App\Http\Controllers;

use App;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;


class LoginAPI extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function authenticate (Request $request)
    {
        //Defining input variables
        $email = $request['email'];
        $password = $request['password'];

        //Verifying if email exists.
        $user = App\User::where('email', '=', $email)->first();
        if ($user['id'] == null) {

            $response["verification"] = false;
            echo json_encode($response);
        }
        else{
            //Checking password associated with the email
            $passwordCheck = \Illuminate\Support\Facades\Hash::check($password, $user->password);
            if($passwordCheck){

                $randomString = str_random(60);
                App\User::where('email', $email)->update(array('api_token' => $randomString));

                $response["verification"] = true;
                $response["name"] = $user['name'];
                $response["email"] = $user['email'];
                $response['api_token'] = $randomString;

                return json_encode($response);

            }
            else{
                $response["verification"] = false;
                return json_encode($response);
            }

        }


    }
}

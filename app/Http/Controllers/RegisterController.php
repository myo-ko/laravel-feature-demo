<?php

namespace App\Http\Controllers;

use App\User;
use App\Cognito\CognitoClient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Aws\CognitoIdentityProvider\Exception\UserNotFoundException;
use Aws\CognitoIdentityProvider\Exception\UserNotConfirmedException;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() {
        return view('register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [    
            'name' => ['required', 'string', 'max:255'],       
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    public function store(Request $request) {

        $validator = $this->validator($request->all());
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $attr = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        $res = app()->make(CognitoClient::class)->register($request->input('email'), $request->input('password'), $attr);

        return response()->json(true, 200);
    }

    public function confirm(Request $request) 
    {
        $validator = Validator::make($request->all(), [           
            'email' => ['required', 'string', 'email', 'max:255'],
            'code' => ['required',],
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $attr = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];
        $res = app()->make(CognitoClient::class)->confirmSignUp($request->input('email'), $request->input('code'), $attr);
        
        $status = $res['@metadata']['statusCode'];
        if ($status == 200) {
            return response()->json("Successfully verified", 200);
        }else {
            return response()->json(dd($res), 505);
        }
    }

    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [           
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required',],
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        try {
            $result = app()->make(CognitoClient::class)->authenticate($request->input('email'), $request->input('password'));

            $accessToken = $result->get('AuthenticationResult');
    
            $response = [
                'status' => true,
                'token' => $accessToken
            ];
            $code = 200;
        }
        catch (CognitoIdentityProviderException $e){

            $response = [
                'status' => false,
                'error' => $e->getAwsErrorCode(),
                'message' => $e->getAwsErrorMessage(),
            ];
            $code = $e->getStatusCode();
                       
        }
        return response()->json($response, $code);

    }
}

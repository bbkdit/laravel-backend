<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        // dd('here');
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);

        // print_r($credentials);die;
         if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function Oldlogin(Request $request){

        $http = new \GuzzleHttp\Client;
        $response = Http::asForm()->post('http://127.0.0.1:8000/oauth/token', [
        // $response = $http->post('http://127.0.0.1:8000/oauth/token', [
            // 'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRETE'),
                'username' => $request->input('username'),
                'password' => $request->input('password'),
                'scope' => '',
            // ],
        ]);
        
        // //  dd($response->json());
        return json_decode((string) $response->getbody(), true);
    }

    public function register(Request $request)
    {
          $request->validate([
                 'fName' => 'required|string',
                 'lName' => 'required|string',
                 'email' => 'required|string|email|unique:users',
                 'password' => 'required|string'
          ]);
          $user = new User;
          $user->first_name = $request->fName;
          $user->last_name = $request->lName;
          $user->email = $request->email;
          $user->password = bcrypt($request->password);
          $user->save();
          return response()->json([
               'message' => 'Successfully created user!'
          ], 201);
    }

    public function logout(Request $request)
    {
        if($request->user()->token()->revoke()){
            return response()->json([
                'message' => 'Successfully logged out'
              ], 200);
        }else{
            return response()->json([
                'message' => 'token mismatch'
              ], 401);
        }
        
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

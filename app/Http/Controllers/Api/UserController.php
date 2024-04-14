<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\Register;
use App\Repository\Api\UserRepository;


class UserController extends Controller
{
    function register(Register $request)
    {
        $repObj=new UserRepository;
        $user=$repObj->registerUser($request);
        $resArr=[
            'status'=>true,
            'message'=>'User created successfully.',
            'data'=>$user
        ];
        return response($resArr, Response::HTTP_CREATED);
    }

    function loginWithEmail(Request $request) {
        $resArr=[
            'status'=>false,
            'message'=>'Invalid credential'
        ];
        if(!\Auth::attempt($request->only('email','password'))){
            return response($resArr, Response::HTTP_UNAUTHORIZED);
        }
        $user=\Auth::user();
        $repObj=new UserRepository;
        $user=$repObj->getUserAfterLoggedIn($user);
        if($request->cookie('jwt')){
            \Cookie()->forget('jwt');
        }
        $expiry=now()->addDays(7);
        $jwt=$user->createToken('beareToken', ['*'], $expiry)->plainTextToken;
        $cookie=cookie('jwt', $jwt,   60 * 24 * 7); // I week
        $resArr=[
            'status'=>true,
            'message'=>'Success',
            //'token'=>$jwt
        ];
        return response($resArr, Response::HTTP_OK)->withCookie($cookie);
    }

    function UserProfile(Request $request) {
        $user=\Auth::user();
        $repObj=new UserRepository;
        $user=$repObj->getUserAfterLoggedIn($user);
        $resArr=[
            'status'=>true,
            'message'=>'Success',
            'data'=>$user
        ];
        return response($resArr, Response::HTTP_OK);
    }

    function logOut(Request $request) {
        $cookie=\Cookie()->forget('jwt');
        $resArr=[
            'status'=>true,
            'message'=>'Success',
            'data'=>$request->user
        ];
        return response($resArr, Response::HTTP_OK)->withCookie($cookie);
    }
}

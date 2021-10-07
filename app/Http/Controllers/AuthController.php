<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;



    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if($validator->fails()){
            return $this->error('check fields!', 401,  $validator->errors());
        }else{
            $attr = $request->all();
        }


        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        $user->assignRole('guest');


        return $this->success( [
            'token' => $user->createToken('API Token')->plainTextToken
        ],"Registered");
    }


    public function login(Request $request)
    {

        $attr = $request->all();


        $validator = Validator::make($attr,[
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return $this->error('Check fields!', 401,  $validator->errors());
        }else{
            $attr = $request->all();
        }



        if (!Auth::attempt($attr)) {
            return $this->error('The information was not entered correctly ', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);

    }

    public function infoAction(Request $request)
    {
        auth()->user()->getRoleNames();
        auth()->user()->getDirectPermissions();
        $user = auth()->user();
        return $this->success($user);
        
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success([
            'message' => 'Token deleted !'
        ]);
    }
}

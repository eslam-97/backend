<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Http\Requests\registerRequest;
use App\Http\Requests\updateUserRequest;
use App\Models\User;
use App\traits\photoTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    use photoTrait;


    public function register(registerRequest $request) {
        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $request->validator->messages()]);
        }

        if ($request->hasFile('avatar')) {
            $photo = $this->savePhoto($request['avatar'], 'users');
        } else {
            $photo = '';
        }

        $user = User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => strtolower($request['email']),
            'phone' => $request['phone'],
            'password' => Hash::make($request->password),
            'avatar' => $photo,
        ]);
        if(!is_null($user)) {
            return response()->json(["status" => "success", "message" => "Success! registration completed", "user" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Registration failed!"]);
        }       
    }


    public function login(loginRequest $request) {

        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json(["validation_errors" =>  $request->validator->messages()]);
        }

        $user = User::where("email", $request->email)->first();

        if(is_null($user)) {
            return response()->json(["status" => "failed", "message" => "Login failed! email not found"]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json(["status" => "success", "login" => true, "token" => $token, "user" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Login failed! invalid password"]);
        }
    }


    public function profile()
    {
    return  Response()->json(auth('sanctum')->user());
    }


    public function signOut() {

     if(auth('sanctum')->user()->tokens()->each(function($token, $key){
        $token->delete();
            })){
            return Response()->json(['msg'=>'sign out']);
        }
    }


    public function updateUser(updateUserRequest $request){

        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json(["status" => "failed", "message" => $request->validator->messages()]);
        }
        $user = User::find($request->id);

        if ($request->hasFile('avatar')) {
            $imageName = $user->avatar;
            if($imageName){
                File::delete($imageName);
            }
            $photo = $this->savePhoto($request->avatar, 'users');
            $user->avatar = $photo;
        } 
        $user->name = $request->name;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->save();

        if(!is_null($user)) {
            return response()->json(["status" => "succes", "message" => "updated Successfully" , 'user' => $user]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "update failed!"]);
        }       
    }



    public function updateUserPassword(updateUserRequest $request){

        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json(["status" => "failed", "message" => $request->validator->messages()]);
        }

        $user = User::find($request->id);
        if(Hash::check($request->password, $user->password)){
        $user->password = Hash::make($request->newPassword);
        $user->save();
        if(!is_null($user)) {
            return response()->json(["status" => "succes", "message" => "password updated Successfully" , 'user' =>$user]);
        }
        }
        else {
            return response()->json(["status" => "failed", "message" => "password don't match!"]);
        }       
    }
}


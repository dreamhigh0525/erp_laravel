<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\access_right;
use App\User;

class UserController extends Controller
{
    public function GetAccessRight($id){
        $user = User::where('email',$id)->first();
        $access=access_right::where('user_id',$user->id)->first();
        if($user->id !=""){
            return response()->json([
                'status' => 'success',
                'data' => $access
            ], 200);
        } else{
            return response()->json([
                'status' => 'Error',
                'data' => 'User not found'
            ], 200);
        }
            
    }

    public function getGroup($id){
        $user = User::where('email',$id)->first();
        echo  $user;
        // if($user->id !=""){
        //     return response()->json([
        //         'status' => 'success',
        //         'data' => $user
        //     ], 200);
        // } else{
        //     return response()->json([
        //         'status' => 'Error',
        //         'data' => 'User not found'
        //     ], 200);
        // }
            
    }
}

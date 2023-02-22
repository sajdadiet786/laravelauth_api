<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    //
    public function sent_reset_password_email(Request $request){
        $request->validate([
            'email'=>'required|email',
        ]);
        $email=$request->email;
        $user=User::where('email', $email)->first(); 
        if(!$user){
            return response([
                'message'=>'Email doesnot exist',
                'status'=>'failed'

            ],404); 

        }
        $token=Str::random(60);
        PasswordReset::create([
            'email'=>$email,
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);
        // /dump("http://127.0.0.1:3000/api/user/reset/".$token);
        Mail::send('reset',['token'=>$token],function(Message $message){
            $message->subject('reset your password');
            $message->to('email');
        });
            
 
        return response([
            'message'=>'Password Reset Email Sent... Check Your Email',
            'status'=>'successs'
        ]);
    }
}

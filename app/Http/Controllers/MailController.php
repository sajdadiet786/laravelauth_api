<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\TestMail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function index(){
        $data=[
            'subject'=>'mail send',
            'body'=>'hello sajda'
        ];
        try {
          Mail::to('sajdap651@gmail.com')->send(new TestMail($data));
          return response()->json(['check your mail box']);
        } catch (Exception $th) {
            //throw $th;
            return response()->json(['sorry some thing wrong']);
        }
    }
}

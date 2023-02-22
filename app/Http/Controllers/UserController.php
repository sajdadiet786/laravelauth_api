<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'tc' => 'required',
        ]);
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
                'status' => 'failed',

            ], 200);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tc' => json_decode($request->tc),

        ]);
        $token = $user->createToken('LaravelAuth@2023')->plainTextToken;
        return response([
            'token' => $token,
            'message' => 'registration success',
            'status' => 'success',

        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password,
            $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;
            return response([
                'token' => $token,
                'message' => 'login success',
                'status' => 'success',

            ], 200);
        }
        return response([
            'message' => 'The provided cradentials are incorrect',
            'status' => 'failed',
        ], 401);

    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'logout success',
            'status' => 'success',
        ], 200);
    }

    public function logged_user()
    {
        $loggeduser = auth()->user();
        return response([
            'user' => $loggeduser,
            'message' => 'Logged user Data',
            'status' => 'success',
        ], 200);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',

        ]);
        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($request->password);
        $loggeduser->save();
        return response([
            'message' => 'password changed successfully',
            'status' => 'success',
        ], 200);
    }

    public function index()
    {
        $user = User::select('name', 'email')->get();
        if (count($user) > 0) {
            $response = [
                'message' => count($user) . 'user found',
                'status' => 'success',
                'data' => $user,
            ];

        } else {
            $response = [
                'message' => count($user) . 'user found',
                'status' => 'failed',
            ];
        }
        return response()->json($response, 200);
        // p($user);
    }



    public function destroy($id)
    {

        $user = User::find($id);
        if (is_null($user)) {
            $response = [
                'message' => 'user does not exist',
                'status' => 'failed',
            ];
            $respCode = 404;
        } else {
            DB::beginTransaction();
            try {
                $user->delete();
                DB::commit();
                $response = [
                    'message' => 'user deleted successfully',
                    'status' => 'success',
                ];
                $respCode = 200;
            } catch (\Exception $err) {
                DB::rollback();
                $response = [
                    'message' => 'internal server error',
                    'status' => 'failed',
                ];
                $respCode = 500;
            }
        }
        return response()->json($response, $respCode);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        // p($request->all());
        // die;
        if (is_null($user)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'user does not exist',

            ], 404);
        } else {
            DB::beginTransaction();
            try {
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->email = $request['email'];
               $user->tc = json_decode($request->tc);
                $user->save();
                DB::commit();

            } catch (\Exception $err) {
                DB::rollback();
                $user = null;
            }
            if (is_null($user)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Internal server error',
                    'error_msg' => $err->getMessage(),

                ], 500);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'user data updated succesfully',

                ], 200);
            }
        }

    }
}

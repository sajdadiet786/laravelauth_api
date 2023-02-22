<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageStoreRequest;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    //
    public function imageStore(ImageStoreRequest $request)
    {
        $images=new Image();
        $request->validate([
            'image'=>'required|max:1024'
        ]);

        $filename="";
        if($request->hasFile('image')){
            $filename=$request->file('image')->store('uploads/category','public');
        }else{
            $filename=Null;
        }
        $images->image=$filename;
        $result=$images->save();
        if($result){
            return response()->json([
                'image'=>$filename,
                'success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }
        
    }
    public function get()
    {
        $images=Image::orderBy('id','DESC')->get();
        return response()->json($images);
    }

        // if($request->hasfile('image')){
        //     $file=$request->file('image');
        //     $filename=time() . '-' .$file->getClientOriginalName();
        //     $uploaded= $file->move(public_path('uploads/category/'),$filename);
        // } 
        // Image::create([
        //     'image'=>$filename
        // ]);
        // return response([
        //         'image'=>$filename,
        //         'message' => 'image uploaded successfully success',
        //         'status' => 'success',
        //     ],201);

        // $validatedData = $request->validated();
        // $validatedData['image'] = $request->file('image')->store('image');
        // $data = Image::create($validatedData);

        // return response([
        //     'data'=>$data,
        //     'message' => 'image uploaded successfully success',
        //     'status' => 'success',
        // ],201);
    }


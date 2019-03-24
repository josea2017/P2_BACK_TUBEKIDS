<?php

namespace App\Http\Controllers;
use App\Video;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   $validator = Validator::make($request->all(), [
            'user_email' => 'required|string|max:255',
            'resource' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);
        //dd($id)
        //dd($request->video);
        $file_size = $request->video['file']['size'];
        $name= $request->video['file']['name'];
        $tmp_name=  $request->video['file']['tmp_name'];
        //$path= '../../videos';
        //$path= __DIR__ . '/../videos/';
        $path = base_path() . "\\resources\\videos\\";
        //dd($path);
        $imgUpload = File::copy($tmp_name, $path. $name);
        //move_uploaded_file($tmp_name, $path.$name);
        //C:\xampp\htdocs\P2_FRONT_TUBEKIDS\videos
        //move_uploaded_file($tmp_name, $path.$name);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        /*$video = Video::create([
            'user_email' => $request->get('user_email'),
            'resource' => $request->get('resource'),
            'name' => $request->get('name'),
        ]);*/
        return response()->json(compact('video'),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
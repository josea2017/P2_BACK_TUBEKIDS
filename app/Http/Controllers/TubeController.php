<?php

namespace App\Http\Controllers;
use App\Tube;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TubeController extends Controller
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
    {
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|string|max:255',
            'resource' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $tube = Tube::create([
            'user_email' => $request->get('user_email'),
            'resource' => $request->get('resource'),
            'name' => $request->get('name'),
        ]);
        return response()->json(compact('tube'),201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $email = $request->get('user_email');
        $dbYouTubeVideos = Tube::orderBy('name', 'asc')->where('user_email', $email)->get();
        return $dbYouTubeVideos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function databaseDeleteYouTubeVideo(Request $request)
    {
     
        $id = $request->get('id');
        $tube = null;
        $tube = \App\Tube::find($id);
        //$tube = DB::table('tubes')->where('id', $id)->get()->first();
        //dd($tube);
        if($tube != null){
        
            $tube->delete();

        }else{
            $contents = "Not found";
            $response = Response::make($contents, 404);
            $response->header('Content-Type', 'application/json');
            return var_dump($response);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function databaseEditYouTubeVideo(Request $request)
    {
     
        $id = $request->get('id');
        $new_name = $request->get('name');
        $tube = null;
        $tube = \App\Tube::find($id);
        //$tube = DB::table('tubes')->where('id', $id)->get()->first();
        //dd($tube);
        if($tube != null){
        
            //$tube->update();
            $tube->name = $new_name;
            $tube->save();

        }else{
            $contents = "Not found";
            $response = Response::make($contents, 404);
            $response->header('Content-Type', 'application/json');
            return var_dump($response);
        }

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

<?php

namespace App\Http\Controllers;
use App\Sub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;


class SubController extends Controller
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
        $sub = Sub::create([
            'full_name'    => $request->get('full_name'),
            'user_name'    => $request->get('user_name'),
            'pin'          => $request->get('pin'),
            'father_email' => $request->get('father_email'),
        ]);

        return response()->json(compact('sub'),201);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubs(Request $request){
        $father_email = $request->get('father_email');
        $subs_list = Sub::orderBy('user_name', 'asc')->where('father_email', $father_email)->get();
        return $subs_list;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchSub(Request $request){
        $id = $request->get('id');
        $sub = DB::table('subs')->where('id', $id)->get()->first();
        return response()->json($sub);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSub(Request $request){
        $id = $request->get('id');
        $profile = null;
        $profile = \App\Sub::find($id);
        //$order = DB::table('orders')->where('id_product', $id_product)->get()->first();
        if($profile != null){
            //$tube->update();
            //return $profile;
            $profile->full_name = $request->get('full_name');
            $profile->user_name = $request->get('user_name');
            $profile->pin = $request->get('pin');
            $profile->save();
            //var_dump($profile2['id']);
        }else{
            $contents = "Not found";
            $response = Response::make($contents, 404);
            $response->header('Content-Type', 'application/json');
            return var_dump($response);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSub(Request $request){
        $id = $request->get('id');
        $sub = null;
        $sub = \App\Sub::find($id);
        
        if($sub != null){
            $sub->delete();
        }else{
            $contents = "Not found";
            $response = Response::make($contents, 404);
            $response->header('Content-Type', 'application/json');
            return var_dump($response);
        }
    }

    public function findSubPerUserName(Request $request){
        //return response()->json($request);
        $sub = null;
       $sub = DB::table('subs')->where('user_name', $request->user_name)->get()->first();
       return response()->json($sub);

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

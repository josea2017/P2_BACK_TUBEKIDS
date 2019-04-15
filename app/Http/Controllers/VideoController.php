<?php

namespace App\Http\Controllers;
use App\Video;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use Response;
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
        $user = $request->get('user_email');
        $file_size = $request->video['file']['size'];
        $name= $request->video['file']['name'];
        $tmp_name=  $request->video['file']['tmp_name'];
        //$path= '../../videos';
        //$path= __DIR__ . '/../videos/';
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $user . "\\";
        //dd($path);
        //$success = \File::copy(base_path('test.text'),base_path('public/'));
        $imgUpload = File::copy($tmp_name, $path.$name);
        //move_uploaded_file($tmp_name, $path.$name);
        //C:\xampp\htdocs\P2_FRONT_TUBEKIDS\videos
        //move_uploaded_file($tmp_name, $path.$name);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $video = Video::create([
            'user_email' => $request->get('user_email'),
            'resource' => $request->get('resource'),
            'name' => $request->get('name'),
        ]);
        return response()->json(compact('video'),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {/*
        dd($request);
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $id . "\\";
        dd($path);*/
    }

    public function countVideos(Request $request){
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $request->get('email') . "\\";
        $array_videos = array();
        $page = array();
    
      $dir = @dir($path) or die("getFileList: Error abriendo el directorio $path para leerlo");
      while(($archivo = $dir->read()) !== false) {
          // Obviamos los archivos ocultos
          if($archivo[0] == ".") continue;
          /*if(is_dir($path . $archivo)) {
             // var_dump($archivo[0]);
          }*/ else if (is_readable($path . $archivo)) {
   
             $contents = null;
             $response = true;
              $contents = File::get($path . $archivo);
              $response = Response::make($contents, 200);
              $response->header('Content-Type', 'video/mp4');
              //return $response;
              array_push($array_videos, $response);

          }
      }
      return count($array_videos);
    }

    public function loadVideos(Request $request){
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $request->get('email') . "\\";
        $array_videos = array();
        $page = array();
    
        
        /////////////////////////////*****************///////////////////////////
        // Array en el que obtendremos los resultados
     // $array_videos = array();
      // Agregamos la barra invertida al final en caso de que no exista
      //if(substr($directorio, -1) != "/") $directorio .= "/";
      //$path = __DIR__ . '/../videos/';
      //$_FILES['answer']
      // Creamos un puntero al directorio y obtenemos el listado de archivos
      $dir = @dir($path) or die("getFileList: Error abriendo el directorio $path para leerlo");
      while(($archivo = $dir->read()) !== false) {
          // Obviamos los archivos ocultos
          if($archivo[0] == ".") continue;
          /*if(is_dir($path . $archivo)) {
             // var_dump($archivo[0]);
          }*/ else if (is_readable($path . $archivo)) {
              //echo "No encontrado";
              /*
              $upload_extension =  explode(".", $path . $archivo);
              $upload_extension = end($upload_extension);
               $array_videos[] = array(
                "name" => basename($path . $archivo),
                "size" => filesize($path . $archivo),
                "tmp_name"   => tempnam($path, 'tmp_name'),
                "type"     => $upload_extension,
              );*/
              //$_FILES = $archivo;
             // return $response;
             $contents = null;
             $response = true;
              $contents = File::get($path . $archivo);
              $response = Response::make($contents, 200);
              $response->header('Content-Type', 'video/mp4');
              //return $response;

              array_push($array_videos, $response);

          }
      }
    //$values_json = base64_encode( json_encode( $array ) );
    //$comment= $array_videos->toArray();
      //return $array_videos;
      //return $response;
      //return $response;
      //$array_videos->header('Content-Type', 'video/mp4');
      //var_dump($array_videos[0]);
      //return $path;
      //return $array_videos;
      //return response()->json($array_videos);
      //return response()->json(json_encode($array_videos), 200);
      //return response()->json(compact('user','token'),201);
      //return response()->json(json_encode($array_videos, true));
      return count($array_videos);
    }


    public function loadIndexVideo(Request $request){
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $request->get('email') . "\\";
        $array_videos = array();
        $page = array();
        $index = $request->get('index');
    
        
        /////////////////////////////*****************///////////////////////////
        // Array en el que obtendremos los resultados
     // $array_videos = array();
      // Agregamos la barra invertida al final en caso de que no exista
      //if(substr($directorio, -1) != "/") $directorio .= "/";
      //$path = __DIR__ . '/../videos/';
      //$_FILES['answer']
      // Creamos un puntero al directorio y obtenemos el listado de archivos
      $dir = @dir($path) or die("getFileList: Error abriendo el directorio $path para leerlo");
      while(($archivo = $dir->read()) !== false) {
          // Obviamos los archivos ocultos
          if($archivo[0] == ".") continue;
          /*if(is_dir($path . $archivo)) {
             // var_dump($archivo[0]);
          }*/ else if (is_readable($path . $archivo)) {
              //echo "No encontrado";
              /*
              $upload_extension =  explode(".", $path . $archivo);
              $upload_extension = end($upload_extension);
               $array_videos[] = array(
                "name" => basename($path . $archivo),
                "size" => filesize($path . $archivo),
                "tmp_name"   => tempnam($path, 'tmp_name'),
                "type"     => $upload_extension,
              );*/
              //$_FILES = $archivo;
             // return $response;
             $contents = null;
             $response = true;
              $contents = File::get($path . $archivo);
              $response = Response::make($contents, 200);
              $response->header('Content-Type', 'video/mp4');
              //return $response;
              array_push($array_videos, $response);

          }
      }
    //$values_json = base64_encode( json_encode( $array ) );
    //$comment= $array_videos->toArray();
      //return $array_videos;
      //return $response;
      //return $response;
      //$array_videos->header('Content-Type', 'video/mp4');
      //var_dump($array_videos[0]);
      //return $path;
      //return $array_videos;
      //return response()->json($array_videos);
      //return response()->json(json_encode($array_videos), 200);
      //return response()->json(compact('user','token'),201);
      //return response()->json(json_encode($array_videos, true));
      //return count($array_videos);
      return $array_videos[$index];
      //return "hola";
    }
    /*$orders = DB::table('orders')->select('id_car', 'created_at', DB::raw("SUM(price_total) as sum"))
                                     ->where('id_user', $user)
                                     ->groupBy('id_car','created_at')
                                     ->get();*/ 


    public function databaseVideosDetail(Request $request){
        $email = $request->get('email');
        $dbVideosDetail = Video::orderBy('name', 'asc')->where('user_email', $email)->get();
        return $dbVideosDetail;
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //pasar email de usuario y nombre del resource a eliminar
    public function serverDeleteVideo($resource, $email)
    {
        $resource2 = $resource;
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $email . "\\";

        $dir = @dir($path) or die("getFileList: Error abriendo el directorio $path para leerlo");
      while(($archivo = $dir->read()) !== false) {
          // Obviamos los archivos ocultos
          if($archivo[0] == ".") continue;
          /*if(is_dir($path . $archivo)) {
             // var_dump($archivo[0]);
          }*/ else if (is_readable($path . $archivo)) {
              
              $file = basename($path . $archivo); 
              //echo $file;
              if($file == $resource2){
                unlink($path . $archivo);
                //echo $file;
                 $contents = "Deleted";
                 $response = Response::make($contents, 204);
                 $response->header('Content-Type', 'application/json');
                 return var_dump($response);
              }
          }
      }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function databaseDeleteVideo(Request $request)
    {
     
        $id = $request->get('id');
        $email = $request->get('email');
        $video = null;
        $video = \App\Video::find($id);
        
        if($video != null){
            $resource = $video->resource;
            //echo $resource;
            //echo $resource;
            $video->delete();
            $this->serverDeleteVideo($resource, $email);
            //echo $email;
            //** $contents = "Deleted";
            //** $response = Response::make($contents, 204);
            //** $response->header('Content-Type', 'application/json');
            ///** */return var_dump($response);
            
        }else{
            $contents = "Not found";
            $response = Response::make($contents, 404);
            $response->header('Content-Type', 'application/json');
            return var_dump($response);
        }
        //var_dump($video);
        //$videos = \App\Video::orderBy('id', 'asc')->get();
        //echo count($videos);
        /*$cant = count($videos);
        $index = 0;
        for($i = 0; $i<$cant; $i++)
        {
            //echo $videos[$i]['id'];
            if($videos[$i]['id'] == $id){
                $index = $i;
            }

        }
        echo $index;*/

    }


    


}

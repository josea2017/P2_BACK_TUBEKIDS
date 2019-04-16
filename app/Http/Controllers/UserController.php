<?php
    namespace App\Http\Controllers;
    require __DIR__ . '/../../../vendor/twilio/sdk/Twilio/autoload.php';
    use App\User;
    use App\Tube;
    use App\Video;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\MessageBag;
    use Twilio\Rest\Client;
    use App\Services\AuthyService;
    use App\Providers\AuthyServiceProvider;
    use Authy\AuthyApi;
    
    use Illuminate\Support\Arr;

    class UserController extends Controller
    {

        public function open() 
            {
                $data = "This data is open and can be accessed without the client being authenticated";
                return response()->json(compact('data'),200);

            }

            public function closed() 
            {
                $data = "Only authorized users can see this";
                return response()->json(compact('data'),200);
            }
            
        public function authenticate(Request $request)
        {
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(compact(['error' => 'invalid_credentials'], 400));
                    return response()->json(compact('user','token'),201);
                    //return response()->json('error', 'invalid_credentials'400));
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            return response()->json(compact('token'));
        }

        public function register(Request $request)
        {
                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',//agregando mas validaciones
                'last_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'country_code' => 'required|string|max:255',
                'birthday' => 'required',

            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'last_name' => $request->get('last_name'),
                'phone_number' => $request->get('phone_number'),
                'country_code' => $request->get('country_code'),
                'birthday' => $request->get('birthday'),

            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
        }

        public function getAuthenticatedUser()
            {
                    try {

                            if (! $user = JWTAuth::parseToken()->authenticate()) {
                                    return response()->json(['user_not_found'], 404);
                            }

                    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                            return response()->json(['token_expired'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                            return response()->json(['token_invalid'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                            return response()->json(['token_absent'], $e->getStatusCode());

                    }

                    return response()->json(compact('user'));
            }

        public function findUserPerEmail(Request $request){
            //return response()->json($request);
            $user = null;
           $user = DB::table('users')->where('email', $request->email)->get()->first();
           return response()->json($user);

        }

        public function video_folder(Request $request)
        {
            $path = base_path() . "\\resources\\videos\\";
            $user = $request->get('email');
            //$path = public_path().'/images/article/imagegallery/' . $galleryId;
            $path = $path . $user;
            File::makeDirectory($path, $mode = 0777, true, true);


            return response()->json(compact('user','path'),201);
        }


        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function getProfile(Request $request)
        {
            $email = $request->get('email');
            $profile = null;
            $profile = \App\User::find($email);
            if($profile != null){
        
                //$tube->update();
                return $profile;
    
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
        public function profileEdit(Request $request){
            /*
                    $product= \App\Product::find($id);
                    $product->name=$request->input('name');
                    $product->description=$request->input('description');
                    $product->stock=$request->input('stock');
                    $product->price=$request->input('price');
                    $product->save();
            */ 
            $email = $request->get('email');
            $profile = null;
            $profile2 = DB::table('users')->where('email', $email)->get()->first();
            $id = $profile2->id;
            //$id = $request->get('id');
            $profile = \App\User::find($id);
            //$order = DB::table('orders')->where('id_product', $id_product)->get()->first();
            if($profile != null){
                //$tube->update();
                //return $profile;
                $profile->name = $request->get('name');
                $profile->last_name = $request->get('last_name');
                $profile->phone_number = $request->get('phone_number');
                $profile->country_code = $request->get('country_code');
                $profile->birthday = $request->get('birthday');
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
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function profileDelete(Request $request){
           
            $email = $request->get('email');
            $profile = null;
            $profile2 = DB::table('users')->where('email', $email)->get()->first();
            $id = $profile2->id;
            $profile = \App\User::find($id);

            $httpClient = new \GuzzleHttp\Client();
            //old $apiKey = 'b2BQFD3gbieN3NOj28Oaq0zxZriRnt3x';
            $apiKey = 'S4dE4Wtimh5Oq2XK4FfV16cd4P0Sd6OH';
            $authyUser = new \Rinvex\Authy\User($httpClient, $apiKey);
            $email = $profile->user_email;
            
            if($profile != null){
                $email = $profile->email;
                $this->serverDeleteVideoFromProfile($email);//Delete Own videos in server
                $this->dbDeleteVideoFromProfileServer($email);//Delete Own video in Db
                $this->dbDeleteVideoFromProfile($email); //Delete Youtube videos in Db
                //$userDeleted = $authyUser->delete($profile->authy_id); // Delete user in authy
                $profile->delete(); //Delete user in database
                //var_dump($email);
            }else{
                $contents = "Not found";
                $response = Response::make($contents, 404);
                $response->header('Content-Type', 'application/json');
                return var_dump($response);
            }
            
            //echo $email;

        }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function testToDelete(Request $request){
            $email = $request->get('email');
            $this->profileDelete($request);

        }

         /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //pasar email de usuario y nombre del resource a eliminar
    public function serverDeleteVideoFromProfile($email)
    {
        $path = base_path() . "\\resources\\videos\\";
        $path = $path . $email . "\\";

        $dir = @dir($path) or die("getFileList: Error abriendo el directorio $path para leerlo");
      while(($archivo = $dir->read()) !== false) {
          // Obviamos los archivos ocultos
          if($archivo[0] == ".") continue;
          
          else if (is_readable($path . $archivo)) {
              
              $file = basename($path . $archivo); 
            
                unlink($path . $archivo);
                
          }
      }
        
    }
 

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dbDeleteVideoFromProfile($emailR)
    {
        $email = $emailR;
        //*$email = $emailR->get('email');
       //$order = DB::table('orders')->where('id_car', $id_car)->get()->first();
       $tubes = \App\Tube::where('user_email', $email)->get();
       //return count($tubes);
       $max = count($tubes);
       for($i = 0; $i < $max; $i++){
           //var_dump($tubes[$i]->id);
           
           $id = $tubes[$i]->id;
           $tube = null;
           $tube = \App\Tube::find($id);
           if($tube != null){
            $tube->delete();
           }
           
       } 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dbDeleteVideoFromProfileServer($emailR)
    {
        $email = $emailR;
        //*$email = $emailR->get('email');
       //$order = DB::table('orders')->where('id_car', $id_car)->get()->first();
       $videos = \App\Video::where('user_email', $email)->get();
       //return count($tubes);
       $max = count($videos);
       for($i = 0; $i < $max; $i++){
           //var_dump($tubes[$i]->id);
           
           $id = $videos[$i]->id;
           $video = null;
           $video = \App\Video::find($id);
           if($video != null){
            $video->delete();
           }
           
       } 

    }


    public function test(){
        // Use the REST API Client to make requests to the Twilio REST API

        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC68e755f9b09bf542c5ae71b4cc2302dd';
        $token = 'dc81fac28291f97f176f6dec305b5fc9';
        $client = new Client($sid, $token);

        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            '+50687551747',
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+13658000838',
                // the body of the text message you'd like to send
                'body' => 'Hey Jose! Good luck on the project!'
            )
        );
    }
    /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
    */
    public function registerAuthyUser(Request $request){
        $email = $request->get('email');
        $profile = null;
        $profile2 = DB::table('users')->where('email', $email)->get()->first();
        $id = $profile2->id;
        $profile = \App\User::find($id);
        $phone_number = $profile->phone_number;
        $country_code = $profile->country_code;
        //$email = "josea3712@gmail.com";
        //$phone_number = "87551747";
        //$country_code = "506";

        //$authyService = new \App\Facades\Authy;
      
        //*$apiKey = 'RaIrngVWsaTp1Pq0sZIKoELRzwETDxZi';
        //old $apiKey = 'b2BQFD3gbieN3NOj28Oaq0zxZriRnt3x';
        $apiKey = 'S4dE4Wtimh5Oq2XK4FfV16cd4P0Sd6OH';
        $httpClient = new \GuzzleHttp\Client();

        $authyUser = new \Rinvex\Authy\User($httpClient, $apiKey);
        $user = $authyUser->register($email, $phone_number, $country_code); // Register user

        $authyToken = new \Rinvex\Authy\Token($httpClient, $apiKey);
        //** $user = $authyUser->register($email, $phone_number, $country_code); // Register user
        //** $tokenVerified = $authyToken->verify(3805608, '144472020'); // Verify token to change status active (tokenSms, userId)
        //** $tokenVerified = $authyToken->verify(9665245, '144472020');
 
        //var_dump($tokenVerified);
        /*
        $succeed = $tokenVerified->succeed();
        if($succeed == false){
            echo "Es false";
        }else{
            echo "Es true";
        }
        */
        //echo $succeed;
        //echo $tokenVerified->success;
        //var_dump($user);
        //$user->get('user')['id']
        $this->registerAuthyUserIdDatabse($email, $user->get('user')['id']);
        return $user->get('user')['id'];
    }

    public function registerAuthyUserIdDatabse($emailResponse, $authyUserId){
            $email = $emailResponse;
            $profile = null;
            $profile2 = DB::table('users')->where('email', $email)->get()->first();
            $id = $profile2->id;

            $profile = \App\User::find($id);

            if($profile != null){
             
                $profile->authy_id = $authyUserId;
                $profile->save();
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
    public function verifyAuthyToken(Request $request){
        $email = $request->get('email');
        $authy_token = $request->get('authy_id');
        $profile = null;
        $profile2 = DB::table('users')->where('email', $email)->get()->first();
        $id = $profile2->id;
        $profile = \App\User::find($id);
        $phone_number = $profile->phone_number;
        $country_code = $profile->country_code;
        $authy_user_id = $profile->authy_id;
        //$email = "josea3712@gmail.com";
        //$phone_number = "87551747";
        //$country_code = "506";
        //$authyService = new \App\Facades\Authy;
        //*$apiKey = 'RaIrngVWsaTp1Pq0sZIKoELRzwETDxZi';
        //old$apiKey = 'b2BQFD3gbieN3NOj28Oaq0zxZriRnt3x';
        $apiKey = 'S4dE4Wtimh5Oq2XK4FfV16cd4P0Sd6OH';
        //RaIrngVWsaTp1Pq0sZIKoELRzwETDxZi
        $httpClient = new \GuzzleHttp\Client();
        $authyToken = new \Rinvex\Authy\Token($httpClient, $apiKey);
        $tokenVerified = $authyToken->verify($authy_token, $authy_user_id); // Verify token
        //echo $authy_user_id;
        //var_dump($tokenVerified); 
        $succeed = $tokenVerified->succeed();
        //var_dump($succeed);
        $value = "";
        if($succeed == false){
            $value = "false";
        }else{
            $value = "true";
        }
        return $value;
        //var_dump($tokenVerified);
        

    }

}//Class End
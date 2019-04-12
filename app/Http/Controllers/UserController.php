<?php

    namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\Input;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;

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
            if($profile != null){
                $profile->delete();
            }else{
                $contents = "Not found";
                $response = Response::make($contents, 404);
                $response->header('Content-Type', 'application/json');
                return var_dump($response);
            }

        }





    }
<?php

    namespace App;


    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Tymon\JWTAuth\Contracts\JWTSubject;
/*
$table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number');
            $table->string('country_code');
            $table->timestamp('birthday');
            $table->string('password');
            $table->rememberToken();
            $table->string('authy_status')->default('unverified');
            $table->string('authy_id')->nullable();
            $table->timestamps();
 */
//class User extends Model implements AuthenticatableContract, CanResetPasswordContract
    class User extends Authenticatable implements JWTSubject
    {
        use Notifiable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name', 'last_name', 'email', 'phone_number', 'country_code', 'birthday', 'password',
        ];

        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
            'password', 'remember_token',
        ];

        public function getJWTIdentifier()
        {
            return $this->getKey();
        }
        public function getJWTCustomClaims()
        {
            return [];
        }


    }
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Sub extends Model
{

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'full_name', 'user_name', 'pin', 'father_email',
        ];

        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
            
        ];



}

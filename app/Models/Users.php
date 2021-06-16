<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  protected $connection = "mysql";
  protected $collection = "users";

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'profile_photo',
    'last_name',
    'first_name',
    'email',
    'email_verified_at',
    'password',
    'remember_token',
  ];
}

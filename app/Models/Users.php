<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  protected $connection = "mysql";
  protected $collection = "dummy_data";

  protected $primaryKey = 'id';

  protected $fillable = [
    'id',
    'last_name',
    'first_name',
    'email',
    'email_verified_at',
    'password',
    'remember_token',
  ];
}

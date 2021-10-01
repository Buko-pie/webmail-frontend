<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Users newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Users newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Users query()
 * @mixin \Eloquent
 */
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

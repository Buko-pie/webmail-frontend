<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class DummyData extends Model
{
  protected $connection = "mysql";
  protected $collection = "dummy_data";

  protected $primaryKey = 'id';

  protected $fillable = [
    'sender',
    'receiver',
    'message',
    'starred',
    'important',
    'read',
    'has_attachment',
    'attachment_link',
    'lables'
  ];

  protected $casts = [
    'lables' => 'array'
  ];
}

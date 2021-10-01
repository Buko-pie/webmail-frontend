<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\DummyData
 *
 * @property int $id
 * @property string $sender
 * @property string $receiver
 * @property string|null $message
 * @property int $starred
 * @property int $important
 * @property int $read
 * @property mixed|null $labels
 * @property int $has_attachment
 * @property string|null $attachment_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DummyData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DummyData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DummyData query()
 * @mixin \Eloquent
 */
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

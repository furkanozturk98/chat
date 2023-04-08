<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Message
 *
 * @property int                             $id
 * @property int                             $from
 * @property int                             $to
 * @property string                          $room_id
 * @property string                          $message
 * @property string                          $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property int|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereStatus($value)
 */
class Message extends Model
{
    use HasFactory ,SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'from',
        'to',
        'room_id',
        'message',
        'image',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function from()
    {
        return $this->belongsTo(Message::class, 'from');
    }

    public function to()
    {
        return $this->belongsTo(Message::class, 'to');
    }
}

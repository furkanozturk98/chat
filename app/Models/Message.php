<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Message
 *
 * @property int         $id
 * @property int         $from
 * @property int         $to
 * @property int         $group_id
 * @property string      $room_id
 * @property string      $message
 * @property string      $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null    $deleted_by
 *
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereFrom($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereMessage($value)
 * @method static Builder|Message whereRoomId($value)
 * @method static Builder|Message whereTo($value)
 * @method static Builder|Message whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property int|null $status
 *
 * @method static Builder|Message whereStatus($value)
 */
class Message extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'from',
        'to',
        'room_id',
        'group_id',
        'message',
        'image',
        'status',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function fromMember()
    {
        return $this->belongsTo(GroupMember::class, 'from', 'member_id', 'group_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }
}

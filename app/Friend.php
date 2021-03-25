<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Friend
 *
 * @property int $id
 * @property int $user_id
 * @property int $friend_id
 * @property int $status
 * @property string $room_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $friend
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Friend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Friend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Friend query()
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereFriendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Friend whereRoomId($value)
 */
class Friend extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'friend_id',
        'status',
        'roomId'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(){
        return $this->belongsTo(User::class,'friend_id');
    }
}

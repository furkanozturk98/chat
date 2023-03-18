<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Friend
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $friend_id
 * @property int                             $blocked_by
 * @property string                          $room_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $friend
 * @property-read User $user
 *
 * @method static Builder|Friend newModelQuery()
 * @method static Builder|Friend newQuery()
 * @method static Builder|Friend query()
 * @method static Builder|Friend whereCreatedAt($value)
 * @method static Builder|Friend whereFriendId($value)
 * @method static Builder|Friend whereId($value)
 * @method static Builder|Friend whereStatus($value)
 * @method static Builder|Friend whereUpdatedAt($value)
 * @method static Builder|Friend whereUserId($value)
 *
 * @mixin Eloquent
 *
 * @method static Builder|Friend whereRoomId($value)
 */
class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'friend_id',
        'blocked_by',
        'room_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    public function blockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}

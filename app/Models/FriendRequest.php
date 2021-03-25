<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FriendRequest
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $fromUser
 * @property-read User $toUser
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FriendRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'from',
        'to',
        'status'
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class,'to');
    }
}

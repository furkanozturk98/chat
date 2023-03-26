<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\FriendRequest
 *
 * @property int         $id
 * @property int         $from
 * @property int         $to
 * @property int         $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $fromUser
 * @property-read User $toUser
 *
 * @method static Builder|FriendRequest newModelQuery()
 * @method static Builder|FriendRequest newQuery()
 * @method static Builder|FriendRequest query()
 * @method static Builder|FriendRequest whereCreatedAt($value)
 * @method static Builder|FriendRequest whereFrom($value)
 * @method static Builder|FriendRequest whereId($value)
 * @method static Builder|FriendRequest whereStatus($value)
 * @method static Builder|FriendRequest whereTo($value)
 * @method static Builder|FriendRequest whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'from',
        'to',
        'status',
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to');
    }
}

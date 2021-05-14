<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\GroupInvite
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property int $group_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupInvite whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read User $fromUser
 * @property-read User $toUser
 * @property-read \App\Models\Group $group
 */
class GroupInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'from',
        'to',
        'group_id',
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class,'group_id');
    }
}

<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupMessageStatus
 *
 * @property int         $id
 * @property int         $group_id
 * @property int         $member_id
 * @property int         $message_id
 * @property int|null    $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|GroupMessageStatus newModelQuery()
 * @method static Builder|GroupMessageStatus newQuery()
 * @method static Builder|GroupMessageStatus query()
 * @method static Builder|GroupMessageStatus whereCreatedAt($value)
 * @method static Builder|GroupMessageStatus whereGroupId($value)
 * @method static Builder|GroupMessageStatus whereId($value)
 * @method static Builder|GroupMessageStatus whereMemberId($value)
 * @method static Builder|GroupMessageStatus whereMessageId($value)
 * @method static Builder|GroupMessageStatus whereStatus($value)
 * @method static Builder|GroupMessageStatus whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property-read Message $message
 * @property-read GroupMember $member
 */
class GroupMessageStatus extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'member_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}

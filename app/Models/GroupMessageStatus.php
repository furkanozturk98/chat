<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GroupMessageStatus
 *
 * @property int                             $id
 * @property int                             $group_id
 * @property int                             $member_id
 * @property int                             $message_id
 * @property int|null                        $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessageStatus whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property-read GroupMessage $message
 * @property-read GroupMember $member
 */
class GroupMessageStatus extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function member()
    {
        return $this->belongsTo(GroupMember::class, 'member_id');
    }

    public function message()
    {
        return $this->belongsTo(GroupMessage::class, 'message_id');
    }
}

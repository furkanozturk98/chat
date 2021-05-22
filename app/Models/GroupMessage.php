<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GroupMessage
 *
 * @property int $id
 * @property int $group_id
 * @property int $sender
 * @property string|null $content
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read GroupMember $member
 */
class GroupMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'sender',
        'content',
        'image'
    ];

    public function member(){
        return $this->belongsTo(GroupMember::class, 'sender');
    }

}

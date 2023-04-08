<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupMessage
 *
 * @property int         $id
 * @property int         $group_id
 * @property int         $sender
 * @property string|null $content
 * @property string|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int         $deleted_by
 *
 * @method static Builder|GroupMessage newModelQuery()
 * @method static Builder|GroupMessage newQuery()
 * @method static Builder|GroupMessage query()
 * @method static Builder|GroupMessage whereContent($value)
 * @method static Builder|GroupMessage whereCreatedAt($value)
 * @method static Builder|GroupMessage whereGroupId($value)
 * @method static Builder|GroupMessage whereId($value)
 * @method static Builder|GroupMessage whereSender($value)
 * @method static Builder|GroupMessage whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property-read GroupMember $member
 */
class GroupMessage extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'sender',
        'content',
        'image',
        'created_at',
        'updated_at',
        'deleted_by',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'sender');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'deleted_by');
    }
}

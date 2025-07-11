<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Publication> $publications
 * @property-read int|null $publications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PublicationType extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }
}

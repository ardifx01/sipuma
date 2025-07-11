<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $nim
 * @property string $faculty
 * @property string $major
 * @property int $year
 * @property int|null $supervisor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Publication> $publications
 * @property-read int|null $publications_count
 * @property-read \App\Models\User|null $supervisor
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereFaculty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereSupervisorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereYear($value)
 * @mixin \Eloquent
 */
class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'faculty',
        'major',
        'year',
        'supervisor_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'student_id', 'user_id');
    }
}

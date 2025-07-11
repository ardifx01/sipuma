<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $nidn
 * @property string $faculty
 * @property string $major
 * @property string|null $expertise
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentProfile> $supervisedStudents
 * @property-read int|null $supervised_students_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereExpertise($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereFaculty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereNidn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DosenProfile whereUserId($value)
 * @mixin \Eloquent
 */
class DosenProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'faculty',
        'major',
        'expertise'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisedStudents()
    {
        return $this->hasMany(StudentProfile::class, 'supervisor_id', 'user_id');
    }
}

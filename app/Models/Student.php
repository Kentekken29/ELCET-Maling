<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'name', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // A student has many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Many-to-Many relationship through enrollments
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot(['discount_applied', 'completion_percentage', 'enrollment_date'])
            ->withTimestamps();
    }
}

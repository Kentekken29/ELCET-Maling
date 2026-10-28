<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'name'];

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
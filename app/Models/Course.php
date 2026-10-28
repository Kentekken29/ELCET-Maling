<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'title', 'instructor_id', 'fee'];

    // Cast the fee to a decimal with 2 places
    protected $casts = ['fee' => 'decimal:2'];

    // A course belongs to one instructor
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    // A course has many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Many-to-Many relationship through enrollments
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withPivot(['discount_applied', 'completion_percentage', 'enrollment_date'])
                    ->withTimestamps();
    }
}
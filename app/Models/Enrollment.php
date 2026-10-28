<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'course_id', 
        'enrollment_date',
        'discount_applied', 
        'completion_percentage'
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'discount_applied' => 'decimal:2',
        'completion_percentage' => 'integer',
    ];

    // An enrollment belongs to one student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // An enrollment belongs to one course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // An enrollment has one certificate (if completed)
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    // Computed Property: Net Amount Paid (Required by Exam)
    // We don't store this in the DB to maintain 3NF, we calculate it on the fly.
    public function getNetAmountPaidAttribute()
    {
        return (float)$this->course->fee - (float)$this->discount_applied;
    }
}
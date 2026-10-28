<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id', 'issued_at'];

    protected $casts = ['issued_at' => 'datetime'];

    // A certificate belongs to one enrollment
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
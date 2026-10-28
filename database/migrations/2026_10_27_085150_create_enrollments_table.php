<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('students')
                  ->onDelete('cascade'); // If student is deleted, their enrollments are deleted
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->onDelete('restrict'); // Cannot delete a course if students are enrolled in it
            $table->date('enrollment_date');
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->unsignedTinyInteger('completion_percentage')->default(0);
            $table->timestamps();
            
            // A student cannot enroll in the exact same course twice
            $table->unique(['student_id', 'course_id']); 
            $table->index('enrollment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
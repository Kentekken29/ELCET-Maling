<?php

use App\Models\Student;
use App\Models\User;
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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        foreach (User::all() as $user) {
            $student = Student::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($user->name)])
                ->orWhereRaw('LOWER(email) = ?', [strtolower($user->email)])
                ->first();

            if (! $student) {
                $firstName = strtok(trim($user->name), " \t\n\r");

                if ($firstName !== false && $firstName !== '') {
                    $student = Student::query()
                        ->whereRaw('LOWER(name) = ?', [strtolower($firstName)])
                        ->whereNull('user_id')
                        ->first();
                }
            }

            if ($student) {
                $student->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

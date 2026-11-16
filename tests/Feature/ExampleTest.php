<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_the_dashboard(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_course_and_enrollment_pages_render(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('courses.index'))->assertOk()->assertSee('Course catalog');
        $this->get(route('courses.create'))->assertOk()->assertSee('Create course');
        $this->get(route('enrollments.index'))->assertOk()->assertSee('All enrollments');
        $this->get(route('enrollments.create'))->assertOk()->assertSee('Create enrollment');
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_course_can_be_created_with_a_freeform_instructor_name(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('courses.store'), [
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_name' => 'john doe',
            'fee' => '1999.99',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('instructors', ['name' => 'John Doe']);
        $this->assertDatabaseHas('courses', ['code' => 'WEB-101', 'title' => 'Introduction to Web Design']);
        $this->assertSame('John Doe', Course::first()->instructor->name);
    }

    public function test_course_fee_can_be_edited_and_saved_from_the_catalog(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        $course = Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1000,
        ]);

        $response = $this->patch(route('courses.update', $course), [
            'fee' => '1250.50',
        ]);

        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'fee' => '1250.50',
        ]);
    }

    public function test_course_fee_cannot_be_saved_as_a_negative_amount(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        $course = Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1000,
        ]);

        $response = $this->patch(route('courses.update', $course), [
            'fee' => '-1',
        ]);

        $response->assertSessionHasErrors([
            'fee' => 'The course fee cannot be negative.',
        ]);
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'fee' => '1000.00',
        ]);
    }

    public function test_course_catalog_has_inline_fee_edit_controls(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1000,
        ]);

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('data-inline-fee-form', false)
            ->assertSee('data-fee-edit', false)
            ->assertSee('data-fee-cancel', false);
    }

    public function test_enrollment_net_fee_can_be_edited_and_saved(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        $student = Student::create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
        $course = Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1500,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => '2026-11-16',
            'discount_applied' => 100,
            'completion_percentage' => 0,
        ]);

        $response = $this->patch(route('enrollments.update', $enrollment), [
            'net_amount_paid' => '1250.50',
        ]);

        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'discount_applied' => '249.50',
        ]);
    }

    public function test_enrollment_net_fee_cannot_exceed_its_course_fee(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        $student = Student::create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
        $course = Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1500,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => '2026-11-16',
            'discount_applied' => 100,
            'completion_percentage' => 0,
        ]);

        $response = $this->patch(route('enrollments.update', $enrollment), [
            'net_amount_paid' => '1500.01',
        ]);

        $response->assertSessionHasErrors([
            'net_amount_paid' => 'The net fee cannot exceed the course fee.',
        ]);
        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'discount_applied' => '100.00',
        ]);
    }

    public function test_enrollment_net_fee_cannot_be_negative(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        $student = Student::create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
        $course = Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1500,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => '2026-11-16',
            'discount_applied' => 100,
            'completion_percentage' => 0,
        ]);

        $response = $this->patch(route('enrollments.update', $enrollment), [
            'net_amount_paid' => '-0.01',
        ]);

        $response->assertSessionHasErrors([
            'net_amount_paid' => 'The net fee cannot be negative.',
        ]);
        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'discount_applied' => '100.00',
        ]);
    }

    public function test_enrollment_catalog_has_inline_net_fee_edit_controls(): void
    {
        $this->actingAs(User::factory()->create());

        $instructor = Instructor::create(['name' => 'John Doe']);
        $student = Student::create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
        $course = Course::create([
            'code' => 'WEB-101',
            'title' => 'Introduction to Web Design',
            'instructor_id' => $instructor->id,
            'fee' => 1500,
        ]);
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => '2026-11-16',
            'discount_applied' => 100,
            'completion_percentage' => 0,
        ]);

        $this->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('data-inline-fee-form', false)
            ->assertSee('data-fee-edit', false)
            ->assertSee('name="net_amount_paid"', false)
            ->assertSee('data-fee-cancel', false);
    }

    public function test_enrollment_can_be_created_with_freeform_student_and_course_names(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('enrollments.store'), [
            'student_name' => 'Jane Doe',
            'course_name' => 'Web Design Basics',
            'enrollment_date' => '2026-11-16',
            'discount_applied' => '0.00',
            'completion_percentage' => '25',
        ]);

        $response->assertRedirect(route('enrollments.index'));
        $this->assertDatabaseHas('students', ['name' => 'Jane Doe']);
        $this->assertDatabaseHas('courses', ['title' => 'Web Design Basics']);
        $this->assertDatabaseHas('enrollments', [
            'student_id' => 1,
            'course_id' => 1,
            'completion_percentage' => 25,
        ]);
    }
}

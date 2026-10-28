<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Welcome back');
    }

    public function test_course_and_enrollment_pages_render(): void
    {
        $this->get(route('courses.index'))->assertOk()->assertSee('Course catalog');
        $this->get(route('courses.create'))->assertOk()->assertSee('Create course');
        $this->get(route('enrollments.index'))->assertOk()->assertSee('All enrollments');
        $this->get(route('enrollments.create'))->assertOk()->assertSee('Create enrollment');
    }
}

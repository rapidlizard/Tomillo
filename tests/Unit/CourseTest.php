<?php

namespace Tests\Unit;

use App\Course;
use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_total_students()
    {
        $course = factory(Course::class)->create();
        $users  = factory(User::class, 10)->create(['type' => 'Student']);
        foreach ($users as $user)
        {
            $course->users()->attach($user);
        }

        $totalStudents = $course->totalStudents();

        $this->assertEquals(10, $totalStudents);
    }

    public function test_count_total_male_students()
    {
        $course = factory(Course::class)->create();
        $maleStudents  = factory(User::class, 10)->create(['type' => 'Student', 'gender'=>'Hombre']);
        $femaleStudents  = factory(User::class, 10)->create(['type' => 'Student', 'gender'=>'Mujer']);
        $students = User::all();
        foreach ($students as $student)
        {
            $course->users()->attach($student);
        }

        $totalMales = $course->totalMaleStudents();

        $this->assertEquals(10, $totalMales);
    }

    public function test_count_total_female_students()
    {
        $course = factory(Course::class)->create();
        $maleStudents   = factory(User::class, 10)->create(['type' => 'Student', 'gender'=>'Hombre']);
        $femaleStudents = factory(User::class, 10)->create(['type' => 'Student', 'gender'=>'Mujer']);
        $students = User::all();
        foreach ($students as $student)
        {
            $course->users()->attach($student);
        }

        $totalFemales = $course->totalFemaleStudents();

        $this->assertEquals(10, $totalFemales);
    }

    public function test_count_total_other_students()
    {
        $course = factory(Course::class)->create();
        $maleStudents   = factory(User::class, 10)->create(['type' => 'Student', 'gender'=>'Hombre']);
        $femaleStudents = factory(User::class, 10)->create(['type' => 'Student', 'gender'=>'Mujer']);
        $otherStudents  = factory(User::class, 5)->create(['type' => 'Student', 'gender'=>'Otro']);
        $students = User::all();
        foreach ($students as $student)
        {
            $course->users()->attach($student);
        }

        $totalOther = $course->totalOtherStudents();

        $this->assertEquals(5, $totalOther);
    }

    public function test_calc_male_students_percentage()
    {
        $course = factory(Course::class)->create();
        $maleStudents   = factory(User::class, 4)->create(['type' => 'Student', 'gender'=>'Hombre']);
        $femaleStudents = factory(User::class, 4)->create(['type' => 'Student', 'gender'=>'Mujer']);
        $otherStudents  = factory(User::class, 2)->create(['type' => 'Student', 'gender'=>'Otro']);
        $students = User::all();
        foreach ($students as $student)
        {
            $course->users()->attach($student);
        }

        $malePercentage = $course->malePercentage();

        $this->assertSame(40, $malePercentage);
    }

    public function test_calc_female_students_percentage()
    {
        $course = factory(Course::class)->create();
        $maleStudents   = factory(User::class, 4)->create(['type' => 'Student', 'gender'=>'Hombre']);
        $femaleStudents = factory(User::class, 4)->create(['type' => 'Student', 'gender'=>'Mujer']);
        $otherStudents  = factory(User::class, 2)->create(['type' => 'Student', 'gender'=>'Otro']);
        $students = User::all();
        foreach ($students as $student)
        {
            $course->users()->attach($student);
        }

        $femalePercentage = $course->femalePercentage();

        $this->assertSame(40, $femalePercentage);
    }

    public function test_calc_other_students_percentage()
    {
        $course = factory(Course::class)->create();
        $maleStudents   = factory(User::class, 4)->create(['type' => 'Student', 'gender'=>'Hombre']);
        $femaleStudents = factory(User::class, 4)->create(['type' => 'Student', 'gender'=>'Mujer']);
        $otherStudents  = factory(User::class, 2)->create(['type' => 'Student', 'gender'=>'Otro']);
        $students = User::all();
        foreach ($students as $student)
        {
            $course->users()->attach($student);
        }

        $otherPercentage = $course->otherPercentage();

        $this->assertSame(20, $otherPercentage);
    }
}
<?php

namespace Tests\Unit;


use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Day;
use App\User;
use App\Course;


class DayTest extends TestCase
{
    use RefreshDatabase;
    const hour = 8; const minute = 00; const second = 0; const tz = 'Europe/Madrid';
    const endhour = 16; const endminute = 30; const endsecond = 0;
    public function test_set_date()
    {
        $date = Day::setDate();
        $this->assertNotNull($date);
    }

    public function test_set_time()
    {
        $time = Day::setTime();
        $this->assertNotNull($time);
    }

    public function test_student_can_check_in()
    {
        $student= factory(User::class)->create();

        $course = Course::create([
            'title' => 'Web Development',
            'description' => 'Full-Stack training',
            'start_date' => date("2020-01-01"),
            'end_date' => date("2020-01-31")
        ]);

        $course->users()->save($student);

        $FakeTodayCourseDay = Carbon::create(2020, 1, 2, 12);
        Carbon::setTestNow($FakeTodayCourseDay);

        $day= Day::checkIn($student);

        $this->assertNotNull($day);

        $this->assertNotNull($student->days);
        $this->assertNotNull($day->date);
        $this->assertNotNull($day->checkIn);
        $this->assertEquals($student->id, $day->user_id);
    }

    public function test_check_out()
    {
        $student = factory(User::class)->create();

        $course = Course::create([
            'title' => 'Web Development',
            'description' => 'Full-Stack training',
            'start_date' => date("2020-01-01"),
            'end_date' => date("2020-01-31")
        ]);

        $course->users()->save($student);

        $FakeTodayCourseDay = Carbon::create(2020, 1, 2, 12);
        Carbon::setTestNow($FakeTodayCourseDay);

        $day = Day::checkIn($student);

        $day->checkout();

        $this->assertNotNull($day->checkOut);
    }

    public function test_check_if_user_checked_out(){

        $student = factory(User::class)->create();

        $course = Course::create([
            'title' => 'Web Development',
            'description' => 'Full-Stack training',
            'start_date' => date("2020-01-01"),
            'end_date' => date("2020-01-31")
        ]);

        $course->users()->save($student);

        $FakeTodayCourseDay = Carbon::create(2020, 1, 2, 12);
        Carbon::setTestNow($FakeTodayCourseDay);

        $day = Day::checkIn($student);

        $this->assertFalse($day->checkIfCheckedOut());

        $day->checkOut();

        $this->assertTrue($day->checkIfCheckedOut());

    }

    public function test_check_if_checked_in_same_day()
    {
        $user = factory(User::class)->create();

        $assignedDay = factory(Day::class)->create();//creates a day instance of today
        $user->addDayToUser($assignedDay); //assigns the day to user

        $dayToCompare = factory(Day::class)->create(); //another day instance of today, not assigned
        /*
        $anotherDay = Day::create([
            'date' => '2021-03-03'
        ]);
        */

        //when we try to reach the days field of user before assigning any day by check-in, the user object anull itself
        $check1 = $dayToCompare->checkIfCheckedInSameDay($user);
        $this->assertTrue($check1);
    }

    public function test_time_worked_in_a_day()
    {
        $checkIn = Carbon::createFromTime(8, 0, 0, 'Europe/Madrid');
        $checkOut = Carbon::createFromTime(16, 30, 0, 'Europe/Madrid');

        $day = factory(Day::class)->create(['checkIn'=> $checkIn, 'checkOut'=> $checkOut]);
        $workedtimeinDay = $day->getTimeWorkedInADay();

        $this->assertEquals($workedtimeinDay['hours'], "8");
        $this->assertEquals($workedtimeinDay['minutes'], "30");
    }

    public function test_time_worked_in_a_course()
    {
        $start = Carbon::createFromTime(DayTest::hour, DayTest::minute, DayTest::second, DayTest::tz);
        $time = Carbon::createFromTime(DayTest::endhour, DayTest::endminute, DayTest::endsecond, DayTest::tz);
        $days = [];
        $day1 = factory(Day::class)->create(['date'=>"2020-01-01", 'checkIn' => $start, 'checkOut' => $time]);
        $day2 = factory(Day::class)->create(['date'=>"2020-01-02", 'checkIn' => $start, 'checkOut' => $time]);
        $day3 = factory(Day::class)->create(['date'=>"2020-01-03", 'checkIn' => $start, 'checkOut' => $time]);
        array_push($days, $day1, $day2, $day3);
        $totalWorkedTimeInCourse = Day::getWorkedHoursOfAStudentInACourse($days);

        $this->assertEquals(24, $totalWorkedTimeInCourse['Hours']);
        $this->assertEquals(30, $totalWorkedTimeInCourse['Minutes']);
    }

    public function test_check_if_today_corresponds_course_dates(){

        $course = Course::create([
            'title' => 'Web Development',
            'description' => 'Full-Stack training',
            'start_date' => date("2020-01-01"),
            'end_date' => date("2020-01-31")
        ]);

        $FakeTodayNoCourseDay = Carbon::create(2020, 6, 1, 12);
        Carbon::setTestNow($FakeTodayNoCourseDay);

        $check1 = Day::checkIfTodayCorrespondsCourseDates($course);
        $this->assertFalse($check1);

        $FakeTodayCourseDay = Carbon::create(2020, 1, 2, 12);
        Carbon::setTestNow($FakeTodayCourseDay);

        $check2 = Day::checkIfTodayCorrespondsCourseDates($course);
        $this->assertTrue($check2);

    }

    public function test_average_total_attended_days_all_students_in_course()
    {
        $attended_course_days = [];
        $course_start = date("2020-01-02");
        $course_end = date("2020-01-08");
        $users = factory(User::class, 2)->create(['type'=>'Student']);
        $day1_user1= factory(Day::class)->create(['date'=>'2020-01-02', 'user_id'=>$users[0]->id]);
        $day2_user1= factory(Day::class)->create(['date'=>'2020-01-03', 'user_id'=>$users[0]->id]);
        $day3_user1= factory(Day::class)->create(['date'=>'2020-01-06', 'user_id'=>$users[0]->id]);
        $day4_user1= factory(Day::class)->create(['date'=>'2020-01-07', 'user_id'=>$users[0]->id]);
        $day1_user2= factory(Day::class)->create(['date'=>'2020-01-02', 'user_id'=>$users[1]->id]);
        $day2_user2= factory(Day::class)->create(['date'=>'2020-01-06', 'user_id'=>$users[1]->id]);
        $day3_user2= factory(Day::class)->create(['date'=>'2020-01-08', 'user_id'=>$users[1]->id]);
        array_push($attended_course_days,$day1_user1, $day2_user1, $day3_user1, $day4_user1, $day1_user2,
            $day2_user2, $day3_user2);
        $course = Course::create([
            'title' => 'Web Development',
            'description' => 'Full-Stack training',
            'start_date' => $course_start,
            'end_date' => $course_end
        ]);

        foreach ($users as $user) {
            $course->users()->save($user);
        };
        $average_Attended_days = Day::average_Attended_Days_course($attended_course_days, $course);
        $attended_days = $average_Attended_days['Attended']; $absentDays = $average_Attended_days['Absent'];
        $this->assertEquals(4, $attended_days);
        $this->assertEquals(1, $absentDays);
    }
}

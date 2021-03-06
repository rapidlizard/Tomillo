<?php

namespace App;
use App\User;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'start_date', 'end_date'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function totalStudents()
    {
        $listOfStudents = $this->users()->where('type', '=', 'Student')->get();

        $totalNumberOfStudents = count($listOfStudents);

        return $totalNumberOfStudents;
    }

    public function totalMaleStudents()
    {
        $listOfStudents = $this->users()->where('type', '=', 'Student')->where('gender', '=', 'Hombre')->get();

        $totalNumberOfMaleStudents = count($listOfStudents);

        return $totalNumberOfMaleStudents;
    }

    public function totalFemaleStudents()
    {
        $listOfStudents = $this->users()->where('type', '=', 'Student')->where('gender', '=', 'Mujer')->get();

        $totalNumberOfFemaleStudents = count($listOfStudents);

        return $totalNumberOfFemaleStudents;
    }

    public function totalOtherStudents()
    {
        $listOfStudents = $this->users()->where('type', '=', 'Student')->where('gender', '=', 'Otro')->get();

        $totalNumberOfOtherStudents = count($listOfStudents);

        return $totalNumberOfOtherStudents;
    }

    public function checkIfThereIsNoStudent(){

        $totalStudents = $this->totalStudents();
        if($totalStudents==0){
            return True;
        }
        return False;
    }

    public function malePercentage()
    {
        if($this->checkIfThereIsNoStudent()){
            return 0;
        }

        $totalStudents = $this->totalStudents();
        $totalMaleStudents = $this->totalMaleStudents();

        $malePercentage = ($totalMaleStudents / $totalStudents) * 100;

        return intval($malePercentage);
    }

    public function femalePercentage()
    {
        if($this->checkIfThereIsNoStudent()){
            return 0;
        }

        $totalStudents = $this->totalStudents();
        $totalFemaleStudents = $this->totalFemaleStudents();

        $femalePercentage = ($totalFemaleStudents / $totalStudents) * 100;

        return intval($femalePercentage);
    }

    public function otherPercentage()
    {
        if($this->checkIfThereIsNoStudent()){
            return 0;
        }

        $totalStudents = $this->totalStudents();
        $totalOtherStudents = $this->totalOtherStudents();

        $otherPercentage = ($totalOtherStudents / $totalStudents) * 100;

        return intval($otherPercentage);
    }

    public function getRangeOfDates(){

        $begin= $this->start_date; 
        $end= $this->end_date; 

        $period = CarbonPeriod::create($begin, $end);

        $dates = $period->toArray();

        return $dates;
    }

    public static function excludeWeekendsFromRange(array $rangeOfDates){

        $OnlyWeekdays=array();
        foreach ($rangeOfDates as $date) {
            if($date->isWeekend()==False){
                array_push($OnlyWeekdays, $date);
            }
        }
        return $OnlyWeekdays;
    }
    public static function convertCarbonRangeIntoStringRange($CarbonRange){
       
        $StringRange=array();
        foreach ($CarbonRange as $date) {
            $formtattedDate= $date->format('Y-m-d');
            array_push($StringRange, $formtattedDate);
        }
        return $StringRange;
    }

    public function getCourseDaysAsCarbon(){

        $CompleteRange = $this->getRangeOfDates();
        $CourseDays = self::excludeWeekendsFromRange($CompleteRange);

        return $CourseDays;
    }

    public function getCourseDaysAsString(){

        $CourseDays = $this->getCourseDaysAsCarbon();

        $CourseDaysAsStrings = self::convertCarbonRangeIntoStringRange($CourseDays);

        return $CourseDaysAsStrings;
    }

    public function getRangeOfCourseDatesUntilNow(){

        $begin = $this->start_date; 

        $end = Carbon::now()->setTimezone('Europe/Madrid');

        if ($end->greaterThan($this->end_date)){
            $end = $this->end_date;
        }

        $period = CarbonPeriod::create($begin, $end);

        $dates = $period->toArray();

        return $dates;
    }

    public function getCourseDaysUntilNow()
    {
        $CompleteRange = $this->getRangeOfCourseDatesUntilNow();

        $CourseDaysUntilToday = self::excludeWeekendsFromRange($CompleteRange);

        $CourseDaysUntilTodayAsString = self::convertCarbonRangeIntoStringRange($CourseDaysUntilToday);

        return $CourseDaysUntilTodayAsString;
    }
}

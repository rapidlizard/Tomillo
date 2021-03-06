<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Day as DayResource;

class User extends JsonResource
{
    public function toArray($request)
    {
        if($this->type == "Student") {
            if($this->studentCourse() != null){
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'email' => $this->email,
                    'type' => $this->type,
                    'gender' => $this->gender,
                    'nationality' => $this->nationality,
                    'age' => $this->age(),
                    'date_of_birth' => $this->date_of_birth,
                    'course' => $this->studentCourse(),
                    'email_verified_at' => $this->email_verified_at,
                    'justifications' => $this->justifications,
                    'assistedDates' => DayResource::collection($this->getAssistedDays()),
                    'assistedDays'=> $this->calculateAssistedDays(),
                    'absentDays'=> $this->calculateAbsentDays(),
                    'absentDates'=> $this->getAbsentDays(),
                    'justifiedDays'=> $this->calculateJustifiedDays(),
                    'canCheckIn' => $this->checkIfCanCheckIn(),
                ];
            }
            return [
                'id' => $this->id,
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'type' => $this->type,
                'gender' => $this->gender,
                'nationality' => $this->nationality,
                'age' => $this->age(),
                'date_of_birth' => $this->date_of_birth,
                'email_verified_at' => $this->email_verified_at,
                'justifications' => $this->justifications,
                'assistedDays'=> "Este estudiante no tiene un curso asignado",
                'absentDays'=> "Este estudiante no tiene un curso asignado",
                'justifiedDays'=> "Este estudiante no tiene un curso asignado",
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'type' => $this->type,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'age' => $this->age(),
            'date_of_birth' => $this->date_of_birth,
            'course' => $this->courses,
            'email_verified_at' => $this->email_verified_at,
        ];
    }
}

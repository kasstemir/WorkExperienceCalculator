<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WorkExperience extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'start_date',
        'end_date',
        'organization',
        'position',
    ];

    public function index(){
        return WorkExperience::all();
    }

    public function add(Request $req){
        $data = $req->all();
        $firstId = 0;
        $lastId = 0;
        foreach ($data as $experience){
            $validatedData = validator($experience,[
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'organization' => 'required|string|max:255',
                'position' => 'required|string|max:255',
            ])->validate();

            if($validatedData) {
                $workExperience = WorkExperience::create($validatedData);
                if ($firstId==0) {
                    $firstId = $workExperience->id;
                }
                $lastId = $workExperience->id;
            }
        }

        $startArray = WorkExperience::whereBetween('id', [$firstId, $lastId])->pluck('start_date')->toArray();
        $endArray = WorkExperience::whereBetween('id', [$firstId, $lastId])->pluck('end_date')->toArray();

        return $this->calculate($startArray,$endArray);
    }

    public function calculate($startArray, $endArray){
        $sortedDates = $this->sortDates($startArray, $endArray);
        $startArray = $sortedDates[0];
        $endArray = $sortedDates[1];

        $totalMonths = 0;
        $previousEndMonth = 0;
        for($i = 0; $i<count($startArray); $i++){
            $start = explode('-',$startArray[$i]);
            $end = explode('-',$endArray[$i]);

            $startMonth = (int)$start[0]*12+(int)$start[1]; //sum of months;
            $endMonth = (int)$end[0]*12+(int)$end[1];

            if($previousEndMonth>$startMonth) //checking for parallel working experience
                $startMonth = $previousEndMonth;
            if($previousEndMonth<$endMonth)
                $previousEndMonth = $endMonth;

            if($endMonth>$startMonth)
                $totalMonths += $endMonth - $startMonth;
        }
        return "Work Experience in Months : ".$totalMonths;
    }

    //sorting Dates by starting_date
    public function sortDates($startArray, $endArray){
        for($i = 0; $i<count($startArray)-1; $i++){
            for($j = 0; $j<count($startArray)-1; $j++){
                if($startArray[$j]>$startArray[$j+1]){
                    $temp = $startArray[$j];
                    $startArray[$j] = $startArray[$j+1];
                    $startArray[$j+1] = $temp;

                    $temp1 = $endArray[$j];
                    $endArray[$j] = $endArray[$j+1];
                    $endArray[$j+1] = $temp1;
                }
            }
        }
        return [$startArray,$endArray];
    }
}

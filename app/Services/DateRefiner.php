<?php

namespace App\Services;


use Carbon\Carbon;

class DateRefiner
{
    public static function datePassRefine(Carbon $date, $refine = null)
    {
        if(!is_array($refine) || in_array('all', $refine) || count($refine) === 0) return true;

        $currentIs = array_keys(array_filter([
            'mondays' => $date->isMonday(),
            'thursdays' => $date->isThursday(),
            'fridays' => $date->isFriday(),
            'wednesdays' => $date->isWednesday(),
            'tuesdays' => $date->isTuesday(),
            'saturdays' => $date->isSaturday(),
            'sundays' => $date->isSunday(),
            'weekdays' => $date->isWeekday(),
            'weekends' => $date->isWeekend(),
        ], function($value){
            return $value;
        }));

        return count(array_intersect($refine, $currentIs)) > 0;
    }

}
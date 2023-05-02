<?php

namespace App\Models;

use Carbon\Carbon;



class SettingPayroll extends Model
{
    protected $fillable = [
        'payroll',
        'workhours_sunday',
        'workhours_monday',
        'workhours_tuesday',
        'workhours_wednesday',
        'workhours_thusday',
        'workhours_friday',
        'workhours_saturday',
    ];

    public static function getdays($dates)
    {
        $daynames = Carbon::parse($dates)->format('l');
        $setting = SettingPayroll::first();
        $workhours = 0;
        switch ($daynames) {
            case ("Sunday"):
                $workhours = $setting->workhours_sunday;
                break;
            case ("Tuesday"):
                $workhours = $setting->workhours_tuesday;
                break;
            case ("Wednesday"):
                $workhours = $setting->workhours_wednesday;
                break;
            case ("Thusday"):
                $workhours = $setting->workhours_thusday;
                break;
            case ("Friday"):
                $workhours = $setting->workhours_friday;
                break;
            case ("Saturday"):
                $workhours = $setting->workhours_saturday;
                break;
            default:
                $workhours = $setting->workhours_monday;
                break;
        }
        return $workhours;
    }
}
<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Zone;

class ParkingService{

    public static function calculatePrice(int $zone_id, string $start_time, string $stop_time = null):int
    {

        $start = new Carbon($start_time);

        $stop = (!is_null($stop_time)) ? new Carbon($stop_time) : now();

        $totalTimeByMinutes = $stop->diffInMinutes($start);

        $priceByMinutes = Zone::find($zone_id)->price_per_hour / 60;

        return ceil($totalTimeByMinutes * $priceByMinutes);

    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageTracker extends Model
{

  protected $fillable = ['inmate_id', 'service_id', 'date', 'usage'];
  protected $table = 'freeservice_usage';

  public static function addMinute($inmate, $service) {
    $today = UsageTracker::where('inmate_id', $inmate)
      ->where('service_id', $service)
      ->where('date', date("Y-m-d"))
      ->get()->first();
    if(is_null($today)) {
      UsageTracker::create([
        'inmate_id' => $inmate,
        'service_id' => $service,
        'date' => date("Y-m-d"),
        'usage' => 1
      ]);
      $usage = 1;
    } else {
      $usage = $today->usage++;
      $today->update(['usage',  $usage]);
    }
    return intval($usage);
  }
}

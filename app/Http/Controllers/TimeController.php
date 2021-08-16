<?php

namespace App\Http\Controllers;

use App\Device;
use App\Facility;
use App\FreeMinute;
use App\InmateActivityHistory;
use App\Service;
use Illuminate\Http\Request;
use App\FlatRateServices;
use App\User;
use App\UsageTracker;

class TimeController extends Controller
{
  public function checkFlatPaid(Request $request) {
    $flat = FlatRateServices::where('user_id', $request->inmate_id)
      ->where('service_id', $request->service_id)
      ->get();

    return [
      "Status" => "Success",
      "Code" => 200,
      "Message" => "Check balance for paid service",
      "Data" => ['paid' => $flat->count() > 0]
    ];
  }

  public function checkBalanceForPaid(Request $request) {
    $balance = User::where('id', $request->inmate_id)
      ->first();

    return [
      "Status" => "Success",
      "Code" => 200,
      "Message" => "Check enough balance",
      "Data" => [
        'enough' => $balance->balance >= $request->cost,
        'balance' => $balance->balance
      ]
    ];
  }

  public function freeserviceminute(Request $request) {
    $usage = UsageTracker::addMinute($request->inmate_id, $request->service_id);

    return [
      "Status" => "Success",
      "Code" => 200,
      "Message" => "Free service minute for usage statistic",
      "Data" => [
        "time_used" => $usage
      ]
    ];
  }

  private function getFacilityRate($id) {
    $dev_id = User::where('id', $id)->select('device_id')->get()->first();
    $fac_id = Device::where('id', $dev_id->device_id)->select('facility_id')->get()->first();
    $charge = Facility::where('id', $fac_id->facility_id)->select('tablet_charge')->get()->first();
    return $charge->tablet_charge;
  }

  private function getFreeMinutes($id) {
    $left = FreeMinute::where('inmate_id', $id)->select('left_minutes')->get()->first();
    return $left->left_minutes;
  }

  private function spendFacilityMinute($user) {
    $rate = $this->getFacilityRate($user->id);
    $free_min = $this->getFreeMinutes($user->id);
    if($free_min > 0) {
      $free_min--;
      FreeMinute::where('inmate_id', $user->id)->update(["left_minutes" => $free_min]);
      return [
        "Status" => "Success",
        "Code" => 200,
        "Message" => "Free service minute",
        "Data" => [
          "type" => "free",
          "free_minutes_left" => $free_min,
          "balance" => $user->balance
        ]
      ];
    }
    $balance = $user->balance - $rate;
    $user->update(['balance' => $balance]);

    return [
      "Status" => "Success",
      "Code" => 200,
      "Message" => "Facility service minute",
      "Data" => [
        "type" => "facility",
        "free_minutes_left" => $free_min,
        "balance" => $balance
      ]
    ];
  }

  private function spendAdditionalMinute($user, $service) {
    $rate = $this->getFacilityRate($user->id);
    $free_min = $this->getFreeMinutes($user->id);
    $additional_rate = $service->charge;
    $balance = $user->balance - $rate - $additional_rate;
    $user->update(['balance' => $balance]);

    return [
      "Status" => "Success",
      "Code" => 200,
      "Message" => "Premium service minute",
      "Data" => [
        "type" => "premium",
        "free_minutes_left" => $free_min,
        "balance" => $balance
      ]
    ];
  }

  public function spendminute(Request $request) {
//      $dev_id = User::where('inmate_id', $request->inmate_id)->select('device_id')->get()->first();
//      $fac_id = Device::where('id', $dev_id->device_id)->select('facility_id')->get()->first();
//      $charge = Facility::where('facility_id', $fac_id->facility_id)->select('tablet_charge')->get()->first();
//      return $fac_id;

    $service = Service::where('id', $request->service_id)->get()->first();
    $user = User::where('id', $request->inmate_id)->get()->first();

    switch($service->type) {
      // type 1 = facility charge
      case('1'):
        return $this->spendFacilityMinute($user);
      // type 2 = premium service
      case('2'):
        return $this->spendAdditionalMinute($user, $service);
      case('0'):
        return [
          "Status" => "Error",
          "Code" => 200,
          "Message" => "Free service",
          "Data" => ['error' => 'Service is free']
        ];
      default:
        return [
          "Status" => "Error",
          "Code" => 200,
          "Message" => "Wrong type of service",
          "Data" => ['error' => 'Wrong service type']
        ];
    }
  }

  public function endsession(Request $request) {
      $history = InmateActivityHistory::where('inmate_id', $request->inmate_id)
          ->where('service_id', $request->service_id)
          ->orderByDesc('id')
          ->get()
          ->first();

      $history->update([
          'exit_reason' => $request->exit_reason,
          'end_datetime' => $request->end_datetime
      ]);

    return [
      "Status" => "Success",
      "Code" => 200,
      "Message" => "End Session",
      "Data" => [
        "message" => "ok"
      ]
    ];
  }
}

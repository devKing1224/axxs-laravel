<?php

/**
 * Function for get all information behalf on logged user.
 * 
 * @return type array of user inforamtion as well as all details information. 
 * 
 * 
 */
function loggedInUser() {
    $user = null;
    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role_id) {
            case 2:
                $facilityInfo = \App\Facility::where('facility_user_id', $user->id)->first();
                $roleInfo = \App\Role::where('id', $user->role_id)->first();
                $user->detail = $facilityInfo;
                $user->roleDetail = $roleInfo;
                break;
            case 3:
                $familyInfo = \App\Family::where('family_user_id', $user->id)->first();
                $roleInfo = \App\Role::where('id', $user->role_id)->first();
                $user->detail = $familyInfo;
                $user->roleDetail = $roleInfo;
                break;
                
             case 4:
                break;
            default:
                $adminInfo = \App\User::where('id', $user->id)->first();
                $roleInfo = \App\Role::where('id', $user->role_id)->first();
                $user->detail = $adminInfo;
                $user->roleDetail = $roleInfo;
                break;
        }
    } else {
        
    }
    return $user;
}

function getDiffrentServicesID() {
    return \App\Service::whereRaw('base_url LIKE "%sendsms%" OR base_url LIKE "%sendemail%"')
                    ->select('id')->get()->pluck('id')->toArray();
}

function getEmailServicesID() {
    return \App\Service::where('base_url', 'LIKE', '%sendemail%')
                    ->select('id')->first();
}

function getSMSServicesID() {
    return \App\Service::where('base_url', 'LIKE', '%sendsms%')
                    ->select('id')->first();
}

function phone_number_format($num) {
  // Allow only Digits, remove all other characters.
  $number = preg_replace("/[^\d]/","",$num);
  // get number length.
  
  $length = strlen($number);
 // if number = 10
 if($length == 10) {
  $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
 }
 if($length == 11 || $length == 12) {
  $newstring = substr($number, -10);
  $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $newstring);
 }
 
  return $number;
 
}

function errorLog(\Exception $ex) {
    $errorString = PHP_EOL . '*******************Error File Name**********************' . PHP_EOL
            . $ex->getFile() . PHP_EOL
            . '*******************Error Line Number**********************' . PHP_EOL
            . $ex->getLine() . PHP_EOL
            . '*******************Error Message **********************' . PHP_EOL
            . $ex->getMessage() . PHP_EOL
            . '********************End Error Log ***********************';

    return \Log::info($errorString);
}

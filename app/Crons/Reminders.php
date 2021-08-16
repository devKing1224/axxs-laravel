<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Crons;

use App\User;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;



/**
 * Description of Reminders
 *
 * @author ankitp
 */
class Reminders {
   
    /**
    *  function for reminder in case of inmate balance low
    * 
    * @param type integer $family_id
    * 
    * retrun array information for inmate and his balance regarding
    */
    public function getReminderInmateBalanceLow() {
        $obj_payment = new User();
        $inmateAndFamilyInformation = $obj_payment->getinmateListLowBalanceInfo(config('axxs.active'));//
        //return $inmateAndFamilyInformation;
        if(count($inmateAndFamilyInformation) > 0) {
            foreach($inmateAndFamilyInformation as $value) {
                $emailSendingStatus = $this->sendReminderMail($value);
                if($emailSendingStatus == true) {
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
        }
    }
    
    /**
    *  function for send email as per reminder
    * 
    * @param type string $family_email_id
    * 
    * retrun array information for inmate and his balance regarding
    */
    function sendReminderMail($inmateValue) {
        $inmateBalance = !empty($inmateValue->admin->balance) ? $inmateValue->admin->balance : '';
        $inmateName = !empty($inmateValue->admin->inmate_name) ? $inmateValue->admin->inmate_name : '';
        $content = [
                    'title'=> 'Balance low reminder', 
                    'body'=> 'Your inmate('.$inmateName.') avaliable balance is ($'.$inmateBalance.'). please recharge his account',
                ];
        try {
            $receiverAddress = $inmateValue->email;
            $var = Mail::to($receiverAddress)->send(new OrderShipped($content));
            return true;
        }
        catch (\Exception $e) {
            return 'error';
        }
    }
    
}

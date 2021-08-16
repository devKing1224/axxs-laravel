<?php

/**
 * Controller for taking handling recieved mail and andoir UI 
 * 
 * PHP version 7.2
 * 
 * @category Recievedmailcontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Client;
use Illuminate\Support\Facades\Redirect;
use App\RecievedInmateEmail;
use App\InmateContacts;
use App\User;
use App\InmateDetails;
use App\PreApprovedEmail;
use App\IncomingMail;
use Session;
use Lang;

class RecievedMailController extends Controller {

    /**
     * Function for fetching the latest inbox emails and save in to database 
     *
     * @param object Request $inmate_id for Inmate ID
     *                                
     * @return null
     */
    public function getInboxMail($inmate_id) {

        /** @var get date of lastly updated email */
        $allemails = RecievedInmateEmail::orderBy('recieved_time', 'DSC')->where('inmate_id', $inmate_id)->select('recieved_time')->first();

        $inmatedetails = InmateDetails::where('inmate_id', $inmate_id)->first();
        if ($allemails) {
            $last_emailrecieved_at = $allemails->recieved_time;
        } else {
            $last_emailrecieved_at = '2018-05-01 10:00:00';
        }


        $oClient = new Client([
            'host' => 'gator4271.hostgator.com',
            'port' => 993,
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => $inmatedetails->email,
            'password' => $inmatedetails->password,
        ]);

        try { /* Connect to the IMAP Server */
            $oClient->connect();

            //Get all Mailboxes
            $aFolder = $oClient->getFolders();
            foreach ($aFolder as $oFolder) {
                /**Get all Messages of the current Mailbox $oFolder */
                $aMessage = $oFolder->getMessages();

                /** @var \Webklex\IMAP\Message $oMessage */
                  foreach ($aFolder as $oFolder) {
                /**Get all Messages of the current Mailbox $oFolder */
                $aMessage = $oFolder->getMessages();

                /** @var \Webklex\IMAP\Message $oMessage */
                foreach ($aMessage as $oMessage) {
                    $userId = false;
                    if ($oMessage->getDate() > $last_emailrecieved_at) {
                        $inmateid = InmateContacts::where('email_phone', '=', $oMessage->from[0]->mail)
                                        ->where('inmate_id', $inmate_id)
                                        ->where('is_approved', 1)
                                        ->select('inmate_id', 'facility_id')->first();
                        if($inmateid){
                            $userId = $inmateid->inmate_id; 
                        }else{
                            $inmate_details = User::where('id', $inmate_id)->first();
                            $preapproved = PreApprovedEmail::where('email_phone', '=', $oMessage->from[0]->mail)
                                            ->where('facility_id', $inmate_details->admin_id)
                                            ->where('status', 0)->first();
                            if($preapproved){
                                $userId = $inmate_id;
                            }
                        }

                        if ($userId) {
                            $saveemails = RecievedInmateEmail::create([
                                        'recieved_time' => $oMessage->getDate(),
                                        'inmate_id' => $userId,
                                        'subject' => $oMessage->subject,
                                        'from_name' => $oMessage->from[0]->personal,
                                        'from_email' => $oMessage->from[0]->mail,
                                        'message' => $oMessage->getHTMLBody(true),
                            ]);
                        }
                    }
                }
            }
            }

            return Redirect::back();
        } catch (\Exception $e) {
            Session::flash('message', Lang::get('email.contact_faility'));
            return Redirect::back();
        }
    }

    /**
     * Function for inmate to view his inbox emails via android device
     *
     * @param object Request $inmate_id  for Inmate ID
     *                       $service_id for service ID
     *                                
     * @return null
     */
    public function viewInmateInboxMail($inmate_id, $service_id) {

        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $inboxmail = \App\IncomingMail::where('to_inmateid',$inmate_id)
                    ->where('status',1)
                    ->where('is_deleted','!=',1)
                    ->orderBy('created_at', 'DSC')
                    ->get();

            return View('emails.recievedmails', array('inboxmail' => $inboxmail, 'inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details));
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function for moving inbox email to trashed folder
     *
     * @param object Request $inmate_id  for Inmate ID
     *                       $service_id for service ID
     *                       $mail_id for mail
     *                       $delete may be 1 for deleted and 0 for undelete
     *                                
     * @return null
     */
    public function deleteInmateInboxMail($inmate_id, $service_id, $mail_id, $delete) {
        $objEmails = new IncomingMail();
        $objEmails->deleteEmail($mail_id, $delete);

        $inmate_details = User::where('id', $inmate_id)->first();

        $inboxmail = IncomingMail::with('inmate')
                ->where('is_deleted', 0)
                ->where('to_inmateid', $inmate_id)
                ->orderBy('created_at', 'DSC')
                ->get();

        return View('emails.recievedmails', array('inboxmail' => $inboxmail, 'inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details));
    }

    /**
     * Function for moving sent email to trashed folder
     *
     * @param object Request $inmate_id  for Inmate ID
     *                       $service_id for service ID
     *                       $mail_id for mail
     *                                
     * @return null
     */
    public function deleteInmateSentMail($inmate_id, $service_id, $mail_id) {

        $objEmails = new SentInmateEmail();
        $objEmails->deleteEmail($mail_id);

        $inmate_details = User::where('id', $inmate_id)->first();
        $inboxmail = SentInmateEmail::with('inmate')
                ->where('is_deleted', 0)
                ->where('inmate_id', $inmate_id)
                ->orderBy('recieved_time', 'DSC')
                ->get();

        return View('emails.recievedmails', array('inboxmail' => $inboxmail, 'inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details));
    }

    /**
     * Function for showing inmate all deleted emails
     *
     * @param object Request $inmate_id  for Inmate ID
     *                       $service_id for service ID
     *                       $mail_id for mail
     *                                
     * @return null
     */
    public function viewInmateDeletedMail($inmate_id, $service_id) {

        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $type = 'trash';
            $trashmail = IncomingMail::with('inmate')
                    ->where('is_deleted', 1)
                    ->where('to_inmateid', $inmate_id)
                    ->orderBy('updated_at', 'DSC')
                    ->get();

            return View('emails.trashmails', array('trashmail' => $trashmail, 'inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'type' => $type));
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function for moving recieved email to move trash 
     *
     * @param object Request $request  keyed to mail id
     *                                
     * @return null
     */
    public function deleteEmail(Request $request) {
        $data = $request->id;
        $objEmails = new RecievedInmateEmail();
        $deleteEmails = $objEmails->deleteEmail($data);
        if (isset($deleteEmails) && !empty($deleteEmails)) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => \Lang::get('service.email_delete_success'),
            ));
        } else {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => \Lang::get('service.email_delete_unsuccess')
            ));
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OrderShipped;
use App\Mail\InmateNewEmail;
use Mail;
use App\SentInmateEmail;
use Validator;
use App\User;
use Auth;
use App\InmateChargesHistory;
use Lang;
use App\InmateContacts;
use App\Mail\ForwardEmail;
use App\InmateDetails;
use App\InmateConfiguration;
use App\InmateSetMaxContact;
use App\RecievedInmateEmail;
use App\BlackListedWord;
use App\InmateSMS;
use App\PreApprovedEmail;
use App\BlockContact;
use App\Facility;

/**
 * To handle Emails details and send to user family
 * @category SendMailController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */
class SendMailController extends Controller {

    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMail(Request $request) {
        $data = $request->input();

        $content = [
            'title' => $data['title'],
            'body' => $data['body'],
        ];

        $receiverAddress = $data['to'];
        $var = Mail::to($receiverAddress)->send(new OrderShipped($content));

        return print($var);
    }

    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendInmateMail(Request $request) {

        $data = $request->input();
        $objBlackListedWord = new BlackListedWord(); 
        $blackListedWords = $objBlackListedWord->getBlacklistedWord();
        $rules = array(
            'to' => 'required|email',
            'body' => 'required',
            'title' => 'required'
        );

        $messages = [
            'to.email' => 'Email ID is required.',
            'to.required' => 'Email ID is required.',
            'title.required' => 'Please mention a subject',
            'body.required' => 'Email body is empty.',
        ];

        $validate = Validator::make($data, $rules, $messages);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            $username= User::find($data['inmate_id']);
            $inmateEmail = $username->email;
            $removeEmail = BlockContact::where('email', $data['to'])->exists();
            if ($inmateEmail && empty($removeEmail)) {
                try {

                    
                    $content = [
                        'title' => !empty($data['title']) ? $data['title'] : '',
                        'body' => !empty($data['body']) ? $data['body'] : '',
                        'from' => $inmateEmail,
                        'name' => ucwords($username->first_name.' '.$username->last_name),
                        'email'=>$data['to']
                     
                    ];

                    $receiverAddress = $data['to'];
                    $var = Mail::to($receiverAddress)->send(new InmateNewEmail($content));
                   
                    if (count(Mail::failures()) > 0) {

                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => Lang::get('email.email_not_send'),
                        ));
                    } else {
                         $str = $data['body'];
                         $blacklisted = 0;
                            foreach ($blackListedWords as $blackListedWord) {
                                    $blacklisted_word = $blackListedWord->blacklisted_words;
                                if (stripos($str, $blacklisted_word) !== false) {
                                    $blacklisted = 1;
                                   
                                } 
                            }

                        $data['configuration_id'] = config('axxs.email_charges');
                        $objInmateChargesHistory = new InmateChargesHistory();
                        $objInmateChargesHistory->chargesService($data);
                        
                        $sendMailDataInsert = SentInmateEmail::Create([
                                    'inmate_id' => $data['inmate_id'],
                                    'to' => $data['to'],
                                    'subject' => $data['title'],
                                    'body' => $data['body'],
                                    'blacklisted' => $blacklisted,
                                    'is_deleted' => config('axxs.active')
                        ]);
                        if ($sendMailDataInsert) {
                            return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 200,
                                        'Message' => Lang::get('email.email_send'),
                            ));
                        }
                    }
                } catch (\Exception $e) {
                    errorLog($e);
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('email.email_not_send'),
                    ));
                }
            } else {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => Lang::get('email.email_removed'),
                ));
            }
        }
    }

    /**
     * Function for load inmate send email list UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
     */
    public function sentInmateEmailListUI(Request $request) {
        $inmate = User::where('id', $request->id)->where('role_id', 4)->first();
        $validateReturn = new User;
        $isValidate = $validateReturn->validateInmateStaffFacility($inmate, Auth::user());
        if ($isValidate) {
            $emaillist = new SentInmateEmail();
            $alleamil = $emaillist->getInmateEmailList($request->id);
            $sentEmailList = SentInmateEmail::where('inmate_id', $request->id)
                    ->get();
        } else {
            return redirect(route('inmate.inmatelist'));
        }

        return View('sentinmateemaillist', ['sentEmailList' => $sentEmailList, 'alleamil' => $alleamil]);
    }

    /**
     * Create function for Get inmate email details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id,
     * 
     * @return Json inmate information return in response
     */
    public function getInmateEmail(Request $request) {
        $data = $request->input();

        $rules = array(
            'inmateEmailID' => 'required',
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            if ($data['inmateEmailType'] == 1) {
                $inmateEmailInfo = sentInmateEmail::where('id', $data['inmateEmailID'])->get();
            } else {
                $inmateEmailInfo = \App\IncomingMail::where('id', $data['inmateEmailID'])
                        ->select('from as to', 'plain as body', 'subject')
                        ->get();
            }

            if (count($inmateEmailInfo) > 0) {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('inmate.inmate_details'),
                            'Data' => $inmateEmailInfo
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('inmate.inmate_not_found')
                ));
            }
        }
    }

    /**
     * Function to send Email UI
     *
     * @param object $inamte_id $service_id The inmate id keyed inmate_id and service_id.
     * 
     * @return view
     */
    public function sendEmailUI($inmate_id, $service_id) {

        $inmateemaildetails = User::where('id', $inmate_id)->first();
        
        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            if ($inmateemaildetails->email != null) {
                $emailCharge = InmateConfiguration::where('id', 3)
                                ->select('value')->first();
                $limitstart = new InmateSetMaxContact;
                $limitinfo = $limitstart->FetchInmateEmailLimit($inmate_id)['max_email'];
                $limitleft = $limitstart->LimitLeftForEmail($inmate_id);
                $contactnumber = InmateContacts::where('inmate_id', $inmate_id)
                        ->where('type', 'email')
                        ->where('is_deleted', '0')
                        ->where('is_approved', '1')
                        ->where('varified', '1')
                        ->select('name','email_phone')
                        ->get()->toArray();

                  $preApprovedContacts = PreApprovedEmail::select('name','email_phone')->where('facility_id', $inmate_details->admin_id)
                                        ->where('is_deleted', 0)
                                        ->where('status', 0)
                                        ->get()->toArray();

                $output = array_merge($contactnumber, $preApprovedContacts);

                     $emails = array();

                foreach ($output as $row) {

                     $emails[$row['email_phone']] = $row;
                }

                $array = array_values($emails);   
                
                $facility = new Facility;
                $facility_charge = $facility->getFacilityEmailSMSChargeByInmateID($inmate_id);
                //SMS charge per facility
                if (isset($facility_charge->email_charges) && $facility_charge->email_charges >= 0) {
                    $emailCharge->value = $facility_charge->email_charges;
                }

                return View('sendemail', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'emailCharge' => $emailCharge->value, 'contactnumber' => $emails, 'limitinfo' => $limitinfo, 'limitleft' => $limitleft, 'inmate_details' => $inmate_details, 'inmateemaildetails' => $inmateemaildetails]);
            } else {
                $message = Lang::get('inmate.Email_not_assigned');
                return view('emails.varifyemail', array('message' => $message));
            }
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function for providing email details to Individual Inmate
     *                           that has been sent by inmate
     * @param object $inamte_id $service_id The inmate id keyed inmate_id and service_id.
     * @return view
     */
    public function sentEmailDetail($inmate_id, $service_id) {

        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $inmateSentEmails = sentInmateEmail::where('inmate_id', $inmate_id)->orderBy('created_at', 'desc')->get();

            return View('inmate.sentemail', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'inmateSentEmails' => $inmateSentEmails]);
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function for ignore blacklisted word from email text.
     *                          
     * @param object $id 
     * @return view
     */
    public function ignoreblacklisted(Request $request) {
        $data = $request->input(); 
        $contact = SentInmateEmail::find($data['id']);
        if ($contact->is_ignored == 0 && $contact->is_deleted == 0 && $contact->blacklisted == 1) {
            $contact->is_ignored = 1;
             $contact->save();
                return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 201
            ));
            } else {
                 return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 400
             
            ));
        }
       
    }

       /**
     * Function for ignore blacklisted word from sms text.
     *                          
     * @param object $id 
     * @return view
     */

    public function ignoreblacklistedSms(Request $request) {
        $data = $request->input(); 
        $contact = InmateSMS::find($data['id']);
        if ($contact->is_ignored == 0 && $contact->is_deleted == 0 && $contact->blacklisted == 1) {
            $contact->is_ignored = 1;
             $contact->save();
                return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 201
            ));
            } else {
                 return response()->json(array(
                'Status' => \Lang::get('common.success'),
                'Code' => 400
             
            ));
        }
       
    }


    /**
     * Function for Inmat6e to view their Inbox Email
     *                         
     * @param object $inmate_id keyed for Inmate id
     * @param object $service_id keyed service_id
     * @param object $mail_id keyed mail id
     * 
     * @return view
     */
    public function forwardEmail(Request $request) {
           
        $data = $request->input(); 
        $inmate_id = $data['inmate_id'];
        $mail_id = $data['mail_id'];
        try {

            $contactEmail = InmateContacts::where('inmate_id', $inmate_id)
                    ->where('type', 'email')
                    ->where('is_deleted', '0')
                    ->where('is_approved', '1')
                    ->where('varified', '1')
                    ->select('name','email_phone')
                    ->get()->toArray();;

            $inmate_details = User::where('id', $inmate_id)->first();

             $inboxmail = \App\IncomingMail::where('id', $mail_id)
                                        ->where('is_deleted', 0)
                                   ->first();
                                 
             $preApprovedContacts = PreApprovedEmail::select('name','id','email_phone','varified','is_approved')->where('facility_id', $inmate_details->admin_id)
                   ->where('is_deleted', 0)
                   ->where('status', 0)
                    ->get()->toArray();

        $output = array_merge($contactEmail, $preApprovedContacts);

             $emails = array();

        foreach ($output as $row) {

             $emails[$row['email_phone']] = $row;
             $emails[$row['name']] = $row;
        }




            if (count($contactEmail) > 0) {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('inmate.inmate_details'),
                            'Data' => $output,
                            'selectedEmail' =>$inboxmail['from'],
                            'selectedname' =>$inboxmail['name'],
                          
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => \Lang::get('inmate.inmate_not_found'),
                ));
            }
        } catch (Exception $ex) {

            return response()->json(array(
                        'Status' => \Lang::get('common.failure'),
                        'Code' => 400,
                        'Message' => \Lang::get('inmate.inmate_not_found'),
                        'Data' => $ex
            ));
        }
    }

    /**
     * Function for Inmat6e to view their Inbox Email
     *                         
     * @param object $inmate_id keyed for Inmate id
     * @param object $service_id keyed service_id
     * @param object $mail_id keyed mail id
     * 
     * @return view
     */
    public function inboxEmailView($inmate_id, $service_id, $mail_id) {
        $type = 'inbox';
        $emailCharge = InmateConfiguration::where('id', 3)
                                ->select('value')->first();
        $maildetails = \App\IncomingMail::with(['attachments' =>function($query){
          $query->where('status',1);
        },'fac_attach'])->where('id', $mail_id)->first();
        $inmate_details = User::where('id', $inmate_id)->first();
        RecievedInmateEmail::where('id', $mail_id)->update(array('is_viewed' => 1));
        return View('emails.inboxemail', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'maildetails' => $maildetails, 'type' => $type, 'emailCharge' =>$emailCharge]);
    }

    /**
     * Function for Inmat6e to view their Inbox Email
     *                         
     * @param object $inmate_id keyed for Inmate id
     * @param object $service_id keyed service_id
     * @param object $mail_id keyed mail id
     * 
     * @return view
     */
    public function generalEmailView($inmate_id, $service_id, $mail_id) {
        $type = 'inbox';
        $emailCharge = InmateConfiguration::where('id', 3)
                                ->select('value')->first();
        $maildetails = \App\IncomingMail::with(['attachments' =>function($query){
          $query->where('status',1);
        },'fac_attach'])->where('id', $mail_id)->first();
        $inmate_details = User::where('id', $inmate_id)->first();
        RecievedInmateEmail::where('id', $mail_id)->update(array('is_viewed' => 1));
        $generalemail = 1;
        return View('emails.generalemail', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'maildetails' => $maildetails, 'type' => $type, 'emailCharge' =>$emailCharge,'generalmail' => $generalemail ]);
    }

    /**
     * Function for Inmate to view their Sent Email
     *                         
     * @param object $inmate_id keyed for Inmate id
     * @param object $service_id keyed service_id
     * @param object $mail_id keyed mail id
     * 
     * @return view
     */
    public function SentmailView($inmate_id, $service_id, $mail_id) {
        $maildetails = SentInmateEmail::where('id', $mail_id)->first();
        $inmate_details = User::where('id', $inmate_id)->first();
        return View('emails.sentboxemail', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'maildetails' => $maildetails]);
    }

    /**
     * Function for Inmate to forward the recieved email
     *                         
     * @param object $inmate_id keyed for Inmate id
     * @param object $service_id keyed service_id
     * @param object $mail_id keyed mail id
     * 
     * @return view
     */
    public function emailForward(Request $request) {
        $data = $request->input();
        $username= User::find($data['inmate_id']);
        $maildetails = \App\IncomingMail::find($data['mail_id']);
        $inmateEmail = InmateDetails::where('inmate_id', $data['inmate_id'])->first();
        $data['service_id']= 8;
         $removeEmail = BlockContact::where('email', $data['to'])->exists();
         
        if ($username['email'] && empty($removeEmail)) {
            try {
                 if($data['emailtype'] == 1){
                    $title = 'RE:';
                }else{ 
                     $title = 'FW:';
                }
                $content = [
                    'title' => $title . $maildetails->subject,
                    'body' => $maildetails->html,
                    'mailbody' => isset($data['bodynew']) ? $data['bodynew'] : '',
                    'from' => $username['email'],
                    'datetime' =>$maildetails->recieved_time,
                    'forwardfrom' =>$maildetails->name.'['.$maildetails->from.']',
                    'subject' => $maildetails->subject,
                    'name' => $username->first_name.' '.$username->last_name,
                    'email' => $data['to']
                ];
                $receiverAddress = $data['to'];
                $var = Mail::to($receiverAddress)->send(new ForwardEmail($content));
                if (count(Mail::failures()) > 0) {

                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => Lang::get('email.email_not_send'),
                    ));
                } else {
                    $data['configuration_id'] = config('axxs.email_charges');
                    $objInmateChargesHistory = new InmateChargesHistory();
                    $objInmateChargesHistory->chargesService($data);
                     SentInmateEmail::Create([
                                'inmate_id' => $data['inmate_id'],
                                'to' => $data['to'],
                                'subject' => $title . $maildetails->subject,
                                'body' =>$data['bodynew'] . $maildetails->message,
                                'is_deleted' => config('axxs.active')
                    ]);

                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => Lang::get('email.email_send'),
                    ));
                }
            } catch (\Exception $e) {
                errorLog($e);
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('email.email_not_send'),
                ));
            }
        } else {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Message' => Lang::get('email.email_removed'),
            ));
        }
    }

    /**
     * Function to General Email UI
     *
     * @param object $inamte_id $service_id The inmate id keyed inmate_id and service_id.
     * 
     * @return view
     */
    public function generalMailUI($inmate_id, $service_id) {

        $inmate_details = User::where('id', $inmate_id)->first();
        $generalmail = 1;
        if ($inmate_details && $inmate_details->role_id == '4') {
            $inboxmail = \App\IncomingMail::where('to_inmateid', $inmate_details->id)
                    ->where('status',1)
                    ->where('from','noreply@theaxxstablet.com')
                    ->orderBy('created_at', 'DSC')
                    ->get();
            

            return View('emails.recievedmails', array('inboxmail' => $inboxmail, 'inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details,'generalmail' => $generalmail ));
        } else {
            return View('errors.404');
        }
    }

}

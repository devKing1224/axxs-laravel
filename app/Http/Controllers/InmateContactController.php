<?php

/**
 * Handling Inmate contact list for 2 way SMS
 * 
 * PHP version 7.2
 * 
 * @category Inmatecontactcontroller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Mail\ContactVerification;
use \App\Mail\OrderShipped;
use App\InmateDetails;
use Mail;
use URL;
use App\InmateContacts;
use App\User;
use Lang;
use Twilio\Rest\Client;
use Session;
use App\InmateSetMaxContact;
use App\InmateSMS;
use App\Facility;
use App\PreApprovedEmail;
use App\PreApprovedContacts;
use App\BlockContact;
class InmateContactController extends Controller {

    /**
     * Function to show contact list of individual Inmate
     *
     * @param  object Request $id  for inmate id,
     *                                
     * @return view
     */
   public function index($id) {

        $inmates = InmateContacts::with('inmate')->where('inmate_id', $id)->get();
        $inmatefacility = User::where('id', $id)->first();
        $validateReturn = new User;
        $isValidate = $validateReturn->validateInmateStaffFacility($inmatefacility, Auth::user());
        if ($isValidate) {
            return view('inmate.inmatecontact', array('inmates' => $inmates));
        } else {
            return redirect(route('inmate.inmatelist'));
        }
    }

    /**
     * Function to view add contact list and view all added in android device
     *
     * @param  object Request $inmate_id  for inmate id,
     * @param  object Request $service_id for service ID
     *                                
     * @return redirected to contactlist page
     */
   public function androidPhoneIndex($inmate_id, $service_id) {
        $inmate_details = User::where('id', $inmate_id)->first();
        $admin_id = $inmate_details->admin_id;
        $check_cntct_app = $this->checkContactApp($admin_id);
        if ($inmate_details && $inmate_details->role_id == '4') {
            $limitstart = new InmateSetMaxContact;
            $limitinfo = $limitstart->FetchInmateNumberLimit($inmate_id)['max_phone'];
            $limitleft = $limitstart->LimitLeftForNumber($inmate_id);
            $contacts = InmateContacts::where('inmate_id', $inmate_id)
                    ->where('type', 'phone')
                    ->get();

             $preApprovedContacts = PreApprovedContacts::where('facility_id', 
                                     $inmate_details->admin_id)
                                  ->where('is_deleted', 0)
                                  ->where('status', 0)
                                  ->get();

            return view('inmate.inmatenumber', array(
                'inmate_id' => $inmate_id,
                'service_id' => $service_id,
                'contacts' => $contacts,
                'limitinfo' => $limitinfo,
                'limitleft' => $limitleft,
                'preApprovedContacts' => $preApprovedContacts,
                'inmate_details' => $inmate_details,
                'cntct_approval' => $check_cntct_app));
        } else {
            return View('errors.404');
        }
    }

    /**
     * Show Page in mobile for adding Email number with his contact details
     * 
     * @param  object Request $inmate_id  for inmate id,
     * @param  object Request $service_id for service ID  
     *                 
     * @return redirected to contactlist page
     */
    public function androidEmailIndex($inmate_id, $service_id) {
 
        
        $inmate_details = User::where('id', $inmate_id)->first();
        $admin_id = $inmate_details->admin_id;
        $check_cntct_app = $this->checkContactApp($admin_id);

        if ($inmate_details && $inmate_details->role_id == '4') {

            $limitstart = new InmateSetMaxContact;
            $limitinfo = $limitstart->FetchInmateEmailLimit($inmate_id)['max_email'];
            $limitleft = $limitstart->LimitLeftForEmail($inmate_id);
                  $preApprovedContacts = PreApprovedEmail::select('id','name','email_phone','varified','is_approved')->where('facility_id', $inmate_details->admin_id)
                   ->where('is_deleted', 0)
                   ->where('status', 0)
                    ->get();

                  $contacts = InmateContacts::select('id','name','email_phone','varified','is_approved')->where('inmate_id', $inmate_id)
                   ->where('type', 'email')
                    ->get();
                    

   

             return view('inmate.inmateemail', array(
                'inmate_id' => $inmate_id,
                'service_id' => $service_id,
                'contacts' => $contacts,
                'preApprovedContacts' => $preApprovedContacts,
                'limitinfo' => $limitinfo,
                'limitleft' => $limitleft,
                'inmate_details' => $inmate_details,
                'cntct_approval' => $check_cntct_app));
        } else {
            return View('errors.404');
        }
    }

    /**
     * Create a new InmateContact instance after a valid registration
     * 
     * @param  object Request $request The contact details keyed inmate_id, 
     *                                name, email_phone, relation,type                                                     
     * @return redirected to contactlist page
     */
    public function storeContactNumber(Request $request) {
        $data = $request->input();
        $inmatefacilityid = User::where('id', $data['inmate_id'])->select('admin_id', 'first_name', 'last_name')->first();
        $rules = array(
            'name' => 'required',
            'email_phone' => 'required|numeric|unique:inmate_contacts,email_phone,NULL,id,facility_id,' . $inmatefacilityid->admin_id,
            'relation' => 'required',
        );
        $messages = [
            'email_phone.required' => 'Please enter a contact number.',
            'email_phone.email' => 'Please enter valid contact number.',
            'email_phone.unique' => 'Contact number has already taken by you or by someone else.',
            'relation.required' => 'Please enter relationship with the contact person.',
            'name.required' => 'Please enter Name of your contact person.',
        ];

        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validator->errors()->all()
            ));
        } else {

            /**
             * Generating Token for verification on contact number
             */
            $t = time();
            $urltoken = md5(uniqid($t, true));
            $facility_details = new Facility;
            $twilio_number = $facility_details->getFacilityInfoByInmateID($data['inmate_id']);

            if ($twilio_number->twilio_number) {
                $inmatecontact = InmateContacts::create([
                            'urltoken' => $urltoken,
                            'facility_id' => $inmatefacilityid->admin_id,
                            'name' => $data['name'],
                            'relation' => $data['relation'],
                            'email_phone' => $data['email_phone'],
                            'is_approved' => ($data['cntct_approval'] == 0) ? 1 : 0,
                            'varified' => ($data['cntct_approval'] == 0) ? 1 : 0,
                            'type' => 'phone',
                            'inmate_id' => $data['inmate_id'],
                ]);

                if (isset($inmatecontact->id) && !empty($inmatecontact->id) && $data['cntct_approval'] == 1) {

                    $AccountSid = env('SMS_ACCOUNT_SID');
                    $AuthToken = env('SMS_AUTH_TOKEN');
                    $client = new Client($AccountSid, $AuthToken);
                    $from = $twilio_number->twilio_number;
                    $name = !empty($data['name']) ? $data['name'] : '';
                    $number = $data['email_phone'];
                    $token = $inmatecontact->urltoken;
                    $baseurl = URL::to("/");
                    $url = $baseurl . "/pnv/" . $token;
                    /**
                     * Sending message for verification on contact number.
                     */
                    $body = $inmatefacilityid->first_name . " " . $inmatefacilityid->last_name . " has requested this SMS for messaging.All content in SMS communications is available for the facility and Law Enforcement to view.  Please except these terms. To verify click " . $url . " here";
                    $people = array(
                        $number => $name,
                    );
                    try {
                        foreach ($people as $number => $name) {
                            $sms = $client->account->messages->create(
                                    $number, array(
                                'from' => $from,
                                'body' => $body
                                    )
                            );
                            if (isset($sms) && !empty($sms)) {
                                return response()->json(array(
                                            'Status' => Lang::get('common.success'),
                                            'Code' => 200,
                                            'Message' => Lang::get('sms.sms_send'),
                                ));
                            }
                        }
                    } catch (\Exception $e) {
                        errorLog($e);
                        if (strpos($e->getMessage(), '[HTTP 400] Unable to create record') !== false) {
                            InmateContacts::destroy($inmatecontact->id);
                            return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 400,
                                        'Message' => Lang::get('sms.sms_not_send_number_issue', ['number' => $number]),
                            ));
                        }
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => Lang::get('sms.sms_inmate_create_password'),
                        ));
                    }
                } else {
                     return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => 'Contact Added Successfully',
                        ));
                }
            } else {
             return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => Lang::get('sms.sms_inmate_facility_issue'),
                ));
             }
             
        }
    }

    /**
     * Create a new InmateContact Email instance after a valid registration
     * 
     * @param  object Request $request The contact details keyed inmate_id, 
     *                                name, email_phone, relation,type   
     *                                                   
     * @return redirected to contactlist page
     */
    public function storeContactEmail(Request $request) {
        $data = $request->input();
        


        $rules = array(
            'name' => 'required',
            'email_phone' => 'required|deny_email|email|unique:inmate_contacts,email_phone,NULL,id,inmate_id,' . $data['inmate_id'],
            'relation' => 'required',
        );
        $messages = [
            'relation.required' => 'Please enter relationship with the contact person.',
            'name.required' => 'Please enter Name of your contact person.',
            'email_phone.required' => 'Please enter an Email ID.',
            'email_phone.email' => 'Please enter a valid Email ID.',
            'email_phone.unique' => 'Same Email ID can not be added again.',
        ];

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validator->errors()->all()
            ));
        } else {
            $inmatefacilityid = User::where('id', $data['inmate_id'])->select('admin_id', 'first_name', 'last_name','email')->first();

            $preApprovedContacts = PreApprovedEmail::where('email_phone', $data['email_phone'])
            ->exists();

            if($preApprovedContacts) {
                 return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' =>'Same Email ID can not be added again'
                 ));
            }


            $t = time();
            $urltoken = md5(uniqid($t, true));
            $inmatecontact = InmateContacts::create([
                        'facility_id' => $inmatefacilityid->admin_id,
                        'name' => ucwords($data['name']),
                        'relation' => $data['relation'],
                        'email_phone' => strtolower($data['email_phone']),
                        'type' => 'email',
                        'is_approved' => ($data['cntct_approval'] == 0) ? 1 : 0,
                        'urltoken' => $urltoken,
                        'inmate_id' => $data['inmate_id'],
            ]);
            if (isset($inmatecontact->id) && !empty($inmatecontact->id) ) {

                $token = $inmatecontact->urltoken;
                $baseurl = URL::to("/");
                $url = $baseurl . "/pnv/" . $token;
                try {
                    $content = [
                        'title' => Lang::get('email.email_verification_subject'),
                        'body' => Lang::get('email.email_body_verification'),
                        'url' => $url,
                        'inmatename' => $inmatefacilityid->first_name . " " . $inmatefacilityid->last_name,
                        'email' => $inmatefacilityid->email,
                        'useremail' => $inmatecontact->email_phone
                    ];

                    $receiverAddress = $inmatecontact->email_phone;
                    $var = Mail::to($receiverAddress)->send(new ContactVerification($content));

                    if (count(Mail::failures()) > 0) {
                        InmateContacts::destroy($inmatecontact->id);
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => Lang::get('email.email_not_send'),
                        ));
                    } else {
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => $var . ' ' . Lang::get('email.email_send'),
                        ));
                    }
                } catch (Exception $ex) {
                    errorLog($ex);
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('email.email_not_send'),
                    ));
                }
            }else {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => 'Contact added Successfully !',
                ));
            }
        }
    }

    /**
     * Verification of Email Id and contact number via token generated by md5 
     * 
     * @param  object Request $token that was generated for authenticating   
     *                                                   
     * @return redirected js with message and data
     */
    public function phoneNumberVerification($token) {
        $t = $token;
        $contact = InmateContacts::where('urltoken', $t)->first();
        if ($contact) {
            if ($contact->varified == 0) {
                $inmatefacilityid = User::where('id', $contact->inmate_id)->select('first_name', 'last_name')->first();
                $facilityemailid = Facility::where('facility_user_id', $contact->facility_id)->first();
                $facilitycontent = [
                    'title' => 'New Contact has been verified.',
                    'body' => $inmatefacilityid->first_name . " " . $inmatefacilityid->last_name . " has created new contact. " . $inmatefacilityid->first_name . " " . $inmatefacilityid->last_name ." and " . ucwords($contact->name) . " are connected now. ",
                ];
                $facilityreceiveremail = $facilityemailid->email;
                $mailtofacility = Mail::to($facilityreceiveremail)->send(new OrderShipped($facilitycontent));

                $contact->varified = '1';
                $contact->save();

                if ($contact->type == 'phone') {
                    $message = Lang::get('email.contact_verified');
                } else {
                    $message = Lang::get('email.email_verified');
                }
            } else {
                if ($contact->type == 'phone') {
                    $message = Lang::get('email.contact_verified');
                } else {
                    $message = Lang::get('email.email_verified');
                }
            }
        } else {
            $message = Lang::get('email.error');
        }
        return view('emails.varifyemail', array('message' => $message));
    }

    /**
     * Edit a  InmateContact instance is_approves field to approve
     *                              or unapprove , activate, deactivate etc
     * 
     * @param object Request $id The contact update keyed id, 
     * @return redirected to contactlist page
     */
    public function edit($id) {
        $test = "";
        $contact = InmateContacts::find($id);
        if ($contact->is_deleted == 0) {
            if ($contact->is_approved == 0) {
                $test = 'Approved';
                $contact->is_approved = 1;
            } else {
                $test = 'InActivated';
                $contact->is_deleted = 1;
                $contact->is_approved = 0;
            }
        } else {
            $test = 'Activated';
            $contact->is_deleted = 0;
            $contact->is_approved = 1;
        }
        $contact->save();
        Session::flash('message', "Contact " . $test . " Successfully");
        return Redirect::back();
    }

    /**
     * Deactivating a  InmateContact instance 
     * 
     * @param object Request $id The contact update keyed id, 
     * 
     * @return redirected to contactlist page
     */
     public function destroy($id) {
        $message = "";
        $contact = InmateContacts::find($id);
        if ($contact->varified == 1) {

            if ($contact->is_deleted == 0) {
                $message = 'InActivated';
                $contact->is_deleted = 1;
                $contact->is_approved = 0;
                $contact->save();
            } else {
                $message = 'Asked for Approval';
                $contact->is_deleted = 0;
                $contact->save();
                $inmatefacilityid = User::where('id', $contact->inmate_id)->select('first_name', 'last_name')->first();
                $facilityemailid = Facility::where('facility_user_id', $contact->facility_id)->first();
                $facilitycontent = [
                    'title' => 'User asked for the approval of a contact which was added earlier.',
                    'body' => $inmatefacilityid->first_name . " " . $inmatefacilityid->last_name . " has activated. " . ucwords($contact->name) . " is inmate's " . $contact->relation,
                ];
                $facilityreceiveremail = $facilityemailid->email;
                $mailtofacility = Mail::to($facilityreceiveremail)->send(new OrderShipped($facilitycontent));
            }
        } else {
            if ($contact->is_deleted == 0) {
                $message = 'is not verified yet but inactivated';
                $contact->is_deleted = 1;
                $contact->save();
            } else {
                $message = 'is not verified yet but activated';
                $contact->is_deleted = 0;
                $contact->save();
            }
        }

        Session::flash('message', "Contact " . $message . " Successfully");
        return Redirect::back();
    }


/**
     * Resend Varification mail 
     * 
     * @param object Request $id The contact update keyed id, 
     * 
     * @return redirected to contactlist page
     */

    public function resendVarification( Request $request){
            
                $data = $request->input();
                $contact = InmateContacts::where('id', $data['id'])
                ->where('is_approved', '0')->first();
                
            $removeEmail = BlockContact::where('email', $contact->email_phone)->exists();
            if(!empty($removeEmail)){
            
            return response()->json(array(
                    'Status' => Lang::get('common.failure'),
                    'Code' => 401,
                    'Message' => "Due to Email ID block you can not send verification email",
            ));
            }      

         if( $contact){ 
                 $inmatefacilityid = User::where('id', $contact->inmate_id)->select('admin_id', 'first_name', 'last_name','email')->first();
                      
                 $token = $contact->urltoken;
                        $baseurl = URL::to("/");
                        $url = $baseurl . "/pnv/" . $token;
                        try {
                            $content = [
                                'title' => Lang::get('email.email_verification_subject'),
                                'body' => Lang::get('email.email_body_verification'),
                                'url' => $url,
                                'inmatename' => $inmatefacilityid->first_name . " " . $inmatefacilityid->last_name,
                                'email' => $inmatefacilityid->email,
                                'useremail' => $contact->email_phone
                            ];

                            $receiverAddress = $contact->email_phone;
                            $var = Mail::to($receiverAddress)->send(new ContactVerification($content));

                            if (count(Mail::failures()) > 0) {
                                InmateContacts::destroy($contact->id);
                                return response()->json(array(
                                            'Status' => Lang::get('common.success'),
                                            'Code' => 200,
                                            'Message' => Lang::get('email.email_not_send'),
                                ));
                            } else {
                                return response()->json(array(
                                            'Status' => Lang::get('common.success'),
                                            'Code' => 200,
                                            'Message' => $var . ' ' . Lang::get('email.email_send'),
                                ));
                            }
                        } catch (Exception $ex) {
                            errorLog($ex);
                            return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 400,
                                        'Message' => Lang::get('email.email_not_send'),
                            ));
                        }
            }
            else{
                   return response()->json(array(
                                'Status' => Lang::get('common.failure'),
                                'Code' => 400,
                                'Message' => "Allready aproved your contacts",
                        ));

            }

    }

    /**
     * Setting the the contact limnit for Inmate by facility
     * 
     * @param  object Request $request keyed to max_email for email limit
     *                                          max_phone for contact number limit , 
     * 
     * @return redirected to contactlist page
     */
    public function setMaxLimitByfacilty(Request $request) {

        $data = $request->input();

        $rules = array(
            'max_phone' => 'required_without_all:max_email|numeric|max:9999',
            'max_email' => 'required_without_all:max_phone|numeric|max:9999',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validator->errors()->all(),
            ));
        } else {

            $check_if_exist = InmateSetMaxContact::where('user_id', $data['user_id'])->first();

            if ($check_if_exist) {
                $inmatemaxcontact = InmateSetMaxContact::where('user_id', $data['user_id'])->update([
                    'max_email' => $data['max_email'],
                    'max_phone' => $data['max_phone'],
                ]);
            } else {
                $inmatemaxcontact = InmateSetMaxContact::create([
                            'user_id' => $data['user_id'],
                            'max_email' => $data['max_email'],
                            'max_phone' => $data['max_phone'],
                ]);
            }

            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => Lang::get('email.max_limit'),
            ));
        }
    }

    /**
     * fetching the limit of contact list for individual inmate 
     *                       for more validation of page view 
     * 
     * @param  object Request $request keyed inmate id with limit, 
     * 
     * @return redirected to contactlist page
     */
    public function getMaxLimitVal(Request $request) {
        $data = $request->input();
        $inmatelimitinfo = InmateSetMaxContact::where('user_id', $data['inmate_ID'])->get();

        if (count($inmatelimitinfo) > 0) {
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => Lang::get('inmate.inmate_details'),
                        'Data' => $inmatelimitinfo
            ));
        } else {
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => Lang::get('inmate.inmate_not_found')
            ));
        }
    }

    /**
     * Creating inmate's hostgator email id with password                    
     * 
     * @param  object Request $request keyed email id with password, 
     * 
     * @return redirected contact.js
     */
    public function createEmail(Request $request) {
        $data = $request->input();

        $rules = array(
            'email' => 'required|unique:inmatedetails',
            'password' => 'required|min:6',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validator->errors()->first(),
            ));
        } else {
            $user = User::findOrFail($data['inmate_id']);
            $user->email = $data['email'];
            $user->save();
            InmateDetails::create([
                'inmate_id' => $data['inmate_id'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => Lang::get('email.details_save'),
            ));
        }
    }

    /**
     * Inmate contact list for android device          
     * 
     * @param  object Request $inmate_id keyed inmate id, 
     * @param  object Request $service_id keyed  service id,
     * 
     * @return redirected contact.js
     */
    public function getInmateContactNumber($inmate_id, $service_id) {
        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $inmate_sms = InmateSMS::where('inmate_id', $inmate_id)
                    ->where('is_deleted', '0')
                    ->distinct('contact_number')
                    ->get(['contact_number']);
            $values = [];

            foreach ($inmate_sms as $sms) {
                foreach (explode(',', $sms->contact_number) as $value) {
                    $values[] = trim($value);
                }
            }
            $values = array_unique($values);
            $inmate_numbers = InmateContacts::whereIn('email_phone', $values)->where('inmate_id', $inmate_id)->get();

            foreach ($inmate_numbers as $inmate_number) {
                     $inmate_sms_viewed = InmateSMS::where('inmate_id', $inmate_id)
                    ->where('contact_number',$inmate_number->email_phone)
                    ->where('is_deleted', '0')
                    ->where('is_viewed', '0')
                    ->exists();
                    $inmate_number->viewed = 0;
                    if ($inmate_sms_viewed) {
                        $inmate_number->viewed = 1;
                    }
            }

            return View('inmate.numberlist', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'inmate_numbers' => $inmate_numbers]);
        } else {
            return View('errors.404');
        }
    }

    /**
     * Showing inmate there trashed SMS in android view       
     * 
     * @param  object Request $inmate_id keyed inmate id, 
     * @param  object Request $service_id keyed  service id,
     * 
     * @return redirected view
     */
    public function getInmateDeletedSMS($inmate_id, $service_id) {
        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $inmate_sms = InmateSMS::where('inmate_id', $inmate_id)
                    ->where('is_deleted', '1')
                    ->distinct('contact_number')
                    ->get(['contact_number']);
            $values[] = "";
            foreach ($inmate_sms as $sms) {
                foreach (explode(',', $sms->contact_number) as $value) {
                    $values[] = trim($value);
                }
            }
            $values = array_unique($values);
            $inmate_numbers = InmateContacts::whereIn('email_phone', $values)->where('inmate_id', $inmate_id)->get();


            return View('inmate.deletednumberlist', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'inmate_numbers' => $inmate_numbers]);
        } else {
            return View('errors.404');
        }
    }

    /**
     * Showing inmate their Chat of all contact person
     * 
     * @param  object Request $inmate_id keyed inmate id, 
     * @param  object Request $service_id keyed  service id,
     * @param  object Request $number_id keyed  contact number,
     * 
     * @return redirected view
     */
    public function getInmateChatSMS($inmate_id, $service_id, $number_id) {
        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $isdeleted = '0';
            $inmate_contact_detail = InmateContacts::where('id', $number_id)->first();
            $limitstart = new InmateSetMaxContact;
            $limitleft = $limitstart->LimitLeftForNumber($inmate_id);
            $inmate_sms = InmateSMS::where('inmate_id', $inmate_id)
                    ->where('contact_number', $inmate_contact_detail->email_phone)
                    ->where('is_deleted', '0')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    InmateSMS::where('inmate_id', $inmate_id)
                    ->where('contact_number', $inmate_contact_detail->email_phone)
                    ->where('is_deleted', '0')
                    ->update(array('is_viewed' => 1));
                   

            return View('inmate.viewsms', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'inmate_sms' => $inmate_sms, 'inmate_contact_detail' => $inmate_contact_detail, 'isdeleted' => $isdeleted, 'limitleft' => $limitleft]);
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function for deacvtivating SMS 
     * 
     * @param  object Request $inmate_id keyed inmate id, 
     * @param  object Request $service_id keyed  service id,
     * @param  object Request $sms_id keyed sms id,
     * @param  object Request $delete keyed is_deleted fieild,
     * 
     * @return redirected view
     */
    public function deleteInmateSMS($inmate_id, $service_id, $sms_id, $delete) {
        $objEmails = new InmateSms();
        $objEmails->deletesms($sms_id, $delete);

        return Redirect::back();
    }

    /**
     * Fecthing Trashed Chat of all contact of a inmate by inmate_id and Number id
     * 
     * @param  object Request $inmate_id keyed inmate id, 
     * @param  object Request $service_id keyed  service id,
     * @param  object Request $number_id keyedcontact number,
     * 
     * @return redirected view
     */
    public function getInmateDeletedChatSMS($inmate_id, $service_id, $number_id) {
        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $isdeleted = '1';
            $inmate_contact_detail = InmateContacts::where('id', $number_id)->first();
            $inmate_sms = InmateSMS::where('inmate_id', $inmate_id)
                    ->where('contact_number', $inmate_contact_detail->email_phone)
                    ->where('is_deleted', '1')
                    ->orderBy('created_at', 'desc')
                    ->get();
            return View('inmate.viewsms', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'inmate_details' => $inmate_details, 'inmate_sms' => $inmate_sms, 'inmate_contact_detail' => $inmate_contact_detail, 'isdeleted' => $isdeleted]);
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function for facility to see all the messages of a perticular Inmate
     * 
     * @param  object Request $inmate_id keyed inmate id, 
     * 
     * @return redirected view
     */
   public function listInmateSMS($inmate_id) {

        $inmateadmin = User::where('id', $inmate_id)
                        ->where('is_deleted', 0)->where('role_id', 4)->first();

        $validateReturn = new User;
        $isValidate = $validateReturn->validateInmateStaffFacility($inmateadmin, Auth::user());
        if ($isValidate) {
            $inmate_sms = InmateSMS::with('contactperson')->where('inmate_id', $inmate_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            return View('inmate.facilityviewsms', ['inmate_id' => $inmate_id, 'inmate_sms' => $inmate_sms]);
        } else {
            return redirect(route('inmate.inmatelist'));
        }
    }

    /**
     * Function for checking contact approval is enabled or not
     * 
     * @param  admin_id, 
     * 
     * @return true false
     */

    public function checkContactApp($admin_id){

       $facilty_details= Facility::select('cntct_approval')->where('facility_user_id',$admin_id)->first();

       return $facilty_details['cntct_approval'];
    }

}

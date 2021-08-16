<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\PreApprovedEmail;
use App\PreApprovedContacts;
use Auth;
use Validator;
use Lang;

/**
 * To handle pre approved email, assign,create,edit etc
 * @category PreApprovedEmailController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

class PreApprovedEmailController extends Controller
{
	protected function guard() {
		return Auth::guard('admin');
	}


	    /**
     * Function for load facility Add UI.
     * 
     * @param object Request $request The inmate details keyed facility ID.
     * 
     * @return NULL.
     */
    public function addEmailUI(Request $request) {
        
           return View('preapprovedemail.addpre_approved_email');
      
    }


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addEmailId(Request $request) {	 

    	$id = Auth::user()->id;
    	
		try {
		    $data = $request->all(); 
		    
		        $email = PreApprovedEmail::where('email_phone', $data['email_phone'])
                ->where('is_deleted', 1)->exists();

                   if($email) {
                         return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 400,
                                    'Message' =>'This email is already available in inactive list'
                         ));
                    } 

		    $rules = array(
		        'email_phone' => 'required|email|unique:pre_approved_emails',
                
		       
		    );
		 
		    $validate = Validator::make($data, $rules);

		    if ($validate->fails()) {
		        return response()->json(array(
		                    'Code' => 400,
		                    'Status' => Lang::get('common.success'),
		                    'Message' => $validate->errors()->all(),
		                    'Response' => array('id' => null)
		        ));
		    } else {
             
		        $pre_approved_email = PreApprovedEmail::create(['email_phone' => $data['email_phone'],'facility_id'=>$id,'name'=>$data['name']]);
		        return response()->json(array(
		                    'Code' => 201,
		                    'Status' => Lang::get('common.success'),
		                    'Message' => Lang::get('facility.pre_approved_email_created'),
		                    'Response' => array('pre_approved_email' => $pre_approved_email)
		        ));
		     }

		} catch (Exception $ex) {
		    return errorLog($ex);
		}

    }

    /**
     * Display a  listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPreEmail() {
        
         $id = Auth::user()->id;

    	 $objPreApprovedEmail = new PreApprovedEmail(); 
          $allemails = $objPreApprovedEmail->getEmailList($id);

        return view('preapprovedemail.emaillist', array('allemails' =>$allemails));
    }

/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function preApprovedEmailDelete(Request $request) {

            try {
            $data = $request->id;

            $objPreApprovedEmail = new PreApprovedEmail(); 
            $preApprovedEmail = $objPreApprovedEmail->preApprovedEmail($data);
     
            if (isset($preApprovedEmail) && !empty($preApprovedEmail)) {

                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.preemail_delete'),
                                )
                );
            }else{
            	     return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('facility.email_delete_error')
                            )
            );

            }
       
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }
   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        //Get all blacklist word and pass it to the view

             if (isset($request->id)) {
            $objPreApprovedEmail = new PreApprovedEmail();
            $preapprovedemail = $objPreApprovedEmail->getpreApprovedEmail($request->id);

            if ($preapprovedemail) {
                return View('preapprovedemail.addpre_approved_email', array('preapprovedemail' => $preapprovedemail));
            } else {
                return redirect(route('preapprovedemail.emaillist'));
            }
        } else {
            return View('preapprovedemail.addpre_approved_email');
        }
    }


 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePreEmail(Request $request) {

	      $data = $request->input(); 
	        $rules = array(
	            'email_phone' => 'required',
	        );

        	$validate = Validator::make($data, $rules);

	        if ($validate->fails()) {
	            return response()->json(
	                            array(
	                                'Status' => \Lang::get('common.success'),
	                                'Code' => 400,
	                                'Message' => $validate->errors()->all()
	                            )
	            );
	        } else {
   
	        $preEmailUpdate = PreApprovedEmail::where('id', $data['id'])->update(['email_phone'=> $data['email_phone'],'name'=>$data['name']]);

	                return response()->json(
	                            array(
	                                'Code' => 200,
	                                'Status' => \Lang::get('common.success'),
	                                'Message' => \Lang::get('facility.preemail_edit_success') 
	                            )
	                );
	            }
	        }




    /**
     * Function for load inactive email UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function emailInactiveListUI(Request $request) {

        if (Auth::user()->hasRole('Facility Admin')) {
            $emailList = PreApprovedEmail::where('is_deleted',1)->where('facility_id', Auth::user()->id)->get();
            return View('preapprovedemail.emailinactivelist', array('emailList' => $emailList));
        } else {
         
            return View('preapprovedemail.emaillist');
        }
    }


    /**
     * Create function for update email details behalf on  id.
     *
     * @param object Request $request The  id keyed id, 
     * 
     * @return NULL
     */
    public function activeEmail(Request $request) {
        $data = $request->input();
        $rules = array(
            'id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $updateData = array(
                'is_deleted' => config('axxs.active'),
                'status' => 0,
            );
            $emailUpdateInfo = PreApprovedEmail::where(array('id' => $data['id']))->update($updateData);
            if (isset($emailUpdateInfo) && !empty($emailUpdateInfo)) {

                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('facility.update_email'),
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('facility.update_email_error')
                ));
            }
        }
    }


   /**
     * Display a  listing of the contacts.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPreContact() {
       
        $id = Auth::user()->id;

         $objPreApprovedContacts= new PreApprovedContacts(); 
          $allcontacts = $objPreApprovedContacts->getContactList($id);
        return view('preapprovedemail.contactlist', array('allcontacts' =>$allcontacts));
    }

   
        /**
     * Function for load contact Add UI.
     * 
     * @param object Request $request The inmate details keyed facility ID.
     * 
     * @return NULL.
     */
    public function addContactUI(Request $request) {
        
           return View('preapprovedemail.addpre_approved_contact');
      
    }

 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addContact(Request $request) {   

        $id = Auth::user()->id;
        
        try {
            $data = $request->all(); 

            $rules = array(
                'contact_number' => 'required|unique:pre_approved_contacts',
                'name' => 'required', 
            );
         
            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Code' => 400,
                            'Status' => Lang::get('common.success'),
                            'Message' => $validate->errors()->all(),
                            'Response' => array('id' => null)
                ));
            } else {
             

                $pre_approved_contact = PreApprovedContacts::create(['contact_number' => $data['contact_number'],'facility_id'=>$id ,'name' => $data['name']]);
                return response()->json(array(
                            'Code' => 201,
                            'Status' => Lang::get('common.success'),
                            'Message' => Lang::get('facility.pre_approved_contact_created'),
                            'Response' => array('pre_approved_contact' => $pre_approved_contact)
                ));
             }

        } catch (Exception $ex) {
            return errorLog($ex);
        }

    }


/**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function preContactDelete(Request $request) {

            try {
            $data = $request->id;

            $objPreApprovedContact = new PreApprovedContacts(); 
            $preApprovedContact = $objPreApprovedContact->deleteApprovedContact($data);
     
            if (isset($preApprovedContact) && !empty($preApprovedContact)) {

                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.precontact_delete'),
                                )
                );
            }else{
                     return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('facility.contact_delete_error')
                            )
            );

            }
       
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {

             if (isset($request->id)) {
            $objPreApprovedContact = new PreApprovedContacts();
            $preapprovedcontact = $objPreApprovedContact->getpreApprovedContact($request->id);

            if ($preapprovedcontact) {
                return View('preapprovedemail.addpre_approved_contact', array('preapprovedcontact' => $preapprovedcontact));
            } else {
                return redirect(route('preapprovedemail.contactlist'));
            }
        } else {
            return View('preapprovedemail.addpre_approved_contact');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePreContact(Request $request) {

          $data = $request->input(); 
            $rules = array(
                'contact_number' => 'required',
            );

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => $validate->errors()->all()
                                )
                );
            } else {
   
            $preContactUpdate = PreApprovedContacts::where('id', $data['id'])->update(['contact_number'=> $data['contact_number'],'name'=> $data['name']]);

                    return response()->json(
                                array(
                                    'Code' => 200,
                                    'Status' => \Lang::get('common.success'),
                                    'Message' => \Lang::get('facility.precontact_edit_success') 
                                )
                    );
                }
            }

 /**
     * Function for load inactive email UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function contactInactiveListUI(Request $request) {

        if (Auth::user()->hasRole('Facility Admin')) {
            $contactList = PreApprovedContacts::where('is_deleted',1)->where('facility_id', Auth::user()->id)->get();
            return View('preapprovedemail.contactinactivelist', array('contactList' => $contactList));
        } else {
         
            return View('preapprovedemail.contactlist');
        }
    }
 /**
     * Create function for update contact details behalf on  id.
     *
     * @param object Request $request The  id keyed id, 
     * 
     * @return NULL
     */
    public function activeContact(Request $request) {
        $data = $request->input();
        $rules = array(
            'id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $updateData = array(
                'is_deleted' => config('axxs.active'),
                'status' => 0,
            );
            $contactUpdateInfo = PreApprovedContacts::where(array('id' => $data['id']))->update($updateData);
            if (isset($contactUpdateInfo) && !empty($contactUpdateInfo)) {

                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('facility.update_contact'),
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('facility.update_contact_error')
                ));
            }
        }
    }

}

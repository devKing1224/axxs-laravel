<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\IncomingMail;
use App\EmailAttachment;
use Auth;
use App\User;
use View;
use Storage;
use Mail;
use DB;
use Lang;
use Symfony\Component\Process\Process;
use App\Mail\RevertMailable;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Validator;
class EmailController extends Controller
{	


	public function __construct(Request $request)
	{
	    $this->request = $request;
	}


    /**
    * This function returns a view which then redirect the facility to users email list
    *@return resource
    */
    public function listEmailUI(){
    	
    	return view('mail.list_usersemail');

    }

    /**
    * This function returns incoming email list
    *@return json object 
    */
    public function getEmaildata(){
    	$facility_id = Auth::User()->id;
    	$getEmail = IncomingMail::where('incoming_emails.status',0)->where('users.admin_id',$facility_id)->select('incoming_emails.id','to', 'subject', 'from', 'html','reply','incoming_emails.created_at','is_blacklisted')->leftjoin('users','users.email','=','incoming_emails.to');
        
    	return Datatables::of($getEmail)
    	->editColumn('html', function ($getEmail) {
            $dissapprove = '';
            if ($getEmail->status == 0) {
                $dissapprove = ' | '.'<a href="javascript:void(0)" title="Disapprove"><i class="fa fa-times" style="color:red" onclick="approve_email('.$getEmail->id.',2)" aria-hidden="true"></i></a>';
            }
            if ($getEmail->is_blacklisted == 1) {
                $color = 'red';
            }else{
                $color = '#337ab7';
            }

            if (Auth::user()->hasPermissionTo('Manage Attached Document')) {
                $attach_button = ' | '.'<a href="javascript:void(0)" title="View Attachment"><i class="fa fa-bars" onclick="view_attachment('.$getEmail->id.')" aria-hidden="true"></i></a>';
            } else {
                $attach_button = '';
            }
    		return '<a href="javascript:void(0)" title="View Email"><i style="color:'.$color.'" class="fa fa-envelope" color="green" onclick="viewemail('.$getEmail->id.')" aria-hidden="true"></i></a>'.' | '.'<a href="javascript:void(0)" title="Approve"><i class="fa fa-check" style="color:green" onclick="approve_email('.$getEmail->id.',1)" aria-hidden="true"></i></a>'.$dissapprove. $attach_button;
		})
    	->make(true);

    }

    /**
    * This function returns email html
    *@return html
    */
    public function viewUserEmail($id){
    	$getEmailhtml = IncomingMail::where('id', $id)->value('html');
    	
    	return $getEmailhtml;

    }


    /**
    * This function aproves/reject user email
    *@return success or error
    */
    public function approveEmail($id){
    	try {
            
            //get receiver email
            $get_email = IncomingMail::select('from','to')->where('id', $id)->first();

            //approve / reject email    
    		IncomingMail::where('id', $id)->update(['status' => $this->request['value']]);

            //approve/disapprove attachments
            EmailAttachment::where('email_id',$id)->where('status',0)->update(['status' => $this->request['value']]);
            $get_count = EmailAttachment::where('email_id', $id)->where('status', 1)->count();
            //getallattachment
            $total_attach = EmailAttachment::where('email_id', $id)->count();
            if ($this->request['value'] == 1) {
                //check user exists

            $rej_attachment = ($total_attach-$get_count);
            if ($rej_attachment > 0) {
                //send mail to sender when any attachmnet rejected
                 $data = array(
                'email'=> $get_email['to'],
                'body' =>  $rej_attachment.' '.'attached document out of '.' '.$total_attach.' '.'was reject to the AxxS Tablet recipient'
                 );
                 $content = array(
                     'to' => $get_email['from'],
                     'from' => $get_email['to']
                 );
                 $to = $get_email['from'];
                     Mail::send('mail_template.rejectattach', $data, function($message) use ($content) {
                  $message->to($content['to'])->subject
                 ('Reject Attachment');
                 $message->from('noreply@theaxxstablet.com','TheAxxsTablet');
                     });
            }
             $user_model = new User();   
             $check_user = $user_model->userExists($get_email['to']);
             
             $facility_attach_charge = \App\Facility::where('facility_user_id',Auth::user()->id)->value('attachment_charge');
             if (isset($facility_attach_charge) && !empty($facility_attach_charge)) {
                 $attach_charge = $facility_attach_charge;
                 //facility attachment charge
             } else {
                $attach_charge =  \App\InmateConfiguration::where('key','attachment_charge')->value('value');
                //global attachment charge
             }
             if ($check_user != null) {
                //get attachment charge

                $email_charge = \App\Facility::where('facility_user_id',Auth::user()->id)->value('incoming_email_charge');
                $total_charge = (($get_count * $attach_charge) + $email_charge);
                $fee = $check_user->balance - $total_charge;
                $user_model->where('id',$check_user->id)->update(['balance' => $fee]);

                //inserting incoming mail charge in user history
                $this->updateIncominMailCharge($check_user->id,$total_charge);
             }
            }else if ($this->request['value'] == 2) {
                $data = array(
               'email'=> $get_email['to'], 
                );
                $content = array(
                    'to' => $get_email['from'],
                    'from' => $get_email['to']
                );
                $to = $get_email['from'];
                    Mail::send('mail_template.rejectmail', $data, function($message) use ($content) {
                 $message->to($content['to'])->subject
                ('Reject Mail');
                $message->from('noreply@theaxxstablet.com','TheAxxsTablet');
                    });
            }
             


         } catch (\Exception $e) {
    		$data = array(
    			'msg' => $e->getMessage(),
    			'status' => 'error'
    		);
    		return $data;
    	}
        if ($this->request['value'] == 2) {
        		$msg = Lang::get('email.email_reject');
                $charge = Lang::get('email.no_charge_deducted');
        	} else{
        		$msg =  Lang::get('email.email_approved');
                $charge = Lang::get('email.charge_deducted').' '.$total_charge;
        	}
    	$data = array(
    			'msg' => $msg,
    			'status' => 'success',
                'charge' => $charge
    		);

    	return $data;
    }

    /**
    * This function returns a view which then redirect the facility to users rejected email list
    *@return resource
    */
    public function rejectedemailUI(){
    	
    	return view('mail.rejected_mail');

    }

    /**
    * This function returns a view which then redirect the facility to users rejected email list
    *@return resource
    */
    public function approvedemailUI(){
        
        return view('mail.approved_mail');

    }

    /**
    * This function to get rejected email list
    *@return json object
    */
    public function getRejectedmail(){
    	$facility_id = Auth::User()->id;
    	$getEmail = IncomingMail::where('incoming_emails.status',2)->where('users.admin_id',$facility_id)->select('incoming_emails.id','incoming_emails.status','to', 'subject', 'from', 'html','is_blacklisted')->leftjoin('users','users.email','=','incoming_emails.to');
    	return Datatables::of($getEmail)
    	->editColumn('html', function ($getEmail) {
                $dissapprove = '';
                if ($getEmail->status == 0 && $getEmail->status != 2 ) {
                    $dissapprove = ' | '.'<a href="javascript:void(0)" title="Disapprove"><i class="fa fa-times" style="color:red" onclick="approve_email('.$getEmail->id.',2)" aria-hidden="true"></i></a>';
                }
                if ($getEmail->is_blacklisted == 1) {
                    $color = 'red';
                }else{
                    $color = '#337ab7';
                }

                if (Auth::user()->hasPermissionTo('Manage Attached Document')) {
                    $attach_button = ' | '.'<a href="javascript:void(0)" title="View Attachment"><i class="fa fa-bars" onclick="view_attachment('.$getEmail->id.')" aria-hidden="true"></i></a>';
                } else {
                    $attach_button = '';
                }
                return '<a href="javascript:void(0)" title="View Email"><i style="color:'.$color.'" class="fa fa-envelope" color="green" onclick="viewemail('.$getEmail->id.')" aria-hidden="true"></i></a>'.' | '.'<a href="javascript:void(0)" title="Approve"><i class="fa fa-check" style="color:green" onclick="approve_email('.$getEmail->id.',1)" aria-hidden="true"></i></a>'.$dissapprove. $attach_button;
            })
    	->make(true);
    }

    /**
    * This function to get approved email list
    *@return json object
    */
    public function getApprovedmail(){
        $facility_id = Auth::User()->id;
        $getEmail = IncomingMail::where('incoming_emails.status',1)->where('users.admin_id',$facility_id)->select('incoming_emails.id','to', 'subject', 'from', 'html')->leftjoin('users','users.email','=','incoming_emails.to');
        return Datatables::of($getEmail)
        ->editColumn('html', function ($getEmail) {

            return '<a href="javascript:void(0)" title="View Email"><i style="color:#337ab7" class="fa fa-envelope" color="green" onclick="viewemail('.$getEmail->id.')" aria-hidden="true"></i></a>';
        })
        ->make(true);
    }

    /**
    * This function returns a view which then redirect the facility to send mail view
    *@return resource
    */
    public function sendEmailUI(){
    	$facility_id = Auth::User()->id;
    	$user = User::where('admin_id',$facility_id)->select('id','email','username')->orderBy('username','ASC')->get();

    	return view('mail.send_mailui',compact('user'));

    }

    /**
    * This function send an email to inmates
    *@return resource
    */
    public function sendEmail(Request $request){
    	$req = $request->all();
        $content =[];
        if (isset($req['file'])) {
            $attachment_id = uniqid();
            foreach ($req['file'] as $key => $attach) {
                //uploading attachment to aws s3 bucket
                $imageName = uniqid('axxs').'.'.$attach->getClientOriginalExtension();
                $t = Storage::disk('s3')->put('attachment_sendbyfacility/'.$imageName, file_get_contents($attach));
                $imageName = Storage::disk('s3')->url('attachment_sendbyfacility/'.$imageName);

                
                //saving attachment in db
                $email_attachment = new EmailAttachment;
                $email_attachment->link = $imageName;
                $email_attachment->status = 1;
                $email_attachment->attachment_id = $attachment_id;
                $email_attachment->save();
                unset($imageName);
            }
            $email_attach = EmailAttachment::select('link')->where('attachment_id',$attachment_id)->get('link')->toArray();
            $content['attach'] = $email_attach;
        }
        
    
        $email = $req['inmate_mail'];
        $to = implode(',',$email);
        
        $data = array(
            'from' => 'noreply@theaxxstablet.com',
            'to' => $to,
            'subject' => $req['subject'],
            'msg' => $req['message'],
            'attachment_id' => isset($attachment_id) ? $attachment_id : NULL,
            'role' => 'facility'
        );

        
        $content['body'] = $req['message'];
        $content['subject'] = $req['subject'];
        DB::table('outgoing_emails')->insert($data);
        foreach ($email as $key => $email_add) {
            $in_data = array(
                'from' => 'noreply@theaxxstablet.com',
                'to_inmateid' => $email_add,
                'subject' => $req['subject'],
                'name' => 'Facility',
                'status' => 1,
                'plain' => $req['message'],
                'html' => $req['message'],
                'attachment_id' =>isset($attachment_id) ? $attachment_id : NULL,
            );
           DB::table('incoming_emails')->insert($in_data); 
        /*$user = User::select('first_name','last_name')->where('email',$email_add)->first();
         $content['name'] = $user['first_name'].' '.$user['last_name'];
            Mail::to($email_add)->send(new RevertMailable($content));*/
        }

        
    	$facility_id = Auth::User()->id;
    	$user = User::where('admin_id',$facility_id)->select('email','username')->orderBy('username','ASC')->get();
        return redirect()->back()->with('message', 'Email has been send successfully');   
    	

    }

    /**
    * This function to view email attachment
    *@return json
    */
    public function viewAttachment($email_id){
        $attachment = \App\EmailAttachment::where('email_id',$email_id)->get();
        $view = view::make('render.email_attachment',array('attach' => $attachment));
        return $view;
        

    }

    /**
    * This function aproves/rehject email attachment
    *@return success or error
    */
    public function approveAttachment($id){
        try {
            EmailAttachment::where('id', $id)->update(['status' => $this->request['value']]);

         } catch (\Exception $e) {
            $data = array(
                'msg' => 'something went wrong',
                'status' => 'error'
            );
            return $data;
        }
        if ($this->request['value'] == 2) {
                $msg = 'Attachment has been rejected';
            } else{
                $msg = 'Attachment has been approved';
            }
        $data = array(
                'msg' => $msg,
                'status' => 'success'
            );

        return $data;
    }

    public function createEmail(){
               /* $data = [
            'params' =>  ["--create", "jdoe@theaxxstablet.com"]
        ];

        
        $string = json_encode($data);
        $string= preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $string);
        
        $process = new Process('curl -k -X POST -H "X-API-Key: ba2c87e4-3fd4-d714-42ba-f1635b7eaf40" -H "Content-Type: application/json" -H "Accept: application/json" "https://54.81.252.152:8443/api/v2/cli/mail/call" -d {"params":["--create","jdoe@theaxxstablet.com"]}');
        $string = "curl -k -X POST -H 'X-API-Key: ba2c87e4-3fd4-d714-42ba-f1635b7eaf40' -H 'Content-Type: application/json' -H 'Accept: application/json' 'https://54.81.252.152:8443/api/v2/cli/mail/call' -d '".'{ "params": ["--create", "jdoe@example.com"]}';
         $process = new Process($string);
        $process->run();
        if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
        }
        echo $process->getOutput();
        die();*/

        $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-API-Key: ba2c87e4-3fd4-d714-42ba-f1635b7eaf40'
];
    $ch = curl_init();
    $facility_id = 'CPCDEMO';
    $inmate_id = '121212';
    $address = $inmate_id.'.'.$facility_id.'@theaxxstablet.com';
    //dd($address);
    curl_setopt($ch, CURLOPT_URL, "https://54.81.252.152:8443/api/v2/cli/mail/call");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"params":["--create","'.$address.'"]}');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 

    $results = curl_exec($ch);
    if (curl_error($ch)) {
    $error_msg = curl_error($ch);
    dd($error_msg);
}
curl_close($ch);
    

    echo $results;
    die();

        //var_dump(openssl_get_cert_locations());
        $xml ='<?xml version="1.0" encoding="UTF-8"?><packet><mail><create><filter><site-id>1</site-id><mailname><name>techdept11</name>
          <mailbox>
                <enabled>true</enabled>
                <quota>1024000</quota>
          </mailbox>
          <forwarding>
                <enabled>true</enabled>
                <address>paul555@testdomain.tst</address>
          </forwarding>
          <alias>michael555</alias>
          <autoresponder>
                <enabled>true</enabled>
                <subject>Your request is accepted</subject>
                <content_type>text/html</content_type>
                <charset>UTF-8</charset>
                <text>Your request will be processed in the nearest 10 days. Thank you.</text>
                <attachment>
                    <tmp-name>/tmp/attachment-file.txt</tmp-name>
                    <file-name>rules.txt</file-name>
                </attachment>
              <forward>techdept@technolux.co.uk</forward>
          </autoresponder>
          <password>
                <value>test123</value>
                <type>plain</type>
          </password>
          <antivir>inout</antivir>
      </mailname>
      <mailname>
          <name>admin11</name>
          <password>
              <value>test</value>
          </password>
          <antivir>inout</antivir>
      </mailname>
   </filter>
</create>
</mail>
</packet>';
        $url='https://54.81.252.152:8443/enterprise/control/agent.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $headr = array();
        $headr[] = 'HTTP_AUTH_LOGIN: admin';
        $headr[] = 'Content-Type: text/xml';
        $headr[] = 'HTTP_AUTH_PASSWD: a8guh2dk~Zu3bGFh ';
        // For xml, change the content-type.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headr);
        
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ask for results to be returned

            // Send to remote and return data to caller.
            
            curl_exec($ch);
        if (curl_error($ch)) {
                 $error_msg = curl_error($ch);
                 dd($error_msg);
        }
            curl_close($ch);

    }

    public function updateIncominMailCharge($inmate_id,$charge){
        //inserting data to inmate activitiy history
        $date = date('Y-m-d');
        $data = array(
            'inmate_id' => $inmate_id,
            'service_id' => 206,
            'start_datetime' => date("Y-m-d h:i:s"),
            'end_datetime' => date("Y-m-dh:i:s")
        );
        $inmate_activity_history = \App\InmateActivityHistory::create($data);
        $sh_data = array(
            'inmate_activity_history_id' => $inmate_activity_history->id,
            'inmate_id' => $inmate_id,
            'service_id' => 206,
            'type' => 1,
            'date' => date('Y-m-d'),
            'status' => 0,
            'charges' => $charge
        );
        $service_history = \App\ServiceHistory::create($sh_data);
    }

    public function whitelistedEmail(){
                return view('wh_email.emaillist');
        }

        public function whitelistedEmaildata(Request $request){
            
            if ($request->ajax()) {
                $getEmail =\App\FreeEmail::all();
                return Datatables::of($getEmail)
                ->addIndexColumn()
                ->editColumn('updated_at', function ($getEmail) {
                    return '<a href="javascript:void(0)" title="View Email"><i style="color:#337ab7" class="fa fa-edit"  onclick="edit_email('.$getEmail->id.')" aria-hidden="true"></i></a>'.' | '.'<a href="javascript:void(0)" title="Delete"><i class="fa fa-trash" onclick="deletewh_email('.$getEmail->id.',1)" aria-hidden="true"></i></a>';
                })
                ->blacklist(['DT_RowIndex','updated_at'])
                ->make(true);
            }else{
                return json_encode(['message' => 'Unsupported request']);
            }  
        }

        public function deleteWhemail($id){
            $res=\App\FreeEmail::where('id',$id)->delete();
              if ($res){
                $data=[
                'status'=>'success',
                'msg'=>'Email Deleted Successfully !'
              ];
              }else{
                $data=[
                'status'=>'error',
                'msg'=>'something went wrong.'
              ];
          }
              return response()->json($data);
        }

        public function addWhitelistedEmail(Request $request){
            $data = $request->all();
            $rules = array(
                'provider' => 'required|unique:free_emails',
                'email' => 'required|email|unique:free_emails',
            );
            $validate = Validator::make($data, $rules);
            if ($validate->fails()) {
                return response()->json(
                    array(
                                    'Code' => 400,
                                    'Status' => \Lang::get('common.validation_error'),
                                    'Message' => $validate->errors()->all(),
                                )
                );
            } else {
                $insert = \App\FreeEmail::create($data);
                if ($insert) {
                    return response()->json(
                        array(
                                        'Code' => 200,
                                        'Status' => \Lang::get('common.success'),
                                        'Message' => 'Whitelisted Email Added Successfully !',
                                    )
                    );
                }else{
                    return response()->json(
                        array(
                                        'Code' => 400,
                                        'Status' => \Lang::get('common.Failure'),
                                        'Message' => \Lang::get('common.something_wrong'),
                                    )
                    );
                }
            }
        }

        public function getEmialdetail($id){
            
                $email_detal = \App\FreeEmail::find($id);
                if ($email_detal) {
                    return response()->json(array(
                                            'Code' => 200,
                                            'Status' => \Lang::get('common.success'),
                                            'Data' => $email_detal,
                                            ));
                }else{
                    return response()->json(array(
                                            'Code' => 404,
                                            'Status' => \Lang::get('common.success'),
                                            'Data' => $email_detal,
                                            ));
                }
            }

        public function updateWhEmail(Request $request){
            $data  = $request->all();
            $rules = $rules = array(
                'provider' => 'required|unique:free_emails,provider,'.$data['id'],
                'email' => 'required|email|unique:free_emails,email,'.$data['id'],
            );
            $validate = Validator::make($data, $rules);
            if ($validate->fails()) {
                return response()->json(
                    array(
                                    'Code' => 400,
                                    'Status' => \Lang::get('common.validation_error'),
                                    'Message' => $validate->errors()->all(),
                                )
                );
            } else {
                $update = \App\FreeEmail::where('id',$data['id'])->update([
                    'provider' => $data['provider'],
                    'email'    => $data['email']
                ]);
                if ($update) {
                    return response()->json(
                        array(
                                        'Code' => 200,
                                        'Status' => \Lang::get('common.success'),
                                        'Message' => 'Whitelisted Email Updated Successfully',
                                    )
                    );
                }else{
                    return response()->json(
                        array(
                                        'Code' => 400,
                                        'Status' => \Lang::get('common.Failure'),
                                        'Message' => \Lang::get('common.something_wrong'),
                                    )
                    );
                }
            }
        }


}

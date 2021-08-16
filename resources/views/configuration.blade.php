@extends('layouts.default')
@section('content')    
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Configuration
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('configuration')}}">Setting</a></li>
            <li class="active">Configuration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                
                <!-- Flash message show -->
                 <div class="alert alert-success" id="alertDiv" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                    <span id="alert"></span>
                </div>
                <!-- Flash message show -->
               <!--<div class="box-body">-->
               <!--     <div class="col-md-6">-->
               <!--         <div class="form-group">-->
               <!--             <a href="{{route('downlaod.apk')}}" ><i class="fa fa-download"></i> Download APK(V1.1.0)</a>-->
               <!--         </div>-->
               <!--     </div>-->
               <!--</div>-->
                <!--<div class="box-body">-->
                <!--    <div class="col-md-6">-->
                <!--        <div class="form-group">-->
                <!--            <a href="{{route('downlaod.apk1')}}" ><i class="fa fa-download"></i> Download APK(V2.1.0)</a>-->
                <!--        </div>-->
                <!--    </div>-->
                   
                <!--</div>-->
               
           

                
                
                <!-- <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{route('downlaod.apk1')}}" ><i class="fa fa-download"></i> Download APK(V5.7)</a>
                        </div>
                    </div>
                </div> -->
                
                 <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{route('downlaod.apk3')}}" ><i class="fa fa-download"></i> Download APK(V2.6) - Latest</a>
                        </div>
                    </div>
                   
                </div>
                
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{route('downlaod.backroommanual')}}" ><i class="fa fa-download"></i> Download backroom user manual</a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{route('downlaod.twowaymanual')}}" ><i class="fa fa-download"></i> Download two way communication user manual</a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{route('downlaod.androidemanual')}}" ><i class="fa fa-download"></i> Download android user manual</a>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#apiurl">API URL Setting</a>
                                </h4>
                            </div>
                            <div id="apiurl" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="APIURL">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Production URL</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="pro_id"  value="{{ $pro_api_url['id']}}">
                                                    
                                                    <input type="text" class="form-control" name="pro_api_url" value="{{ $pro_api_url['content'] or ''}}" id="pro_api_url" required="required">
                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">QA URL</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="qa_id"  value="{{ $qa_api_url['id']}}">
                                                    <input type="text" class="form-control" name="qa_api_url" value="{{ $qa_api_url['content'] or ''}}" id="qa_api_url"  required="">
                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Test URL</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="test_id"  value="{{ $test_api_url['id']}}">
                                                <input type="text" class="form-control" name="test_api_url" value="{{ $test_api_url['content'] or ''}}" id="test_api_url"  required="">
                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary" id="api_url">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">API Endpoint URL***</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#lowblmsg">Low Balance Message</a>
                                </h4>
                            </div>
                            <div id="lowblmsg" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="LowBLMsg">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Low Balance Message</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id"  value="{{ $configurationDetails[16]->id}}">
                                                    <textarea name="low_bl_msg" rows="5" class="form-control" placeholder="Enter a Low Balance here that will pop up when the inmate Balance goes low">{{ $configurationDetails[16]->content or ''}}</textarea>
                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary" id="low_bl_msg">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Low Balance here that will pop up when the inmate Balance goes low***</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#freemin_msg">Free Minutes Message</a>
                                </h4>
                            </div>
                            <div id="freemin_msg" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="FreeMinMsg">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Free Minutes Message</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id"  value="{{ $configurationDetails[17]->id}}">
                                                    <textarea name="exp_freemin_msg" rows="5" class="form-control" placeholder="Enter a Free Minutes here that will pop up when the inmate Free Minutes going to comsume">{{ $configurationDetails[17]->content or ''}}</textarea>
                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary" id="free_min_exp">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Free Minutes here that will pop up when the inmate Free Minutes going to consume***</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#freeminutes">First Free Minutes</a>
                                </h4>
                            </div>
                            <div id="freeminutes" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="autoLoggedform">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">First Free Minutes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id"  value="{{ $configurationDetails[15]->id}}">
                                                    <input type="input" class="form-control intonly" name="value" value="{{ (int)$configurationDetails[15]->value}}" id="auto_logged_time_value" placeholder="Please enter auto logout time">
                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendConfigureData" id="autoLogged">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">First Free Minutes***</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#autoLogoutTime">Tablet Auto Logout Time (In minute)</a>
                                </h4>
                            </div>
                            <div id="autoLogoutTime" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="autoLoggedform">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Tablet Auto Logout Time (In minute)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id"  value="{{ $configurationDetails[0]->id}}">
                                                    <input type="input" class="form-control intonly" name="value" value="{{ $configurationDetails[0]->value}}" id="auto_logged_time_value" placeholder="Please enter auto logout time">
    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendConfigureData" id="autoLogged">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Time will be calculated in Minute for tablet***</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#freeServiceCharge">Tablet Charges After Free Minutes($/per Minutes)</a>
                                </h4>
                            </div>
                            <div id="freeServiceCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="freeTabletConfigform">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Tablet charges($/Minutes)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="freeTimeID"  value="{{ $configurationDetails[1]->id}}">
                                                    <input type="text" class="form-control" name="freeTimeValue" value="{{ $configurationDetails[1]->value}}" id="auto_logged_time_value" placeholder="Please enter auto logout time">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Number of free minutes(Minutes/12 Hours)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="tabletChargeID"  value="{{ $configurationDetails[4]->id}}">
                                                    <input type="text" class="form-control intonly" name="tabletChargeValue" value="{{ $configurationDetails[4]->value}}" id="auto_logged_time_value" placeholder="Please enter auto logout time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendFreeTabletConfigureData" >Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Charges will be calculated in every minute</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#EmailCharge">Email Charge ($/per Email)</a>
                                </h4>
                            </div>
                            <div id="EmailCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="emailChargeform">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Email Charge ($/per Email)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id"  value="{{ $configurationDetails[2]->id}}">
                                                    <input type="text" class="form-control" name="value" value="{{ $configurationDetails[2]->value}}" id="auto_logged_time_value" placeholder="Please enter auto logout time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendConfigureData" id="emailCharge">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Charges($) will be calculated for every sent email</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#attachCharge">Attachment Charge ($/per Attachment)</a>
                                </h4>
                            </div>
                            <div id="attachCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="attachChargeform">
                                        <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Attachment Charge ($/per Attachment)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id"  value="{{ $configurationDetails[11]->id}}">
                                                    <input type="text" class="form-control" name="value" value="{{ $configurationDetails[11]->value}}" id="attach_fee" placeholder="Please Enter Attachment Fee ">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendConfigureData" id="attachCharge">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Charges($) will be calculated for every sent attachment</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#SMSCharge">SMS Charge ($/per SMS)</a>
                                </h4>
                            </div>
                            <div id="SMSCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="smsChargeform">
                                       <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">SMS Charge ($/per SMS)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id" id="auto_logged_time_key" value="{{ $configurationDetails[3]->id}}">
                                                    <input type="text" class="form-control" name="value" value="{{ $configurationDetails[3]->value}}" id="auto_logged_time_value" placeholder="Please enter auto logout time">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendConfigureData" id="smsCharge">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Charges($) will be calculated for every sent SMS</div>
                            </div>
                        </div>
                    </div>
                     <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#BalanceCharge">Set Lower Balance Limit To Notify Family ($)</a>
                                </h4>
                            </div>
                            <div id="BalanceCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="BalanceChargeform">
                                       <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Balance Is Less Than $ </label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id" id="auto_logged_time_key" value="{{ $configurationDetails[5]->id}}">
                                                    <input type="text" class="form-control" name="value" value="{{ $configurationDetails[5]->value}}" id="auto_logged_time_value" placeholder="Please minimum balance check before ssending email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary sendConfigureData" id="BalanceCharge">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Email will be sent to Family if User's account balance is less than ${{ $configurationDetails[5]->value}} </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#NegativeCharge">Set Negative Balance ($)</a>
                                </h4>
                            </div>
                            <div id="NegativeCharge" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="NegativeChargeForm">
                                       <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Balance Goes in Negative Upto $ </label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="">
                                                    <input type="hidden" class="form-control" name="id" id="auto_logged_time_key" value="{{ $configurationDetails[6]->id or ''}}">
                                                    <input type="text" class="form-control" name="value" value="{{ $configurationDetails[6]->value or ''}}" id="auto_logged_time_value" placeholder="Please Insert Negative Balance ">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary" id="NegativeChargesub">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Negative Balance goes upto ${{ $configurationDetails[6]->value or ''}} for Inmates. </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#welcomemsg">Welcome Message</a>
                                </h4>
                            </div>
                            <div id="welcomemsg" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form role="form" method="post" action="javascript:;" id="WelcomeForm">
                                       <div class="alert alert-success" id="alertDiv" style="display:none">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>    
                                            <span id="alert"></span>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Enter Welcome Message</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                    <textarea name="msgcontent" rows="5" class="form-control" placeholder="Enter a Welcome Message here that will pop up when the user loggin in the device">{{ $configurationDetails[7]->content or ''}}</textarea>
                                                
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-md-4">
                                                <div class="">
                                                    <label for="exampleInputEmail1">Status</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <input  id="togglestatus" type="checkbox" class="form-control"  data-toggle="toggle" data-style="ios" data-on="Active" data-off="Inactive"  data-width="100" value="{{$configurationDetails[7]->is_active or ''}}" <?php if (isset($configurationDetails[7]->is_active) && $configurationDetails[7]->is_active  == 1): ?>
                                        checked
                                    <?php endif ?> >
                                    <input type="hidden" name="welcomemsg_status" value="{{$configurationDetails[7]->is_active or '1'}}" id="welcomemsg_status" >
                                    <input type="hidden" class="form-control" name="id" id="auto_logged_time_key" value="{{ $configurationDetails[7]->id or ''}}">
                                                
                                            </div>
                                        </div>
                                        <div class="box-footer text-right">
                                            <button type="submit" class="btn btn-primary" id="Welcomemsgsub">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer">Last Updated on {{$configurationDetails[7]->updated_at}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('/assets/js/customJS/superadmin.js') }}" type="text/javascript"></script>
@stop
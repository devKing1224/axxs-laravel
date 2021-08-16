

<table id="" class="table table-bordered table-striped">
                            
                            <tbody>
                                @php($count = 1) 
                                @if(count($userList)>0)
                                @foreach($userList as $val)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    @hasanyrole('Facility Admin|Facility Staff')
                                    @else
                                     <td><b>{{ $val->facility_name }}</b></td>
                                     <td>{{ $val->location }}</td>
                                    @endhasanyrole
                                    <td>{{ $val->last_name }}</td>
                                    <td>{{ $val->first_name }}</td>
                                    <td>{{ $val->middle_name }}</td>
                                   <td> @if(!empty($val->date_of_birth))
                                    {{  date('m-d-Y', strtotime($val->date_of_birth )) }}
                                    @endif
                                    </td>
                                    <!--<td>{{ phone_number_format($val->phone) }}</td>-->
                                    <!--<td>{{ $val->city }}</td>-->
                                    <!--<td>{{ $val->username }}</td>-->
                                    <td>{{ $val->balance }}</td>
                                     <td>
                                    @role('Super Admin|Facility Admin')
                                        <a href="{{ route('inmate.view', ['id' => $val->id]) }}" data-toggle="tooltip" title="View" ><i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('inmate.add', ['id' => $val->id]) }}" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="inmateID" id="{{ $val->id }}" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>
                                        
                                        <a href="{{ route('inmate.servicedetails', ['id' => $val->id]) }}" data-toggle="tooltip" title="Service Details" ><i class="fa fa-server"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('family.list', ['id' => $val->id]) }}" data-toggle="tooltip" title="Family Details" ><i class="fa fa-home"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @if(App\SentInmateEmail::getBlacklistedbyinmate($val->id))
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope" style="color:red"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        @if(App\InmateSMS::getBlacklistedsmsbyinmate($val->id)) 
                                        <a href="{{ route('inmate.sms', ['id' => $val->id]) }}" data-toggle="tooltip" title="SMS Details" ><i class="fa fa-mobile" style="color:red"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                          <a href="{{ route('inmate.sms', ['id' => $val->id]) }}" data-toggle="tooltip" title="SMS Details" ><i class="fa fa-mobile"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif
                                        <a href="{{ route('inmate.inmateloggedhistory', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Logged Details" ><i class="fa fa-hdd-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                       <a href="{{ route('inmate.inmateactivity', ['id' => $val->id]) }}" data-toggle="tooltip" title="Activity History Details" <i class="fa fa-history"></i>&nbsp;&nbsp;&nbsp;</a>
                                       
                                       @if(App\InmateContacts::getVarificationInmate($val->id)) 
                                        <a href="{{ route('contactlist', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Contact Person" ><i class="fa fa-book" style="color:maroon"></i>&nbsp;&nbsp;&nbsp;</a>
                                       @else
                                        <a href="{{ route('contactlist', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Contact Person" ><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;</a>
                                         @endif
                                        
                                        <a href="{{ route('userreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Service Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('user_email_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Email List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('user_contact_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Mobile List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('user_service_history_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Activity History Details Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @can('Download Fund Report')
                                        <a href="{{ route('user_fund_history_report', ['id' => $val->id]) }}" data-toggle="tooltip"  title="Download Fund Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @else
                                        @can('Manage Users')
                                        <a href="{{ route('inmate.view', ['id' => $val->id]) }}" data-toggle="tooltip" title="View" <i class="fa fa-eye"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href="{{ route('inmate.add', ['id' => $val->id]) }}" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                                        <a href='javascript:;' token="{{ csrf_token() }}" class="inmateID" id="{{ $val->id }}" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>
                                        
                                        <a href="{{ route('inmate.inmateloggedhistory', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Logged Details" ><i class="fa fa-hdd-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                     
                                        @endcan
                                        @can('Enable Service Permission For Users')
                                        <a href="{{ route('inmate.servicedetails', ['id' => $val->id]) }}" data-toggle="tooltip" title="Service Details" ><i class="fa fa-server"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Manage Family')
                                        <a href="{{ route('family.list', ['id' => $val->id]) }}" data-toggle="tooltip" title="Family Details" ><i class="fa fa-home"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('View User Email')
                                        <a href="{{ route('inmate.sentinmateemaillist', ['id' => $val->id]) }}" data-toggle="tooltip" title="Email Details" ><i class="fa fa-envelope"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('View User SMS')
                                        <a href="{{ route('inmate.sms', ['id' => $val->id]) }}" data-toggle="tooltip" title="SMS Details" ><i class="fa fa-mobile"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Manage User Contacts')
                                        <a href="{{ route('contactlist', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Contact Person" ><i class="fa fa-book"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User With Services Report')
                                        <a href="{{ route('userreport', ['id' => $val->id]) }}" data-toggle="tooltip" title="User Service Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User Email List Report')
                                        <a href="{{ route('user_email_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Email List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @can('Download User Number List Report')
                                        <a href="{{ route('user_contact_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Download Mobile List Report" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                           <a href="{{ route('user_service_history_report', ['id' => $val->id]) }}" data-toggle="tooltip" title="Activity History Details" ><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endcan
                                        @endrole 

                                        @role('Facility Admin')
                                        <a href="{{ route('user_dispute_report', ['id' => $val->id]) }}"  token="{{ csrf_token() }}"  maxtype="email"    value="{{ $val->id }}"  @if($val->is_view == 1) style="color: red"  inmate_id={{$val->id}}@endif title="User Dispute Report" ><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;&nbsp;</a>

                                        
                                        @if($maxLimitbyfacility)
                                        @if($maxLimitbyfacility->max_phone)
                                        @if($maxLimitbyfacility->max_email)

                                        @else
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="email"   data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @endif  
                                        @else
                                        @if($maxLimitbyfacility->max_email)
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="phone" data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>
                                        @else
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="none"  data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>  
                                        @endif
                                        @endif
                                        @else
                                        <a href="" token="{{ csrf_token() }}" class="Maxlimitview" maxtype="none"  data-toggle="modal" data-target="#imageUploadModal" value="{{ $val->id }}" title="Set Max Limit" ><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;</a>

                                        @endif
                                        @endrole
                                    </td>
                                </tr>
                                @endforeach
                                @else

                                <span>No Results Found</span>

                                @endif

                            </tbody>
                        </table>
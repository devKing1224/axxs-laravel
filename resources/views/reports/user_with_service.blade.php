<html>
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Facility ID</th>
                <th>Facility User ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Birthday</th>
                <th>Username</th>
                <th>Phone </th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Service Category</th>
                <th>Service Name</th>
                <th>Feature On</th>
                <th>Feature Off</th>
                <th>Email</th>
            </tr>
            @if(count($serviceList) > 0 )
             @foreach($serviceList as $index => $servicelist)
            <tr>
                <td>@if($index == 0) {{ $users->inmateFacility->name }} @endif</td>
                <td>@if($index == 0) {{ $users->inmateFacility->facility_id }} @endif</td>
                <td>@if($index == 0) {{ $users->inmateFacility->id }} @endif</td>
                <td>@if($index == 0) {{ $users->last_name }} @endif</td>
                <td>@if($index == 0) {{ $users->first_name }} @endif</td>
                <td>@if($index == 0) {{ $users->middle_name }} @endif</td>
                <td>@if($index == 0) {{ $users->date_of_birth }} @endif</td>
                <td>@if($index == 0) {{ $users->username }} @endif</td>
                <td>@if($index == 0) {{ phone_number_format($users->phone) }} @endif</td>
                <td>@if($index == 0) {{ $users->address_line_1 }} {{ $users->address_line_2 }} @endif</td>
                <td>@if($index == 0) {{ $users->city }} @endif</td>
                <td>@if($index == 0) {{ $users->state }} @endif</td>
                <td>@if($index == 0) {{ $users->zip }} @endif</td>
                <td>@if($servicelist->Service_category_name == "") NULL @else {{ $servicelist->Service_category_name }}@endif</td>
                <td>{{ $servicelist->name }}</td>
                <td> <?php $feature = 'Off' ?>
                    @foreach($list as $value)
                        @if($value->id === $servicelist->id)
                         <?php $feature = 'On' ?>
                         On
                        @endif
                    @endforeach
                </td>
                <td>
                    @if($feature == 'Off')
                        Off
                    @endif
                </td>
              
                <td>@if($index == 0) @if(isset($useremailinfo->email)){{ $useremailinfo->email }} @else No Email @endif @endif</td>
           
                
            </tr>
             @endforeach
             @else
             <tr>
                <td>{{ $users->inmateFacility->name }}</td>
                <td>{{ $users->inmateFacility->facility_id }}</td>
                <td>{{ $users->inmateFacility->id }}</td>
                <td>{{ $users->last_name }} </td>
                <td>{{ $users->first_name }} </td>
                <td>{{ $users->middle_name }} </td>
                <td>{{ $users->date_of_birth }} </td>
                <td>{{ $users->inmate_id }} </td>
                <td>{{ phone_number_format($users->phone) }} </td>
                <td>{{ $users->address_line_1 }} {{ $users->address_line_2 }}</td>
                <td>{{ $users->city }} </td>
                <td>{{ $users->state }} </td>
                <td>{{ $users->zip }} </td>
                <td> -</td>
                <td> -</td>
                <td> - </td>
                <td> - </td>              
                <td>@if(isset($useremailinfo->email)){{ $useremailinfo->email }} @else No Email @endif</td>
            </tr>
             @endif
        </thead>
    </table>
</html>

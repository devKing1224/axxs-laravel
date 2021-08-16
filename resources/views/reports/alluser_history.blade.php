<html>
<table>
    <thead>
        <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Date</th>
            </tr>
            <tr>
                <td>{{ $fac_name }}</td>
                <td>{{ $date }}</td>
            </tr>
    </thead>
</table>
    @if(count($users)>0)
    @foreach($users as $user)
     @if(count($user->vendorsInfoHistory) > 0 )
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
             
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>@if($user->inmateEmail && $user->inmateEmail->email) {{ $user->inmateEmail->email }} @else No Email @endif</td>
            </tr>

        </thead>
    </table>
    <table>
        <thead>
            <tr style="background: #FFE495">
                <th>S. No.</th>
                <th>Service Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration (seconds)</th>
                <th>Per Minute Type</th>
                <th>Per Minute Rate</th>
                <th>Charges ($.$$)</th>
                <th>Free Minutes Used</th>  

            </tr>
            @php($count = 1 )
            @if(count($user->vendorsInfoHistory) > 0 )
            @foreach($user->vendorsInfoHistory as $val)
            @if($val->end_time !== '')
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $val->vendorDetailsNames->name }}</td>
                <td>{{ $val->start_datetime }}</td>
                <td>{{ $val->end_datetime }}</td>
                <td>@if(!empty($val->vendorDetails->duration)) {{ $val->vendorDetails->duration }} @else -- @endif </td>
                <td>@if($val->vendorDetailsNames->type == 0) Free @elseif($val->vendorDetailsNames->type == 1) Facility Rate @elseif($val->vendorDetailsNames->type == 2) Premium Rate @else -- @endif </td>
                <td>@if($val->vendorDetailsNames->type == 0) 0.00 @elseif($val->vendorDetails->type == 1) {{ $val->vendorDetails->rate }} @elseif($val->vendorDetailsNames->type == 2) {{ $val->vendorDetails->rate }} @else -- @endif </td>
                <td>@if(!empty($val->vendorDetails->charges)) {{ $val->vendorDetails->charges }} @else -- @endif </td>
                <td>@if(!empty($val->vendorDetails->free_minutes_used)) {{ $val->vendorDetails->free_minutes_used }} @elseif($val->vendorDetailsNames->type == 0) 0 @else -- @endif </td>

            </tr>
            @endif
            @endforeach
            @else 
            <tr>

                <td>1</td>
                <td>None</td>
                <td> -</td>
                <td> -</td>
                <td> -</td>
                <td>No Data Found</td>                                           
            </tr>
            @endif
        </thead>
    </table>
    @endif
    @endforeach
    @endif
</html>

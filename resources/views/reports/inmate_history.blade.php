<html>
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Facility ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Birthday</th>
                <th>User ID</th>
                <th>User Email</th>
            </tr>
             
            <tr>
                <td>@if(isset($user->inmateFacility) && !empty($user->inmateFacility)){{ $user->inmateFacility->name }} @else - @endif</td>
                <td>@if(isset($user->inmateFacility) && !empty($user->inmateFacility)) {{ $user->inmateFacility->facility_id }} @else - @endif</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->first_name }} </td>
                <td>{{ $user->middle_name }}</td>
                <td>{{ $user->date_of_birth }}</td>
                <td>{{ $user->inmate_id }}</td>
                <td>@if(isset($user->inmateEmail) && !empty($user->inmateEmail)){{ $user->inmateEmail->email }} @else - @endif</td>
            </tr>

        </thead>
    </table>
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>S. No.</th>
                <th>Service Name</th>
                <th>Start Date Time</th>
                <th>End Date Time</th>
                <th>Duration (seconds)</th>
                <th>Per Minute Type</th>
                <th>Per Minute Rate</th>
                <th>Charge ($.$$)</th> 
                <th>Free Minutes Used</th>

            </tr>
            @php($count = 1 )
            @if(count($inmate_activity_history) > 0 )
            @foreach($inmate_activity_history as $val)
            @if($val->end_datetime !== '')
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $val->name }}</td>
                <td>{{ $val->start_datetime }}</td>
                <td>{{ $val->end_datetime }}</td>
                <td>@if(!empty($val->duration)) {{ $val->duration }} @else -- @endif </td>
                <td>@if($val->type == 0) Free @elseif($val->type == 1) Facility Rate @elseif($val->type == 2) Premium Rate @else -- @endif </td>
                <td>@if($val->type == 0) 0.00 @elseif($val->type == 1) {{ $val->rate }} @elseif($val->type == 2) {{ $val->rate }} @else -- @endif </td>
                <td>@if(!empty($val->charges) && $val->end_datetime !== '') {{ $val->charges }} @elseif($val->type == 0) Free @else -- @endif </td>
                <td>@if(!empty($val->free_minutes_used)) {{ $val->free_minutes_used }} @elseif($val->type == 0) 0 @else -- @endif </td>
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
</html>

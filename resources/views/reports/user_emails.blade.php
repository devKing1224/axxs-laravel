<html>
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Facility ID</th>
                <th>Facility User ID</th>
                <th>User Last Name</th>
                <th>User First Name</th>
                <th>User Middle Name</th>
                <th>Birthday</th>
                <th>Username</th>
                <th>Email Address</th>
                <th>Email Service </th>
            </tr>
             
            <tr>
                <td>{{ $user->inmateFacility->name }}</td>
                <td>{{ $user->inmateFacility->facility_id }}</td>
                <td>{{ $user->inmateFacility->id }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->middle_name }}</td>
                <td>{{ $user->date_of_birth }}</td>
                <td>{{ $user->username }}</td>
                <td>@if($useremailinfo) {{ $useremailinfo->email }} @else - @endif</td>
                <td>@if(count($serviceinfo->ServiceByUser))Yes @else No @endif</td>
            </tr>

        </thead>
    </table>
    <table>
        <thead>
            <tr style="background: #FFE495">
                <th>Facility Name</th>
                <th>Facility ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Birthday</th>
                <th>User ID</th>
                <th>Contact Name</th>
                <th>Email</th>
            </tr>
            @if(count($user->contactEmailList) > 0)
                 @foreach($user->contactEmailList as $index => $allemail)
                    <tr>
                        <td>@if( $index == 0) {{ $user->inmateFacility->name }} @endif</td>
                        <td>@if( $index == 0) {{ $user->inmateFacility->facility_id }} @endif</td>
                        <td>@if( $index == 0) {{ $user->last_name }} @endif</td>
                        <td>@if( $index == 0) {{ $user->first_name }} @endif</td>
                        <td>@if( $index == 0) {{ $user->middle_name }} @endif</td>
                        <td>@if( $index == 0) {{ $user->date_of_birth }} @endif</td>
                        <td>@if( $index == 0) {{ $user->inmate_id }} @endif</td>
                        <td>{{ $allemail->name }}</td>
                        <td>{{ $allemail->email_phone }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $user->inmateFacility->name }}</td>
                    <td>{{ $user->inmateFacility->facility_id }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->middle_name }}</td>
                    <td>{{ $user->date_of_birth }}</td>
                    <td>{{ $user->inmate_id }}</td>
                    <td>No Data Available</td>
                    <td>Not Any Email Found</td>
                </tr>
            @endif
        </thead>
    </table>
</html>

<html>
    <table>
        <thead>
            <tr style="background: #D7D7D7">

                <th>Facility Name Inactive</th>
                <th>Facility Id</th>
                <th>User ID</th>
                <th>User Last Name</th>
                <th>User First Name</th>
                <th>User Middle Name</th>
                <th>Birthday</th>
                <th>Phone</th> 
                <th>City</th>
                <th>Email</th>
                <th>Balance</th>

            </tr>
            @if(count($facility->facilityinactiveusers)> 0) 
                @foreach($facility->facilityinactiveusers as $index => $user)
                    <tr>
                        <td>@if($index== 0) {{ $facility->name }} @endif</td>
                        <td>@if($index== 0) {{ $facility->facility_id }} @endif</td>
                        <td> {{ $user->inmate_id }}</td>
                        <td> {{ $user->last_name }}</td>
                        <td> {{ $user->first_name }}</td>
                        <td> {{ $user->middle_name }}</td>
                        <td> {{ $user->date_of_birth }}</td>
                        <td> {{ phone_number_format($user->phone) }}</td>              
                        <td> {{ $user->city }}</td>
                        <td> @foreach($useremailinfo as $emails)
                            @if($emails->inmate_id == $user->id)
                            {{ $emails->email }}
                            @endif
                            @endforeach
                        </td>
                        <td> {{ $user->balance }}</td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $facility->name }}</td>
                    <td>{{ $facility->facility_id }}</td>
                    <td> -</td>
                    <td> -</td>
                    <td> -</td>
                    <td> -</td>              
                    <td> -</td>
                    <td> -</td>
                    <td> -</td>
                    <td> -</td>
                    <td> -</td>

                </tr>
            @endif
        </thead>
    </table>
</html>

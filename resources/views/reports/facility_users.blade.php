<html>
    <table>
        <thead>
            <tr style="background: #D7D7D7">

                <th>Facility Name</th>
                <th>Facility Id</th>
                <th>User Last Name</th>
                <th>User First Name</th>
                <th>User Middle Name</th>
                <th>birthday</th>
                <th>Phone</th> 
                <th>City</th>
                <th>Username</th>
                <th>Balance</th>
                <th>Status</th>

            </tr>
            @if(count($facility->facilityusers) > 0)
            @foreach($facility->facilityusers as $index => $user)
            <tr>

                <td>@if($index== 0) {{ $facility->name }} @endif</td>
                <td>@if($index== 0) {{ $facility->facility_id }} @endif</td>
                <td> {{ $user->last_name }}</td>
                <td> {{ $user->first_name }}</td>
                <td> {{ $user->middle_name }}</td>
                <td> {{ $user->date_of_birth }}</td>
                <td> {{ phone_number_format($user->phone) }}</td>              
                <td> {{ $user->city }}</td>
                <td> {{ $user->username }}</td>
                <td> {{ $user->balance }}</td>
                <td> @if($user->is_deleted == 0 ) Active @else Inactive @endif</td>

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
                <td> -</td>

            </tr>
            @endif
        </thead>
    </table>
</html>

<html>
    <table>
        <thead>
             <tr style="background: #D7D7D7">
                <th>Name</th>
                <th>Id</th>
                <th>Total User</th>
                <th>Username</th>
                <th>Email</th> 
                <th>Phone Number</th>
                <th>Twilio Number</th>
                <th>Address 1</th>
                <th>Address 2</th>
                <th>State</th>
                <th>City</th>
                <th>Status</th>
                
            </tr>
             @foreach($facility as $index => $facility)
            <tr>
               
                <td> {{ $facility->name }}</td>
                <td> {{ $facility->facility_id }}</td>
                <td> {{ $facility->total_inmate }}</td>
                <td> {{ $facility->facilityuser->username }}</td>
                <td> {{ $facility->email }}</td>              
                <td> {{ phone_number_format($facility->phone) }}</td>
                <td> {{ phone_number_format($facility->twilio_number) }}</td>
                <td> {{ $facility->address_line_1 }}</td>
                <td> {{ $facility->address_line_2 }}</td>
                <td> {{ $facility->state }}</td>
                <td> {{ $facility->city }}</td>
                <td> @if($facility->is_deleted == 0 ) Active @else Inactive @endif</td>
                               
            </tr>
             @endforeach
        </thead>
    </table>
</html>

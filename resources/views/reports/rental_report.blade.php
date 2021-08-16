<html>
    <table>
        <thead>
          <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Total User</th>
                <th>Email</th> 
                <th>Address 1</th>
                <th>Address 2</th>
                <th>city</th>
                <th>State</th>
                <th>Total Charges</th>
             
            </tr>
            
          @foreach($facility as $index => $facility)
          
            <tr>

             <td> {{ $facility['name'] }}</td>
             <td> {{ $facility['total_inmate'] }}</td>
             <td> {{ $facility['email'] }}</td>
             <td> {{ $facility['address_line_1'] }}</td>
             <td> {{ $facility['address_line_2'] }}</td>
             <td> {{ $facility['city'] }}</td>
             <td> {{ $facility['state'] }}</td> 
             <td>{{ $facilityTotal }}</td>         
            </tr>
           @endforeach
        </thead>
    </table>

      <table>
        <thead>

             <tr style="background: #FFE495">
                <th>S. No.</th>
                <th>User First Name</th>
                <th>Last Name</th> 
                <th>Email</th>
                <th>Blance</th>
                <th>Address 1</th>
                <th>Address 2</th>
                <th>City</th>
                <th>State</th>
                <th>DOB</th>
              
                <th>Charges</th>
             
            </tr>
            @php($count = 1 )
           @foreach($facility->facilityusers as $facilitysuser)             
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $facilitysuser->first_name }}</td>
                    <td>{{ $facilitysuser->last_name }}</td>
                    <td>{{ $facilitysuser->email }}</td>
                    <td>{{ $facilitysuser->balance }}</td>
                    <td>{{ $facilitysuser->address_line_1 }}</td>                    
                    <td>{{ $facilitysuser->address_line_2 }}</td>
                    <td>{{ $facilitysuser->city }}</td>
                    <td>{{ $facilitysuser->state }}</td>
                  
                    <td>{{ $facilitysuser->date_of_birth }}</td>
                    <td>
                        {{ $facilitysuser->charges }}
                   
                    </td>
                </tr>
                @endforeach
        </thead>
    </table>
</html>
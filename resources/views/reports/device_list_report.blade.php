<html>
    
    
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>S No.</th>
                @if($is_admin == 1)
                <th>Facility Name</th>
                @endif
                <th>IMEI</th>
                <th>Device Provider</th>
                <th>Device ID</th>
                <th>Added On Date</th>
                <th>Password</th>
            </tr>
            @if(count($device)>0)
            <?php $count = 1; ?>
             @foreach($device as $devices)
            <tr>
                <td>{{ $count ++}}</td>
                @if($is_admin == 1)
                <td>{{ $devices->facility_name }}</td>
                @endif
                <td>{{ $devices->imei }}</td>
                <td>{{ $devices->device_provider }}</td>
                <td>{{ $devices->device_id }}</td>
                <td>{{ date('d-M-Y',strtotime($devices->created_at)) }}</td>
                
                <td>@if( $devices->device_password == null) N/A @else {{ $devices->device_password }} @endif</td>
                

            </tr>
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

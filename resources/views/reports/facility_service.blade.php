<html>
    <table>
        <thead>
             <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Facility Id</th>
                <th>Service Name</th>
                <th>Category Name</th>
                <th>Service Type</th> 
                
            </tr>
            @if(count($facilityServiceList) > 0 )
             @foreach($facilityServiceList as $index => $service)
            <tr>
               
                <td>@if($index == 0) {{ $user->name }} @endif</td>
                <td>@if($index == 0) {{ $user->facility_id }} @endif</td>
                <td> {{ $service->name }}</td>
                <td>@if($service->Service_category_name) {{ $service->Service_category_name }} @else Null @endif</td>
                <td> @if($service->type == 0 )Free @else Paid @endif</td>                                           
            </tr>
             @endforeach
             @else 
             <tr>
               
                <td>{{ $user->name }} </td>
                <td> {{ $user->facility_id }}</td>
                <td> -</td>
                <td> -</td>
                <td> -</td>                                           
            </tr>
             @endif
        </thead>
    </table>
</html>

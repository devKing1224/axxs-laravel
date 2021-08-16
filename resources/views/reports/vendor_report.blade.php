<html>
    
    @foreach($user as $index => $facility)
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Facility ID</th>
                <th>Service Name</th>
                <th>Service Category</th>
                <th>On/Off</th>
            </tr>
             
            <tr>
                <td>@if(isset($facility->detailFacility->name)){{ $facility->detailFacility->name }}@endif</td>
                <td>@if(isset($facility->detailFacility->facility_id)){{ $facility->detailFacility->facility_id }}@endif</td>
                <td>{{ $service->name }}</td>
                <td>@if($service->service_category_id == null) NULL @else {{ $service->serviceCategory->name  }} @endif</td>
                <td>@if (count($facility->getFacilityService) > 0 ) On @else Off @endif</td>
            </tr>

        </thead>
    </table>
    <table>
        <thead>
            <tr style="background: #FFE495">
                <th>User Last Name</th>
                <th>User First Name</th>
                <th>User Middle Name</th>
                <th>Birthday</th>
                <th>User ID by Facility</th>
                <th>User ID</th>
                <th>Login Date Time</th>
                <th>Logout Date Time</th>
                <th>Charges</th>
            </tr>
            
            @if(count($facility->allInmateByFacility) > 0)
                 @foreach($facility->allInmateByFacility as $i => $inmate)
                    @if(count($inmate->vendorInfo) > 0)
                       @foreach($inmate->vendorInfo as $j => $vendor)
                       <tr>
                           <td>@if( $j == 0 ){{ $inmate->last_name }} @endif</td>
                           <td>@if( $j == 0 ){{ $inmate->first_name }} @endif</td>
                           <td>@if( $j == 0 ){{ $inmate->middle_name }} @endif</td>
                           <td>@if( $j == 0 ){{ $inmate->date_of_birth }} @endif</td>
                           <td>@if( $j == 0 ){{ $inmate->inmate_id }} @endif</td>
                           <td>@if( $j == 0 ){{ $inmate->id }} @endif </td>
                           <td>{{ $vendor->date }} {{ $vendor->start_time }}</td>
                           <td>{{ $vendor->date }} {{ $vendor->end_time }}</td>
                           <td>@if(isset($vendor->vendorDetails)){{ $vendor->vendorDetails->charges }} @else 0 @endif</td>
                           
                       </tr>
                       @endforeach
                    @else 
                         <tr >
                           <td>{{ $inmate->last_name }} </td>
                           <td> {{ $inmate->first_name }} </td>
                           <td> {{ $inmate->middle_name }} </td>
                           <td> {{ $inmate->date_of_birth }} </td>
                           <td> {{ $inmate->inmate_id }}</td>
                           <td> {{ $inmate->id }} </td>
                           <td> -</td>
                           <td> -</td>
                           <td> -</td>
                           
                       </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                </tr>
            @endif
        </thead>
    </table>
    @endforeach
</html>

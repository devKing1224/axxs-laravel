<html>
    @if(isset($user))
    @if($facility == true)
    <table>
        <thead>
            <tr style="background: #D7D7D7" >
                <th>Facility Name</th>
                <th>Date Range</th>
            </tr>
            <tr>
                <td>{{ $user->facility_name }}</td>
                <td>{{$s_date}} To {{$e_date}}</td>
            </tr>
        </thead>    
    </table>
    @endif

    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>First Name</th>
                <th>Last Name</th>
                @if($facility == false)
                <th>Birthday</th>
                <th>User ID</th>
                @endif
                <th>Email</th>
            </tr>
             
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                @if($facility == false)
                <td>{{ $user->date_of_birth }}</td>
                <td>{{ $user->inmate_id }}</td>
                @endif
                <td>@if($user->inmateEmail && $user->inmateEmail->email) {{ $user->inmateEmail->email }} @elseif($user->email) {{ $user->email }} @else No Email @endif</td>
            </tr>

        </thead>
    </table>

    <table>
        @php
        $sum = 0;
        $sum_charges = 0;
        $sum_free_minutes_used = 0;
        @endphp
        <thead>
            <tr style="background: #D7D7D7">
                <th>Service Name</th>
                <th>Spend Duration (Minutes)</th>
                <th>Charges</th>
                <th>Free Minutes Used</th>
            </tr>
            @if(count($service) > 0)
            @foreach($service as $key=>$services) 
            <tr>
                <td>{{ $services->name }}</td>
                <td>
                @php
                    $total =  ($services->total_duration);
                    echo $total ;
                    $sum+= $total ;
                @endphp
               </td>
               <td>
                @php
                    $total_charges =  $services->charges;
                    echo $total_charges ;
                    $sum_charges+= $total_charges ;
                @endphp
               </td>
               <td>
                @php
                    if(!empty($services_history->free_minutes_used))
                        $total_free_minutes_used = $services_history->free_minutes_used;
                    else
                        $total_free_minutes_used = 0;
                    echo $total_free_minutes_used;
                    $sum_free_minutes_used += $total_free_minutes_used;
                @endphp
                </td>
            </tr>
            
            @endforeach
            
            @else
            <tr>
                <td>No Service Used</td>
                <td>Null</td>
            </tr>
            @endif

            <tr style="background: #D7D7D7">
                <th>Total</th>
                <th>{{$sum}}</th>
                <th>{{$sum_charges}}</th>
                <th>{{$sum_free_minutes_used}}</th>

            </tr>

        </thead>
        

    </table>



    
    @endif



    </html>
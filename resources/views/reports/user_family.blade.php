<html>
    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>Facility Name</th>
                <th>Facility User Id</th>
                <th>User Last Name</th>
                <th>User First Name</th>
                <th>User Middle Name</th>
                <th>Birthday</th>
                <th>User Id</th>
                <th>Family Last Name</th>
                <th>Family First Name</th>
                <th>Family Phone</th>
                <th>Family City</th>
                <th>Family Email</th>
                <th>Family Active/Inactive</th>

            </tr>
            @if(count($user) > 0))
                @foreach($user as $index => $facility)
                    @if(count($facility->facilityUsersWithFamily) > 0)
                        @foreach($facility->facilityUsersWithFamily as $index1 => $userdetail)
                            @if(count($userdetail->familyInfo) > 0 )
                                @foreach($userdetail->familyInfo as $index2 => $family)
                                    <tr style="wrap-text: true;"> 
                                    @if($index1 == 0 && $index2 == 0) <td style="background: #EEE"> @else <td> @endif
                                            @if($index1 == 0 && $index2 == 0) {{ $facility->name }} @endif</td>
                                        @if($index1 == 0 && $index2 == 0) <td style="background: #EEE"> @else <td> @endif
                                            @if($index1== 0 && $index2== 0) {{ $facility->facility_user_id }} @endif </td>
                                        
                                         @if($index2 == 0) <td style="background:#FFE495 "> @else <td> @endif
                                             @if($index2== 0){{ $userdetail->last_name }}@endif</td>
                                             
                                         @if($index2 == 0) <td style="background:#FFE495 "> @else <td> @endif
                                             @if($index2== 0) {{ $userdetail->first_name }} @endif</td>
                                             
                                        @if($index2 == 0) <td style="background:#FFE495 "> @else <td> @endif
                                             @if($index2== 0){{ $userdetail->middle_name }}@endif</td>
                                             
                                         @if($index2 == 0) <td style="background:#FFE495 "> @else <td> @endif
                                             @if($index2== 0){{ $userdetail->date_of_birth }}@endif</td>
                                             
                                         @if($index2 == 0) <td style="background:#FFE495 "> @else <td> @endif
                                             @if($index2== 0) {{ $userdetail->inmate_id }} @endif</td>
                                        <td> {{ $family->last_name }}</td>
                                        <td> {{ $family->first_name }}</td>
                                        <td>{{ phone_number_format($family->phone) }} </td>
                                        <td>{{ $family->city }} </td>
                                        <td>{{ $family->email }} </td>
                                        <td> @if($family->is_deleted == 0 ) Active @else Inactive @endif</td>
                                    </tr>
                                    <br>
                                @endforeach
                            @else
                                <tr>
                                    @if($index1 == 0) <td style="background: #EEE"> @else <td > @endif
                                       @if($index1== 0)  {{ $facility->name }} @endif</td>
                                    @if($index1 == 0) <td style="background: #EEE"> @else <td> @endif
                                        @if($index1== 0) {{ $facility->facility_user_id }} @endif </td>
                                    <td style="background:#FFE495 ">{{ $userdetail->last_name }}</td>
                                    <td style="background:#FFE495 ">{{ $userdetail->first_name }} </td>
                                    <td style="background:#FFE495 ">{{ $userdetail->middle_name }} </td>
                                    <td style="background:#FFE495 ">{{ $userdetail->date_of_birth }} </td>
                                    <td style="background:#FFE495 ">{{ $userdetail->inmate_id }} </td>
                                    <td> -</td>
                                    <td> -</td>
                                    <td> -</td>
                                    <td> -</td>
                                    <td> -</td>
                                </tr>
                                <br>
                            @endif

                        @endforeach
                    @else 
                        <tr>
                            <td style="background: #EEE"> {{ $facility->name }} </td>
                             <td style="background: #EEE"> {{ $facility->facility_user_id }} </td>
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
                            <td> -</td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </thead>
    </table>
</html>

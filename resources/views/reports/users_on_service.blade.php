<html>
<table>
  <thead>
    <tr style="background: #D7D7D7">
      <th>Services/Time</th>
      @foreach($time_slots as $time)
      <th>{{$time}}</th>
      @endforeach
    </tr>
    
    @if(count($service) > 0)
    @foreach($service as $key=>$service)
    <tr>
      <td style="background: #D7D7D7">{{$service['name']}}</td>
      @foreach($user_data as $userdata)
      @if($service['id'] == $userdata['service_id'])
      @foreach($userdata['user_data'] as $user)
      <td >{{$user}}</td>
      @endforeach
      @endif
      @endforeach
    </tr>
    @endforeach
    @else
    
    <tr>
      <td style="background: #D7D7D7;text-align: center;" colspan="13">No Data Found</td>
    </tr>
    @endif
  </thead>
</table>
</html>
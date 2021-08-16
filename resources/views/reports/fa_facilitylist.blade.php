<html>
@if(isset($fadmin))
<table>
  <thead>
    <tr style="background: #337ab7">
          <th>Facility Admin Name</th>
          <th>Total Facility</th>
          <th>Email</th> 
      </tr>
     
    
    
      <tr>

       <td> {{ $fadmin['first_name'] }}</td>  
       <td> {{ $fadmin['total_facility'] }}</td>
       <td> {{ $fadmin['email'] }}</td>    
      </tr>
     
  </thead>
@endif

</table>


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
            </tr>
           
          @foreach($facility as $index => $facility)
          
            <tr>

             <td> {{ $facility->name }}</td>
             <td> {{ $facility['total_inmate'] }}</td>
             <td> {{ $facility['email'] }}</td>
             <td> {{ $facility['address_line_1'] }}</td>
             <td> {{ $facility['address_line_2'] }}</td>
             <td> {{ $facility['city'] }}</td>
             <td> {{ $facility['state'] }}</td>      
            </tr>
           @endforeach
        </thead>
    </table>
</html>
<html>
    <table>
        <thead>
             <tr style="background: #D7D7D7">
                <th>Service Category Name</th>
                <th>Service Name</th>
                <th>Service Type</th>
                <th>Charges/Hourly($)</th>
                <th>Icon</th>
                <th>Start Page Url</th>
            </tr>
             @foreach($serviceList as $servicelist)
            <tr>
                <td>
                    @if($servicelist->Service_category_name == "") 
                    NULL 
                    @else 
                    {{ $servicelist->Service_category_name }}
                    @endif
                </td>
                <td>{{ $servicelist->name }}</td>
                <td>@if(isset($servicelist->type) && $servicelist->type == 0) Free  @else Paid @endif</td>
                <td>{{ $servicelist->charge }} $</td>
                <td>{{ $servicelist->logo_url }}</td>
                <td>{{ $servicelist->base_url }}</td>
            </tr>
             @endforeach
        </thead>
    </table>
</html>

<html>
    
    <table>
        <thead>
        <tr style="background: #D7D7D7">
            <th>Name</th>
            <th>D.O.B</th>
            <th>Amount ($)</th>
        </tr>

        <tr>
            @if(isset($inmate))
            <td>{{ $inmate['first_name'].' '.$inmate['last_name']}}</td>
            <td>@if($inmate['date_of_birth'] == null) N/A @else {{ $inmate['date_of_birth']}} @endif</td>
            <td>{{ $inmate['balance']}}</td>
            @endif
        </tr>
    </thead>
</table>

    <table>
        <thead>
            <tr style="background: #D7D7D7">
                <th>S No.</th>
                <th>Family ID</th>
                <th>Payment Status</th>
                <th>Transaction ID</th>
                <th>Depositor Email</th>
                <th>Depositor Name</th>
                <th>Amount ($)</th>
                <th>Date</th>
            </tr>
            @if(count($pymnt_info)>0 || count($purchase_inmate)>0)
            <?php $count = 1; ?>
             @foreach($pymnt_info as $deposits)
            <tr>
                <td>{{ $count ++}}</td>
                <td>@if($deposits->family_id == 0) Guest @else {{ $deposits->family_id }} @endif</td>
                <td>{{ $deposits->payment_status }}</td>
                <td>{{ $deposits->transaction_id }}</td>
                <td>{{ $deposits->client_email }}</td>
                <td>@if(empty($deposits->client_name))N/A @else {{ $deposits->client_name }} @endif</td>
                <td>{{ $deposits->amount }}</td>
                <td>{{ date('d-M-Y',strtotime($deposits->created_at)) }}</td>

            </tr>
            @endforeach
             @foreach($purchase_inmate as $funds)
            <tr>
                <td>{{ $count ++}}</td>
                <td>None</td>
                <td>YES</td>
                <td>{{ $funds->customerTransactionId }}</td>
                <td>None</td>
                <td>None</td>
                <td>{{ $funds->amount }}</td>
                <td>{{ date('d-M-Y',strtotime($funds->created_at)) }}</td>

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

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	
	
	

	<table class="table table-condensed">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Download Link</th>
      <th scope="col">Type</th>
      <th scope="col">Approve / Disapprove</th>
    </tr>
  </thead>
  <tbody>
    @if(count($attach) > 0)
  	@foreach($attach as $key=>$attachments)
    <tr>
      <th scope="row">{{$key+1}}</th>
      <td><a target="_blank" href="{{$attachments->link}}">Attachment {{$key+1}}</a></td>
      <td>{{$attachments->type}}</td>
      <td>
        @if($attachments->status == 0)
        <a href="javascript:void(0)"  title="Approve"><i class="fa fa-check" style="color:green" onclick="approve_attach({{$attachments->id}},1,{{$attachments->email_id}})" aria-hidden="true"></i></a> | <a href="javascript:void(0)" title="Disapprove"><i class="fa fa-times" style="color:red" onclick="approve_attach({{$attachments->id}},2,{{$attachments->email_id}})" aria-hidden="true"></i></a>
        @elseif($attachments->status == 1)
        <a href="javascript:void(0)" title="Disapprove"><i class="fa fa-times" style="color:red" onclick="approve_attach({{$attachments->id}},2,{{$attachments->email_id}})" aria-hidden="true"></i></a>
        @elseif($attachments->status == 2)
        <a href="javascript:void(0)"  title="Approve"><i class="fa fa-check" style="color:green" onclick="approve_attach({{$attachments->id}},1,{{$attachments->email_id}})" aria-hidden="true"></i></a>

        @endif

      </td>
      
    </tr>
    @endforeach
    @else
    <tr><td>No Attachment Found</td></tr>
    @endif
  </tbody>
</table>
</body>
</html>
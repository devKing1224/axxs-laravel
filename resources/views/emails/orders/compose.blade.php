
<?php header('Access-Control-Allow-Origin: *');
$link = "https://theaxxstablet.com/index.php/api/emailblock/".urlencode(base64_encode($content['email']));
 ?>
<!DOCTYPE html>
<head>
    @include('includes.head')
</head>
<body>

    {!! $content['body'] !!}

    <br>
    <p>Sincerely,</p>
    {{ $content['name'] }},<br>
    https://theaxxstablet.com

    <br><br>
    <small style="font-size:10px">The information contained in this e-mail transmission is privileged, confidential and covered by the Electronic Communications Privacy Act, 18 U.S.C. Sections 2510-2521. If you are not the intended recipient, do not read, distribute, or reproduce this transmission. If you have received this e-mail in error or do not wish to receive our emails, please click <a href="{{ $link }}">remove
    </a> and you will be removed from our database.
    </small>
  
</html>
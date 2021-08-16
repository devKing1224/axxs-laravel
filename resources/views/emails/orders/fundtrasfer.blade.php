<?php header('Access-Control-Allow-Origin: *');
$link = "https://theaxxstablet.com/index.php/api/emailblock/".urlencode(base64_encode($content['email']));
 ?>
<!DOCTYPE html>
<head>
    @include('includes.head')
</head>
<body>
    <p>Dear {{ $content['client_name'] }}, </p>
    <p>Welcome! Thank you for supporting "{{ ucwords($content['username']) }}" through AxxS Tablet. </p>
    <p><i>Your support will be used for the following.</i></p>
    <ul>
        <li>Tablet rental time</li>
        <li>Free Health and Wellness support</li>
        <li>Free Education and Training</li>
        <li>Free Entertainment (podcast, movies, games, news, etc.)</li>
        <li>Email</li>
        <li>Text Messaging (SMS)</li>
        <li>Texting</li>
        <li>Access to prep CED, Court Ordered and College readiness classes</li>
    </ul>

    <p><b>To Send Additional Funds</b><br>
        <a class="text-info" href="https://theaxxstablet.com"><u>theaxxstablet.com</u></a></p>
    <br>
    <p><b><i>Still have questions?</i></b><br>
    If you have any questions about funding, please contact us at <a>funds@axxstablet.com</a>
    If you have any questions about AxxS Tablet, please contact us at <a>info@axxstablet.com</a>

    </p>
     Sincerely,<br>
    AxxS Tablet App Team,<br>
    <a href="https://theaxxstablet.com"><u>theaxxstablet.com</u></a>

    <br><br>
    <small>The information contained in this e-mail transmission is privileged, confidential and covered by the Electronic Communications Privacy Act, 18 U.S.C. Sections 2510-2521. If you are not the intended recipient, do not read, distribute, or reproduce this transmission. If you have received this e-mail in error or do not wish to receive our emails, please click <a href="{{ $link }}">remove
    </a> and you will be removed from our database.
    </small>
    
</body>
</html>


 


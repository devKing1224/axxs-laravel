<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<head>
    @include('includes.head')
</head>
<body>
    <p>Dear {{ $content['staffname'] }} </p>
    <p>Congratulations! You have been assigned a role of Facility Staff Member in TheAxxsTablet</p>
    <p><i>Your login credentials are given below and are confidential so do not share with any one.</i></p>
    <ul>
        <li>Username: {{$content['username']}}</li>
        <li>Password: {{$content['password']}}</li>
        
    </ul>

    <p><b>To login into our backroom application click here.</b><br>
        <a class="text-info" href="https://theaxxstablet.com"><u>theaxxstablet.com</u></a></p>
    <br>
    <p><b><i>Still have questions?</i></b><br>
    If you have any questions, please contact us at <a>info@axxstablet.com</a>
    </p>
    Sincerely,<br>
    AxxS Tablet App Team,<br>
    <a href="https://theaxxstablet.com"><u>theaxxstablet.com</u></a>

    <br><br>
    <small>You are receiving this email to confirm the action noted above. Your email address will not be saved or used for any other purpose. We value your privacy and NEVER give or sell any specific information about you to any manufacturer or direct marketers. 
        Privacy and the security of personal information are very important to us. If you have questions or concerns about how we protect your privacy and the privacy of others, read our site Privacy Policy.
    </small>
    
</body>
</html>

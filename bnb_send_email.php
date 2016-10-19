<?php

$to = $_POST['email'];

$subject = 'Message from lifeat5280.com with driving directions';

$message = '<html><body>';
$message .= '<br><strong>Static Google Map:</strong><br>';
$message .= '<img src="https://maps.googleapis.com/maps/api/staticmap?'.$_POST['map_params'].'" alt="Google Map" />';  
$message .= '<br><br><strong>Driving Directions: </strong><br>'.$_POST['directions'];
$message .= "</body></html>";

$headers = "From: LifeAt5280.com <noreply@lifeat5280.com>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers)) echo 'OK';
<?php
include('src/config.php');
include('src/mail.class.php');
$data = Mail::viewEmailBody($_GET['id']);
extract($data);
echo $msg;
?>
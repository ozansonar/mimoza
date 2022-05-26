<?php

use Mrt\MimozaCore\Mail;

$message = [];
if (isset($_POST["mailing_id"])) {
	$id = $functions->cleanPostInt("mailing_id");

	include_once $system->path("includes/System/Mail.php");
	$mail_class = new Mail($db);
	$send = $mail_class->sendMailing($id);
	$message = $send;
} else {
	$message["reply"][] = "Birşeyler yanış gitti tekrar deneyin.";
}

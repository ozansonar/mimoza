<?php

use Mrt\MimozaCore\Mail;

$log->logThis($log->logTypes["CONTACT_API_SEND"]);
if(isset($_POST)){
	$log->logThis(8);
	header('Content-Type: text/html; charset=utf-8');
	header('Content-Type: text/json; charset=utf-8');

	$name = $functions->cleanPost("name");
	$surname = $functions->cleanPost("surname");
	$email = $functions->cleanPost("email");
	$subject = $functions->cleanPost("subject");
	$msg = $functions->cleanPost("message");
	$message = [];

	if(!$name){
		$message["reply"][] = $functions->textManager("contact_validate_ad");
	}

	if(!empty($name)){
		if(strlen($name) < 2){
			$message["reply"][] = $functions->textManager("contact_validate_min_2_ad");
		}
		if(strlen($name) > 60){
			$message["reply"][] = $functions->textManager("contact_validate_max_60_ad");
		}
	}
	if(!$surname){
		$message["reply"][] = $functions->textManager("contact_validate_soyad");
	}
	if(!empty($surname)){
		if(strlen($surname) < 2){
			$message["reply"][] = $functions->textManager("contact_validate_min_2_soyad");
		}
		if(strlen($surname) > 60){
			$message["reply"][] = $functions->textManager("contact_validate_max_60_soyad");
		}
	}

	if(!$email){
		$message["reply"][] = $functions->textManager("contact_validate_email");
	}

	if(!empty($email) && !$functions->isEmail($email)) {
		$message["reply"][] = $functions->textManager("contact_validate_gecersiz_email");
	}

	if(!$msg){
		$message["reply"][] = $functions->textManager("contact_validate_mesaj");
	}

	if(!empty($msg)){
		if(strlen($msg) < 10){
			$message["reply"][] = $functions->textManager("contact_validate_min_10_mesaj");
		}
		if(strlen($msg) > 2000){
			$message["reply"][] = $functions->textManager("contact_validate_max_2000_mesaj");
		}
	}

	//hata yoksa doğrulama yap
	if (empty($message) && defined("LIVE_MODE")) {
		$recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
		$recaptcha_secret = CAPTCHA_SECRET_KEY;
		$recaptcha_response = $_POST['g-recaptcha-response'];

		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
		$recaptcha = json_decode($recaptcha);

		if ($recaptcha->success  === false) {
			$message["reply"][] = $functions->textManager("contact_validate_bot_onay");
		}
	}


	if(empty($message)){
		$mail_template = $siteManager->getMailTemplate(1);
		if(empty($mail_template)){
			exit("mail teması hatasi");
		}
		$s_msg = "";
		$s_msg .="<p><b>Ad Soyad:</b> ".$name." ".$surname."</p>";
		$s_msg .="<p><b>E-posta:</b> ".$email."</p>";
		if(!empty($subject)){
			$s_msg .="<p><b>Konu:</b> ".$subject."</p>";
		}
		$s_msg .="<p><b>Mesaj:</b> ".$msg."</p>";

		$mail_temp = $mail_template->text;
		$mail_temp = str_replace(["#project_name#", "#message#"], [$settings->project_name, $s_msg],$mail_temp);

		$db_data = [];
		$db_data["name"] = $name;
		$db_data["surname"] = $surname;
		$db_data["subject"] = $subject;
		$db_data["email"] = $email;
		$db_data["message"] = $msg;
		$db::insert("contact_form",$db_data);

		$mail = new Mail($db);
		$mail->message = $mail_temp;
		$mail->address = $settings->site_mail;
		$mail->sender_name = $name." ".$surname;
		$mail->subject = $subject;

		$send = $mail->send();
		if($send){
			$message["success"][] = $functions->textManager("contact_form_success");
			$log->logThis($log->logTypes["CONTACT_API_SEND_SUCCESS"]);
		}else{
			$message["reply"][] = $functions->textManager("contact_form_error");
			$log->logThis($log->logTypes["CONTACT_API_SEND_ERR"]);
		}
	}
}
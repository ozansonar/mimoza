<?php

use OS\MimozaCore\Mail;

$log->this('COMMENT_REQUREST','post data:'.json_encode($_POST));

if(isset($_POST)){
	header('Content-Type: text/html; charset=utf-8');
	header('Content-Type: text/json; charset=utf-8');

    $formData = [];

    $formData['id'] = $functions->cleanPost("id");
    $formData['type'] = $functions->cleanPost("type");
    $formData['name'] = $functions->cleanPost("name");
    $formData['surname'] = $functions->cleanPost("surname");
    $formData['email'] = $functions->cleanPost("email");
    $formData['comment'] = $functions->cleanPost("comment");
	$message = [];

    if(!array_key_exists($formData['type'],$constants::commentType)){
        $message['reply'][] = $functions->textManager('comment_gecersiz_yorum_tipi');
    }
	if(empty($formData['id'])){
		$message["reply"][] = $functions->textManager('comment_gecersiz_icerik_anahtari');
	}
    if(!empty($formData['id'])){
        if(!is_numeric($formData['id'])){
            $message['reply'][] = $functions->textManager('comment_gecersiz_icerik_anahtari');
        }
        //ilgili tabloda bu bu idli içeriğe yorum yapılabilir mi kontrol et
        $query = $db::query('SELECT id,title FROM '.$constants::commentType[$formData['type']]['table'].' WHERE id=:id AND comment=1 AND status=1 AND deleted=0');
        $postId = $formData['id'];
        $query->bindParam(':id',$postId,PDO::PARAM_INT);
        $query->execute();
        if($query->rowCount() === 0){
            $message['reply'][] = $functions->textManager('comment_icerige_yorum_yapilamaz');
        }
    }
    if(empty($formData['name'])){
        $message['reply'][] = $functions->textManager('comment_ad_bos');
    }
    if(!empty($formData['name'])){
        if(strlen($formData['name']) < 2){
            $message['reply'][] = $functions->textManager('comment_ad_min');
        }
        if(strlen($formData['name']) > 30){
            $message['reply'][] = $functions->textManager('comment_ad_max');
        }
    }
    if(empty($formData['surname'])){
        $message['reply'][] = $functions->textManager('comment_soyad_bos');
    }
    if(!empty($formData['surname'])){
        if(strlen($formData['surname']) < 2){
            $message['reply'][] = $functions->textManager('comment_soyad_min');
        }
        if(strlen($formData['surname']) > 30){
            $message['reply'][] = $functions->textManager('comment_soyad_max');
        }
    }
    if(empty($formData['email'])){
        $message['reply'][] = $functions->textManager('comment_email_bos');
    }
    if(!empty($formData['email'])){
        if($functions->isEmail($formData['email']) === false){
            $message['reply'][] = $functions->textManager('comment_email_gecersiz');
        }
        if(strlen($formData['email']) > 100){
            $message['reply'][] = $functions->textManager('comment_email_max');
        }

    }
    if(empty($formData['comment'])){
        $message['reply'][] = $functions->textManager('comment_yorum_bos');
    }
    if(!empty($formData['comment'])){
        if(strlen($formData['comment']) < 10){
            $message['reply'][] = $functions->textManager('comment_yorum_min');
        }
        if(strlen($formData['comment']) > 2000){
            $message['reply'][] = $functions->textManager('comment_yorum_max');
        }
    }

    //hata yoksa doğrulama yap
    if (empty($message) && defined("LIVE_MODE")) {
        $captchaVerify = $functions->googleRecapcha();
        if($captchaVerify["result"] === false){
            $message["reply"][] = $captchaVerify["msg"];
        }
    }


	if(empty($message)){
		$dbData = [];
		$dbData["post_id"] = $formData['id'];
		$dbData["type"] = $formData['type'];
		$dbData["name"] = $formData['name'];
		$dbData["surname"] = $formData['surname'];
		$dbData["email"] = $formData['email'];
		$dbData["comment"] = $formData['comment'];
		$dbData["ip"] = $functions->getIpAddress();
		$add = $db::insert("comment",$dbData);
        if($add > 0){
            $message["success"][] = $functions->textManager("comment_yorum_yapildi");
            $log->this('COMMENT_ADD_SUCC');

            //mail işlemleri
            $getMailSendAdminList = $siteManager->getMailSendAdminList();
            $mailTemplate = $siteManager->getMailTemplate(22);
            if(!empty($getMailSendAdminList) && !empty($mailTemplate)){
                $getData = $query->fetch(PDO::FETCH_OBJ);
                $mailTemplateText = $mailTemplate->text;
                $mailTemplateText = str_replace("#icerik_adi#",$getData->title,$mailTemplateText);
                $mailTemplateText = str_replace("#ad#",$formData['name'],$mailTemplateText);
                $mailTemplateText = str_replace("#soyad#",$formData['surname'],$mailTemplateText);
                $mailTemplateText = str_replace("#eposta#",$formData['email'],$mailTemplateText);
                $mailTemplateText = str_replace("#yorum#",$formData['comment'],$mailTemplateText);
                foreach ($getMailSendAdminList as $adminRow){
                    $mail = new Mail($db);
                    $mail->message = $mailTemplateText;
                    $mail->address = $adminRow->email;
                    $mail->subject = $mailTemplate->subject;
                    $mail->send();
                }
            }
        }else{
            $message["reply"][] = $functions->textManager("comment_yorum_yapilamadi");
            $log->this('COMMENT_ADD_ERR');
        }
	}
}
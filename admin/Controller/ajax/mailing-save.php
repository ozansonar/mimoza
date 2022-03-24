<?php
/**
 * Author: Ozan SONAR
 * Mail : ozansonar1@gmail.com
 * User: Ozan
 * Date: 17.10.2021
 * Time: 01:00
 */
if(isset($_POST["subject"]) && !empty($_POST["subject"])){
    $page_data = array();
    $page_data["subject"] = $functions->clean_post("subject");
    $page_data["text"] = $functions->clean_post_textarea("text");
    $page_data["attachment"] = $functions->post("attecament");
    $page_data["user"] = $functions->post("user");
    $page_data["send"] = $functions->post("send");
    $page_data["test_user"] = $functions->post("user_test");
    $page_data["image"] = $functions->clean_post("image");
    if(empty($page_data["subject"])){
        $message["reply"][] = "Lütfen mailin konusunu yazınız.";
    }

    if(empty($page_data["text"])){
        $message["reply"][] = "Lütfen mailin içeriğini yazınız.";
    }

    if(!empty($page_data["user"]) && !empty($page_data["send"])){
        if(in_array(999,$page_data["user"])){
            $page_data["clean_users"][999] = 999;
        }
        foreach ($page_data["user"] as $user_key){
            if(array_key_exists($user_key,$systemAdminUserType)){
                $page_data["clean_users"][$user_key] = $user_key;
            }
        }
    }


    if (empty($page_data["send"])) {
        $test_user_array = array();
        //alttaki checkbox işaretlenmedi yazılan kişiye test maili atılıyor
        foreach ($page_data["test_user"] as $user_key => $user_value) {
            $email = $functions->cleaner($user_value["email"]);
            $name = $functions->cleaner($user_value["name"]);
            $surname = $functions->cleaner($user_value["surname"]);

            if (empty($email)) {
                $message["reply"][] = "Lütfen test maili gönderilecek adresi yazınız.";
            }
            if (!empty($email)) {
                if (!$functions->is_email($email)) {
                    $message["reply"][] = "Lütfen geçerli bir mail adresi yazınız.";
                }
            }
            if (empty($name)) {
                $message["reply"][] = "Lütfen mail gönderilecek kişinin ismi yazınız.";
            }
            if (empty($surname)) {
                $message["reply"][] = "Lütfen mail gönderilecek kişinin soyadını yazınız.";
            }

            $test_user_array[$user_key]["email"] = $email;
            $test_user_array[$user_key]["name"] = $name;
            $test_user_array[$user_key]["surname"] = $surname;
        }
    }
    if(empty($page_data["send"]) && empty($test_user_array)){
        $message["reply"][] = "Lütfen mail göndermek istediğiniz kullanıcıları yazınız.";
    }elseif(!empty($page_data["send"]) && empty($page_data["user"])){
        $message["reply"][] = "Lütfen mail göndermek kullanıcı gurubunu seçiniz.";
    }
    $replaced_image = array();
    //gizli olarak gelen $image değeri boş değilse mesajda bu değerleri arayıp replace edeceğiz
    if (!empty($page_data["image"])) {
        $uploaded_image = explode(",", $page_data["image"]);
        foreach ($uploaded_image as $uploaded_image_row) {
            if (!empty($uploaded_image_row)) {
                if (strstr($page_data["text"], $uploaded_image_row)) {
                    $uniq = time() . uniqid();
                    $page_data["text"] = str_replace( $fileTypePath["mailing"]["url"]. $uploaded_image_row, "cid:image_" . $uniq, $page_data["text"]);
                    $replaced_image[$uniq] = $uploaded_image_row;
                }
            }
        }
    }
    //mailing ekler
    $attacament_array = array();
    if (!empty($page_data["attachment"])) {
        foreach ($page_data["attachment"] as $attecament_row) {
            $at_name = $functions->cleaner($attecament_row);
            if (file_exists($fileTypePath["mailing_attachment"]["full_path"].$at_name)) {
                $attacament_array[] = $at_name;
            }
        }
    }
    if(empty($message)){
        $add_data = array();
        $add_data["user_id"] = $_SESSION["user_id"];
        $add_data["subject"] = $page_data["subject"];
        $add_data["text"] = $page_data["text"];
        $add_data["image"] = !empty($replaced_image) ? serialize($replaced_image):null;
        $add_data["attachment"] = !empty($page_data["attachment"]) ? serialize($page_data["attachment"]):null;
        $add_data["group"] = !empty($page_data["send"]) ? implode(",",$page_data["user"]):null;
        $add_data["status"] = 1;
        $insert = $db::insert("mailing",$add_data);
        if($insert){
            $mailing_id = $db::getLastInsertedId();
            if(empty($page_data["send"])){
                foreach ($test_user_array as $test_user){
                    $mailing_user = array();
                    $mailing_user["mailing_id"] = $mailing_id;
                    $mailing_user["name"] = $test_user["name"];
                    $mailing_user["surname"] = $test_user["surname"];
                    $mailing_user["email"] = $test_user["email"];
                    $mailing_user["status"] = 1;
                    $db::insert("mailing_user",$mailing_user);
                }
            }else{
                if(array_key_exists(999,$page_data["clean_users"])){
                    $where = "";
                }else{
                    $where_text = implode(",",$page_data["clean_users"]);
                    $where = " AND `rank` IN (".$where_text.") ";
                }
                $user_select = $db::query("SELECT * FROM users WHERE status=1 AND deleted=0 AND send_mail=1 ".$where." ");
                $user_select->execute();
                $user_data = $user_select->fetchAll(PDO::FETCH_OBJ);
                if(!empty($user_data)){
                    foreach ($user_data as $user_row){
                        $mailing_user = array();
                        $mailing_user["mailing_id"] = $mailing_id;
                        $mailing_user["name"] = $user_row->name;
                        $mailing_user["surname"] = $user_row->surname;
                        $mailing_user["email"] = $user_row->email;
                        $mailing_user["status"] = 1;
                        $db::insert("mailing_user",$mailing_user);
                    }
                }
            }

            $message["success"][] = "Mailing başarıyla eklendi. Yönlendiriliyorsunuz...";
            $message["url"] = $adminSystem->adminUrl("mailing-view?id=".$mailing_id);
            $message["time"] = 3000;
        }else{
            $message["reply"][] = "Mailing eklenemedi lütfen tekrar deneyin.";
        }
    }
}else{
    $message["reply"][] = "Geçersiz istek.";
}
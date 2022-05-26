<?php
if (isset($_POST["subject"]) && !empty($_POST["subject"])) {
	$pageData = [];
	$pageData["subject"] = $functions->cleanPost("subject");
	$pageData["text"] = $functions->cleanPostTextarea("text");
	$pageData["attachment"] = $functions->post("attecament");
	$pageData["user"] = $functions->post("user");
	$pageData["send"] = $functions->post("send");
	$pageData["test_user"] = $functions->post("user_test");
	$pageData["image"] = $functions->cleanPost("image");
	if (empty($pageData["subject"])) {
		$message["reply"][] = "Lütfen mailin konusunu yazınız.";
	}

	if (empty($pageData["text"])) {
		$message["reply"][] = "Lütfen mailin içeriğini yazınız.";
	}

	if (!empty($pageData["user"]) && !empty($pageData["send"])) {
		if (in_array(999, $pageData["user"], true)) {
			$pageData["clean_users"][999] = 999;
		}
		foreach ($pageData["user"] as $user_key) {
			if (array_key_exists($user_key, $constants::systemAdminUserType)) {
				$pageData["clean_users"][$user_key] = $user_key;
			}
		}
	}


	if (empty($pageData["send"])) {
		$test_user_array = [];
		//alttaki checkbox işaretlenmedi yazılan kişiye test maili atılıyor
		foreach ($pageData["test_user"] as $user_key => $user_value) {
			$email = $functions->cleaner($user_value["email"]);
			$name = $functions->cleaner($user_value["name"]);
			$surname = $functions->cleaner($user_value["surname"]);

			if (empty($email)) {
				$message["reply"][] = "Lütfen test maili gönderilecek adresi yazınız.";
			}
			if (!empty($email) && !$functions->isEmail($email)) {
				$message["reply"][] = "Lütfen geçerli bir mail adresi yazınız.";
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
	if (empty($pageData["send"]) && empty($test_user_array)) {
		$message["reply"][] = "Lütfen mail göndermek istediğiniz kullanıcıları yazınız.";
	} elseif (!empty($pageData["send"]) && empty($pageData["user"])) {
		$message["reply"][] = "Lütfen mail göndermek kullanıcı gurubunu seçiniz.";
	}
	$replaced_image = [];
	//gizli olarak gelen $image değeri boş değilse mesajda bu değerleri arayıp replace edeceğiz
	if (!empty($pageData["image"])) {
		$uploaded_image = explode(",", $pageData["image"]);
		foreach ($uploaded_image as $uploaded_image_row) {
			if (!empty($uploaded_image_row) && strpos($pageData["text"], $uploaded_image_row) !== false) {
				$uniq = time() . uniqid();
				$pageData["text"] = str_replace($constants::fileTypePath["mailing"]["url"] . $uploaded_image_row, "cid:image_" . $uniq, $pageData["text"]);
				$replaced_image[$uniq] = $uploaded_image_row;
			}
		}
	}
	//mailing ekler
	$attacament_array = [];
	if (!empty($pageData["attachment"])) {
		foreach ($pageData["attachment"] as $attecament_row) {
			$at_name = $functions->cleaner($attecament_row);
			if (file_exists($constants::fileTypePath["mailing_attachment"]["full_path"] . $at_name)) {
				$attacament_array[] = $at_name;
			}
		}
	}
	if (empty($message)) {
		$add_data = [];
		$add_data["user_id"] = $_SESSION["user_id"];
		$add_data["subject"] = $pageData["subject"];
		$add_data["text"] = $pageData["text"];
		$add_data["image"] = !empty($replaced_image) ? serialize($replaced_image) : null;
		$add_data["attachment"] = !empty($pageData["attachment"]) ? serialize($pageData["attachment"]) : null;
		$add_data["group"] = !empty($pageData["send"]) ? implode(",", $pageData["user"]) : null;
		$add_data["status"] = 1;
		$insert = $db::insert("mailing", $add_data);
		if ($insert) {
			$mailing_id = $db::getLastInsertedId();
			if (empty($pageData["send"])) {
				foreach ($test_user_array as $test_user) {
					$mailing_user = [];
					$mailing_user["mailing_id"] = $mailing_id;
					$mailing_user["name"] = $test_user["name"];
					$mailing_user["surname"] = $test_user["surname"];
					$mailing_user["email"] = $test_user["email"];
					$mailing_user["status"] = 1;
					$db::insert("mailing_user", $mailing_user);
				}
			} else {
				if (array_key_exists(999, $pageData["clean_users"])) {
					$where = "";
				} else {
					$where_text = implode(",", $pageData["clean_users"]);
					$where = " AND `rank` IN (" . $where_text . ") ";
				}
				$user_select = $db::query("SELECT * FROM users WHERE status=1 AND deleted=0 AND send_mail=1 " . $where . " ");
				$user_select->execute();
				$user_data = $user_select->fetchAll(PDO::FETCH_OBJ);
				if (!empty($user_data)) {
					foreach ($user_data as $user_row) {
						$mailing_user = [];
						$mailing_user["mailing_id"] = $mailing_id;
						$mailing_user["name"] = $user_row->name;
						$mailing_user["surname"] = $user_row->surname;
						$mailing_user["email"] = $user_row->email;
						$mailing_user["status"] = 1;
						$db::insert("mailing_user", $mailing_user);
					}
				}
			}

			$message["success"][] = "Mailing başarıyla eklendi. Yönlendiriliyorsunuz...";
			$message["url"] = $system->adminUrl("mailing-view?id=" . $mailing_id);
			$message["time"] = 3000;
		} else {
			$message["reply"][] = "Mailing eklenemedi lütfen tekrar deneyin.";
		}
	}
} else {
	$message["reply"][] = "Geçersiz istek.";
}
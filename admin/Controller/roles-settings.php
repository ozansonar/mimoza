<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "roles";
$pageAddRoleKey = "roles-settings";


$id = 0;

if (isset($_GET["id"])) {
	//update yetki kontrolü ve gösterme yetkisi de olması lazım
	if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === false
		|| $session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
		$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
		$session->permissionDenied();
	}

	$log->logThis($log->logTypes['ROLES_DETAIL']);
	$id = $functions->cleanGetInt("id");

	$data = $db::selectQuery("role_groups", array(
		"id" => $id,
		"deleted" => 0,
	), true);

	if (empty($data)) {
		$functions->redirect($system->adminUrl());
	}

	$role_select = $db::query("SELECT * FROM role_permission WHERE deleted=0 AND role_group=:rg");
	$role_select->bindParam(":rg", $data->id, PDO::PARAM_INT);
	$role_select->execute();
	$role_data = $role_select->fetchAll(PDO::FETCH_OBJ);
	$role_array = [];
	foreach ($role_data as $roles) {
		$role_array[$roles->role_key][] = $roles->permission;
	}

} else if (!$session->sessionRoleControl($pageAddRoleKey, $constants::addPermissionKey)) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
	$session->permissionDenied();
}

$customCss = [
	"plugins/form-validation-engine/css/validationEngine.jquery.css",
	"plugins/icheck-bootstrap/icheck-bootstrap.min.css",
];
$customJs = [
	"plugins/form-validation-engine/js/jquery.validationEngine.js",
	"plugins/form-validation-engine/js/languages/jquery.validationEngine-tr.js",
];

$extra = [
	[
		'url' => 'user-tracing',
		'title' => 'Kullanıcı Hareketleri İzleme',
		'icon' => 'icon-book',
		'permissions' => [
			's' => 'Görüntüleme',
		]
	],
	[
		//galeride ayrı yetkiye gerek yok 1 tane yeterli
		'url' => 'gallery-image-upload',
		'title' => 'Galeri Resim Ekleme',
		'icon' => 'icon-book',
		'permissions' => [
			'a' => 'Ful Yetki',
		]
	],
	[
		//galeride ayrı yetkiye gerek yok 1 tane yeterli
		'url' => 'video-upload',
		'title' => 'Video Yükleme',
		'icon' => 'icon-book',
		'permissions' => [
			'a' => 'Ful Yetki',
		]
	],
];

if (isset($_POST["submit"]) && (int)$_POST["submit"] === 1) {

	$message = [];
	$role_group_name = $functions->cleanPost("group_name");
	$permissions = isset($_POST["permissions"]) ? $functions->post("permissions") : null;

	if (empty($role_group_name)) {
		$message["reply"][] = "Grup İsmi boş olamaz";
	}

	if (empty($message)) {
		$add_data = [];
		$add_data["group_name"] = $role_group_name;
		$add_data["status"] = 1;
		if ($id > 0) {
			$update = $db::update("role_groups", $add_data, array("id" => $id));
			if ($update) {
				if (isset($_POST["permissions"])) {
					foreach ($permissions as $p_key => $p_val) {
						foreach ($p_val as $key => $val) {
							//kontrollü bir şekilde ekle olmayanlari
							if (!isset($role_array[$p_key]) || !in_array($val, $role_array[$p_key], true)) {
								$role_add = [];
								$role_add["role_group"] = $id;
								$role_add["role_key"] = $functions->cleaner($p_key);
								$role_add["permission"] = $functions->cleaner($val);
								$db::insert("role_permission", $role_add);
							}
						}
					}
					//mevcut rolleri çekip gelenler ile karşılaştıracağız
					$m_role = $db::query("SELECT * FROM role_permission WHERE role_group=:rg AND deleted=0");
					$m_role->bindParam(":rg", $id);
					$m_role->execute();
					$m_role_data = $m_role->fetchAll(PDO::FETCH_OBJ);
					$m_role_count = $m_role->rowCount();
					if ($m_role_count > 0) {
						$mevcut_izinler = [];
						foreach ($m_role_data as $m_role) {
							if (isset($permissions[$m_role->role_key])) {
								if (!in_array($m_role->permission, $permissions[$m_role->role_key], true)) {
									$role_query_2 = $db::query("UPDATE role_permission SET deleted=1 WHERE role_key=:rk AND permission=:per AND role_group=:rg");
									$role_query_2->bindParam(":rk", $m_role->role_key);
									$role_query_2->bindParam(":per", $m_role->permission);
									$role_query_2->bindParam(":rg", $id);
									$role_query_2->execute();
								}
							} else {
								$role_query_2 = $db::query("UPDATE role_permission SET deleted=1 WHERE role_key=:rk AND role_group=:rg");
								$role_query_2->bindParam(":rk", $m_role->role_key);
								$role_query_2->bindParam(":rg", $id);
								$role_query_2->execute();
							}
						}
					}
				} else {
					//her hangi bir yetki seçilmemişsee hepsini sil
					$delete_role = $db::query("UPDATE role_permission SET deleted=1 WHERE role_group=:rg");
					$delete_role->bindParam(":rg", $id);
					$delete_role->execute();
				}

				$message["success"][] = $lang["content-completed"];
				$functions->refresh($system->adminUrl("roles-settings?id=" . $id, 3));
				$log->logThis($log->logTypes['ROLES_EDIT_SUCC']);
			} else {
				$log->logThis($log->logTypes['ROLES_EDIT_ERR']);
				$message["reply"][] = $lang["content-completed-error"];
			}
		} else {
			//ekleme
			$insert = $db::insert("role_groups", $add_data);
			$last_id = $db::getLastInsertedId();
			if ($insert) {
				if (isset($_POST["permissions"])) {
					foreach ($permissions as $p_key => $p_val) {
						foreach ($p_val as $key => $val) {
							$role_add = [];
							$role_add["role_group"] = $last_id;
							$role_add["role_key"] = $functions->cleaner($p_key);
							$role_add["permission"] = $functions->cleaner($val);
							$db::insert("role_permission", $role_add);
						}
					}
				}

				$message["success"][] = $lang["content-completed"];
				$functions->refresh($system->adminUrl("roles-settings?id=" . $last_id, 3));
				$log->logThis($log->logTypes['ROLES_ADD_SUCC']);
			} else {
				$log->logThis($log->logTypes['ROLES_ADD_ERR']);
				$message["reply"][] = $lang["content-completed-error"];
			}
		}
	}
}


View::backend('roles-settings', [
	'title' => "Yetki " . (isset($data) ? "Düzenle" : "Ekle"),
	'pageButtonRedirectLink' => "roles",
	'pageButtonRedirectText' => "Kullanıcı Yetkileri",
	'pageButtonIcon' => null,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageRoleKey' => $pageRoleKey,
	'onyuz_yetkiler' => $onyuz_yetkiler ?? NULL,
	'extra' => $extra ?? NULL,
	'role_array' => $role_array ?? NULL,
	'pageData' => $data ?? NULL,
	'css' =>$customCss,
	'js' =>$customJs,
]);
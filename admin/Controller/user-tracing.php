<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "user-tracing";
if ($session->sessionRoleControl($pageRoleKey, $constants::listPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::editPermissionKey);
	$session->permissionDenied();
}

$log->logThis($log->logTypes['USER_TRACING']);

$id = 0;
if (isset($_GET["id"])) {
	$id = $functions->cleanGetInt("id");
	$selectQuery = $db::selectQuery("users", array(
		"id" => $id,
		"deleted" => 0,
	), true);
	if (empty($selectQuery)) {
		$functions->redirect($system->adminUrl());
	}
	$logs_get = $db::query("SELECT l.*,lt.log_key FROM logs l INNER JOIN log_types lt ON lt.log_val=l.log_type WHERE user_id=:uid ORDER BY l.id DESC");
	$logs_get->bindParam(":uid", $id, PDO::PARAM_INT);
	$logs_get->execute();
	$logs_count = $logs_get->rowCount();
	$log_array = [];
	if ($logs_count > 0) {
		$logs_data = $logs_get->fetchAll(PDO::FETCH_OBJ);
		foreach ($logs_data as $logs_row) {
			$date = date('Y-m-d', strtotime($logs_row->log_datetime));
			$log_array[$date][] = $logs_row;
		}
	}
}
if ($id === 0) {
	$functions->redirect('user');
}

View::backend('user-tracing', [
	'title' => "Kullanıcı Hareketleri",
	'subTitle' => $selectQuery->name . " " . $selectQuery->surname . " isimli kullanıcının sistemimizdeki hareketleri.",
	'id' => $id,
	'pageButtonRedirectLink' => "user",
	'pageButtonRedirectText' => "Kullancılar",
	'pageButtonIcon' => "fas fa-th-list",
	'pageRoleKey' => $pageRoleKey,
	'logs' => $log_array,

]);
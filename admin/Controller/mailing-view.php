<?php

use Mrt\MimozaCore\View;

$pageRoleKey = "mailler";
$pageAddRoleKey = "mail-settings";

if ($session->sessionRoleControl($pageRoleKey, $constants::detailPermissionKey) === false) {
	$log->logThis($log->logTypes["IZINSIZ_ERISIM_ISTEGI"], "izinsiz erişim isteği user id->" . $_SESSION["user_id"] . " role key => " . $pageRoleKey . " permissions => " . $constants::listPermissionKey);
	$session->permissionDenied();
}

$log->logThis($log->logTypes['MAILLER_DETAIL']);
$id = $functions->cleanGetInt("id");
$table = "mailing";
$table_2 = "mailing_user";

$mailing = $db::selectQuery($table, array(
	"id" => $id,
	"deleted" => 0,
), true);

if (empty($mailing)) {
	$functions->redirect($system->adminUrl("mailler"));
}

if (!empty($mailing->image)) {
	$image = unserialize($mailing->image);
	foreach ($image as $m_key => $m_value) {
		$mailing->text = str_replace("cid:image_" . $m_key, $constants::fileTypePath["mailing"]["url"] . $m_value, $mailing->text);
	}
}

if (!empty($mailing->attachment)) {
	$attachment_array = [];
	$attachment = unserialize($mailing->attachment);
	foreach ($attachment as $attachment_key => $attachment_row) {
		$at_name = $functions->cleaner($attachment_row);
		if (file_exists($constants::fileTypePath["mailing_attachment"]["full_path"] . $at_name)) {
			$attachment_array[$attachment_key]["url"] = $constants::fileTypePath["mailing_attachment"]["url"] . $at_name;
			$attachment_array[$attachment_key]["name"] = $at_name;
		}
	}
}

$mailing_user = $db::selectQuery($table_2, array(
	"mailing_id" => $mailing->id,
	"deleted" => 0,
));

$mailing_user_list = [];
foreach ($mailing_user as $user_row) {
	$mailing_user_list[$user_row->send][$user_row->id] = $user_row;
}

View::backend('mailing-view', [
	'title' => 'Mail Detayları',
	'pageRoleKey' => $pageRoleKey,
	'pageAddRoleKey' => $pageAddRoleKey,
	'pageButtonRedirectLink' => $pageRoleKey,
	'pageButtonRedirectText' => "Mail Listesi",
	'pageButtonIcon' => "fas fa-plus-square",
	'mailingUsers' => $mailing_user_list,
	'mailing' => $mailing,
]);
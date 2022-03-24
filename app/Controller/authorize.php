<?php
/**
 * This file manage to access uploads/auth files.
 * You can keep files private from not logged users via this directory.
 *
 */
session_start();
if (isset($_SESSION['user_id'])) {
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
		ini_set('memory_limit', '64M');
		$fileOpen = fopen($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'], 'rb');
		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($fileInfo, $_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"]);
		header('Content-type: ' . $mime);
		fpassthru($fileOpen);
		fclose($fileOpen);
	} else {
		header("HTTP/1.1 404");
		include "view/404.phtml";
	}
	exit;
}
header('Location:' . $_SERVER['REMOTE_HOST'] . '/login');
exit;

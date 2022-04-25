<?php


/**
 * Sunucu bağımsız değişkenler burada belirleniyor.
 * Sunucu bağımlılığı olan değişkenleri sunucunuza göre belirtin.
 */

// Session start
ob_start();
session_start();

if (gethostname() === "hostname") {

	// Hata mesajlarının aktif hale getirilmesi
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	// Locale-Zaman Ayarlaması
	setlocale(LC_TIME, 'tr_TR');
	date_default_timezone_set('UTC');
	date_default_timezone_set('Europe/Istanbul');

	//default charset
	ini_set("default_charset", "utf-8");

	//sistemde tek dil olacak eğer çoklu dil olacksa bunun kontrolü indexde yapılmalıdır
	$_SESSION["lang"] = "tr";

	//yalnızca proje canlıya çıkılırken tanımlanması lazım formlarda cacpcha'lar buna göre çalışıyor.
	//define("LIVE_MODE", true);
	define("CAPTCHA_SITE_KEY", "");
	define("CAPTCHA_SECRET_KEY", "");

	//Eğer aktif ise hata mesajlarını sisteme tanımlanan hata moturu tarafından gösterir.
	define("DEBUG_MODE", true);

	//Veri tabanı bilgileri
	define("DB_HOST", "localhost");
	define("DB_NAME", "mimoza");
	define("DB_USER", "root");
	define("DB_PASS", "");

	//sistem pathleri
	$protocol = (@$_SERVER["HTTPS"] === "on") ? "https://" : "http://";
	//sistemin full pathi örn: /home/virtual/site-adi.com
	define("ROOT_PATH", realpath('.'));
	// site bir alt dizini kurulduysa yani test.com/proje-adi
	define('SUBFOLDER_NAME', dirname($_SERVER['SCRIPT_NAME']));
	//proje url'i yani http://proje-adi.com
	define('SITE_URL', $protocol . $_SERVER['SERVER_NAME'] . (SUBFOLDER_NAME === '/' ? null : SUBFOLDER_NAME));

	//editörden dosyların yükleneceği path
	//sitenin ismi "test.com/proje1" gibi
	$_SESSION["EDITOR_UPLOAD_SITE_FOLDER"] = "projectfiles";  //canlı sistemde buna gerek yok
	//upload edilecek klasör ismi
	$_SESSION["SESSION_PATH_KEY"] = "uploads";

	// This is session expire date. Session will store in database and check session from db.
	define("SESSION_DURATION", 86400);
	// Cache file
	define("CACHE_FILE_PATH", ROOT_PATH . '/public/cache/');
	// Default language
	define("DEFAULT_LANGUAGE", 'tr');


} else {
	die("Tanımsız Host: " . gethostname());
}

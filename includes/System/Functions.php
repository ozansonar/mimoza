<?php

namespace Includes\System;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Exception;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class Functions
{
	/**
	 * @var
	 */
	public $form_lang; //dili olan formları post etmek istediğimizde bunu gönderirsek sistem kendisi dili ekleyip post edecektir

	/**
	 * Y-m-d H:i:s formatindaki tarihi 12 Şubat,2018 formatına döndürür
	 *
	 * @param $tarih
	 * @return string|null
	 * @throws Exception
	 */
	public function date_long($tarih): ?string
	{
		if (empty($tarih)) {
			return null;
		}
		global $months;
		$tarih = new DateTime($tarih);
		$tarih = $tarih->format("d") . " " . $months[$_SESSION["lang"]]["long"][$tarih->format("m")] . ", " . $tarih->format("Y");
		return $tarih;
	}

	/**
	 * Y-m-d H:i:s formatindaki tarihi 12 Şub,2018 formatına döndürür
	 *
	 * @param $tarih
	 * @return string|null
	 * @throws Exception
	 */
	public function date_short($tarih): ?string
	{
		if (empty($tarih)) {
			return null;
		}
		global $months;
		$tarih = new DateTime($tarih);
		$tarih = $tarih->format("d") . " " . $months[$_SESSION["lang"]]["short"][$tarih->format("m")] . ", " . $tarih->format("Y");
		return $tarih;
	}

	/**
	 * Y-m-d H:i:s formatindaki tarihi 12 Şubat,2018 formatına döndürür
	 * @param $tarih
	 * @return string
	 * @throws Exception
	 */
	public function date_long_and_time($tarih): string
	{
		$tarih = new DateTime($tarih);
		global $months;
		$tarih = $tarih->format("d") . " " . $months[$_SESSION["lang"]]["long"][$tarih->format("m")] . ", " . $tarih->format("Y") . " " . $tarih->format("H") . ":" . $tarih->format("i");
		return $tarih;
	}

	/**
	 * @param $url
	 */
	public function redirect($url)
	{
		if ($url) {
			if (!headers_sent()) {
				header("Location:" . $url);
			} else {
				echo '<script>location.href="' . $url . '";</script>';
			}
		}
		exit;
	}

	/**
	 * @param $text
	 * @return string|null
	 */
	public function cleaner($text): ?string
	{
		if ($text !== NULL) {
			// TODO:: str_replace
//		$array = array('insert', 'update', 'union', '<script', 'alert', 'select', '*');
//		$text = str_replace($array, '', $text);
			return htmlspecialchars(stripslashes(strip_tags(trim($text))));
		}
		return NULL;
	}

	/**
	 * @param $text
	 * @return string|null
	 */
	public function cleaner_textarea($text): ?string
	{
		return $text;
		// TODO:: str_replace deprecated
		//return  trim(str_replace(['insert', 'update', 'union', 'select', '*', '<script'], ['', '', '', '', '', ''], $text));
	}

	/**
	 * @param $mail
	 * @return bool
	 */
	public function is_email($mail): bool
	{
		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $url
	 * @return bool
	 */
	public function is_url($url): bool
	{
		if (filter_var($this->cleaner($url), FILTER_VALIDATE_URL)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function csrfToken(): string
	{
        $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
		$token = '<input type="hidden" name="token" id="token" value="' . $_SESSION['token'] . '">';
		return $token;
	}

	public function getCsrfToken(): string
	{
		if (!isset($_SESSION['token'])) {
			$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
		}
		return $_SESSION['token'];
	}


	/**
	 * @param $str
	 * @param int $limit
	 * @return string
	 */
	public function kisalt($str, int $limit = 10): string
	{
		$str = strip_tags(htmlspecialchars_decode(html_entity_decode($str), ENT_QUOTES));
		$length = strlen($str);
		if ($length > $limit) {
			$str = mb_substr($str, 0, $limit, 'UTF-8');
		}
		return $str;
	}

	/**
	 * Permalink oluşturur.
	 *
	 * @param string $str
	 * @param array $options
	 * @return string
	 */
	public function permalink(string $str, array $options = array()): string
	{
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
		// tırnaklar için replace
		if (!empty($str)) {
			$str = str_replace(array('"&#39;"', "&quot;"), array(NULL, NULL), $str);
		}
		$defaults = array(
			'delimiter' => '-',
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => true
		);
		$options = array_merge($defaults, $options);
		$char_map = array(
			// Latin
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
			'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
			'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
			'ß' => 'ss',
			'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
			'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
			'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
			'ÿ' => 'y',
			// Latin symbols
			'©' => '(c)',
			// Greek
			'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
			'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
			'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
			'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
			'Ϋ' => 'Y',
			'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
			'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
			'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
			'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
			'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
			// Turkish
			'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
			'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
			// Russian
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
			'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
			'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
			'Я' => 'Ya',
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
			'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
			'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
			'я' => 'ya',
			// Ukrainian
			'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
			'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
			// Czech
			'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
			'Ž' => 'Z',
			'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
			'ž' => 'z',
			// Polish
			'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
			'Ż' => 'Z',
			'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
			'ż' => 'z',
			// Latvian
			'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
			'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
			'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
			'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
		$str = trim($str, $options['delimiter']);
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}

	/**
	 * Ip adresini döner
	 *
	 * @return array|false|string
	 */
	public function GetIP(): string
	{
		if (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
			if (strpos($ip, ',') !== false) {
				$tmp = explode(',', $ip);
				$ip = trim($tmp[0]);
			}
		} else {
			$ip = getenv("REMOTE_ADDR");
		}
		return $ip;
	}

	/**
	 * Verilen değeri size olarak döndürür (1GB, 3.44 MB ..)
	 * @param $bytes
	 * @return string
	 */
	public function formatSizeUnits($bytes): string
	{
		if ($bytes >= 1208925819614629174706176) {
			$bytes = number_format($bytes / 1208925819614629174706176, 2) . ' YB';
		} // Yotta Bytes
		elseif ($bytes >= 1180591620717411303424) {
			$bytes = number_format($bytes / 1180591620717411303424, 2) . ' ZB';
		} // Zetta Bytes
		elseif ($bytes >= 1152921504606846976) {
			$bytes = number_format($bytes / 1152921504606846976, 2) . ' EB';
		} // Exa Bytes
		elseif ($bytes >= 1125899906842624) {
			$bytes = number_format($bytes / 1125899906842624, 2) . ' PB';
		} // Peta Bytes
		elseif ($bytes >= 1099511627776) {
			$bytes = number_format($bytes / 1099511627776, 2) . ' TB';
		} // Tera Bytes
		elseif ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} // Giga Bytes
		elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} // Mage Bytes
		elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} //Kilo Bytes
		elseif ($bytes > 1) {
			$bytes .= ' bytes';
		} elseif ($bytes == 1) {
			$bytes .= ' byte';
		} else {
			$bytes = '0 bytes';
		}
		return $bytes;
	}

	/**
	 * Debug için
	 *
	 * @param $data
	 */
	public function pre_arr($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	/**
	 * @param $data
	 * @return mixed|null
	 */
	public function post($data)
	{
		if (!empty($this->form_lang)) {
			return $_POST[$data . "_" . $this->form_lang] ?? null;
		}
		return $_POST[$data] ?? null;
	}

	/**
	 * $_GET[$data] değerini döner
	 *
	 * @param $data
	 * @return mixed|null
	 */
	public function get($data)
	{
		return $_GET[$data] ?? null;
	}

	/**
	 * $_POST[$data] değerini temizleyerek döner.
	 * @param $data
	 * @return string|null
	 */
	public function clean_post($data): ?string
	{
		return $this->cleaner($this->post($data));
	}

	public function cleanPostArray($postKey): array
	{
		$purified = [];
		foreach ($this->post($postKey) as $key => $item) {
			$purified[$key] = $this->cleaner($item);
		}
		return $purified;
	}

	/**
	 * Textarea'dan gelen değerleri temizlemek için kullanılır.
	 *
	 * @param $data
	 * @return string|null
	 */
	public function clean_post_textarea($data): ?string
	{
		return $this->cleaner_textarea($this->post($data));
	}

	/**
	 * $_GET[$data] değerini temizleyerek döner
	 *
	 * @param $data
	 * @return string
	 */
	public function clean_get($data): string
	{
		return $this->cleaner($this->get($data));
	}

	/**
	 * $_POST[$data] integer yapar ve temizleyerek döner.
	 *
	 * @param $data
	 * @return int
	 */
	public function clean_post_int($data): int
	{
		return (int)($this->post($data));
	}

	/**
	 * $_GET[$data] değerini integer olarak ve temizleyerek döner.
	 *
	 * @param $data
	 * @return int
	 */
	public function clean_get_int($data): int
	{
		return (int)($this->get($data));
	}

	/**
	 * Verilen name değerini temizler ve $_POST[$name] değerini döner
	 *
	 * @param $name
	 * @return array|string|string[]|void
	 */
	public function array_post($name)
	{
		if (isset($_POST[$name])) {
			if (is_array($_POST[$name])) {
				return array_map(function ($item) {
					return $this->cleaner($item);
				}, $_POST[$name]);
			}
			return htmlspecialchars(trim($_POST[$name]));
		}
	}

	/**
	 * Bir datanın integer olup olmadığını kontrol eder ve temizler
	 *
	 * @param $data
	 * @return false|string
	 */
	public function is_int($data)
	{
		$data = trim($data);
		if (is_numeric($this->clean_post($data))) {
			return trim($this->clean_post($data));
		}
		return false;
	}

	/**
	 * Telefon numarasını doğrular
	 *
	 * @param $phone
	 * @return bool
	 */
	public function phone_validation($phone): bool
	{
		if (preg_match("/^[0-9]{4}-[0-9]{3}-[0-9]{4}$/", $phone)) {
			return true;
		}
		return false;
	}

	/**
	 * @param $sayi
	 * @param $birinci_sayi
	 * @param $ikinci_sayi
	 * @return bool
	 */
	public function aralik_kontrolu($sayi, $birinci_sayi, $ikinci_sayi)
	{
		$aralik = range($birinci_sayi, $ikinci_sayi);
		return in_array($sayi, $aralik);
	}

	/**
	 * Yazı büyük yapar
	 *
	 * @param $string
	 * @return string
	 */
	public function tr_strtoupper($string): string
	{
		$search = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
		$replace = array("Ç", "İ", "I", "Ğ", "Ö", "Ş", "Ü");
		$string = str_replace($search, $replace, $string);
		$string = strtoupper($string);
		return $string;
	}

	/**
	 * Yazıyı küçük yapar
	 *
	 * @param $string
	 * @return string
	 */
	public function tr_strtolower($string): string
	{
		$search = array("Ç", "İ", "I", "Ğ", "Ö", "Ş", "Ü");
		$replace = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
		$text = str_replace($search, $replace, $string);
		$text = strtolower($text);
		return $text;
	}

	/**
	 * Bazı tr karekterler bu şekilde db de tutulmuş onları çevirmek için yazıldı
	 *
	 * @param $string
	 * @return array|string|string[]
	 */
	public function tr_cevir($string): string
	{
		$search = array("&#252;", "&#231;", "&#220;", "&#199;", "&#246;", "&#214;", "&uuml;", "&ouml;");
		$replace = array("ü", "ç", "Ü", "Ç", "ö", "Ö", "ü", "ö");
		$text = str_replace($search, $replace, $string);
		return $text;
	}

	/**
	 * Türkçe karakterleri en ye çevirip küçültür
	 *
	 * @param $string
	 * @return string
	 */
	public function en_strtolower($string): string
	{
		$search = array("ç", "Ç", "ı", "İ", "I", "ğ", "Ğ", "ö", "Ö", "ş", "Ş", "ü", "Ü");
		$replace = array("c", "c", "i", "i", "i", "g", "g", "o", "o", "s", "s", "u", "u");
		$text = str_replace($search, $replace, $string);
		$text = strtolower($text);
		return $text;
	}

	/**
	 * Kelimelerin ilk harflerini büyük yapar
	 *
	 * @param $string
	 * @return string
	 */
	function tr_ucwords($string): string
	{
		$lower_arr = array("i" => "İ");
		$string = strtr($string, $lower_arr);
		return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
	}

	/**
	 * Cümlenin ilk harifini büyük yapar
	 *
	 * @param $str
	 * @return string
	 */
	function tr_ucfirst($str): string
	{
		$m_uzunluk = mb_strlen($str, "UTF-8");
		$ilkharf = mb_substr($str, 0, 1, "UTF-8");
		$kalan = mb_substr($str, 1, $m_uzunluk - 1, "UTF-8");
		$ilkharf = mb_strtoupper($ilkharf, "UTF-8");
		$kalan = mb_strtolower($kalan, "UTF-8");
		return $ilkharf . $kalan;
	}

	/**
	 * @param $url
	 * @return bool|string
	 */
	public function curlKullan($url): string
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
		$curlData = curl_exec($curl);
		curl_close($curl);
		return $curlData;
	}

	/**
	 * Site url verir. (https://site.com/)
	 * @param string $url
	 * @return string
	 */
	public function site_url(string $url = ""): string
	{
		return SITE_URL . "/" . $url;
	}

	/**
	 * Sitenin birden fazla dili varsa urli düzeltiyor.
	 *
	 * @param string $url
	 * @return string
	 */
	public function site_url_lang(string $url = ""): string
	{
		if (defined("MULTIPLE_LANGUAGE")) {
			return SITE_URL . "/" . $_SESSION["lang"] . "/" . $url;
		}
		return SITE_URL . "/" . $url;
	}

	/**
	 * Sitenin urlini verir. (https://site.com/uploads/$url)
	 * @param string $url
	 * @return string
	 */
	function uploads_url(string $url = ""): string
	{
		return SITE_URL . "/uploads/" . $url;
	}

	/**
	 * Site root path verir. (/var/home/site.com)
	 * @param string $url
	 * @return string
	 */
	public function root_url(string $url = ""): string
	{
		return ROOT_PATH . "/" . $url;
	}

	/**
	 * Sayfayı verilen time (saniye) değerine göre yeniler
	 *
	 * @param string $url
	 * @param int $time
	 */
	public function refresh(string $url, int $time = 5): void
	{
		header("Refresh:$time; url=$url");
		//exit;
	}

	/**
	 * @param string $text
	 * @return bool
	 */
	public function safString(string $text)
	{
		return preg_match("/^[a-zA-ZşŞüÜıİğĞçÇöÖ]+$/", $text) == 1;
	}

	/**
	 * @param string $text
	 * @return bool
	 */
	public function safString_bosluklu(string $text): bool
	{
		return preg_match("/^[a-zA-ZşŞüÜıİğĞçÇöÖ ]+$/", $text) == 1;
	}

	/**
	 *
	 */
	public function permission_page()
	{
		echo 'Bu bölümü görüntüleme yetkiniz yok!';
		exit;
	}

	/**
	 * @param $password
	 * @param string|null $msg_key_text
	 * @return array
	 */
	public function passwordControl($password, string $msg_key_text = null): array
	{
		$password = $this->cleaner($password);
		$errors = array();
		if (empty($password)) {
			$errors[] = $msg_key_text . " boş olamaz.";
		}
		if (!empty($password)) {
			if (strlen($password) < 8) {
				$errors[] = $msg_key_text . " en az 8 karakter olmalıdır.";
			}

			if (strlen($password) >= 8) {
				if (!preg_match("#[0-9]+#", $password)) {
					$errors[] = $msg_key_text . " en az bir rakam içermek zorundadır.";
				}
				if (!preg_match("#[a-z]+#", $password)) {
					$errors[] = $msg_key_text . " en az bir küçük harf içermelidir.";
				}
				if (!preg_match("#[A-Z]+#", $password)) {
					$errors[] = $msg_key_text . " en az bir büyük harf içermelidir.";
				}
			}
		}
		return $errors;
	}

	/**
	 * Telefon numarasını formatlar
	 *
	 * @param $number
	 * @return mixed|string
	 */
	public function phoneFormat($number)
	{
		if (ctype_digit($number) && strlen($number) == 10) {
			$number = '+90 ' . substr($number, 0, 3) . ' ' . substr($number, 3, 3) . ' ' . substr($number, 6, 2) . ' ' . substr($number, 8, 2);
		} else if (ctype_digit($number) && strlen($number) == 7) {
			$number = substr($number, 0, 3) . ' ' . substr($number, 3, 4);
		}
		return $number;
	}

	/**
	 * Büyük-Küçük harfler ve sayılardan oluşan string döndürür
	 *
	 * @param int $length
	 * @return string
	 */
	public function generateRandomString(int $length = 10): string
	{
		$characters = '0123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return str_shuffle($randomString);
	}

	/**
	 * Küçük harflerden oluşan string döndürür
	 *
	 * @param int $length
	 * @return string
	 */
	public function generateLowerString(int $length = 10): string
	{
		$characters = 'abcdefghijkmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	/**
	 * Büyük harflerden oluşan string döndürür
	 *
	 * @param int $length
	 * @return string
	 */
	public function generateUpperString(int $length = 10): string
	{
		$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	/**
	 * Sayılardan oluşan string döndürür
	 *
	 * @param int $length
	 * @return string
	 */
	public function generateNumberString(int $length = 10): string
	{
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	/**
	 * @param array $item
	 * @return array|void
	 */
	public function image_upload_array(array $item = array())
	{
		require_once $this->root_url("includes/class/class.upload.php");
		if (count($_FILES[$item["name"]]) > 0) {
			$url = $this->root_url("uploads/");
			if (isset($item["path"])) {
				$url = $this->root_url("uploads/" . $item["path"]);
			}
			$file_size = 2;
			if (isset($item["file_size"])) {
				$file_size = $item["file_size"];
			}
			if (isset($item["resize"]) && $item["resize"] == true) {
				if (isset($item["path"])) {
					$url = $this->root_url("uploads/" . $item["path"]);
				}
				if (isset($item["width"]) and isset($item["height"])) {
					$width = $item["width"];
					$height = $item["height"];
					$url = $this->root_url("uploads/" . $item["path"]);
				}
			}
			$image['name'] = $_FILES[$item["name"]]["name"][$item["count"]]["file"];
			$image['type'] = $_FILES[$item["name"]]["type"][$item["count"]]["file"];
			$image['tmp_name'] = $_FILES[$item["name"]]["tmp_name"][$item["count"]]["file"];
			$image['error'] = $_FILES[$item["name"]]["error"][$item["count"]]["file"];
			$image['size'] = $_FILES[$item["name"]]["size"][$item["count"]]["file"];
			$image_name = uniqid() . "-" . time();
			$handle = new Upload($image, "tr_TR");
			if ($handle->uploaded) {
				// maksimum yüklenecek dosya boyutu belirlenir. 1024 = 1KB
				$handle->file_max_size = 1024 * 1024 * $file_size; // 5 mb yapar
				$handle->file_new_name_body = $image_name;

				//thumb varsa önce resme dokunmadan yükle
				if (isset($item["thumb"])) {
					$handle->Process($url);
					$url = $this->root_url("uploads/" . $item["path"] . "/thumb/");
					$handle->file_new_name_body = $image_name;
				}
				$handle->image_ratio = false;
				if (isset($item["resize"]) && $item["resize"] == true) {
					$handle->image_resize = true;
					//$handle->image_ratio_crop = false;
					$handle->image_ratio_crop = true;
					$handle->image_x = $width;
					$handle->image_y = $height;
					//arka alanı beyaz yapar
					//$handle->image_ratio_fill = true;
				}
				if (isset($item["upload_type"])) {
					if ($item["upload_type"] == "img") {
						$handle->allowed = array("image/jpeg", "image/png");
					} elseif ($item["upload_type"] == "pdf") {
						$handle->allowed = array("application/pdf");
					} elseif ($item["upload_type"] == "pdf_and_img_and_word") {
						$handle->allowed = array("application/pdf", "image/jpeg", "image/png", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
					} elseif ($item["upload_type"] == "pdf_and_img") {
						$handle->allowed = array("application/pdf", "image/jpeg", "image/png", "image/jpg");
					} elseif ($item["upload_type"] == "pdf_and_word") {
						$handle->allowed = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
					} else {
						$handle->allowed = array("image/jpeg", "image/png", "image/jpg");
					}
				} else {
					$handle->allowed = array("image/jpeg", "image/png", "image/jpg");
				}
				$handle->Process($url);
				$img_path = $handle->file_dst_name;
				if ($handle->processed) {
					//resim yüklenmişse çalışır
					$result = 1;
					return array($result, $img_path);
				} else {
					//resim yüklenememişse çalışır
					//$result = $handle->error;
					$result = 2;
					return array($result, $img_path);
				}
			} else {
				//resim seçilmemişse çalışır
				$result = 3;
				return array($result, $img_path = "");
			}
		}
	}

	/**
	 * İki tarih arasında kaç ay olduğunu döner.
	 *
	 * @param $from
	 * @param $to
	 * @return float|int|mixed
	 */
	public function get_interval_in_month($from, $to)
	{
		$month_in_year = 12;
		$date_from = getdate(strtotime($from));
		$date_to = getdate(strtotime($to));
		return ($date_to['year'] - $date_from['year']) * $month_in_year -
			($month_in_year - $date_to['mon']) +
			($month_in_year - $date_from['mon']);
	}

	/**
	 * Şifre Generate ediyor
	 *
	 * @return string
	 */
	public function generate_password(): string
	{
		$password = $this->generateNumberString(5) . $this->generateLowerString(3) . $this->generateUpperString(3);
		return str_shuffle($password);
	}

	/**
	 * admin panelde kullanılıyor çok faydalı
	 *
	 * @param $text
	 * @param int $limit
	 * @param string $icon
	 * @return mixed|string
	 */
	public function textModal($text, int $limit = 25, string $icon = "fas fa-info-circle ml-2")
	{
		$result = null;
		if (strlen($text) > $limit) {
			$result .= $this->kisalt($text, $limit);
			$uniq = uniqid(true) . time();
			$result .= '  <i class="' . $icon . ' ml-2 table-modal-icon" data-toggle="modal" data-target="#page_modal_' . $uniq . '"></i>';
			$result .= '<!-- Basic modal -->
                            <div id="page_modal_' . $uniq . '" class="modal fade" tabindex="-1">
                                <div class="modal-dialog  modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detay</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>' . $text . '</p>             
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Kapat</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- /basic modal -->';
		} else {
			$result = $text;
		}
		return $result;
	}

	/**
	 * Karakter seti iso olan textleri utf-8'e çevirir
	 *
	 * @param $text
	 * @return false|string
	 */
	public function text_edit($text)
	{
		return iconv("ISO-8859-9", "UTF-8", $text);
	}

	/**
	 * Veritabanından session lang değerine göre statik metinleri döner.
	 *
	 * @param $key
	 * @return mixed|null
	 */
	public function textManager($key)
	{
		global $textManager;
		return ($textManager->{$_SESSION["lang"]}[$key]) ?? NULL;
	}

	/**
	 * Content Tablosundaki kayıtların gönderilen değerlere göre linkini oluşturur.
	 *
	 * @param null $categories
	 * @param null $content
	 * @return string
	 */
	public function createContentUrl($categories = null, $content = null): string
	{
		global $settings;
		$categories_link = null;
		if (!empty($categories)) {
			$categories_link = $categories->link . "-" . $categories->id;
		}
		$content_link = null;
		if (!empty($content)) {
			$content_link = $content->link . "-" . $content->id;
		}
		return $this->site_url_lang($settings->{"content_prefix_" . $_SESSION["lang"]} . "/" . $categories_link . "/" . $content_link);
	}

	/**
	 * @return array
	 */
	public function system_lang_key_value(): array
	{
		global $projectLanguages;
		$lang_array = array();
		foreach ($projectLanguages as $project_languages_row) {
			$lang_array[$project_languages_row->short_lang] = $project_languages_row->lang;
		}
		return $lang_array;
	}

	/**
	 * Tabloda 0000-00-00 olan date değerleini eler. True ise gerçek bir tarihtir.
	 *
	 * @param $date
	 * @return bool
	 */
	public function is_date($date): bool
	{
		return $date != "0000-00-00";
	}

	/**
	 * Verilen iki tarih arasındaki tarihleri verilen formata göre döndürür.
	 *
	 * @param string $begin
	 * @param string $end
	 * @param string $format
	 * @return array
	 */
	public function DatePeriod_start_end(string $begin, string $end, string $format = 'Y-m-d'): array
	{
		$period = CarbonPeriod::create($begin, $end);
		$dates = [];
		foreach ($period as $date) {
			$dates[] = $date->format($format);
		}
		return $dates;
	}

	/**
	 * Tarihten günümüze kadar  dizi olarak ayları döner => [2021-01-01 => 'February']
	 *
	 * @param Carbon $start
	 * @return array
	 */
	public function getMonthListFromDate(Carbon $start): array
	{
		$start = $start->startOfMonth();
		$end = Carbon::today()->startOfMonth();

		do {
			$months[$start->format('m-Y')] = $start->format('F');
		} while ($start->addMonth() <= $end);

		return $months;
	}




}
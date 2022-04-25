<?php
/**
 * Veri tabanı bağlantısını yapılan ana sınıf. Bütün veri tabanı işlemleri buradan yapılıyor.
 *
 * @version 1.0.0
 * @author Ozan SONAR <ozansonar1@gmail.com>
 */

namespace Includes\System;

use Exception;
use PDO;
use PDOException;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * PDO sınıfı kullanılarak oluşturulan database sınıfı
 */
class Database
{
	/**
	 * Database variable
	 *
	 * @var PDO
	 */
	public static PDO $db;

	/**
	 * Veri tabanını kullanmak için sınıf başladığında kullanılan metod.
	 *
	 * @param string $host Veri tabanı sunucu adresi
	 * @param string $dbname Veri tabanı adı
	 * @param string $user Veri tabanı kullanıcı adı
	 * @param string $password Veri tabanı kullanıcı şifresi
	 * @throws PDOException
	 * @throws Exception
	 */
	public function __construct(string $host, string $dbname, string $user, string $password, string $charset = 'utf8')
	{
		try {
			self::$db = new PDO("mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8", $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . $charset . "';"));
			self::$db->exec("SET CHARACTER SET " . $charset);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db->exec("set names " . $charset);
			if (DEBUG_MODE) {
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		} catch (PDOException $e) {
			throw self::throwException($e);
		}
	}

	public static function query($query)
	{
		return self::$db->prepare($query);
	}

	/**
	 * Veri tabanı hatalarını hata mesajı motoruna göre döner.
	 *
	 * @param Throwable $message
	 * @return PDOException
	 */
	public static function throwException(Throwable $message): PDOException
	{
		if (DEBUG_MODE) {
			$handler = new PrettyPageHandler();
			$handler->setEditor('phpstorm');
			$whoops = new Run();
			$whoops->pushHandler($handler);
			return new PDOException($whoops->handleException($message));
		}
		return new PDOException();
	}

	/**
	 * Tabloya eklenen son kaydın id'sini döner
	 *
	 * @return string
	 */
	public static function getLastInsertedId(): string
	{
		return self::$db->lastInsertId();
	}

	/**
	 * Tabloya veri eklemek için kullanılan metotdur.
	 *
	 * @param string $table Tablo ismi
	 * @param array $data Eklenecek veri 'column_name'=>'value'
	 * @throws PDOException
	 */
	public static function insert(string $table, array $data): bool
	{
		try {
			$sql_text = self::getSqlText($data);
			$insert = self::$db->prepare("INSERT INTO " . $table . " SET " . $sql_text . " ");
			foreach ($data as $dat_key => $dat_value) {
				$insert->bindValue(':' . $dat_key, $dat_value, self::paramType($dat_value));
			}

			return $insert->execute();

		} catch (PDOException $e) {
			throw self::throwException($e);
		}
	}

	/**
	 * Verilen bilgilere göre tabloda güncelleme işlemi yapar
	 *
	 * @param string $table Tablo ismi
	 * @param array $data Güncellenecek değerler  'column_name'=>'val'
	 * @param array $where Güncelleme şartları 'column_name'=>'val'
	 * @param array $whereNot
	 * @return bool
	 */
	public static function update(string $table, array $data, array $where, array $whereNotIn = NULL): bool
	{
		try {
			$sqlText = self::getSqlText($data);
			$whereText = self::getWhereText($where);
			$whereNotText = self::getWhereNotText($whereNotIn);

			$update = self::$db->prepare("UPDATE " . $table . " SET " . $sqlText . " WHERE " . $whereText . $whereNotText . " ");

			foreach ($data as $dat_key => $dat_value) {
				$update->bindValue(':' . $dat_key, $dat_value, self::paramType($dat_value));
			}

			foreach ($where as $key => $value) {
				$update->bindValue(':' . $key, $value, self::paramType($value));
			}

			return $update->execute();

		} catch (PDOException $e) {
			throw self::throwException($e);
		}
	}

	/**
	 * Veri tabanına gönderilen verilerin tiplerini döner.
	 *
	 * @param string|int|boolean|null $val Veri tabanındaki değer.
	 * @return int
	 */
	public static function paramType($val): int
	{
		if (is_int($val)) {
			return PDO::PARAM_INT;
		}

		if (is_bool($val)) {
			return PDO::PARAM_BOOL;
		}

		if (is_null($val)) {
			return PDO::PARAM_NULL;
		}
		return PDO::PARAM_STR;
	}

	/**
	 * Verilen bilgilere göre veri döner.
	 *
	 * @param string $table Tablo adı
	 * @param array|null $where Sorgu şartları.
	 * @param bool $singleRow
	 * @param array|null $select Çekilecek kolonlar. Varsayılan olarak tüm kolonları çeker
	 * @param int $type Dönen veri tipi. 5 obje döndürür, 2 ise dizi döndürür. Bakınız: https://phpdelusions.net/pdo/fetch_modes
	 * @param string $orderColumn Dönen verilerin sıralanması için. Varsayılan değer id'dir.
	 * @return array|false|mixed|object
	 */
	public static function selectQuery(string $table, array $where = null, bool $singleRow = false, array $select = null, int $type = 5, string $orderColumn = " id ")
	{
		try {
			if (!empty($select)) {
				$selectQuery = implode(', ', $select);
			} else {
				$selectQuery = '*';
			}
			if (!empty($where)) {
				$whereText = " WHERE " . self::getWhereText($where);
			} else {
				$whereText = "";
			}

			$select = self::$db->prepare("SELECT " . $selectQuery . " FROM " . $table . $whereText . "  ORDER BY " . $orderColumn . " ");
			if (!empty($where)) {
				foreach ($where as $key => $value) {
					$select->bindValue(':' . $key, $value, self::paramType($value));
				}
			}
			$select->execute();
			if ($singleRow === true) {
				return $select->fetch($type);
			}
			return $select->fetchAll($type);
		} catch (PDOException $e) {
			throw self::throwException($e);
		}
	}

	/**
	 *  For use make pagination
	 *
	 * @param string $sql
	 * @param array $where
	 * @return array
	 */
	public static function paginate(string $sql, array $where = array()): array
	{
		global $textManager;
		// sayfa sayısı (kaçıncı sayfa)
		$sayfa = isset($_GET['q']) && is_numeric($_GET['q']) ? $_GET['q'] : 1;
		if ($sayfa <= 0) {
			$sayfa = 1;
		}
		// veriler kaçtan başlayacak
		$baslangic = ($sayfa * $where["limit"]) - $where["limit"];


		//veriyi bulalım
		$query = self::$db->prepare($sql);
		if (!empty($where)) {
			foreach ($where as $w_key => $w_value) {
				$query->bindValue(':' . $w_key, $w_value, self::paramType($w_value));
			}
			$query->bindValue(":baslangic", $baslangic, PDO::PARAM_INT);
		}
		$query->execute();
		$result = array();
		$data = $query->fetchAll(PDO::FETCH_OBJ);
		$result["data"] = $data;

		//şimdi toplam sayıyı buluyoruz
		$total_count = self::$db->prepare("SELECT FOUND_ROWS() as total");
		$total_count->execute();
		$total = $total_count->fetch(PDO::FETCH_OBJ);
		$paginate_count = ceil($total->total / $where["limit"]);


		$sol = $sayfa - 3;
		$sag = $sayfa + 3;

		if ($sayfa <= 3) {
			$sag = 7;
		}
		if ($sag > $paginate_count) {
			$sol = $paginate_count - 6;
		}

		$counter = 1;
		if (isset($sayfa) && $sayfa > 1) {
			$result["paginate"][$counter]["value"] = 1;
			$result["paginate"][$counter]["text"] = $textManager->{$_SESSION["lang"]}["sayfalama_ilk_sayfa"] ?? null;
			$counter++;

			$result["paginate"][$counter]["value"] = $sayfa - 1;
			$result["paginate"][$counter]["text"] = $textManager->{$_SESSION["lang"]}["sayfalama_onceki_sayfa"] ?? null;
			$counter++;
		}
		for ($i = $sol; $i <= $sag; $i++) {
			if ($i > 0 && $i <= $paginate_count) {
				$result["paginate"][$counter]["text"] = $i;
				$result["paginate"][$counter]["value"] = $i;
				if ($i == $sayfa) {
					$result["paginate"][$counter]["active"] = 1;
				}
				$counter++;
			}
		}

		if (isset($sayfa) && $sayfa < $paginate_count) {
			$result["paginate"][$counter]["value"] = $sayfa + 1;
			$result["paginate"][$counter]["text"] = $textManager->{$_SESSION["lang"]}["sayfalama_sonraki_sayfa"] ?? null;

			$counter++;
			$result["paginate"][$counter]["text"] = $textManager->{$_SESSION["lang"]}["sayfalama_son_sayfa"] ?? null;
			$result["paginate"][$counter]["value"] = $paginate_count;
		}
		return $result;

	}

	/**
	 * It's returns not in string in based on the given array
	 *
	 * @param array $whereNotIn
	 * @return string
	 */
	protected static function getWhereNotText(array $whereNotIn = NULL): string
	{
		if (!empty($whereNotIn)) {
			$whereNotBuild = [];
			foreach ($whereNotIn as $key => $value) {
				$whereNotBuild[] = $key . " NOT IN (" . $value . " )";
			}
			return implode(" AND ", $whereNotBuild);
		}
		return '';
	}

	/**
	 * It's returns where string in based on the given array
	 *
	 * @param array $where
	 * @return string
	 */
	protected static function getWhereText(array $where): string
	{
		$whereBuild = [];
		foreach (array_keys($where) as $key) {
			$whereBuild[] = $key . "=:" . $key;
		}
		return implode(" AND ", $whereBuild);
	}

	/**
	 * It's returns sql string in based on the given array
	 *
	 * @param array $data
	 * @return string
	 */
	protected static function getSqlText(array $data): string
	{
		$sqlBuild = [];
		foreach (array_keys($data) as $key) {
			$sqlBuild[] = "`".$key."`" . "=:" . $key;
		}

		return implode(", ", $sqlBuild);
	}
}
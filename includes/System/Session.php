<?php

namespace Includes\System;

use JetBrains\PhpStorm\Pure;

class Session
{
	/**
	 * Database class
	 *
	 * @var Database
	 */
	private Database $database;

	/**
	 * Function class
	 *
	 * @var Functions
	 */
	private Functions $functions;

	/**
	 * Constructor Function
	 *
	 * @param Database $database
	 */
	public function __construct(Database $database)
	{
		$this->database = $database;
		$this->functions = new Functions();
	}

	/**
	 * Login method
	 *
	 * @param string $mail
	 * @param string $password
	 * @return array
	 */
	public function login(string $mail, string $password): array
	{
		$mail = $this->functions->cleaner($mail);
		$password = $this->functions->cleaner($password);

		$message = array();
		if (empty($mail) || empty($password)) {
			$message["reply"][] = $this->functions->textManager("giris_ad_veya_sifre_bos");
		}
		if (!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			$message["reply"][] = $this->functions->textManager("giris_email_gecerli_formatta_degil");
		}
		if (empty($message)) {
			$userData = $this->userControl($mail);
			if (empty($userData)) {
				$message["reply"][] = $this->functions->textManager("giris_hatali_bilgiler");
			} elseif ((int)$userData->status !== 1) {
				$message["reply"][] = $this->functions->textManager("giris_hesap_beklemede");
			} elseif ((int)$userData->email_verify !== 1) {
				$message["reply"][] = $this->functions->textManager("giris_hesap_dogrulanmamis");
			} else {
				$passwordVerify = password_verify($password, $userData->password);
				if ($passwordVerify) {
					if ($userData->rank < 1) {
						$message["reply"][] = $this->functions->textManager("giris_yetkisiz_kullanici");
					} else {
						session_regenerate_id();
						$this->createSession(
							[
								"user_email" => $userData->email,
								"user_id" => $userData->id,
								"user_rank" => $userData->rank,
								"role_group" => $userData->role_group,
								"session_token" => $this->functions->generateRandomString(100),
							]);
						$message["success"][] = $this->functions->textManager("giris_islem_basarili");
						$message["redirect_time"] = 5;
					}
				} else {
					$message["reply"][] = $this->functions->textManager("giris_hatali_bilgiler");
				}
			}
		}
		return $message;
	}

	/**
	 * It's create session with given array as key => value
	 *
	 * @param array $array
	 */
	public function createSession(array $array = []): void
	{
		if ($this->addUserSessionToDatabase($array)) {
			foreach ($array as $key => $value) {
				$_SESSION[$this->functions->cleaner($key)] = $this->functions->cleaner($value);
			}
		}
	}

	/**
	 * It's return true if there is a user session
	 *
	 * @return bool
	 */
	public function isThereUserSession(): bool
	{
		if (isset($_SESSION["user_id"], $_SESSION["user_email"]) && $this->checkUserSession()) {
			$selectQuery = $this->database::selectQuery("users", array(
				"id" => $_SESSION["user_id"],
				"email_verify" => 1,
				"deleted" => 0,
			), true);
			return $selectQuery && (int)$selectQuery->status === 1;
		}
		return false;
	}

	/**
	 * It's return true if there is a admin session
	 *
	 * @return bool
	 */
	public function isThereAdminSession(): bool
	{
		if (isset($_SESSION["user_id"], $_SESSION["user_email"]) && $this->checkUserSession()) {
			$selectQuery = $this->database::selectQuery("users", array(
				"id" => $_SESSION["user_id"],
				"email_verify" => 1,
				"status" => 1,
				"deleted" => 0,
			), true);
			return $selectQuery && $selectQuery->rank >= 60;
		}
		return false;
	}

	/**
	 * $id gönderirlirse o idye ait verileri verir gönderilmez ise sessionda olan user_id ye ait verileri verir
	 *
	 * @param int|null $id
	 * @return false|object
	 */
	public function getUserInfo(int $id = null)
	{
		$id = (is_null($id)) ? (int)$_SESSION["user_id"] : $id;
		$selectQuery = $this->database::selectQuery("users", [
			"id" => $id,
			"deleted" => 0,
		], true);

		return !(empty($selectQuery)) ? $selectQuery : false;
	}

	/**
	 * It's return email if is valid
	 *
	 * @param string $mail
	 * @return string|null
	 */
	public function isThatAnEmail(string $mail): ?string
	{
		return (filter_var($mail, FILTER_VALIDATE_EMAIL)) ? $mail : NULL;
	}

	/**
	 * Returns a user whose email was given
	 *
	 * @param string $email
	 * @return array
	 */
    public function userControl(string $email): ?object
    {
        $userMail = $this->isThatAnEmail($email);
        $user = $this->database::selectQuery("users", array(
            "email" => $userMail,
            "deleted" => 0,
        ), true);
        if (!empty($user)) {
            return $user;
        }
        return NULL;
    }

	/**
	 * It's return session value
	 *
	 * @param $name
	 * @return false|mixed
	 */
	public function get($name)
	{
		return !(empty($_SESSION[$name])) ? $_SESSION[$name] : NULL;
	}

	/**
	 * Kullanıcının yetkisini kontrol eder
	 *
	 * @param $roleKey
	 * @param $permission
	 * @return bool
	 */
	public function sessionRoleControl($roleKey, $permission): bool
	{
		$selectQuery = $this->database::selectQuery("role_permission", array(
			"role_group" => $this->get("role_group"),
			"role_key" => $roleKey,
			"permission" => $permission,
			"deleted" => 0,
		));
		return !(empty($selectQuery));
	}

	/**
	 * Redirect to 403 page
	 */
	public function permissionDenied(): void
	{
		$this->functions->redirect("403");
	}

	/**
	 * It's add users session_token to database
	 *
	 * @param array $user
	 * @return bool
	 */
	public function addUserSessionToDatabase(array $user): bool
	{
		// Creating new session
		return $this->database::insert("sessions", [
			'user_id' => $user['user_id'],
			'token' => $user['session_token'],
			'browser' => $_SERVER['HTTP_USER_AGENT'],
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'x_forwarded_for' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? NULL,
			'expire_date' => date('Y-m-d H:i:s', (time() + SESSION_DURATION)),
			'created_at' => date('Y-m-d H:i:s'),
		]);

	}

	/**
	 * It's check user session with db
	 *
	 * @return bool
	 */
	public function checkUserSession(): bool
	{
		if (!empty($this->get('user_id'))) {
			$session = $this->database::selectQuery('sessions', [
				'user_id' => $this->get('user_id'),
				'token' => $this->get('session_token'),
				'deleted' => 0,
			], true);

			// Delete Sessions
			if (empty($session)) {
				$this->destroyAllSessions();
				$this->put(['session_error' => 'Oturumunuz bulunamadı. Lütfen oturum açınız!']);
				return false;
			}

			if (isset($session->expire_date) && $session->expire_date < date('Y-m-d H:i:s')) {
				$this->destroyAllSessions();
				$this->deleteUserAllSessions($session);
				$this->put(['session_error' => 'Oturum süreniz dolduğu için oturumunuz sonlanmıştır!']);
				return false;
			}

			// Update session time
			$this->database::update('sessions',
				['expire_date' => date('Y-m-d H:i:s', time() + SESSION_DURATION)],
				['id' => $session->id]
			);
		}
		return true;
	}

	/**
	 * It's delete specific user sessions from sessions table
	 *
	 * @param $userId
	 * @return bool
	 */
	public function deleteUserAllSessions($session): bool
	{
		return $this->database::update('sessions',
			['deleted' => 1,],
			['user_id' => $session->user_id]);
	}

	/**
	 * It's destroy all user sessions
	 *
	 * @return void
	 */
	private function destroyAllSessions(): void
	{
		unset($_SESSION["user_email"], $_SESSION["user_id"], $_SESSION["user_rank"], $_SESSION["role_group"], $_SESSION["session_token"]);
	}

	/**
	 * Unset session value
	 *
	 * @param string $session
	 * @return void
	 */
	public function delete(string $session): void
	{

		unset($_SESSION[$session]);
	}

	public function put(array $array): void
	{
		foreach ($array as $key => $value) {
			$_SESSION[$key] = $value;
		}
	}


}

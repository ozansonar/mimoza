<?php

namespace Includes\System;

class Core
{
	/**
	 * It's return controller path
	 *
	 * @param string $controllerName
	 * @return string
	 */
	public function controller(string $controllerName): string
	{
		$controllerName = strtolower($controllerName);
		return ROOT_PATH . '/app/Controller/' . $controllerName . '.php';
	}

	/**
	 * @param $index
	 * @return false|mixed
	 */
	public function route($index)
	{
		global $route;
		return $route[$index] ?? false;
	}

	/**
	 * It's return view url
	 *
	 * @param string $viewName
	 * @return string
	 */
	public function view(string $viewName): string
	{
		global $settings;
		return ROOT_PATH . '/app/View/' . $settings->theme . '/' . $viewName . '.php';
	}

	/**
	 * It's return public url
	 *
	 * @param string $url
	 * @return string
	 */
	public function publicUrl(string $url = ''): string
	{
		global $settings;
		return SITE_URL . '/public/' . $settings->theme . '/' . $url;
	}

	/**
	 *
	 * @param string $path
	 * @param bool $theme Theme folder.
	 * @return string
	 */
	public function publicPath(string $path = '', bool $theme = true): string
	{
		global $settings;
		$themePath = ($theme) ? $settings->theme . '/' : '';

		return ROOT_PATH . '/public/' . $themePath . $path;
	}

	/**
	 * It's include error page that is specified by given code page and exit
	 *
	 * @param $code
	 */
	public function abort($code): void
	{
		if (file_exists(ROOT_PATH . '/public/errors/' . $code . '.php')) {
			http_response_code($code);
			include "public/errors/" . $code . ".php";
			exit();
		}
		echo 'Lütfen ' . $code . ' http kodu için public/error/ klasörü altına hata sayfasını ekleyiniz.';
		exit;
	}

	public function uploadUrl(string $url)
	{
		return SITE_URL . '/uploads/' . $url;
	}

    public function url($url = ''): string
    {
        global $settings;
        if ((int)$settings->link_sort_lang === 2) {
            return SITE_URL . '/' . $url;
        }
        return SITE_URL . '/' . $_SESSION['lang'] . '/' . $url;
    }

	public function backend($url = ''): string
	{
		return SITE_URL . '/' . $_SESSION['lang'] . '/admin/' . $url;
	}

	public function userUrl(string $username): string
	{
		return SITE_URL . '/user/' . $username;
	}

	public function getTranslation($englishVersion): string
	{
		try {
			$langJson = json_decode(file_get_contents($this->publicPath('languages/' . $_SESSION['lang'] . '.json')), false, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {

		}
		return $langJson->{$englishVersion} ?? $englishVersion;
	}

	public function path($filePath = ''): string
	{
		return ROOT_PATH . '/' . $filePath;
	}

	public function setLanguage(string $language): string
	{
		unset($_SESSION['lang']);
		$_SESSION['lang'] = $language;
		$uri = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
		$uri[0] = $language;
		return SITE_URL . '/' . implode('/', $uri);
	}

	public function isUrlActive(string $url): bool
	{
		$uri = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
		return isset($uri[1]) && $url === $uri[1];

	}

}

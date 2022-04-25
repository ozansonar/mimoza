<?php

if (!function_exists('__')) {

	function __(string $key): ?string
	{
		global $system;
		try {
			$langJson = json_decode(file_get_contents($system->publicPath('languages/' . $_SESSION['lang'] . '.json')), false, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {

		}
		return $langJson->{$key} ?? $key;
	}
}

if (!function_exists('appLanguage')) {

	function appLanguage(): string
	{
		if (isset($_SESSION['lang'])){
			return $_SESSION['lang'];
		}
		$_SESSION['lang'] = DEFAULT_LANGUAGE;
		return $_SESSION['lang'];
	}
}

if (!function_exists('user')) {

	function user(string $property): string
	{
		global $loggedUser;
		return $loggedUser->{$property};
	}
}
<?php

namespace Includes\System;

class View
{

	/**
	 * It's return layout
	 *
	 * @param string $view
	 * @param array|null $data
	 * @param string $layout
	 * @return mixed
	 */
	public static function layout(string $view, array $data = null, string $layout = 'main')
	{
		global $metaTag;
		global $functions;
		global $settings;
		global $siteManager;
		global $system;
		global $projectLanguages;
		global $session;
		global $fileTypePath;
		global $loggedUser;
		global $socialMedia;
		global $message;

		$data['view'] = self::view($view);
		$data = (object)$data;
		return require ROOT_PATH . "/app/View/layouts/{$layout}.php";
	}

	/**
	 * It's return view
	 *
	 * @param string $view view name
	 * @return string view path
	 */
	public static function view(string $view): string
	{
		global $settings;
		return ROOT_PATH . '/app/View/' . $settings->theme . '/' . $view . '.php';
	}
}
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
	public static function layout(string $view, array $data = null, string $layout = 'main'): mixed
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
		global $menuItems;

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


	/**
	 * It's return layout
	 *
	 * @param string $view
	 * @param array|null $data
	 * @param string $layout
	 * @return mixed
	 */
	public static function backend(string $view, array $data = null, string $layout = 'main'): mixed
	{
		if (!isset($_SESSION['theme'])) {
			$_SESSION['theme'] = 'light-layout';
		}

		global $metaTag;
		global $functions;
		global $settings;
		global $siteManager;
		global $system;
		global $session;
		global $fileTypePath;
		global $loggedUser;
		global $socialMedia;
		global $message;
		global $betaMenu;
		global $adminSystem;
		global $listPermissionKey;
		global $editPermissionKey;
		global $deletePermissionKey;
		global $projectLanguages;
		global $form;
		global $admin_text;

		$data['theme'] = $_SESSION['theme'];
		$data['view'] = self::backendView($view);
		$data = (object)$data;
		return require ROOT_PATH . "/beta/View/layouts/{$layout}.php";
	}

	/**
	 * It's return view
	 *
	 * @param string $view view name
	 * @return string view path
	 */
	public static function backendView(string $view): string
	{
		return ROOT_PATH . '/beta/View/proje/' . $view . '.php';
	}

}
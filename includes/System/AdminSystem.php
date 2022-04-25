<?php

namespace Includes\System;


class AdminSystem
{

	/**
	 * @param $controllerName
	 * @return string
	 */
	public function adminController($controllerName): string
	{
		$controllerName = strtolower($controllerName);
		return ROOT_PATH . '/beta/Controller/' . $controllerName . '.php';
	}

	/**
	 * @param $viewName
	 * @return string
	 */
	public function adminView($viewName): string
	{
		return ROOT_PATH . '/beta/View/proje/' . $viewName . '.php';
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public function adminPublicUrl(string $url = ''): string
	{
		return SITE_URL . '/vendor/almasaeed2010/adminlte/' . $url;
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public function adminUrl(string $url = ''): string
	{
		return SITE_URL . '/beta/' . $url;
	}

}
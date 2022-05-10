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
		return ROOT_PATH . '/admin/Controller/' . $controllerName . '.php';
	}

	/**
	 * @param $viewName
	 * @return string
	 */
	public function adminView($viewName): string
	{
		return ROOT_PATH . '/admin/View/proje/' . $viewName . '.php';
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public function adminPublicUrl(string $url = ''): string
	{
		return SITE_URL . '/vendor/ozansonar/mimoza-panel-file/' . $url;
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public function adminUrl(string $url = ''): string
	{
		return SITE_URL . '/admin/' . $url;
	}

}
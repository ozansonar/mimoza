<?php

namespace Includes\System;

use Exception;
use JsonException;

class Cache
{
	private Log $log;

	public function __construct($log)
	{
		$this->log = $log;

	}

	/**
	 * This function handle to creating cache file. It's return boolean according to result
	 *
	 * @param string $filename
	 * @param object|array $data
	 * @param string $language
	 * @return bool
	 */
	public function put(string $filename, $data, string $language = DEFAULT_LANGUAGE): bool
	{
		try {
			$cachedFile = fopen(CACHE_FILE_PATH . $filename . '_' . $language . '.json', 'wb');
			fwrite($cachedFile, json_encode($data, JSON_THROW_ON_ERROR));
			fclose($cachedFile);
			return true;
		} catch (JsonException $e) {
			$this->log->logThis($this->log->logTypes['CACHING_ERROR'], $e->getMessage());
			return false;
		}
	}

	/**
	 * This function is for deleting cache file. Returns true/false according to the result
	 *
	 * @param string $filename
	 * @param string $language
	 * @return bool
	 */
	public function flush(string $filename, string $language = DEFAULT_LANGUAGE): bool
	{
		try {
			if (file_exists(CACHE_FILE_PATH . $filename . '_' . $language . '.json')) {
				if (unlink(CACHE_FILE_PATH . $filename . '_' . $language . '.json')) {
					$this->log->logThis($this->log->logTypes['CACHING_FILE_DELETE_SUCCESS'], CACHE_FILE_PATH . $filename . '_' . $language . '.json');
					return true;
				}
				$this->log->logThis($this->log->logTypes['CACHING_FILE_DELETE_ERROR'], CACHE_FILE_PATH . $filename . '_' . $language . '.json');
				return false;
			}

		} catch (Exception $e) {
			$this->log->logThis($this->log->logTypes['CACHING_FILE_DELETE_ERROR'], CACHE_FILE_PATH . $filename . '_' . $language . '.json' . ' | => ' . $e->getMessage());
			return false;
		}
		return false;
	}

	public function get(string $filename, ?object $data = NULL, string $language = DEFAULT_LANGUAGE): ?object
	{
		if ($this->check($filename, $data, $language)) {
			try {
				return (object)json_decode(file_get_contents(CACHE_FILE_PATH . $filename . '_' . $language . '.json'), false, 512, JSON_THROW_ON_ERROR);
			} catch (JsonException $e) {
				$this->log->logThis($this->log->logTypes['CACHING_ERROR'], $e->getMessage());
				return NULL;
			}
		}

		if ($data !== NULL) {
			$this->put($filename, $data, $language);
			return $this->get($filename, $data, $language);
		}

		return NULL;
	}

	/**
	 * It's check cache file.
	 *
	 * @param string $filename
	 * @param object|null $data
	 * @param string $language
	 * @return bool
	 */
	private function check(string $filename, ?object $data = NULL, string $language = DEFAULT_LANGUAGE): bool
	{
		// file exist
		if (file_exists(CACHE_FILE_PATH . $filename . '_' . $language . '.json')) {
			return true;
		}

		// data exist but file not
		if ($data !== NULL && !file_exists(CACHE_FILE_PATH . $filename . '_' . $language . '.json')) {
			$this->put($filename, $data, $language);
			return true;
		}

		return false;
	}

}
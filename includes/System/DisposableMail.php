<?php

namespace Includes\System;


class DisposableMail
{
	private Log $log;
	private string $sourceUrl;
	private string $disposableProvidersFilePath;
	private string $fileSeparator;

	public function __construct(Log $log, array $config = NULL)
	{
		$this->log = $log;

		if (!isset($config['file_path'])) {
			$config['file_path'] = 'public/cache/disposable-mail';
		}

		$this->fileSeparator = $this->getFileSeparator($config['file_path']);
		$this->disposableProvidersFilePath = ROOT_PATH . $this->fileSeparator . $config['file_path'] . '.txt';

		// setting up source url
		if (isset($config['source_url'])) {
			$this->sourceUrl = $config['source_url'];
		} else {
			$this->sourceUrl = 'https://raw.githubusercontent.com/7c/fakefilter/main/txt/data.txt';
		}
	}

	private function getDisposableMailProviders(): array
	{
		$this->reCreateProviderFile();
		return explode(',', file_get_contents($this->disposableProvidersFilePath));
	}

	private function getCleanedProvidersData(): array
	{
		// delete old file
		if (file_exists(ROOT_PATH . $this->fileSeparator . 'public/temp/disposable-mail.txt')) {
			unlink(ROOT_PATH . $this->fileSeparator . 'public/temp/disposable-mail.txt');
		}

		// Create new temp file
		$dirtyDisposableProviders = file_get_contents($this->sourceUrl);
		$handle = fopen(ROOT_PATH . $this->fileSeparator . 'public/temp/disposable-mail.txt', 'wb');
		fwrite($handle, $dirtyDisposableProviders);
		fclose($handle);

		$temp = fopen(ROOT_PATH. $this->fileSeparator . 'public/temp/disposable-mail.txt', 'rb');
		$cleanedDisposableProviders = [];
		if ($temp) {
			while (($line = fgets($temp)) !== false) {
				if (!str_starts_with($line, '#')) {
					$cleanedDisposableProviders[] = trim($line);
				}
			}
			fclose($temp);
		} else {
			$this->log->logThis($this->log->logTypes['DISPOSABLE_MAIL_READ_ERROR']);
		}
		return array_values(array_filter(array_unique($cleanedDisposableProviders)));
	}

	private function getFileSeparator(string $filePath = NULL): string
	{
		if (str_ends_with(ROOT_PATH, '/') || str_starts_with($filePath, '/')) {
			$fileSeparator = '';
		} else {
			$fileSeparator = '/';
		}

		return $fileSeparator;
	}

	private function reCreateProviderFile(): void
	{
		if (time() >= filemtime(ROOT_PATH . '/public/cache/disposable-mail.txt') + 8600) {
			if (file_exists($this->disposableProvidersFilePath)) {
				unlink($this->disposableProvidersFilePath);
			}

			$newFile = fopen($this->disposableProvidersFilePath, 'wb+');
			fwrite($newFile, implode(',', $this->getCleanedProvidersData()));
			fclose($newFile);
		}
	}

	public function isThisMailDisposable(string $email): bool
	{
		[$user, $provider] = explode('@', $email);
		if (in_array($provider, $this->getDisposableMailProviders(), true)) {
			return true;
		}
		return false;
	}

}
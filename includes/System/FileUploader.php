<?php
/**
 * Dosya yükleme sınıfı.
 * Bu dosya ile yüklenen resimler istenirse sıkıştırlabilir ve uploads/ altına kayıt yapar
 *
 */

namespace Includes\System;

include ROOT_PATH . "/includes/System/ImgCompressor.php";

use Verot\Upload\Upload;

class FileUploader
{

	//dosyaların yükleneceği bilgilerin bulunduğu array
	/**
	 * @var array
	 */
	public array $upload_path_info = array();
	//dosyanın yükleneceği klasör $upload_path_info arrayında keyi olmalı yoksa default keyi altındaki full_path dizinine yükler
	/**
	 * @var string
	 */
	public string $upload_folder = "default";
	//global olarak gelen $_FILES da bulunan resimimizi barındıran key
	/**
	 * @var string
	 */
	public string $global_file_name = "img";
	//resmi resize edecek miyiz
	/**
	 * @var bool
	 */
	public bool $resize = false;
	//resise width
	/**
	 * @var int
	 */
	public int $width = 1366;
	//resize heiht
	/**
	 * @var int
	 */
	public int $height = 768;
	//yüklenecek max dosya boyutu 1:1mb,2:mb,5:5mb,10:10mb olarak düşünün
	/**
	 * @var int
	 */
	public int $max_file_size = 2;
	//yüklencek file türü
	/**
	 * @var string
	 */
	public string $upload_type = "img";
	//resme sıkıştırma uygulansın mı
	/**
	 * @var bool
	 */
	public bool $compressor = false;
	//sıkıtırma yapılabilecek uzantılar
	/**
	 * @var string[]
	 */
	public array $compressor_extension = array("png", "jpg", "jpeg");
	//sıkıştırma leveli
	/**
	 * @var int
	 */
	public int $compressor_level = 4;

	//galleri resmi upload ederken kullanırız galeriye ait klasör oluşturur onun içine resimleri atar
	/**
	 * @var int
	 */
	public int $gallery_id = 0;


	/**
	 * @param $file_type_path
	 */
	public function __construct($file_type_path)
	{
		$this->upload_path_info = $file_type_path;
	}

	/**
	 * @return array|false
	 */
	public function file_upload()
	{
		if (isset($_FILES[$this->global_file_name])) {
			$url = $this->upload_path_info["default"]["full_path"];
			if (array_key_exists($this->upload_folder, $this->upload_path_info)) {
				$url = $this->upload_path_info[$this->upload_folder]["full_path"] . ($this->gallery_id > 0 ? $this->gallery_id . "/" : null);
			}
			$result = array();
			$image = $_FILES[$this->global_file_name];
            $userId = $_SESSION["user_id"]."-" ?? null;
            $image_name = $userId.uniqid('', true) . "-" . time();
			$handle = new Upload($image, "tr_TR");
			if ($handle->uploaded) {
				// maksimum yüklenecek dosya boyutu belirlenir. 1024 = 1KB
				$handle->file_max_size = 1024 * 1024 * $this->max_file_size; // 10 mb yapar
				$handle->file_new_name_body = $image_name;

				$handle->image_ratio = false;
				if ($this->resize) {
					$handle->image_resize = true;
					//$handle->image_ratio_crop = false;
					$handle->image_ratio_crop = true;
					$handle->image_x = $this->width;
					$handle->image_y = $this->height;
					//arka alanı beyaz yapar
					//$handle->image_ratio_fill = true;
				}
				if ($this->upload_type === "img") {
					$handle->allowed = array("image/jpeg", "image/png", "image/jpg");
				} elseif ($this->upload_type === "pdf") {
					$handle->allowed = array("application/pdf");
				} elseif ($this->upload_type === "pdf_and_img") {
					$handle->allowed = array("application/pdf", "image/jpeg", "image/png", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
				} elseif ($this->upload_type === "word") {
					$handle->allowed = array("application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
				} elseif ($this->upload_type === "pdf_word_image_excel") {
					$handle->allowed = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/excel", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/png", "image/jpg");
				} elseif ($this->upload_type === "mp3") {
					$handle->allowed = array("audio/*");
				} else {
					$handle->allowed = array("image/jpeg", "image/png", "image/jpg");
				}

				$handle->Process($url);
				$img_path = $handle->file_dst_name;
				if ($handle->processed) {
					$img_name_explode = explode(".", $img_path);
					$extension = end($img_name_explode);

					if ($this->compressor && in_array($extension, $this->compressor_extension, true)) {
						if (!file_exists($url . "compressed") && !mkdir($concurrentDirectory = $url . "compressed", 0777) && !is_dir($concurrentDirectory)) {
							throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
						}
						// setting
						$setting_img = array(
							'directory' => $url . "compressed", // directory file compressed output
							'file_type' => array( // file format allowed
								'image/jpeg',
								'image/png',
								'image/gif'
							)
						);
						// create object
						$ImgCompressor = new ImgCompressor($setting_img);
						$ImgCompressor->run($url . $img_path, $extension, $this->compressor_level, $img_path);
					}
					//resim yüklenmişse çalışır
					$result["result"] = 1;
					$result["img_name"] = $img_path;

				} else {
					//resim yüklenememişse çalışır
					$result["result"] = 2;
					$result["result_message"] = $handle->error;
				}
			} else {
				//resim seçilmemişse çalışır
				$result["result"] = 3;
				$result["result_message"] = $handle->error;
			}
			return $result;
		}
		return false;
	}
}
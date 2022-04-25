<?php

namespace Includes\System;

use JetBrains\PhpStorm\Pure;

class AdminForm
{

	/**
	 * An instance of the Functions class
	 *
	 * @var Functions
	 */
	public Functions $functions;

	/**
	 * Language code
	 *
	 * @var string
	 */
	public string $lang;

	/**
	 * If it's not present, all inputs are prefixed with lang codes
	 *
	 * @var int
	 */
	public int $formNameWithoutLangCode;

	/**
	 *
	 */
	#[Pure] public function __construct()
	{
		$this->functions = new Functions();
	}

	/**
	 * It's return HTML input according to giving options
	 *
	 * @param string $name
	 * @param array $item
	 * @param array|null $data
	 * @return string
	 */
	public function input(string $name, array $item = [], array $data = null): string
	{
		$name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang : $name;
		$type = $item["type"] ?? "text";
		$item_hidden = $item["item_hidden"] ?? null;
		$label = $item["label"] ?? null;
		$input_group = isset($item["input_group"]) ? 1 : 0;
		$id = "id-" . $this->functions->permalink($name_lang);
		$group_icon = $item["group_icon"] ?? null;
		$required = isset($item["required"]) && (int)$item["required"] === 1 ? "required validate[required]" : null;
		$disabled = isset($item["disabled"]) && (int)$item["disabled"] === 1 ? "disabled" : null;
		$class = $item["class"] ?? null;
		if (!empty($this->lang)) {
			$value = !empty($data) && isset($data[$this->lang][$name]) ? $data[$this->lang][$name] : null;
		} else {
			$value = !empty($data) && isset($data[$name]) ? $data[$name] : null;
		}


		if (isset($item["order"]) && empty($value)) {
			$value = 1;
		}
		$max_size = 5000;
		if (isset($item["max_size"])) {
			$max_size = (int)$item["max_size"];
		}

		$html = '<div class="' . ($input_group == 1 ? "input-group" : "form-group") . ' mb-1" id="div_' . $name . '" ' . ($item_hidden == 1 ? $item["show_data"] == $item["show_value"] ? null : "style='display:none;'" : null) . '>';
		$html .= '<div class="d-block w-100"><label for="' . $id . '">' . $label . '</label></div>';
		$html .= '<input type="' . $type . '" class="form-control ' . $class . ' ' . $required . '" name="' . $name_lang . '" id="' . $id . '" placeholder="' . $label . '" value="' . $value . '" ' . $disabled . '>';
		if ($input_group == 1) {
			$html .= '<div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="' . $group_icon . '"></span>
                                    </div>
                                </div>';
		}
		if (isset($item["description"])) {
			$html .= '<span class="form-text text-muted">' . $item["description"] . '</span>';
		}
		$html .= '</div>';
		if (isset($item["order"])) {
			$html .= '
                            <script>
                                $(document).ready(function(){
                                   $("#' . $id . '").TouchSpin({ 
                                    min: 1, 
                                    buttondown_class: "btn btn-primary",
                                    buttonup_class: "btn btn-primary",
                                    buttondown_txt: feather.icons["minus"].toSvg(),
                                    buttonup_txt: feather.icons["plus"].toSvg()
                                    });
                                });
                            </script>
                        ';
		}

		return $html;
	}

    public function order(string $name, array $item = [], array $data = null): string
    {
        $name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang : $name;
        $id = "id-" . $this->functions->permalink($name_lang);
        if (!empty($this->lang)) {
            $value = !empty($data) && isset($data[$this->lang][$name]) ? $data[$this->lang][$name] : 1;
        } else {
            $value = !empty($data) && isset($data[$name]) ? $data[$name] : 1;
        }
        $label = $item["label"] ?? null;
        $html = null;
        $html .='<div class="col-12 mb-1">
                <div class="col-12"><label for="">'.$label.'</label></div>
                    <div class="input-group w-100 p-0"> 
                        <input type="number" class="touchspin form-control" id="'.$id.'" name="'.$name_lang.'" value="'.$value.'" />
                    </div> 
                </div>';
        $html .= '
            <script>
                $(document).ready(function(){
                   $("#' . $id . '").TouchSpin({ 
                    min: 1, 
                    buttondown_class: "btn btn-primary",
                    buttonup_class: "btn btn-primary",
                    buttondown_txt: feather.icons["minus"].toSvg(),
                    buttonup_txt: feather.icons["plus"].toSvg()
                    });
                });
            </script>
        ';
        return $html;
    }

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
    public function select($name, $item = array(), $data = null): string
	{
        $brackets = isset($item['multiple']) ? '[]' : '';
        $name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang . $brackets  : $name;
        $label = isset($item["label"]) ? $item["label"] : null;
        $multiple = isset($item["multiple"]) ? 'multiple="multiple"' : null;
        $required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
        $value = !empty($data) && isset($data[$this->lang][$name]) ? $data[$this->lang][$name] : "-1";
        $html = '<div class="form-group">
                       <label for="id_' . $name . '">' . $label . '</label>
                       <select class="form-control select2bs4 ' . $required . '" '.$multiple.' name="' . $name_lang . '"  id="id_' . $name_lang . '" style="width: 100%;">
                           <option value="">Seçiniz</option>';
        $each_item = null;
        if (isset($item["multiple_lang_select"])) {
            if (isset($item["select_item"][$this->lang])) {
                foreach ($item["select_item"][$this->lang] as $item_key => $item_value) {
                    $each_item .= '<option value="' . $item_key . '" ' . ($value == $item_key ? "selected" : null) . '>' . $item_value . '</option>';
                }
            }
        } else {
            foreach ($item["select_item"] as $item_key => $item_value) {
                $each_item .= '<option value="' . $item_key . '" ' . ($value == $item_key ? "selected" : null) . '>' . $item_value . '</option>';
            }
        }

        $html .= $each_item . '</select>
           </div>';
        $html .= '
               <script>
                   $(document).ready(function(){
                       //Initialize Select2 Elements
                       $(".select2bs4").select2({
                           theme: "bootstrap4"
                       });
                   });
               </script>
           ';
        return $html;
    }

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function button($name, $item = array(), $data = null)
	{
		//$name = $name."_".$this->lang;
		$text = isset($item["text"]) ? $item["text"] : null;
		$type = isset($item["type"]) ? $item["type"] : "submit";
		$btn_type = isset($item["btn_class"]) ? $item["btn_class"] : "btn btn-primary";
		$form_name = isset($item["form_name"]) ? $item["form_name"] : "pageForm";
		$onclick_function = isset($item["onclick_function"]) ? $item["onclick_function"] : null;
		$class = isset($item["class"]) ? $item["class"] : null;
		$icon_type = isset($item["icon"]) ? "<i class='" . $item["icon"] . "'></i>" : null;
		$html = '<button type="' . $type . '" ' . (!empty($onclick_function) ? 'onclick="' . $onclick_function . '"' : null) . ' form="' . $form_name . '" id="id_' . $name . '" value="1" name="' . $name . '" class="' . $btn_type . ' ' . $class . '">' . $icon_type . ' ' . $text . '</button>';
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function file($name, $item = array(), $data = null)
	{
		global $fileTypePath;
		$name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang : $name;
		$label = isset($item["label"]) ? $item["label"] : null;
		$required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
		$file_key = isset($item["file_key"]) ? $item["file_key"] : null;
		$show_image_label_text = isset($item["show_image_label_text"]) ? $item["show_image_label_text"] : "Mevcut Dosya ->";
		$delete_link = isset($item["delete_link"]) ? $item["delete_link"] : null;
		$html = '<div class="form-group">
                        <label for="id_' . $name_lang . '">' . $label . '</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="' . $name_lang . '" class="custom-file-input ' . $required . '" id="id_' . $name_lang . '">
                                <label class="custom-file-label" for="id_img">Seç</label>
                            </div> 
                        </div>';
		if (array_key_exists($file_key, $fileTypePath) && isset($data[$this->lang][$name]) && !empty($data[$this->lang][$name]) && file_exists($fileTypePath[$file_key]["full_path"] . $data[$this->lang][$name])) {

			$html .= '<p class="mt-1">';
			$html .= '<a href="' . $fileTypePath[$file_key]["url"] . $data[$this->lang][$name] . '" data-toggle="lightbox" class="btn btn-info"> Resmi Gör (tıklayınız) <i class="fa fa-images"></i>';
			$html .= '</a>';
			if (!empty($delete_link)) {
				$html .= '<a href="' . $delete_link . '" class="btn btn-danger ml-2">Dosyayı SİL <i class="fa fa-trash"></i></a>';
			}
			$html .= '</p>';


		}
		$html .= '</div>';
		$html .= "<script>
                            $(\".custom-file-input\").on(\"change\", function() {
                                var fileName = $(this).val().split(\"\\/\").pop();
                                $(this).siblings(\".custom-file-label\").addClass(\"selected\").html(fileName);
                            });
                            </script>";
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function textarea($name, $item = array(), $data = null)
	{
		$name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang : $name;
		$item_hidden = isset($item["item_hidden"]) ? $item["item_hidden"] : null;
		$label = isset($item["label"]) ? $item["label"] : null;
		$required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
		$disabled = isset($item["disabled"]) && $item["disabled"] == 1 ? "disabled" : null;
		$class = isset($item["class"]) ? $item["class"] : null;
		$value = !empty($data) && isset($data[$this->lang][$name]) ? $data[$this->lang][$name] : null;
		$html = '<div class="form-group" id="div_' . $name_lang . '" ' . ($item_hidden == 1 ? $item["show_data"] == $item["show_value"] ? null : "style='display:none;'" : null) . '>
                        <label for="id_' . $name_lang . '">' . $label . '</label>
                        <textarea class="form-control ' . $class . ' ' . $required . '" id="id_' . $name_lang . '" name="' . $name_lang . '" rows="5" ' . $disabled . '>' . $value . '</textarea>
                    </div>';
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function date_mask($name, $item = array(), $data = null)
	{
		$label = isset($item["label"]) ? $item["label"] : null;
		$value = !empty($data) && isset($data[$name]) ? $this->functions->date_long($data[$name]) : null;
		$value2 = !empty($data) && isset($data[$name]) ? $data[$name] : null;
		$html = '<div class="form-group">
                  <label>' . $label . '</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" name="' . $name . '" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                  </div>
                  <!-- /.input group -->
                </div>
                <script>
                    $(document).ready(function(){
                       //Datemask dd/mm/yyyy
                        $("#id_' . $name . '").inputmask("dd/mm/yyyy", { "placeholder": "dd/mm/yyyy" });
                        $("[data-mask]").inputmask();
                    });
                </script>
                ';
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function date_range($name, $item = array(), $data = null)
	{
		$label = isset($item["label"]) ? $item["label"] : null;
		$value = !empty($data) && isset($data[$name]) ? $data[$name] : null;
		$html = '<div class="form-group">
                        <label>' . $label . '</label>                
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            </div>
                            <input type="text" class="form-control float-right" value="' . $value . '" name="' . $name . '" id="id_' . $name . '">
                        </div>
                        <!-- /.input group -->
                    </div>
                <script>
                    $(document).ready(function(){
                       $("#id_' . $name . '").daterangepicker({
                            locale: {
                              format: "DD-MM-YYYY"
                            }
                       });
                    });
                </script>
                ';
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function input_tags($name, $item = array(), $data = null)
	{
		$name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang : $name;
		$type = isset($item["type"]) ? $item["type"] : "text";
		$item_hidden = isset($item["item_hidden"]) ? $item["item_hidden"] : null;
		$label = isset($item["label"]) ? $item["label"] : null;
		$input_group = isset($item["input_group"]) ? 1 : 0;
		$id = "id-" . $this->functions->permalink($name_lang);
		$group_icon = isset($item["group_icon"]) ? $item["group_icon"] : null;
		$required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
		$class = isset($item["class"]) ? $item["class"] : null;
		$value = !empty($data) && isset($data[$this->lang][$name]) ? $data[$this->lang][$name] : null;

		if (isset($item["order"]) && empty($value)) {
			$value = 1;
		}
		$html = '<div class="' . ($input_group == 1 ? "input-group" : "form-group") . ' mb-3" id="div_' . $name . '" ' . ($item_hidden == 1 ? $item["show_data"] == $item["show_value"] ? null : "style='display:none;'" : null) . '>';
		$html .= '<div class="d-block w-100"><label for="' . $id . '">' . $label . '</label></div>';
		$html .= '<div class="bootstrap-tagsinput w-100">
                    <input type="' . $type . '" class="form-control ' . $class . ' ' . $required . '" name="' . $name_lang . '" id="' . $id . '" placeholder="Ekle" value="' . $value . '" data-role="tagsinput" placeholder="Ekle">
                </div>';
		if ($input_group == 1) {
			$html .= '<div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="' . $group_icon . '"></span>
                                    </div>
                                </div>';
		}
		$html .= '</div>';
		$html .= '
                <script>
                    $(document).ready(function(){
                       $(".tags-input").tagsinput();
                    });
                </script>
            ';

		return $html;
	}

	/**
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function checkbox($items = array(), $data = null)
	{
		$checkbox = "";
		foreach ($items["option"] as $item) {
			$name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $item["name"] . "_" . $this->lang : $item["name"];
			$id = "id_" . $name_lang;
			$check_value = $item["value"];
			$value = !empty($data) && isset($data[$this->lang][$item["name"]]) ? $data[$this->lang][$item["name"]] : null;
			$checkbox .= '<div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" name="' . $name_lang . '" id="' . $id . '" value="' . $check_value . '" ' . ($value === $check_value ? "checked" : null) . '>
                        <label for="' . $id . '" class="form-check-label">
                          ' . $item["label"] . '
                        </label>
                      </div>';
		}
		return '<div class="form-group clearfix">' . $checkbox . '</div>';
	}

	/**
	 * @param string $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function date(string $name, array $item = [], $data = null): string
	{
		$label = $item["label"] ?? null;
		$value = !empty($data) && isset($data[$this->lang][$name]) && !empty($data[$this->lang][$name]) ? $data[$this->lang][$name] : null;
		$name_lang = !empty($this->lang) && empty($this->formNameWithoutLangCode) ? $name . "_" . $this->lang : $name;
		$required = isset($item["required"]) && (int)$item["required"] === 1 ? "required validate[required]" : null;
		$id = "id_" . $name_lang;
		$html = '<div class="form-group">
                  <label>' . $label . '</label>
                    <div class="input-group date" id="' . $id . '" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input ' . $required . '" name="' . $name_lang . '" value="' . $value . '" data-target="#' . $id . '"/>
                        <div class="input-group-append" data-target="#' . $id . '" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>';
		$html .= '
                <script>
                    $(document).ready(function(){
                       //Date picker
                        $("#' . $id . '").datetimepicker({
                            format: "DD-MM-YYYY",
                            locale: "' . $_SESSION["lang"] . '",
                        });
                    });
                </script>
            ';
		return $html;
	}
}
<?php

namespace Includes\System;

/**
 *
 */
class Form
{

	// $database;
	/**
	 * @var Functions
	 */
	public $functions;

	//public $site_manager;

	/**
	 *
	 */
	public function __construct()
	{
		//$this->database = new DB();
		$this->functions = new Functions();
		//$this->site_manager = new siteManager();
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function input($name, $item = array(), $data = null)
	{
		$item_hidden = isset($item["item_hidden"]) ? $item["item_hidden"] : null;
		$label = isset($item["label"]) ? $item["label"] : null;
        $label_description = isset($item["label_description"]) ? $item["label_description"] : null;
		$class = isset($item["class"]) ? $item["class"] : null;
		$addon = isset($item["addon"]) ? $item["addon"] : null;
		$type = isset($item["type"]) ? $item["type"] : "text";
		$required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
		$required_label = isset($item["required"]) && $item["required"] == 1 ? "required" : null;
		$value = !empty($data) && isset($data[$name]) ? $data[$name] : null;
		$html = '<div class="mb-3" id="div_' . $name . '" ' . ($item_hidden == 1 ? $item["show_data"] == $item["show_value"] ? null : "style='display:none;'" : null) . '>
                      <label for="id_' . $name . '" id="div_' . $name . '" class="form-label ' . $required_label . '">' . $label . $label_description .'</label>
                      <input type="'.$type.'" class="form-control ' . $class . ' ' . $required . '" name="' . $name . '" id="id_' . $name . '" placeholder="' . $label . '" value="' . $value . '" '.$addon.'>
                </div>';
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function select($name, $item = array(), $data = null)
	{
		$label = isset($item["label"]) ? $item["label"] : null;
		$required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
		$required_label = isset($item["required"]) && $item["required"] == 1 ? "required" : null;
		$value = !empty($data) && isset($data[$name]) ? $data[$name] : null;
		$html = '<div class="form-group mb-3">
                        <label for="id_' . $name . '" class="' . $required_label . '">' . $label . '</label>
                        <select class="form-control select2bs4 ' . $required . '" name="' . $name . '"  id="id_' . $name . '" style="width: 100%;">
                            <option value="">Seçiniz</option>';
		$each_item = null;
		foreach ($item["select_item"] as $item_key => $item_value) {
			$each_item .= '<option value="' . $item_key . '" ' . ($value == $item_key ? "selected" : null) . '>' . $item_value . '</option>';
		}
		$html .= $each_item . '</select>
            </div>';
		return $html;
	}

	/**
	 * @param $name
	 * @param array $item
	 * @param null $data
	 * @return string
	 */
	public function date($name, $item = array(), $data = null)
	{

		$label = isset($item["label"]) ? $item["label"] : null;
		$value = !empty($data) && isset($data[$name]) ? $this->functions->date_long($data[$name]) : null;
		$value2 = !empty($data) && isset($data[$name]) ? $data[$name] : null;
		$html = '<div class="form-group input-date">
                    <label for="dtp_input2" class="col-md-2 control-label">' . $label . '</label>
                    <div class="input-group date div_' . $name . ' col-md-5" data-date="" data-date-format="dd MM yyyy" data-link-fi
                    eld="dtp_input_' . $name . '" data-link-format="yyyy-mm-dd">
                        <input class="form-control" size="16" type="text" value="' . $value . '" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input_' . $name . '" value="' . $value2 . '" name="' . $name . '" /><br/>
                </div>
                <script>
                    $(document).ready(function(){
                       $(".div_' . $name . '").datetimepicker({
                            language:  "tr",
                            weekStart: 1,
                            todayBtn:  1,
                            autoclose: 1,
                            todayHighlight: 1,
                            startView: 2,
                            minView: 2,
                            forceParse: 0
                        });
                    });
                </script>
                ';
		return $html;
	}

    public function textarea($name,$item = array(),$data = null){
        $id = "id_".$name;
        $label = isset($item["label"]) ? $item["label"] : null;
        $class = isset($item["class"]) ? $item["class"] : null;
        $required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]" : null;
        $required_label = isset($item["required"]) && $item["required"] == 1 ? "required" : null;
        $value = !empty($data) && isset($data[$name]) ? $data[$name] : null;
        $html = '<div class="mb-3">
          <label for="'.$id.'" class="form-label '.$required_label.'">'.$label.'</label>
          <textarea class="form-control '.$class.' '.$required.'" id="'.$id.'" name="'.$name.'" rows="5" placeholder="'.$label.'">'.$value.'</textarea>
        </div>';

        return $html;
    }

    public function button($name,$item = array()){
        $text = isset($item["text"]) ? $item["text"]:"Kaydet";
        $type = isset($item["type"]) ? $item["type"]:"submit";
        $class = isset($item["class"]) ? $item["class"]:null;
        $value = isset($item["value"]) ? $item["value"]:0;
        $btn_class = isset($item["btn_class"]) ? $item["btn_class"]:"btn btn-success";
        $id = "id_".$name;
        $html = '<button type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'" class="'.$btn_class.' '.$class.'">'.$text.'</button>';

        return $html;
    }

    public function file($name,$item = array(),$data = null){
        global $fileTypePath;
        $label = isset($item["label"]) ? $item["label"]:null;
        $required = isset($item["required"]) && $item["required"] == 1 ? "required validate[required]":null;
        $html = '<div class="mb-3">
                      <label for="id_'.$name.'" class="form-label">'.$label.'</label>
                      <input class="form-control" type="file" name="'.$name.'" id="id_'.$name.'">
                    </div>';
        if(isset($data["img"]) && !empty($data["img"]) && file_exists($fileTypePath["user_image"]["full_path"].$data["img"])){

            $html .= '<p class="mt-1">'.$label.' ->';
            $html .= '<a href="'.$fileTypePath["user_image"]["url"].$data["img"].'" data-fancybox="gallery"> Resmi Gör (tıklayınız)';
            $html .= '</a>';
            $html .= '</p>';

        }
        $html .= '</div>';
        return $html;
    }
}
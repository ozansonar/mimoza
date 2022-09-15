<?php

use Mrt\MimozaCore\View;

$log->logThis($log->logTypes["INDEX"]);
//bu sayfadakullanılan özel css'ler
$customCss = []; 
$slider_data_query = $db::$db->prepare("SELECT * FROM slider WHERE lang=:lang AND img != '' AND status=1 AND deleted=0 ORDER BY show_order ASC");
$slider_data_query->bindParam(':lang',$_SESSION['lang'],PDO::PARAM_STR);
$slider_data_query->execute();
$slider_data_count = $slider_data_query->rowCount();
$slider_data = $slider_data_query->fetchAll(PDO::FETCH_OBJ);
$slider_array = [];
$slider_count = 0;
foreach ($slider_data as $slider_row){
    if(empty($slider_row->title)){
        continue;
    }
    if(empty($slider_row->img) || !file_exists($constants::fileTypePath["slider"]["compressed"].$slider_row->img)){
        continue;
    }
    $link = $system->url($settings->{"slider_prefix_".$_SESSION["lang"]}."/".$slider_row->link."-".$slider_row->id);
    if($slider_row->site_disi_link == 1){
        $link = $slider_row->back_link;
    }
    $img = $constants::fileTypePath["slider"]["url_compressed"].$slider_row->img;
    $slider_array[$slider_row->id]["count"] = $slider_count;
    $slider_array[$slider_row->id]["img"] = $img;
    $slider_array[$slider_row->id]["active"] = $slider_count == 0 ? "active":null;
    $slider_array[$slider_row->id]["link"] = $link;
    $slider_array[$slider_row->id]["title"] = $slider_row->title;
    $slider_array[$slider_row->id]["abstract"] = $slider_row->abstract;
    $slider_array[$slider_row->id]["count"] = $slider_count;
    $slider_array[$slider_row->id]["target"] = $slider_row->yeni_sekme == 1 ? "_blank":"_self";
    $slider_count++;
}

//anasayfada listelenecek içerkler
$content_query = $db::$db->prepare("SELECT c.*,cc.title as c_title,cc.link as c_link,cc.id as c_id FROM content c INNER JOIN content_categories cc ON cc.id=c.cat_id WHERE c.index_show=1 AND c.lang=:lang AND c.status=1 AND c.deleted=0 AND cc.status=1 AND cc.deleted=0");
$content_query->bindParam(":lang",$_SESSION["lang"],PDO::PARAM_STR);
$content_query->execute();
$content_count = $content_query->rowCount();
$content_data = $content_query->fetchAll(PDO::FETCH_OBJ);
$content_array = [];

foreach ($content_data as $content_row){

    if(empty($content_row->img) || !file_exists($constants::fileTypePath["content"]["compressed"].$content_row->img)){
        continue;
    }
    //$created_at = Carbon::createFromFormat('Y-m-d H:i:s',$content_row->created_at);
    $content_array[$content_row->id]["id"] = $content_row->id;
	$content_array[$content_row->id]["title"] = $functions->shorten($content_row->title,45);
    $content_array[$content_row->id]["abstract"] = $functions->shorten($functions->cleaner($content_row->abstract),100);
    $content_array[$content_row->id]["img"] =  $constants::fileTypePath["content"]["url_compressed"].$content_row->img;
    $content_array[$content_row->id]["link"] = $system->url($settings->{"content_prefix_".$_SESSION["lang"]}."/".$content_row->c_link."-".$content_row->c_id."/".$content_row->link."-".$content_row->id);
    $content_array[$content_row->id]["date"] = $content_row->created_at;
    //$content_array[$content_row->id]["date"] = $created_at->toAtomString();
    $content_array[$content_row->id]["keywords"] = !empty($content_row->keywords) ? $content_row->keywords:$content_row->title;
}

//bu sayfadakullanılan özel css'ler
$customCss = [];

//bu sayfadakullanılan özel js'ler
$customJs = [];
//$customJs[] = "vendor/lazysizes/lazysizes.min.js";

View::layout('index',[
	'title' => 'Ana Sayfa',
	'slider' => $slider_array,
	'content' => $content_array,
]);
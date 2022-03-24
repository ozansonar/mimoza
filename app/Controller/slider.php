<?php

use Includes\System\View;

$log->logThis($log->logTypes["SLIDER"],$system->route(1));

//link ve id gelecek
$content_link = explode("-",$system->route(1));
$id = end($content_link);
$normal_link = array_pop($content_link);
$normal_link = implode("-",$content_link);
$link = $functions->site_url_lang($system->route(0)."/".$normal_link."-".$id);
$sql = $db::$db->prepare("SELECT * FROM slider WHERE lang=:lang AND link=:link AND id=:id AND status=1 AND deleted=0 LIMIT 0,1");
$sql->bindParam(":lang",$_SESSION['lang']);
$sql->bindParam(":link",$normal_link);
$sql->bindParam(":id",$id);
$sql->execute();
$pageData = $sql->fetch(PDO::FETCH_OBJ);
$p_count = $sql->rowCount();

$created_at = new DateTime($pageData->created_at);

//içerik
if($p_count !== 1){
    $functions->redirect($functions->site_url_lang());
}

//bu sayfadakullanılan özel css'ler
$customCss = [];

//bu sayfadakullanılan özel js'ler
$customJs = [];

$imgLink = false;
if(!empty($pageData->img) && file_exists($fileTypePath["slider"]["full_path"].$pageData->img)){
    $imgLink = $fileTypePath["slider"]["url"].$pageData->img;
}

//meta tag
$metaTag->title = $pageData->title;
 

View::layout('content-detail',[
    'pageData' => $pageData,
    'imgLink' => $imgLink,
]);
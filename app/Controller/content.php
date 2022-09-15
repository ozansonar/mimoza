<?php
use Mrt\MimozaCore\View;

//bu sayfadakullanılan özel css'ler
$customCss = [
    "plugins/fancybox/jquery.fancybox.min.css"
];

//bu sayfadakullanılan özel js'ler
$customJs = [
    "plugins/fancybox/jquery.fancybox.min.js"
];

//hiç bir parametre gelmemiş içerik kategorileri listelenecek
if (!$system->route(1)) {
    $selectQuery = $db::selectQuery("content_categories",array(
        "lang" => $_SESSION["lang"],
        "status" => 1,
        "deleted" => 0,
    ),false,null,5," show_order DESC");

    View::layout('content-categories-list',[
        'list' => $selectQuery
    ]);
}else if(!empty($system->route(1)) && !$system->route(2)){
    //####################### HİÇ BİR PARAMETRE GELMEDİ KATEGORİLER LİSTELENECEK #######################\\

    //kategori sorgulaması
    $content_link = explode("-",$system->route(1));
    $id = end($content_link);
    $normal_link = array_pop($content_link);
    $normal_link = implode("-",$content_link);

    //içerik kategorisi bilgileri
    list($link_count,$link_data) = $siteManager->getCategory($id);
    if($link_count != 1){
        $functions->redirect($system->url());
    }


    $log->logThis($log->logTypes["COTENT_LIST"], $system->route(1));
    //sadece kategori var ve bu kategoriye ait veriler listelenecek
    $metaTag->title = $link_data->title ." - ".$settings->title;

    $cat_id = $link_data->id;

    //göstereceğimiz veriler için sorgymuzu yazıp gönderiyoruz fonksiyon kendisi sayfalanmış şekilde veriyi bize veriyor
    $pagination_and_data = $db::paginate("SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM content WHERE cat_id=:c_id AND status=1 AND deleted=0 ORDER BY show_order DESC LIMIT :baslangic,:limit",array(
        "c_id" => $cat_id,
        "limit" => 10,
    ));

    $pageData = $pagination_and_data["data"];

    View::layout('content',[
        "pageData" => $pageData,
        "category" => $link_data,
        "pagination" => $pagination_and_data,
    ]);

}elseif (!empty($system->route(1)) && !empty($system->route(2))){
    //####################### İÇERİK DETAY KISMI #######################\\
    //kategori sorgulaması
    $content_link = explode("-",$system->route(1));
    $id = end($content_link);
    $normal_link = array_pop($content_link);
    $normal_link = implode("-",$content_link);

    //içerik kategorisi bilgileri
    list($link_count,$link_data) = $siteManager->getCategory($id);
    if($link_count != 1){
        $functions->redirect($system->url());
    }

    $log->logThis($log->logTypes["CONTENT_DETAIL"], $system->route(1));
     //detay kısmı

    //gelen linki - ile parçaladık ama içeriğin başlığı deneme-içerik gibi olabilir o yüzden id yi içinden alıp son elemanı silip diğerlerni bilirleştirelim
    $content_link = explode("-",$system->route(2));
    $id = end($content_link);
    $normal_link = array_pop($content_link);
    $normal_link = implode("-",$content_link);
    $page_query = $db::$db->prepare("SELECT *
    FROM content WHERE id=:id AND link=:link AND status=1 AND deleted=0 ORDER BY created_at DESC LIMIT 0,1");
    $page_query->bindParam(":id",$id,PDO::PARAM_INT);
    $page_query->bindParam(":link",$normal_link,PDO::PARAM_STR);
    $page_query->execute();
    $pageData_count = $page_query->rowCount();
    $pageData = $page_query->fetch(PDO::FETCH_OBJ);

    if($pageData_count != 1){
        $functions->redirect($system->url());
    }

    $metaTag->title = $pageData->title." - ".$settings->title;
    if(!empty($pageData->keywords)){
        $metaTag->keywords = $pageData->keywords;
    }
    if(!empty($pageData->description)){
        $metaTag->description = $pageData->description;
    }


    $created_at = new DateTime($pageData->created_at);
    $imgLink = false;
    if(!empty($pageData->img) && file_exists($constants::fileTypePath["content"]["full_path"].$pageData->img)){
        $imgLink = $constants::fileTypePath["content"]["url"].$pageData->img;
    }

    //sağ alan olsun mu ?
    $right_bar  = true;

    //okunma sayısı yapımı
    $cookie_name = $link_data->link."_".$pageData->link."-".$pageData->id;
    $cookie_value = $pageData->id;
    $c_time = time() + (60 * 60);
    setcookie($cookie_name, $cookie_value,time() + 86400); // 1 saatlik çerez
    if(!isset($_COOKIE[$cookie_name])) {
        //çerez yok okunmayı arttır
        $show_count = $db::$db->prepare("UPDATE content SET show_count=:show WHERE id=:id AND deleted=0");
        $count_plus = $pageData->show_count+1;
        $show_count->bindParam(":show",$count_plus,PDO::PARAM_INT);
        $show_count->bindParam(":id",$id,PDO::PARAM_INT);
        $show_count->execute();
    }

    View::layout('content-detail',[
        "pageData" => $pageData,
        "imgLink" => $imgLink,
        "customCss" => $customCss,
        "customJs" => $customJs,
    ]);
}
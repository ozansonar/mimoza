<?php
use Includes\System\View;

//bu sayfadakullanılan özel css'ler
$customCss = [];

//bu sayfadakullanılan özel js'ler
$customJs = [];

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
        $functions->redirect($functions->site_url());
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

    $page_data = $pagination_and_data["data"];

    View::layout('content',[
        "pageData" => $page_data,
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
        $functions->redirect($functions->site_url());
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
    $page_data_count = $page_query->rowCount();
    $page_data = $page_query->fetch(PDO::FETCH_OBJ);

    if($page_data_count != 1){
        $functions->redirect($functions->site_url());
    }

    $metaTag->title = $page_data->title." - ".$settings->title;
    if(!empty($page_data->keywords)){
        $metaTag->keywords = $page_data->keywords;
    }
    if(!empty($page_data->description)){
        $metaTag->description = $page_data->description;
    }


    $created_at = new DateTime($page_data->created_at);
    $imgLink = false;
    if(!empty($page_data->img) && file_exists($fileTypePath["content"]["full_path"].$page_data->img)){
        $imgLink = $fileTypePath["content"]["url"].$page_data->img;
    }

    //sağ alan olsun mu ?
    $right_bar  = true;

    //okunma sayısı yapımı
    $cookie_name = $link_data->link."_".$page_data->link."-".$page_data->id;
    $cookie_value = $page_data->id;
    $c_time = time() + (60 * 60);
    setcookie($cookie_name, $cookie_value,time() + 86400); // 1 saatlik çerez
    if(!isset($_COOKIE[$cookie_name])) {
        //çerez yok okunmayı arttır
        $show_count = $db::$db->prepare("UPDATE content SET show_count=:show WHERE id=:id AND deleted=0");
        $count_plus = $page_data->show_count+1;
        $show_count->bindParam(":show",$count_plus,PDO::PARAM_INT);
        $show_count->bindParam(":id",$id,PDO::PARAM_INT);
        $show_count->execute();
    }

    View::layout('content-detail',[
        "pageData" => $page_data,
        "imgLink" => $imgLink
    ]);
}
<?php
use OS\MimozaCore\View;

//bu sayfadakullanılan özel css'ler
$customCss = [
    "plugins/fancybox/jquery.fancybox.min.css"
];

//bu sayfadakullanılan özel js'ler
$customJs = [
    "plugins/fancybox/jquery.fancybox.min.js"
];

if (!$system->route(1)) {
    //####################### HİÇ BİR PARAMETRE GELMEDİ KATEGORİLER LİSTELENECEK #######################\\
    $selectQuery = $db::selectQuery("content_categories",array(
        "lang" => $_SESSION["lang"],
        "status" => 1,
        "deleted" => 0,
    ),false,null,5," show_order DESC");

    //diğer diller için ayarlanıyor
    foreach ($projectLanguages as $rowLang){
        $getLangPrefix = $siteManager->getPrefix('content',$rowLang->short_lang);
        if(empty($getLangPrefix)){
            continue;
        }
        $otherLanguageContent[$rowLang->short_lang] = $system->urlWithoutLanguage($rowLang->short_lang.'/'.$siteManager->getPrefix('content',$rowLang->short_lang));
    }

    $breadcrumb = [
        [
            'title' => $functions->textManager('breadcrumb_content_categories_list'),
            'active' => true,
        ]
    ];

    View::layout('content-categories-list',[
        'list' => $selectQuery,
        'breadcrumb' => $breadcrumb
    ]);
}else if(!empty($system->route(1)) && !$system->route(2)){
    //####################### İÇERİK KATEGORİSİNE AİT KATEGORİLER LİSTELENEK #######################\\

    //kategori sorgulaması
    $contentLink = explode("-",$system->route(1));
    $id = $functions->numberOnly(end($contentLink));

    //içerik kategorisi bilgileri
    list($linkCount,$linkData) = $siteManager->getCategory($id);
    if((int)$linkCount !== 1){
        $functions->redirect($system->url());
    }


    $log->logThis($log->logTypes["COTENT_LIST"], $system->route(1));
    //sadece kategori var ve bu kategoriye ait veriler listelenecek
    $metaTag->title = $linkData->title ." - ".$settings->title;
 
    //göstereceğimiz veriler için sorgymuzu yazıp gönderiyoruz fonksiyon kendisi sayfalanmış şekilde veriyi bize veriyor
    $pagination_and_data = $db::paginate("SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM content WHERE cat_id=:c_id AND status=1 AND deleted=0 ORDER BY show_order DESC LIMIT :baslangic,:limit",array(
        "c_id" => $linkData->id,
        "limit" => 10,
    ));

    $breadcrumb = [
        [
            'title' => $functions->textManager('breadcrumb_content_categories_list'),
            'url' => $system->url($siteManager->getPrefix('content')),
        ],
        [
            'title' => $linkData->title,
            'active' => true,
        ]
    ];


    //bu içeriğe ait diğer veriler
    $otherLanguageContent = $siteManager->getOrtherLanguageContentCategories($linkData->lang_id);

    $pageData = $pagination_and_data["data"];

    View::layout('content',[
        'pageData' => $pageData,
        'category' => $linkData,
        'pagination' => $pagination_and_data,
        'breadcrumb' => $breadcrumb
    ]);

}elseif (!empty($system->route(1)) && !empty($system->route(2))){
    //####################### İÇERİK DETAY KISMI #######################\\
    //kategori sorgulaması
    $contentLink = explode("-",$system->route(1));
    $id = $functions->numberOnly(end($contentLink));
    $normalLink = array_pop($contentLink);
    $normalLink = implode("-",$contentLink);

    //içerik kategorisi bilgileri
    list($linkCount,$linkData) = $siteManager->getCategory($id);
    if((int)$linkCount !== 1){
        $functions->redirect($system->url());
    }

    $log->logThis($log->logTypes["CONTENT_DETAIL"], $system->route(1));
     //detay kısmı

    //gelen linki - ile parçaladık ama içeriğin başlığı deneme-içerik gibi olabilir o yüzden id yi içinden alıp son elemanı silip diğerlerni bilirleştirelim
    $contentLink = explode("-",$system->route(2));
    $id = $functions->numberOnly(end($contentLink));
    $normalLink = array_pop($contentLink);
    $normalLink = $functions->cleaner(implode("-",$contentLink));
    $page_query = $db::$db->prepare("SELECT *
    FROM content WHERE lang=:lang AND id=:id AND link=:link AND status=1 AND deleted=0 ORDER BY created_at DESC LIMIT 0,1");
    $page_query->bindParam(":id",$id,PDO::PARAM_INT);
    $page_query->bindParam(":link",$normalLink,PDO::PARAM_STR);
    $page_query->bindParam(":lang",$_SESSION['lang'],PDO::PARAM_STR);
    $page_query->execute();
    $pageData_count = $page_query->rowCount();
    $pageData = $page_query->fetch(PDO::FETCH_OBJ);

    //bu içeriğe ait diğer veriler
    $otherLanguageContent = $siteManager->getOrtherLanguageContent($pageData->lang_id);

    if((int)$pageData_count !== 1){
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

    $breadcrumb = [
        [
            'title' => $functions->textManager('breadcrumb_content_categories_list'),
            'url' => $system->url($siteManager->getPrefix('content')),
        ],
        [
            'title' => $linkData->title,
            'url' => $system->url($siteManager->getPrefix('content').'/'.$linkData->link.'-'.$linkData->id),
        ],
        [
            'title' => $pageData->title,
            'active' => true,
        ]
    ];

    //sağ alan olsun mu ?
    $right_bar  = true;

    //okunma sayısı yapımı
    $cookie_name = $linkData->link."_".$pageData->link."-".$pageData->id;
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
        'breadcrumb' => $breadcrumb
    ]);
}
<?php

use Mrt\MimozaCore\View;

$log->logThis($log->logTypes["SEARCH"]);
$count = 0;
if(isset($_GET["q"]) && !empty($_GET["q"])){
    $text = $functions->cleanGet("q");
    $aranacak = "%".$text."%";

    // içeriklerde aranıyor
    $content_query = $db::query("SELECT c.*,ct.link as c_link FROM content c 
        INNER JOIN content_categories ct ON ct.id=c.cat_id
        WHERE 
        (c.title LIKE :keyword OR c.link LIKE :keyword OR c.text LIKE :keyword OR c.abstract LIKE :keyword) 
        AND c.lang=:lang AND c.deleted=0 AND c.status=1
        AND ct.status=1 AND ct.deleted=0
        ORDER BY c.created_at DESC");
        $content_query->bindParam(':lang', $_SESSION["lang"], PDO::PARAM_STR);
        $content_query->bindParam(':keyword', $aranacak, PDO::PARAM_STR);
        $content_query->execute();
        $content_query_count = $content_query->rowCount();
        $contentQueryQata = $content_query->fetchAll(PDO::FETCH_OBJ);
        $count += $content_query_count;

    //kategorilerde aranıyor
    $page_query_categories = $db::query("SELECT * FROM content_categories
        WHERE 
        (title LIKE :keyword OR link LIKE :keyword) 
        AND lang=:lang AND deleted=0 AND status=1
        ORDER BY show_order ASC");
    $page_query_categories->bindParam(':lang', $_SESSION["lang"], PDO::PARAM_STR);
    $page_query_categories->bindParam(':keyword', $aranacak, PDO::PARAM_STR);
    $page_query_categories->execute();
    $pageData_count_categories = $page_query_categories->rowCount();
    $pageDataCategories = $page_query_categories->fetchAll(PDO::FETCH_OBJ);
    $count += $pageData_count_categories;

    //sayfalarda aranıyor
    $page_query = $db::query("SELECT * FROM page
        WHERE 
        (title LIKE :keyword OR link LIKE :keyword OR text LIKE :keyword OR abstract LIKE :keyword) 
        AND lang=:lang AND deleted=0 AND status=1
        ORDER BY show_order ASC");
    $page_query->bindParam(':lang', $_SESSION["lang"], PDO::PARAM_STR);
    $page_query->bindParam(':keyword', $aranacak, PDO::PARAM_STR);
    $page_query->execute();
    $page_query_count = $page_query->rowCount();
    $pageQueryData = $page_query->fetchAll(PDO::FETCH_OBJ);
    $count += $page_query_count;

}
$metaTag->title = $functions->textManager("arama_baslik");

View::layout('search',[
    'text' => $text,
    'contentQueryQata' => $contentQueryQata,
    'pageDataCategories' => $pageDataCategories,
    'pageQueryData' => $pageQueryData,
    'count' => $count,
]);
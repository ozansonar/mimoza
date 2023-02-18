<?php
/**
 * Bu sınıf sistemin dinamik şekilde  sitemap.xml dosyasını oluşturmak için yazıldı
 * Sitemde tanımlı dilleri tarayıp ona göre haritayı oluşturacaktır sistemde ekli olan tablolar için yapıyorum sizin harici bir tablonuz linkiniz olursa side onu yapınız
 * Ozan SONAR [ozansonar1@gmail.com]
 */
namespace Includes\SiteMap;

use OS\MimozaCore\Database;
use PDO;

class SiteMap
{
    private Database $database;
    public ?string $sitemap = null;
    public string $domain;
    public array $languages;
    public ?string $defaultLanguage = "tr";
    public function __construct(Database $database,$domain,$languages,$defaultLanguage)
    {
        $this->database = $database;
        $this->domain = $domain;
        $this->languages = $languages;
        $this->defaultLanguage = $defaultLanguage;
        $this->sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $this->sitemap .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.w3.org/TR/xhtml11/xhtml11_schema.html http://www.w3.org/2002/08/xhtml/xhtml1-strict.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/TR/xhtml11/xhtml11_schema.html">'.PHP_EOL;
    }

    public function start(): void
    {
        $this->sitemap = '<?xml version="1.0" encoding="UTF-8"?>
            ';
    }

    public function end(): void
    {
        $this->sitemap .= '</urlset>'.PHP_EOL;
    }

    public function loc(array $data = null): void
    {
        $url = $data["loc"] === "/" ? $this->domain:$data["loc"];
        $this->sitemap .= "<loc>".($url)."</loc>";
    }
    public function lastMod(array $data = null): void
    {
        $this->sitemap .= "<lastmod>".($data["lastmod"] ?? date("Y-m-d"))."</lastmod>";
    }
    public function changefreq(array $data = null): void
    {
        $this->sitemap .= "<changefreq>".($data["changefreq"] ?? "w")."</changefreq>";
    }

    public function priority(array $data = null): void
    {
        $this->sitemap .= "<priority>".($data["priority"] ?? 0.8)."</priority>";
    }
    public function altarnative(array $data = null): void
    {
        if(isset($data["altarnative"]) && !empty($data["altarnative"])){
            //echo "<pre>";print_r($data);echo "</pre>";
            foreach ($data["altarnative"] as $altarnativeKey=>$altarnativeValue){
                $this->sitemap .= '<xhtml:link rel="alternate" hreflang="'.$altarnativeKey.'" href="'.$altarnativeValue.'" />';
            }
        }
    }

    public function addMap(array $data): void
    {
        $this->sitemap .= "<url>";
        $this->loc($data);
        $this->lastMod($data);
        $this->changefreq($data);
        $this->priority($data);
        $this->altarnative($data);
        $this->sitemap .= "</url>";
    }

    public function link(string $lang,string $link): string
    {
        if($lang !== $this->defaultLanguage){
            return $this->domain.$lang."/".$link;
        }
        return $this->domain.$link;
    }

    /**
     * Sayfaların haritasını verir
     * @return void
     */
    public function pageMap(): void
    {
        $mapArray = [];
        $getQuery = $this->database::query("SELECT p.link,p.id,p.lang,p.lang_id FROM page p WHERE p.status=1 AND p.deleted=0 ORDER BY p.id DESC");
        $getQuery->execute();
        $getData = $getQuery->fetchAll(PDO::FETCH_OBJ);
        foreach ($getData as $row){
            $mapArray[$row->lang_id][$row->lang]["lang"] = $row->lang;
            $mapArray[$row->lang_id][$row->lang]["link"] = $this->link($row->lang,$row->link);
        }
        foreach ($mapArray as $mapKey=>$map){
            foreach ($this->languages as $lang){
                if(array_key_exists($lang->short_lang,$map)){
                    //eğer array 1 den büyükse diğer dilde de urli var demektir
                    $altarnetive = [];
                    if(count($map) > 1){
                        foreach ($map as $mapLangKey=>$mapRow){
                            if($mapLangKey === $lang->short_lang){
                                continue;
                            }
                            $altarnetive[$mapRow["lang"]] = $mapRow["link"];
                        }
                    }

                    $this->addMap([
                        "loc" => $map[$lang->short_lang]["link"],
                        "lastmod" => date("Y-m-d"),
                        "changefreq" => "w",
                        "priority" => 0.8,
                        "altarnative" => $altarnetive
                    ]);

                }
            }
        }
    }

    /**
     * İçeriklerin haritasını verir
     * @return void
     */
    public function contentsMap(): void
    {
        $mapArray = [];
        $getQuery = $this->database::query("
            SELECT c.id,c.link,c.updated_at,c.lang,c.lang_id,cc.link as cc_link,cc.id as cc_id FROM content c 
            INNER JOIN content_categories cc ON cc.id=c.cat_id
            WHERE c.status=1 AND c.deleted=0 AND cc.status=1 AND cc.deleted=0               
            ORDER BY c.id DESC");
        $getQuery->execute();
        $getData = $getQuery->fetchAll(PDO::FETCH_OBJ);
        foreach ($getData as $row){
            $mapArray[$row->lang_id][$row->lang]["lang"] = $row->lang;
            $mapArray[$row->lang_id][$row->lang]["date"] = date_d_m_y_to_y_m_d($row->updated_at);
            $mapArray[$row->lang_id][$row->lang]["link"] = $this->link($row->lang,getPrefix("content")."/".$row->cc_link."-".$row->cc_id."/".$row->link."-".$row->id);
        }

        foreach ($mapArray as $mapKey=>$map){
            foreach ($this->languages as $lang){
                if(array_key_exists($lang->short_lang,$map)){
                    //eğer array 1 den büyükse diğer dilde de urli var demektir
                    $altarnetive = [];
                    if(count($map) > 1){
                        foreach ($map as $mapLangKey=>$mapRow){
                            if($mapLangKey === $lang->short_lang){
                                continue;
                            }
                            $altarnetive[$mapRow["lang"]] = $mapRow["link"];
                        }
                    }

                    $this->addMap([
                        "loc" => $map[$lang->short_lang]["link"],
                        "lastmod" => $map[$lang->short_lang]["date"],
                        "changefreq" => "w",
                        "priority" => 1,
                        "altarnative" => $altarnetive
                    ]);

                }
            }
        }
    }

    /**
     * Arrayda belirtilmiş yolların haritasını çıkarır
     * @return void
     */
    public function controllerMap(): void
    {
        global $constants;
        $mapArray = [];
        foreach ($constants::systemLinkPrefix as $fileKey=>$file){
            //echo "<pre>";print_r($file);echo "</pre>";exit;
            if((int)$file["active"] !== 1){
                continue;
            }
            foreach ($this->languages as $lang){
                $url = $this->link($lang->short_lang,$this->getPrefix2($fileKey,$lang->short_lang));
                $this->addMap([
                    "loc" => $url,
                    "lastmod" => date("Y-m-d"),
                    "changefreq" => "w",
                    "priority" => 1,
                    "altarnative" => $this->getPrefixAltarnative($fileKey,$lang->short_lang)
                ]);
            }
        }
    }

    public function getPrefix(string $prefix,?string $lang="tr"): string
    {
        global $settings;
        if(isset($settings->{$prefix."_prefix_".$lang})){
            return $settings->{$prefix."_prefix_".$lang};
        }
    }
    public function getPrefix2(string $prefix,?string $lang="tr"): string
    {
        global $settings;
        if(isset($settings->{$prefix.$lang})){
            return $settings->{$prefix.$lang};
        }
    }

    public function getPrefixAltarnative(string $prefix,string $lang): array
    {
        global $settings;
        $data = [];
        foreach ($this->languages as $language){
            if($lang === $language->short_lang){
                continue;
            }
            if(isset($settings->{$prefix.$language->short_lang})){
                $data[$language->short_lang] =  $this->link($language->short_lang,$settings->{$prefix.$language->short_lang});
            }
        }
        return $data;
    }



    public function generate(): string
    {
        $this->controllerMap();
        $this->pageMap();
        $this->contentsMap();
        $this->end();
        return $this->sitemap;
    }
}
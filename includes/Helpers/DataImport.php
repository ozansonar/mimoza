<?php

use Includes\System\SiteManager;
use Includes\System\Database;

class DataImport{
    public $database;
    public $helper;
    public $site_manager;
    public function __construct(){
        $this->database = new Database();
        $this->helper = new helper();
        $this->site_manager = new SiteManager($this->database);
    }

    public function aidat_add(){
        if ($xlsx = SimpleXLSX::parse($this->helper->root_url("data/aidatbilgisi.xlsx")) ) {
            $data = array();
            foreach ($xlsx->rows() as $row){
                if(!is_numeric($row[0])){
                    continue;
                }
                $data[$row[0]]["yil"] = $row[0];
                $tutar_parse = explode(" ",$row[2]);
                $data[$row[0]]["tutar"] = trim($tutar_parse[0]);
                $data[$row[0]]["status"] = 1;
            }
            ksort($data);
            foreach ($data as $db_data){
                $insert = $this->database->insert("aidat",$db_data);
                if($insert){
                    $this->helper->pre_arr($db_data);
                }
            }
        } else {
            echo SimpleXLSX::parse_error();
        }
    }

    public function asil_uye_add($uye_type){
        $excel_file = "";
        $user_rank = 1;
        switch ($uye_type){
            case 40:
                $excel_file = "asiluye.xlsx";
                $user_rank = 40;
                break;
            case 30:
                $excel_file = "onursal-uye.xlsx";
                $user_rank = 30;
                break;
            case 10:
                $excel_file = "vefat-uye.xlsx";
                $user_rank = 10;
                break;
            case 8:
                $excel_file = "derbis-uye.xlsx";
                $user_rank = 8;
                break;
            case 5:
                $excel_file = "istifa-uye.xlsx";
                $user_rank = 5;
                break;
            default:
                $excel_file = "";
                break;
        }
        if ($xlsx = SimpleXLSX::parse($this->helper->root_url("data/".$excel_file)) ) {
            //exceli okumadan önce resimleri hazırlayalım
            $user_image_array = array();
            $user_img_path = $this->helper->root_url("data/uye-gorsel/");
            $dir = new DirectoryIterator($user_img_path);
            foreach ($dir as $fileinfo) {
                //$this->helper->pre_arr($fileinfo);
                if(strstr($fileinfo->getFilename(),".png")){
                    //$fileinfo->getFilename()
                    $file_name = explode("_",$fileinfo->getFilename());
                    $array_key = rtrim($this->helper->en_strtolower(end($file_name)),".png");
                    if(array_key_exists($array_key,$user_image_array)){
                        $this->helper->pre_arr("aynı isim->".$fileinfo);
                        continue;
                    }
                    $user_image_array[$array_key]["name"] = end($file_name);
                    $user_image_array[$array_key]["file_path"] = $fileinfo->getpathName();
                    $user_image_array[$array_key]["upload_path"] = "/home/virtual/totbid.org.tr/uploads/user/";
                }
            }
            $data = array();
            $counter = 1;
            foreach ($xlsx->rows() as  $key=>$row){
                if($key == 0){
                    continue;
                }
                if($key > 10){
                    //continue;
                }
                $email = $this->helper->cleaner($row[1]);
                if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                    if($email == "noReply@iris-interaktif.com"){
                        $email = str_replace("@",uniqid()."@",$email);
                    }
                    $data[$counter]["email"] = $email;
                }else{
                    //$data[$counter]["email"] = "hatalı mail ->".$email;
                    $this->helper->pre_arr("hatalı mail ->".$email);
                }

                $telefon = $this->helper->cleaner($row[2]);
                $data[$counter]["telefon"] = $telefon;

                $tc_no = $this->helper->cleaner($row[3]);
                $data[$counter]["tc_no"] = $tc_no;

                $ad = explode(" ",$this->helper->cleaner($row[4]));
                $soyad = explode(" ",$this->helper->cleaner($row[4]));
                array_pop($ad);
                $data[$counter]["name"] = implode(" ",$ad);
                $data[$counter]["surname"] = end($soyad);

                //resim için isim soyisim formatlama
                $formating_img_name = $this->helper->en_strtolower($data[$counter]["name"])." ".$this->helper->en_strtolower(strtolower($data[$counter]["surname"]));
                $data[$counter]["img_name"] = str_replace(" ","-",$formating_img_name);


                //$user_image = array_filter(glob('*'), $this->helper->root_url("data/uye-gorsel"));
                //$this->helper->pre_arr($user_imagesa);

                $kayit_tarihi = $this->helper->cleaner($row[5]);
                $data[$counter]["created_at"] = $kayit_tarihi;

                $adres_bilgileri = preg_replace('/\[(.*)\]/',"",$row[6]);
                $adres = $this->helper->cleaner($adres_bilgileri);
                $data[$counter]["adres"] = $adres;
                /*
                 * Toplantı sonrası bu alan kaldırıldı.
                if(!empty($row[7])){
                    $nufus_bilgisi_parse = explode(":",$this->helper->cleaner($row[7]));
                    $nufus_bilgisi = isset($nufus_bilgisi_parse[1]) ? trim($nufus_bilgisi_parse[1]):null;
                }else{
                    $nufus_bilgisi = null;
                }
                $data[$counter]["nufus_bilgisi"] = $nufus_bilgisi;
                */
                /*
                 * Toplantı sonrası bu alan kaldırıldı.
                if(!empty($row[8])){
                    $uzamanlik_bilgisi_parse = explode(":",$this->helper->cleaner($row[8]));
                    $uzamanlik_bilgisi = isset($uzamanlik_bilgisi_parse[1]) ? trim($uzamanlik_bilgisi_parse[1]):null;
                }else{
                    $uzamanlik_bilgisi = null;
                }
                $data[$counter]["uzmanlik_bilgisi"] = $uzamanlik_bilgisi;
                */


                if(!empty($row[9])){
                    $fakulte_bilgisi_parse = explode(":",$this->helper->cleaner($row[9]));
                    $fakulte_bilgisi = isset($fakulte_bilgisi_parse[1]) ? trim($fakulte_bilgisi_parse[1]):null;
                }else{
                    $fakulte_bilgisi = null;
                }
                $data[$counter]["fakulte_bilgisi"] = $fakulte_bilgisi;

                if(!empty($row[10])){
                    $ihtisas_parse = explode(":",$this->helper->cleaner($row[10]));
                    $ihtisas = isset($ihtisas_parse[1]) ? trim($ihtisas_parse[1]):null;
                }else{
                    $ihtisas = null;
                }
                $data[$counter]["ihtisas"] = $ihtisas;

                if(!empty($row[11])){
                    $dernek_kayit_defteri_no_parse = explode(":",$this->helper->cleaner($row[11]));
                    $dernek_kayit_defteri_no = isset($dernek_kayit_defteri_no_parse[1]) ? trim($dernek_kayit_defteri_no_parse[1]):null;
                }else{
                    $dernek_kayit_defteri_no = null;
                }
                $data[$counter]["dernek_kayit_defteri_no"] = $dernek_kayit_defteri_no;

                if(!empty($row[12])){
                    $calistigi_kurum_parse = explode(":",$this->helper->cleaner($row[12]));
                    $calistigi_kurum = isset($calistigi_kurum_parse[1]) ? trim($calistigi_kurum_parse[1]):null;
                }else{
                    $calistigi_kurum = null;
                }
                $data[$counter]["calistigi_kurum"] = $calistigi_kurum;

                /*
                 * Toplantı sonrası bu alan kaldırıldı.
                if(!empty($row[13])){
                    $sertifika_parse = explode(":",$this->helper->cleaner($row[13]));
                    $sertifika = isset($sertifika_parse[1]) ? trim($sertifika_parse[1]):null;
                }else{
                    $sertifika = null;
                }
                $data[$counter]["sertifika"] = $sertifika;
                */
                /*
                 * bu excelde bulunan herkes asil üye imiş
                if(!empty($row[14])){
                    if(strstr($row[14],"ASİL")){
                        $rank = 40;
                    }else{
                        //$rank = "üyelik tipi yok-1";
                        $rank = 0;
                    }
                }else{
                    //$rank = "üyelik tipi yok 1";
                    $rank = 0;
                }
                */
                $data[$counter]["rank"] = $user_rank;
                $unvan_diger_data = null;
                if(!empty($row[15])){
                    $unvan_diger_parse = explode(":",$this->helper->cleaner($row[15]));
                    $unvan_diger = isset($unvan_diger_parse[1]) ? trim($unvan_diger_parse[1]):null;
                    if($unvan_diger == "Asistan Doktor"){
                        $unvan = 1;
                    }elseif ($unvan_diger == "OP. DR."){
                        $unvan = 2;
                    }elseif ($unvan_diger == "DR. ÖĞR. ÜYESİ"){
                        $unvan = 3;
                    }elseif ($unvan_diger == "DOÇ. DR."){
                        $unvan = 4;
                    }elseif ($unvan_diger == "PROF. DR."){
                        $unvan = 5;
                    }elseif(!empty($unvan_diger)){
                        $unvan = 99;
                        $unvan_diger_data = $unvan_diger;
                    }
                    //UZMAN emekli vs
                }else{
                    $unvan = null;
                }
                $data[$counter]["unvan"] = $unvan;
                $data[$counter]["unvan_diger"] = $unvan_diger_data;


                if(!empty($row[17])){
                    $asistanlik_baslama_parse = explode(":",$this->helper->cleaner($row[17]));
                    $asistanlik_baslama = isset($asistanlik_baslama_parse[1]) ? trim($this->helper->date_d_m_y_to_y_m_d($asistanlik_baslama_parse[1])):null;
                }else{
                    $asistanlik_baslama = null;
                }
                $data[$counter]["asistanlik_baslama"] = $asistanlik_baslama;

                if(!empty($row[18])){
                    $uzman_olma_parse = explode(":",$this->helper->cleaner($row[18]));
                    $uzman_olma = isset($uzman_olma_parse[1]) ? trim($this->helper->date_d_m_y_to_y_m_d($uzman_olma_parse[1])):null;
                }else{
                    $uzman_olma = null;
                }
                $data[$counter]["uzman_olma"] = $uzman_olma;

                if(!empty($row[19])){
                    $yk_bilgisi_parse = explode(":",$this->helper->cleaner($row[19]));
                    $yk_bilgisi = isset($yk_bilgisi_parse[1]) ? trim($this->helper->date_d_m_y_to_y_m_d($yk_bilgisi_parse[1])):null;
                }else{
                    $yk_bilgisi = null;
                }
                $data[$counter]["yk_bilgisi"] = $yk_bilgisi;

                if(!empty($row[21])){
                    $yuksek_lisans_parse = explode(":",$this->helper->cleaner($row[21]));
                    $yuksek_lisans = isset($yuksek_lisans_parse[1]) ? trim($yuksek_lisans_parse[1]):null;
                }else{
                    $yuksek_lisans = null;
                }
                $data[$counter]["yuksek_lisans"] = $yuksek_lisans;

                //elle eklenmesi gerekenler
                $data[$counter]["role_group"] = 17;
                $data[$counter]["send_mail"] = 1;
                $data[$counter]["email_verify"] = 1;
                $data[$counter]["status"] = 1;
                $data[$counter]["password"] = password_hash(12341234,PASSWORD_DEFAULT);
                $counter++;
            }
           /*
            *
            *  // Copying gfg.txt to geeksforgeeks.txt
            $srcfile = '/home/virtual/totbid.org.tr/data/uye-gorsel/uye1673_cansunar-ege.png';
            $destfile = '/home/virtual/totbid.org.tr/uploads/user/cansunar-ege.png';

            if (!copy($srcfile, $destfile)) {
                echo "File cannot be copied! \n";
            }
            else {
                echo "File has been copied!";
            }*/
            echo "toplam counter değeri:".$counter." toplam array count: ".count($data);
            foreach ($data as $data_key=>$db_data){
                if($this->site_manager->uniq_email($db_data["email"]) > 0){
                    $this->helper->pre_arr("mevcut mail -> ".$db_data["email"]);
                    continue;
                }
                $img_name = $db_data["img_name"];
                unset($db_data["img_name"]);
                //resim eşleşmesi bu maillerde yapılmasın
                $no_resim = array("drahmetaslan@gmail.com","draaslan@hotmail.com","dromeryavuz@hotmail.com","dromeryvz@gmail.com");
                if(!in_array($db_data["email"],$no_resim)){
                    //sıfırşamayapıp tekrar çalıştır.
                    //kullanıcı eklendi resmi varsa bulalım
                    if(array_key_exists($img_name,$user_image_array)){
                        //kullanıcı resmini kopyala
                        $upload_img_name = $this->helper->generateRandomString(30).uniqid().".png";
                        if(copy($user_image_array[$img_name]["file_path"], $user_image_array[$img_name]["upload_path"]."/".$upload_img_name)){
                            $db_data["img"] = $upload_img_name;
                        }else{
                            $this->helper->pre_arr("resim yüklenemedi.".$db_data);
                        }
                    }
                }

                $insert = $this->database->insert("users",$db_data);
                if($insert){

                }else{
                    echo "----------------";
                    echo "eklenemedi";
                    $this->helper->pre_arr($db_data);
                    echo "----------------";
                }
            }
        } else {
            echo SimpleXLSX::parse_error();
        }
    }

    public function aidat_odemeleri(){
        //bu kullanıcıları geçelim
        $gecilecek_kullanicilar = array(
            "ahmet-aslan",
            "mehmet-yilmaz",
            "omer-yavuz",
            "bunyamin-ari",
        );

        //kullanıcıları çekip array yapalım
        $user_array = array();
        list($u_count,$u_data) = $this->database->fetch_all("users"," WHERE deleted=0");
        foreach ($u_data as $user_row){
            $user_name_key = str_replace(" ","-",$this->helper->en_strtolower($user_row->name)." ".$this->helper->en_strtolower($user_row->surname));
            if(array_key_exists($user_name_key,$user_array)){
                $this->helper->pre_arr("user var ".$user_name_key);
            }
            $user_array[$user_name_key] = $user_row;
        }
        //aidarları array olarak yapalım
        $aidat_arrray = array();
        list($aidat_count,$aidat_data) = $this->database->fetch_all("aidat");
        foreach ($aidat_data as $aidat_row){
            $aidat_arrray[$aidat_row->yil] = $aidat_row->id;
        }
        //$this->helper->pre_arr($aidat_arrray);

        //$this->helper->pre_arr($u_count." user count");
        //$this->helper->pre_arr($user_array);
        if($xlsx = SimpleXLSX::parse($this->helper->root_url("data/aidatdetay.xlsx")) ) {
            $aidat_row = array();
            $user_aidat = array();
            $counter = -1;
            foreach ($xlsx->rows() as $key=>$row){
                $aidat_row[$key] = $row;
            }
            foreach ($xlsx->rows() as $key=>$row){
                $counter++;
                if($key >= 3){
                    $text = trim($row[0]);
                    if(!is_numeric($text) && !filter_var($text,FILTER_VALIDATE_EMAIL) && !empty($text)){
                        $user_name = $this->helper->en_strtolower(str_replace(" ","-",$text));
                        //$this->helper->pre_arr($user_name." tsh" .$key);
                        if(array_key_exists($user_name,$user_aidat)){
                            $this->helper->pre_arr("aynı kullanıcı ->".$user_name);
                            continue;
                        }
                        $odeme_durumu = $aidat_row[$key+2];
                        for ($q=1; $q<=21;$q++){
                            if(!empty($odeme_durumu[$q]) && $odeme_durumu[$q] == "ÖDENDİ"){
                                $user_aidat[$user_name]["aidat"][2022-$q] = 1;
                            }elseif (!empty($odeme_durumu[$q]) && $odeme_durumu[$q] == "ÖDENMEDİ"){
                                $user_aidat[$user_name]["aidat"][2022-$q] = 2;
                            }
                        }
                        $user_aidat[$user_name]["ad"] = $text;
                    }
                }
            }
            foreach ($user_aidat as $aidat_key=>$aidat_user){
                if(array_key_exists($aidat_key,$user_array)){
                    $user_id = $user_array[$aidat_key]->id;
                    if(isset($aidat_user["aidat"])){
                        foreach ($aidat_user["aidat"] as $aidat_key=>$aidat_row){
                            $aidat_data_db = array();
                            $aidat_data_db["user_id"] = $user_id;
                            $aidat_data_db["aidat_id"] = $aidat_arrray[$aidat_key];
                            $aidat_data_db["odeme"] = $aidat_row;
                            $aidat_data_db["status"] = 1;
                            //$this->helper->pre_arr($aidat_data_db);
                            $add = $this->database->insert("aidat_odemeleri",$aidat_data_db);
                            if(!$add){
                                $this->helper->pre_arr("eklenemedi ".$aidat_data_db);
                            }
                        }
                    }
                }
            }
        } else {
            echo SimpleXLSX::parse_error();
        }
    }

    //sistemde bulunan kişilere sistemin değiştiğine daiar 1 sefrlik mail gönderimi yapar
    public function kullanici_sifre_gonderme(){
        $database = $this->database;


        $mailler = new mail();

        list($user_count,$user_data) = $database->fetch_all("users"," WHERE deleted=0");
        foreach ($user_data as $user_data_row){
            $id = $user_data_row->id;

            //daha önce gonderilmiş mi
            $daha_once_gitmismi = $database->db->prepare("SELECT * FROM bilgilendirilen_user_list WHERE user_id=:u_id AND deleted=0");
            $daha_once_gitmismi->bindParam(":u_id",$id,PDO::PARAM_INT);
            $daha_once_gitmismi->execute();
            $daha_once_giden_count = $daha_once_gitmismi->rowCount();
            if($daha_once_giden_count < 1){
                $name_surname = $user_data_row->name." ".$user_data_row->surname;
                $email = $user_data_row->email;
                $mail_metni = "";

                $password = $this->helper->generate_password();

                $mailler->adress = $email;
                $mailler->subject = "TOTBİD - Yeni sistemimiz açıldı. Şifreniz :".$password;
                $mailler->message = $mail_metni;
                $mailler->mail_send();

                if($mailler->sent_status == 1){

                    $upd_data = array();
                    $upd_data["password"] = password_hash($password,PASSWORD_DEFAULT);
                    $database->update("users",$upd_data,"id",$id);

                    $gonderildi = array();
                    $gonderildi["user_id"] = $id;
                    $database->insert("bilgilendirilen_user_list",$gonderildi);
                }



            }
            $this->helper->pre_arr($daha_once_giden_count);
        }
    }

    public function haber_arsiv_add(){
        $starttime = microtime(true);
        ini_set('max_execution_time', 0);
        //simple_html_dom.php
        include $this->helper->root_url("includes/class/simple_html_dom.php");
        $min = 21;
        $max = 71;
        $link = "http://www.totbid.org.tr/totbid/haber?Page=";
        $site_url  = "http://www.totbid.org.tr";
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5";
        $dosyalarin_kopyalanacagi_dizin = "../uploads/content/aktarim/";
        $dosyalarin_kopyalanacagi_dizin_2 = "uploads/content//aktarim/";
        $toplam_haber_sayisi = 1769;
        $data_1 = array();
        $data_count = 1;
        for ($t=$min;$t<=$max;$t++){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $link.$t);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $html = curl_exec($ch);
            curl_close($ch);

            $html = str_get_html($html);
            foreach($html->find('a.list-group-item') as $element){
                $data_1[$data_count]["title"] = trim($element->innertext);
                $data_1[$data_count]["url"] = trim($site_url.$element->href);
                $data_count++;
            }

        }

        $project_db_data = array();
        foreach ($data_1 as $data_key=>$data_row){
            if($data_row["url"] != "http://www.totbid.org.tr/totbid/haber/ramazan-bayraminiz-kutlu-olsun---/21421"){
                //continue;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $data_row["url"]);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $html = curl_exec($ch);
            curl_close($ch);

            $html = str_get_html($html);
            foreach($html->find(".content-wrap") as $element)  {
                $project_db_data[$data_key]["cat_id_tr"] = 100;
                $project_db_data[$data_key]["show_order_tr"] = $toplam_haber_sayisi-$data_key;
                $project_db_data[$data_key]["status_tr"] = 1;
                $project_db_data[$data_key]["link_tr"] = $this->helper->permalink($data_row["title"]);
                $project_db_data[$data_key]["title_tr"] = $data_row["title"];
                $project_db_data[$data_key]["text_tr"] = $element->find('.icerik',0)->innertext;

                $uzantilar = array(
                    "png" => "png",
                    "jpg" => "jpg",
                    "jpeg" => "jpeg",
                    "pdf" => "pdf",
                    "doc" => "doc",
                    "docx" => "docx",
                    "pttx" => "pttx"
                );

                //kaç resim olacağı belli değil o yüzden varsayılan olarak 10 tane dönelim
                for ($i=0;$i<=10;$i++){
                    $img = @$element->find('.icerik img',$i)->src;
                    if(!empty($img)){
                        $img_parse = explode(".",$img);
                        $uzanti = "jpg";
                        if(array_key_exists(end($img_parse),$uzantilar)){
                            $uzanti = $uzantilar[end($img_parse)];
                        }
                        $upload_img_name = $this->helper->generateRandomString(30).uniqid().".".$uzanti;
                        if(@copy($img, $this->helper->root_url($dosyalarin_kopyalanacagi_dizin_2.$upload_img_name))){
                            //$project_db_data[$data_key]["img"] = $dosyalarin_kopyalanacagi_dizin.$upload_img_name;
                            $project_db_data[$data_key]["text_tr"] = str_replace($img,$dosyalarin_kopyalanacagi_dizin.$upload_img_name,$project_db_data[$data_key]["text_tr"]);
                        }
                    }
                }

                //kaç link olacağı belli değil o yüzden varsayılan olarak 10 tane dönelim
                for ($i=0;$i<=10;$i++){
                    $href = @$element->find('.icerik a',$i)->href;
                    if(!empty($href)){
                        //dosyalar
                        $href_explode = explode(".",$href);
                        if(array_key_exists(end($href_explode),$uzantilar)){
                            //http://yonetim.citius.technology//haber/haber21430/tot2021-162-saglik-bakanligi-prp.pdf
                            $upload_img_name = $this->helper->generateRandomString(30).uniqid().".".end($href_explode);
                            if(@copy($href, $this->helper->root_url($dosyalarin_kopyalanacagi_dizin_2.$upload_img_name))){
                                //$project_db_data[$data_key]["file"] = $dosyalarin_kopyalanacagi_dizin.$upload_img_name;
                                $project_db_data[$data_key]["text_tr"] = str_replace($href,$dosyalarin_kopyalanacagi_dizin.$upload_img_name,$project_db_data[$data_key]["text_tr"]);
                            }
                        }
                    }
                }
            }
        }
        foreach ($project_db_data as $project_db_data_row){
            $project_db_data_row["abstract_tr"] = $project_db_data_row["text_tr"];
            $this->helper->pre_arr($project_db_data_row);
            $this->database->insert("content",$project_db_data_row);
        }
        $endtime = microtime(true);
        printf("Page loaded in %f seconds", $endtime - $starttime );

    }

    public function eklenen_icerik_linkleri(){
        list($c,$d) = $this->database->fetch_all("content"," WHERE cat_id_tr=100 AND deleted=0");
        foreach ($d as $row){
            //$this->helper->pre_arr($row);
            $upd = array();
            $upd["title_tr"] = $this->helper->tr_cevir($row->title_tr);
            $upd["text_tr"] = $this->helper->tr_cevir($row->text_tr);
            $upd["abstract_tr"] = $this->helper->tr_cevir($row->abstract_tr);
            $upd["link_tr"] = $this->helper->permalink($this->helper->tr_cevir($row->title_tr));
            $this->database->update("content",$upd,"id",$row->id);
            //$this->helper->pre_arr($upd);
        }
    }
}
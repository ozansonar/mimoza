<?php
//sistemde kullanılan keyler burada üretiliyor ve db de burdaki arraydan generate ediliyor
$language_text_manager = array(
	"header" => array(
		"title" => "Header Yazılar (sayfanın en üst kısmı)",
		"form" => array(
			array(
				"name" => "header_search_button",
				"label" => "Arama Buton"
			),
            array(
                "name" => "header_search_placeholder",
                "label" => "Arama Kutusu"
            ),
            array(
                "name" => "header_giris_button",
                "label" => "Giriş Yap"
            ),
            array(
                "name" => "header_profil",
                "label" => "Bilgilerim"
            ),
            array(
                "name" => "header_yonetim_paneli",
                "label" => "Yönetim Paneli"
            ),
            array(
                "name" => "header_cikis",
                "label" => "Çıkış"
            ),
		),
	),
    "footer" => array(
        "title" => "Footer Yazılar (sayfanın en alt kısmı)",
        "form" => array(
            array(
                "name" => "footer_sayfalar",
                "label" => "Sayfalar Başlık"
            ),
            array(
                "name" => "footer_text",
                "label" => "En alttaki yazı"
            ),
        ),
    ),
    "site_bakimda" => array(
        "title" => "Sistem Bakımda",
        "form" => array(
            array(
                "name" => "site_bakimda_baslik",
                "label" => "Sistem Bakımda Başlık"
            ),
            array(
                "name" => "site_bakimda_aciklama",
                "label" => "Sistem Bakımda Açıklama",
                "type" => "textarea",
                "class" => "ckeditor",
            ),
        ),
    ),
    "giris_sayfasi" => array(
        "title" => "Giriş sayfası",
        "form" => array(
            array(
                "name" => "giris_email",
                "label" => "E-posta",
            ),
            array(
                "name" => "giris_sifre",
                "label" => "Şifre",
            ),
            array(
                "name" => "giris_button",
                "label" => "Giriş Buton",
            ),
            array(
                "name" => "giris_sifremi_unuttum_button",
                "label" => "Şifremi Unuttum Buton",
            ),
            array(
                "name" => "giris_ad_veya_sifre_bos",
                "label" => "Kullanıcı adı veya şifre boş",
            ),
            array(
                "name" => "giris_email_gecerli_formatta_degil",
                "label" => "E-posta geçerli formatta değil.",
            ),
            array(
                "name" => "giris_hatali_bilgiler",
                "label" => "Girdiği bilgiler hatalı",
            ),
            array(
                "name" => "giris_hesap_beklemede",
                "label" => "Kullanıcı hesabı beklemede",
            ),
            array(
                "name" => "giris_hesap_dogrulanmamis",
                "label" => "Kullanıcı hesabı doğrulanmamış",
            ),
            array(
                "name" => "giris_yetkisiz_kullanici",
                "label" => "Kullanıcının bu bölüme girmesi için yetkisi yok",
            ),
            array(
                "name" => "giris_islem_basarili",
                "label" => "Kullanıcı girişi başarılı",
            ),
            array(
                "name" => "giris_sifremi_unuttum_title",
                "label" => "Şifremi Unuttum Başlık",
            ),
            array(
                "name" => "giris_sifremi_unuttum_email",
                "label" => "Şifremi Unuttum E-posta",
            ),
            array(
                "name" => "giris_sifremi_unuttum_kaydet_button",
                "label" => "Şifremi Unuttum Gönderme Butonu",
            ),
            array(
                "name" => "giris_sifremi_unuttum_bos_email",
                "label" => "Şifremi unuttum boş mail uyarısı",
            ),
            array(
                "name" => "giris_sifremi_unuttum_gecersiz_email",
                "label" => "Şifremi unuttum geçersiz mail uyarısı",
            ),
            array(
                "name" => "giris_sifremi_unuttum_kayitsiz_email",
                "label" => "Şifremi unuttum sistemde olmayan mail",
            ),
            array(
                "name" => "giris_sifremi_unuttum_mail_gonderildi",
                "label" => "Şifremi unuttum maili gitti",
            ),
            array(
                "name" => "giris_sifremi_unuttum_mail_gonderilemedi",
                "label" => "Şifremi unuttum maili gönderilemedi",
            ),
        ),
    ),
    "iletisim_sayfasi" => array(
        "title" => "İletişim Sayfası",
        "form" => array(
            array(
                "name" => "contact_passive_title",
                "label" => "Pasif Başlık"
            ),
            array(
                "name" => "contact_title",
                "label" => "Sayfa Başlık"
            ),
            array(
                "name" => "contact_form_top_title",
                "label" => "Formun Üstündeki Başlık"
            ),
            array(
                "name" => "contact_form_top_abstract",
                "label" => "Formun Üstündeki Açıklama"
            ),
            array(
                "name" => "contact_right_title",
                "label" => "Sağ Başlık"
            ),
            array(
                "name" => "contact_sosyal_medya_baslik",
                "label" => "Sosyal Medya Başlık"
            ),
            array(
                "name" => "contact_adres",
                "label" => "Adres"
            ),
            array(
                "name" => "contact_telefon",
                "label" => "Telefon"
            ),
            array(
                "name" => "contact_email",
                "label" => "E-posta"
            ),
            array(
                "name" => "contact_name",
                "label" => "Form Ad"
            ),
            array(
                "name" => "contact_surname",
                "label" => "Form Soyad"
            ),
            array(
                "name" => "contact_subject",
                "label" => "Form Konu"
            ),
            array(
                "name" => "contact_form_email",
                "label" => "E-posta"
            ),
            array(
                "name" => "contact_form_telefon",
                "label" => "Telefon"
            ),
            array(
                "name" => "contact_message",
                "label" => "Form Mesaj"
            ),
            array(
                "name" => "contact_button",
                "label" => "Gönder"
            ),
            array(
                "name" => "contact_button_gonderiliyor",
                "label" => "Gönderiliyor..."
            ),
            array(
                "name" => "contact_button_gonderildi",
                "label" => "Gönderildi"
            ),
            array(
                "name" => "contact_form_success",
                "label" => "Form Gönderme Başarılı"
            ),
            array(
                "name" => "contact_form_error",
                "label" => "Form Gönderme Hatalı"
            ),
            array(
                "name" => "contact_uyari_buton",
                "label" => "Form Uyarı Butonu"
            ),
            array(
                "name" => "contact_validate_ad",
                "label" => "Form Doğrulama Boş Ad"
            ),
            array(
                "name" => "contact_validate_min_2_ad",
                "label" => "Form Doğrulama Ad En az 2 Karakter"
            ),
            array(
                "name" => "contact_validate_max_60_ad",
                "label" => "Form Doğrulama Ad En fazla 60 Karakter"
            ),
            array(
                "name" => "contact_validate_sadece_harf_ad",
                "label" => "Form Doğrulama Ad Sadece Yazı Olmalı"
            ),
            array(
                "name" => "contact_validate_soyad",
                "label" => "Form Doğrulama Boş Soyad"
            ),
            array(
                "name" => "contact_validate_min_2_soyad",
                "label" => "Form Doğrulama Soyad az 2 Karakter"
            ),
            array(
                "name" => "contact_validate_max_60_soyad",
                "label" => "Form Doğrulama Soyad En fazla 60 Karakter"
            ),
            array(
                "name" => "contact_validate_sadece_harf_soyad",
                "label" => "Form Doğrulama Soyad Sadece Yazı Olmalı"
            ),
            array(
                "name" => "contact_validate_email",
                "label" => "Form Doğrulama Boş E-posta"
            ),
            array(
                "name" => "contact_validate_gecersiz_email",
                "label" => "Form Doğrulama Geçersiz E-posta"
            ),
            array(
                "name" => "contact_validate_mesaj",
                "label" => "Form Doğrulama Boş Mesaj"
            ),
            array(
                "name" => "contact_validate_min_10_mesaj",
                "label" => "Form Doğrulama Mesaj az 10 Karakter"
            ),
            array(
                "name" => "contact_validate_max_2000_mesaj",
                "label" => "Form Doğrulama Mesaj En fazla 2000 Karakter"
            ),
            array(
                "name" => "contact_validate_bot_onay",
                "label" => "Lütfen bot olmadığınızı onaylayınız."
            ),
        ),
    ),
    "home" => array( 
        "title" => "Ana Sayfa Değişkenleri",
        "form" => array(
            array(
                "name" => "home_content_head",
                "label" => "Ana Sayfa İçerik Başlık"
            ),
            array(
                "name" => "home_content_button_text",
                "label" => "Ana Sayfa İçerik Buton Yazı"
            ),
        ),
    ),
    "sistem_popup" => array(
        "title" => "Uyarı Popup'u",
        "form" => array(
            array(
                "name" => "sistem_popup_title",
                "label" => "Popup Başlığı"
            ),
            array(
                "name" => "sistem_popup_button",
                "label" => "Popup Buton"
            ),
        ),
    ),
    "sayfalama_sistemi" => array(
        "title" => "Sayfalama sistemi yazıları",
        "form" => array(
            array(
                "name" => "sayfalama_ilk_sayfa",
                "label" => "İlk Sayfa"
            ),
            array(
                "name" => "sayfalama_onceki_sayfa",
                "label" => "Önceki Sayfa"
            ),
            array(
                "name" => "sayfalama_sonraki_sayfa",
                "label" => "Sonraki Sayfa"
            ),
            array(
                "name" => "sayfalama_son_sayfa",
                "label" => "Son Sayfa"
            ),
        ),
    ),
    "icerik_kismi" => array(
        "title" => "İçerikler Kısmı",
        "form" => array(
            array(
                "name" => "icerik_bulunamadi",
                "label" => "İçerik bulunamadığında çıkan uyarı."
            ),
            array(
                "name" => "icerik_detay_buton",
                "label" => "İçerik Detay Butonu"
            ),
        ),
    ),
    "arama_kismi" => array(
        "title" => "Arama Kısmı",
        "form" => array(
            array(
                "name" => "arama_baslik",
                "label" => "Arama sayfası başlık"
            ),
            array(
                "name" => "arama_icerik_bulunamadi",
                "label" => "Arama sonucu bulunamadı"
            ),
            array(
                "name" => "arama_icerik_sonuc_bulundu",
                "label" => "Arama sonucu bulundu"
            ),
            array(
                "name" => "arama_bulunan_content",
                "label" => "Bulunan İçerikler"
            ),
            array(
                "name" => "arama_bulunan_category",
                "label" => "Bulunan İçerik Kategorileri"
            ),
            array(
                "name" => "arama_bulunan_sayfa",
                "label" => "Bulunan Sayfalar"
            ),
        ),
    ),
    "sifre_yenileme" => array(
        "title" => "Şifre Yenileme Kısmı",
        "form" => array(
            array(
                "name" => "sifre_yenileme_baslik",
                "label" => "Şifre yenileme Başlık"
            ),
            array(
                "name" => "sifre_yenileme_sifre_input",
                "label" => "Şifre alanı"
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_input",
                "label" => "Şifre Tekrarı alanı"
            ),
            array(
                "name" => "sifre_yenileme_buton",
                "label" => "Şifre Yenileme Buton"
            ),
            array(
                "name" => "sifre_yenileme_linkin_kullanim_suresi_dolmus",
                "label" => "Şifre yenileme linkinin süresi dolmuş."
            ),
            array(
                "name" => "sifre_yenileme_sifreniz_bos_olamaz",
                "label" => "Şifreniz boş olamaz."
            ),
            array(
                "name" => "sifre_yenileme_sifreniz_en_az_8_karakter_olmalidir",
                "label" => "Şifreniz en az 8 karakter olmaldır."
            ),
            array(
                "name" => "sifre_yenileme_sifreniz_en_fazla_50_karakter_olmalidir",
                "label" => "Şifreniz en fazla 50 karakter olmaldır."
            ),
            array(
                "name" => "sifre_yenileme_sifreniz_en_az_1_rakam",
                "label" => "Şifreniz en az 1 rakam içermek zorundadır."
            ),
            array(
                "name" => "sifre_yenileme_sifreniz_en_az_1_kucuk_harf",
                "label" => "Şifreniz en az 1 küçük harf içermek zorundadır."
            ),
            array(
                "name" => "sifre_yenileme_sifreniz_en_az_1_buyuk_harf",
                "label" => "Şifreniz en az 1 büyük harf içermek zorundadır."
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_bos_olamaz",
                "label" => "Şifre Tekrarı boş olamaz."
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_en_az_8_karakter_olmalidir",
                "label" => "Şifre Tekrarı en az 8 karakter olmaldır."
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_en_fazla_50_karakter_olmalidir",
                "label" => "Şifre Tekrarı en fazla 50 karakter olmaldır."
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_en_az_1_rakam",
                "label" => "Şifre Tekrarı en az 1 rakam içermek zorundadır."
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_en_az_1_kucuk_harf",
                "label" => "Şifre Tekrarı en az 1 küçük harf içermek zorundadır."
            ),
            array(
                "name" => "sifre_yenileme_sifre_tekrari_en_az_1_buyuk_harf",
                "label" => "Şifre Tekrarı en az 1 büyük harf içermek zorundadır."
            ),
            array(
                "name" => "sifre_yenileme_sifre_ve_tekrari_ayni_olmalidir",
                "label" => "Şifre ve Tekrarı aynı olmalıdır.",
            ),
            array(
                "name" => "sifre_yenileme_basarili",
                "label" => "Şifre yenileme başarılı.",
            ),
            array(
                "name" => "sifre_yenileme_hatali",
                "label" => "Şifre yenileme hatalı.",
            ),
        ),
    ),
);
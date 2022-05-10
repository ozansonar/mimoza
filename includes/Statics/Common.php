<?php

// User types
$systemAdminUserType = [
	0 => [
		"form_text" => "Tanımsız Üye Tipi",
		"view_text" => "Tanımsız Üye Tipi"
	],
	1 => [
		"form_text" => "Normal Kullanıcı",
		"view_text" => "Normal Kullanıcı"
	],
	60 => [
		"form_text" => "Admin",
		"view_text" => "Admin"
	],
	90 => [
		"form_text" => "Süper Admin",
		"view_text" => "Süper Admin"
	],
];

// sistemde onaylı onaysız bekliyor kısımları
$systemStatus = [
	1 => [
		"form_text" => "Onaylı",
        "view_text" => "Onaylandı",
        "view_class" => "badge badge-success",
	],
	2 => [
        "form_text" => "Onaysız",
        "view_text" => "Onaylanmadı",
        "view_class" => "badge badge-danger",
	],
	3 => [
		"form_text" => "Bekliyor",
		"view_text" => "Beklemede",
		"view_class" => "badge badge-info",
	],
];

$systemStatusVersion = [
	3 => "Bekliyor",
	1 => "Onaylı",
	2 => "Onaysız",
];

$adminPanelTheme = [
	1 => "Koyu",
	2 => "Açık",
];

//mailing durumları
$mailingSendStatus = [
	3 => [
		"view_text" => "Bekliyor",
		"view_class" => "badge badge-info",
	],
	2 => [
		"view_text" => "Tamamlanamadı",
		"view_class" => "badge badge-danger",
	],
	1 => [
		"view_text" => "Tamamlandı",
		"view_class" => "badge badge-success",
	],
];

$systemMenuTypes = [
	1 => [
		"view_text" => "Ana Menü",
		"form_text" => "Ana Menü",
	],
	2 => [
		"view_text" => "Alt Menü",
		"form_text" => "Alt Menü",
	],
];

$systemMenuTypesVersion2 = [
	1 => "Ana Menü",
	2 => "Alt Menü",
];

$menuShowType = [
	1 => "Header",
	2 => "Footer",
	3 => "Header-Footer",
];

$moneyTypes = [
	1 => array(
		"short" => "TL",
		"long" => "Türk Lirası",
		"icon" => "₺",
		"fa-icon" => "fas fa-lira-sign"
	),
	2 => array(
		"short" => "USD",
		"long" => "Amerikan Doları",
		"icon" => "$",
		"fa-icon" => "fa-dollar-sign"
	),
	3 => array(
		"short" => "EU",
		"long" => "Euro",
		"icon" => "€",
		"fa-icon" => "fas fa-euro-sign",
	),
];

$moneyTypes2 = [
	1 => "TL",
	2 => "USD",
	3 => "Euro",
];

$contactOptions = [
	1 => [
		"text" => "WhatsApp",
	],
	2 => [
		"text" => "Viber",
	]
];

$socialMedia = [
	"google" => [
		"title" => "Google+",
		"url" => "https://plus.google.com/",
	],
	"facebook" => [
		"title" => "Facebook",
		"url" => "https://www.facebook.com/",
	],
	"twitter" => [
		"title" => "Twitter",
		"url" => "https://twitter.com/",
	],
	"instagram" => [
		"title" => "İnstagram",
		"url" => "https://www.instagram.com/",
	],
	"youtube" => [
		"title" => "YouTube",
		"url" => "https://www.youtube.com/channel/",
	],
	"linkedin" => [
		"title" => "LinkedIn",
		"url" => "https://www.linkedin.com/in/",
	],
	"vk" => [
		"title" => "Vk",
		"url" => "https://vk.com/",
	],
	"telegram" => [
		"title" => "Telegram",
		"url" => "https://telegram.me/",
	],
	"whatsapp" => [
		"title" => "WhatsApp",
		"url" => "https://api.whatsapp.com/send?phone=90",
	],
];

$informationLanguages = [
	1 => [
		"text" => "Türkçe",
	],
	2 => [
		"text" => "İngilizce",
	],
	3 => [
		"text" => "Rusça",
	],
	4 => [
		"text" => "Almanca",
	],
	5 => [
		"text" => "Lehçe",
	],
];

$systemMenuShowType = [
	1 => [
		"form_text" => "Üst Menü",
		"view_text" => "Üst Menü",
	],
	2 => [
		"form_text" => "Alt Menü",
		"view_text" => "Alt Menü",
	],
];

//site modları
$systemSiteMod = [
	1 => [
		"form_text" => "Evet",
	],
	2 => [
		"form_text" => "Hayır",
	]
];

$systemSiteModVersion2 = [
	1 => "Evet",
	2 => "Hayır",
];

//smtp secure
$smtpSecureType = [
	1 => "SLL",
	2 => "TLS"
];

//smtp send mode
$smtpSendMode = [
	1 => "Normal mod (sistem de gitmesi gereken kişilere mail gönderir canlı mod)",
	2 => "Mailler belirli bir adrese gitsin  (developer/test mod)"
];

//kullanıcı sistem mailleri istiyor mu
$systemUserMailSend = [
	1 => [
		"form_text" => "Evet",
	],
	2 => [
		"form_text" => "Hayır",
	],
];

// Gallery
$systemGalleryTypes = [
	1 => [
		"form_text" => "Ana Galeri",
		"view_text" => "Ana Galeri",
	],
	2 => [
		"form_text" => "Alt Galeri",
		"view_text" => "Alt Galeri",
	],
];

$systemGalleryTypesVersion2 = [
	1 => "Ana Galeri",
	2 => "Alt Galeri",
];

// Content categories show types
$systemContentCategoriesShowTypes = [
	1 => [
		"form_text" => "Satır Satır",
		"view_text" => "Satır Satır",
	],
	2 => [
		"form_text" => "Kutu Kutu",
		"view_text" => "Kutu Kutu",
	],

];

$systemContentCategoriesShowTypes2 = [
	1 => "Satır Satır",
	2 => "Kutu Kutu",
];

// Yes - No option in select
$systemYesAndNoText = [
	1 => [
		"form_text" => "Evet"
	],
	2 => [
		"form_text" => "Hayır"
	],
];

$systemYesAndNoVersion2 = [
	1 => "Evet",
	2 => "Hayır"
];

// Mounts to text
$mountText = [
	1 => [
		"text" => "Birinci",
	],
	2 => [
		"text" => "İkinci",
	],
	3 => [
		"text" => "Üçüncü",
	],
	4 => [
		"text" => "Dördüncü",
	],
	5 => [
		"text" => "Beşinci",
	],
	6 => [
		"text" => "Altıncı",
	],
	7 => [
		"text" => "Yedinci",
	],
	8 => [
		"text" => "Sekizinci",
	],
	9 => [
		"text" => "Dokuzuncu",
	],
	10 => [
		"text" => "Onuncu",
	],
	11 => [
		"text" => "Onbirinci",
	],
	12 => [
		"text" => "Onikinci",
	],
];

//slider yükleme tipleri
$sliderTypes = [
	"1" => "Sadece Resim",
	"2" => "Resim ve Link",
	"3" => "Resim ve Dış Link",
	"7" => "Yazı ve Resim",
	"8" => "Yazı,Resim ve Link",
	"9" => "Yazı,Resim ve Dış Link",
	"10" => "Resim ve İç Link",
];


// File extensions controls
$extensionsControl = [
	"img_and_pdf" => [
		"png",
		"jpg",
		"pdf",
	],
	"word" => [
		"docx",
		"doc"
	],
	"pdf" => [
		'pdf',
	],
	"pdf_and_word" => [
		"docx",
		"doc",
		"pdf"
	],
];

// Subscription status
$subscriptionStatus = [
	1 => [
		"label" => "Ödendi",
		"class" => "badge bg-success",
	],
	2 => [
		"label" => "Ödenmedi",
		"class" => "badge bg-danger",
	],
];

// File uploads paths by context. If you want to add some additional path. Please add a path into this array
$fileTypePath = [
	"default" => [
		"aciklama" => "Varsayılan yükleme konumu",
		"folder" => "default",
		"full_path" => $functions->root_url("uploads/default/"),
		"url" => $functions->site_url("uploads/default/"),
		"url_compressed" => $functions->site_url("uploads/default/compressed/"),
		"compressed" => $functions->root_url("uploads/default/compressed/"),
	],
	"user_image" => [
		"aciklama" => "kullanıcıların resimleri",
		"folder" => "user",
		"full_path" => $functions->root_url("uploads/user/"),
		"url" => $functions->site_url("uploads/user/"),
		"url_compressed" => $functions->site_url("uploads/user/compressed/"),
		"compressed" => $functions->root_url("uploads/user/compressed/"),
	],
	"page_image" => [
		"aciklama" => "sayfaların resimleri",
		"folder" => "page",
		"full_path" => $functions->root_url("uploads/page/"),
		"url" => $functions->site_url("uploads/page/"),
		"url_compressed" => $functions->site_url("uploads/page/compressed/"),
		"compressed" => $functions->root_url("uploads/page/compressed/"),
	],
	"content_categories" => [
		"aciklama" => "Kategori Resimleri",
		"folder" => "content_categories",
		"full_path" => $functions->root_url("uploads/content_categories/"),
		"url" => $functions->site_url("uploads/content_categories/"),
		"url_compressed" => $functions->site_url("uploads/content_categories/compressed/"),
		"compressed" => $functions->root_url("uploads/content_categories/compressed/"),
	],
	"content" => [
		"aciklama" => "İçerik Resimleri",
		"folder" => "content",
		"full_path" => $functions->root_url("uploads/content/"),
		"url" => $functions->site_url("uploads/content/"),
		"url_compressed" => $functions->site_url("uploads/content/compressed/"),
		"compressed" => $functions->root_url("uploads/content/compressed/"),
	],
	"slider" => [
		"aciklama" => "Slider Resimleri",
		"folder" => "slider",
		"full_path" => $functions->root_url("uploads/slider/"),
		"url" => $functions->site_url("uploads/slider/"),
		"url_compressed" => $functions->site_url("uploads/slider/compressed/"),
		"compressed" => $functions->root_url("uploads/slider/compressed/"),
	],
	"project_image" => [
		"aciklama" => "Proje Resimleri Bulunur",
		"folder" => "project_image",
		"full_path" => $functions->root_url("uploads/project_image/"),
		"url" => $functions->site_url("uploads/project_image/"),
		"url_compressed" => $functions->site_url("uploads/project_image/compressed/"),
		"compressed" => $functions->root_url("uploads/project_image/compressed/"),
	],
	"gallery" => [
		"aciklama" => "Geleri Resimleri Bulunur",
		"folder" => "gallery",
		"full_path" => $functions->root_url("uploads/gallery/"),
		"url" => $functions->site_url("uploads/gallery/"),
		"url_compressed" => $functions->site_url("uploads/gallery/compressed/"),
		"compressed" => $functions->root_url("uploads/gallery/compressed/"),
	],
	"mailing" => [
		"aciklama" => "Mailing Resimleri Bulunur",
		"folder" => "mailing",
		"full_path" => $functions->root_url("uploads/mailing/"),
		"url" => $functions->site_url("uploads/mailing/"),
		"url_compressed" => $functions->site_url("uploads/mailing/compressed/"),
		"compressed" => $functions->root_url("uploads/mailing/compressed/"),
	],
	"mailing_attachment" => [
		"aciklama" => "Mailing Ekleri Bulunur",
		"folder" => "mailing_attachment",
		"full_path" => $functions->root_url("uploads/mailing_attachment/"),
		"url" => $functions->site_url("uploads/mailing_attachment/"),
		"url_compressed" => $functions->site_url("uploads/mailing_attachment/compressed/"),
		"compressed" => $functions->root_url("uploads/mailing_attachment/compressed/"),
	],
];

//sistemde kullanıclan keyler
$addPermissionKey = "a";
$deletePermissionKey = "d";
$editPermissionKey = "e";
$listPermissionKey = "s";
$detailPermissionKey = "de";
$actionPermissionKey = "i";


$months = [
	"tr" => [
		"long" => [
			"01" => "Ocak",
			"02" => "Şubat",
			"03" => "Mart",
			"04" => "Nisan",
			"05" => "Mayıs",
			"06" => "Haziran",
			"07" => "Temmuz",
			"08" => "Ağustos",
			"09" => "Eylül",
			"10" => "Ekim",
			"11" => "Kasım",
			"12" => "Aralık"
		],
		"short" => [
			"01" => "Oca",
			"02" => "Şub",
			"03" => "Mar",
			"04" => "Nis",
			"05" => "May",
			"06" => "Haz",
			"07" => "Tem",
			"08" => "Ağu",
			"09" => "Eyl",
			"10" => "Eki",
			"11" => "Kas",
			"12" => "Ara"
		],
	],
	"ru" => [
		"long" => [
			"01" => "Январь",
			"02" => "Февраль",
			"03" => "Март",
			"04" => "Апрель",
			"05" => "Май",
			"06" => "Июнь",
			"07" => "Июль",
			"08" => "Август",
			"09" => "Сентябрь",
			"10" => "Октябрь",
			"11" => "Ноябрь",
			"12" => "Декабрь"
		],
		"short" => [
			"01" => "янв",
			"02" => "февр",
			"03" => "мар",
			"04" => "апр",
			"05" => "мая ",
			"06" => "июн",
			"07" => "июл",
			"08" => "авг",
			"09" => "сент",
			"10" => "окт",
			"11" => "нояб",
			"12" => "дек"
		],
	],
];

//html purifier tarafından bu arrayda bulunan keyler taranamayacak
$allowedSpecialHtmlPost = ["text", "adres", "contact_despription"];
//çoklu dildeki keyleri bu şekilde ekleyelim
foreach ($langData as $langRow) {
    $allowedSpecialHtmlPost[] = "text_" . $langRow->short_lang;
    $allowedSpecialHtmlPost[] = "abstract_" . $langRow->short_lang;
    $allowedSpecialHtmlPost[] = "site_bakimda_aciklama_" . $langRow->short_lang;
    $allowedSpecialHtmlPost[] = "404_page_text_" . $langRow->short_lang;
}

// sayfa linkleme yaparken bu adresler listelenmesin
$pageLinkNoListController = [
	"admin",
	"ajax",
	"contact-api",
	"forgot-password",
	"giris",
	"hesap-dogrulama",
	"logout",
	"profil",
	"sifremi-unuttum",
	"site-bakimda",
	"iletisim"
];

$systemLinkPrefix = [
    "content_prefix_" => [
        "title" => "İçerik Linki",
        "controller" => "content",
    ],
    "search_prefix_" => [
        "title" => "Arama Linki",
        "controller" => "arama",
    ],
    "slider_prefix_" => [
        "title" => "Slider Linki",
        "controller" => "slider"
    ],
    "iletisim_prefix_" => [
        "title" => "İletişim Linki",
        "controller" => "iletisim",
    ],
    "profile_prefix_" => [
        "title" => "Profil Linki",
        "controller" => "profil",
    ],
    "giris_prefix_" => [
        "title" => "Giriş Linki",
        "controller" => "giris",
    ],
    "sifremi_unutum_prefix_" => [
        "title" => "Şifremi unuttum",
        "controller" => "sifremi-unuttum",
    ],
    "sifre_yenile_prefix_" => [
        "title" => "Şifre Yenileme Linki",
        "controller" => "sifre-yenile",
    ],
    "uye_ol_prefix_" => [
        "title" => "Üye Olma",
        "controller" => "sign-up",
    ],
    "hesap_dogrulama_prefix_" => [
        "title" => "Hesap Doğrulama",
        "controller" =>  "account-activate",
    ],
];
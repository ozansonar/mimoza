<?php

namespace Includes\Project;

class Constants
{

	public const systemAdminUserType = [
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
	public const systemStatus = [
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
	public const systemStatusVersion = [
		3 => "Bekliyor",
		1 => "Onaylı",
		2 => "Onaysız",
	];
	public const adminPanelTheme = [
		1 => "Koyu",
		2 => "Açık",
	];
	public const mailingSendStatus = [
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
	public const systemMenuTypes = [
		1 => [
			"view_text" => "Ana Menü",
			"form_text" => "Ana Menü",
		],
		2 => [
			"view_text" => "Alt Menü",
			"form_text" => "Alt Menü",
		],
	];
	public const systemMenuTypesVersion2 = [
		1 => "Ana Menü",
		2 => "Alt Menü",
	];
	public const menuShowType = [
		1 => "Header",
		2 => "Footer",
		3 => "Header-Footer",
	];
	public const socialMedia = [
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
	public const systemSiteMod = [
		1 => [
			"form_text" => "Evet",
		],
		2 => [
			"form_text" => "Hayır",
		]
	];
	public const systemSiteModVersion2 = [
		1 => "Evet",
		2 => "Hayır",
	];
	public const smtpSecureType = [
		'ssl' => "SSL",
		'tls' => "TLS"
	];
	public const smtpSendMode = [
		1 => "Normal mod (sistem de gitmesi gereken kişilere mail gönderir canlı mod)",
		2 => "Mailler belirli bir adrese gitsin  (developer/test mod)"
	];
	public const systemGalleryTypes = [
		1 => [
			"form_text" => "Ana Galeri",
			"view_text" => "Ana Galeri",
		],
		2 => [
			"form_text" => "Alt Galeri",
			"view_text" => "Alt Galeri",
		],
	];
	public const systemGalleryTypesVersion2 = [
		1 => "Ana Galeri",
		2 => "Alt Galeri",
	];
	public const systemContentCategoriesShowTypes = [
		1 => [
			"form_text" => "Satır Satır",
			"view_text" => "Satır Satır",
		],
		2 => [
			"form_text" => "Kutu Kutu",
			"view_text" => "Kutu Kutu",
		],

	];
	public const systemContentCategoriesShowTypes2 = [
		1 => "Satır Satır",
		2 => "Kutu Kutu",
	];
	public const systemYesAndNoText = [
		1 => [
			"form_text" => "Evet"
		],
		2 => [
			"form_text" => "Hayır"
		],
	];
    public const systemLanguageRoute = [
        1 => "Evet",
        2 => "Hayır",
        3 => "Sadece varsayılan dilde linklerde dil kısaltması olmasın"
    ];

	public const fileTypePath = [
		"default" => [
			"aciklama" => "Varsayılan yükleme konumu",
			"folder" => "default",
			"full_path" => ROOT_PATH . '/uploads/default/',
			"url" => SITE_URL . '/uploads/default/',
			"url_compressed" => SITE_URL . '/uploads/default/compressed/',
			"compressed" => ROOT_PATH . "/uploads/default/compressed/",
		],
		"user_image" => [
			"aciklama" => "kullanıcıların resimleri",
			"folder" => "user",
			"full_path" => ROOT_PATH . "/uploads/user/",
			"url" => SITE_URL . "/uploads/user/",
			"url_compressed" => SITE_URL . "/uploads/user/compressed/",
			"compressed" => ROOT_PATH . "/uploads/user/compressed/",
		],
		"page_image" => [
			"aciklama" => "sayfaların resimleri",
			"folder" => "page",
			"full_path" => ROOT_PATH . "/uploads/page/",
			"url" => SITE_URL . "/uploads/page/",
			"url_compressed" => SITE_URL . "/uploads/page/compressed/",
			"compressed" => ROOT_PATH . "/uploads/page/compressed/",
		],
		"content_categories" => [
			"aciklama" => "Kategori Resimleri",
			"folder" => "content_categories",
			"full_path" => ROOT_PATH . "/uploads/content_categories/",
			"url" => SITE_URL . "/uploads/content_categories/",
			"url_compressed" => SITE_URL . "/uploads/content_categories/compressed/",
			"compressed" => ROOT_PATH . "/uploads/content_categories/compressed/",
		],
		"content" => [
			"aciklama" => "İçerik Resimleri",
			"folder" => "content",
			"full_path" => ROOT_PATH . "/uploads/content/",
			"url" => SITE_URL . "/uploads/content/",
			"url_compressed" => SITE_URL . "/uploads/content/compressed/",
			"compressed" => ROOT_PATH . "/uploads/content/compressed/",
		],
		"slider" => [
			"aciklama" => "Slider Resimleri",
			"folder" => "slider",
			"full_path" => ROOT_PATH . "/uploads/slider/",
			"url" => SITE_URL . "/uploads/slider/",
			"url_compressed" => SITE_URL . "/uploads/slider/compressed/",
			"compressed" => ROOT_PATH . "/uploads/slider/compressed/",
		],
		"project_image" => [
			"aciklama" => "Proje Resimleri Bulunur",
			"folder" => "project_image",
			"full_path" => ROOT_PATH . "/uploads/project_image/",
			"url" => SITE_URL . "/uploads/project_image/",
			"url_compressed" => SITE_URL . "/uploads/project_image/compressed/",
			"compressed" => ROOT_PATH . "/uploads/project_image/compressed/",
		],
		"gallery" => [
			"aciklama" => "Geleri Resimleri Bulunur",
			"folder" => "gallery",
			"full_path" => ROOT_PATH . "/uploads/gallery/",
			"url" => SITE_URL . "/uploads/gallery/",
			"url_compressed" => SITE_URL . "/uploads/gallery/compressed/",
			"compressed" => ROOT_PATH . "/uploads/gallery/compressed/",
		],
		"mailing" => [
			"aciklama" => "Mailing Resimleri Bulunur",
			"folder" => "mailing",
			"full_path" => ROOT_PATH . "/uploads/mailing/",
			"url" => SITE_URL . "/uploads/mailing/",
			"url_compressed" => SITE_URL . "/uploads/mailing/compressed/",
			"compressed" => ROOT_PATH . "/uploads/mailing/compressed/",
		],
		"mailing_attachment" => [
			"aciklama" => "Mailing Ekleri Bulunur",
			"folder" => "mailing_attachment",
			"full_path" => ROOT_PATH . "/uploads/mailing_attachment/",
			"url" => SITE_URL . "/uploads/mailing_attachment/",
			"url_compressed" => SITE_URL . "/uploads/mailing_attachment/compressed/",
			"compressed" => ROOT_PATH . "/uploads/mailing_attachment/compressed/",
		],
	];
	public const addPermissionKey = "a";
	public const deletePermissionKey = "d";
	public const editPermissionKey = "e";
	public const listPermissionKey = "s";
	public const detailPermissionKey = "de";
	public const months = [
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
	];
	public const pageLinkNoListController = [
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
	public const systemLinkPrefix = [
		"content_prefix_" => [
			"title" => "İçerik Linki",
			"controller" => "content",
		],
		"search_prefix_" => [
			"title" => "Arama Linki",
			"controller" => "search",
		],
		"slider_prefix_" => [
			"title" => "Slider Linki",
			"controller" => "slider"
		],
		"iletisim_prefix_" => [
			"title" => "İletişim Linki",
			"controller" => "contact",
		],
		"profile_prefix_" => [
			"title" => "Profil Linki",
			"controller" => "profile",
		],
		"giris_prefix_" => [
			"title" => "Giriş Linki",
			"controller" => "login",
		],
		"sifremi_unutum_prefix_" => [
			"title" => "Şifremi unuttum",
			"controller" => "forgot-password",
		],
		"sifre_yenile_prefix_" => [
			"title" => "Şifre Yenileme Linki",
			"controller" => "password-reset",
		],
		"uye_ol_prefix_" => [
			"title" => "Üye Olma",
			"controller" => "sign-up",
		],
		"hesap_dogrulama_prefix_" => [
			"title" => "Hesap Doğrulama",
			"controller" => "account-activate",
		],
	];

    public const commentType = [
        1 => [
            'table' => 'content',
            'title' => 'İçerik',
        ]
    ];
}
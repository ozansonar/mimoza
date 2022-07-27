<?php
global $menu;

$menu = [
	[
		'url' => 'index',
		'title' => 'Anasayfa',
		'icon' => 'nav-icon fas fa-home',
		'permissions' => [
			's' => 'Görüntüleme'
		],
	],
	[
		'url' => '../',
		'title' => 'Siteye Dön',
		'icon' => 'nav-icon fas fa-paper-plane',
		'permissions' => [
			's' => 'Görüntüleme'
		],
		'target' => 'blank',
	],
	[
		'url' => 'top-menu',
		'title' => 'Menü İşlemleri',
		'icon' => 'nav-icon fas fa-bars',
		'submenu' => [
			[
				'url' => 'menu',
				'title' => 'Menüler',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'menu-settings',
				'title' => 'Menü Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'top-slider',
		'title' => 'Slider İşlemleri',
		'icon' => 'nav-icon fas fa-sliders-h',
		'submenu' => [
			[
				'url' => 'slider',
				'title' => 'Sliderlar',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'slider-settings',
				'title' => 'Slider Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			],

		],
	],
	[
		'url' => 'top-content-categories',
		'title' => 'İçerik İşlemleri',
		'icon' => 'nav-icon fas fa-book-open',
		'submenu' => [
			[
				'url' => 'content-categories',
				'title' => 'İçerik Kategorileri',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'content-categories-settings',
				'title' => 'İçerik Kategori Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			],
			[
				'url' => 'content',
				'title' => 'İçerikler',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'content-settings',
				'title' => 'İçerik Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			],
		],
	],
	[
		'url' => 'top-page',
		'title' => 'Sayfa İşlemleri',
		'icon' => 'nav-icon fas fa-file',
		'submenu' => [
			[
				'url' => 'page',
				'title' => 'Sayfalar',
				'icon' => 'nav-icon fas fa-tree',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'page-settings',
				'title' => 'Sayfa Ekle',
				'icon' => 'nav-icon fas fa-tree',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'top-user',
		'title' => 'Kullanıcı İşlemleri',
		'icon' => 'nav-icon fas fa-users',
		'submenu' => [
			[
				'url' => 'account-settings',
				'title' => 'Hesap Ayarlarım',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
				],
			],
			[
				'url' => 'user',
				'title' => 'Kullanıcılar',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'user-settings',
				'title' => 'Kullanıcı Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			],
		],
	],
	[
		'url' => 'top-page-link',
		'title' => 'Sayfa Link İşlemleri',
		'icon' => 'nav-icon fas fa-link',
		'submenu' => [
			[
				'url' => 'page-link',
				'title' => 'Sayfalar Linkleri',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'page-link-settings',
				'title' => 'Sayfa Link Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'top-roles',
		'title' => 'Yetki Rolleri',
		'icon' => 'nav-icon fas fa-user-tag',
		'submenu' => [
			[
				'url' => 'roles',
				'title' => 'Yetki Tipleri',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'roles-settings',
				'title' => 'Yeni Rol Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'top-gallery',
		'title' => 'Resim Galerisi',
		'icon' => 'nav-icon fas fa-images',
		'submenu' => [
			[
				'url' => 'gallery',
				'title' => 'Galeriler',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'gallery-settings',
				'title' => 'Yeni Galeri Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'top-lang',
		'title' => 'Dil İşlemleri',
		'icon' => 'nav-icon fas fa-globe',
		'submenu' => [
			[
				'url' => 'lang',
				'title' => 'Diller',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'lang-settings',
				'title' => 'Yeni Dil Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	], [
		'url' => 'top-eposta',
		'title' => 'E-posta Tema İşlemleri',
		'icon' => 'nav-icon fas fa-envelope',
		'submenu' => [
			[
				'url' => 'email-themes',
				'title' => 'E-posta Temaları',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
				],
			],
			[
				'url' => 'email-theme-settings',
				'title' => 'Yeni E-posta Teması Ekle',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'top-mail',
		'title' => 'Mail İşlemleri',
		'icon' => 'nav-icon fas fa-envelope',
		'submenu' => [
			[
				'url' => 'mailler',
				'title' => 'Gönderilmiş Mailler',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					's' => 'Görüntüleme',
					'e' => 'Düzenleme',
					'd' => 'Silme',
					'de' => 'Detay',
					'mail_send' => 'Mail Gönderme',
				],
			],
			[
				'url' => 'mail-settings',
				'title' => 'Yeni Mail Gönder',
				'icon' => 'far fa-circle nav-icon',
				'permissions' => [
					'a' => 'Ekleme',
				],
			]
		],
	],
	[
		'url' => 'contact',
		'title' => 'İletişim Mesajları',
		'icon' => 'nav-icon fas fa-mail-bulk',
		'permissions' => [
			's' => 'Görüntüleme',
			'e' => 'Düzenleme',
			'send' => 'Gönderme',
			'd' => 'Silme'
		]
	],
	[
		'url' => 'language-text-setting',
		'title' => 'Dil Yazı Ayarları',
		'icon' => 'nav-icon fas fa-language',
		'permissions' => [
			's' => 'Görüntüleme',
			'e' => 'Düzenleme'
		]
	],
	[
		'url' => 'settings',
		'title' => 'Ayarlar',
		'icon' => 'nav-icon fas fa-cogs',
		'permissions' => [
			's' => 'Görüntüleme',
			'e' => 'Düzenleme'
		]
	]
];
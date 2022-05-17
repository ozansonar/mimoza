SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `message` mediumtext NOT NULL,
  `read_user` tinyint(1) NOT NULL DEFAULT '0',
  `read_user_id` int(11) DEFAULT '0',
  `read_date` datetime DEFAULT NULL,
  `reply_subject` varchar(255) DEFAULT NULL,
  `reply_text` mediumtext,
  `reply_send_date` datetime DEFAULT NULL,
  `reply_send_user_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `abstract` text,
  `img` varchar(250) DEFAULT NULL,
  `index_show` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` int(11) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text,
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `show_count` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `content_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `show_type` int(11) DEFAULT '0' COMMENT 'gösterim_sekli',
  `show_order` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `email_template` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `not_text` text,
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `email_template` (`id`, `subject`, `text`, `not_text`, `lang`, `lang_id`, `user_id`, `status`, `deleted`) VALUES
(1, 'İletişim', '<p>#project_name# iletişim formundan yeni bir mesajınız var.</p>\r\n\r\n<p>#message#</p>', 'İletişim formundan atılan mesajlar', 'tr', '20211001211101', 66, 1, 0),
(2, '', '', '', 'en', '20211001211101', 66, 0, 0),
(4, 'Şifre Yenileme', '<p>Şifrenizi sıfırlamak için lütfen <a href=\"#link#\" target=\"_blank\">tıklayınız.</a></p>\r\n\r\n<p>NOT: Şifre sıfırlama linki 1 gün geçerlidir.</p>', 'Şifre sıfırlama isteğinde bulunulduğunda giden mail', 'tr', '20211011205227', 66, 1, 0),
(5, '', '', '', 'en', '20211011205227', 66, 0, 0);
CREATE TABLE `file_url` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `lang` varchar(10) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `file_url` (`id`, `url`, `controller`, `lang`, `user_id`, `status`, `deleted`) VALUES
(1, 'icerik', 'content', 'tr', 66, 1, 0),
(2, 'arama', 'search', 'tr', 66, 1, 0),
(3, 'slider', 'slider', 'tr', 66, 1, 0),
(4, 'iletisim', 'contact', 'tr', 66, 1, 0),
(5, 'profil', 'profile', 'tr', 66, 1, 0),
(6, 'profile', 'profile', 'en', 66, 1, 0),
(10, 'contact', 'contact', 'en', 66, 1, 0),
(13, 'giris', 'login', 'tr', 66, 1, 0),
(15, 'sifremi-unuttum', 'forgot-password', 'tr', 66, 1, 0),
(16, 'forgot-password', 'forgot-password', 'en', 66, 1, 0),
(18, 'sifre-yenile', 'password-reset', 'tr', 66, 1, 0),
(19, 'reset-password', 'password-reset', 'en', 66, 1, 0),
(21, 'login', 'giris', 'en', 81, 1, 0),
(22, 'kayit-ol', 'sign-up', 'tr', 81, 1, 0),
(23, 'register', 'sign-up', 'en', 81, 1, 0),
(24, 'hesap-dogrulama', 'account-activate', 'tr', 81, 1, 0),
(25, 'account-authentication', 'account-activate', 'en', 81, 1, 0);
CREATE TABLE `forgot_password` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hash` text,
  `deleted` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `top_id` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `gallery_image` (
  `id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `lang` (
  `id` int(11) NOT NULL,
  `lang` varchar(50) NOT NULL,
  `short_lang` varchar(10) NOT NULL,
  `lang_iso` varchar(10) DEFAULT NULL,
  `default_lang` tinyint(1) NOT NULL DEFAULT '2',
  `form_validate` tinyint(1) DEFAULT '2',
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(5) DEFAULT NULL,
  `log_type` int(5) NOT NULL,
  `log_datetime` datetime NOT NULL,
  `client_ip` varchar(15) NOT NULL,
  `log_browser` varchar(90) DEFAULT NULL,
  `log_os` varchar(90) DEFAULT NULL,
  `log_page` varchar(100) DEFAULT NULL,
  `log_query_string` varchar(255) DEFAULT NULL,
  `log_http_user_agent` varchar(255) DEFAULT NULL,
  `log_detail` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE `log_types` (
  `id` int(11) NOT NULL,
  `log_key` varchar(100) NOT NULL,
  `log_val` int(9) NOT NULL,
  `log_desc` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
INSERT INTO `log_types` (`id`, `log_key`, `log_val`, `log_desc`) VALUES
(287, 'INDEX', 1, ''),
(288, 'PAGE_NOT_FOUND', 2, ''),
(289, 'ADMIN', 3, ''),
(290, 'SEARCH', 4, ''),
(291, 'CONTACT_API_SEND', 5, ''),
(292, 'CONTACT_API_SEND_SUCCESS', 6, ''),
(293, 'CONTACT_API_SEND_ERR', 7, ''),
(294, 'İletişim Post', 8, ''),
(295, 'COTENT_LIST', 9, ''),
(296, 'TAG', 10, ''),
(297, 'GALLERY', 11, ''),
(298, 'CONTACT', 12, ''),
(299, 'PAGE', 13, ''),
(300, 'SITE_BAKIMDA', 14, ''),
(301, 'SLIDER', 15, ''),
(302, 'CONTENT_DETAIL', 16, ''),
(303, 'IZINSIZ_ERISIM_ISTEGI', 17, ''),
(304, 'SLIDER_LIST', 18, ''),
(305, 'SLIDER_DELETE_SUCC', 19, ''),
(306, 'SLIDER_DELETE_ERR', 20, ''),
(307, 'SLIDER_DETAIL', 21, ''),
(308, 'SLIDER_EDIT_SUCC', 22, ''),
(309, 'SLIDER_EDIT_ERR', 23, ''),
(310, 'SLIDER_ADD_SUCC', 24, ''),
(311, 'SLIDER_ADD_ERR', 25, ''),
(312, 'CONTENT_CATEGORIES_LIST', 26, ''),
(313, 'CONTENT_CATEGORIES_DEL_SUCC', 27, ''),
(314, 'CONTENT_CATEGORIES_DEL_ERR', 28, ''),
(315, 'CONTENT_CATEGORIES_DETAIL', 29, ''),
(316, 'CONTENT_CATEGORIES_EDIT_SUCC', 30, ''),
(317, 'CONTENT_CATEGORIES_EDIT_ERR', 31, ''),
(318, 'CONTENT_CATEGORIES_ADD_SUCC', 32, ''),
(319, 'CONTENT_CATEGORIES_ADD_ERR', 33, ''),
(320, 'CONTENT_LIST', 34, ''),
(321, 'CONTENT_DEL_SUCC', 35, ''),
(322, 'CONTENT_DEL_ERR', 36, ''),
(323, 'CONTENT_DETAIL', 37, ''),
(324, 'CONTENT_EDIT_SUCC', 38, ''),
(325, 'CONTENT_EDIT_ERR', 39, ''),
(326, 'CONTENT_ADD_SUCC', 40, ''),
(327, 'CONTENT_ADD_ERR', 41, ''),
(328, 'MENU_LIST', 42, ''),
(329, 'MENU_DELETE_SUCC', 43, ''),
(330, 'MENU_DELETE_ERR', 44, ''),
(331, 'MENU_DETAIL', 45, ''),
(332, 'MENU_EDIT_SUCC', 46, ''),
(333, 'MENU_EDIT_ERR', 47, ''),
(334, 'MENU_ADD_SUCC', 48, ''),
(335, 'MENU_ADD_ERR', 49, ''),
(336, 'PAGE_LIST', 50, ''),
(337, 'PAGE_LIST_DELETE_SUCC', 51, ''),
(338, 'PAGE_LIST_DELETE_ERR', 52, ''),
(339, 'PAGE_DETAIL', 53, ''),
(340, 'PAGE_EDIT_SUCC', 54, ''),
(341, 'PAGE_EDIT_ERR', 55, ''),
(342, 'PAGE_ADD_SUCC', 56, ''),
(343, 'PAGE_ADD_ERR', 57, ''),
(344, 'ACCOUNT_DETAIL', 58, ''),
(345, 'ACCOUNT_EDIT_SUCC', 59, ''),
(346, 'ACCOUNT_EDIT_ERR', 60, ''),
(347, 'USER_DELETE_SUCC', 61, ''),
(348, 'USER_DELETE_ERR', 62, ''),
(349, 'USER_LIST', 63, ''),
(350, 'USER_DETAIL', 64, ''),
(351, 'USER_EDIT_SUCC', 65, ''),
(352, 'USER_EDIT_ERR', 66, ''),
(353, 'USER_ADD_SUCC', 67, ''),
(354, 'USER_ADD_ERR', 68, ''),
(355, 'PAGE_LINK_LIST', 69, ''),
(356, 'PAGE_LINK_DELETE_SUCC', 70, ''),
(357, 'PAGE_LINK_DELETE_ERR', 71, ''),
(358, 'PAGE_LINK_DETAIL', 72, ''),
(359, 'PAGE_LINK_EDIT_SUCC', 73, ''),
(360, 'PAGE_LINK_EDIT_ERR', 74, ''),
(361, 'PAGE_LINK_ADD_SUCC', 75, ''),
(362, 'PAGE_LINK_ADD_ERR', 76, ''),
(363, 'ROLES_LIST', 77, ''),
(364, 'ROLES_DELETE_SUCC', 78, ''),
(365, 'ROLES_DELETE_ERR', 80, ''),
(366, 'ROLES_DETAIL', 81, ''),
(367, 'ROLES_EDIT_SUCC', 82, ''),
(368, 'ROLES_EDIT_ERR', 83, ''),
(369, 'CONTACT_LIST', 84, ''),
(370, 'CONTACT_DELETE_SUCC', 85, ''),
(371, 'CONTACT_DELETE_ERR', 86, ''),
(372, 'CONTACT_DETAIL', 87, ''),
(373, 'CONTACT_CEVAP_SEND_SUCC', 88, ''),
(374, 'CONTACT_CEVAP_SEND_ERR', 89, ''),
(375, 'GALLERY_LIST', 90, ''),
(376, 'GALLERY_DELETE_SUCC', 91, ''),
(377, 'GALLERY_DELETE_ERR', 92, ''),
(378, 'GALLERY_DETAIL', 93, ''),
(379, 'GALLERY_EDIT_SUCC', 94, ''),
(380, 'GALLERY_EDIT_ERR', 95, ''),
(381, 'GALLERY_ADD_SUCC', 96, ''),
(382, 'GALLERY_ADD_ERR', 97, ''),
(383, 'GALLERY_IMAGE_UPLOAD_DETAIL', 98, ''),
(384, 'GALLERY_IMAGE_UPLOAD_DELETE_SUCC', 99, ''),
(385, 'GALLERY_IMAGE_UPLOAD_DELETE_ERR', 100, ''),
(386, 'GALLERY_IMAGE_UPLOAD_SUCC', 101, ''),
(387, 'GALLERY_IMAGE_UPLOAD_ERR', 102, ''),
(388, 'SETTINGS_DETAIL', 103, ''),
(389, 'SETTINGS_UPDATE_SUCC', 104, ''),
(390, 'SETTINGS_UPDATE_ERR', 105, ''),
(391, 'ROLES_ADD_SUCC', 105, ''),
(392, 'ROLES_ADD_ERR', 105, ''),
(393, 'STATIC_PAGE_LIST', 106, ''),
(394, 'STATIC_PAGE_DETAIL', 107, ''),
(395, 'STATIC_PAGE_EDIT_SUCC', 108, ''),
(396, 'STATIC_PAGE_EDIT_ERR', 109, ''),
(397, 'GALLERY_VIDEO_UPLOAD_DETAIL', 110, ''),
(398, 'GALLERY_VIDEO_EDIT_SUCC', 111, ''),
(399, 'GALLERY_VIDEO_EDIT_ERR', 112, ''),
(400, 'GALLERY_VIDEO_ADD_SUCC', 113, ''),
(401, 'GALLERY_VIDEO_ADD_ERR', 114, ''),
(402, 'GALLERY_VIDEO_DEL_SUCC', 115, ''),
(403, 'GALLERY_VIDEO_DEL_ERR', 116, ''),
(459, 'ADMIN_LOGOUT', 390, ''),
(460, 'ADMIN_LOGIN', 391, ''),
(461, 'ADMIN_LOGIN_PAGE', 392, ''),
(462, 'USER_TRACING', 394, ''),
(463, 'SLIDER_ADD_PAGE', 395, ''),
(464, 'PAGE_LINK_ADD_PAGE', 396, ''),
(465, 'LANG_LIST', 397, ''),
(466, 'LANG_LIST', 398, ''),
(467, 'LANG_DEL_ERR', 399, ''),
(468, 'LANG_DETAIL', 400, ''),
(469, 'LANG_ADD_PAGE', 401, ''),
(470, 'LANG_EDIT_SUCC', 402, ''),
(471, 'LANG_EDIT_ERR', 403, ''),
(472, 'LANG_ADD_SUCC', 404, ''),
(473, 'LANG_ADD_ERR', 405, ''),
(474, 'LANG_DEL_SUCC', 406, ''),
(475, 'LANGUAGE_TEXT_LIST', 407, ''),
(476, 'LANGUAGE_TEXT_UPDATE_SUCC', 408, ''),
(477, 'LANGUAGE_TEXT_UPDATE_ERR', 409, ''),
(478, 'AJAX_REQUEST', 410, ''),
(528, 'EMAIL_TEMALARI_LIST', 459, ''),
(529, 'EMAIL_TEMALARI_DEL_SUCC', 460, ''),
(530, 'EMAIL_TEMALARI_DEL_ERR', 461, ''),
(531, 'EMAIL_TEMALARI_DETAIL', 462, ''),
(532, 'EMAIL_TEMALARI_ADD_PAGE_DETAIL', 463, ''),
(533, 'EMAIL_TEMALARI_EDIT_SUCC', 464, ''),
(534, 'EMAIL_TEMALARI_EDIT_ERR', 465, ''),
(535, 'EMAIL_TEMALARI_ADD_SUCC', 466, ''),
(536, 'EMAIL_TEMALARI_ADD_ERR', 467, ''),
(546, 'USER_LOGOUT', 468, ''),
(547, 'GIRIS', 469, ''),
(548, 'SIFREMI_UNUTTUM_PAGE', 470, ''),
(549, 'SIFREMI_UNUTTUM_SUCC', 471, ''),
(550, 'SIFREMI_UNUTTUM_ERR', 472, ''),
(551, 'GIRIS_BASARILI', 473, ''),
(552, 'SIFRE_YENILE_PAGE', 474, ''),
(553, 'SIFRE_YENILE_SUCC', 475, ''),
(554, 'SIFRE_YENILE_ERR', 476, ''),
(555, 'MAILLER_LIST', 477, ''),
(556, 'MAILLER_LIST_DEL_SUCC', 478, ''),
(557, 'MAILLER_LIST_DEL_ERR', 479, ''),
(558, 'MAILLER_DETAIL', 480, ''),
(559, 'AJAX_REQUEST_ERROR', 481, '');
CREATE TABLE `mailing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'mailing oluşturan user_id',
  `subject` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `image` text,
  `attachment` text,
  `group` varchar(255) DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '3' COMMENT '3:bekliyor,1:tamamlandı:2:yarım kaldı',
  `completed_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '3:bekliyor:1:onaylı:2:onaysız',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `mailing_user` (
  `id` int(11) NOT NULL,
  `mailing_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `send` int(11) NOT NULL DEFAULT '3' COMMENT '3:bekliyor:1:gönderildi:2:gönderilemedi',
  `try_to_send` int(11) NOT NULL DEFAULT '0',
  `send_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `mail_log` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `send_type` tinyint(1) DEFAULT NULL,
  `extra_log` text,
  `sent_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `show_order` int(11) NOT NULL,
  `menu_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:ana menü 2:alt menü',
  `top_id` int(11) NOT NULL DEFAULT '0',
  `redirect` tinyint(1) NOT NULL DEFAULT '0',
  `redirect_link` varchar(255) DEFAULT NULL,
  `redirect_open_type` int(11) DEFAULT '0' COMMENT '1:yeni sekme,0:normal',
  `show_type` tinyint(1) DEFAULT NULL COMMENT '1:header,2:footer,3:header-footer',
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `abstract` text,
  `text` mediumtext,
  `img` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `show_count` tinyint(11) DEFAULT '0',
  `show_order` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `post_tags` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `role_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL COMMENT 'rol grup name',
  `status` tinyint(1) DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='kullanıcı rol grunlari';
INSERT INTO `role_groups` (`id`, `group_name`, `status`, `deleted`) VALUES
(1, 'Süper Admin', 1, 0);
CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL,
  `role_group` int(11) DEFAULT '0',
  `role_key` varchar(255) DEFAULT NULL,
  `permission` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `role_permission` (`id`, `role_group`, `role_key`, `permission`, `deleted`) VALUES
(187, 1, 'contact', 's', 0),
(188, 1, 'gallery', 's', 0),
(192, 1, 'top-gallery', 's', 0),
(194, 1, 'gallery', 'e', 0),
(195, 1, 'gallery', 'd', 0),
(196, 1, 'gallery-settings', 'a', 0),
(177, 1, 'page-link', 's', 0),
(178, 1, 'page-link', 'e', 0),
(179, 1, 'page-link', 'd', 0),
(180, 1, 'page-link-settings', 'a', 0),
(181, 1, 'roles-settings', 'a', 0),
(184, 1, 'contact', 'send', 0),
(185, 1, 'contact', 'd', 0),
(186, 1, 'contact', 'e', 0),
(160, 1, 'page', 's', 0),
(161, 1, 'top-user', 's', 0),
(162, 1, 'account-settings', 's', 0),
(164, 1, 'account-settings', 'e', 0),
(168, 1, 'user-settings', 'a', 0),
(169, 1, 'user', 's', 0),
(170, 1, 'user', 'e', 0),
(171, 1, 'user', 'd', 0),
(172, 1, 'top-page-link', 's', 0),
(128, 1, 'roles', 'd', 0),
(130, 1, '../', 's', 0),
(131, 1, 'top-slider', 's', 0),
(133, 1, 'slider', 'e', 0),
(134, 1, 'slider', 'd', 0),
(135, 1, 'slider-settings', 'a', 0),
(136, 1, 'slider', 's', 0),
(137, 1, 'top-content-categories', 's', 0),
(138, 1, 'content-categories', 's', 0),
(139, 1, 'content-categories', 'e', 0),
(140, 1, 'content-categories', 'd', 0),
(142, 1, 'content-categories-settings', 'a', 0),
(143, 1, 'content', 's', 0),
(144, 1, 'content', 'e', 0),
(145, 1, 'content', 'd', 0),
(147, 1, 'content-settings', 'a', 0),
(148, 1, 'top-menu', 's', 0),
(149, 1, 'menu', 's', 0),
(150, 1, 'menu', 'e', 0),
(151, 1, 'menu', 'd', 0),
(154, 1, 'menu-settings', 'a', 0),
(155, 1, 'top-page', 's', 0),
(157, 1, 'page', 'e', 0),
(158, 1, 'page', 'd', 0),
(159, 1, 'page-settings', 'a', 0),
(126, 1, 'roles', 'e', 0),
(123, 1, 'top-roles', 's', 0),
(124, 1, 'roles', 's', 0),
(197, 1, 'settings', 's', 0),
(200, 1, 'settings', 'e', 0),
(203, 1, 'user-tracing', 's', 0),
(295, 1, 'lang-settings', 'a', 0),
(311, 1, 'servis-settings', 'a', 1),
(296, 1, 'index', 's', 0),
(297, 1, 'gallery-image-upload', 'a', 0),
(298, 1, 'video-upload', 'a', 0),
(299, 1, 'language-text-setting', 's', 0),
(300, 1, 'language-text-setting', 'e', 0), 
(301, 1, 'top-tur', 's', 1),
(291, 1, 'lang', 'd', 0),
(292, 1, 'lang', 's', 0),
(293, 1, 'lang', 'e', 0),
(294, 1, 'top-lang', 's', 0), 
(327, 1, 'top-eposta', 's', 0),
(328, 1, 'eposta-temalari', 's', 0),
(329, 1, 'eposta-temalari', 'e', 0),
(330, 1, 'eposta-temalari', 'd', 0),
(331, 1, 'eposta-tema-settings', 'a', 0),
(332, 1, 'rezervasyonlar', 's', 1),
(333, 1, 'rezervasyonlar', 'de', 1),
(334, 1, 'top-mail', 's', 0),
(335, 1, 'mailler', 's', 0),
(336, 1, 'mailler', 'e', 0),
(337, 1, 'mailler', 'd', 0),
(338, 1, 'mail-settings', 'a', 0),
(339, 1, 'mailler', 'de', 0);
CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `ip_address` varchar(60) DEFAULT NULL,
  `x_forwarded_for` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `expire_date` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `val` mediumtext,
  `lang` varchar(5) DEFAULT NULL COMMENT 'sadece sayfalar için kullanılan yazılarda gelir ayarlarla bir alakası yok'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `settings` (`id`, `name`, `val`, `lang`) VALUES
(1, 'description', 'description,description,description', NULL),
(2, 'keywords', 'keywords,keywords1', NULL),
(3, 'title', 'Proje Başlığı', NULL),
(4, 'link_sort_lang', '1', NULL),
(5, 'site_status', '2', NULL),
(6, 'header_logo', '', NULL),
(7, 'project_name', 'Project name', NULL),
(8, 'theme', 'proje', NULL),
(9, 'logo_text', '', NULL),
(10, 'footer_text', '', NULL),
(11, 'google', '', NULL),
(12, 'facebook', '', NULL),
(13, 'twitter', '', NULL),
(14, 'instagram', '', NULL),
(15, 'youtube', '', NULL),
(16, 'linkedin', '', NULL),
(17, 'vk', '', NULL),
(18, 'telegram', '', NULL),
(19, 'whatsapp', '', NULL),
(20, 'site_mail', 'demo@demo.com', NULL),
(21, 'phone', '0000000000', NULL),
(22, 'fax', '', NULL),
(23, 'maps', '', NULL),
(24, 'adres', '', NULL),
(25, 'contact_despription', NULL, NULL),
(26, 'site_status_title', NULL, NULL),
(27, 'site_status_text', NULL, NULL),
(28, 'mail_send_mode', '0', NULL),
(29, 'smtp_host', '', NULL),
(30, 'smtp_email', '', NULL),
(31, 'smtp_password', '', NULL),
(32, 'smtp_port', '', NULL),
(33, 'smtp_secure', '1', NULL),
(34, 'smtp_send_name_surname', '', NULL),
(35, 'smtp_send_email_adres', '', NULL),
(36, 'smtp_send_email_reply_adres', '', NULL),
(37, 'smtp_mail_send_debug', '2', NULL),
(38, 'smtp_send_debug_adres', '', NULL),
(39, 'page_not_found_title', NULL, NULL),
(40, 'page_not_found_text', NULL, NULL),
(41, 'content_prefix_tr', 'icerik', NULL),
(42, 'search_prefix_tr', 'arama', NULL),
(43, 'slider_prefix_tr', 'slider', NULL),
(44, 'iletisim_prefix_tr', 'iletisim', NULL),
(45, 'content_prefix_en', 'icerik', NULL),
(46, 'search_prefix_en', 'arama', NULL),
(47, 'slider_prefix_en', 'slider', NULL),
(48, 'iletisim_prefix_en', 'contact', NULL),
(49, 'content_prefix_ru', 'icerik', NULL),
(50, 'search_prefix_ru', 'arama', NULL),
(51, 'slider_prefix_ru', 'slider', NULL),
(52, 'iletisim_prefix_ru', 'contact', NULL),
(53, 'mail_tempate_logo', '', NULL),
(54, 'header_searc_text', 'Arama', 'tr'),
(55, 'header_search_button', 'Arama', 'tr'),
(56, 'header_search_placeholder', 'Ara...', 'tr'),
(57, 'header_profil', 'Bilgilerim', 'tr'),
(58, 'header_yonetim_paneli', 'Yönetim Paneli', 'tr'),
(59, 'header_cikis', 'Çıkış', 'tr'),
(60, 'profile_prefix_tr', 'profil', NULL),
(61, 'profile_prefix_en', 'profile', NULL),
(62, 'profile_prefix_ru', 'profile', NULL),
(63, 'header_search_button', 'Search', 'en'),
(64, 'header_search_placeholder', 'Search...', 'en'),
(65, 'header_profil', 'Profile', 'en'),
(66, 'header_yonetim_paneli', 'Management', 'en'),
(67, 'header_cikis', 'Logout', 'en'),
(68, 'contact_passive_title', 'İletişim', 'tr'),
(69, 'contact_title', 'İletişim', 'tr'),
(70, 'contact_form_top_title', 'Bizimle iletişime geçin.', 'tr'),
(71, 'contact_form_top_abstract', 'Formu doldurarak bize kolayca ulaşabilirsiniz.', 'tr'),
(72, 'contact_right_title', 'Sosyal Medya Hesaplarımız', 'tr'),
(73, 'contact_iletisim_bilgilerimiz', 'İletişim Bilgilerimiz', 'tr'),
(74, 'contact_adres', 'Adres', 'tr'),
(75, 'contact_telefon', 'Telefon', 'tr'),
(76, 'contact_email', 'E-posta', 'tr'),
(77, 'contact_name', 'Ad', 'tr'),
(78, 'contact_surname', 'Soyad', 'tr'),
(79, 'contact_subject', 'Konu', 'tr'),
(80, 'contact_form_email', 'E-posta', 'tr'),
(81, 'contact_message', 'Mesajınız', 'tr'),
(82, 'contact_button', 'Gönder', 'tr'),
(83, 'contact_button_gonderiliyor', 'Gönderiliyor...', 'tr'),
(84, 'contact_form_success', 'Tebrikler! Mesajınız bize ulaşmıştır teşekkür ederiz.', 'tr'),
(85, 'contact_form_error', 'Hata! Mesajınız alınamadı lütfen tekrar deneyin.', 'tr'),
(86, 'home_content_head', 'İçerik Başlık', 'tr'),
(87, 'home_content_buton_text', 'Devamını Oku', 'tr'),
(88, 'home_content_button_text', 'Devamını Oku', 'tr'),
(89, 'contact_sosyal_medya_baslik', 'Bizi Sosyal Medyada Takip Edin', 'tr'),
(90, 'contact_form_telefon', 'Telefon', 'tr'),
(91, 'contact_button_gonderildi', 'Gönderildi', 'tr'),
(92, 'contact_uyari_buton', 'Tamam', 'tr'),
(93, 'contact_validate_ad', 'Ad boş olamaz.', 'tr'),
(94, 'contact_validate_min_2_ad', 'Ad en az 2 karekter olmalıdır.', 'tr'),
(95, 'contact_validate_max_60_ad', 'Ad en faz 60 karekter olmalıdır.', 'tr'),
(96, 'contact_validate_sadece_harf_ad', 'Ad sadece harf olabilir.', 'tr'),
(97, 'contact_validate_soyad', 'Soyisim boş olamaz.', 'tr'),
(98, 'contact_validate_min_2_soyad', 'Soyisim 2 karakterden az olamaz.', 'tr'),
(99, 'contact_validate_max_60_soyad', 'Soyisim 60 karakterden fazla olamaz.', 'tr'),
(100, 'contact_validate_sadece_harf_soyad', 'Soyisim sadece sayı olabilir.', 'tr'),
(101, 'contact_validate_email', 'E-posta boş olamaz.', 'tr'),
(102, 'contact_validate_gecersiz_email', 'Geçerli bir e-posta adresi giriniz.', 'tr'),
(103, 'contact_validate_mesaj', 'Mesaj boş olamaz.', 'tr'),
(104, 'contact_validate_min_10_mesaj', 'Mesaj 10 karakterden az olamaz.', 'tr'),
(105, 'contact_validate_max_2000_mesaj', 'Mesaj 2000 karakterden fazla olamaz.', 'tr'),
(106, 'contact_validate_bot_onay', 'Lütfen bot olmadığınızı onaylayınız.', 'tr'),
(107, 'footer_sayfalar', 'Linkler', 'tr'),
(108, 'header_giris_button', 'Üye Girişi', 'tr'),
(109, 'giris_prefix_tr', 'giris', NULL),
(110, 'giris_prefix_en', 'login', NULL),
(111, 'giris_prefix_ru', 'login', NULL),
(112, 'giris_email', 'E-posta', 'tr'),
(113, 'giris_sifre', 'Şifre', 'tr'),
(114, 'giris_button', 'Giriş', 'tr'),
(115, 'giris_sifremi_unututm_button', 'Şifremi Unuttum', 'tr'),
(116, 'giris_sifremi_unuttum_button', 'Şifremi Unuttum', 'tr'),
(117, 'giris_sifremi_unuttum_email', 'E-posta', 'tr'),
(118, 'giris_sifremi_unuttum_kaydet_button', 'Şifremi Sıfırla', 'tr'),
(119, 'sifremi_unutum_prefix_tr', 'sifremi-unuttum', NULL),
(120, 'sifremi_unutum_prefix_en', 'forgot-password', NULL),
(121, 'sifremi_unutum_prefix_ru', 'forgot-password', NULL),
(122, 'giris_sifremi_unuttum_title', 'Şifremi Unuttum', 'tr'),
(123, 'sifre_yenile_prefix_tr', 'sifre-yenile', NULL),
(124, 'sifre_yenile_prefix_en', 'reset-password', NULL),
(125, 'sifre_yenile_prefix_ru', 'reset-password', NULL),
(126, 'giris_sifremi_unuttum_bos_email', 'Lütfen e-Posta adresini yazınız.', 'tr'),
(127, 'giris_sifremi_unuttum_gecersiz_email', 'Lütfen geçerli bir e-Posta adresi giriniz.', 'tr'),
(128, 'giris_sifremi_unuttum_kayitsiz_email', 'Girdiğiniz e-Posta adresi kayıtlarımızda mevcut değil tekrar deneyiniz.', 'tr'),
(129, 'giris_sifremi_unuttum_mail_gonderildi', 'Şifre sıfırlama linki gönderildi. Lütfen mailinizi kontrol ediniz.', 'tr'),
(130, 'giris_sifremi_unuttum_mail_gonderilemedi', 'Mail gönderilemedi lütfen tekrar deneyiniz.', 'tr'),
(131, 'sistem_popup_title', 'Uyarı', 'tr'),
(132, 'sistem_popup_button', 'Tamam', 'tr'),
(133, 'giris_ad_veya_sifre_bos', 'Kullanıcı adı veya şifre boş olamaz.', 'tr'),
(134, 'giris_email_gecerli_formatta_degil', 'Lütfen geçerli bir mail adresi giriniz.', 'tr'),
(135, 'giris_hatali_bilgiler', 'Girdiğiniz bilgiler hatalı, lütfen kontrol edin.', 'tr'),
(136, 'giris_hesap_beklemede', 'Hesap beklemede. Lütfen yönetici ile iletişime geçin.', 'tr'),
(137, 'giris_hesap_dogrulanmamis', 'Hesap doğrulanmamış. Lütfen hesabınızı doğrulayın.', 'tr'),
(138, 'giris_yetkisiz_kullanici', 'Bu bölüme girmek için yetkiniz bulunmuyor!', 'tr'),
(139, 'giris_islem_basarili', 'Giriş işleminiz başarılı yönlendiriliyorsunuz.', 'tr'),
(140, 'sayfalama_ilk_sayfa', 'İlk', 'tr'),
(141, 'sayfalama_son_sayfa', 'Son', 'tr'),
(142, 'sayfalama_sonraki_sayfa', 'Sonraki', 'tr'),
(143, 'sayfalama_onceki_sayfa', 'Önceki', 'tr'),
(144, 'icerik_bulunamadi', 'İçerik bulunamadı...', 'tr'),
(145, 'icerik_detay_buton', 'Detay', 'tr'),
(146, 'arama_baslik', 'Arama', 'tr'),
(147, 'arama_bulunan_content', 'İçeriklerde Bulunanlar', 'tr'),
(148, 'arama_bulunan_category', 'İçerik Kategorileri', 'tr'),
(149, 'arama_bulunan_sayfa', 'Sayfalar', 'tr'),
(150, 'arama_icerik_bulunamadı', 'Her hangi bir sonuç bulunamadı.', 'tr'),
(151, 'arama_icerik_bulunamadi', 'Her hangi bir sonuç bulunamadı.', 'tr'),
(152, 'arama_icerik_sonuc_bulundu', '#aranan# ile ilgili toplam #sonuc# bulundu.', 'tr'),
(153, 'site_bakimda_baslik', 'Sitemiz Bakım Modundadır', 'tr'),
(154, 'site_bakimda_aciklama', '<p>Sistemimiz bakımdadır. Lütfen daha sonra tekrar ziyaret <strong><u>ediniz.</u></strong></p>', 'tr'),
(155, 'footer_text', 'Tüm hakları saklıdır izinsiz kopya yapılamaz.', 'tr'),
(156, 'sifre_yenileme_baslik', 'Şifre Yenileme', 'tr'),
(157, 'sifre_yenileme_linkin_kullanim_suresi_dolmus', 'Üzgünüz şifre yenileme linkinizin süresi dolmuş. Tekrar şifremi unuttum kısmından işlem yapınız.', 'tr'),
(158, 'sifre_yenileme_sifreniz_bos_olamaz', 'Şifreniz boş olamaz.', 'tr'),
(159, 'sifre_yenileme_sifreniz_en_az_8_karakter_olmalidir', 'Şifreniz en az 8 karakter olmaldır.', 'tr'),
(160, 'sifre_yenileme_sifreniz_en_fazla_50_karakter_olmalidir', 'Şifreniz en fazla 50 karakter olmaldır.', 'tr'),
(161, 'sifre_yenileme_sifreniz_en_az_1_rakam', 'Şifreniz en az 1 rakam içermek zorundadır.', 'tr'),
(162, 'sifre_yenileme_sifreniz_en_az_1_kucuk_harf', 'Şifreniz en az 1 küçük harf içermek zorundadır.', 'tr'),
(163, 'sifre_yenileme_sifreniz_en_az_1_buyuk_harf', 'Şifreniz en az 1 büyük harf içermek zorundadır.', 'tr'),
(164, 'sifre_yenileme_sifre_tekrari_bos_olamaz', 'Şifre Tekrarı boş olamaz.', 'tr'),
(165, 'sifre_yenileme_sifre_tekrari_en_az_8_karakter_olmalidir', 'Şifre Tekrarı en az 8 karakter olmaldır.', 'tr'),
(166, 'sifre_yenileme_sifre_tekrari_en_fazla_50_karakter_olmalidir', 'Şifre Tekrarı en fazla 50 karakter olmaldır.', 'tr'),
(167, 'sifre_yenileme_sifre_tekrari_en_az_1_rakam', 'Şifre Tekrarı en az 1 rakam içermek zorundadır.', 'tr'),
(168, 'sifre_yenileme_sifre_tekrari_en_az_1_kucuk_harf', 'Şifre Tekrarı en az 1 küçük harf içermek zorundadır.', 'tr'),
(169, 'sifre_yenileme_sifre_tekrari_en_az_1_buyuk_harf', 'Şifre Tekrarı en az 1 büyük harf içermek zorundadır.', 'tr'),
(170, 'sifre_yenileme_sifre_ve_tekrari_ayni_olmalidir', 'Şifre ve Tekrarı aynı olmalıdır.', 'tr'),
(171, 'sifre_yenileme_basarili', 'Şifre yenileme başarılı. Yönlendiriliyorsunuz.', 'tr'),
(172, 'sifre_yenileme_hatali', 'Şifre yenileme hatalı. Tekrar deneyin.', 'tr'),
(173, 'sifre_yenileme_sifre_input', 'Şifre', 'tr'),
(174, 'sifre_yenileme_sifre_tekrari_input', 'Şifre Tekarı', 'tr'),
(175, 'sifre_yenileme_buton', 'Şifremi Yenile', 'tr'),
(176, 'uye_ol_prefix_tr', 'kayit-ol', NULL),
(177, 'uye_ol_prefix_en', 'register', NULL),
(178, 'hesap_dogrulama_prefix_tr', 'hesap-dogrulama', NULL),
(179, 'hesap_dogrulama_prefix_en', 'account-authentication', NULL);
CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `text` longtext,
  `img` varchar(255) DEFAULT NULL,
  `show_order` int(11) DEFAULT NULL,
  `site_disi_link` tinyint(1) NOT NULL DEFAULT '0',
  `back_link` varchar(255) DEFAULT NULL,
  `yeni_sekme` tinyint(1) NOT NULL DEFAULT '0',
  `lang` varchar(5) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `abstract` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `tags` (
                        `id` int(11) NOT NULL,
                        `name` varchar(255) NOT NULL,
                        `link` varchar(255) NOT NULL,
                        `deleted` tinyint(1) NOT NULL DEFAULT '0',
                        `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `email` varchar(250) NOT NULL,
                         `password` varchar(250) NOT NULL,
                         `name` varchar(250) NOT NULL,
                         `surname` varchar(250) NOT NULL,
                         `img` varchar(255) DEFAULT NULL,
                         `telefon` varchar(255) NOT NULL,
                         `role_group` int(11) NOT NULL DEFAULT '1' COMMENT 'yetkisi oldu rol grubu',
                         `rank` int(11) NOT NULL DEFAULT '1' COMMENT '1:normal_üye,90:admin',
                         `email_verify` tinyint(1) NOT NULL DEFAULT '0',
                         `send_mail` int(1) NOT NULL DEFAULT '0' COMMENT 'mail gönderilecek mi mail istiyor mu',
                         `verify_code` varchar(255) DEFAULT NULL,
                         `theme` tinyint(1) DEFAULT '1' COMMENT 'admin paneli teması 1:koyu,2:açık',
                         `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                         `deleted` tinyint(1) NOT NULL DEFAULT '0',
                         `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `users` (`id`, `email`, `password`, `name`, `surname`, `img`, `telefon`, `role_group`, `rank`, `email_verify`, `send_mail`, `verify_code`, `theme`, `status`, `deleted`) VALUES
(81, 'demo@demo.com', '$2y$10$.jci1zvg95FQAih9pTLphunLcJZ8TWJpOUX2P6l5AdOl/Affo3Esq', 'Demo', 'Demo', '', '', 1, 90, 1, 1, '', 1, 1, 0);

INSERT INTO `lang` (`id`, `lang`, `short_lang`, `lang_iso`, `default_lang`, `form_validate`, `user_id`, `status`, `deleted`, `created_at`, `updated_at`) VALUES (NULL, 'Türkçe', 'tr', 'tr_TR', '1', '1', '1', '1', '0', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

CREATE TABLE `youtube_videos` (
  `id` int(11) NOT NULL,
  `gallery_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `show_order` int(11) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `lang_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `contact_form`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `content`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `content_categories`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `email_template`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `file_url`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `forgot_password`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `gallery`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `gallery_image`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `lang`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `log_types`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `mailing`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `mailing_user`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `mail_log`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `role_groups`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `lang` (`lang`);
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `youtube_videos`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `content_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `file_url`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `gallery_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `log_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=560;
ALTER TABLE `mailing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mailing_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mail_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `post_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `role_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
ALTER TABLE `role_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
ALTER TABLE `youtube_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

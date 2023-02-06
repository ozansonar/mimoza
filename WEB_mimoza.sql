CREATE TABLE IF NOT EXISTS `contact_form` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                              `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                              PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `query_error`
(
    id int(11) NOT NULL AUTO_INCREMENT,
    type       varchar(10)                          null,
    `table`    varchar(10)                          null,
    error      mediumtext                           null,
    table_data longtext                             null,
    where_data longtext                             null,
    extra_text mediumtext                           null,
    lang       varchar(20)                          null,
    created_at datetime   default CURRENT_TIMESTAMP null,
    updated_at datetime   default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    status     tinyint(1) default 0                 null,
    deleted    tinyint(1) default 0                 null,
    check (json_valid(`table_data`)),
    check (json_valid(`where_data`)),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `content` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
                                         `cat_id` int(11) NOT NULL,
                                         `title` varchar(255) DEFAULT NULL,
                                         `link` varchar(255) NOT NULL,
                                         `text` longtext DEFAULT NULL,
                                         `abstract` text DEFAULT NULL,
                                         `img` varchar(250) DEFAULT NULL,
                                         `index_show` tinyint(1) NOT NULL DEFAULT '0',
                                         `show_order` int(11) NOT NULL,
                                         `keywords` varchar(255) DEFAULT NULL,
                                         `description` text,
                                         `lang` varchar(5) DEFAULT NULL,
                                         `lang_id` varchar(255) DEFAULT NULL,
                                         `user_id` int(11) NOT NULL,
                                         `show_count` int(11) DEFAULT '0',
                                         `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                         `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                         `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                         `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                         PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO `content` (`id`, `cat_id`, `title`, `link`, `text`, `abstract`, `img`, `index_show`, `show_order`, `keywords`, `description`, `lang`, `lang_id`, `user_id`, `show_count`, `status`, `deleted`) VALUES
                                                                                                                                                                                                               (1, 1, 'İçerik 1', 'icerik-1', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum integer enim neque volutpat ac tincidunt. Habitasse platea dictumst quisque sagittis purus. Ornare massa eget egestas purus viverra accumsan in nisl. Egestas fringilla phasellus faucibus scelerisque eleifend donec pretium vulputate sapien. Nibh tellus molestie nunc non blandit massa enim. Interdum varius sit amet mattis vulputate enim nulla aliquet porttitor. Nunc mi ipsum faucibus vitae aliquet. Mus mauris vitae ultricies leo integer malesuada nunc vel. Sed vulputate odio ut enim blandit volutpat maecenas volutpat blandit. Nulla aliquet enim tortor at auctor urna nunc id. Nam aliquam sem et tortor consequat id porta nibh. Id aliquet risus feugiat in ante. Ultrices gravida dictum fusce ut placerat orci. Et leo duis ut diam quam nulla.</p>\n\n<p>Cras tincidunt lobortis feugiat vivamus at augue. Dignissim sodales ut eu sem integer. Leo vel fringilla est ullamcorper eget nulla facilisi etiam. Amet nisl suscipit adipiscing bibendum. Eu sem integer vitae justo eget magna fermentum. At volutpat diam ut venenatis tellus in. Faucibus vitae aliquet nec ullamcorper sit amet risus nullam eget. Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Ipsum faucibus vitae aliquet nec ullamcorper sit amet. Vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae. Id venenatis a condimentum vitae sapien pellentesque habitant morbi tristique.</p>\n\n<p>Lobortis elementum nibh tellus molestie nunc non blandit massa. Scelerisque varius morbi enim nunc faucibus a pellentesque sit amet. Rhoncus dolor purus non enim praesent elementum facilisis. Egestas sed sed risus pretium quam vulputate. Erat imperdiet sed euismod nisi porta lorem. Tempor orci dapibus ultrices in iaculis nunc sed. Ac odio tempor orci dapibus ultrices. Diam sit amet nisl suscipit adipiscing bibendum. Maecenas volutpat blandit aliquam etiam. Nulla facilisi nullam vehicula ipsum a arcu cursus. Auctor elit sed vulputate mi sit amet mauris commodo. Sollicitudin nibh sit amet commodo nulla facilisi nullam vehicula ipsum. Ut tortor pretium viverra suspendisse potenti nullam ac. Sit amet purus gravida quis blandit turpis cursus in. Sagittis aliquam malesuada bibendum arcu vitae elementum curabitur.</p>\n\n<p>At quis risus sed vulputate odio ut enim blandit. Sed risus pretium quam vulputate. Enim nulla aliquet porttitor lacus. Neque laoreet suspendisse interdum consectetur libero id faucibus. Etiam sit amet nisl purus in mollis. Amet aliquam id diam maecenas ultricies mi. Lorem ipsum dolor sit amet consectetur adipiscing elit duis tristique. Volutpat sed cras ornare arcu dui vivamus arcu felis bibendum. Molestie nunc non blandit massa enim nec dui nunc. Neque volutpat ac tincidunt vitae semper quis. Facilisis leo vel fringilla est ullamcorper. Risus sed vulputate odio ut enim blandit volutpat maecenas volutpat. Commodo viverra maecenas accumsan lacus vel facilisis volutpat est. Semper quis lectus nulla at volutpat diam ut. Posuere morbi leo urna molestie at elementum eu facilisis sed. Vitae elementum curabitur vitae nunc sed velit dignissim. Accumsan lacus vel facilisis volutpat est velit.</p>\n\n<p>Quis eleifend quam adipiscing vitae proin sagittis. Auctor neque vitae tempus quam pellentesque nec nam aliquam. Massa tincidunt nunc pulvinar sapien et ligula ullamcorper. Dolor sit amet consectetur adipiscing elit ut aliquam purus sit. Lorem ipsum dolor sit amet consectetur adipiscing. Aliquet nec ullamcorper sit amet risus nullam eget. Rhoncus urna neque viverra justo nec ultrices dui. Ante in nibh mauris cursus. In eu mi bibendum neque. Est sit amet facilisis magna. Ipsum suspendisse ultrices gravida dictum fusce ut placerat orci. Etiam non quam lacus suspendisse faucibus interdum posuere. Dignissim suspendisse in est ante in.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '632343288decc3.45599641-1663255336.jpg', 1, 1, 'key1,key2,key,key4', 'description 1 description 2 description 3 description 4', 'tr', '20220915181619', 81, 1, 1, 0),
                                                                                                                                                                                                               (2, 2, 'İçerik 2', 'icerik-2', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum integer enim neque volutpat ac tincidunt. Habitasse platea dictumst quisque sagittis purus. Ornare massa eget egestas purus viverra accumsan in nisl. Egestas fringilla phasellus faucibus scelerisque eleifend donec pretium vulputate sapien. Nibh tellus molestie nunc non blandit massa enim. Interdum varius sit amet mattis vulputate enim nulla aliquet porttitor. Nunc mi ipsum faucibus vitae aliquet. Mus mauris vitae ultricies leo integer malesuada nunc vel. Sed vulputate odio ut enim blandit volutpat maecenas volutpat blandit. Nulla aliquet enim tortor at auctor urna nunc id. Nam aliquam sem et tortor consequat id porta nibh. Id aliquet risus feugiat in ante. Ultrices gravida dictum fusce ut placerat orci. Et leo duis ut diam quam nulla.</p>\n\n<p>Cras tincidunt lobortis feugiat vivamus at augue. Dignissim sodales ut eu sem integer. Leo vel fringilla est ullamcorper eget nulla facilisi etiam. Amet nisl suscipit adipiscing bibendum. Eu sem integer vitae justo eget magna fermentum. At volutpat diam ut venenatis tellus in. Faucibus vitae aliquet nec ullamcorper sit amet risus nullam eget. Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Ipsum faucibus vitae aliquet nec ullamcorper sit amet. Vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae. Id venenatis a condimentum vitae sapien pellentesque habitant morbi tristique.</p>\n\n<p>Lobortis elementum nibh tellus molestie nunc non blandit massa. Scelerisque varius morbi enim nunc faucibus a pellentesque sit amet. Rhoncus dolor purus non enim praesent elementum facilisis. Egestas sed sed risus pretium quam vulputate. Erat imperdiet sed euismod nisi porta lorem. Tempor orci dapibus ultrices in iaculis nunc sed. Ac odio tempor orci dapibus ultrices. Diam sit amet nisl suscipit adipiscing bibendum. Maecenas volutpat blandit aliquam etiam. Nulla facilisi nullam vehicula ipsum a arcu cursus. Auctor elit sed vulputate mi sit amet mauris commodo. Sollicitudin nibh sit amet commodo nulla facilisi nullam vehicula ipsum. Ut tortor pretium viverra suspendisse potenti nullam ac. Sit amet purus gravida quis blandit turpis cursus in. Sagittis aliquam malesuada bibendum arcu vitae elementum curabitur.</p>\n\n<p>At quis risus sed vulputate odio ut enim blandit. Sed risus pretium quam vulputate. Enim nulla aliquet porttitor lacus. Neque laoreet suspendisse interdum consectetur libero id faucibus. Etiam sit amet nisl purus in mollis. Amet aliquam id diam maecenas ultricies mi. Lorem ipsum dolor sit amet consectetur adipiscing elit duis tristique. Volutpat sed cras ornare arcu dui vivamus arcu felis bibendum. Molestie nunc non blandit massa enim nec dui nunc. Neque volutpat ac tincidunt vitae semper quis. Facilisis leo vel fringilla est ullamcorper. Risus sed vulputate odio ut enim blandit volutpat maecenas volutpat. Commodo viverra maecenas accumsan lacus vel facilisis volutpat est. Semper quis lectus nulla at volutpat diam ut. Posuere morbi leo urna molestie at elementum eu facilisis sed. Vitae elementum curabitur vitae nunc sed velit dignissim. Accumsan lacus vel facilisis volutpat est velit.</p>\n\n<p>Quis eleifend quam adipiscing vitae proin sagittis. Auctor neque vitae tempus quam pellentesque nec nam aliquam. Massa tincidunt nunc pulvinar sapien et ligula ullamcorper. Dolor sit amet consectetur adipiscing elit ut aliquam purus sit. Lorem ipsum dolor sit amet consectetur adipiscing. Aliquet nec ullamcorper sit amet risus nullam eget. Rhoncus urna neque viverra justo nec ultrices dui. Ante in nibh mauris cursus. In eu mi bibendum neque. Est sit amet facilisis magna. Ipsum suspendisse ultrices gravida dictum fusce ut placerat orci. Etiam non quam lacus suspendisse faucibus interdum posuere. Dignissim suspendisse in est ante in.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '632343d35284b3.04539745-1663255507.jpg', 1, 10, NULL, NULL, 'tr', '20220915182507', 81, 1, 1, 0),
                                                                                                                                                                                                               (3, 1, 'İçerik 3', 'icerik-3', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum integer enim neque volutpat ac tincidunt. Habitasse platea dictumst quisque sagittis purus. Ornare massa eget egestas purus viverra accumsan in nisl. Egestas fringilla phasellus faucibus scelerisque eleifend donec pretium vulputate sapien. Nibh tellus molestie nunc non blandit massa enim. Interdum varius sit amet mattis vulputate enim nulla aliquet porttitor. Nunc mi ipsum faucibus vitae aliquet. Mus mauris vitae ultricies leo integer malesuada nunc vel. Sed vulputate odio ut enim blandit volutpat maecenas volutpat blandit. Nulla aliquet enim tortor at auctor urna nunc id. Nam aliquam sem et tortor consequat id porta nibh. Id aliquet risus feugiat in ante. Ultrices gravida dictum fusce ut placerat orci. Et leo duis ut diam quam nulla.</p>\n\n<p>Cras tincidunt lobortis feugiat vivamus at augue. Dignissim sodales ut eu sem integer. Leo vel fringilla est ullamcorper eget nulla facilisi etiam. Amet nisl suscipit adipiscing bibendum. Eu sem integer vitae justo eget magna fermentum. At volutpat diam ut venenatis tellus in. Faucibus vitae aliquet nec ullamcorper sit amet risus nullam eget. Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Ipsum faucibus vitae aliquet nec ullamcorper sit amet. Vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae. Id venenatis a condimentum vitae sapien pellentesque habitant morbi tristique.</p>\n\n<p>Lobortis elementum nibh tellus molestie nunc non blandit massa. Scelerisque varius morbi enim nunc faucibus a pellentesque sit amet. Rhoncus dolor purus non enim praesent elementum facilisis. Egestas sed sed risus pretium quam vulputate. Erat imperdiet sed euismod nisi porta lorem. Tempor orci dapibus ultrices in iaculis nunc sed. Ac odio tempor orci dapibus ultrices. Diam sit amet nisl suscipit adipiscing bibendum. Maecenas volutpat blandit aliquam etiam. Nulla facilisi nullam vehicula ipsum a arcu cursus. Auctor elit sed vulputate mi sit amet mauris commodo. Sollicitudin nibh sit amet commodo nulla facilisi nullam vehicula ipsum. Ut tortor pretium viverra suspendisse potenti nullam ac. Sit amet purus gravida quis blandit turpis cursus in. Sagittis aliquam malesuada bibendum arcu vitae elementum curabitur.</p>\n\n<p>At quis risus sed vulputate odio ut enim blandit. Sed risus pretium quam vulputate. Enim nulla aliquet porttitor lacus. Neque laoreet suspendisse interdum consectetur libero id faucibus. Etiam sit amet nisl purus in mollis. Amet aliquam id diam maecenas ultricies mi. Lorem ipsum dolor sit amet consectetur adipiscing elit duis tristique. Volutpat sed cras ornare arcu dui vivamus arcu felis bibendum. Molestie nunc non blandit massa enim nec dui nunc. Neque volutpat ac tincidunt vitae semper quis. Facilisis leo vel fringilla est ullamcorper. Risus sed vulputate odio ut enim blandit volutpat maecenas volutpat. Commodo viverra maecenas accumsan lacus vel facilisis volutpat est. Semper quis lectus nulla at volutpat diam ut. Posuere morbi leo urna molestie at elementum eu facilisis sed. Vitae elementum curabitur vitae nunc sed velit dignissim. Accumsan lacus vel facilisis volutpat est velit.</p>\n\n<p>Quis eleifend quam adipiscing vitae proin sagittis. Auctor neque vitae tempus quam pellentesque nec nam aliquam. Massa tincidunt nunc pulvinar sapien et ligula ullamcorper. Dolor sit amet consectetur adipiscing elit ut aliquam purus sit. Lorem ipsum dolor sit amet consectetur adipiscing. Aliquet nec ullamcorper sit amet risus nullam eget. Rhoncus urna neque viverra justo nec ultrices dui. Ante in nibh mauris cursus. In eu mi bibendum neque. Est sit amet facilisis magna. Ipsum suspendisse ultrices gravida dictum fusce ut placerat orci. Etiam non quam lacus suspendisse faucibus interdum posuere. Dignissim suspendisse in est ante in.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '632345fc61a6c9.25376989-1663256060.jpg', 1, 30, NULL, NULL, 'tr', '20220915183420', 81, 0, 1, 0),
                                                                                                                                                                                                               (4, 2, 'İçerik 4', 'icerik-4', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum integer enim neque volutpat ac tincidunt. Habitasse platea dictumst quisque sagittis purus. Ornare massa eget egestas purus viverra accumsan in nisl. Egestas fringilla phasellus faucibus scelerisque eleifend donec pretium vulputate sapien. Nibh tellus molestie nunc non blandit massa enim. Interdum varius sit amet mattis vulputate enim nulla aliquet porttitor. Nunc mi ipsum faucibus vitae aliquet. Mus mauris vitae ultricies leo integer malesuada nunc vel. Sed vulputate odio ut enim blandit volutpat maecenas volutpat blandit. Nulla aliquet enim tortor at auctor urna nunc id. Nam aliquam sem et tortor consequat id porta nibh. Id aliquet risus feugiat in ante. Ultrices gravida dictum fusce ut placerat orci. Et leo duis ut diam quam nulla.</p>\n\n<p>Cras tincidunt lobortis feugiat vivamus at augue. Dignissim sodales ut eu sem integer. Leo vel fringilla est ullamcorper eget nulla facilisi etiam. Amet nisl suscipit adipiscing bibendum. Eu sem integer vitae justo eget magna fermentum. At volutpat diam ut venenatis tellus in. Faucibus vitae aliquet nec ullamcorper sit amet risus nullam eget. Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Ipsum faucibus vitae aliquet nec ullamcorper sit amet. Vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae. Id venenatis a condimentum vitae sapien pellentesque habitant morbi tristique.</p>\n\n<p>Lobortis elementum nibh tellus molestie nunc non blandit massa. Scelerisque varius morbi enim nunc faucibus a pellentesque sit amet. Rhoncus dolor purus non enim praesent elementum facilisis. Egestas sed sed risus pretium quam vulputate. Erat imperdiet sed euismod nisi porta lorem. Tempor orci dapibus ultrices in iaculis nunc sed. Ac odio tempor orci dapibus ultrices. Diam sit amet nisl suscipit adipiscing bibendum. Maecenas volutpat blandit aliquam etiam. Nulla facilisi nullam vehicula ipsum a arcu cursus. Auctor elit sed vulputate mi sit amet mauris commodo. Sollicitudin nibh sit amet commodo nulla facilisi nullam vehicula ipsum. Ut tortor pretium viverra suspendisse potenti nullam ac. Sit amet purus gravida quis blandit turpis cursus in. Sagittis aliquam malesuada bibendum arcu vitae elementum curabitur.</p>\n\n<p>At quis risus sed vulputate odio ut enim blandit. Sed risus pretium quam vulputate. Enim nulla aliquet porttitor lacus. Neque laoreet suspendisse interdum consectetur libero id faucibus. Etiam sit amet nisl purus in mollis. Amet aliquam id diam maecenas ultricies mi. Lorem ipsum dolor sit amet consectetur adipiscing elit duis tristique. Volutpat sed cras ornare arcu dui vivamus arcu felis bibendum. Molestie nunc non blandit massa enim nec dui nunc. Neque volutpat ac tincidunt vitae semper quis. Facilisis leo vel fringilla est ullamcorper. Risus sed vulputate odio ut enim blandit volutpat maecenas volutpat. Commodo viverra maecenas accumsan lacus vel facilisis volutpat est. Semper quis lectus nulla at volutpat diam ut. Posuere morbi leo urna molestie at elementum eu facilisis sed. Vitae elementum curabitur vitae nunc sed velit dignissim. Accumsan lacus vel facilisis volutpat est velit.</p>\n\n<p>Quis eleifend quam adipiscing vitae proin sagittis. Auctor neque vitae tempus quam pellentesque nec nam aliquam. Massa tincidunt nunc pulvinar sapien et ligula ullamcorper. Dolor sit amet consectetur adipiscing elit ut aliquam purus sit. Lorem ipsum dolor sit amet consectetur adipiscing. Aliquet nec ullamcorper sit amet risus nullam eget. Rhoncus urna neque viverra justo nec ultrices dui. Ante in nibh mauris cursus. In eu mi bibendum neque. Est sit amet facilisis magna. Ipsum suspendisse ultrices gravida dictum fusce ut placerat orci. Etiam non quam lacus suspendisse faucibus interdum posuere. Dignissim suspendisse in est ante in.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', '632346371bb2a3.33603075-1663256119.jpg', 1, 50, NULL, NULL, 'tr', '20220915183519', 81, 0, 1, 0);


CREATE TABLE IF NOT EXISTS `content_categories` (
                                                    `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                                    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                                    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `content_categories` (`id`, `title`, `link`, `img`, `show_type`, `show_order`, `user_id`, `lang`, `lang_id`, `status`, `deleted`) VALUES
                                                                                                                                                  (1, 'Teknoloji', 'teknoloji', NULL, 2, 1, 81, 'tr', '20220915180720', 1, 0),
                                                                                                                                                  (2, 'Spor', 'spor', NULL, 1, 2, 81, 'tr', '20220915180738', 1, 0);

CREATE TABLE IF NOT EXISTS `email_template` (
                                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                                `subject` varchar(255) NOT NULL,
                                                `text` text NOT NULL,
                                                `not_text` text,
                                                `lang` varchar(5) DEFAULT NULL,
                                                `lang_id` varchar(255) DEFAULT NULL,
                                                `user_id` int(11) NOT NULL,
                                                `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                                `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                                PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


INSERT INTO `email_template` (`id`, `subject`, `text`, `not_text`, `lang`, `lang_id`, `user_id`, `status`, `deleted`) VALUES
                                                                                                                          (1, 'İletişim', '<p>#project_name# iletişim formundan yeni bir mesajınız var.</p>\r\n\r\n<p>#message#</p>', 'İletişim formundan atılan mesajlar', 'tr', '20211001211101', 66, 1, 0),
                                                                                                                          (2, '', '', '', 'en', '20211001211101', 66, 0, 0),
                                                                                                                          (4, 'Şifre Yenileme', '<p>Şifrenizi sıfırlamak için lütfen <a href=\"#link#\" target=\"_blank\">tıklayınız.</a></p>\r\n\r\n<p>NOT: Şifre sıfırlama linki 1 gün geçerlidir.</p>', 'Şifre sıfırlama isteğinde bulunulduğunda giden mail', 'tr', '20211011205227', 66, 1, 0),
                                                                                                                          (5, '', '', '', 'en', '20211011205227', 66, 0, 0);

CREATE TABLE IF NOT EXISTS `file_url` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `url` varchar(255) NOT NULL,
                                          `controller` varchar(255) NOT NULL,
                                          `lang` varchar(10) DEFAULT NULL,
                                          `user_id` int(11) NOT NULL,
                                          `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                          `deleted` tinyint(4) NOT NULL DEFAULT '0',
                                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                          `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                          PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;


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


CREATE TABLE IF NOT EXISTS `forgot_password` (
                                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                                 `user_id` int(11) DEFAULT NULL,
                                                 `type` int(11) DEFAULT NULL,
                                                 `email` varchar(255) DEFAULT NULL,
                                                 `hash` text,
                                                 `deleted` int(11) DEFAULT '0',
                                                 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                 `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                                 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gallery` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                         `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                         PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gallery_image` (
                                               `id` int(11) NOT NULL AUTO_INCREMENT,
                                               `gallery_id` int(11) NOT NULL,
                                               `image` varchar(255) NOT NULL,
                                               `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                               `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                               `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                               `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                               PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `lang` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `lang` varchar(50) NOT NULL,
                                      `short_lang` varchar(10) NOT NULL,
                                      `lang_iso` varchar(10) DEFAULT NULL,
                                      `default_lang` tinyint(1) NOT NULL DEFAULT '2',
                                      `form_validate` tinyint(1) DEFAULT '2',
                                      `user_id` int(11) DEFAULT NULL,
                                      `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                      `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `lang` (`id`, `lang`, `short_lang`, `lang_iso`, `default_lang`, `form_validate`, `user_id`, `status`, `deleted`) VALUES
    (1, 'Türkçe', 'tr', 'tr_TR', 1, 1, 1, 1, 0);


CREATE TABLE IF NOT EXISTS `logs` (
                                      `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                      `user_id` int(5) DEFAULT NULL,
                                      `log_type` int(5) NOT NULL,
                                      `log_datetime` datetime NOT NULL,
                                      `client_ip` varchar(100) NOT NULL,
                                      `log_browser` varchar(90) DEFAULT NULL,
                                      `log_os` varchar(90) DEFAULT NULL,
                                      `log_page` varchar(100) DEFAULT NULL,
                                      `log_query_string` varchar(255) DEFAULT NULL,
                                      `log_http_user_agent` varchar(255) DEFAULT NULL,
                                      `log_detail` text DEFAULT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=355 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `log_types` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
                                           `log_key` varchar(100) NOT NULL,
                                           `log_val` int(9) NOT NULL,
                                           `log_desc` varchar(255) DEFAULT NULL,
                                           PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=560 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

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

CREATE TABLE IF NOT EXISTS `mailing` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                         `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                         PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `mailing_user` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                              `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                              PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_log` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `mail` varchar(255) NOT NULL,
                                          `send_debug` varchar(255) DEFAULT NULL COMMENT 'eğer burda adres yazıyorsa mail bu adrese gönderilmiştir',
                                          `subject` varchar(255) NOT NULL,
                                          `message` mediumtext NOT NULL,
                                          `send_type` tinyint(1) DEFAULT NULL,
                                          `extra_log` text,
                                          `sent_status` tinyint(1) NOT NULL DEFAULT '0',
                                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                          PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `menu` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `name` varchar(255) DEFAULT NULL,
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
                                      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


INSERT INTO `menu` (`id`, `name`, `link`, `show_order`, `menu_type`, `top_id`, `redirect`, `redirect_link`, `redirect_open_type`, `show_type`, `lang`, `lang_id`, `user_id`, `status`, `deleted`) VALUES
                                                                                                                                                                                                      (1, 'Hakkımızda', 'hakkimizda', 10, 1, 0, 0, NULL, 0, 3, 'tr', '20220915175552', 81, 1, 0),
                                                                                                                                                                                                      (2, 'Anasayfa', 'index', 1, 1, 0, 0, NULL, 0, 1, 'tr', '20220915175616', 81, 1, 0),
                                                                                                                                                                                                      (3, 'İletişim', 'iletisim', 50, 1, 0, 0, NULL, 0, 3, 'tr', '20220915175933', 81, 1, 0),
                                                                                                                                                                                                      (5, 'İçerik Kategorileri', 'icerik', 15, 1, 0, 0, NULL, 0, 3, 'tr', '20220916095617', 81, 1, 0),
                                                                                                                                                                                                      (6, 'İçerikler', 'icerikler', 20, 1, 0, 0, NULL, 0, 1, 'tr', '20220916095742', 81, 1, 0),
                                                                                                                                                                                                      (7, 'Spor Haberleri', 'icerik/spor-2', 1, 2, 6, 0, NULL, 0, 3, 'tr', '20220916102708', 81, 1, 0),
                                                                                                                                                                                                      (8, 'Teknoloji Haberleri', 'icerik/teknoloji-1', 1, 2, 6, 0, NULL, 0, 3, 'tr', '20220916102752', 81, 1, 0);

CREATE TABLE IF NOT EXISTS `page` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `page` (`id`, `title`, `link`, `abstract`, `text`, `img`, `keywords`, `description`, `show_count`, `show_order`, `user_id`, `lang`, `lang_id`, `status`, `deleted`) VALUES
    (1, 'Hakkımızda', 'hakkimizda', '<p>orem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In dictum non consectetur a erat nam at. </p>', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In dictum non consectetur a erat nam at. Feugiat in ante metus dictum at tempor commodo ullamcorper a. Dolor purus non enim praesent elementum facilisis. Consectetur adipiscing elit ut aliquam. Ut morbi tincidunt augue interdum velit. Ac turpis egestas integer eget aliquet nibh praesent. Non consectetur a erat nam at lectus urna duis convallis. Urna neque viverra justo nec ultrices dui. Volutpat blandit aliquam etiam erat velit scelerisque. Nunc sed velit dignissim sodales. Nulla posuere sollicitudin aliquam ultrices sagittis orci. Turpis massa tincidunt dui ut. Ut diam quam nulla porttitor. Egestas tellus rutrum tellus pellentesque. Egestas maecenas pharetra convallis posuere morbi leo urna molestie at. Erat imperdiet sed euismod nisi porta lorem mollis aliquam. Et molestie ac feugiat sed lectus vestibulum mattis ullamcorper velit. Magnis dis parturient montes nascetur ridiculus mus. Sem viverra aliquet eget sit amet tellus cras.</p>\n\n<p>Aliquet nibh praesent tristique magna sit. Egestas erat imperdiet sed euismod nisi porta lorem mollis aliquam. Arcu cursus vitae congue mauris. Vitae nunc sed velit dignissim sodales ut. Urna id volutpat lacus laoreet non curabitur gravida. Ut sem viverra aliquet eget sit amet tellus cras adipiscing. Eget mi proin sed libero. Convallis convallis tellus id interdum velit laoreet id donec ultrices. Consectetur purus ut faucibus pulvinar elementum integer. Nec sagittis aliquam malesuada bibendum arcu vitae elementum. Diam vel quam elementum pulvinar etiam non quam lacus suspendisse. Fermentum et sollicitudin ac orci phasellus egestas. Fames ac turpis egestas sed tempus urna et. Felis imperdiet proin fermentum leo vel orci porta non. Fermentum iaculis eu non diam phasellus vestibulum lorem sed. Et ultrices neque ornare aenean euismod elementum nisi. Risus pretium quam vulputate dignissim suspendisse in est.</p>\n\n<p>Erat imperdiet sed euismod nisi porta. Praesent semper feugiat nibh sed pulvinar proin gravida hendrerit. Fringilla urna porttitor rhoncus dolor purus non. Commodo odio aenean sed adipiscing diam. Eget magna fermentum iaculis eu non diam phasellus vestibulum. Sagittis id consectetur purus ut faucibus pulvinar. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Molestie at elementum eu facilisis sed odio morbi. Velit scelerisque in dictum non consectetur a erat. Odio aenean sed adipiscing diam. Risus quis varius quam quisque id diam. Dignissim sodales ut eu sem integer vitae justo eget magna. Velit scelerisque in dictum non consectetur a erat nam at. Faucibus pulvinar elementum integer enim neque volutpat. Fusce id velit ut tortor pretium viverra. Hendrerit gravida rutrum quisque non tellus orci ac auctor. Tincidunt eget nullam non nisi. Vivamus at augue eget arcu dictum varius duis at consectetur. Turpis in eu mi bibendum neque egestas congue quisque egestas.</p>\n\n<p><strong>Ut faucibus pulvinar elementum integer enim neque volutpat ac tincidunt. Vulputate eu scelerisque felis imperdiet. Nibh sed pulvinar proin gravida hendrerit lectus. </strong>Massa sapien faucibus et molestie. Malesuada fames ac turpis egestas integer eget aliquet nibh. Ultrices in iaculis nunc sed augue lacus viverra. Consectetur adipiscing elit pellentesque habitant morbi. Maecenas volutpat blandit aliquam etiam erat. Proin sed libero enim sed faucibus turpis in eu mi. Massa massa ultricies mi quis hendrerit dolor magna eget. Natoque penatibus et magnis dis parturient montes nascetur. Pulvinar neque laoreet suspendisse interdum consectetur libero id faucibus nisl. Tellus in hac habitasse platea dictumst vestibulum. Congue nisi vitae suscipit tellus mauris. Fames ac turpis egestas maecenas pharetra.</p>\n\n<p>Pellentesque habitant morbi tristique senectus et netus et malesuada. Sit amet est placerat in egestas erat imperdiet sed. Sit amet facilisis magna etiam tempor. Nibh mauris cursus mattis molestie a iaculis at erat pellentesque. Libero nunc consequat interdum varius sit. Tristique magna sit amet purus gravida quis. At imperdiet dui accumsan sit amet. Tristique senectus et netus et malesuada. Purus in mollis nunc sed id. Orci dapibus ultrices in iaculis nunc sed augue. Vel fringilla est ullamcorper eget. Ultrices gravida dictum fusce ut placerat orci. Ac tortor dignissim convallis aenean et tortor at risus. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu. Consectetur a erat nam at lectus urna duis. Scelerisque fermentum dui faucibus in ornare quam viverra orci. Sed lectus vestibulum mattis ullamcorper velit sed ullamcorper. Eu volutpat odio facilisis mauris sit amet massa vitae.</p>', '63233d667d9a13.17812379-1663253862.jpg', NULL, NULL, 0, 0, 81, 'tr', '20220915175742', 1, 0);


CREATE TABLE IF NOT EXISTS `post_tags` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
                                           `post_id` int(11) NOT NULL,
                                           `tag_id` int(11) NOT NULL,
                                           `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                           PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `role_groups` (
                                             `id` int(11) NOT NULL AUTO_INCREMENT,
                                             `group_name` varchar(255) DEFAULT NULL COMMENT 'rol grup name',
                                             `status` tinyint(1) DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                             `deleted` tinyint(1) DEFAULT '0',
                                             `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                             `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                             PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='kullanıcı rol grunlari';


INSERT INTO `role_groups` (`id`, `group_name`, `status`, `deleted`) VALUES
    (1, 'Süper Admin', 1, 0);


CREATE TABLE IF NOT EXISTS `role_permission` (
                                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                                 `role_group` int(11) DEFAULT '0',
                                                 `role_key` varchar(255) DEFAULT NULL,
                                                 `permission` varchar(255) DEFAULT NULL,
                                                 `deleted` tinyint(4) DEFAULT '0',
                                                 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=340 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `role_permission`
--

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

CREATE TABLE IF NOT EXISTS `sessions` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `user_id` int(11) NOT NULL,
                                          `token` varchar(255) NOT NULL,
                                          `browser` varchar(255) DEFAULT NULL,
                                          `ip_address` varchar(60) DEFAULT NULL,
                                          `x_forwarded_for` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                          `expire_date` timestamp NOT NULL,
                                          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                          `deleted` int(11) DEFAULT '0',
                                          PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `name` varchar(255) NOT NULL,
                                          `val` mediumtext,
                                          `lang` varchar(5) DEFAULT NULL COMMENT 'sadece sayfalar için kullanılan yazılarda gelir ayarlarla bir alakası yok',
                                          PRIMARY KEY (`id`),
                                          KEY `name` (`name`),
                                          KEY `lang` (`lang`)
) ENGINE=MyISAM AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;


INSERT INTO `settings` (`id`, `name`, `val`, `lang`) VALUES
                                                         (1, 'description', 'description,description,description', NULL),
                                                         (2, 'keywords', 'keywords,keywords1', NULL),
                                                         (3, 'title', 'Proje Başlığı', NULL),
                                                         (4, 'link_sort_lang', '1', NULL),
                                                         (5, 'site_status', '2', NULL),
                                                         (6, 'header_logo', '', NULL),
                                                         (7, 'project_name', 'Project name', NULL),
                                                         (8, 'theme', 'proje', NULL),
                                                         (9, 'logo_text', NULL, NULL),
                                                         (10, 'footer_text', '', NULL),
                                                         (11, 'google', NULL, NULL),
                                                         (12, 'facebook', NULL, NULL),
                                                         (13, 'twitter', NULL, NULL),
                                                         (14, 'instagram', NULL, NULL),
                                                         (15, 'youtube', NULL, NULL),
                                                         (16, 'linkedin', NULL, NULL),
                                                         (17, 'vk', NULL, NULL),
                                                         (18, 'telegram', NULL, NULL),
                                                         (19, 'whatsapp', NULL, NULL),
                                                         (20, 'site_mail', 'demo@demo.com', NULL),
                                                         (21, 'phone', '0000000000', NULL),
                                                         (22, 'fax', NULL, NULL),
                                                         (23, 'maps', NULL, NULL),
                                                         (24, 'adres', NULL, NULL),
                                                         (25, 'contact_despription', NULL, NULL),
                                                         (26, 'site_status_title', NULL, NULL),
                                                         (27, 'site_status_text', NULL, NULL),
                                                         (28, 'mail_send_mode', '0', NULL),
                                                         (29, 'smtp_host', NULL, NULL),
                                                         (30, 'smtp_email', NULL, NULL),
                                                         (31, 'smtp_password', NULL, NULL),
                                                         (32, 'smtp_port', NULL, NULL),
                                                         (33, 'smtp_secure', '1', NULL),
                                                         (34, 'smtp_send_name_surname', NULL, NULL),
                                                         (35, 'smtp_send_email_adres', NULL, NULL),
                                                         (36, 'smtp_send_email_reply_adres', NULL, NULL),
                                                         (37, 'smtp_mail_send_debug', '2', NULL),
                                                         (38, 'smtp_send_debug_adres', NULL, NULL),
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


CREATE TABLE IF NOT EXISTS `slider` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                        `abstract` text DEFAULT NULL,
                                        PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


INSERT INTO `slider` (`id`, `title`, `link`, `text`, `img`, `show_order`, `site_disi_link`, `back_link`, `yeni_sekme`, `lang`, `lang_id`, `user_id`, `status`, `deleted`, `abstract`) VALUES
                                                                                                                                                                                          (1, 'Example Slider 1', 'example-slider-1', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p><span style=\"color:#e74c3c;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '63233ca52c3255.86193474-1663253669.jpg', 1, 0, NULL, 0, 'tr', '20220915174933', 81, 1, 0, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>'),
                                                                                                                                                                                          (2, 'Example Slider 2', 'example-slider-2', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p><u><em><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</strong></em></u></p>', '63233cae7d1b90.84144918-1663253678.jpg', 2, 0, NULL, 0, 'tr', '20220915175036', 81, 1, 0, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>'),
                                                                                                                                                                                          (3, 'Example Slider 3', 'example-slider-3', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '63233cb70bfc47.55895155-1663253687.jpg', 3, 0, NULL, 0, 'tr', '20220915175115', 81, 1, 0, NULL);


CREATE TABLE IF NOT EXISTS `tags` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `name` varchar(255) NOT NULL,
                                      `link` varchar(255) NOT NULL,
                                      `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `email` varchar(250) NOT NULL,
                                       `password` varchar(250) NOT NULL,
                                       `name` varchar(250) NOT NULL,
                                       `surname` varchar(250) NOT NULL,
                                       `img` varchar(255) DEFAULT NULL,
                                       `telefon` varchar(255) DEFAULT NULL,
                                       `role_group` int(11) NOT NULL DEFAULT '1' COMMENT 'yetkisi oldu rol grubu',
                                       `rank` int(11) NOT NULL DEFAULT '1' COMMENT '1:normal_üye,90:admin',
                                       `email_verify` tinyint(1) NOT NULL DEFAULT '0',
                                       `send_mail` int(1) NOT NULL DEFAULT '0' COMMENT 'mail gönderilecek mi mail istiyor mu',
                                       `verify_code` varchar(255) DEFAULT NULL,
                                       `theme` tinyint(1) DEFAULT '1' COMMENT 'admin paneli teması 1:koyu,2:açık',
                                       `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1:onaylı,2:onaysız,3:bekliyor',
                                       `deleted` tinyint(1) NOT NULL DEFAULT '0',
                                       `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                       `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                       PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `email`, `password`, `name`, `surname`, `img`, `telefon`, `role_group`, `rank`, `email_verify`, `send_mail`, `verify_code`, `theme`, `status`, `deleted`) VALUES
    (81, 'demo@demo.com', '$2y$10$.jci1zvg95FQAih9pTLphunLcJZ8TWJpOUX2P6l5AdOl/Affo3Esq', 'Demo', 'Demo', '', '', 1, 90, 1, 1, '', 1, 1, 0);


create table if not exists audit_log
(
    id                  int(11) unsigned auto_increment
        primary key,
    action_log_id       int                           null,
    table_name          varchar(255)                  not null,
    row_id              int                           null,
    field_name          varchar(255) default ''       not null,
    old_value           text                  null,
    new_value           text                  null,
    activity            varchar(10)      default 'UPDATE' not null,
    modified_datetime   datetime                      null,
    modified_by_user_id int                           not null
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `youtube_videos` (
                                                `id` int(11) NOT NULL AUTO_INCREMENT,
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
                                                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                                PRIMARY KEY (`id`)
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

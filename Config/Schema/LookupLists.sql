CREATE TABLE `lookup_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `lookup_list_item_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique` (`slug`)
) DEFAULT CHARSET=latin1;

CREATE TABLE `lookup_list_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lookup_list_id` int(10) unsigned NOT NULL,
  `item_id` int(10) NOT NULL,
  `slug` varchar(34) NOT NULL,
  `value` varchar(256) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `public` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_2` (`lookup_list_id`,`slug`) USING BTREE,
  UNIQUE KEY `idx_unique` (`lookup_list_id`,`item_id`)
) DEFAULT CHARSET=latin1;

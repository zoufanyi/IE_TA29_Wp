CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_debug` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `data` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1 DEFAULT COLLATE="utf8_general_ci";

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_forms_elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `f_id` int(10) unsigned NOT NULL,
  `e_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `f_id` (`f_id`)
) AUTO_INCREMENT=1 DEFAULT COLLATE="utf8_general_ci";

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_forms_options` (
  `f_id` int(10) unsigned NOT NULL,
  `o_id` int(10) unsigned NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`f_id`,`o_id`)
) DEFAULT COLLATE="utf8_general_ci";

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` mediumtext NOT NULL,
  `description` text NOT NULL,
  `description_long` text NOT NULL,
  `type` text NOT NULL,
  `cat` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) DEFAULT COLLATE="utf8_general_ci";

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_submissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `f_id` int(10) unsigned NOT NULL,
  `data` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(50) NOT NULL,
  `ref_id` VARCHAR(16) NOT NULL,
  `total` DOUBLE NOT NULL,
  `payment_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `transaction_id` VARCHAR(50) NOT NULL,
  `token` VARCHAR(20) NOT NULL,
  `user_mail` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `f_id` (`f_id`)
) DEFAULT COLLATE="utf8_general_ci";

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `data` mediumtext NOT NULL,
  `options` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1000 DEFAULT COLLATE="utf8_general_ci";


CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_files` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`f_id` INT(10) UNSIGNED NOT NULL,
	`ref_id` VARCHAR(16) NOT NULL,
	`url` VARCHAR(2048) NOT NULL,
	`file` VARCHAR(2048) NOT NULL,
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=5 DEFAULT COLLATE="utf8_general_ci";

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_forms_views` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` INT(10) UNSIGNED NOT NULL,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `form_id` (`form_id`)
);

CREATE TABLE IF NOT EXISTS `__PREFIX__ezfc_preview` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `f_id` INT(11) NOT NULL,
  `data` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `f_id` (`f_id`)
)
COLLATE='latin1_swedish_ci';

REPLACE INTO `__PREFIX__ezfc_preview` (`id`, `f_id`, `data`) VALUES
  (1, 0, '{"form":{"id":"58","name":"Wizard"},"elements":[{"id":"21","f_id":"58","e_id":"18","data":"{\\"name\\":\\"General\\",\\"title\\":\\"General\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"18\\"}","position":"21"},{"id":"20","f_id":"58","e_id":"2","data":"{\\"name\\":\\"form__email_recipient\\",\\"label\\":\\"Email address\\",\\"required\\":\\"0\\",\\"use_address\\":\\"0\\",\\"double_check\\":\\"0\\",\\"allow_multiple\\":\\"0\\",\\"value\\":\\"\\",\\"value_external\\":\\"\\",\\"placeholder\\":\\"your@email.com\\",\\"icon\\":\\"\\",\\"show_in_email\\":\\"1\\",\\"description\\":\\"Submissions will be sent to this email address\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"GET\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"2\\"}","position":"20"},{"id":"19","f_id":"58","e_id":"1","data":"{\\"name\\":\\"form__email_admin_sender\\",\\"label\\":\\"Email sender name\\",\\"required\\":\\"0\\",\\"value\\":\\"\\",\\"value_external\\":\\"\\",\\"placeholder\\":\\"Ezplugins <hello@ezplugins.de>\\",\\"icon\\":\\"\\",\\"is_telephone_nr\\":\\"0\\",\\"custom_regex\\":\\"\\",\\"custom_error_message\\":\\"\\",\\"custom_filter\\":\\"\\",\\"show_in_email\\":\\"1\\",\\"description\\":\\"Sender name in emails. Please make sure to use the correct syntax: Name <your@email.com>\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"GET\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"1\\"}","position":"19"},{"id":"18","f_id":"58","e_id":"1","data":"{\\"name\\":\\"form__currency\\",\\"label\\":\\"Currency\\",\\"required\\":\\"0\\",\\"value\\":\\"$\\",\\"value_external\\":\\"\\",\\"placeholder\\":\\"\\",\\"icon\\":\\"\\",\\"is_telephone_nr\\":\\"0\\",\\"custom_regex\\":\\"\\",\\"custom_error_message\\":\\"\\",\\"custom_filter\\":\\"\\",\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"GET\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"1\\"}","position":"18"},{"id":"17","f_id":"58","e_id":"4","data":"{\\"name\\":\\"form__currency_position\\",\\"label\\":\\"Currency position\\",\\"required\\":\\"0\\",\\"calculate_enabled\\":\\"1\\",\\"is_currency\\":\\"1\\",\\"options\\":[{\\"value\\":\\"0\\",\\"text\\":\\"Before\\"},{\\"value\\":\\"1\\",\\"text\\":\\"After\\"}],\\"calculate\\":[{\\"operator\\":\\"0\\",\\"target\\":\\"0\\",\\"value\\":\\"\\"}],\\"overwrite_price\\":\\"0\\",\\"calculate_when_hidden\\":\\"1\\",\\"calculate_before\\":\\"0\\",\\"conditional\\":[{\\"action\\":\\"0\\",\\"target\\":\\"0\\",\\"target_value\\":\\"\\",\\"operator\\":\\"0\\",\\"value\\":\\"\\",\\"redirect\\":\\"\\"}],\\"discount\\":[{\\"range_min\\":\\"\\",\\"range_max\\":\\"\\",\\"operator\\":\\"0\\",\\"discount_value\\":\\"\\"}],\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"4\\"}","position":"17"},{"id":"16","f_id":"58","e_id":"4","data":"{\\"name\\":\\"price_format\\",\\"label\\":\\"Price format\\",\\"required\\":\\"0\\",\\"calculate_enabled\\":\\"1\\",\\"is_currency\\":\\"1\\",\\"options\\":[{\\"value\\":\\"default\\",\\"text\\":\\"Default: $1,337.99\\"},{\\"value\\":\\"eu\\",\\"text\\":\\"European: $1.337,99\\"}],\\"calculate\\":[{\\"operator\\":\\"0\\",\\"target\\":\\"0\\",\\"value\\":\\"\\"}],\\"overwrite_price\\":\\"0\\",\\"calculate_when_hidden\\":\\"1\\",\\"calculate_before\\":\\"0\\",\\"conditional\\":[{\\"action\\":\\"0\\",\\"target\\":\\"0\\",\\"target_value\\":\\"\\",\\"operator\\":\\"0\\",\\"value\\":\\"\\",\\"redirect\\":\\"\\"}],\\"discount\\":[{\\"range_min\\":\\"\\",\\"range_max\\":\\"\\",\\"operator\\":\\"0\\",\\"discount_value\\":\\"\\"}],\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"4\\"}","position":"16"},{"id":"15","f_id":"58","e_id":"6","data":"{\\"name\\":\\"show_decimal_numbers\\",\\"label\\":\\"Always show decimal numbers\\",\\"required\\":\\"0\\",\\"calculate_enabled\\":\\"1\\",\\"is_currency\\":\\"1\\",\\"options\\":[{\\"value\\":\\"1\\",\\"text\\":\\"Yes\\",\\"image\\":\\"\\"}],\\"calculate\\":[{\\"operator\\":\\"0\\",\\"target\\":\\"0\\",\\"value\\":\\"\\"}],\\"overwrite_price\\":\\"0\\",\\"calculate_when_hidden\\":\\"1\\",\\"calculate_before\\":\\"0\\",\\"conditional\\":[{\\"action\\":\\"0\\",\\"target\\":\\"0\\",\\"target_value\\":\\"\\",\\"operator\\":\\"0\\",\\"value\\":\\"\\",\\"redirect\\":\\"\\"}],\\"discount\\":[{\\"range_min\\":\\"\\",\\"range_max\\":\\"\\",\\"operator\\":\\"0\\",\\"discount_value\\":\\"\\"}],\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"max_width\\":\\"\\",\\"max_height\\":\\"\\",\\"inline\\":\\"0\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"6\\",\\"preselect\\":\\"\\"}","position":"15"},{"id":"14","f_id":"58","e_id":"19","data":"{\\"name\\":\\"Step end\\",\\"previous_step\\":\\"Previous Step\\",\\"next_step\\":\\"Next Step\\",\\"add_line\\":\\"1\\",\\"columns\\":6,\\"group_id\\":0,\\"e_id\\":\\"19\\"}","position":"14"},{"id":"13","f_id":"58","e_id":"18","data":"{\\"name\\":\\"PayPal\\",\\"title\\":\\"PayPal\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"18\\"}","position":"13"},{"id":"12","f_id":"58","e_id":"6","data":"{\\"name\\":\\"global__use_paypal\\",\\"label\\":\\"Do you want to use PayPal?\\",\\"required\\":\\"0\\",\\"calculate_enabled\\":\\"1\\",\\"is_currency\\":\\"1\\",\\"options\\":[{\\"value\\":\\"1\\",\\"text\\":\\"Yes\\",\\"image\\":\\"\\"}],\\"calculate\\":[{\\"operator\\":\\"0\\",\\"target\\":\\"0\\",\\"value\\":\\"\\"}],\\"overwrite_price\\":\\"0\\",\\"calculate_when_hidden\\":\\"1\\",\\"calculate_before\\":\\"0\\",\\"conditional\\":[{\\"action\\":\\"show\\",\\"target\\":\\"11\\",\\"target_value\\":\\"\\",\\"operator\\":\\"equals\\",\\"value\\":\\"1\\",\\"redirect\\":\\"\\"}],\\"discount\\":[{\\"range_min\\":\\"\\",\\"range_max\\":\\"\\",\\"operator\\":\\"0\\",\\"discount_value\\":\\"\\"}],\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"max_width\\":\\"\\",\\"max_height\\":\\"\\",\\"inline\\":\\"0\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"0\\",\\"e_id\\":\\"6\\",\\"preselect\\":\\"\\"}","position":"12"},{"id":"11","f_id":"58","e_id":"25","data":"{\\"name\\":\\"Group\\",\\"wrapper_class\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"25\\"}","position":"11"},{"id":"10","f_id":"58","e_id":"1","data":"{\\"name\\":\\"global__pp_username\\",\\"label\\":\\"PayPal API username\\",\\"required\\":\\"0\\",\\"value\\":\\"\\",\\"value_external\\":\\"\\",\\"placeholder\\":\\"\\",\\"icon\\":\\"\\",\\"is_telephone_nr\\":\\"0\\",\\"custom_regex\\":\\"\\",\\"custom_error_message\\":\\"\\",\\"custom_filter\\":\\"\\",\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"GET\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"1\\"}","position":"10"},{"id":"9","f_id":"58","e_id":"1","data":"{\\"name\\":\\"global__pp_password\\",\\"label\\":\\"PayPal API password\\",\\"required\\":\\"0\\",\\"value\\":\\"\\",\\"value_external\\":\\"\\",\\"placeholder\\":\\"\\",\\"icon\\":\\"\\",\\"is_telephone_nr\\":\\"0\\",\\"custom_regex\\":\\"\\",\\"custom_error_message\\":\\"\\",\\"custom_filter\\":\\"\\",\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"GET\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"1\\"}","position":"9"},{"id":"8","f_id":"58","e_id":"1","data":"{\\"name\\":\\"global__pp_signature\\",\\"label\\":\\"PayPal API signature\\",\\"required\\":\\"0\\",\\"value\\":\\"\\",\\"value_external\\":\\"\\",\\"placeholder\\":\\"\\",\\"icon\\":\\"\\",\\"is_telephone_nr\\":\\"0\\",\\"custom_regex\\":\\"\\",\\"custom_error_message\\":\\"\\",\\"custom_filter\\":\\"\\",\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"GET\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"1\\"}","position":"8"},{"id":"7","f_id":"58","e_id":"6","data":"{\\"name\\":\\"global__pp_sandbox\\",\\"label\\":\\"Use sandbox\\",\\"required\\":\\"0\\",\\"calculate_enabled\\":\\"1\\",\\"is_currency\\":\\"1\\",\\"options\\":[{\\"value\\":\\"1\\",\\"text\\":\\"Yes\\",\\"image\\":\\"\\"}],\\"calculate\\":[{\\"operator\\":\\"0\\",\\"target\\":\\"0\\",\\"value\\":\\"\\"}],\\"overwrite_price\\":\\"0\\",\\"calculate_when_hidden\\":\\"1\\",\\"calculate_before\\":\\"0\\",\\"conditional\\":[{\\"action\\":\\"show\\",\\"target\\":\\"0\\",\\"target_value\\":\\"\\",\\"operator\\":\\"equals\\",\\"value\\":\\"1\\",\\"redirect\\":\\"\\"}],\\"discount\\":[{\\"range_min\\":\\"\\",\\"range_max\\":\\"\\",\\"operator\\":\\"0\\",\\"discount_value\\":\\"\\"}],\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"max_width\\":\\"\\",\\"max_height\\":\\"\\",\\"inline\\":\\"0\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"6\\",\\"preselect\\":\\"\\"}","position":"7"},{"id":"6","f_id":"58","e_id":"12","data":"{\\"name\\":\\"Sandbox\\\\/Live\\",\\"html\\":\\"Please make sure to use the correct credentials as sandbox and live credentials are different. You can test the&nbsp;credentials on the Help \\\\/ debug page.\\",\\"show_in_email\\":\\"0\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"12\\"}","position":"6"},{"id":"5","f_id":"58","e_id":"6","data":"{\\"name\\":\\"pp_create_pages\\",\\"label\\":\\"Create PayPal pages\\",\\"required\\":\\"0\\",\\"calculate_enabled\\":\\"1\\",\\"is_currency\\":\\"1\\",\\"options\\":[{\\"value\\":\\"1\\",\\"text\\":\\"Yes\\",\\"image\\":\\"\\"}],\\"calculate\\":[{\\"operator\\":\\"0\\",\\"target\\":\\"0\\",\\"value\\":\\"\\"}],\\"overwrite_price\\":\\"0\\",\\"calculate_when_hidden\\":\\"1\\",\\"calculate_before\\":\\"0\\",\\"conditional\\":[{\\"action\\":\\"show\\",\\"target\\":\\"0\\",\\"target_value\\":\\"\\",\\"operator\\":\\"equals\\",\\"value\\":\\"1\\",\\"redirect\\":\\"\\"}],\\"discount\\":[{\\"range_min\\":\\"\\",\\"range_max\\":\\"\\",\\"operator\\":\\"0\\",\\"discount_value\\":\\"\\"}],\\"show_in_email\\":\\"1\\",\\"description\\":\\"\\",\\"max_width\\":\\"\\",\\"max_height\\":\\"\\",\\"inline\\":\\"0\\",\\"class\\":\\"\\",\\"wrapper_class\\":\\"\\",\\"style\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"6\\",\\"preselect\\":\\"\\"}","position":"5"},{"id":"4","f_id":"58","e_id":"12","data":"{\\"name\\":\\"Create pages info\\",\\"html\\":\\"The plugin can create all relevant PayPal sites for you automatically. The plugin will create 2 new sites with the relevant shortcodes. Please note that if you change the permalink of the pages, you need to update the pages in the global settings as well.\\",\\"show_in_email\\":\\"0\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"12\\"}","position":"4"},{"id":"3","f_id":"58","e_id":"27","data":"{\\"name\\":\\"Spacer\\",\\"height\\":\\"30\\",\\"wrapper_class\\":\\"\\",\\"wrapper_style\\":\\"\\",\\"style\\":\\"\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"27\\"}","position":"3"},{"id":"2","f_id":"58","e_id":"12","data":"{\\"name\\":\\"Currency Info\\",\\"html\\":\\"&lt;strong&gt;You need to&nbsp;set the currency code in the global settings manually.&lt;\\\\/strong&gt;\\",\\"show_in_email\\":\\"0\\",\\"hidden\\":\\"0\\",\\"columns\\":\\"6\\",\\"group_id\\":\\"11\\",\\"e_id\\":\\"12\\"}","position":"2"},{"id":"1","f_id":"58","e_id":"19","data":"{\\"name\\":\\"Step end\\",\\"previous_step\\":\\"Previous Step\\",\\"next_step\\":\\"Next Step\\",\\"add_line\\":\\"1\\",\\"columns\\":6,\\"group_id\\":0,\\"e_id\\":\\"19\\"}","position":"1"}],"options":{"1":{"value":"ms@roberto-gruppe.de"},"2":{"value":"Thank you for your submission!"},"3":{"value":"60"},"4":{"value":"0"},"5":{"value":"€"},"6":{"value":"Price"},"7":{"value":"0"},"8":{"value":"1"},"9":{"value":"0"},"10":{"value":""},"11":{"value":"Your submission!!"},"12":{"value":"Thank you for your submission, we will contact you soon!\\r\\n\\r\\n{{result_simple}}"},"13":{"value":"New submission"},"14":{"value":"You have received a new submission:\\r\\n\\r\\n{{result}}"},"15":{"value":"Save"},"16":{"value":"Add to cart"},"17":{"value":"ezfc-autowidth"},"18":{"value":"1"},"19":{"value":"slick"},"20":{"value":"1"},"21":{"value":"mm/dd/yy"},"22":{"value":"0"},"23":{"value":"Check out with PayPal"},"24":{"value":"Your submission"},"25":{"value":"Thank you for your submission,\\r\\n\\r\\nwe have received your payment via PayPal."},"26":{"value":"We have received your payment, thank you!"},"27":{"value":""},"28":{"value":""},"29":{"value":"Minimum submission value is %s"},"30":{"value":"0"},"31":{"value":"-1"},"32":{"value":"0"},"33":{"value":"H:i"},"34":{"value":"0,0[.]00"},"35":{"value":""},"36":{"value":"0"},"37":{"value":"0"},"38":{"value":"0"},"39":{"value":"Request price"},"40":{"value":"-"},"41":{"value":"0"},"42":{"value":""},"43":{"value":""},"44":{"value":""},"45":{"value":""},"46":{"value":"1000"},"47":{"value":"30"},"48":{"value":"0"},"49":{"value":"20"},"50":{"value":"1"},"51":{"value":".ezfc-form-58 .ezfc-element-wrapper-spacer {height:px;}"},"52":{"value":""},"53":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"54":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"55":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"56":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"57":{"value":"a:4:{s:5:\\"color\\";s:0:\\"\\";s:5:\\"width\\";s:0:\\"\\";s:5:\\"style\\";s:4:\\"none\\";s:6:\\"radius\\";s:0:\\"\\";}"},"58":{"value":""},"59":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"60":{"value":""},"61":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"62":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"63":{"value":"a:4:{s:5:\\"color\\";s:0:\\"\\";s:5:\\"width\\";s:0:\\"\\";s:5:\\"style\\";s:4:\\"none\\";s:6:\\"radius\\";s:0:\\"\\";}"},"64":{"value":""},"65":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"66":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"67":{"value":"a:4:{s:5:\\"color\\";s:0:\\"\\";s:5:\\"width\\";s:0:\\"\\";s:5:\\"style\\";s:4:\\"none\\";s:6:\\"radius\\";s:0:\\"\\";}"},"68":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"69":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"70":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"71":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"72":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"73":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"74":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"75":{"value":"0"},"76":{"value":"a:2:{s:7:\\"enabled\\";s:1:\\"0\\";s:4:\\"text\\";s:0:\\"\\";}"},"77":{"value":""},"78":{"value":""},"79":{"value":"0"},"80":{"value":"Summary"},"81":{"value":"Check your order"},"82":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"83":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"84":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"85":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"86":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"87":{"value":"a:1:{s:5:\\"color\\";s:0:\\"\\";}"},"88":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"89":{"value":"0"},"90":{"value":""},"91":{"value":""},"92":{"value":"Gesamt"},"93":{"value":"a:2:{s:5:\\"value\\";s:0:\\"\\";s:4:\\"unit\\";s:2:\\"px\\";}"},"94":{"value":"0"},"95":{"value":"0"},"96":{"value":"0"},"97":{"value":"0"},"98":{"value":"0"},"99":{"value":"0"},"100":{"value":"1"},"101":{"value":"Step %d"},"102":{"value":"1"},"103":{"value":"1"},"104":{"value":"0"},"105":{"value":"0"}},"version":"2.9.4.0"}');
<?php
	/*
	MGB 0.7.x - OpenSource PHP and MySql Guestbook
	Copyright (C) 2004 - 2013 Juergen Grueneisl - http://www.m-gb.org/

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

	=======
	061.php
	=======
	*/

	// 0.6 - 0.6.3

	if(file_exists("../functions.inc.php")) {
		if(unlink("../functions.inc.php")) {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''functions.inc.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">&nbsp;OK!<br /></span>\n";
			$success++;
			$count++;
		} else {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''functions.inc.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: red;\">ERROR!<br /></span>\n";
			$count++;
		}
	}

	if(file_exists("../load_templates.inc.php")) {
		if(unlink("../load_templates.inc.php")) {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''load_templates.inc.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">&nbsp;OK!<br /></span>\n";
			$success++;
			$count++;
		} else {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''load_templates.inc.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: red;\">ERROR!<br /></span>\n";
			$count++;
		}
	}

	if(file_exists("../load_settings.inc.php")) {
		if(unlink("../load_settings.inc.php")) {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''load_settings.inc.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">&nbsp;OK!<br /></span>\n";
			$success++;
			$count++;
		} else {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''load_settings.inc.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: red;\">ERROR!<br /></span>\n";
			$count++;
		}
	}

	if(file_exists("../captcha.php")) {
		if(unlink("../captcha.php")) {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''captcha.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">&nbsp;OK!<br /></span>\n";
			$success++;
			$count++;
		} else {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''captcha.php'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: red;\">ERROR!<br /></span>\n";
			$count++;
		}
	}

	if(file_exists("../akoom.ttf")) {
		if(unlink("../akoom.ttf")) {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''akoom.ttf'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">&nbsp;OK!<br /></span>\n";
			$success++;
			$count++;
		} else {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- deleting old ''akoom.ttf'' from root directory...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: red;\">ERROR!<br /></span>\n";
			$count++;
		}
	}

	if(file_exists("../config.inc.php")) {
		if(rename("../config.inc.php", "../config.inc.bak")) {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- renaming old ''config.inc.php'' to ''config.inc.bak''...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">&nbsp;OK!<br /></span>\n";
			$success++;
			$count++;
		} else {
			echo "\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold;\">- renaming old ''config.inc.php'' to ''config.inc.bak''...</span>\n
				\t\t<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: red;\">ERROR!<br /></span>\n";
			$count++;
		}
	}

	$sql = array();

	// 0.6.4

	$sql[1] = "CREATE TABLE ".$db['prefix']."captcha_math (
		`math` VARCHAR( 20 ) NOT NULL ,
		  `sum` INT NOT NULL ,
		  PRIMARY KEY ( `math` )
		  );";
	$sqldescription[1] = "- Creating table for new captcha method...";

	$sql[2] = "INSERT INTO ".$db['prefix']."captcha_math (
			`math` ,
			`sum`
		) VALUES (
			'1+2+3' ,
			'6'
			);";
	$sqldescription[2] = "- Inserting default values for new captcha method...";
	$sqlisinsert[2] = 1;

	$sql[3] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `captcha_method` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `captcha`";
	$sqldescription[3] = "- Inserting 'captcha_method' in settings table...";

	// 0.6.5

	$sql[4] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `akismet_plugin` TINYINT(1) DEFAULT '0' NOT NULL AFTER `bbcode`";
	$sqldescription[4] = "- Adding 'akismet_plugin' in settings table...";

	$sql[5] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `akismet_api` VARCHAR(50) NOT NULL AFTER `akismet_plugin`";
	$sqldescription[5] = "- Adding 'akismet_api' in settings table...";

	$sql[6] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `akismet_mark_as_spam` TINYINT( 1 ) DEFAULT '1' NOT NULL AFTER `akismet_api`";
	$sqldescription[6] = "- Adding 'akismet_mark_as_spam' in settings table...";

	$sql[7] = "ALTER TABLE `".$db['prefix']."entries`
			ADD `isspam` TINYINT( 1 ) NOT NULL AFTER `checked`";
	$sqldescription[7] = "- Adding 'isspam' in entries table...";

	$sql[8] = "ALTER TABLE `".$db['prefix']."user`
			ADD `r_spam` TINYINT( 1 ) NOT NULL AFTER `r_edit`";
	$sqldescription[8] = "- Adding 'r_spam' in user table...";

	// 0.6.6

	$sql[9] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `time_lock` INT( 1 ) NOT NULL AFTER `akismet_mark_as_spam`";
	$sqldescription[9] = "- Adding 'time_lock' in settings table...";

	$sql[10] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `time_lock_value` INT( 3 ) DEFAULT '30' NOT NULL AFTER `time_lock`";
	$sqldescription[10] = "- Adding 'time_lock_value' in settings table...";

	$sql[11] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `time_lock_maxtime` INT DEFAULT '300' NOT NULL AFTER `time_lock_value`";
	$sqldescription[11] = "- Adding 'time_lock_maxtime' in settings table...";

	$sql[12] = "ALTER TABLE `".$db['prefix']."user`
			ADD `r_edit_smilies` TINYINT( 1 ) NOT NULL AFTER `r_spam`";
	$sqldescription[12] = "- Adding 'r_edit_smilies' in user table...";

	// 0.6.7

	$sql[13] = "CREATE TABLE ".$db['prefix']."smilies (
		  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		  `path` VARCHAR( 255 ) NOT NULL ,
		  `replacement` VARCHAR( 255 ) NOT NULL ,
		  `height` TINYINT( 4 ) NOT NULL ,
		  `width` TINYINT( 4 ) NOT NULL
		  );";
	$sqldescription[13] = "- Creating table for smilies...";

	$sql[14] = "INSERT INTO ".$db['prefix']."smilies (
		  `ID` ,
		  `path` ,
		  `replacement` ,
		  `height` ,
		  `width`
		  ) VALUES 	( NULL , 'smiley_smile.gif', ':smile:, :), :-)', '15', '15' ),
					( NULL , 'smiley_wink.gif', ':wink:, ;), ;-)', '15', '15' ),
					( NULL , 'smiley_lol.gif', ':lol:', '15', '15' ),
					( NULL , 'smiley_biggrin.gif', ':biggrin:, :D, :-D', '15', '15' ),
					( NULL , 'smiley_cool.gif', ':cool:, B), B-)', '15', '15' ),
					( NULL , 'smiley_fun.gif', ':fun:, ^^', '15', '15' ),
					( NULL , 'smiley_surprised.gif', ':surprised:, :O, :-O', '15', '15' ),
					( NULL , 'smiley_tongue.gif', ':tongue:, :P, :-P', '15', '15' ),
					( NULL , 'smiley_confused.gif', ':confused:, :-/', '15', '15' ),
					( NULL , 'smiley_eek.gif', ':eek:, 8O, 8-O', '15', '15' ),
					( NULL , 'smiley_doubt.gif', ':doubt:', '15', '15' ),
					( NULL , 'smiley_neutral.gif', ':neutral:, :|, :-|', '15', '15' ),
					( NULL , 'smiley_redface.gif', ':redface:', '15', '15' ),
					( NULL , 'smiley_rolleyes.gif', ':rolleyes:', '15', '15' ),
					( NULL , 'smiley_silenced.gif', ':silenced:', '15', '15' ),
					( NULL , 'smiley_sad.gif', ':sad:, :(, :-(', '15', '15' ),
					( NULL , 'smiley_cry.gif', ':cry:, :\'(, :\'-(', '15', '15' ),
					( NULL , 'smiley_doh.gif', ':doh:', '15', '15' ),
					( NULL , 'smiley_angry.gif', ':angry:', '15', '15' ),
					( NULL , 'smiley_evil.gif', ':evil:', '15', '15' ),
					( NULL , 'icon_arrow.gif', ':arrow:', '15', '15' ),
					( NULL , 'icon_exclaim.gif', ':exclaim:', '15', '15' ),
					( NULL , 'icon_question.gif', ':question:', '15', '15' );";
	$sqldescription[14] = "- Adding smilies...";
	$sqlisinsert[14] = 1;

	// 0.6.8

	$sql[15] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `gravatar_type` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `gravatar_rating`";
	$sqldescription[15] = "- Adding 'gravatar_type' in settings table...";

	$sql[16] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `gravatar_size` INT( 3 ) NOT NULL DEFAULT '50' AFTER `gravatar_type`";
	$sqldescription[16] = "- Adding 'gravatar_size' in settings table...";

	$sql[17] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `gravatar_position` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `gravatar_size` ";
	$sqldescription[17] = "- Adding 'gravatar_position' in settings table...";

	$sql[18] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `entries_order_asc_desc` VARCHAR( 4 ) NOT NULL DEFAULT 'DESC' AFTER `entries_order`";
	$sqldescription[18] = "- Adding 'entries_order_asc_desc' in settings table...";

	$sql[19] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `entries_numbering` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `entries_order_asc_desc`";
	$sqldescription[19] = "- Adding 'entries_numbering' in settings table...";

	$sql[20] = "ALTER TABLE `".$db['prefix']."settings`
			CHANGE `entries_order` `entries_order` VARCHAR( 11 ) NOT NULL DEFAULT 'ID'";
	$sqldescription[20] = "- Changing 'entries_order' in settings table...";

	$sql[21] = "UPDATE `".$db['prefix']."settings` SET `entries_order` = 'ID'";
	$sqldescription[21] = "- Changing value of 'entries_order' in settings table...";
	$sqlisinsert[21] = 1;

	$sql[22] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `smileys_break` INT( 2 ) NOT NULL DEFAULT '11' AFTER `smileys`";
	$sqldescription[22] = "- Adding 'smileys_break' in settings table...";

	$sql[23] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `smileys_order` VARCHAR( 4 ) NOT NULL DEFAULT 'ASC' AFTER `smileys_break`";
	$sqldescription[23] = "- Adding 'smileys_order' in settings table...";

	$sql[24] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `password_min_length` TINYINT( 2 ) NOT NULL DEFAULT '8' AFTER `session_timeout`";
	$sqldescription[24] = "- Adding 'password_min_length' in settings table...";

	// 0.6.9

	$sql[25] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `allow_img_tag` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `bbcode`";
	$sqldescription[25] = "- Adding 'allow_img_tag' in settings table...";

	$sql[26] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `max_img_width` INT( 4 ) NOT NULL DEFAULT '400' AFTER `allow_img_tag`";
	$sqldescription[26] = "- Adding 'max_img_width' in settings table...";

	$sql[27] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `max_img_height` INT( 4 ) NOT NULL DEFAULT '400' AFTER `max_img_width`";
	$sqldescription[27] = "- Adding 'max_img_height' in settings table...";

	$sql[28] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `center_img` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `max_img_height`";
	$sqldescription[28] = "- Adding 'center_img' in settings table...";

	$sql[29] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `allow_flash_tag` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `center_img`";
	$sqldescription[29] = "- Adding 'allow_flash_tag' in settings table...";

	$sql[30] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `max_flash_width` INT( 4 ) NOT NULL DEFAULT '400' AFTER `allow_flash_tag`";
	$sqldescription[30] = "- Adding 'max_flash_width' in settings table...";

	$sql[31] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `max_flash_height` INT( 4 ) NOT NULL DEFAULT '400' AFTER `max_flash_width`";
	$sqldescription[31] = "- Adding 'max_flash_height' in settings table...";

	$sql[32] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `center_flash` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `max_flash_height`";
	$sqldescription[32] = "- Adding 'center_flash' in settings table...";

	$sql[33] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `captcha_coords_x` INT( 3 ) NOT NULL DEFAULT '20' AFTER `captcha_method`";
	$sqldescription[33] = "- Adding 'captcha_coords_x' in settings table...";

	$sql[34] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `captcha_coords_y` INT( 3 ) NOT NULL DEFAULT '25' AFTER `captcha_coords_x`";
	$sqldescription[34] = "- Adding 'captcha_coords_y' in settings table...";

	$sql[35] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `captcha_color` VARCHAR( 6 ) NOT NULL DEFAULT '505050' AFTER `captcha_coords_y`";
	$sqldescription[35] = "- Adding 'captcha_color' in settings table...";

	$sql[36] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `captcha_angle_1` INT( 4 ) NOT NULL DEFAULT '-10' AFTER `captcha_color`";
	$sqldescription[36] = "- Adding 'captcha_angle_1' in settings table...";

	$sql[37] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `captcha_angle_2` INT( 4 ) NOT NULL DEFAULT '5' AFTER `captcha_angle_1` ";
	$sqldescription[37] = "- Adding 'captcha_angle_2' in settings table...";

	// 0.6.9.1 - 0.6.9.3

	$sql[38] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `timezone` VARCHAR( 255 ) NOT NULL DEFAULT 'Europe/Berlin' AFTER `h_description`";
	$sqldescription[38] = "- Adding 'timezone' in settings table...";

	// 0.6.9.4

	$sql[39] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `require_email` TINYINT ( 1 ) NOT NULL DEFAULT '1' AFTER `moderated`";
	$sqldescription[39] = "- Adding 'require_email' in settings table...";

	$sql[40] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `sendmail_user_text_moderated` MEDIUMTEXT NOT NULL AFTER `sendmail_user_text`";
	$sqldescription[40] = "- Adding 'sendmail_user_text_moderated' in settings table...";

	$sql[41] = "ALTER TABLE `".$db['prefix']."settings`
			ADD `sendmail_contactmail_text_copy` MEDIUMTEXT NOT NULL AFTER `sendmail_contactmail_text`";
	$sqldescription[41] = "- Adding 'sendmail_contactmail_text_copy' in settings table...";

	include("../language/".$settings['language_path']."/lang_admin.php");

	$sql[42] = "UPDATE `".$db['prefix']."settings` SET
			`sendmail_user_text` = '".$lang['sendmail_user_text']."',
			`sendmail_user_text_moderated` = '".$lang['sendmail_user_text_moderated']."',
			`sendmail_contactmail_text_copy` = '".$lang['sendmail_contactmail_text_copy']."';";
	$sqldescription[42] = "- Inserting values in new fields ...";
	$sqlisinsert[42] = 1;

	$sql[43] = "ALTER TABLE `".$db['prefix']."user` ADD
			`user_ip` VARCHAR( 255 ) NOT NULL AFTER `user_key`";
	$sqldescription[43] = "- Adding 'user_ip' in user table...";

	// 0.6.9.5

	$sql[44] = "CREATE TABLE IF NOT EXISTS ".$db['prefix']."spam (
			  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `name` VARCHAR( 255 ) NOT NULL ,
			  `ip` VARCHAR( 15 ) NOT NULL ,
			  `email` VARCHAR( 255 ) NOT NULL ,
			  `city` VARCHAR( 255 ) NOT NULL ,
			  `icq` VARCHAR( 255 ) NOT NULL ,
			  `aim` VARCHAR( 255 ) NOT NULL ,
			  `msn` VARCHAR( 255 ) NOT NULL ,
			  `fb` VARCHAR( 255 ) NOT NULL ,
			  `twitter` VARCHAR( 255 ) NOT NULL ,
			  `hp` VARCHAR( 255 ) NOT NULL ,
			  `message` MEDIUMTEXT NOT NULL ,
			  `comment` MEDIUMTEXT NOT NULL ,
			  `user_notification` TINYINT( 1 ) NOT NULL ,
			  `user_show_email` TINYINT( 1 ) NOT NULL ,
			  `captcha` VARCHAR( 9 ) NOT NULL ,
			  `sent_captcha` VARCHAR( 9 ) NOT NULL ,
			  `counter` TINYINT( 1 ) NOT NULL ,
			  `timestamp` INT( 11 ) NOT NULL
			  )";
	$sqldescription[44] = "- Creating spam table ...";

	$sql[45] = "CREATE TABLE IF NOT EXISTS ".$db['prefix']."banlist_ips (
			  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `banned_ip` VARCHAR( 255 ) NOT NULL ,
			  `banned_ip_first` VARCHAR( 255 ) NOT NULL ,
			  `banned_ip_second` VARCHAR( 255 ) NOT NULL ,
			  `banned_ip_third` VARCHAR( 255 ) NOT NULL ,
			  `banned_ip_fourth` VARCHAR( 255 ) NOT NULL ,
			  `matches` INT( 11 ) NOT NULL ,
			  `timestamp` INT( 11 ) NOT NULL
			  )";
	$sqldescription[45] = "- Creating ip banlist ...";

	$sql[46] = "CREATE TABLE IF NOT EXISTS ".$db['prefix']."banlist_emails (
			  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `banned_email` VARCHAR( 255 ) NOT NULL ,
			  `banned_email_first` VARCHAR( 255 ) NOT NULL ,
			  `banned_email_second` VARCHAR( 255 ) NOT NULL ,
			  `matches` INT( 11 ) NOT NULL ,
			  `timestamp` INT( 11 ) NOT NULL
			  )";
	$sqldescription[46] = "- Creating email banlist ...";

	$sql[47] = "CREATE TABLE IF NOT EXISTS ".$db['prefix']."banlist_domains (
			  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `banned_domain` VARCHAR( 255 ) NOT NULL ,
			  `matches` INT( 11 ) NOT NULL ,
			  `timestamp` INT( 11 ) NOT NULL
			  )";
	$sqldescription[47] = "- Creating domain banlist ...";

	$sql[48] = "CREATE TABLE IF NOT EXISTS ".$db['prefix']."spam_log (
			  `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `ip` VARCHAR( 255 ) NOT NULL ,
			  `email` VARCHAR( 255 ) NOT NULL ,
			  `user_agent` VARCHAR( 255 ) NOT NULL ,
			  `hp` VARCHAR( 255 ) NOT NULL ,
			  `message` MEDIUMTEXT NOT NULL ,
			  `type` INT( 2 ) NOT NULL ,
			  `site` VARCHAR( 255 ) NOT NULL ,
			  `timestamp` VARCHAR( 255 ) NOT NULL
			  )";
	$sqldescription[48] = "- Creating spam log table ...";

	$sql[49] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`announcement_message` MEDIUMTEXT NOT NULL AFTER `gravatar_position`";
	$sqldescription[49] = "- Adding new field 'announcement_message' in settings table ...";

	$sql[50] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `spam_mail` VARCHAR( 255 ) NOT NULL AFTER `gravatar_position`";
	$sqldescription[50] = "- Adding 'spam_mail' to settings table...";

	$sql[51] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `banlist_emails` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `spam_mail`";
	$sqldescription[51] = "- Adding 'banlist_emails' to settings table...";

	$sql[52] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `banlist_domains` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `banlist_emails`";
	$sqldescription[52] = "- Adding 'banlist_domains' to settings table...";

	$sql[53] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `banlist_ips` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `banlist_domains`";
	$sqldescription[53] = "- Adding 'banlist_ips' to settings table...";

	$sql[54] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `banlist_log` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `banlist_ips`";
	$sqldescription[54] = "- Adding 'banlist_log' to settings table...";

	$sql[55] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `captcha_length` TINYINT( 2 ) NOT NULL DEFAULT '6' AFTER `captcha_method`";
	$sqldescription[55] = "- Adding 'captcha_length' to settings table...";

	$sql[56] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `captcha_double_hash` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `captcha_length`";
	$sqldescription[56] = "- Adding 'captcha_double_hash' to settings table...";

	$sql[57] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `time_lock_spam_count` TINYINT( 2 ) NOT NULL DEFAULT '5' AFTER `time_lock_maxtime`";
	$sqldescription[57] = "- Adding 'time_lock_spam_count' to settings table...";

	$sql[58] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `blocktime` INT( 10 ) NOT NULL DEFAULT '99999999' AFTER `require_email`";
	$sqldescription[58] = "- Adding 'blocktime' to settings table...";

	$sql[59] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `wrong_captcha_count` INT( 2 ) NOT NULL DEFAULT '5' AFTER `captcha_angle_2`";
	$sqldescription[59] = "- Adding 'wrong_captcha_count' to settings table...";

	$sql[60] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `debug_mode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `banlist_log`";
	$sqldescription[60] = "- Adding 'debug_mode' to settings table...";

	$sql[61] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `recaptcha_pub_key` VARCHAR( 255 ) DEFAULT '' NOT NULL AFTER `captcha_angle_2`";
	$sqldescription[61] = "- Adding 'recaptcha_pub_key' to settings table...";

	$sql[62] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `recaptcha_private_key` VARCHAR( 255 ) DEFAULT '' NOT NULL AFTER `recaptcha_pub_key`";
	$sqldescription[62] = "- Adding 'recaptcha_private_key' to settings table...";

	$sql[63] = "ALTER TABLE `".$db['prefix']."settings`
				ADD `recaptcha_style` VARCHAR( 15 ) DEFAULT 'red' NOT NULL AFTER `recaptcha_private_key`";
	$sqldescription[63] = "- Adding 'recaptcha_style' to settings table...";

	$sql[64] = "ALTER TABLE `".$db['prefix']."spam`
				ADD `fb` VARCHAR( 255 ) NOT NULL AFTER `msn`";
	$sqldescription[64] = "- Adding 'fb' to spam table...";

	$sql[65] = "ALTER TABLE `".$db['prefix']."spam`
				ADD `twitter` VARCHAR( 255 ) NOT NULL AFTER `fb`";
	$sqldescription[65] = "- Adding 'twitter' to spam table...";

	$sql[66] = "ALTER TABLE `".$db['prefix']."settings`
				CHANGE `dateform` `dateform` VARCHAR( 255 ) NOT NULL DEFAULT 'd.m.Y, H:i'";
	$sqldescription[66] = "- Update 'dateform' in settings table...";

	$sql[67] = "UPDATE `".$db['prefix']."settings` SET
			`dateform` = 'd.m.Y, H:i';";
	$sqldescription[67] = "- Inserting values in new fields ...";
	$sqlisinsert[67] = 1;

	$sql[68] = "ALTER TABLE `".$db['prefix']."settings` ADD `mailer_method` TINYINT( 1 ) NOT NULL  DEFAULT '0' AFTER `sendmail_contactmail_text_copy` ,
				ADD `smtp_server` VARCHAR( 255 ) NOT NULL AFTER `mailer_method` ,
				ADD `smtp_port` INT( 6 ) NOT NULL DEFAULT '25' AFTER `smtp_server` ,
				ADD `smtp_user` VARCHAR( 255 ) NOT NULL AFTER `smtp_port` ,
				ADD `smtp_password` VARCHAR( 255 ) NOT NULL AFTER `smtp_user`,
				ADD `smtp_auth` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `smtp_password`";
	$sqldescription[68] = "- Adding smtp fields in settings table...";

	$sql[69] = "ALTER TABLE `".$db['prefix']."settings` ADD `keystroke` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `time_lock_spam_count` ,
				ADD `keystroke_max_cps` TINYINT( 2 ) NOT NULL DEFAULT '8' AFTER `keystroke` ,
				ADD `keystroke_ban_time` INT( 6 ) NOT NULL DEFAULT '20' AFTER `keystroke_max_cps`";
	$sqldescription[69] = "- Adding keystroke fields in settings table...";

	$sql[70] = "ALTER TABLE `".$db['prefix']."settings` ADD `captcha_max_length` TINYINT( 1 ) NOT NULL AFTER `captcha_length`";
	$sqldescription[70] = "- Adding field for captcha max length in settings table ...";

	$sql[71] = "ALTER TABLE `".$db['prefix']."settings` ADD `captcha_salt` VARCHAR( 255 ) NOT NULL DEFAULT '".mt_rand()."' AFTER `captcha_max_length`";
	$sqldescription[71] = "- Adding field for captcha salt in settings table ...";

	$sql[72] = "ALTER TABLE `".$db['prefix']."settings` ADD `captcha_hash_method` VARCHAR( 255 ) NOT NULL DEFAULT 'sha256' AFTER `captcha_salt`";
	$sqldescription[72] = "- Adding field for captcha hash method in settings table ...";

	$sql[73] = "ALTER TABLE `".$db['prefix']."settings` ADD `caching` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `admin_gbemail`";
	$sqldescription[73] = "- Adding field for caching in settings table ...";

	$sql[74] = "ALTER TABLE `".$db['prefix']."settings` ADD `dynamic_fieldnames` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `keystroke_ban_time`";
	$sqldescription[74] = "- Adding field for dynamic fieldnames in settings table ...";

	$sql[75] = "ALTER TABLE `".$db['prefix']."settings` ADD `dynamic_fieldnames_method` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `dynamic_fieldnames`";
	$sqldescription[75] = "- Adding field for dynamic fieldnames method in settings table ...";

	$sql[76] = "ALTER TABLE `".$db['prefix']."settings` ADD `dynamic_fieldnames_length` INT( 255 ) NOT NULL DEFAULT '16' AFTER `dynamic_fieldnames_method`";
	$sqldescription[76] = "- Adding field for dynamic fieldnames length in settings table ...";

	// 0.7 & 0.7.0.1 & 0.7.0.2
	
	$sql[77] = "ALTER TABLE `".$db['prefix']."spam_log` ADD
			`hp` VARCHAR( 255 ) NOT NULL AFTER `user_agent`";
	$sqldescription[77] = "- Adding 'hp' to spam_log table. If this fails with a <i>DUPLICATE COLUMN</i> Error, don't worry. Everything's fine.";

	$sql[78] = "ALTER TABLE `".$db['prefix']."entries` ADD
			`twitter` VARCHAR( 255 ) NOT NULL AFTER `fb`";
	$sqldescription[78] = "- Adding 'twitter' to entries table. If this fails with a <i>DUPLICATE COLUMN</i> Error, don't worry. Everything's fine.";

	$sql[79] = "ALTER TABLE `".$db['prefix']."user` ADD
			`r_settings_database` TINYINT( 1 ) NOT NULL AFTER `r_settings`";
	$sqldescription[79] = "- Adding 'r_settings_database' to user table...";

	$sql[80] = "ALTER TABLE `".$db['prefix']."user` ADD
			`r_banlists` TINYINT( 1 ) NOT NULL AFTER `r_edit_smilies`";
	$sqldescription[80] = "- Adding 'r_banlists' to user table...";

	$sql[81] = "ALTER TABLE `".$db['prefix']."spam` ADD
			`user_agent` VARCHAR( 255 ) NOT NULL AFTER `counter`";
	$sqldescription[81] = "- Adding 'user_agent' to spam table...";

	$sql[82] = "ALTER TABLE `".$db['prefix']."entries` ADD
			`user_agent` VARCHAR( 255 ) NOT NULL AFTER `ip`";
	$sqldescription[82] = "- Adding 'user_agent' to entries table...";

	$sql[83] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`direct_access` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `announcement_message`";
	$sqldescription[83] = "- Adding 'direct_access' to settings table...";

	$sql[84] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`direct_access_text` VARCHAR( 255 ) NOT NULL DEFAULT '".$_SERVER['SERVER_NAME']."' AFTER `direct_access`";
	$sqldescription[84] = "- Adding 'direct_access_text' to settings table...";

	$sql[85] = "ALTER TABLE `".$db['prefix']."spam_log` ADD
			`http_referer` VARCHAR( 255 ) NOT NULL AFTER `user_agent`";
	$sqldescription[85] = "- Adding 'http_referer' to spam_log table...";

	$sql[86] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`search_engines_excluded` INT( 1 ) NOT NULL DEFAULT '1' AFTER `direct_access_text`";
	$sqldescription[86] = "- Adding 'search_engines_excluded' to settings table...";

	$sql[87] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`search_engines` VARCHAR( 255 ) NOT NULL AFTER `search_engines_excluded`";
	$sqldescription[87] = "- Adding 'search_engines' to settings table...";

	$sql[88] = "INSERT INTO ".$db['prefix']."settings ( `search_engines` ) VALUES ( 'Googlebot/, Baiduspider, bingbot/, MJ12bot/, Exabot, ia_archiver, msnbot/, Yahoo! Slurp, SEO search Crawler/, crawleradmin.t-info@telekom.de' );";
	$sqldescription[88] = "- Inserting values for 'search engines' in settings table...";
	$sqlisinsert[88] = 1;

	$sql[89] = "ALTER TABLE `".$db['prefix']."spam_log` ADD
			`name` VARCHAR( 255 ) NOT NULL AFTER `ip`";
	$sqldescription[89] = "- Adding 'name' to spam_log table...";

	$sql[90] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`check_against_anti_spam_sites` INT( 1 ) NOT NULL DEFAULT '1' AFTER `search_engines`";
	$sqldescription[90] = "- Adding 'check_against_anti_spam_sites' to settings table...";

	$sql[91] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_username_frequency` INT( 5 ) NOT NULL DEFAULT '30' AFTER `check_against_anti_spam_sites`";
	$sqldescription[91] = "- Adding 'sfs_username_frequency' to settings table...";

	$sql[92] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_email_frequency` INT( 5 ) NOT NULL DEFAULT '1' AFTER `sfs_username_frequency`";
	$sqldescription[92] = "- Adding 'sfs_email_frequency' to settings table...";

	$sql[93] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_ip_frequency` INT( 5 ) NOT NULL DEFAULT '5' AFTER `sfs_email_frequency`";
	$sqldescription[93] = "- Adding 'sfs_ip_frequency' to settings table...";

	$sql[94] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_username_required` INT( 1 ) NOT NULL AFTER `sfs_ip_frequency`";
	$sqldescription[94] = "- Adding 'sfs_username_required' to settings table...";

	$sql[95] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_email_required` INT( 1 ) NOT NULL AFTER `sfs_username_required`";
	$sqldescription[95] = "- Adding 'sfs_email_required' to settings table...";

	$sql[96] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_ip_required` INT( 1 ) NOT NULL DEFAULT '1' AFTER `sfs_email_required`";
	$sqldescription[96] = "- Adding 'sfs_ip_required' to settings table...";

	$sql[97] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_mark_as_spam` INT( 1 ) NOT NULL DEFAULT '0' AFTER `sfs_ip_required`";
	$sqldescription[97] = "- Adding 'sfs_mark_as_spam' to settings table...";

	$sql[98] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`sfs_api_key` VARCHAR( 255 ) NOT NULL AFTER `sfs_mark_as_spam`";
	$sqldescription[98] = "- Adding 'sfs_api_key' to settings table...";

	$sql[99] = "ALTER TABLE `".$db['prefix']."spam` ADD
			`sneaked` INT( 1 ) NOT NULL DEFAULT '0' AFTER `user_agent`";
	$sqldescription[99] = "- Adding 'sneaked' to spam table...";

	$sql[100] = "ALTER TABLE `".$db['prefix']."spam_log` ADD
			`sneaked` INT( 1 ) NOT NULL DEFAULT '0' AFTER `site`";
	$sqldescription[100] = "- Adding 'sneaked' to spam_log table...";

	$sql[101] = "ALTER TABLE `".$db['prefix']."entries` CHANGE
			`ip` `ip` VARCHAR( 255 ) NOT NULL";
	$sqldescription[101] = "- Changing maximum length of 'ip' in entries table to 255...";

	$sql[102] = "ALTER TABLE `".$db['prefix']."spam` CHANGE
			`ip` `ip` VARCHAR( 255 ) NOT NULL";
	$sqldescription[102] = "- Changing maximum length of 'ip' in spam table to 255...";

	$sql[103] = "ALTER TABLE `".$db['prefix']."user` CHANGE
			`user_ip` `user_ip` VARCHAR( 255 ) NOT NULL";
	$sqldescription[103] = "- Changing maximum length of 'user_ip' in user table to 255...";

	$sql[104] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`ayah_pub_key` VARCHAR( 255 ) NOT NULL AFTER `sfs_api_key`";
	$sqldescription[104] = "- Adding 'ayah_pub_key' to settings table...";

	$sql[105] = "ALTER TABLE `".$db['prefix']."settings` ADD
			`ayah_score_key` VARCHAR( 255 ) NOT NULL AFTER `ayah_pub_key`";
	$sqldescription[105] = "- Adding 'ayah_score_key' to settings table...";
	
	$sql[106] = "ALTER TABLE `".$db['prefix']."settings` ADD
		`captcha_add_noise` TINYINT(1) NOT NULL DEFAULT '0' AFTER `captcha_angle_2`";
	$sqldescription[106] = "- Adding 'captcha_add_noise' to settings table...";

	$sql[107] = "ALTER TABLE `".$db['prefix']."settings` ADD
		`captcha_noise_color` VARCHAR( 6 ) NOT NULL DEFAULT '' AFTER `captcha_add_noise`";
	$sqldescription[107] = "- Adding 'captcha_noise_color' to settings table...";

	$sql[108] = "ALTER TABLE `".$db['prefix']."settings` ADD
		`captcha_noise_count` INT( 3 ) NOT NULL DEFAULT '5' AFTER `captcha_noise_color`";
	$sqldescription[108] = "- Adding 'captcha_noise_color' to settings table...";

	$sql[109] = "ALTER TABLE `".$db['prefix']."settings`
					ADD `show_field_city` TINYINT(1) NOT NULL DEFAULT '1' AFTER `ayah_score_key`,
					ADD `show_field_icq` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_field_city`,
					ADD `show_field_aim` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_field_icq`,
					ADD `show_field_fb` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_field_aim`,
					ADD `show_field_twitter` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_field_fb`,
					ADD `show_field_hp` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_field_twitter`";
	$sqldescription[109] = "- Adding options for deactivating some fields in newentry.php...";

	$sql[110] = "CREATE TABLE IF NOT EXISTS ".$db['prefix']."sys_log (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`type` int(4) NOT NULL,
		`name` varchar(255) NOT NULL,
		`email` varchar(255) NOT NULL,
		`text` varchar(5000) NOT NULL,
		`user` varchar(255) NOT NULL,
		`user_new` varchar(255) NOT NULL,
		`user_edit` varchar(255) NOT NULL,
		`ip` varchar(255) NOT NULL,
		`timestamp` int(255) NOT NULL,
		PRIMARY KEY (`ID`)
		) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
	$sqldescription[110] = "- Adding sys_log table...";

	$sql[111] = "ALTER TABLE `".$db['prefix']."settings` ADD `autoblock` TINYINT(1) NOT NULL DEFAULT '0' AFTER `banlist_log` ,
		ADD `autoblock_value` TINYINT(5) NOT NULL DEFAULT '5' AFTER `autoblock` ,
		ADD `autoblock_config` INT(7) NOT NULL DEFAULT '60' AFTER `autoblock_value`";
	$sqldescription[111] = "- Adding fields for automatic IP ban...";

	$sql[112] = "ALTER TABLE `".$db['prefix']."settings` ADD `banlist_cleanup` TINYINT(1) NOT NULL DEFAULT '1' AFTER `banlist_log`";
	$sqldescription[112] = "- Adding field for automatic banlist cleanup...";

	// update version number every time
	if(isset($_POST['update_version']) AND $_POST['update_version'] == 1) {
		$sql[113] = "UPDATE `".$db['prefix']."settings` SET `version` = '".MGB_VERSION."'";
		$sqldescription[113] = "- Updating version number...";

		if(isset($settings['language_path']) AND $settings['language_path'] == "lang_german_ansi") {
			$sql[114] = "UPDATE `".$db['prefix']."settings` SET `language_path` = 'lang_german_utf8'";
			$sqldescription[114] = "- Updating language path (delete \"language/lang_german_ansi/\" after this upgrade and VERY IMPORTANT: start <a href='convert_ansi.php'>convert_ansi.php</a>)!";
		}
	} else {
		if(isset($settings['language_path']) AND $settings['language_path'] == "lang_german_ansi") {
			$sql[114] = "UPDATE `".$db['prefix']."settings` SET `language_path` = 'lang_german_utf8'";
			$sqldescription[114] = "- Updating language path (delete \"language/lang_german_ansi/\" after this upgrade and VERY IMPORTANT: start <a href='convert_ansi.php'>convert_ansi.php</a>)!";
		}
	}
?>
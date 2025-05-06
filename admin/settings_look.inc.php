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

	=====================
	settings_look.inc.php
	=====================

	DATE OF CREATION: 24.02.2013; 14:14
	*/

	// make sure nobody has direct acces to this script
	if (!defined('ADMINISTRATION')) {
		include ("error.html");
		die();
	} else {
		require ("../includes/functions.inc.php");
		// load template
		$content_settings_look = mgb_load_template("admin", "default", "settings_look", $settings['debug_mode']);

		if(!isset($_GET['action'])) { $_GET['action'] = "settings_look"; }
		if(check_rights($_GET['action'], $_SESSION['user_ID'])) {
			if(isset($_POST['sent_settings']) AND $_POST['sent_settings'] == 1) {
				// load the default style and iconset for template if changed
				if($_POST['template_path'] != $settings['template_path']) {
					include ("../templates/".$_POST['template_path']."/settings.php");
					$_POST['template_style_path'] = $template['default_style'];
					$_POST['iconset_path'] = $template['default_iconset'];
				}
				// needed values in this script:
				// =============================
				// entries_per_page
				// dateform
				$empty_needed_value = 0;
				if(empty($_POST['entries_per_page'])) { $empty_needed_value = 7; } // no entries_per_page information
				if(empty($_POST['dateform'])) { $empty_needed_value = 8; } // no dateform information
				if($empty_needed_value == 0) { // no error, continue with saving settings
					// everything's okay now, let's save the data
					$sql = "UPDATE `".$db['prefix']."settings` SET
						`template_path` = '".cleanstr($_POST['template_path'])."',
						`template_style_path` = '".cleanstr($_POST['template_style_path'])."',
						`iconset_path` = '".cleanstr($_POST['iconset_path'])."',
						`language_path` = '".cleanstr($_POST['language_path'])."',
						`badwords` = '".cleanstr($_POST['badwords'])."',
						`user_notification` = '".cleanstr($_POST['user_notification'])."',
						`user_show_email` = '".cleanstr($_POST['user_show_email'])."',
						`entries_per_page` = '".cleanstr($_POST['entries_per_page'])."',
						`entries_order` = '".cleanstr($_POST['entries_order'])."',
						`entries_order_asc_desc` = '".cleanstr($_POST['entries_order_asc_desc'])."',
						`entries_numbering` = '".cleanstr($_POST['entries_numbering'])."',
						`wordwrap` = '".cleanstr($_POST['wordwrap'])."',
						`dateform` = '".cleanstr($_POST['dateform'])."'";

					if (mgb_sql_connect($sql, "Error while saving general settings.", 0)) {
						$saved_settings_successfull = 1;
						mgb_trigger_sys_log('1004', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
						mgb_erase_cache(MGB_ROOT."cache/");
					}

					require(MGB_ROOT."includes/load_settings.inc.php");
				}
			}

			// load active language
			include(MGB_ROOT."language/".$settings['language_path']."/settings.php");

			// load template
			$page_include = $content_settings_look;

			// start replacement for template

			// get names of languages
			$path = MGB_ROOT."language/";
			foreach (glob($path."*") as $filename) {
				if($filename != "." && $filename != "..") {
					if(is_dir($filename)) {
						if(!isset($edit_option_language_path)) { $edit_option_language_path = NULL; }
						include ($filename."/settings.php");
						if(isset($charset) AND $charset == "utf-8") { // only utf-8 languages are loaded into the guestbook
							$edit_option_language_path .= "<option ";
							$filename = str_replace(MGB_ROOT."language/", "", $filename);
							if($filename == $settings['language_path']) {
								$edit_option_language_path .= "selected ";
								$active_language_short = $language_short;
								$active_language_author = $language_author;
								$active_language_version = $language_version;
								$active_language_charset = $charset;
							}
							$edit_option_language_path .= "value=\"".$filename."\">".$language."</option>";
						}
					}
				}
			}

			// get names of templates
			$path = MGB_ROOT."templates/";
			foreach (glob($path."*") as $filename) {
				if($filename != "." && $filename != "..") {
					if(is_dir($filename)) {
						if(!isset($edit_option_template_path)) { $edit_option_template_path = NULL; }
						$edit_option_template_path .= "<option ";
						$filename = str_replace(MGB_ROOT."templates/", "", $filename);
						if($filename == $settings['template_path']) {
							$edit_option_template_path .= "selected ";
							$active_template = $filename;
						}
						include ($path.$filename."/settings.php");
						$filename = str_replace(MGB_ROOT."templates/", "", $filename);
						if(!isset($template['name'])) {
							$edit_option_template_path .= "value=\"".$filename."\">".$filename."</option>";
						} else {
							$edit_option_template_path .= "value=\"".$filename."\">".$template['name']."</option>";
						}
					}
				}
			}

			// get names of template styles
			$path = MGB_ROOT."templates/".$active_template."/css/";
			foreach (glob($path."*") as $filename) {
				if($filename != "." && $filename != "..") {
					if(is_dir($filename)) {
						if(!isset($edit_option_template_style_path)) { $edit_option_template_style_path = NULL; }
						$edit_option_template_style_path .= "<option ";
						$filename = str_replace(MGB_ROOT."templates/".$active_template."/css/", "", $filename);
						if($filename == $settings['template_style_path']) { $edit_option_template_style_path .= "selected "; }
						$edit_option_template_style_path .= "value=\"".$filename."\">".$filename."</option>";
					}
				}
			}

			// get names of iconsets
			$path = MGB_ROOT."images/iconsets/";
			foreach (glob($path."*") as $filename) {
				if($filename != "." && $filename != "..") {
					if(is_dir($filename)) {
						if(!isset($edit_option_iconset_path)) { $edit_option_iconset_path = NULL; }
						$edit_option_iconset_path .= "<option ";
						include ($filename."/settings.php");
						$filename = str_replace(MGB_ROOT."images/iconsets/", "", $filename);
						if($filename == $settings['iconset_path']) { $edit_option_iconset_path .= "selected "; }
						$edit_option_iconset_path .= "value=\"".$filename."\">".$name[$active_language_short]."</option>";
					}
				}
			}

			// replacement that has nothing to do with front end
			$page_include = template("URL_SETTINGS", "admin.php?action=settings_look".$sid, $page_include);

			// value replacement
			$page_include = template("LANG_EDIT_AUTHOR_NAME", $active_language_author, $page_include);
			$page_include = template("LANG_EDIT_VERSION", $active_language_version, $page_include);
			$page_include = template("LANG_EDIT_CHARSET", $active_language_charset, $page_include);
			// $page_include = template("LANG_EDIT_EXPL_LANGUAGE_AUTHOR", $lang['edit_author'], $page_include);
			$page_include = template("LANG_EDIT_EXPL_LANGUAGE_VERSION", $lang['version'].":", $page_include);
			// $page_include = template("LANG_EDIT_EXPL_LANGUAGE_CHARSET", $lang['edit_expl_language_charset'], $page_include);

			$page_include = template("LANG_ID", mgb_remove_evil_things($lang['id'], ":"), $page_include);
			$page_include = template("LANG_NAME", mgb_remove_evil_things($lang['name'], ":"), $page_include);
			$page_include = template("LANG_CITY", mgb_remove_evil_things($lang['city'], ":"), $page_include);
			$page_include = template("LANG_EMAIL", mgb_remove_evil_things($lang['email'], ":"), $page_include);
			$page_include = template("LANG_ICQ", mgb_remove_evil_things($lang['icq'], ":"), $page_include);
			$page_include = template("LANG_AIM", mgb_remove_evil_things($lang['aim'], ":"), $page_include);
			$page_include = template("LANG_MSN", mgb_remove_evil_things($lang['msn'], ":"), $page_include);
			$page_include = template("LANG_HP", mgb_remove_evil_things($lang['hp'], ":"), $page_include);
			$page_include = template("LANG_MESSAGE", mgb_remove_evil_things($lang['message'], ":"), $page_include);
			$page_include = template("LANG_COMMENT", mgb_remove_evil_things($lang['comment'], ":"), $page_include);
			$page_include = template("LANG_TIMESTAMP", mgb_remove_evil_things($lang['timestamp'], ":"), $page_include);
			
			if(empty($selected_entries_order_0)) { $selected_entries_order_0 = ""; }
			if(empty($selected_entries_order_1)) { $selected_entries_order_1 = ""; }
			if(empty($selected_entries_order_2)) { $selected_entries_order_2 = ""; }
			if(empty($selected_entries_order_3)) { $selected_entries_order_3 = ""; }
			if(empty($selected_entries_order_4)) { $selected_entries_order_4 = ""; }
			if(empty($selected_entries_order_5)) { $selected_entries_order_5 = ""; }
			if(empty($selected_entries_order_6)) { $selected_entries_order_6 = ""; }
			if(empty($selected_entries_order_7)) { $selected_entries_order_7 = ""; }
			if(empty($selected_entries_order_8)) { $selected_entries_order_8 = ""; }
			if(empty($selected_entries_order_9)) { $selected_entries_order_9 = ""; }

			if ($settings['entries_order'] == "ID") {
				$selected_entries_order_0 = " selected"; }
			elseif ($settings['entries_order'] == "name") {
				$selected_entries_order_1 = " selected"; }
			elseif ($settings['entries_order'] == "city") {
				$selected_entries_order_2 = " selected"; }
			elseif ($settings['entries_order'] == "email") {
				$selected_entries_order_3 = " selected"; }
			elseif ($settings['entries_order'] == "icq") {
				$selected_entries_order_4 = " selected"; }
			elseif ($settings['entries_order'] == "aim") {
				$selected_entries_order_5 = " selected"; }
			elseif ($settings['entries_order'] == "hp") {
				$selected_entries_order_6 = " selected"; }
			elseif ($settings['entries_order'] == "message") {
				$selected_entries_order_7 = " selected"; }
			elseif ($settings['entries_order'] == "comment") {
				$selected_entries_order_8 = " selected"; }
			elseif ($settings['entries_order'] == "timestamp") {
				$selected_entries_order_9 = " selected"; }
			
			if ($settings['entries_order_asc_desc'] == "ASC") {
				$selected_entries_order_asc_desc_0 = " selected";
				$selected_entries_order_asc_desc_1 = "";
			} else {
				$selected_entries_order_asc_desc_0 = "";
				$selected_entries_order_asc_desc_1 = " selected";
			}
			
			if ($settings['entries_numbering'] == 0) {
				$selected_entries_numbering_0 = " selected";
				$selected_entries_numbering_1 = "";
			} else {
				$selected_entries_numbering_0 = "";
				$selected_entries_numbering_1 = " selected";
			}
			
			if ($settings['user_notification'] == 0) {
				$selected_user_notification_0 = " selected";
				$selected_user_notification_1 = "";
			} else {
				$selected_user_notification_0 = "";
				$selected_user_notification_1 = " selected";
			}
			
			if ($settings['user_show_email'] == 0) {
				$selected_user_show_email_0 = " selected";
				$selected_user_show_email_1 = "";
			} else {
				$selected_user_show_email_0 = "";
				$selected_user_show_email_1	= " selected";
			}

			$page_include = template("EDIT_OPTION_TEMPLATE_PATH", $edit_option_template_path, $page_include);
			$page_include = template("EDIT_OPTION_TEMPLATE_STYLE_PATH", $edit_option_template_style_path, $page_include);
			$page_include = template("EDIT_OPTION_ICONSET_PATH", $edit_option_iconset_path, $page_include);
			$page_include = template("EDIT_OPTION_LANGUAGE_PATH", $edit_option_language_path, $page_include);
			$page_include = template("EDIT_BADWORDS", $settings['badwords'], $page_include);
			$page_include = template("SELECTED_USER_NOTIFICATION_0", $selected_user_notification_0, $page_include);
			$page_include = template("SELECTED_USER_NOTIFICATION_1", $selected_user_notification_1, $page_include);
			$page_include = template("SELECTED_USER_SHOW_EMAIL_0", $selected_user_show_email_0, $page_include);
			$page_include = template("SELECTED_USER_SHOW_EMAIL_1", $selected_user_show_email_1, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_0", $selected_entries_order_0, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_1", $selected_entries_order_1, $page_include);
			$page_include = template("EDIT_ENTRIES_PER_PAGE", $settings['entries_per_page'], $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_0", $selected_entries_order_0, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_1", $selected_entries_order_1, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_2", $selected_entries_order_2, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_3", $selected_entries_order_3, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_4", $selected_entries_order_4, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_5", $selected_entries_order_5, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_6", $selected_entries_order_6, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_7", $selected_entries_order_7, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_8", $selected_entries_order_8, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_9", $selected_entries_order_9, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_ASC_DESC_0", $selected_entries_order_asc_desc_0, $page_include);
			$page_include = template("SELECTED_ENTRIES_ORDER_ASC_DESC_1", $selected_entries_order_asc_desc_1, $page_include);
			$page_include = template("SELECTED_ENTRIES_NUMBERING_0", $selected_entries_numbering_0, $page_include);
			$page_include = template("SELECTED_ENTRIES_NUMBERING_1", $selected_entries_numbering_1, $page_include);
			$page_include = template("EDIT_WORDWRAP", $settings['wordwrap'], $page_include);
			$page_include = template("EDIT_DATEFORM", $settings['dateform'], $page_include);

			// front end / language replacement
			// $page_include = mgb_template_language($page_include, MGB_ROOT."language/".$settings['language_path']."/lang_admin.php", $settings['debug_mode']); // last number defines debug mode

			// is scrolling function needed?
			$content_scrolling_function = "";
		} else {
			$page_include = "<span class=\"admin\">".$lang['errormessage'][4]."</span>"; // user has no access to this page, user level too low
			$content_scrolling_function = "<br>";
		}
	}
?>

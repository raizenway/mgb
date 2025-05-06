<?php
	/*
	MGB 0.7.x - OpenSource PHP and MySQL Guestbook
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

	=========
	index.php
	=========
	*/

	// show all errors
	error_reporting(E_ALL & ~E_NOTICE);

	// define site name
	$site_name = "index.php";

	// set root path
	define('MGB_ROOT', dirname(__FILE__)."/");

	// include functions
	require_once(MGB_ROOT.'includes/functions.inc.php');

	// check if mgb has been already installed
	mgb_iou_check(MGB_ROOT);

	// load config, templates, settings and language files
	require (MGB_ROOT.'includes/config.inc.php');
	require (MGB_ROOT.'includes/load_settings.inc.php');

	// check if user's ip is on banlist
	if($settings['banlist_ips'] == 1) {
		$result = mgb_sql_connect("SELECT banned_ip, timestamp FROM ".$db['prefix']."banlist_ips WHERE banned_ip = '".$_SERVER['REMOTE_ADDR']."'", "Error while checking remote IP.", 1);
		$ip_check = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($ip_check['banned_ip'] == $_SERVER['REMOTE_ADDR']) {
			if($settings['blocktime'] != 99999999 AND $settings['banlist_cleanup'] == 1) {
				if(time() - $ip_check['timestamp'] >= $settings['blocktime']) {
					mgb_sql_connect("DELETE FROM `".$db['prefix']."banlist_ips` WHERE banned_ip='".$_SERVER['REMOTE_ADDR']."' LIMIT 1", "Error while deleting a single ip entry.", 0);
				} else {
					die();
				}
			} else {
				die();
			}
		}
	}

	// override language path if "lang" parameter is set
	if(!empty($_GET['lang'])) {
		MGB_ROOT."language/".$settings['language_path'] = mgb_get_language_path($_GET['lang']);
	}

	require (MGB_ROOT."language/".$settings['language_path'].'/lang_main.php');
	require (MGB_ROOT."language/".$settings['language_path'].'/settings.php');

	// if caching is activated, include page from cache directory instead of loading it out of the database
	if($settings['caching'] == 1 AND !file_exists(MGB_ROOT.'install')) {
		if(!empty($_GET['p'])) {
			$filename = "cache/index_".$language_short."_".$_GET['p'].".html";
		} else {
			$filename = "cache/index_".$language_short."_1.html";
		}

		if(file_exists($filename) AND (time() - filemtime($filename)) < 259200) { // 3 days, value in seconds
	    	if(!empty($settings['debug_mode'])) {
	    		echo "<pre>\n";
	    		echo "<span>Debug: Page loaded out of the cache as \"".$filename."\"</span>\n";
	    		echo "</pre>\n";
	    	}
	    	include($filename);
	    	exit();
		}
	}

	// set timezone
	if(function_exists("date_default_timezone_set")) {
		date_default_timezone_set($settings['timezone']);
	}

	// load general templates
	$content_header = mgb_load_template("user", $settings['template_path'], "general/header", $settings['debug_mode']);
	$content_footer = mgb_load_template("user", $settings['template_path'], "general/footer", $settings['debug_mode']);
	$content_copyright = mgb_load_template("user", $settings['template_path'], "general/copyright", $settings['debug_mode']);
	$content_scrolling_function = mgb_load_template("user", $settings['template_path'], "general/scrolling_function", $settings['debug_mode']);
	$content_errormessage = mgb_load_template("user", $settings['template_path'], "general/errormessage", $settings['debug_mode']);

	// load index templates
	$content_index_announcement_message = mgb_load_template("user", $settings['template_path'], "main/index_announcement_message", $settings['debug_mode']);
	$content_index_body = mgb_load_template("user", $settings['template_path'], "main/index_body", $settings['debug_mode']);
	$content_index_entry = mgb_load_template("user", $settings['template_path'], "main/index_entry", $settings['debug_mode']);
	$content_index_entry_aim = mgb_load_template("user", $settings['template_path'], "main/index_entry_aim", $settings['debug_mode']);
	$content_index_entry_city = mgb_load_template("user", $settings['template_path'], "main/index_entry_city", $settings['debug_mode']);
	$content_index_entry_comment = mgb_load_template("user", $settings['template_path'], "main/index_entry_comment", $settings['debug_mode']);
	$content_index_entry_email = mgb_load_template("user", $settings['template_path'], "main/index_entry_email", $settings['debug_mode']);
	$content_index_entry_gravatar = mgb_load_template("user", $settings['template_path'], "main/index_entry_gravatar", $settings['debug_mode']);
	$content_index_entry_hp = mgb_load_template("user", $settings['template_path'], "main/index_entry_hp", $settings['debug_mode']);
	$content_index_entry_icq = mgb_load_template("user", $settings['template_path'], "main/index_entry_icq", $settings['debug_mode']);
	$content_index_entry_info = mgb_load_template("user", $settings['template_path'], "main/index_entry_info", $settings['debug_mode']);
	$content_index_entry_message = mgb_load_template("user", $settings['template_path'], "main/index_entry_message", $settings['debug_mode']);
	$content_index_entry_msn = mgb_load_template("user", $settings['template_path'], "main/index_entry_msn", $settings['debug_mode']);
	$content_index_entry_fb = mgb_load_template("user", $settings['template_path'], "main/index_entry_fb", $settings['debug_mode']);
	$content_index_entry_twitter = mgb_load_template("user", $settings['template_path'], "main/index_entry_twitter", $settings['debug_mode']);

	// set number of site to "1" if it is "0"
	if(empty($_GET['p'])) { $_GET['p'] = 1; }

	// get total number of entries
	$results = mysqli_fetch_assoc(mgb_sql_connect("SELECT COUNT(ID) FROM ".$db['prefix']."entries WHERE checked = 1", "Error while counting guestbook entries.", 1));
	$total = $results['COUNT(ID)'];

	// compute how many pages there are
	$p = ($total / $settings['entries_per_page']);

	if($p <= 1) {
		$p = 0;
		if($total > 1) {
			$how_many_entries = $total."&nbsp;".$lang['entries'];
			}
		elseif($total == 0) {
			$how_many_entries = $lang['no_entries'];
			}
		else {
			$how_many_entries = $total."&nbsp;".$lang['entry'];
		}
	}
	else {
		$p = ceil($p);
		$how_many_entries = $total."&nbsp;".$lang['entries_on_pages'];
	}

	$pagenr = $_GET['p'];
	// $pagenr = secure_value($_GET['p']);

	$load_start = ($pagenr * $settings['entries_per_page']) - $settings['entries_per_page'];
	$load_end = $settings['entries_per_page'];

	$pages_total = ceil($p);

	if($pagenr == 1) {
		$sf_forwards = "<a href=\"index.php?p=".($pagenr + 1)."{PARAMLANG_B}\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
		$sf_pagenumber = $pagenr;
		if($pages_total >= 3 ) {
			$sf_last = "<a href=\"index.php?p=".$pages_total."{PARAMLANG_B}\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
		}
	}

	if($pagenr > 1) {
		if(($pages_total >= 3) AND ($pagenr > 2)) {
			$sf_first = "<a href=\"index.php?p=1{PARAMLANG_B}\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
		}
		$sf_backwards = "<a href=\"index.php?p=".($pagenr - 1)."{PARAMLANG_B}\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
		$sf_pagenumber = $pagenr;
		$sf_forwards = "<a href=\"index.php?p=".($pagenr + 1)."{PARAMLANG_B}\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
		if(($pages_total >= 3) AND ($pagenr < ($pages_total - 1))) {
			 $sf_last = "&nbsp;<a href=\"index.php?p=".$pages_total."{PARAMLANG_B}\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
		}
	}

	if($pagenr == $pages_total) {
		if($pages_total >= 3) {
			$sf_first = "<a href=\"index.php?p=1"."{PARAMLANG_B}\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
		}
		$sf_backwards = "<a href=\"index.php?p=".($pagenr - 1)."{PARAMLANG_B}\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
		$sf_pagenumber = $pagenr;
		$sf_forwards = "";
	}

	if($pages_total <= 0) {
		$content_scrolling_function = "<br>";
	}

	// load guestbook entries
	$result = mgb_sql_connect("SELECT ID, name, city, email, icq, aim, msn, hp, fb, twitter, message, comment, timestamp, user_show_email FROM ".$db['prefix']."entries WHERE checked = 1 ORDER BY ".$settings['entries_order']." ".$settings['entries_order_asc_desc']." LIMIT $load_start, $load_end", "Error while loading guestbook entries.", 1);

	// put them in an array
	for($i = 0; $i < mysqli_num_rows($result); $i++) {
		$entry[$i] = mysqli_fetch_array($result, MYSQLI_ASSOC);
	}
	
	// fill header template with content
	$refresh = "";
	$page_header = $content_header;

	// check if "install" directory already exists
	if(file_exists(MGB_ROOT.'install') AND is_dir(MGB_ROOT.'install')) {
		$install_directory_exists = $content_errormessage;
		$install_directory_exists = template("ERRORMESSAGE", $lang['install_directory_exists'], $install_directory_exists);
		$page_header = template("INSTALL_DIRECTORY_EXISTS", $install_directory_exists, $page_header);
	}
	else {
		$page_header = template("INSTALL_DIRECTORY_EXISTS", "", $page_header);
	}

	$page_header = template("H_LANGUAGE_SHORT", $language_short, $page_header);
	$page_header = template("H_DOMAIN", $settings['h_domain'], $page_header);
	$page_header = template("H_AUTHOR", $settings['h_author'], $page_header);
	$page_header = template("H_KEYWORDS", $settings['h_keywords'], $page_header);
	$page_header = template("H_DESCRIPTION", $settings['h_description'], $page_header);
	$page_header = template("H_CHARSET", $charset, $page_header);
	$page_header = template("REFRESH", $refresh, $page_header);

	// fill entry template with content
	if($settings['entries_numbering'] == 0) {
		$entry_counter = ($settings['entries_per_page'] * $pagenr) - $settings['entries_per_page'];
	}
	else {
		$entry_counter = ($total - ($settings['entries_per_page'] * $pagenr) + ($settings['entries_per_page'] + 1));
	}

	if(empty($page_entry_echo)) { $page_entry_echo = ""; }
	
	if($total > 0) {
		for($i = 0; $i < count($entry); $i++) {
			$page_entry[$i] = $content_index_entry;
			if($settings['entries_numbering'] == 0) {
				$entry_counter++;
			}
			else {
				$entry_counter--;
			}

			// wordwrap: if message contains words longer than $settings['wordwrap'] they will
			// be broken into two or more strings. If $settings['wordwrap'] == 0, function is off
			// this method taken from http://de.php.net/manual/en/function.wordwrap.php#64517
			// by ab_at_notenet(dot)dk (thanks for that!!) will luckily not break html tags

			if(!$settings['wordwrap'] == 0) {
				$entry[$i]['message'] = textWrap($entry[$i]['message'], $settings['wordwrap']);
			}

			// set smilies
			if($settings['smileys'] == 1) {
				$entry[$i]['message'] = set_smilies($entry[$i]['message']);
				$entry[$i]['comment'] = set_smilies($entry[$i]['comment']);
			}
			else {
				$entry[$i]['message'] = delete_smilies($entry[$i]['message']);
				$entry[$i]['comment'] = delete_smilies($entry[$i]['comment']);
			}

			// set bbcode
			if($settings['bbcode'] == 1) {
				$entry[$i]['message'] = bbcode_format($entry[$i]['message'], "");
				$entry[$i]['comment'] = bbcode_format($entry[$i]['comment'], "");
			}
			else {
				$entry[$i]['message'] = bbcode_delete($entry[$i]['message']);
				$entry[$i]['comment'] = bbcode_delete($entry[$i]['comment']);
			}

			// find out which optional data has been set by the user
			$info = $content_index_entry_info;
			$email = $content_index_entry_email;
			$message = $content_index_entry_message;
			$city = $content_index_entry_city;
			$hp = $content_index_entry_hp;
			$gravatar = $content_index_entry_gravatar;
			$icq = $content_index_entry_icq;
			$aim = $content_index_entry_aim;
			$msn = $content_index_entry_msn;
			$fb = $content_index_entry_fb;
			$twitter = $content_index_entry_twitter;
			$comment = $content_index_entry_comment;

			$info_icons = 7;

			if(empty($entry[$i]['city'])) {
				$city = "";
			}
			if(empty($entry[$i]['hp'])) {
				$hp = "";
				$info_icons--;
			} else {
				$entry_hp_text = $lang['hp_of'];
			}
			if(empty($entry[$i]['icq'])) {
				$icq = "";
				$info_icons--;
			}
			if(empty($entry[$i]['aim'])) {
				$aim = "";
				$info_icons--;
			}
			if(empty($entry[$i]['msn'])) {
				$msn = "";
				$info_icons--;
			}
			if(empty($entry[$i]['fb'])) {
				$fb = "";
				$info_icons--;
			}
			if(empty($entry[$i]['twitter'])) {
				$twitter = "";
				$info_icons--;
			}
			if(empty($entry[$i]['comment'])) {
				$comment = "";
			}

			// check if email is set
			if(!empty($entry[$i]['email'])) {
				// find out if the user wants his email to be shown
				if($entry[$i]['user_show_email'] != 0) {
					$entry_email_path = "email.php?id=".$entry[$i]['ID']."{PARAMLANG_B}";
					$entry_email_pic = "images/iconsets/".$settings['iconset_path']."/email.png";
					$entry_email_text = $lang['email_yes'];
				}
				else {
					$info_icons--;
					$email = "";
				}
			}
			else {
				$info_icons--;
				$email = "";
			}

			if(!$settings['badwords'] == NULL) {
				// replace badwords
				$badwords = explode(',', $settings['badwords']);
				foreach($badwords as $key => $val)
				$badwords[$key] = trim($val);

				$entry[$i]['name'] = badwords($entry[$i]['name']);
				$entry[$i]['city'] = badwords($entry[$i]['city']);
				$entry[$i]['message'] = badwords($entry[$i]['message']);
			}

			$timestamp = date($settings['dateform'], $entry[$i]['timestamp']);

			if(!empty($settings['gravatar_show']) AND ($settings['gravatar_show'] == 1) AND (!empty($entry[$i]['email']))) {
				// load gravatar
				if($settings['gravatar_rating'] == 0) { $gravatar_rating = "G"; }
				if($settings['gravatar_rating'] == 1) { $gravatar_rating = "PG"; }
				if($settings['gravatar_rating'] == 2) { $gravatar_rating = "R"; }
				if($settings['gravatar_rating'] == 3) { $gravatar_rating = "X"; }
				if($settings['gravatar_type'] == 0) { $gravatar_type = "&amp;f=y"; }
				if($settings['gravatar_type'] == 1) { $gravatar_type = "&amp;d=mm"; }
				if($settings['gravatar_type'] == 2) { $gravatar_type = "&amp;d=identicon"; }
				if($settings['gravatar_type'] == 3) { $gravatar_type = "&amp;d=monsterid"; }
				if($settings['gravatar_type'] == 4) { $gravatar_type = "&amp;d=wavatar"; }
				if($settings['gravatar_type'] == 5) { $gravatar_type = "&amp;d=retro"; }
				if($settings['gravatar_type'] == 6) { $gravatar_type = "&amp;d=blank"; }

				if($_SERVER['HTTPS'] == "on") {
					$gravatar_url = "https";
				} else {
					$gravatar_url = "http";
				}
				
				$gravatar_url.= "://www.gravatar.com/avatar/".md5(strtolower(trim($entry[$i]['email'])))."?s=".$settings['gravatar_size']."&amp;r=".$gravatar_rating.$gravatar_type;
				$img_gravatar = "<img src=\"".$gravatar_url."\" class=\"gravatar\" style=\"width: ".$settings['gravatar_size']."px; height: ".$settings['gravatar_size']."px;\" alt=\"".$lang['gravatar']."\" title=\"".$lang['gravatar']."\">";
			}
			else {
				$gravatar_size = 0;
				$img_gravatar = "";
			}

			// fill template with other templates if set
			if($info_icons > 0) {
				$page_entry[$i] = template("TEMPLATE_ENTRY_INFO", $info, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_EMAIL", $email, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_HP", $hp, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_ICQ", $icq, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_AIM", $aim, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_MSN", $msn, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_FB", $fb, $page_entry[$i]);
				$page_entry[$i] = template("TEMPLATE_ENTRY_TWITTER", $twitter, $page_entry[$i]);
			}
			elseif($info_icons == 0) {
				$page_entry[$i] = template("TEMPLATE_ENTRY_INFO", "", $page_entry[$i]);
			}

			$page_entry[$i] = template("TEMPLATE_ENTRY_CITY", $city, $page_entry[$i]);
			$page_entry[$i] = template("TEMPLATE_ENTRY_MESSAGE", $message, $page_entry[$i]);

			if($settings['gravatar_position'] == 0) {
				$page_entry[$i] = template("ENTRY_GRAVATAR_LEFT", $gravatar, $page_entry[$i]);
				$page_entry[$i] = template("ENTRY_GRAVATAR_RIGHT", "", $page_entry[$i]);
				$page_entry[$i] = template("GRAVATAR_CSS", "entry_message_gravatar_left", $page_entry[$i]);
			}
			else {
				$page_entry[$i] = template("ENTRY_GRAVATAR_LEFT", "", $page_entry[$i]);
				$page_entry[$i] = template("ENTRY_GRAVATAR_RIGHT", $gravatar, $page_entry[$i]);
				$page_entry[$i] = template("GRAVATAR_CSS", "entry_message_gravatar_right", $page_entry[$i]);
			}

			$page_entry[$i] = template("TEMPLATE_ENTRY_COMMENT", $comment, $page_entry[$i]);

			// fill template with entry (language)
			if(empty($entry_email_text)) { $entry_email_text = ""; }
			if(empty($entry_hp_text)) { $entry_hp_text = ""; }
			$page_entry[$i] = template("LANG_EMAIL_OF", $entry_email_text, $page_entry[$i]);
			$page_entry[$i] = template("LANG_HP_OF", $entry_hp_text, $page_entry[$i]);

			// fill template with entry (strings)
			$page_entry[$i] = template("ENTRY_ID", $entry_counter, $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_ANCHOR", "<a href=\"index.php{PARAMLANG_A}&amp;p=".$pagenr."#e".$entry_counter."\" title=\"".$lang['anchor']."\">&raquo;</a>", $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_CITY", $entry[$i]['city'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_EMAIL_PIC", $entry_email_pic, $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_EMAIL_PATH", $entry_email_path, $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_TIMESTAMP", $timestamp, $page_entry[$i]);
			$page_entry[$i] = template("GRAVATAR_SIZE", $settings['gravatar_size'], $page_entry[$i]);
			$page_entry[$i] = template("IMG_GRAVATAR", $img_gravatar, $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_MESSAGE", $entry[$i]['message'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_HP", $entry[$i]['hp'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_ICQ_NUMBER", $entry[$i]['icq'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_AIM_NAME", $entry[$i]['aim'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_MSN", $entry[$i]['msn'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_FB", $entry[$i]['fb'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_TWITTER", $entry[$i]['twitter'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_COMMENT", $entry[$i]['comment'], $page_entry[$i]);
			$page_entry[$i] = template("ENTRY_NAME", $entry[$i]['name'], $page_entry[$i]);
			$page_entry[$i] = template("TEMPLATE_PATH", "templates/".$settings['template_path'], $page_entry[$i]);

			$page_entry_echo .= $page_entry[$i];
		}
	}

	if(empty($page_entry_echo)) { $page_entry_echo = ""; }

	// check if an announcement message is set
	if(empty($settings['announcement_message'])) {
		$content_index_announcement_message = "";
	}
	else {
		$settings['announcement_message'] = nl2br($settings['announcement_message']);
		$t1 = chr(10);
		$t2 = chr(13);
		$settings['announcement_message'] = str_ireplace($t1, '', $settings['announcement_message']);
		$settings['announcement_message'] = str_ireplace($t2, '', $settings['announcement_message']);
		$settings['announcement_message'] = set_smilies($settings['announcement_message']);
		$settings['announcement_message'] = bbcode_format($settings['announcement_message'], "");
	}

	// fill index_body.tpl and load templates first
	$page_body_index = $content_index_body;
	$page_body_index = template("HEADER", $page_header, $page_body_index);
	$page_body_index = template("SCRIPT_RECAPTCHAV2", "", $page_body_index);
	$page_body_index = template("TEMPLATE_ANNOUNCEMENT_MESSAGE", $content_index_announcement_message, $page_body_index);
	$page_body_index = template("TEMPLATE_SCROLLING_FUNCTION", $content_scrolling_function, $page_body_index);
	$page_body_index = template("TEMPLATE_ENTRIES", $page_entry_echo, $page_body_index);
	$page_body_index = template("TEMPLATE_PATH", "templates/".$settings['template_path'], $page_body_index);
	$page_body_index = template("TEMPLATE_STYLE_PATH", $settings['template_style_path'], $page_body_index);
	$page_body_index = template("TEMPLATE_COPYRIGHT", $content_copyright, $page_body_index);
	$page_body_index = template("TEMPLATE_FOOTER", $content_footer, $page_body_index);

	// then strings. starting by initialising some of them
	if(!isset($sf_first)) { $sf_first = ''; }
	if(!isset($sf_backwards)) { $sf_backwards = ''; }
	if(!isset($sf_last)) { $sf_last = ''; }
	if(!isset($_GET['lang'])) { $_GET['lang'] = 'de'; }
	$page_body_index = template("TITLE", $settings['title'], $page_body_index);
	$page_body_index = template("ICONSET_PATH", $settings['iconset_path'], $page_body_index);
	$page_body_index = template("LANG_HOW_MANY_ENTRIES", $how_many_entries, $page_body_index);
	$page_body_index = template("ANNOUNCEMENT_MESSAGE", $settings['announcement_message'], $page_body_index);
	$page_body_index = template("PAGES", $p, $page_body_index);
	$page_body_index = template("SF_FIRST", $sf_first, $page_body_index);
	$page_body_index = template("SF_BACKWARDS", $sf_backwards, $page_body_index);
	$page_body_index = template("SF_PAGENUMBER", $sf_pagenumber, $page_body_index);
	$page_body_index = template("SF_FORWARDS", $sf_forwards, $page_body_index);
	$page_body_index = template("SF_LAST", $sf_last, $page_body_index);
	$page_body_index = template("MGB_VERSION", $settings['version'], $page_body_index);
	$page_body_index = template("COPYRIGHT_DATE", date("Y"), $page_body_index);
	$page_body_index = template("PARAMLANG_A", "?lang=".cleanstr($_GET['lang']), $page_body_index);
	$page_body_index = template("PARAMLANG_B", "&amp;lang=".cleanstr($_GET['lang']), $page_body_index);

	// fill in the rest of the language strings
	$page_body_index = mgb_template_language($page_body_index, MGB_ROOT."language/".$settings['language_path']."/lang_main.php", $settings['debug_mode']); // last number defines debug mode

	// start caching if activated
	if($settings['caching'] == 1 AND !file_exists(MGB_ROOT.'install')) {
		ob_start();
	}

	// display the page
	echo $page_body_index;

	if($settings['caching'] == 1 AND !file_exists(MGB_ROOT.'install')) {
		if(!empty($_GET['p'])) {
			$filename = MGB_ROOT."cache/index_".$language_short."_".$_GET['p'].".html";
		} else {
			$filename = MGB_ROOT."cache/index_".$language_short."_1.html";
		}

		// write the cache file
		if(is_writable(MGB_ROOT.'cache')) {
			file_put_contents($filename, ob_get_flush());
		}
	}
?>

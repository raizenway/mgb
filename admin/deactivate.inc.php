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

	==================
	deactivate.inc.php
	==================
	*/

	// make sure nobody has direct acces to this script
	if (!defined('ADMINISTRATION')) {
		include ("error.html");
		die();
	} else {
		if(check_rights($_GET['action'], $_SESSION['user_ID'])) {
			// load config, settings and language files
			require (MGB_ROOT."includes/config.inc.php");
			require (MGB_ROOT."includes/functions.inc.php");
			require (MGB_ROOT."includes/load_settings.inc.php");
			require (MGB_ROOT."language/".$settings['language_path']."/lang_admin.php");

			// load templates
			$content_deactivate = mgb_load_template("admin", "default", "deactivate", $settings['debug_mode']);

			// set number of site to "1" if it is "0"
			if(!isset($_GET['p'])) { $_GET['p'] = 1; }

			if(empty($_POST['dropbox'])) { $_POST['dropbox'] = ""; }
			$_POST['dropbox'] = cleanstr($_POST['dropbox']);

			if(isset($_POST['dropbox']) AND $_POST['dropbox'] == 1) {
				mgb_sql_connect("UPDATE `".$db['prefix']."entries` SET `checked` = '0' WHERE checked = 1", "Error while deactivating all entries at once.", 0);
				mgb_trigger_sys_log('1014', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
				mgb_erase_cache("../cache/");
			}

			if(isset($_GET['id'])) {
				mgb_sql_connect("UPDATE `".$db['prefix']."entries` SET `checked` = '0' WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deactivating entry.", 0);
				mgb_trigger_sys_log('1014', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
				mgb_erase_cache("../cache/");
			}

			// get total number of entries
			$results = mysqli_fetch_assoc(mgb_sql_connect("SELECT COUNT(ID) FROM ".$db['prefix']."entries WHERE checked=1", "Error while counting deactivated entries.", 1));
			$total = $results['COUNT(ID)'];

			// compute how many pages there are
			$p = ($total / 20);

			if ($p <= 1) {
				$p = 0;
				if ($total > 1) {
					$how_many_entries = "<span class=\"admin\">".$total."&nbsp;".$lang['entries']."</span>";
				} elseif ($total == 0) {
					$how_many_entries = "<span class=\"admin\">".$lang['no_activated_entries']."</span>";
				} else {
					$how_many_entries = "<span class=\"admin\">".$total."&nbsp;".$lang['entry']."</span>";
				}
			} else {
				$p = ceil($p);
				$how_many_entries = "<span class=\"admin\">".$total."&nbsp;".$lang['entries_on_pages']."</span>";
			}

			$load_start = ($_GET['p'] * 20) - 20;
			$load_end = 20;

			$pages_total = ceil($p);

			if ($_GET['p'] == 1) {
				$sf_forwards = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=".($_GET['p'] + 1).$sid."\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				if ($pages_total >= 3 ) {
					$sf_last = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=".$pages_total."\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
				}
			}

			if ($_GET['p'] > 1) {
				if (($pages_total >= 3) AND ($_GET['p'] > 2)) {
					$sf_first = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=1".$sid."\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
				}
				$sf_backwards = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=".($_GET['p'] - 1).$sid."\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				$sf_forwards = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=".($_GET['p'] + 1).$sid."\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
				if (($pages_total >= 3) AND ($_GET['p'] < ($pages_total - 1))) {
					$sf_last = "&nbsp;<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=".$pages_total.$sid."\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
				}
			}

			if ($_GET['p'] == $pages_total) {
				if ($pages_total >= 3) {
					$sf_first = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=1".$sid."\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
				}
				$sf_backwards = "<a class=\"admin\" href=\"admin.php?action=deactivate&amp;p=".($_GET['p'] - 1).$sid."\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				$sf_forwards = "";
			}

			if ($pages_total <= 0) {
				$content_scrolling_function = "<br><br>";
			}

			// load guestbook entries
			$result = mgb_sql_connect("SELECT * FROM ".$db['prefix']."entries WHERE checked = 1 ORDER BY ID DESC LIMIT ".$load_start.",".$load_end, "Error while loading entries.", 1);

			$counter = 0;
			
			if(!isset($entry)) {$entry = array(); }

			for($i = 0; $i < mysqli_num_rows($result); $i++) {
				$entry[$i] = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$counter++;
			}

			if ($counter <= 1) {
				if ($_GET['p'] == 1) {
					$add_page_nr = NULL;
				} else {
					$add_page_nr = "&amp;p=".($_GET['p'] - 1);
				}
			} else {
				$add_page_nr = "&amp;p=".$_GET['p'];
			}

			// fill entry template with content
			require ("../includes/functions.inc.php");

			if(!empty($entry)) {

				for($i = 0; $i < count($entry); $i++) {
					$page_entry[$i] = $content_deactivate;

					if(empty($entry[$i]['ip'])) { $entry[$i]['ip'] = "-"; }
					if(empty($entry[$i]['comment'])) { $entry[$i]['comment'] = "-"; }

					// wordwrap: if message contains words longer than $settings['wordwrap'] they will
					// be broken into two or more strings. If $settings['wordwrap'] == 0, function is off
					// this method taken from http://de.php.net/manual/en/function.wordwrap.php#64517
					// will luckily not affect html tags

					$entry[$i]['message'] = textWrap($entry[$i]['message'], 45);
					$entry[$i]['comment'] = textWrap($entry[$i]['comment'], 45);

					// convert bbcodes
					$entry[$i]['message'] = bbcode_format($entry[$i]['message'], "adminpanel");
					$entry[$i]['comment'] = bbcode_format($entry[$i]['comment'], "adminpanel");

					// fill template with entry (strings)
					$page_entry[$i] = template("ENTRY_ID", $entry[$i]['ID'], $page_entry[$i]);
					$page_entry[$i] = template("ENTRY_NAME", substr($entry[$i]['name'], 0, 20), $page_entry[$i]);
					$page_entry[$i] = template("ENTRY_MESSAGE", $entry[$i]['message'], $page_entry[$i]);
					$page_entry[$i] = template("ENTRY_IP", $entry[$i]['ip'], $page_entry[$i]);
					$page_entry[$i] = template("ENTRY_EMAIL", $entry[$i]['email'], $page_entry[$i]);
					$page_entry[$i] = template("ENTRY_HP", $entry[$i]['hp'], $page_entry[$i]);
					$page_entry[$i] = template("ENTRY_COMMENT", $entry[$i]['comment'], $page_entry[$i]);
					$page_entry[$i] = template("LANG_QUOTE", $lang['quote'], $page_entry[$i]);
					$page_entry[$i] = template("DEACTIVATE", "<a href=\"admin.php?action=deactivate&amp;id=".$entry[$i]['ID'].$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/deactivate.png\" title=\"".$lang['deactivate_entry']."\" alt=\"".$lang['deactivate_entry']."\"></a>", $page_entry[$i]);
					$page_entry[$i] = template("TEMPLATE_PATH", "templates/".$settings['template_path'], $page_entry[$i]);

					if(!isset($page_include)) { $page_include = NULL; }
					$page_include .= $page_entry[$i];
				}
			}
		} else {
			$page_include = "<span class=\"admin\">".$lang['errormessage'][4]."</span>";
			$content_scrolling_function = "<br>";
		}
	}
?>

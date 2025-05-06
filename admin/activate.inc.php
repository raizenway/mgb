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

	================
	activate.inc.php
	================
	*/

	// make sure nobody has direct access to this script
	if(!defined('ADMINISTRATION')) {
		include ("error.html");
		die();
	} else {
		if(check_rights($_GET['action'], $_SESSION['user_ID'])) {
			// load config, settings and language files
			require (MGB_ROOT."includes/config.inc.php");
			require (MGB_ROOT."includes/load_settings.inc.php");
			require (MGB_ROOT."language/".$settings['language_path']."/lang_admin.php");

			// load template
			$content_activate = mgb_load_template("admin", "default", "activate", $settings['debug_mode']);

			// set number of site to "1" if it is "0"
			if(!isset($_GET['p'])) { $_GET['p'] = 1; }

			if(empty($_POST['dropbox'])) { $_POST['dropbox'] = ""; }
			$_POST['dropbox'] = cleanstr($_POST['dropbox']);

			if(isset($_POST['dropbox']) AND $_POST['dropbox'] == 1) { // Activate all entries at once
				mgb_sql_connect("UPDATE `".$db['prefix']."entries` SET `checked` = '1' WHERE checked=0", "Error while activating all entries at once and updating sql table.", 0);
				mgb_erase_cache("../cache/");
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 2) { // Put all entries on spam table
				// check if user has too many counts in trying to make a guestbook entry
				$spam_list_result = mysqli_fetch_assoc(mgb_sql_connect("SELECT COUNT(ID) FROM ".$db['prefix']."spam", "Error while counting entries in spam table.", 1));
				$spam_list_total = $spam_list_result['COUNT(ID)'];
				$spam_list_result = mgb_sql_connect("SELECT id, ip, email, counter FROM ".$db['prefix']."spam", "Error while loading entries from spam table.", 1);

				for($i = 0; $i < mysqli_num_rows($spam_list_result); $i++) {
					$spam_list[$i] = mysqli_fetch_array($spam_list_result, MYSQLI_ASSOC); // put all entries from spam table into an array named $spam_list
				}

				$unchecked_entries_result = mgb_sql_connect("SELECT id, ip, email FROM ".$db['prefix']."entries WHERE checked = 0 AND isspam = 0", "Error while loading entry from ".$db['prefix']."entries.", 1);

				for($i = 0; $i < mysqli_num_rows($unchecked_entries_result); $i++) {
					$store_spam = 1;
					$new_spam[$i] = mysqli_fetch_array($unchecked_entries_result, MYSQLI_ASSOC);
					for($j = 0; $j < $spam_list_total; $j++) {
						if($spam_list[$j]['ip'] == $new_spam[$i]['ip'] OR $spam_list[$j]['email'] == $new_spam[$i]['email']) {
							$store_spam = 0;
							if ($spam_list[$j]['counter'] < 5) {
								$spam_list[$j]['counter']++;
								mgb_sql_connect("UPDATE `".$db['prefix']."spam` SET `counter` = '".$spam_list[$j]['counter']."', `timestamp` = '".time()."' WHERE ID='".$spam_list[$j]['id']."' LIMIT 1", "Error while saving data into ".$db['prefix']."spam", 0);
								// refresh spam list
								$spam_list_result = mgb_sql_connect("SELECT id, ip, email, counter FROM ".$db['prefix']."spam", "Error while loading entries from spam table.", 1);
								for($k = 0; $k < mysqli_num_rows($spam_list_result); $k++) {
									$spam_list[$k] = mysqli_fetch_array($spam_list_result, MYSQLI_ASSOC); // put all entries from spam table into an array named $spam_list
								}
							}
						}
					}

					if($store_spam == 1) {
						// load whole entry
						$whole_spam_entry = mgb_sql_connect("SELECT * FROM ".$db['prefix']."entries WHERE id = ".$new_spam[$i]['id']." AND checked = 0 AND isspam = 0", "Error while loading entry from ".$db['prefix']."entries.", 1);
						for($l = 0; $l < mysqli_num_rows($whole_spam_entry); $l++) {
							$whole_entry[$l] = mysqli_fetch_array($whole_spam_entry, MYSQLI_ASSOC);
							// store entry in spam table
							mgb_sql_connect("INSERT INTO ".$db['prefix']."spam (
								name,
								ip,
								email,
								city,
								icq,
								aim,
								msn,
								fb,
								twitter,
								hp,
								message,
								user_notification,
								user_show_email,
								captcha,
								sent_captcha,
								counter,
								user_agent,
								sneaked,
								timestamp
								) values (
								'".$whole_entry[$l]['name']."',
								'".$whole_entry[$l]['ip']."',
								'".$whole_entry[$l]['email']."',
								'".$whole_entry[$l]['city']."',
								'".$whole_entry[$l]['icq']."',
								'".$whole_entry[$l]['aim']."',
								'".$whole_entry[$l]['msn']."',
								'".$whole_entry[$l]['fb']."',
								'".$whole_entry[$l]['twitter']."',
								'".$whole_entry[$l]['hp']."',
								'".$whole_entry[$l]['message']."',
								'".$whole_entry[$l]['user_notification']."',
								'".$whole_entry[$l]['user_show_email']."',
								'0',
								'0',
								'1',
								'".$whole_entry[$l]['user_agent']."',
								'0',
								'".$whole_entry[$l]['timestamp']."'
								)", "Error while saving data into ".$db['prefix']."spam", 0);
						}
					}
				}
				// delete entries from entries table
				mgb_sql_connect("DELETE FROM `".$db['prefix']."entries` WHERE checked = 0 AND isspam = 0", "Error while deleting entry in ".$db['prefix']."entries", 0);
			}

			if(isset($_GET['id'])) {
				if(isset($_GET['isspam']) AND secure_value($_GET['isspam'] == 1)) {
					// get data of the entry from database
					$id = secure_value($_GET['id']);
					$result = mgb_sql_connect("SELECT * FROM ".$db['prefix']."entries WHERE ID=".$id, "Error while loading entry from ".$db['prefix']."entries.", 1);

					for($i = 0; $i < mysqli_num_rows($result); $i++) {
						$spam[$id] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					}

					// store entry in spam table
					mgb_sql_connect("INSERT INTO ".$db['prefix']."spam (
						name,
						ip,
						email,
						city,
						icq,
						aim,
						msn,
						fb,
						twitter,
						hp,
						message,
						user_notification,
						user_show_email,
						captcha,
						sent_captcha,
						counter,
						user_agent,
						sneaked,
						timestamp
						) values (
						'".$spam[$id]['name']."',
						'".$spam[$id]['ip']."',
						'".$spam[$id]['email']."',
						'".$spam[$id]['city']."',
						'".$spam[$id]['icq']."',
						'".$spam[$id]['aim']."',
						'".$spam[$id]['msn']."',
						'".$spam[$id]['fb']."',
						'".$spam[$id]['twitter']."',
						'".$spam[$id]['hp']."',
						'".$spam[$id]['message']."',
						'".$spam[$id]['user_notification']."',
						'".$spam[$id]['user_show_email']."',
						'0',
						'0',
						'1',
						'".$spam[$id]['user_agent']."',
						'0',
						'".$spam[$id]['timestamp']."'
						)", "Error while saving data into ".$db['prefix']."spam", 0);
					// delete entry from entries table
					mgb_sql_connect("DELETE FROM `".$db['prefix']."entries` WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deleting entry in ".$db['prefix']."entries", 0);
					mgb_erase_cache("../cache/");
				} else {
					mgb_sql_connect("UPDATE `".$db['prefix']."entries` SET `checked` = '1' WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while activating entry and updating sql table.", 0);
					mgb_trigger_sys_log('1013', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
					mgb_erase_cache("../cache/");
				}

				// send an email to user
				if(isset($_GET['notify']) AND $_GET['notify'] == 1 AND !isset($_GET['isspam'])) {
					$result = mgb_sql_connect("SELECT name, email, message FROM ".$db['prefix']."entries WHERE id=".secure_value($_GET['id'])." LIMIT 1", "Error while loading information for sending an email to user.", 1);
					$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$name = $data['name'];
					$email = $data['email'];
					$message = $data['message'];

					$date = date("d"."/"."m"."/"."Y");
					$time = date("H".":"."i");

					$url_to_gb = "http://".$settings['h_domain'].$settings['gb_path']."index.php";

					$lang['sendmail_user_notification_title'] = format_mail(repl_uml($lang['sendmail_user_notification_title'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", "");
					$settings['sendmail_user_notification_text'] = format_mail(repl_uml($settings['sendmail_user_notification_text'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", "");

					$mail_header = "content-type: text/plain; charset=".$charset."\r\n";
					$mail_header .= "from: ".$settings['admin_gbemail']."\r\n";
					$mail_header .= "Reply-To: ".$settings['admin_gbemail']."\r\n";
					$mail_header .= "X-Mailer: PHP/".phpversion();

					if(!empty($email)) {
						if($settings['mailer_method'] == 0) {
							$mail_send = @mail($email, $lang['sendmail_user_notification_title'], $settings['sendmail_user_notification_text'], $mail_header);
							if($mail_send) {
								$sendemail_successfull = 1;
								mgb_trigger_sys_log('1026', $data['name'], '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							} else {
								$sendemail_successfull = 0;
							}
						} elseif($settings['mailer_method'] == 1 AND file_exists(MGB_ROOT."plugins/phpmailer/class.phpmailer.php")) {
							$mail_send = mgb_phpmailer($email, $settings['admin_email'], $name, $settings['h_domain'], $lang['sendmail_user_notification_title'], $settings['sendmail_user_notification_text'], $settings['debug_mode'], "adminpanel", $language_short, $charset);
							if($mail_send[0] == 0) {
								$sendemail_successfull = 0;
								$template_message = "<br><br>phpmailer: ".$mail_send[1];
							} else {
								$sendemail_successfull = 1;
								mgb_trigger_sys_log('1026', $data['name'], '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							}
						}
					}
				}
			}

			// get total number of entries
			$results = mysqli_fetch_assoc(mgb_sql_connect("SELECT COUNT(ID) FROM ".$db['prefix']."entries WHERE checked=0 AND isspam=0", "Error while counting entries.", 1));
			$total = $results['COUNT(ID)'];

			// compute how many pages there are
			$p = ($total / 20);

			if ($p <= 1) {
				$p = 0;
				if ($total > 1) {
					$how_many_entries = "<span class=\"admin\">".$total."&nbsp;".$lang['entries']."</span>";
				} elseif ($total == 0) {
					$how_many_entries = "<span class=\"admin\">".$lang['no_deactivated_entries']."</span>";
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
				$sf_forwards = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=".($_GET['p'] + 1).$sid."\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				if ($pages_total >= 3 ) {
					$sf_last = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=".$pages_total."\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
				}
			}

			if ($_GET['p'] > 1) {
				if (($pages_total >= 3) AND ($_GET['p'] > 2)) {
					$sf_first = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=1".$sid."\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
				}
				$sf_backwards = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=".($_GET['p'] - 1).$sid."\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				$sf_forwards = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=".($_GET['p'] + 1).$sid."\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
				if (($pages_total >= 3) AND ($_GET['p'] < ($pages_total - 1))) {
					 $sf_last = "&nbsp;<a class=\"admin\" href=\"admin.php?action=activate&amp;p=".$pages_total.$sid."\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
				}
			}

			if ($_GET['p'] == $pages_total) {
				if ($pages_total >= 3) {
					$sf_first = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=1".$sid."\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
				}
				$sf_backwards = "<a class=\"admin\" href=\"admin.php?action=activate&amp;p=".($_GET['p'] - 1).$sid."\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				$sf_forwards = "";
			}

			if ($pages_total <= 0) {
				$content_scrolling_function = "<br><br>";
			}

			// load guestbook entries
			$result = mgb_sql_connect("SELECT * FROM ".$db['prefix']."entries WHERE checked=0 AND isspam=0 ORDER BY ID DESC LIMIT $load_start, $load_end", "Error while loading inactive guestbook entries.", 1);

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
			require (MGB_ROOT."includes/functions.inc.php");

			if(!empty($entry)) {

				for($i = 0; $i < count($entry); $i++) {
					$page_entry[$i] = $content_activate;

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
					$page_entry[$i] = template("ACTIVATE", "<a href=\"admin.php?action=activate&amp;id=".$entry[$i]['ID']."&amp;notify=".$entry[$i]['user_notification'].$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/activate.png\" title=\"".$lang['activate_entry']."\" alt=\"".$lang['activate_entry']."\"></a>", $page_entry[$i]);
					$page_entry[$i] = template("MARK_AS_SPAM", "<a href=\"admin.php?action=activate&amp;id=".$entry[$i]['ID']."&amp;isspam=1".$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/spam.png\" title=\"".$lang['mark_as_spam']."\" alt=\"".$lang['mark_as_spam']."\"></a>", $page_entry[$i]);
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

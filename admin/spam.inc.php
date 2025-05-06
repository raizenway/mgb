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

	============
	spam.inc.php
	============
	*/

	// make sure nobody has direct access to this script
	if (!defined('ADMINISTRATION')) {
		include ("error.html");
		die();
	} else {
		if(check_rights($_GET['action'], $_SESSION['user_ID'])) {
			// load config, settings and language files
			require (MGB_ROOT."includes/config.inc.php");
			require (MGB_ROOT."includes/load_settings.inc.php");
			require (MGB_ROOT."language/".$settings['language_path']."/lang_admin.php");
			// load templates
			$content_spam = mgb_load_template("admin", "default", "spam", $settings['debug_mode']);

			// set number of site to "1" if it is "0"
			if(!isset($_GET['p'])) { $_GET['p'] = 1; }

			$_POST['dropbox'] = cleanstr($_POST['dropbox']);

			if(isset($_POST['dropbox']) AND $_POST['dropbox'] == 1) { // Delete all spam entries
				mgb_sql_connect("TRUNCATE ".$db['prefix']."spam", "Error while deleting all spam entries.", 0);
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 2) { // No spam but let them deactivated
				$result = mgb_sql_connect("SELECT * FROM ".$db['prefix']."spam", "Error while loading entries from ".$db['prefix']."spam.", 1);
				for($i = 0; $i < mysqli_num_rows($result); $i++) {
					$spam_entry[$i] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					// store entries in entries table
					mgb_sql_connect("INSERT INTO ".$db['prefix']."entries (
						name,
						city,
						email,
						icq,
						aim,
						msn,
						fb,
						twitter,
						hp,
						message,
						comment,
						ip,
						user_agent,
						timestamp,
						user_notification,
						user_show_email,
						checked,
						isspam
						) values (
						'".$spam_entry[$i]['name']."',
						'".$spam_entry[$i]['city']."',
						'".$spam_entry[$i]['email']."',
						'".$spam_entry[$i]['icq']."',
						'".$spam_entry[$i]['aim']."',
						'".$spam_entry[$i]['msn']."',
						'".$spam_entry[$i]['fb']."',
						'".$spam_entry[$i]['twitter']."',
						'".$spam_entry[$i]['hp']."',
						'".$spam_entry[$i]['message']."',
						'',
						'".$spam_entry[$i]['ip']."',
						'".$spam_entry[$i]['user_agent']."',
						'".$spam_entry[$i]['timestamp']."',
						'".$spam_entry[$i]['user_notification']."',
						'".$spam_entry[$i]['user_show_email']."',
						'0',
						'0'
						)", "Error while saving data into ".$db['prefix']."entries", 0);
				}
				// delete all entries from spam table
				mgb_sql_connect("TRUNCATE ".$db['prefix']."spam", "Error while deleting all spam entries.", 0);
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 3) { // No spam and activate them
				$result = mgb_sql_connect("SELECT * FROM ".$db['prefix']."spam", "Error while loading entries from ".$db['prefix']."spam.", 1);
				for($i = 0; $i < mysqli_num_rows($result); $i++) {
					$spam_entry[$i] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					// store entries in entries table
					mgb_sql_connect("INSERT INTO ".$db['prefix']."entries (
						name,
						city,
						email,
						icq,
						aim,
						msn,
						fb,
						twitter,
						hp,
						message,
						comment,
						ip,
						user_agent,
						timestamp,
						user_notification,
						user_show_email,
						checked,
						isspam
						) values (
						'".$spam_entry[$i]['name']."',
						'".$spam_entry[$i]['city']."',
						'".$spam_entry[$i]['email']."',
						'".$spam_entry[$i]['icq']."',
						'".$spam_entry[$i]['aim']."',
						'".$spam_entry[$i]['msn']."',
						'".$spam_entry[$i]['fb']."',
						'".$spam_entry[$i]['twitter']."',
						'".$spam_entry[$i]['hp']."',
						'".$spam_entry[$i]['message']."',
						'',
						'".$spam_entry[$i]['ip']."',
						'".$spam_entry[$i]['user_agent']."',
						'".$spam_entry[$i]['timestamp']."',
						'".$spam_entry[$i]['user_notification']."',
						'".$spam_entry[$i]['user_show_email']."',
						'1',
						'0'
						)", "Error while saving data into ".$db['prefix']."entries", 0);
					mgb_erase_cache(MGB_ROOT."cache/");
				}
				// delete all entries from spam table
				mgb_sql_connect("TRUNCATE ".$db['prefix']."spam", "Error while deleting all spam entries.", 0);
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 4) { // Put all IPs on banlist
				$script_time_start = microtime(true);
				$entry_counter = 0;
				$result = mgb_sql_connect("SELECT ip FROM ".$db['prefix']."spam", "Error while loading ips from ".$db['prefix']."spam.", 1);
				for($j = 0; $j < mysqli_num_rows($result); $j++) {
					$spam_entry[$j] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$counter = 0;
					$result_parts = explode(".", $spam_entry[$j]['ip']);
					$banned_ips = mgb_sql_connect("SELECT banned_ip FROM ".$db['prefix']."banlist_ips WHERE banned_ip_first = '".$result_parts[0]."'", "Error while loading banned ips from ".$db['prefix']."banlist_ips.", 1);
					for($i = 0; $i < mysqli_num_rows($banned_ips); $i++) {
						$ips[$i] = mysqli_fetch_array($banned_ips, MYSQLI_ASSOC);
						// put ip on ip banlist if it is not already in there
						if($spam_entry[$j]['ip'] == $ips[$i]['banned_ip']) {
							$counter++;
						}
					}
					if($counter == 0) {
						mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_ips (
							ID,
							banned_ip,
							matches,
							timestamp )
						values (
							NULL,
							'".$spam_entry[$j]['ip']."',
							'0',
							'".time()."' )", "Error while saving data into ".$db['prefix']."banlist_ips", 0);
						$entry_counter++;
					}
					// delete all entries from spam table
					// mgb_sql_connect("TRUNCATE ".$db['prefix']."spam", "Error while deleting all spam entries.", 0);
				}
				if($entry_counter > 0) {
					$script_time_end = microtime(true);
					$script_time = $script_time_end - $script_time_start;
					$lang['updated_ips'] = str_replace('{COUNTER}', $entry_counter, $lang['updated_ips']);
					$lang['compared_ips'] = str_replace('{COUNTER}', mysqli_num_rows($banned_ips), $lang['compared_ips']);
					$template_message = $lang['updated_ips'].'<br>'.$lang['compared_ips'];
				}
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 5) { // Put all emails on banlist
				$script_time_start = microtime(true);
				$entry_counter = 0;
				$result = mgb_sql_connect("SELECT email FROM ".$db['prefix']."spam", "Error while loading emails from ".$db['prefix']."spam.", 1);
				for($j = 0; $j < mysqli_num_rows($result); $j++) {
					$spam_entry[$j] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$counter = 0;
					$banned_emails = mgb_sql_connect("SELECT banned_email FROM ".$db['prefix']."banlist_emails", "Error while loading banned emails from ".$db['prefix']."banlist_emails.", 1);
					for($i = 0; $i < mysqli_num_rows($banned_emails); $i++) {
						$emails[$i] = mysqli_fetch_array($banned_emails, MYSQLI_ASSOC);
						// put email on email banlist if it is not already in there
						if($spam_entry[$j]['email'] == $emails[$i]['banned_email']) {
							$counter++;
						}
					} if($counter == 0) {
						mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_emails (
							ID,
							banned_email,
							banned_email_first,
							banned_email_second,
							matches,
							timestamp )
						values (
							NULL,
							'".$spam_entry[$j]['email']."',
							'".$result_parts[0]."',
							'".$result_parts[1]."',
							'0',
							'".time()."' )", "Error while saving data into ".$db['prefix']."banlist_emails", 0);
						$entry_counter++;
					}

					// delete all entries from spam table
					// mgb_sql_connect("TRUNCATE ".$db['prefix']."spam", "Error while deleting all spam entries.", 0);
				}
				if($entry_counter > 0) {
					$script_time_end = microtime(true);
					$script_time = $script_time_end - $script_time_start;
					$lang['updated_emails'] = str_replace('{COUNTER}', $entry_counter, $lang['updated_emails']);
					$lang['compared_emails'] = str_replace('{COUNTER}', mysqli_num_rows($banned_ips), $lang['compared_emails']);;
					$template_message = $lang['updated_emails'].'<br>'.$lang['compared_emails'];
				}
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 6) { // Put all domains on banlist
				$script_time_start = microtime(true);
				$entry_counter = 0;
				$result = mgb_sql_connect("SELECT email FROM ".$db['prefix']."spam", "Error while loading emails from ".$db['prefix']."spam.", 1);
				for($j = 0; $j < mysqli_num_rows($result); $j++) {
					$spam_entry[$j] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$counter = 0;
					$user_domain = $spam_entry[$j]['email'];
					$user_domain = explode("@", $user_domain);
					$banned_domains = mgb_sql_connect("SELECT banned_domain FROM ".$db['prefix']."banlist_domains", "Error while loading banned domains from ".$db['prefix']."banlist_domains.", 1);
					for($i = 0; $i < mysqli_num_rows($banned_domains); $i++) {
						$domains[$i] = mysqli_fetch_array($banned_domains, MYSQLI_ASSOC);
						// put domain on domain banlist if it is not already in there
						if($user_domain[1] == $domains[$i]['banned_domain']) {
							$counter++;
						}
					} if($counter == 0) {
						mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_domains (
							ID ,
							banned_domain ,
							matches,
							timestamp
						) VALUES (
							NULL ,
							'".$user_domain[1]."' ,
							'0',
							'".time()."'
						);", "Error while saving data into ".$db['prefix']."banlist_emails", 0);
						$entry_counter++;
					}
					// delete all entries from spam table
					// mgb_sql_connect("TRUNCATE ".$db['prefix']."spam", "Error while deleting all spam entries.", 0);
				}
				if($entry_counter > 0) {
					$script_time_end = microtime(true);
					$script_time = $script_time_end - $script_time_start;
					$lang['updated_domains'] = str_replace('{COUNTER}', $entry_counter, $lang['updated_domains']);
					$lang['compared_domains'] = str_replace('{COUNTER}', mysqli_num_rows($banned_ips), $lang['compared_domains']);
					$template_message = $lang['updated_domains'].'<br>'.$lang['compared_domains'];
				}
			} elseif(isset($_POST['dropbox']) AND $_POST['dropbox'] == 7) { // Sneak everything
				if(!empty($settings['sfs_api_key'])) {
					$script_time_start = microtime(true);
					$entry_counter = 0;
					$result = mgb_sql_connect("SELECT ID, name, ip, email, hp, message, sneaked FROM ".$db['prefix']."spam", "Error while loading entries from ".$db['prefix']."spam.", 1);
					for($i = 0; $i < mysqli_num_rows($result); $i++) {
						$spam_entry[$i] = mysqli_fetch_array($result, MYSQLI_ASSOC);
						if($spam_entry[$i]['sneaked'] != 1) {
							$data = "username=".urlencode(iconv('GBK', 'UTF-8', $spam_entry[$i]['name']));
							$data.= "&ip_addr=".$spam_entry[$i]['ip'];
							$data.= "&email=".urlencode(iconv('GBK', 'UTF-8', $spam_entry[$i]['email']));
							$data.= "&api_key=".$settings['sfs_api_key'];
							$data.= "&evidence=".urlencode(iconv('GBK', 'UTF-8', $spam_entry[$i]['message']."<br><br>".$spam_entry[$i]['hp']));
							if(!empty($settings['debug_mode'])) {
								mgb_echo($data);
							}

							$response = mgb_report_spam($data);
							if($response == 200) {
								mgb_sql_connect("UPDATE `".$db['prefix']."spam` SET `sneaked` = '1' WHERE ID=".$spam_entry[$i]['ID']." LIMIT 1", "Error while sneaking spam entry and updating sql table.", 0);
								$entry_counter++;
							} elseif($response == 403) {
								$template_message = $lang['report_failed'];
							} elseif($response == "") {
								mgb_sql_connect("UPDATE `".$db['prefix']."spam` SET `sneaked` = '1' WHERE ID=".$spam_entry[$i]['ID']." LIMIT 1", "Error while sneaking spam entry and updating sql table.", 0);
								$entry_counter++;
							} else {
								$template_message = $response;
							}
						}
					}
					if($entry_counter > 0) {
						$script_time_end = microtime(true);
						$script_time = $script_time_end - $script_time_start;
						$lang['entries_sneaked'] = str_replace('{COUNTER}', $entry_counter, $lang['entries_sneaked']);
						$lang['entries_sneaked'] = str_replace('{TIME}', round($script_time, 3), $lang['entries_sneaked']);
						$template_message = $lang['entries_sneaked'];
					}
				} else {
					$template_message = $lang['empty_needed_value'][43]; // missing api key
				}
			}

			if(isset($_GET['id'])) {
				if(isset($_GET['spam_action'])) {
					if($_GET['spam_action'] == 'delete') {
						mgb_sql_connect("DELETE FROM `".$db['prefix']."spam` WHERE ID=".$_GET['id']." LIMIT 1", "Error while deleting a single spam entry.", 0);
					} elseif($_GET['spam_action'] == 'nospam_deactivate') {
						$result = mgb_sql_connect("SELECT name, city, email, icq, aim, msn, fb, twitter, hp, message, ip, user_agent, timestamp, user_notification, user_show_email FROM ".$db['prefix']."spam WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while loading an entry from spam table", 1);
						while ($spam_entry = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							mgb_sql_connect("INSERT INTO ".$db['prefix']."entries (
								name,
								city,
								email,
								icq,
								aim,
								msn,
								fb,
								twitter,
								hp,
								message,
								comment,
								ip,
								user_agent,
								timestamp,
								user_notification,
								user_show_email,
								checked,
								isspam
								) values (
								'".$spam_entry['name']."',
								'".$spam_entry['city']."',
								'".$spam_entry['email']."',
								'".$spam_entry['icq']."',
								'".$spam_entry['aim']."',
								'".$spam_entry['msn']."',
								'".$spam_entry['fb']."',
								'".$spam_entry['twitter']."',
								'".$spam_entry['hp']."',
								'".$spam_entry['message']."',
								'',
								'".$spam_entry['ip']."',
								'".$spam_entry['user_agent']."',
								'".$spam_entry['timestamp']."',
								'".$spam_entry['user_notification']."',
								'".$spam_entry['user_show_email']."',
								'0',
								'0'
								)", "Error while saving data into ".$db['prefix']."entries", 0);
						}
						// delete the entry from spam table
						mgb_sql_connect("DELETE FROM `".$db['prefix']."spam` WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deleting an entry from spam table.", 0);
					} elseif($_GET['spam_action'] == 'nospam') {
						$result = mgb_sql_connect("SELECT name, city, email, icq, aim, msn, fb, twitter, hp, message, ip, user_agent, timestamp, user_notification, user_show_email FROM ".$db['prefix']."spam WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while loading an entry from spam table", 1);
						while ($spam_entry = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							mgb_sql_connect("INSERT INTO ".$db['prefix']."entries (
								name,
								city,
								email,
								icq,
								aim,
								msn,
								fb,
								twitter,
								hp,
								message,
								comment,
								ip,
								user_agent,
								timestamp,
								user_notification,
								user_show_email,
								checked,
								isspam
								) values (
								'".$spam_entry['name']."',
								'".$spam_entry['city']."',
								'".$spam_entry['email']."',
								'".$spam_entry['icq']."',
								'".$spam_entry['aim']."',
								'".$spam_entry['msn']."',
								'".$spam_entry['fb']."',
								'".$spam_entry['twitter']."',
								'".$spam_entry['hp']."',
								'".$spam_entry['message']."',
								'',
								'".$spam_entry['ip']."',
								'".$spam_entry['user_agent']."',
								'".$spam_entry['timestamp']."',
								'".$spam_entry['user_notification']."',
								'".$spam_entry['user_show_email']."',
								'1',
								'0'
								)", "Error while saving data into ".$db['prefix']."entries", 0);
							mgb_erase_cache(MGB_ROOT."cache/");
						}
						// delete the entry from spam table
						mgb_sql_connect("DELETE FROM `".$db['prefix']."spam` WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deleting an entry from spam table.", 0);
					} elseif($_GET['spam_action'] == 'add_to_permanent_ip_banlist') {
						$script_time_start = microtime(true);
						$result = mgb_sql_connect("SELECT ip FROM ".$db['prefix']."spam WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while loading IP from spam table", 1);
						while($spam_entry = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							$result_parts = explode(".", $spam_entry['ip']);
							$banned_ips = mgb_sql_connect("SELECT banned_ip FROM ".$db['prefix']."banlist_ips WHERE banned_ip = '".$spam_entry['ip']."'", "Error while loading banned ips from ".$db['prefix']."banlist_ips.", 1);
							$ip = mysqli_fetch_array($banned_ips, MYSQLI_ASSOC);
							// put ip on ip banlist if it is not already in there
							if($spam_entry['ip'] == $ip['banned_ip']) {
								$counter++;
							}

							if($counter == 0) {
								mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_ips (
									ID,
									banned_ip,
									matches,
									timestamp )
								values (
									NULL,
									'".$spam_entry['ip']."',
									'0',
									'".time()."' )", "Error while saving data into ".$db['prefix']."banlist_ips", 0);
								$template_message = str_replace('{IP}', $spam_entry['ip'], $lang['spam_added_to_ip_list']);
							} else {
								$template_message = str_replace('{IP}', $spam_entry['ip'], $lang['spam_is_already_on_email_list']);
							}
						}
						$script_time_end = microtime(true);
						$script_time = $script_time_end - $script_time_start;
						// delete the entry from spam table
						// mgb_sql_connect("DELETE FROM `".$db['prefix']."spam` WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deleting an entry from spam table.", 0);
					} elseif($_GET['spam_action'] == 'add_to_permanent_email_banlist') {
						$script_time_start = microtime(true);
						$result = mgb_sql_connect("SELECT email FROM ".$db['prefix']."spam WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while loading email from spam table", 1);
						while($spam_entry = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							$result_parts = explode("@", $spam_entry['email']);
							$banned_emails = mgb_sql_connect("SELECT banned_email FROM ".$db['prefix']."banlist_emails WHERE banned_email = '".$spam_entry['email']."'", "Error while loading banned emails from ".$db['prefix']."banlist_emails.", 1);
							$email = mysqli_fetch_array($banned_emails, MYSQLI_ASSOC);
							// put email on email banlist if it is not already in there
							if($spam_entry['email'] == $email['banned_email']) {
								$counter++;
							}

							if($counter == 0) {
								mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_emails (
									ID,
									banned_email,
									banned_email_first,
									banned_email_second,
									matches,
									timestamp )
								values (
									NULL,
									'".$spam_entry['email']."',
									'".$result_parts[0]."',
									'".$result_parts[1]."',
									'0',
									'".time()."' )", "Error while saving data into ".$db['prefix']."banlist_emails", 0);
								$template_message = str_replace('{EMAIL}', $spam_entry['email'], $lang['spam_added_to_email_list']);
							} else {
								$template_message = str_replace('{EMAIL}', $spam_entry['email'], $lang['spam_is_already_on_email_list']);
							}
						}
						$script_time_end = microtime(true);
						$script_time = $script_time_end - $script_time_start;
						// delete the entry from spam table
						// mgb_sql_connect("DELETE FROM `".$db['prefix']."spam` WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deleting an entry from spam table.", 0);
					} elseif($_GET['spam_action'] == 'add_to_permanent_domain_banlist') {
						$script_time_start = microtime(true);
						$result = mgb_sql_connect("SELECT email FROM ".$db['prefix']."spam WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while loading email from spam table", 1);
						while ($spam_entry = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							$user_domain = explode("@", $spam_entry['email']);
							$banned_domain = mgb_sql_connect("SELECT banned_domain FROM ".$db['prefix']."banlist_domains WHERE banned_domain = '".$user_domain[1]."'", "Error while loading banned domains from ".$db['prefix']."banlist_domains.", 1);
							$domain = mysqli_fetch_array($banned_domain, MYSQLI_ASSOC);
							// put domain on domain banlist if it is not already in there
							if($user_domain[1] != $domain['banned_domain']) {
								mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_domains (
									ID,
									banned_domain,
									matches,
									timestamp
								) values (
									NULL,
									'".$user_domain[1]."',
									'0',
									'".time()."'
								)", "Error while saving data into ".$db['prefix']."banlist_domains", 0);
								$template_message = str_replace('{DOMAIN}', $user_domain[1], $lang['spam_added_to_domain_list']);
							} else {
								$template_message = str_replace('{DOMAIN}', $user_domain[1], $lang['spam_is_already_on_domain_list']);
							}
						}
						$script_time_end = microtime(true);
						$script_time = $script_time_end - $script_time_start;
						// delete the entry from spam table
						// mgb_sql_connect("DELETE FROM `".$db['prefix']."spam` WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while deleting an entry from spam table.", 0);
					} elseif($_GET['spam_action'] == 'report_to_stopforumspam') {
						if(!empty($settings['sfs_api_key'])) {
							$result = mgb_sql_connect("SELECT name, ip, email, hp, message, sneaked FROM ".$db['prefix']."spam WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while loading email from spam table", 1);
							$spam_entry = mysqli_fetch_array($result, MYSQLI_ASSOC);
							if($spam_entry['sneaked'] != 1) {
								$data = "username=".urlencode(iconv('GBK', 'UTF-8', $spam_entry['name']));
								$data.= "&ip_addr=".$spam_entry['ip'];
								$data.= "&email=".urlencode(iconv('GBK', 'UTF-8', $spam_entry['email']));
								$data.= "&api_key=".$settings['sfs_api_key'];
								$data.= "&evidence=".urlencode(iconv('GBK', 'UTF-8', $spam_entry['message']."<br><br>".$spam_entry['hp']));

								if(!empty($settings['debug_mode'])) {
									mgb_echo($data);
								}

								$response = mgb_report_spam($data);
								if($response == 200) {
									$template_message = $lang['report_successfull'];
									mgb_sql_connect("UPDATE `".$db['prefix']."spam` SET `sneaked` = '1' WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while sneaking spam entry and updating sql table.", 0);
								} elseif($response == 403) {
									$template_message = $lang['report_failed'];
								} elseif($response == "") {
									$template_message = $lang['report_successfull'];
									mgb_sql_connect("UPDATE `".$db['prefix']."spam` SET `sneaked` = '1' WHERE ID=".secure_value($_GET['id'])." LIMIT 1", "Error while sneaking spam entry and updating sql table.", 0);
								} else {
									$template_message = $response;
								}
							} else {
								$template_message = $lang['entry_already_sneaked'];
							}
						} else {
							$template_message = $lang['empty_needed_value'][43]; // missing api key
						}
					}
				}

				// send an email to user
				if(isset($_GET['notify']) AND $_GET['notify'] == 1) {
					$result = mgb_sql_connect("SELECT name, email, message FROM ".$db['prefix']."entries WHERE id=".secure_value($_GET['id'])." LIMIT 1", "Error while getting information about the user to send an email.", 1);
					$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$name = $data['name'];
					$email = $data['email'];
					$message = $data['message'];

					$date = date("d"."/"."m"."/"."Y");
					$time = date("H".":"."i");

					$url_to_gb = "http://".$settings['h_domain'].$settings['gb_path']."index.php";

					$lang['sendmail_user_notification_title'] = format_mail(repl_uml($lang['sendmail_user_notification_title'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", "");
					$settings['sendmail_user_notification_text'] = format_mail(repl_uml($settings['sendmail_user_notification_text'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", "");

					$mail_header = "content-type: text/plain; charset=".$charset."\n";

					$mail_header .= "from: ".$settings['admin_gbemail'];

					if($settings['mailer_method'] == 0) {
						$mail_send = @mail($email, $lang['sendmail_user_notification_title'], $settings['sendmail_user_notification_text'], $mail_header);
						if($mail_send) {
							$sendemail_successfull = 1;
						} else {
							$sendemail_successfull = 0;
						}
					} elseif($settings['mailer_method'] == 1 AND file_exists(MGB_ROOT."plugins/phpmailer/class.phpmailer.php")) {
						$mail_send = mgb_phpmailer($email, $settings['admin_email'], $name, $settings['h_domain'], $lang['sendmail_user_notification_title'], $settings['sendmail_user_notification_text'], "adminpanel", $language_short, $charset);
						if($mail_send[0] == 0) {
							$sendemail_successfull = 0;
							$template_message = "<br><br>phpmailer: ".$mail_send[1];
						} else {
							$sendemail_successfull = 1;
						}
					}
				}
			}

			// get total number of entries
			$results = mysqli_fetch_assoc(mgb_sql_connect("SELECT COUNT(ID) FROM ".$db['prefix']."spam", "Error while counting spam entries.", 1));
			$total = $results['COUNT(ID)'];

			// compute how many pages there are
			$p = ($total / 20);

			if ($p <= 1) {
				$p = 0;
				if ($total > 1) {
					$how_many_entries = "<span class=\"admin\">".$total."&nbsp;".$lang['entries']."</span>";
				} elseif ($total == 0) {
					$how_many_entries = "<span class=\"admin\">".$lang['no_spam_entries']."</span>";
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
				$sf_forwards = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=".($_GET['p'] + 1).$sid."\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				if ($pages_total >= 3 ) {
					$sf_last = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=".$pages_total."\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
				}
			}

			if ($_GET['p'] > 1) {
				if (($pages_total >= 3) AND ($_GET['p'] > 2)) {
					$sf_first = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=1".$sid."\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
				}
				$sf_backwards = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=".($_GET['p'] - 1).$sid."\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				$sf_forwards = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=".($_GET['p'] + 1).$sid."\" title=\"".$lang['page_forwards']."\">".$lang['page_forwards_symbol']."</a>";
				if (($pages_total >= 3) AND ($_GET['p'] < ($pages_total - 1))) {
					$sf_last = "&nbsp;<a class=\"admin\" href=\"admin.php?action=spam&amp;p=".$pages_total.$sid."\" title=\"".$lang['page_last']."\">".$lang['page_last_symbol']."</a>";
				}
			}

			if ($_GET['p'] == $pages_total) {
				if ($pages_total >= 3) {
					$sf_first = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=1".$sid."\" title=\"".$lang['page_first']."\">".$lang['page_first_symbol']."</a>";
				}
				$sf_backwards = "<a class=\"admin\" href=\"admin.php?action=spam&amp;p=".($_GET['p'] - 1).$sid."\" title=\"".$lang['page_backwards']."\">".$lang['page_backwards_symbol']."</a>";
				$sf_pagenumber = $_GET['p'];
				$sf_forwards = "";
			}

			if ($pages_total <= 0) {
				$content_scrolling_function = "<br><br>";
			}

			// load guestbook entries
			$result = mgb_sql_connect("SELECT ID, name, message, ip, email, hp, comment, timestamp, counter, sneaked FROM ".$db['prefix']."spam ORDER BY counter DESC LIMIT $load_start, $load_end", "Error while loading guestbook entries.", 1);

			$counter = 0;

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

			if(!isset($entry)) { $entry = array(); }

			for($i = 0; $i < count($entry); $i++) {
				$page_entry[$i] = $content_spam;

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
				$ID = $i + 1;
				$page_entry[$i] = template("ENTRY_ID", $ID, $page_entry[$i]);

				$entry_timestamp = mgb_modern_timestamp($entry[$i]['timestamp'], $settings['language_path'], "adminpanel");

				if ($entry[$i]['counter'] >= 3) {
					$page_entry[$i] = template("ENTRY_NAME", "<span style='color: #ff0000'>".substr($entry[$i]['name'], 0, 20)."<br><br>{LANG_USER_BLOCKED}</span><br><br>".$lang['last_contact']."&nbsp;".ceil($time_in_list).$timecode, $page_entry[$i]);
				} else {
					$page_entry[$i] = template("ENTRY_NAME", substr($entry[$i]['name'], 0, 20)."<br><br>".$lang['in_list_since']."&nbsp;".$entry_timestamp, $page_entry[$i]);
				}

				if(strlen($entry[$i]['hp']) > 50) {
					$entry[$i]['hp'] = substr($entry[$i]['hp'], 0, 50).$lang['shortened'];
				}

				// get domain of entry
				$entry_domain = explode("@", $entry[$i]['email']);

				$page_entry[$i] = template("ENTRY_MESSAGE", $entry[$i]['message'], $page_entry[$i]);
				$page_entry[$i] = template("ENTRY_IP", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=add_to_permanent_ip_banlist".$add_page_nr.$sid."\" onClick=\"return confirm('{LANG_CONFIRM_ADD_TO_PERMANENT_IP_BLOCKLIST}'); submit();\" title=\"".$lang['spam_add_to_ip_banlist']."\">".$entry[$i]['ip']."</a>", $page_entry[$i]);
				if(empty($entry[$i]['sneaked'])) {
					$page_entry[$i] = template("ENTRY_REPORT_SPAM", "&nbsp;-&nbsp;<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=report_to_stopforumspam".$add_page_nr.$sid."\" onClick=\"return confirm('{LANG_CONFIRM_REPORT_TO_STOPFORUMSPAM}'); submit();\" title=\"{LANG_REPORT_SPAM}\">".$lang['confirm_report_spam']."</a>", $page_entry[$i]);
				} else {
					$page_entry[$i] = template("ENTRY_REPORT_SPAM", "", $page_entry[$i]);
				}
				$page_entry[$i] = template("ENTRY_EMAIL", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=add_to_permanent_email_banlist".$add_page_nr.$sid."\" onClick=\"return confirm('{LANG_CONFIRM_ADD_TO_PERMANENT_EMAIL_BLOCKLIST}'); submit();\"title=\"".$lang['spam_add_to_email_banlist']."\">".$entry[$i]['email']."</a>", $page_entry[$i]);
				$page_entry[$i] = template("ENTRY_DOMAIN", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=add_to_permanent_domain_banlist".$add_page_nr.$sid."\" onClick=\"return confirm('{LANG_CONFIRM_ADD_TO_PERMANENT_EMAIL_BLOCKLIST}'); submit();\"title=\"".$lang['spam_add_to_domain_banlist']."\">".$entry_domain[1]."</a>", $page_entry[$i]);
				$page_entry[$i] = template("ENTRY_HP", $entry[$i]['hp'], $page_entry[$i]);
				$page_entry[$i] = template("ENTRY_COMMENT", $entry[$i]['comment'], $page_entry[$i]);
				$page_entry[$i] = template("LANG_QUOTE", $lang['quote'], $page_entry[$i]);
				$page_entry[$i] = template("DELETE", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=delete".$add_page_nr.$sid."\" onClick=\"return confirm('{LANG_CONFIRM_DELETE}'); submit();\"><img class=\"icon\" src=\"templates/default/images/delete.png\" title=\"".$lang['delete_entry']."\" alt=\"".$lang['delete_entry']."\"></a>", $page_entry[$i]);
				$page_entry[$i] = template("SPAM_ADD_TO_BLOCKLISTS", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=spam_add_to_blocklists".$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/spam.png\" title=\"".$lang['spam_add_to_blocklists']."\" alt=\"".$lang['spam_add_to_blocklists']."\"></a>", $page_entry[$i]);
				$page_entry[$i] = template("NO_SPAM_DEACTIVATE", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=nospam_deactivate".$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/nospam2.png\" title=\"".$lang['nospam_deactivate_entry']."\" alt=\"".$lang['nospam_deactivate_entry']."\"></a>", $page_entry[$i]);
				$page_entry[$i] = template("NO_SPAM", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=nospam&amp;notify=".$entry[$i]['user_notification'].$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/nospam.png\" title=\"".$lang['nospam_entry']."\" alt=\"".$lang['nospam_entry']."\"></a>", $page_entry[$i]);
				$page_entry[$i] = template("REPORT_AS_NO_SPAM", "<a href=\"admin.php?action=spam&amp;id=".$entry[$i]['ID']."&amp;spam_action=report&amp;notify=".$entry[$i]['user_notification'].$add_page_nr.$sid."\"><img class=\"icon\" src=\"templates/default/images/report.png\" title=\"".$lang['report_entry']."\" alt=\"".$lang['report_entry']."\"></a>", $page_entry[$i]);

				if(!isset($page_include)) { $page_include = NULL; }
				$page_include .= $page_entry[$i];
			}
		} else {
			$page_include = "<span class=\"admin\">".$lang['errormessage'][4]."</span>";
			$content_scrolling_function = "<br>";
		}
	}
?>

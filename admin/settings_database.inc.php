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

	=========================
	settings_database.inc.php
	=========================

	DATE OF CREATION: 30.05.2013; 12:10
	*/

	// make sure nobody has direct acces to this script
	if(!defined('ADMINISTRATION')) {
		include ("error.html");
		die();
	} else {
		require(MGB_ROOT."includes/functions.inc.php");

		// load template
		$content_settings_database = mgb_load_template("admin", "default", "settings_database", $settings['debug_mode']);

		if(!isset($_GET['action'])) { $_GET['action'] = "settings_database"; }
		if(check_rights($_GET['action'], $_SESSION['user_ID'])) {
			if(isset($_POST['sent_settings']) AND $_POST['sent_settings'] == 1) {
				// CREATE BACKUPS
				if(!empty($_POST['edit_create_db_backup_full'])) {
					$script_time_start = microtime(true);
					include(MGB_ROOT."includes/config.inc.php");

					$sql_dump = "-- MGB OpenSource Guestbook SQL Dump\n";
					$sql_dump.= "-- Version: ".$settings['version']."\n";
					$sql_dump.= "-- https://www.m-gb.org/\n";
					$sql_dump.= "--\n";
					$sql_dump.= "-- Host: ".$db['hostname']."\n";
					$sql_dump.= "-- Database: ".$db['dbname']."\n";
					$sql_dump.= "-- Tables: banlist_domains, banlist_emails, banlist_ips, entries, settings, smilies, spam, spam_log, user\n";
					$sql_dump.= "-- ---------------------------------------;\n\n";

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_domains", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_domains", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_emails", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_emails", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_ips", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_ips", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "entries", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "entries", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "settings", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "settings", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "smilies", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "smilies", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "spam", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "spam", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "spam_log", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "spam_log", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "sys_log", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "sys_log", 2);

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "user", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "user", 2);

					$sql_dump.= "-- END OF FILE --";

					$backup_filename = "-".$db['prefix']."full.sql";
				} elseif(!empty($_POST['edit_create_db_backup_entries'])) {
					$script_time_start = microtime(true);
					include(MGB_ROOT."includes/config.inc.php");

					$sql_dump = "-- MGB OpenSource Guestbook SQL Dump\n";
					$sql_dump.= "-- Version: ".$settings['version']."\n";
					$sql_dump.= "-- https://www.m-gb.org/\n";
					$sql_dump.= "--\n";
					$sql_dump.= "-- Host: ".$db['hostname']."\n";
					$sql_dump.= "-- Database: ".$db['dbname']."\n";
					$sql_dump.= "-- Tables: entries\n";
					$sql_dump.= "-- ---------------------------------------;\n\n";

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "entries", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "entries", 2);

					$sql_dump.= "-- END OF FILE --";

					$backup_filename = "-".$db['prefix']."entries.sql";
				} elseif(!empty($_POST['edit_create_db_backup_banlist_ips'])) {
					$script_time_start = microtime(true);
					include(MGB_ROOT."includes/config.inc.php");

					$sql_dump = "-- MGB OpenSource Guestbook SQL Dump\n";
					$sql_dump.= "-- Version: ".$settings['version']."\n";
					$sql_dump.= "-- https://www.m-gb.org/\n";
					$sql_dump.= "--\n";
					$sql_dump.= "-- Host: ".$db['hostname']."\n";
					$sql_dump.= "-- Database: ".$db['dbname']."\n";
					$sql_dump.= "-- Tables: banlist_ips\n";
					$sql_dump.= "-- ---------------------------------------;\n\n";

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_ips", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_ips", 2);

					$sql_dump.= "-- END OF FILE --";

					$backup_filename = "-".$db['prefix']."banlist_ips.sql";
				} elseif(!empty($_POST['edit_create_db_backup_banlist_emails'])) {
					$script_time_start = microtime(true);
					include("../includes/config.inc.php");

					$sql_dump = "-- MGB OpenSource Guestbook SQL Dump\n";
					$sql_dump.= "-- Version: ".$settings['version']."\n";
					$sql_dump.= "-- https://www.m-gb.org/\n";
					$sql_dump.= "--\n";
					$sql_dump.= "-- Host: ".$db['hostname']."\n";
					$sql_dump.= "-- Database: ".$db['dbname']."\n";
					$sql_dump.= "-- Tables: banlist_emails\n";
					$sql_dump.= "-- ---------------------------------------;\n\n";

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_emails", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_emails", 2);

					$sql_dump.= "-- END OF FILE --";

					$backup_filename = "-".$db['prefix']."banlist_emails.sql";
				} elseif(!empty($_POST['edit_create_db_backup_banlist_domains'])) {
					$script_time_start = microtime(true);
					include(MGB_ROOT."includes/config.inc.php");

					$sql_dump = "-- MGB OpenSource Guestbook SQL Dump\n";
					$sql_dump.= "-- Version: ".$settings['version']."\n";
					$sql_dump.= "-- https://www.m-gb.org/\n";
					$sql_dump.= "--\n";
					$sql_dump.= "-- Host: ".$db['hostname']."\n";
					$sql_dump.= "-- Database: ".$db['dbname']."\n";
					$sql_dump.= "-- Tables: banlist_domains\n";
					$sql_dump.= "-- ---------------------------------------;\n\n";

					// get structure of sql table
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_domains", 1);
					$sql_dump.= mgb_get_sql_structure($db['prefix'], "banlist_domains", 2);

					$sql_dump.= "-- END OF FILE --";

					$backup_filename = "-".$db['prefix']."banlist_domains.sql";
				}

				if(!empty($backup_filename)) {
					if(file_exists(MGB_ROOT."save") AND is_dir(MGB_ROOT."save") AND is_writable(MGB_ROOT."save")) {
						$timestamp = time();
						if(mgb_write_export_file(MGB_ROOT."save/".$timestamp.$backup_filename, $sql_dump) == TRUE) {
							$script_time_end = microtime(true);
							mgb_trigger_sys_log('1011', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							$script_time = $script_time_end - $script_time_start;
							$template_message = "<span class='newer_version'><a href='".MGB_ROOT."save/".$timestamp.$backup_filename."' target='_blank'>".str_replace('{TIME}', round($script_time, 3), $lang['sql_dump_successfull'])."</span>";
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][17]."</span>";
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][17]."</span>";
					}
				}

				// DELETE BACKUPS
				if(!empty($_POST['edit_delete_db_backup_full'])) {
					if(!empty($_POST['database_backup_full'])) {
						if(unlink(MGB_ROOT."save/".$_POST['database_backup_full']) == TRUE) {
							mgb_trigger_sys_log('1012', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							$template_message = "<span class='newer_version'>".$lang['edit_delete_backup_successfull']."</span>";
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][19]."</span>"; // error while deleting backup
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				} elseif(!empty($_POST['edit_delete_db_backup_entries'])) {
					if(!empty($_POST['database_backup_entries'])) {
						if(unlink(MGB_ROOT."save/".$_POST['database_backup_entries']) == TRUE) {
							mgb_trigger_sys_log('1012', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							$template_message = "<span class='newer_version'>".$lang['edit_delete_backup_successfull']."</span>";
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][19]."</span>"; // error while deleting backup
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				} elseif(!empty($_POST['edit_delete_db_backup_banlist_ips'])) {
					if(!empty($_POST['database_backup_banlist_ips'])) {
						if(unlink(MGB_ROOT."save/".$_POST['database_backup_banlist_ips']) == TRUE) {
							mgb_trigger_sys_log('1012', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							$template_message = "<span class='newer_version'>".$lang['edit_delete_backup_successfull']."</span>";
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][19]."</span>"; // error while deleting backup
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				} elseif(!empty($_POST['edit_delete_db_backup_banlist_emails'])) {
					if(!empty($_POST['database_backup_banlist_emails'])) {
						if(unlink(MGB_ROOT."save/".$_POST['database_backup_banlist_emails']) == TRUE) {
							mgb_trigger_sys_log('1012', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							$template_message = "<span class='newer_version'>".$lang['edit_delete_backup_successfull']."</span>";
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][19]."</span>"; // error while deleting backup
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				} elseif(!empty($_POST['edit_delete_db_backup_banlist_domains'])) {
					if(!empty($_POST['database_backup_banlist_domains'])) {
						if(unlink(MGB_ROOT."save/".$_POST['database_backup_banlist_domains']) == TRUE) {
							mgb_trigger_sys_log('1012', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
							$template_message = "<span class='newer_version'>".$lang['edit_delete_backup_successfull']."</span>";
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][19]."</span>"; // error while deleting backup
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				}

				// RESTORE BACKUPS

				// full
				elseif(!empty($_POST['edit_restore_db_backup_full'])) {
					if(!empty($_POST['database_backup_full'])) {
						$script_time_start = microtime(true);
						if(mgb_sql_connect("DROP TABLE IF EXISTS 
							".$db['prefix']."banlist_domains,
							".$db['prefix']."banlist_emails,
							".$db['prefix']."banlist_ips,
							".$db['prefix']."entries,
							".$db['prefix']."settings,
							".$db['prefix']."smilies,
							".$db['prefix']."spam,
							".$db['prefix']."spam_log,
							".$db['prefix']."sys_log,
							".$db['prefix']."user", 
							"Error while deleting all tables of MGB OpenSource Guestbook.", 0) == TRUE) {
							$backup_file = file_get_contents(MGB_ROOT."save/".$_POST['database_backup_full']);
							$backup_file = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $backup_file); // delete empty lines
							$backup_file = template("DB_PREFIX", $db['prefix'], $backup_file); // replace prefix
							$backup_part = preg_split("/;\n/", $backup_file); // split the file into separate sql lines
							$real_count = count($backup_part) - 1;
							for($i = 0; $i <= count($backup_part); $i++) {
								if(!empty($backup_part[$i])) {
									if($i != $real_count) {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i].";<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring all tables of MGB OpenSource Guestbook.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									} else {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i]."<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring all tables of MGB OpenSource Guestbook.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									}
								}
							}
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][22]."</span>"; // error while deleting table
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				}

				// entries
				elseif(!empty($_POST['edit_restore_db_backup_entries'])) {
					if(!empty($_POST['database_backup_entries'])) {
						if(mgb_sql_connect("DROP TABLE IF EXISTS ".$db['prefix']."entries", "Error while deleting table ".$db['prefix']."entries.", 0) == TRUE) {
							$script_time_start = microtime(true);
							$backup_file = file_get_contents("../save/".$_POST['database_backup_entries']);
							$backup_file = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $backup_file); // delete empty lines
							$backup_file = template("DB_PREFIX", $db['prefix'], $backup_file); // replace prefix
							$backup_part = preg_split("/;\n/", $backup_file); // split the file into separate sql lines
							$real_count = count($backup_part) - 1;
							for($i = 0; $i <= count($backup_part); $i++) {
								if(!empty($backup_part[$i])) {
									if($i != $real_count) {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i].";<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."entries.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									} else {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i]."<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."entries.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									}
								}
							}
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][22]."</span>"; // error while deleting table
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				}

				// restore banlist_ips
				elseif(!empty($_POST['edit_restore_db_backup_banlist_ips'])) {
					if(!empty($_POST['database_backup_banlist_ips'])) {
						if(mgb_sql_connect("DROP TABLE IF EXISTS ".$db['prefix']."banlist_ips", "Error while deleting table ".$db['prefix']."banlist_ips.", 0) == TRUE) {
							$script_time_start = microtime(true);
							$backup_file = file_get_contents("../save/".$_POST['database_backup_banlist_ips']);
							$backup_file = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $backup_file); // delete empty lines
							$backup_file = template("DB_PREFIX", $db['prefix'], $backup_file); // replace prefix
							$backup_part = preg_split("/;\n/", $backup_file); // split the file into separate sql lines
							$real_count = count($backup_part) - 1;
							for($i = 0; $i <= count($backup_part); $i++) {
								if(!empty($backup_part[$i])) {
									if($i != $real_count) {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i].";<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."banlist_ips.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									} else {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i]."<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."banlist_ips.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									}
								}
							}
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][22]."</span>"; // error while deleting table
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				}

				// restore banlist_emails
				elseif(!empty($_POST['edit_restore_db_backup_banlist_emails'])) {
					if(!empty($_POST['database_backup_banlist_emails'])) {
						if(mgb_sql_connect("DROP TABLE IF EXISTS ".$db['prefix']."banlist_emails", "Error while deleting table ".$db['prefix']."banlist_emails.", 0) == TRUE) {
							$script_time_start = microtime(true);
							$backup_file = file_get_contents("../save/".$_POST['database_backup_banlist_emails']);
							$backup_file = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $backup_file); // delete empty lines
							$backup_file = template("DB_PREFIX", $db['prefix'], $backup_file); // replace prefix
							$backup_part = preg_split("/;\n/", $backup_file); // split the file into separate sql lines
							$real_count = count($backup_part) - 1;
							for($i = 0; $i <= count($backup_part); $i++) {
								if(!empty($backup_part[$i])) {
									if($i != $real_count) {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i].";<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."banlist_emails.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									} else {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i]."<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."banlist_emails.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									}
								}
							}
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][22]."</span>"; // error while deleting table
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				}

				// restore banlist_domains
				elseif(!empty($_POST['edit_restore_db_backup_banlist_domains'])) {
					if(!empty($_POST['database_backup_banlist_domains'])) {
						if(mgb_sql_connect("DROP TABLE IF EXISTS ".$db['prefix']."banlist_domains", "Error while deleting table ".$db['prefix']."banlist_domains.", 0) == TRUE) {
							$script_time_start = microtime(true);
							$backup_file = file_get_contents("../save/".$_POST['database_backup_banlist_domains']);
							$backup_file = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $backup_file); // delete empty lines
							$backup_file = template("DB_PREFIX", $db['prefix'], $backup_file); // replace prefix
							$backup_part = preg_split("/;\n/", $backup_file); // split the file into separate sql lines
							$real_count = count($backup_part) - 1;
							for($i = 0; $i <= count($backup_part); $i++) {
								if(!empty($backup_part[$i])) {
									if($i != $real_count) {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i].";<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."banlist_domains.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									} else {
										$possible_comment = substr($backup_part[$i], '0', '2');
										if($possible_comment != "--") {
											if($settings['debug_mode'] == 1) { echo $i.": ".$backup_part[$i]."<br>"; }
											if(mgb_sql_connect($backup_part[$i], "Error while restoring table ".$db['prefix']."banlist_domains.", 0) == TRUE) {
												$script_time_end = microtime(true);
												mgb_trigger_sys_log('1030', '', '', '', $_SESSION['user_name'], '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
												$script_time = $script_time_end - $script_time_start;
												$template_message = "<span class='newer_version'>".$lang['edit_restore_backup_successfull']."<br>Daf&uuml;r wurden ".round($script_time, 3)." Sekunden ben&ouml;tigt.</span>";
											} else {
												$template_message = "<span class='old_version'>".$lang['errormessage'][21]."</span>"; // error while restoring backup
											}
										}
									}
								}
							}
						} else {
							$template_message = "<span class='old_version'>".$lang['errormessage'][22]."</span>"; // error while deleting table
						}
					} else {
						$template_message = "<span class='old_version'>".$lang['errormessage'][20]."</span>"; // no backup selected
					}
				}
			}

			// load active language
			include ("../language/".$settings['language_path']."/settings.php");

			// load template
			$page_include = $content_settings_database;
			
			// initiate variables
			$edit_option_database_backups_full = "";
			$edit_option_database_backups_entries = "";
			$edit_option_database_backups_banlist_ips = "";
			$edit_option_database_backups_banlist_emails = "";
			$edit_option_database_backups_banlist_domains = "";

			// load data

			// display backups
			$path = "../save/";
			foreach (glob($path."*") as $filename) {
				if($filename != "." && $filename != "..") {
					$filename = preg_replace("/..\/save\//", "", $filename);
					$display = explode("-", $filename);
					if(preg_match("/full/", $display[1])) {
						$edit_option_database_backups_full .= "<option ";
						$age = mgb_modern_timestamp($display[0], $settings['language_path'], "adminpanel");
						$display = date($settings['dateform'], $display[0]);
						$edit_option_database_backups_full .= "value=\"".$filename."\">".$display." :: ".$age." {LANG_OLD}</option>";
					} elseif(preg_match("/entries/", $display[1])) {
						$edit_option_database_backups_entries .= "<option ";
						$age = mgb_modern_timestamp($display[0], $settings['language_path'], "adminpanel");
						$display = date($settings['dateform'], $display[0]);
						$edit_option_database_backups_entries .= "value=\"".$filename."\">".$display." :: ".$age." {LANG_OLD}</option>";
					} elseif(preg_match("/banlist_ips/", $display[1])) {
						$edit_option_database_backups_banlist_ips .= "<option ";
						$age = mgb_modern_timestamp($display[0], $settings['language_path'], "adminpanel");
						$display = date($settings['dateform'], $display[0]);
						$edit_option_database_backups_banlist_ips .= "value=\"".$filename."\">".$display." :: ".$age." {LANG_OLD}</option>";
					} elseif(preg_match("/banlist_emails/", $display[1])) {
						$edit_option_database_backups_banlist_emails .= "<option ";
						$age = mgb_modern_timestamp($display[0], $settings['language_path'], "adminpanel");
						$display = date($settings['dateform'], $display[0]);
						$edit_option_database_backups_banlist_emails .= "value=\"".$filename."\">".$display." :: ".$age." {LANG_OLD}</option>";
					} elseif(preg_match("/banlist_domains/", $display[1])) {
						$edit_option_database_backups_banlist_domains .= "<option ";
						$age = mgb_modern_timestamp($display[0], $settings['language_path'], "adminpanel");
						$display = date($settings['dateform'], $display[0]);
						$edit_option_database_backups_banlist_domains .= "value=\"".$filename."\">".$display." :: ".$age." {LANG_OLD}</option>";
					}
				}
			}

			if(empty($edit_option_database_backups_full)) {
				$edit_option_database_backups_full = "<option value=\"0\">{LANG_EDIT_NO_BACKUP}</option>";
			}
			if(empty($edit_option_database_backups_entries)) {
				$edit_option_database_backups_entries = "<option value=\"0\">{LANG_EDIT_NO_BACKUP}</option>";
			}
			if(empty($edit_option_database_backups_banlist_ips)) {
				$edit_option_database_backups_banlist_ips = "<option value=\"0\">{LANG_EDIT_NO_BACKUP}</option>";
			}
			if(empty($edit_option_database_backups_banlist_emails)) {
				$edit_option_database_backups_banlist_emails = "<option value=\"0\">{LANG_EDIT_NO_BACKUP}</option>";
			}
			if(empty($edit_option_database_backups_banlist_domains)) {
				$edit_option_database_backups_banlist_domains = "<option value=\"0\">{LANG_EDIT_NO_BACKUP}</option>";
			}
			
			// check for mysqli-extension
			if(extension_loaded('mysqli')) {
				$mysql_type = 'mysqli';
			} elseif(extension_loaded('mysql')) {
				$mysql_type = 'mysql';
			} else {
				$mysql_type = 'currently unknown';
			}

			// start replacement for template
			
			// replacement that has nothing to do with front end
			$page_include = template("URL_SETTINGS", "admin.php?action=settings_database".$sid, $page_include);

			// value replacement
			include("../includes/config.inc.php");
			$page_include = template("SERVER_NAME", mysqli_get_host_info(mysqli_connect($db['hostname'], $db['username'], $db['password'])), $page_include);
			mysqli_close(mysqli_connect($db['hostname'], $db['username'], $db['password'])); 
			$page_include = template("DATABASE_NAME", $db['dbname'], $page_include);
			$page_include = template("DATABASE_TYPE", $mysql_type, $page_include);
			$page_include = template("DATABASE_CLIENT_VERSION", $mysql_client, $page_include);
			$page_include = template("DATABASE_SERVER_VERSION", $mysql_server, $page_include);
			$page_include = template("DATABASE_PREFIX", $db['prefix'], $page_include);
			$page_include = template("PHP_VERSION", phpversion(), $page_include);
			$page_include = template("SERVER_DOCUMENT_ROOT", MGB_ROOT, $page_include);

			$page_include = template("EDIT_OPTION_DATABASE_BACKUPS_FULL", $edit_option_database_backups_full, $page_include);
			$page_include = template("EDIT_OPTION_DATABASE_BACKUPS_ENTRIES", $edit_option_database_backups_entries, $page_include);
			$page_include = template("EDIT_OPTION_DATABASE_BACKUPS_BANLIST_IPS", $edit_option_database_backups_banlist_ips, $page_include);
			$page_include = template("EDIT_OPTION_DATABASE_BACKUPS_BANLIST_EMAILS", $edit_option_database_backups_banlist_emails, $page_include);
			$page_include = template("EDIT_OPTION_DATABASE_BACKUPS_BANLIST_DOMAINS", $edit_option_database_backups_banlist_domains, $page_include);

			// is scrolling function needed?
			$content_scrolling_function = "";
		} else {
			$page_include = "<span class=\"admin\">".$lang['errormessage'][4]."</span>"; // user has no access to this page, user level too low
			$content_scrolling_function = "<br>";
		}
	}
?>

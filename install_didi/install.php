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

	===================
	install.php - 0.7.1
	===================
	*/

	// Show all errors but no warnings and make sure they are displayed
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set("display_errors", 1);

	// set root path
	define('MGB_ROOT', dirname(dirname(__FILE__))."/");

	// set timezone
	if(function_exists("date_default_timezone_set")) {
		date_default_timezone_set('Europe/Berlin');
	}

	require MGB_ROOT.'install/includes/functions.inc.php';

	if(file_exists(MGB_ROOT.'install/includes/config.inc.php')) {
		include_once (MGB_ROOT.'install/includes/config.inc.php');
		if(isset($mgb_installation_complete) AND (cleanstr($mgb_installation_complete) == TRUE)) {
			echo "<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: green;\">config.inc.php exists. It seems that MGB has been already installed.</span><br><br>
			<span style=\"font-family: verdana, arial, helvetica, sans-serif; font-size: 12px; font-weight: bold; color: darkblue;\">If you want to upgrade your MGB installation call <a href='upgrade.php'>upgrade.php</a> instead of install.php. If you want a new installation, delete ''config.inc.php'' in root/includes directory and try again.</span>";
			die();
		}
	}

	// start session
	session_name("sid");
	ini_set('url_rewriter.tags', '');
	session_start();
	session_regenerate_id();

	if(SID != NULL) { $sid = "?".SID; } else { $sid = NULL;	}

	// load template
	require MGB_ROOT.'install/includes/load_templates.inc.php';
	require MGB_ROOT.'install/includes/config.inc.php';

	// load main template
	$page_body = $content_install_body;
	$page_header = $content_install_header;

	// set this to 1 if you want to ignore warnings
	$ignore_warnings = 0;

	if(isset($_POST['install_language'])) {
		$_SESSION['install_language'] = $_POST['install_language'];
	}

	if(isset($_SESSION['install_language'])) {
		require (MGB_ROOT."language/".$_SESSION['install_language']."/lang_install.php");
		require (MGB_ROOT."language/".$_SESSION['install_language']."/settings.php");

		// set timezone
		if(function_exists("date_default_timezone_set")) {
			date_default_timezone_set($language_timezone);
		}

		if(empty($_POST['step'])) {
			$page_body = template("TEMPLATE_STEPS", $content_install_eula, $page_body);
			$page_body = template("LANG_EULA_EXPL", $lang['eula_expl'], $page_body);
			$page_body = template("LANG_EULA_AGREE", $lang['eula_agree'], $page_body);
			$page_body = template("LANG_EULA_DISAGREE", $lang['eula_disagree'], $page_body);
			$page_body = template("LANG_NEXT_STEP", $lang['next_step'], $page_body);
		} elseif(!empty($_POST['step']) AND $_POST['step'] == 1) {
			if(isset($_POST['eula_agreement']) AND $_POST['eula_agreement'] == 1) {
				switch(version_compare('7.0.0', phpversion())) {
					case -1: $img_php = "<img src=\"template/images/ok.png\" alt=\"OK\">";
						break;
					case 0: $img_php = "<img src=\"template/images/ok.png\" alt=\"OK\">";
						break;
					case 1: $img_php = "<img src=\"template/images/nok.png\" alt=\"NOT OK\">";
						$error_php = 1;
						break;
				}

				// is mysqli extension loaded?
				if(extension_loaded('mysqli')) {
					$mysqli_loaded_lang = $lang['yes'];
					$img_mysqli = "<img src=\"template/images/ok.png\" alt=\"OK\">";
					echo "yes";
				} else {
					$mysqli_loaded_lang = $lang['no'];
					$img_mysqli = "<img src=\"template/images/nok.png\" alt=\"NOT OK\">";
					$error_mysqli = 1;
					echo "no";
				}

				// does imagegd exist?
				if(function_exists('imagegd2') OR function_exists('imagegd')) {
					$gd_exists_lang = $lang['yes'];
					$img_gd = "<img src=\"template/images/ok.png\" alt=\"OK\">";
				} else {
					$gd_exists_lang = $lang['no'];
					$img_gd = "<img src=\"template/images/warning.png\" alt=\"WARNING\">";
					$error_gd = 1;
				}

				// does the config file exist and is it writable?
				if(!file_exists(MGB_ROOT.'includes/config.inc.php')) {
					if($file = fopen(MGB_ROOT.'includes/config.inc.php', 'w')) {
						fclose($file);
					} else {
						$error_writable = 1;
					}
				}

				if(is_writable(MGB_ROOT.'includes/config.inc.php') && is_writable(MGB_ROOT.'cache') && is_writable(MGB_ROOT.'save')) {
					$cfg_writable_lang = $lang['yes'];
					$img_cfg_writable = "<img src=\"template/images/ok.png\" alt=\"OK\">";
				} else {
					$cfg_writable_lang = $lang['no'];
					$img_cfg_writable = "<img src=\"template/images/nok.png\" alt=\"NOT OK\">";
					$error_writable = 1;
				}

				// is register_globals off?
				if(ini_get('register_globals') == 1) {
					$reg_globals_lang = $lang['active'];
					$img_reg_globals = "<img src=\"template/images/warning.png\" alt=\"WARNING\">";
					$error_reg_globals = 1;
				} else {
					$reg_globals_lang = $lang['inactive'];
					$img_reg_globals = "<img src=\"template/images/ok.png\" alt=\"OK\">";
				}

				$page_body = template("TEMPLATE_STEPS", $content_install_step1, $page_body);
				$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);

				if(!isset($errorcode)) {
					$errorcode = NULL;
				}

				if(isset($error_gd)) { $page_body = template("ERROR_3", "<span class=\"install_error_low\">-&nbsp;".$lang['error_3']."</span><br>", $page_body); $show_next_step = 1; $errorcode++; } else { $page_body = template("ERROR_3", "", $page_body); }
				if(isset($error_reg_globals)) { $page_body = template("ERROR_5", "<span class=\"install_error_low\">-&nbsp;".$lang['error_5']."</span><br>", $page_body); $show_next_step = 1; $errorcode++; } else { $page_body = template("ERROR_5", "", $page_body); }
				if(isset($error_php)) { $page_body = template("ERROR_1", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_1']."</span><br>", $page_body); $show_next_step = 0; $errorcode++; } else { $page_body = template("ERROR_1", "", $page_body); }
				if(isset($error_mysqli)) { $page_body = template("ERROR_6", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_6']."</span><br>", $page_body); $show_next_step = 0; $errorcode++; } else { $page_body = template("ERROR_6", "", $page_body); }
				if(isset($error_mysql)) { $page_body = template("ERROR_2", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_2']."</span><br>", $page_body); $show_next_step = 0; $errorcode++; } else { $page_body = template("ERROR_2", "", $page_body); }
				if(isset($error_writable)) { $page_body = template("ERROR_4", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_4']."</span><br>", $page_body); $show_next_step = 0; $errorcode++; } else { $page_body = template("ERROR_4", "", $page_body); }
				if(!isset($errorcode)) { $page_body = template("NO_ERROR", "<span class=\"install_no_error\">-&nbsp;".$lang['no_error']."</span><br>", $page_body); $show_next_step = 1; } else { $page_body = template("NO_ERROR", "", $page_body); }

				$page_body = template("ERROR", "", $page_body);

				$page_body = template("LANG_THANKS", $lang['thanks'], $page_body);
				$page_body = template("LANG_EXPL_STEP1", $lang['expl_step1'], $page_body);
				$page_body = template("LANG_SERVER", /* $lang['srvcfg_server'] */ "&nbsp;", $page_body);
				$page_body = template("LANG_PHP", $lang['srvcfg_phpversion'], $page_body);
				$page_body = template("LANG_MYSQLI", $lang['srvcfg_mysqliversion'], $page_body);
				// $page_body = template("LANG_MYSQL", $lang['srvcfg_mysqlversion'], $page_body);
				$page_body = template("LANG_GD", $lang['srvcfg_gd'], $page_body);
				$page_body = template("LANG_CFG_WRITABLE", $lang['srvcfg_writable'], $page_body);
				$page_body = template("LANG_REG_GLOBALS", $lang['srvcfg_reg_globals'], $page_body);

				$page_body = template("SRVCFG_SERVER", /* $_SERVER['SERVER_SOFTWARE'] */ "&nbsp;", $page_body);
				$page_body = template("SRVCFG_PHP", phpversion(), $page_body);
				$page_body = template("SRVCFG_MYSQLI", $mysqli_loaded_lang, $page_body);
				// $page_body = template("SRVCFG_MYSQL", $mysql_version, $page_body);
				$page_body = template("SRVCFG_GD", $gd_exists_lang, $page_body);
				$page_body = template("SRVCFG_CFG_WRITABLE", $cfg_writable_lang, $page_body);
				$page_body = template("SRVCFG_REG_GLOBALS", $reg_globals_lang, $page_body);

				$page_body = template("IMG_PHP", $img_php, $page_body);
				$page_body = template("IMG_MYSQLI", $img_mysqli, $page_body);
				// $page_body = template("IMG_MYSQL", $img_mysql, $page_body);
				$page_body = template("IMG_GD", $img_gd, $page_body);
				$page_body = template("IMG_CFG_WRITABLE", $img_cfg_writable, $page_body);
				$page_body = template("IMG_REG_GLOBALS", $img_reg_globals, $page_body);

				if(isset($ignore_warnings) AND $ignore_warnings == 1) {
					$show_next_step = 1;
				}

				if(isset($show_next_step) AND $show_next_step == 1) {
					$next_step = "<form action=\"install.php".$sid."\" method=\"post\">\n";
					$next_step .= "<input type=\"hidden\" name=\"step\" value=\"2\">\n";
					$next_step .= "<input type=\"submit\" class=\"install_button\" name=\"next\" value=\"{LANG_NEXT_STEP}\">\n";
					$next_step .= "</form>";
					$page_body = template("NEXT_STEP", $next_step, $page_body);
				} else {
					$page_body = template("NEXT_STEP", "", $page_body);
				}
			} else {
				// EULA was not accpted, destroy session
				session_unset();
				session_destroy();
				$_SESSION = array();
			}
		} elseif(!empty($_POST['step']) AND $_POST['step'] == 2) {
			$page_body = template("TEMPLATE_STEPS", $content_install_step2, $page_body);
			$page_body = template("LANG_EXPL_STEP2", $lang['expl_step2'], $page_body);
			$page_body = template("LANG_DB_TITLE", $lang['db_title'], $page_body);
			$page_body = template("LANG_DB_HOSTNAME", $lang['db_hostname'], $page_body);
			$page_body = template("LANG_DB_DBNAME", $lang['db_dbname'], $page_body);
			$page_body = template("LANG_DB_USERNAME", $lang['db_username'], $page_body);
			$page_body = template("LANG_DB_PASSWORD", $lang['db_password'], $page_body);
			$page_body = template("LANG_DB_PREFIX", $lang['db_prefix'], $page_body);
			$page_body = template("LANG_ADMIN_TITLE", $lang['admin_title'], $page_body);
			$page_body = template("LANG_ADMIN_NAME", $lang['admin_name'], $page_body);
			$page_body = template("LANG_ADMIN_USERNAME", $lang['admin_username'], $page_body);
			$page_body = template("LANG_ADMIN_PASSWORD", $lang['admin_password'], $page_body);
			$page_body = template("LANG_ADMIN_EMAIL", $lang['admin_email'], $page_body);
			$page_body = template("LANG_ADMIN_GBEMAIL", $lang['admin_gbemail'], $page_body);

			if(!isset($_POST['sent'])) {
				$page_body = template("POST_DB_HOSTNAME", $_SERVER["SERVER_NAME"], $page_body);
				$page_body = template("POST_DB_DBNAME", "", $page_body);
				$page_body = template("POST_DB_USERNAME", "", $page_body);
				$page_body = template("POST_DB_PASSWORD", "", $page_body);
				$page_body = template("POST_DB_PREFIX", "mgb_", $page_body);
				$page_body = template("POST_ADMIN_NAME", $lang['post_admin_name'], $page_body);
				$page_body = template("POST_ADMIN_USERNAME", $lang['post_admin_username'], $page_body);
				$page_body = template("POST_ADMIN_PASSWORD", "", $page_body);
				$page_body = template("POST_ADMIN_EMAIL", "", $page_body);
				$page_body = template("POST_ADMIN_GBEMAIL", "noreply@".$_SERVER["SERVER_NAME"], $page_body);

				$page_body = template("TEMPLATE_WARNINGS", "", $page_body);
				$page_body = template("VALUE_STEP", 2, $page_body);
				$page_body = template("VALUE_SENT", 1, $page_body);
			} elseif(isset($_POST['sent']) AND ($_POST['sent'] == 1)) {
				$_POST['db_hostname'] = cleanstr($_POST['db_hostname']);
				$_POST['db_dbname'] = cleanstr($_POST['db_dbname']);
				$_POST['db_username'] = cleanstr($_POST['db_username']);
				$_POST['db_password'] = cleanstr($_POST['db_password']);
				$_POST['db_prefix'] = cleanstr($_POST['db_prefix']);
				$_POST['admin_name'] = cleanstr($_POST['admin_name']);
				$_POST['admin_username'] = cleanstr($_POST['admin_username']);
				$_POST['admin_password'] = cleanstr($_POST['admin_password']);
				$_POST['admin_email'] = cleanstr($_POST['admin_email']);
				$_POST['admin_gbemail'] = cleanstr($_POST['admin_gbemail']);

				$page_body = template("POST_DB_HOSTNAME", $_POST['db_hostname'], $page_body);
				$page_body = template("POST_DB_DBNAME", $_POST['db_dbname'], $page_body);
				$page_body = template("POST_DB_USERNAME", $_POST['db_username'], $page_body);
				$page_body = template("POST_DB_PASSWORD", $_POST['db_password'], $page_body);
				$page_body = template("POST_DB_PREFIX", $_POST['db_prefix'], $page_body);
				$page_body = template("POST_ADMIN_NAME", $_POST['admin_name'], $page_body);
				$page_body = template("POST_ADMIN_USERNAME", $_POST['admin_username'], $page_body);
				$page_body = template("POST_ADMIN_PASSWORD", $_POST['admin_password'], $page_body);
				$page_body = template("POST_ADMIN_EMAIL", $_POST['admin_email'], $page_body);
				$page_body = template("POST_ADMIN_GBEMAIL", $_POST['admin_gbemail'], $page_body);

				if(!empty($_POST['db_hostname']) AND !empty($_POST['db_dbname']) AND !empty($_POST['db_username']) AND !empty($_POST['db_prefix']) AND !empty($_POST['admin_name']) AND !empty($_POST['admin_username']) AND !empty($_POST['admin_password']) AND !empty($_POST['admin_email']) AND !empty($_POST['admin_gbemail'])) {
					// modify mysql error reporting
					mysqli_report(MYSQLI_REPORT_ERROR);
					
					// check connection to database
					$link = mysqli_connect($_POST['db_hostname'], $_POST['db_username'], $_POST['db_password'], $_POST['db_dbname']);

					if(isset($link) AND ($link == TRUE)) {
						// check if another installation with this prefix is already in the database
						$sql[1] = "SELECT * FROM ".$_POST['db_prefix']."banlist_domains";
						$sql[2] = "SELECT * FROM ".$_POST['db_prefix']."banlist_emails";
						$sql[3] = "SELECT * FROM ".$_POST['db_prefix']."banlist_ips";
						$sql[4] = "SELECT * FROM ".$_POST['db_prefix']."entries";
						$sql[5] = "SELECT * FROM ".$_POST['db_prefix']."settings";
						$sql[6] = "SELECT * FROM ".$_POST['db_prefix']."smilies";
						$sql[7] = "SELECT * FROM ".$_POST['db_prefix']."spam";
						$sql[8] = "SELECT * FROM ".$_POST['db_prefix']."spam_log";
						$sql[9] = "SELECT * FROM ".$_POST['db_prefix']."sys_log";
						$sql[10] = "SELECT * FROM ".$_POST['db_prefix']."user";

						$link = new mysqli($_POST['db_hostname'], $_POST['db_username'], $_POST['db_password'], $_POST['db_dbname']) or die ("(install.php) Error, line 280: " . $link->connect_error);
						// mysqli_set_charset($link, 'utf8');

						foreach($sql as $val) {
							if($link->query($val) == TRUE) {
								$prefix_already_used = 1;
							} else {
								if($prefix_already_used != 1) {
									$prefix_already_used = 0;
								}
							}
						}

						if($prefix_already_used == 0) {
							if(check_mail($_POST['admin_email']) AND check_mail($_POST['admin_gbemail'])) {
								if(check_prefix($_POST['db_prefix'])) {
									if(check_username($_POST['admin_username'])) {
										$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
										$page_body = template("ERROR", "", $page_body);
										$page_body = template("NO_ERROR", "<span class=\"install_no_error\">-&nbsp;".$lang['no_error']."</span><br>", $page_body);
										$page_body = template("VALUE_STEP", 3, $page_body);
										$page_body = template("VALUE_SENT", 2, $page_body);

										$_SESSION['db_hostname'] = $_POST['db_hostname'];
										$_SESSION['db_dbname'] = $_POST['db_dbname'];
										$_SESSION['db_username'] = $_POST['db_username'];
										$_SESSION['db_password'] = $_POST['db_password'];
										$_SESSION['db_prefix'] = $_POST['db_prefix'];
										$_SESSION['admin_name'] = $_POST['admin_name'];
										$_SESSION['admin_username'] = $_POST['admin_username'];
										$_SESSION['admin_password'] = $_POST['admin_password'];
										$_SESSION['admin_email'] = $_POST['admin_email'];
										$_SESSION['admin_gbemail'] = $_POST['admin_gbemail'];
									} else {
										$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
										$page_body = template("ERROR", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_6_step2']."</span><br>", $page_body);
										$page_body = template("NO_ERROR", "", $page_body);
										$page_body = template("VALUE_STEP", 2, $page_body);
										$page_body = template("VALUE_SENT", 1, $page_body);
									}
								} else {
									$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
									$page_body = template("ERROR", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_5_step2']."</span><br>", $page_body);
									$page_body = template("NO_ERROR", "", $page_body);
									$page_body = template("VALUE_STEP", 2, $page_body);
									$page_body = template("VALUE_SENT", 1, $page_body);
								}
							} else {
								$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
								$page_body = template("ERROR", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_2_step2']."</span><br>", $page_body);
								$page_body = template("NO_ERROR", "", $page_body);
								$page_body = template("VALUE_STEP", 2, $page_body);
								$page_body = template("VALUE_SENT", 1, $page_body);
							}
						} else {
							$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
							$page_body = template("ERROR", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_4_step2']."</span><br>", $page_body);
							$page_body = template("NO_ERROR", "", $page_body);
							$page_body = template("VALUE_STEP", 2, $page_body);
							$page_body = template("VALUE_SENT", 1, $page_body);
						}
					} else {
						$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
						$page_body = template("ERROR", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_3_step2']."</span><br>", $page_body);
						$page_body = template("NO_ERROR", "", $page_body);
						$page_body = template("VALUE_STEP", 2, $page_body);
						$page_body = template("VALUE_SENT", 1, $page_body);
					}
				} else {
					$page_body = template("TEMPLATE_WARNINGS", $content_install_warnings, $page_body);
					$page_body = template("ERROR", "<span class=\"install_error_critical\">-&nbsp;".$lang['error_1_step2']."</span><br>", $page_body);
					$page_body = template("NO_ERROR", "", $page_body);
					$page_body = template("VALUE_STEP", 2, $page_body);
					$page_body = template("VALUE_SENT", 1, $page_body);
				}

				$page_body = template("ERROR_1", "", $page_body);
				$page_body = template("ERROR_2", "", $page_body);
				$page_body = template("ERROR_3", "", $page_body);
				$page_body = template("ERROR_4", "", $page_body);
				$page_body = template("ERROR_5", "", $page_body);
			}
		} elseif(!empty($_POST['step']) AND ($_POST['step'] == 3)) {
			require_once (MGB_ROOT."install/mysql.php");

			if($success == count($sql)) {
				$config_file = "<?php\n\n";
				$config_file.= "\t/*\n";
				$config_file.= "\tTHIS FILE WAS AUTOMATICALLY GENERATED BY MGB\n";
				$config_file.= "\tDO NOT MODIFY IT!\n\n";
				$config_file.= "\tDATE FILE WAS CREATED: ".date("d M Y, H:i")."\n";
				$config_file.= "\t*/\n\n";
				$config_file.= "\t// Database settings\n";
				$config_file.= "\t\$db = array();\n\n";
				$config_file.= "\t\$db['hostname'] = '".$_SESSION['db_hostname']."';\n";
				$config_file.= "\t\$db['dbname'] = '".$_SESSION['db_dbname']."';\n";
				$config_file.= "\t\$db['username'] = '".$_SESSION['db_username']."';\n";
				$config_file.= "\t\$db['password'] = '".$_SESSION['db_password']."';\n";
				$config_file.= "\t\$db['prefix'] = '".$_SESSION['db_prefix']."';\n\n";
				$config_file.= "\t\$mgb_installation_complete = TRUE;\n";
				$config_file.= "\t\$mgb_installation_timestamp = ".time().";\n\n";
				$config_file.= "?>";
				if(write_config(MGB_ROOT.'includes/config.inc.php', $config_file) == TRUE) {
					$success++;
				} else {
					echo "<span class='install_error_critical'>ERROR: Config could not be written!</span><br><br>";
				}
			}

			$to = count($sql) + 1;

			if($success == $to) {
				$page_body = template("TEMPLATE_STEPS", $content_install_step3, $page_body);
				$page_body = template("LANG_EXPL_STEP3", $lang['expl_step3'], $page_body);
				$page_body = template("LANG_TO_ADMINISTRATION", $lang['to_administration'], $page_body);
				$page_body = template("LANG_IMPORT", $lang['import'], $page_body);
				$page_body = template("LANG_TO_GUESTBOOK", $lang['to_guestbook'], $page_body);

				// do a complete backup of fresh install
				include ("../includes/functions.inc.php");

				sleep(1); // wait one second

				$sql_dump = "-- MGB OpenSource Guestbook SQL Dump\n";
				$sql_dump.= "-- Version: ".MGB_VERSION."\n";
				$sql_dump.= "-- http://www.m-gb.org/\n";
				$sql_dump.= "--\n";
				$sql_dump.= "-- Host: ".$_SESSION['db_hostname']."\n";
				$sql_dump.= "-- Database: ".$_SESSION['db_dbname']."\n";
				$sql_dump.= "-- Tables: banlist_domains, banlist_emails, banlist_ips, entries, settings, smilies, spam, spam_log, user\n";
				$sql_dump.= "-- Type of backup: first backup after a fresh install\n";
				$sql_dump.= "-- ---------------------------------------;\n\n";

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "banlist_domains", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "banlist_domains", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "banlist_emails", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "banlist_emails", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "banlist_ips", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "banlist_ips", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "entries", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "entries", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "settings", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "settings", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "smilies", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "smilies", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "spam", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "spam", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "spam_log", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "spam_log", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "sys_log", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "sys_log", 2);

				// get structure of sql table
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "user", 1);
				$sql_dump.= mgb_get_sql_structure($_SESSION['db_prefix'], "user", 2);

				$sql_dump.= "-- END OF FILE --";

				$backup_filename = "-".$_SESSION['db_prefix']."full.sql";

				if(!empty($backup_filename)) {
					if(file_exists(MGB_ROOT.'save') AND is_dir(MGB_ROOT.'save') AND is_writable(MGB_ROOT.'save')) {
						mgb_write_export_file(MGB_ROOT.'save/'.time().$backup_filename, $sql_dump);
					}
				}
			}

			// destroy session
			session_unset();
			session_destroy();
			$_SESSION = array();
		} else {
			// something went wrong
			$page_body = template("TEMPLATE_STEPS", $content_install_step3_fail, $page_body);
			$page_body = template("LANG_EXPL_STEP3_FAIL", $lang['expl_step3_fail'], $page_body);
			$page_body = template("LANG_TO_INSTALL", $lang['to_install'], $page_body);

			// destroy session
			session_unset();
			session_destroy();
			$_SESSION = array();
		}
	}

	if(!isset($_SESSION['install_language'])) {
		// choose language for installation
		$path = MGB_ROOT.'language/';
		foreach (glob($path."*") as $filename) {
			if($filename != "." && $filename != "..") {
				if(is_dir($filename)) {
					if(!isset($install_option_language)) { $install_option_language = ""; }
					include ($filename."/settings.php");
					$install_option_language .= "<option ";
					if(basename($filename) == "lang_german_utf8") {
						$install_option_language .= "selected ";
					}
					$filename = str_replace(MGB_ROOT."language/", '', $filename);
					$install_option_language .= "value=\"".$filename."\">".$language."</option>";
				}
			}
		}

		$language_short = "en";
		$lang['h_title'] = "Welcome to the installation of MGB OpenSource Guestbook ".MGB_VERSION;
		$charset = "utf-8";
		$lang['title'] = "Installation of MGB OpenSource Guestbook ".MGB_VERSION;
		$lang['next_step'] = "&raquo; Next step &raquo;";

		$page_body = template("TEMPLATE_STEPS", $content_install_choose_language, $page_body);
		$page_body = template("INSTALL_OPTION_LANGUAGE", $install_option_language, $page_body);
	}

	$page_header = template("H_LANGUAGE_SHORT", $language_short, $page_header);
	$page_header = template("H_INSTALL_TITLE", $lang['h_title'], $page_header);
	$page_header = template("H_CHARSET", $charset, $page_header);

	$page_body = template("TEMPLATE_HEADER", $page_header, $page_body);
	$page_body = template("TITLE", $lang['title'], $page_body);
	$page_body = template("INSTALL_FORM_ACTION", "install.php".$sid, $page_body);
	$page_body = template("LANG_NEXT_STEP", $lang['next_step'], $page_body);
	$page_body = template("TEMPLATE_COPYRIGHT", $content_install_copyright, $page_body);
	$page_body = template("TEMPLATE_FOOTER", $content_install_footer, $page_body);
	$page_body = template("COPYRIGHT_DATE", date("Y"), $page_body);
	$page_body = template("MGB_VERSION", MGB_VERSION, $page_body);

	echo $page_body;
?>

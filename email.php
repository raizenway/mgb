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

	=========
	email.php
	=========
	*/

	// show all errors
	error_reporting(E_ALL & ~E_NOTICE);

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Datum in der Vergangenheit

	// define site name
	$site_name = "email.php";

	// set root path
	define('MGB_ROOT', dirname(__FILE__)."/");

	// include functions
	require_once(MGB_ROOT.'includes/functions.inc.php');

	// start the session
	@session_name("newentry");
	ini_set('url_rewriter.tags', '');
	@session_start();
	@session_regenerate_id();

	if(empty($_SESSION['start_time'])) {
		$_SESSION['start_time'] = time();
	}

	// check if mgb has been already installed
	mgb_iou_check("");

	// load config, settings, language files and necessary templates
	require(MGB_ROOT.'includes/config.inc.php');
	require(MGB_ROOT.'includes/load_settings.inc.php');

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

	// override language path if lang parameter is set
	if(!empty($_GET['lang'])) {
		MGB_ROOT."language/".$settings['language_path'] = mgb_get_language_path($_GET['lang']);
	}

	require(MGB_ROOT."language/".$settings['language_path'].'/lang_main.php');
	require(MGB_ROOT."language/".$settings['language_path'].'/settings.php');

	// set timezone
	if(function_exists("date_default_timezone_set")) {
		date_default_timezone_set($settings['timezone']);
	}

	// check if this is a direct or not allowed access to the script
	if($settings['direct_access'] == 1) {
		if(mgb_http_referer($settings['direct_access_text'], $settings['search_engines_excluded'], $settings['search_engines'], $settings['banlist_log'], $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], $db['prefix'], $site_name, $settings['debug_mode']) == FALSE) {
			// destroy active session
			session_unset();
			session_destroy();
			$_SESSION = array();
			die();
		}
	}

	// load general templates
	$content_header = mgb_load_template("user", $settings['template_path'], "general/header", $settings['debug_mode']);
	$content_footer = mgb_load_template("user", $settings['template_path'], "general/footer", $settings['debug_mode']);
	$content_copyright = mgb_load_template("user", $settings['template_path'], "general/copyright", $settings['debug_mode']);
	$content_scrolling_function = mgb_load_template("user", $settings['template_path'], "general/scrolling_function", $settings['debug_mode']);
	$content_errormessage = mgb_load_template("user", $settings['template_path'], "general/errormessage", $settings['debug_mode']);
	if($settings['captcha_method'] == 2) {
		if(file_exists("plugins/recaptcha/recaptchalib.php")) {
			require_once('plugins/recaptcha/recaptchalib.php');
			$publickey = $settings['recaptcha_pub_key'];
			$content_captcha = "<br>";
			$content_captcha.= "<script type=\"text/javascript\">var RecaptchaOptions = { ";
			$content_captcha.= "lang : '".$language_short."',";
			$content_captcha.= "theme : '".$settings['recaptcha_style']."',";
			$content_captcha.= " };</script>";
			$content_captcha.= recaptcha_get_html($publickey)."<br>";
		} else {
		 	$content_captcha = mgb_load_template("user", $settings['template_path'], "general/captcha", $settings['debug_mode']);
			}
	} elseif($settings['captcha_method'] == 3) {
		if(file_exists("plugins/ayah/ayah.php")) {
		 		require_once("plugins/ayah/ayah.php");
		 		$ayah = new AYAH();
		 		// $content_captcha = mgb_load_template("user", $settings['template_path'], "general/captcha_ayah", $settings['debug_mode']);
		 		$content_captcha = $ayah->getPublisherHTML();
	  		}
	} else {
		$content_captcha = mgb_load_template("user", $settings['template_path'], "general/captcha", $settings['debug_mode']);
	}


	// load main templates
	$content_email_body = mgb_load_template("user", $settings['template_path'], "main/email_body", $settings['debug_mode']);
	$content_email_body_success = mgb_load_template("user", $settings['template_path'], "main/email_body_success", $settings['debug_mode']);
	$content_email_user_accept_akismet_service = mgb_load_template("user", $settings['template_path'], "main/email_user_accept_akismet_service", $settings['debug_mode']);

	$captcha_generated = 0;

	// load user data
	if(empty($_GET['id'])) {
		if(empty($_SESSION['file_call'])) {
			$_SESSION['file_call'] = time();
		}
		if($_SESSION['file_call_count'] < $settings['autoblock_value']) {
			$_SESSION['file_call_count']++;
		} else {
			if(time() - $_SESSION['file_call'] <= $settings['autoblock_config']) {
				// check if user's ip is on banlist
				$result = mgb_sql_connect("SELECT banned_ip FROM ".$db['prefix']."banlist_ips WHERE banned_ip='".$_SERVER['REMOTE_ADDR']."'", "Error while checking remote IP.", 1);
				$ip_check = mysqli_fetch_array($result, MYSQLI_ASSOC);
				if($ip_check['banned_ip'] != $_SERVER['REMOTE_ADDR']) {
					// put user's ip on banlist
					$result_parts = explode(".", $_SERVER['REMOTE_ADDR']);
					mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_ips (
								ID,
								banned_ip,
								banned_ip_first,
								banned_ip_second,
								banned_ip_third,
								banned_ip_fourth,
								matches,
								timestamp )
							values (
								NULL,
								'".$_SERVER['REMOTE_ADDR']."',
								'".$result_parts[0]."',
								'".$result_parts[1]."',
								'".$result_parts[2]."',
								'".$result_parts[3]."',
								'0',
								'".time()."' )", "Error while saving data into ".$db['prefix']."banlist_ips", 0);
				}

				// now destroy actual session
				session_unset();
				session_destroy();
				$_SESSION = array();

				// and lock the user/spammer out
				die ();
			} else {
				$_SESSION['file_call'] = time();
			}
		}
	} elseif($_GET['id'] == "denied") {
		$errorcode = 8; // user refuses to receive emails over the guestbook
		$_POST['sent'] = 1;
	} elseif($_GET['id'] != "admin") {
		$result = mgb_sql_connect("SELECT ID, name, email, user_show_email FROM ".$db['prefix']."entries WHERE id=".secure_value($_GET['id']), "Error while loading information about user.", 1);
		$sendemail = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if(empty($sendemail['ID'])) { // this id doesn't exist
			if(empty($_SESSION['file_call'])) {
				$_SESSION['file_call'] = time();
			}
			if($_SESSION['file_call_count'] <= $settings['autoblock_value']) {
				$_SESSION['file_call_count']++;
			} else {
				if(time() - $_SESSION['file_call'] <= $settings['autoblock_config']) {
					// check if user's ip is on banlist
					$result = mgb_sql_connect("SELECT banned_ip FROM ".$db['prefix']."banlist_ips WHERE banned_ip='".$_SERVER['REMOTE_ADDR']."'", "Error while checking remote IP.", 1);
					$ip_check = mysqli_fetch_array($result, MYSQLI_ASSOC);
					if($ip_check['banned_ip'] != $_SERVER['REMOTE_ADDR']) {
						// put user's ip on banlist
						$result_parts = explode(".", $_SERVER['REMOTE_ADDR']);
						mgb_sql_connect("INSERT INTO ".$db['prefix']."banlist_ips (
									ID,
									banned_ip,
									banned_ip_first,
									banned_ip_second,
									banned_ip_third,
									banned_ip_fourth,
									matches,
									timestamp )
								values (
									NULL,
									'".$_SERVER['REMOTE_ADDR']."',
									'".$result_parts[0]."',
									'".$result_parts[1]."',
									'".$result_parts[2]."',
									'".$result_parts[3]."',
									'0',
									'".time()."' )", "Error while saving data into ".$db['prefix']."banlist_ips", 0);
					}

					// now destroy actual session
					session_unset();
					session_destroy();
					$_SESSION = array();

					// and lock the user/spammer out
					die ();
				} else {
					$_SESSION['file_call'] = time();
				}
			}
		} else {
			$sendemail_name = $sendemail['name'];
			$sendemail_email = $sendemail['email'];
			$sendemail_user_show_email = $sendemail['user_show_email'];

			if($sendemail_user_show_email == 0) { // user refuses to receive emails over the guestbook
				$errorcode = 8;
				$_POST['sent'] = 1;
			}
		}
	} elseif($_GET['id'] == "admin") {
		$sendemail_email = $settings['admin_email'];
		$sendemail_name = $settings['admin_name'];
	}

	if(!empty($_POST['sent'])) {
		// get information about formular elements
		if(empty($_POST[$_SESSION['FORM_ELEMENT_CAPTCHA']])) { $_POST[$_SESSION['FORM_ELEMENT_CAPTCHA']] = ""; }
		$_POST['name'] = $_POST[$_SESSION['FORM_ELEMENT_NAME']];
		$_POST['email'] = $_POST[$_SESSION['FORM_ELEMENT_EMAIL']];
		$_POST['message'] = $_POST[$_SESSION['FORM_ELEMENT_MESSAGE']];
		$_POST['captcha'] = $_POST[$_SESSION['FORM_ELEMENT_CAPTCHA']];

		// delete html, php code and white spaces
		if(empty($_POST['user_sendcopytome'])) { $_POST['user_sendcopytome'] = 0; }
		if(empty($_POST['name'])) { $_POST['name'] = ""; }
		if(empty($_POST['email'])) { $_POST['email'] = ""; }
		if(empty($_POST['message'])) { $_POST['message'] = ""; }

		// ANTI-SPAM #1
		// ============
		// check www.stopforumspam.com if user is known for intense spamming
		if($settings['check_against_anti_spam_sites'] == 1) {
			if(mgb_spam_request($_POST['name'], $_POST['email'], $_SERVER['REMOTE_ADDR'], $settings['sfs_username_frequency'], $settings['sfs_email_frequency'], $settings['sfs_ip_frequency'], $settings['sfs_username_required'], $settings['sfs_email_required'], $settings['sfs_ip_required']) == 1) {
				$errorcode = 19; // user was blocked by www.stopforumspam.com
			}
		}

		$_POST['email'] = cleanstr($_POST['email']);
		$_POST['user_sendcopytome'] = cleanstr($_POST['user_sendcopytome']);

		// include akismet if it exists
		if(file_exists("plugins/akismet/akismet.class.php") AND (!empty($settings['akismet_api'])) AND ($settings['akismet_api'] != "") AND (!empty($_POST['user_accept_akismet_service']) AND $_POST['user_accept_akismet_service'] == 1) AND ($_POST['name'] != "") AND ($_POST['email'] != "") AND ($_POST['message'] != "")) {
			include ("plugins/akismet/akismet.class.php");

			$akismet_author = bbcode_delete($_POST['name']);
			$akismet_email = bbcode_delete($_POST['email']);
			$akismet_website = bbcode_delete($_POST['hp']);
			$akismet_body = bbcode_delete($_POST['message']);

			// check for spam
			// Load array with comment data.
			$comment = array(
				'author' => $akismet_author,
				'email' => $akismet_email,
				'website' => $akismet_website,
				'body' => $akismet_body,
				'permalink' => 'http://'.$settings['h_domain'].$settings['gb_path'],
				'user_ip' => $_SERVER['REMOTE_ADDR'], // Optional, if not in array defaults to $_SERVER['REMOTE_ADDR'].
				'user_agent' => $_SERVER['HTTP_USER_AGENT'], // Optional, if not in array defaults to $_SERVER['HTTP_USER_AGENT'].
				);

			// Instantiate an instance of the class.
			$akismet = new Akismet('http://'.$settings['h_domain'].$settings['gb_path'], $settings['akismet_api'], $comment);

			// Test for errors.
			if($akismet->errorsExist()) { // Returns true if any errors exist.
				if($akismet->isError('AKISMET_INVALID_KEY')) {
					echo "AKISMET API KEY INVALID";
				} elseif($akismet->isError('AKISMET_RESPONSE_FAILED')) {
					echo "AKISMET RESPONSE FAILED";
				} elseif($akismet->isError('AKISMET_SERVER_NOT_FOUND')) {
					echo "AKISMET_SERVER_NOT_FOUND";
				}
			} else {
				// No errors, check for spam.
				if($akismet->isSpam()) { // Returns true if Akismet thinks the comment is spam.
					die();
				}
			}
		}

		// form was sent and is ok!
		if(empty($errorcode)) { $errorcode = ""; }
		if(empty($errorcode) OR $errorcode != 8) {
			// check if user typed too fast and detect possible spam
			if($settings['keystroke'] == 1 AND $errorcode != 10) {
				if(empty($_SESSION['keystroke_ban_time'])) {
					if(!mgb_get_keystrokes($settings['keystroke_max_cps'], $settings['keystroke_ban_time'], $settings['dynamic_fieldnames'], $settings['debug_mode'])) {
						$errorcode = 17; // too fast typing, possible spam robot?
						$type = 11;
					}
				} else {
					$keystroke_ban_time_rest = $_SESSION['keystroke_ban_time'] - time();
					if($keystroke_ban_time_rest >= 1) {
						$errorcode = 18; // user is already banned for too fast typing
					} else {
						if(!mgb_get_keystrokes($settings['keystroke_max_cps'], $settings['keystroke_ban_time'], $settings['dynamic_fieldnames'], $settings['debug_mode'])) {
							$errorcode = 17; // too fast typing, possible spam robot?
							$type = 11; // blocked by keystroke
						}
					}
				}
			}

			// check if captcha is correct
			if($settings['captcha'] == 1 AND !empty($_POST['name']) AND !empty($_POST['email']) AND !empty($_POST['message'])) {
				if($settings['captcha_method'] == 0) { // security code
					if($_SESSION['CAPTCHA_CODE'] != $_POST['captcha']) {
						$errorcode = 7;  // captcha wrong or not set
						mgb_trigger_sys_log('4004', '', '', '', '', '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
					}
				} elseif($settings['captcha_method'] == 1) { // mathematical captcha
					if($_SESSION['CAPTCHA_SUM'] != $_POST['captcha']) {
						$errorcode = 7; // captcha wrong or not set
						mgb_trigger_sys_log('4004', '', '', '', '', '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
					}
				} elseif($settings['captcha_method'] == 2) { // reCaptcha
					if(file_exists("plugins/recaptcha/recaptchalib.php")) {
						require_once('plugins/recaptcha/recaptchalib.php');
						$resp = recaptcha_check_answer($settings['recaptcha_private_key'],
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);
						if(!$resp->is_valid) {
							$errorcode = 7; // captcha wrong or not set
							mgb_trigger_sys_log('4004', '', '', '', '', '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
						}
					} else {
						echo "<span>reCaptcha Plugin not found!</span><br>";
						$errorcode = 7;
					}
				} elseif($settings['captcha_method'] == 3) { // ayah (are you a human?)
							if(file_exists("plugins/ayah/ayah.php")) {
								if (array_key_exists('send', $_POST)) {
									$score = $ayah->scoreResult();
									if(!$score) {
										// user didn't pass the game
										$errorcode = 7;
										mgb_trigger_sys_log('4004', '', '', '', '', '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
									}
								}
							} else {
						echo "<span>ayah Plugin not found!</span></br>";
						$errorcode = 7;
					}
				}

				if((!empty($settings['banlist_log']) AND $errorcode == 7) AND (!empty($_POST['captcha']) OR !empty($_POST['recaptcha_response_field']))) {
					$type = 9; // captcha wrong
					mgb_sql_connect("INSERT INTO ".$db['prefix']."spam_log (
						ID ,
						ip ,
						name ,
						email ,
						user_agent ,
						hp ,
						message ,
						type ,
						site ,
						timestamp
						) values (
						NULL ,
						'".$_SERVER['REMOTE_ADDR']."' ,
						'".cleanstr($_POST['name'])."' ,
						'".cleanstr($_POST['email'])."' ,
						'".$_SERVER['HTTP_USER_AGENT']."' ,
						'".cleanstr($_POST['hp'])."' ,
						'".cleanstr($_POST['message'])."' ,
						'".$type."' ,
						'".$site_name."' ,
						'".time()."')", "ERROR while saving data into spam_log.", 0);
				}
			}

			// check email
			if(!check_mail($_POST['email'])) { $errorcode = 4; }

			// check ip, email and domain with banlists
			if((!empty($_POST['name']) AND !empty($_POST['email']) AND !empty($_POST['message'])) AND ($errorcode != 7) AND ($errorcode != 4) AND (!$settings['banlist_ips'] == 1 OR $settings['banlist_emails'] == 1 OR $settings['banlist_domains'] == 1)) {
				$check_banlists = mgb_check_banlists($_SERVER['REMOTE_ADDR'], $_POST['email'], $settings['blocktime'], $settings['banlist_ips'], $settings['banlist_emails'], $settings['banlist_domains'], $settings['debug_mode']);
				if($check_banlists == 1) {
					$errorcode = 14; // blocked by ip
					$type = 1; // blocked by ip
				} elseif($check_banlists == 2) {
					$errorcode = 12; // blocked by email
					$type = 3; // blocked by email
				} elseif($check_banlists == 3) {
					$errorcode = 13; // blocked by domain
					$type = 4; // blocked by domain
				}

				if(!empty($settings['banlist_log']) AND ($errorcode == 12 OR $errorcode == 13 OR $errorcode == 14 OR $errorcode == 17)) {
					mgb_sql_connect("INSERT INTO ".$db['prefix']."spam_log (
						ID ,
						ip ,
						name ,
						email ,
						user_agent ,
						hp ,
						message ,
						type ,
						site ,
						timestamp
						) values (
						NULL ,
						'".$_SERVER['REMOTE_ADDR']."' ,
						'".cleanstr($_POST['name'])."' ,
						'".cleanstr($_POST['email'])."' ,
						'".$_SERVER['HTTP_USER_AGENT']."' ,
						'".cleanstr($_POST['hp'])."' ,
						'".cleanstr($_POST['message'])."' ,
						'".$type."' ,
						'".$site_name."' ,
						'".time()."')", "ERROR while saving data into spam_log.", 0);
				}

				// send mail to admin if an email is set
				if(!empty($settings['spam_mail']) AND !empty($settings['spam_mail']) AND !empty($errorcode)) {
					mgb_spam_mail($charset,
						$settings['spam_mail'],
						$settings['admin_gbemail'],
						$_SERVER['REMOTE_ADDR'],
						$_POST['name'],
						$_POST['email'],
						$_POST['hp'],
						$_SERVER['HTTP_USER_AGENT'],
						'',
						'',
						$_POST['message'],
						$site_name,
						$type,
						$settings['mailer_method']);
				}
			}

			// check necessary fields
			if(empty($_POST['message'])) { $errorcode = 1; }
			if(empty($_POST['email'])) { $errorcode = 2; }
			if(empty($_POST['name'])) { $errorcode = 3; }

			if(!empty($settings['akismet_plugin']) AND $_POST['user_accept_akismet_service'] != 1) { $errorcode = 11; } // akismet agreement hasn't been accepted

			if(empty($errorcode)) {
				// delete bbcode
				$_POST['name'] = bbcode_delete($_POST['name']);
				$_POST['message'] = bbcode_delete($_POST['message']);

				$_POST['message'] = nl2br($_POST['message']);
				$t1 = chr(10);
				$t2 = chr(13);
				$_POST['message'] = str_replace($t1,'', $_POST['message']);
				$_POST['message'] = str_replace($t2,'', $_POST['message']);

				$name = $_POST['name'];
				$email = $_POST['email'];
				$message = $_POST['message'];
				$url_to_gb = "http://".$settings['h_domain'].$settings['gb_path']."email.php?id=admin";

				$date = date("d"."/"."m"."/"."Y");
				$time = date("H".":"."i");

				if($settings['mailer_method'] == 0) {
					$mail_header = "content-type: text/plain; charset=".$charset."\r\n";
					$mail_header .= "from: ".$_POST['email']."\r\n";
					$mail_header .= "Reply-To: ".$_POST['email']."\r\n";
					$mail_header .= "X-Mailer: PHP/".phpversion();

					$mail_send = @mail($sendemail_email,
					format_mail(repl_uml($lang['email_caption'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""),
					format_mail(repl_uml($settings['sendmail_contactmail_text'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""),
					$mail_header);
					if($mail_send) {
						$sendemail_successfull = 1;
						mgb_trigger_sys_log('4001', $_POST['name'], $_POST['email'], $_POST['message'], '', '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
					}
				} elseif($settings['mailer_method'] == 1) {
					$mail_send = mgb_phpmailer($sendemail_email, $email, $name, $settings['h_domain'],
					format_mail(repl_uml($lang['email_caption'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""),
					format_mail(repl_uml($settings['sendmail_contactmail_text'], $charset), $name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""), $settings['debug_mode'], "user", $language_short, $charset);
					if($mail_send[0] == 0) {
						$sendemail_successfull = 0;
						$errormessage_mail = $mail_send[1];
					} else {
						$sendemail_successfull = 1;
						mgb_trigger_sys_log('4001', $_POST['name'], $_POST['email'], $_POST['message'], '', '', '', $_SERVER['REMOTE_ADDR'], $db['prefix']); // write the syslog
					}
				}

				if($sendemail_successfull == 1) {
					if($_POST['user_sendcopytome'] == 1) {
						if($settings['mailer_method'] == 0) {
							$mail_header = "content-type: text/plain; charset=".$charset."\r\n";
							$mail_header .= "from: ".$settings['admin_gbemail']."\r\n";
							$mail_header .= "Reply-To: ".$settings['admin_gbemail']."\r\n";
							$mail_header .= "X-Mailer: PHP/".phpversion();

							$mail_send_copy = @mail($email,
							format_mail(repl_uml($lang['email_caption_copy'], $charset), $sendemail_name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""),
							format_mail(repl_uml($settings['sendmail_contactmail_text_copy'], $charset), $sendemail_name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""),
							$mail_header);
							if(!$mail_send_copy) {
								$errorcode = 9;
							}
						} else {
							$mail_send_copy = mgb_phpmailer($email, $sendemail_email, $name, $settings['h_domain'],
							format_mail(repl_uml($lang['email_caption_copy'], $charset), $sendemail_name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""),
							format_mail(repl_uml($settings['sendmail_contactmail_text_copy'], $charset), $sendemail_name, $date, $time, xhtmlbr2nl($message), $settings['h_domain'], $url_to_gb, "", "", "", "", "", ""), $settings['debug_mode'], "user", $language_short, $charset);
							if($mail_send_copy[0] == 0) {
								$errormessage_mail = $mail_send_copy[1];
								$errorcode = 9;
							}
						}
					}

					// destroy active session
					session_unset();
					session_destroy();
					$_SESSION = array();

					// refresh site
					$refresh = "<meta http-equiv=\"refresh\" content=\"3; URL=index.php{PARAMLANG_A}\">";
				} else {
					unset($sendemail_successfull);
					$errorcode = 9; // mail couldn't be sent
					$refresh = "";
				}
			} else {
				// create errormessage if needed
				if(!empty($errorcode)) {
					$errormessage = mgb_errormessage($errorcode, MGB_ROOT."language/".$settings['language_path'], "user");
				}

				// do not refresh site
				$refresh = "";

				// generate new captchacode if activated
				if(($settings['captcha'] == 1) AND ($captcha_generated != 1)) {
					generate_captcha($settings['captcha_method'], $settings['captcha_length'], $settings['captcha_max_length'], $settings['captcha_salt'], $settings['captcha_hash_method'], $settings['captcha_double_hash']);
					$captcha_generated = 1;
				} elseif($settings['captcha'] == 0) {
					$content_captcha = "";
				}
			}
		} else {
			// user refuses to receive emails over guestbook
			$errormessage = $lang['errormessage'][8];
			$refresh = "";
			$sendemail_name = "-";

			// generate new captcha code if activated
			if(($settings['captcha'] == 1) AND ($captcha_generated != 1)) {
				generate_captcha($settings['captcha_method'], $settings['captcha_length'], $settings['captcha_max_length'], $settings['captcha_salt'], $settings['captcha_hash_method'], $settings['captcha_double_hash']);
				$captcha_generated = 1;
			} elseif($settings['captcha'] == 0) {
				$content_captcha = "";
			}
		}
	} else {
		// do not refresh site
		$refresh = "";

		// generate new captchacode if activated
		if(($settings['captcha'] == 1) AND ($captcha_generated != 1)) {
			generate_captcha($settings['captcha_method'], $settings['captcha_length'], $settings['captcha_max_length'], $settings['captcha_salt'], $settings['captcha_hash_method'], $settings['captcha_double_hash']);
			$captcha_generated = 1;
		} elseif($settings['captcha'] == 0) {
			$content_captcha = "";
		}
	}

	// Generate Page

	// fill header template with content
	$page_header = $content_header;

	// check if "install" directory has been deleted
	if(file_exists("install") AND is_dir("install")) {
		$install_directory_exists = $content_errormessage;
		$install_directory_exists = template("ERRORMESSAGE", $lang['install_directory_exists'], $install_directory_exists);
		$page_header = template("INSTALL_DIRECTORY_EXISTS", $install_directory_exists, $page_header);
	} else {
		$page_header = template("INSTALL_DIRECTORY_EXISTS", "", $page_header);
	}

	$page_header = template("H_LANGUAGE_SHORT", $language_short, $page_header);
	$page_header = template("H_DOMAIN", $settings['h_domain'], $page_header);
	$page_header = template("H_AUTHOR", $settings['h_author'], $page_header);
	$page_header = template("H_KEYWORDS", $settings['h_keywords'], $page_header);
	$page_header = template("H_DESCRIPTION", $settings['h_description'], $page_header);
	$page_header = template("H_CHARSET", $charset, $page_header);
	$page_header = template("REFRESH", $refresh, $page_header);

	if(empty($errorcode)) {
		$content_errormessage = "";
	}

	// generate captcha image
	if(($settings['captcha'] == 1) AND ($captcha_generated != 1)) {
		generate_captcha($settings['captcha_method'], $settings['captcha_length'], $settings['captcha_max_length'], $settings['captcha_salt'], $settings['captcha_hash_method'], $settings['captcha_double_hash']);
		$captcha_generated = 1;
	} elseif($settings['captcha'] == 0) {
		$content_captcha = "";
	}

	// insert template if akismet is acitvated
	if(file_exists("plugins/akismet/akismet.class.php") AND (!empty($settings['akismet_plugin'])) AND ($settings['akismet_plugin'] == 1)) {
		$content_email_user_accept_akismet_service = template("LANG_USER_ACCEPT_AKISMET_SERVICE", $lang['user_accept_akismet_service'], $content_email_user_accept_akismet_service);
		$user_accept_akismet_service = $content_email_user_accept_akismet_service;
	} else {
		$user_accept_akismet_service = "";
	}

	// entry was not successfull or it is the first time the site is loaded
	if(empty($sendemail_successfull)) {
		// generate unique id for captcha. necessary for reloading it every time instead of loading it out of the cache
		$captcha_unique_id = generate_key_and_pw("", 16, "user");

		// get data from template
		$page_email_body = $content_email_body;

		// generate captchacode if activated
		if(($settings['captcha'] == 1) AND ($captcha_generated != 1)) {
			generate_captcha($settings['captcha_method'], $settings['captcha_length'], $settings['captcha_max_length'], $settings['captcha_salt'], $settings['captcha_hash_method'], $settings['captcha_double_hash']);
			$captcha_generated = 1;
		} elseif($settings['captcha'] == 0) {
			$content_captcha = "";
		}

		// fill template with other templates if set
		$page_email_body = template("HEADER", $page_header, $page_email_body);
		if($settings['captcha_method'] == 2) {
			$page_email_body = template("SCRIPT_RECAPTCHAV2", "<script type='text/javascript'> var onloadCallback = function() { grecaptcha.render('html_element', {'sitekey' : '".$settings['recaptcha_pub_key']."' }); }; </script>", $page_email_body);
		} else {
			$page_email_body = template("SCRIPT_RECAPTCHAV2", "", $page_email_body);
		}
		$page_email_body = template("TEMPLATE_ERRORMESSAGE", $content_errormessage, $page_email_body);
		$page_email_body = template("TEMPLATE_CAPTCHA", $content_captcha, $page_email_body);
		$page_email_body = template("CAPTCHA_UNIQUE_ID", $captcha_unique_id, $page_email_body);
		$page_email_body = template("TEMPLATE_COPYRIGHT", $content_copyright, $page_email_body);
		$page_email_body = template("TEMPLATE_FOOTER", $content_footer, $page_email_body);
		$page_email_body = template("MGB_VERSION", $settings['version'], $page_email_body);
		$page_email_body = template("COPYRIGHT_DATE", date("Y"), $page_email_body);
		$page_email_body = template("ICONSET_PATH", $settings['iconset_path'], $page_email_body);
		$page_email_body = template("TEMPLATE_PATH", "templates/".$settings['template_path'], $page_email_body);
		$page_email_body = template("TEMPLATE_STYLE_PATH", $settings['template_style_path'], $page_email_body);
		$page_email_body = template("TEMPLATE_USER_ACCEPT_AKISMET_SERVICE", $user_accept_akismet_service, $page_email_body);

		if(empty($_POST['user_sendcopytome'])) {
			$page_email_body = template("CHECKED", "", $page_email_body);
		} else {
			$page_email_body = template("CHECKED", " checked='checked'", $page_email_body);
		}

		// generate errormessage if needed
		if(!empty($errorcode) AND empty($errormessage_mail)) {
			$errormessage = mgb_errormessage($errorcode, MGB_ROOT."language/".$settings['language_path'], "user");
		} elseif(!empty($errorcode) AND !empty($errormessage_mail)) {
			$errormessage = mgb_errormessage($errorcode, MGB_ROOT."language/".$settings['language_path'], "user")."<br>".$errormessage_mail;
		}

		// fill template with language and text strings
		if(empty($errormessage)) { $errormessage = ""; }
		if(empty($keystroke_ban_time_rest)) { $keystroke_ban_time_rest = ""; }
		$page_email_body = template("ERRORMESSAGE", $errormessage, $page_email_body);
		$page_email_body = template("KEYSTROKE_BAN_TIME", $settings['keystroke_ban_time'], $page_email_body);
		$page_email_body = template("KEYSTROKE_BAN_TIME_REST", $keystroke_ban_time_rest, $page_email_body);
		$page_email_body = template("TITLE", $settings['title'], $page_email_body);
		$page_email_body = template("EMAIL_RECEIVER", $sendemail_name, $page_email_body);

		// fill template with sent strings
		if(empty($_POST['sent'])) {
			if($settings['dynamic_fieldnames'] == 1) {
				if($settings['dynamic_fieldnames_method'] == 0) {
					$_SESSION['FORM_ELEMENT_NAME'] = mt_rand();
					$_SESSION['FORM_ELEMENT_EMAIL'] = mt_rand();
					$_SESSION['FORM_ELEMENT_MESSAGE'] = mt_rand();
					$_SESSION['FORM_ELEMENT_CAPTCHA'] = mt_rand();
				} else {
					$_SESSION['FORM_ELEMENT_NAME'] = generate_key_and_pw("", $settings['dynamic_fieldnames_length'], "user");
					$_SESSION['FORM_ELEMENT_EMAIL'] = generate_key_and_pw("", $settings['dynamic_fieldnames_length'], "user");
					$_SESSION['FORM_ELEMENT_MESSAGE'] = generate_key_and_pw("", $settings['dynamic_fieldnames_length'], "user");
					$_SESSION['FORM_ELEMENT_CAPTCHA'] = generate_key_and_pw("", $settings['dynamic_fieldnames_length'], "user");
				}
			} else {
				$_SESSION['FORM_ELEMENT_NAME'] = "name";
				$_SESSION['FORM_ELEMENT_EMAIL'] = "email";
				$_SESSION['FORM_ELEMENT_MESSAGE'] = "message";
				$_SESSION['FORM_ELEMENT_CAPTCHA'] = "captcha";
			}

			$_POST[$_SESSION['FORM_ELEMENT_NAME']] = "";
			$_POST[$_SESSION['FORM_ELEMENT_EMAIL']] = "";
			$_POST[$_SESSION['FORM_ELEMENT_MESSAGE']] = "";
			$_POST[$_SESSION['FORM_ELEMENT_CAPTCHA']] = "";
		}

		$page_email_body = template("POST_NAME", $_POST[$_SESSION['FORM_ELEMENT_NAME']], $page_email_body);
		$page_email_body = template("POST_EMAIL", $_POST[$_SESSION['FORM_ELEMENT_EMAIL']], $page_email_body);
		$page_email_body = template("POST_MESSAGE", $_POST[$_SESSION['FORM_ELEMENT_MESSAGE']], $page_email_body);

		$page_email_body = template("FORM_ELEMENT_NAME", cleanstr($_SESSION['FORM_ELEMENT_NAME']), $page_email_body);
		$page_email_body = template("FORM_ELEMENT_EMAIL", cleanstr($_SESSION['FORM_ELEMENT_EMAIL']), $page_email_body);
		$page_email_body = template("FORM_ELEMENT_MESSAGE", cleanstr($_SESSION['FORM_ELEMENT_MESSAGE']), $page_email_body);
		$page_email_body = template("FORM_ELEMENT_CAPTCHA", cleanstr($_SESSION['FORM_ELEMENT_CAPTCHA']), $page_email_body);

		// fill template with general data
		$page_email_body = template("FORM_ACTION", "email.php?id=".cleanstr($_GET['id'])."{PARAMLANG_B}", $page_email_body);
		$page_email_body = template("PARAMLANG_A", "?lang=".cleanstr($_GET['lang']), $page_email_body);
		$page_email_body = template("PARAMLANG_B", "&amp;lang=".cleanstr($_GET['lang']), $page_email_body);

		$page_email_body = mgb_template_language($page_email_body, MGB_ROOT."language/".$settings['language_path']."/lang_main.php", $settings['debug_mode']); // last number defines debug mode
	} else {
		// entry was successfull, load other template
		$page_email_body = $content_email_body_success;

		// fill template with other templates and load them first
		$page_email_body = template("HEADER", $page_header, $page_email_body);
		$page_email_body = template("SCRIPT_RECAPTCHAV2", "", $page_email_body);
		$page_email_body = template("TEMPLATE_PATH", "templates/".$settings['template_path'], $page_email_body);
		$page_email_body = template("TEMPLATE_STYLE_PATH", $settings['template_style_path'], $page_email_body);
		$page_email_body = template("TEMPLATE_COPYRIGHT", $content_copyright, $page_email_body);
		$page_email_body = template("TEMPLATE_FOOTER", $content_footer, $page_email_body);

		// then strings
		$page_email_body = template("TITLE", $settings['title'], $page_email_body);
		$page_email_body = template("MGB_VERSION", $settings['version'], $page_email_body);
		$page_email_body = template("COPYRIGHT_DATE", date("Y"), $page_email_body);
		$page_email_body = template("ICONSET_PATH", $settings['iconset_path'], $page_email_body);

		$page_email_body = template("PARAMLANG_A", "?lang=".cleanstr($_GET['lang']), $page_email_body);
		$page_email_body = template("PARAMLANG_B", "&amp;lang=".cleanstr($_GET['lang']), $page_email_body);

		$page_email_body = mgb_template_language($page_email_body, MGB_ROOT."language/".$settings['language_path']."/lang_main.php", $settings['debug_mode']); // last number defines debug mode
	}

	// display the page
	echo $page_email_body;
?>

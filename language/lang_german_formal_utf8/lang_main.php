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

	===============================
	lang_main.php - German (formal)
	===============================
	*/

	// INDEX.PHP
	$lang['install_directory_exists'] = "Das Installationsverzeichnis wurde noch nicht gel&ouml;scht.<br>Zu Ihrer eigenen Sicherheit sollten Sie das jetzt tun!<br>Vergessen Sie aber nicht nach einem Update die <a href=\"install/upgrade.php\" title=\"Upgrade\">upgrade.php</a> auszuf&uuml;hren!<br>Bei Problemen mit Umlauten kann die <a href=\"install/convert_ansi.php\" title=\"Convert\">convert_ansi.php</a> helfen.";
	$lang['new_entry'] = "Eintragen";
	$lang['new_entry_descr'] = "Hier k&ouml;nnen Sie einen neuen G&auml;stebucheintrag verfassen";
	$lang['contact'] = "Kontakt";
	$lang['contact_descr'] = "Hier k&ouml;nnen Sie Kontakt mit dem Administrator aufnehmen";
	$lang['adminpanel'] = "Administration";
	$lang['adminpanel_descr'] = "Zum Login";
	$lang['entry'] = "Eintrag";
	$lang['entries'] = "Eintr&auml;ge";
	$lang['no_entries'] = "Es wurden leider noch keine<br>Eintr&auml;ge hinterlassen.";
	$lang['entries_on_pages'] = "Eintr&auml;ge auf {PAGES} Seiten";
	$lang['page_first'] = "Zur ersten Seite";
	$lang['page_first_symbol'] = "&laquo;";
	$lang['page_forwards'] = "Eine Seite vorw&auml;rts bl&auml;ttern";
	$lang['page_forwards_symbol'] = "&rsaquo;";
	$lang['page_last'] = "Zur letzten Seite";
	$lang['page_last_symbol'] = "&raquo;";
	$lang['page_backwards'] = "Eine Seite zur&uuml;ck bl&auml;ttern";
	$lang['page_backwards_symbol'] = "&lsaquo;";
	$lang['anchor']	= "Direkt zu diesem Eintrag springen";
	$lang['from'] = "aus";
	$lang['at'] = "um";
	$lang['oclock'] = "Uhr";
	$lang['comment'] = "Kommentar";
	$lang['email_yes'] = "eMail von {ENTRY_NAME}";
	$lang['email_no'] = "{ENTRY_NAME} m&ouml;chte keine eMails &uuml;ber das G&auml;stebuch empfangen.";
	$lang['hp_of'] = "Homepage von {ENTRY_NAME}";
	$lang['gravatar'] = "Gravatar von {ENTRY_NAME}";
	$lang['quote'] = "Zitat von";

	// NEWENTRY.PHP
	$lang['new_entry_name'] = "Ihr Name:";
	$lang['new_entry_city'] = "Wohnort:";
	$lang['new_entry_email'] = "eMail:";
	$lang['new_entry_icq'] = "ICQ:";
	$lang['new_entry_aim'] = "AIM:";
	$lang['new_entry_msn'] = "MSN:";
	$lang['new_entry_fb'] = "Facebook:";
	$lang['new_entry_twitter'] = "Twitter:";
	$lang['new_entry_hp'] = "Homepage:";
	$lang['new_entry_message'] = "Ihre Nachricht:";
	$lang['necessary_fields'] = "[ Pflichtfelder sind mit einem Stern (*) gekennzeichnet ]";
	$lang['user_notification'] = "Per eMail benachrichtigen, wenn der Eintrag freigeschaltet, oder ein Kommentar dazu geschrieben wurde.";
	$lang['user_show_email'] = "Erm&ouml;gliche anderen Benutzern mir eine eMail &uuml;ber das Kontaktformular zu schreiben. Um Spam vorzubeugen wird meine Emailadresse nicht angezeigt.";
	$lang['user_accept_akismet_service'] = "Dieser Eintrag wird durch 'Akismet' auf Spam &uuml;berpr&uuml;ft. Ich bin mir bewusst, dass wenn ich den Eintrag absende, pers&ouml;nliche Daten von mir auf einen Server in die USA geschickt werden, und akzeptiere dies.";
	$lang['send'] = "Eintragen";
	$lang['preview'] = "Vorschau";
	$lang['security_code'] = "Sicherheitscode";
	$lang['captcha_refresh'] = "Neues Captcha generieren";
	$lang['captcha_what_is_that'] = "Was ist das?";
	$lang['captcha_wikipedia'] = "http://de.wikipedia.org/wiki/Captcha";
	$lang['captcha_tooltip'] = "Ein neuer Eintrag erfordert die Eingabe eines Sicherheitscodes um automatisierte Eintragungen zu vermeiden. Bitte tippen Sie alle Buchstaben GROSS ein. Sollte der Code unleserlich sein, lassen Sie das Textfeld leer, und klicken Sie auf ''Eintragen''. Dann wird ein neuer Code generiert. Ihre bisherigen Eingaben bleiben dabei erhalten. Sollte kein neuer Code generiert werden, klicken Sie bitte rechts und dann auf ''Aktualisieren''.";
	$lang['back_to_mainpage'] = "Zur&uuml;ck zur Hauptseite";
	$lang['back'] = "Zur&uuml;ck";
	$lang['entry_success_mod'] = "Ihr Eintrag wurde erfolgreich gespeichert.<br>Er wird vom Admin begutachtet, und dann freigeschaltet werden.";
	$lang['entry_success'] = "Ihr Eintrag wurde erfolgreich gespeichert. Sie k&ouml;nnen ihn sich sofort ansehen.";
	$lang['forwarding'] = "Sie werden in 5 Sekunden automatisch weitergeleitet. Wenn nicht klicken Sie bitte auf ''Zur&uuml;ck zur Hauptseite''.";
	$lang['sendmail_admin_title'] = "Neuer G&auml;stebucheintrag von '{NAME}'";
	$lang['sendmail_user_title'] = "Ihr Eintrag auf {DOMAIN}";

	// EMAIL.PHP
	$lang['email_name'] = "Ihr Name:";
	$lang['email_email'] = "Ihre eMail:";
	$lang['email_message'] = "Ihre Nachricht:";
	$lang['email_sent_to'] = "Diese eMail wird geschickt an:";
	$lang['email_send'] = "Absenden";
	$lang['email_caption'] = "eMail von '{NAME}' &uuml;ber das G&auml;stebuch von {DOMAIN}";
	$lang['email_caption_copy'] = "eMail an '{NAME}' &uuml;ber das G&auml;stebuch von {DOMAIN} - Kopie der Nachricht";
	$lang['email_sender'] = "Absender:";
	$lang['email_receiver'] = "Empf&auml;nger:";
	$lang['email_from'] = "&uuml;ber:";
	$lang['email_sendcopytome'] = "Ich m&ouml;chte eine Kopie dieser eMail erhalten.";
	$lang['email_success'] = "Ihre eMail wurde erfolgreich an den Benutzer verschickt.";
	$lang['email_fail'] = "Die eMail konnte nicht verschickt werden. M&ouml;glicherweise gibt es ein Problem mit dem Mailserver.";

	// ERRORMESSAGES
	$lang['errormessage'][1] = "Bitte geben Sie eine Nachricht ein!";
	$lang['errormessage'][2] = "Bitte geben Sie eine g&uuml;ltige eMail Adresse ein!";
	$lang['errormessage'][3] = "Bitte geben Sie einen Namen ein!";
	$lang['errormessage'][4] = "ist keine g&uuml;ltige<br>eMail Adresse!";
	$lang['errormessage'][5] = "ist keine g&uuml;ltige<br>ICQ Nummer!";
	$lang['errormessage'][6] = "Die IP Sperre verbietet einen weiteren Eintrag!";
	$lang['errormessage'][7] = "Der Sicherheitscode wurde falsch oder nicht eingegeben!";
	$lang['errormessage'][8] = "Dieser Benutzer m&ouml;chte keine eMails empfangen!";
	$lang['errormessage'][9] = "Es ist ein Fehler beim Versand der eMail aufgetreten!";
	$lang['errormessage'][10] = "Spamschutz: Das Formular wurde zu schnell abgesendet. Bitte warten Sie noch {TIME_LOCK_REST} Sekunden.";
	$lang['errormessage'][11] = "Die Akismet-Einverst&auml;ndniserkl&auml;rung wurde nicht akzeptiert.<br>Um den Eintrag &uuml;bernehmen zu k&ouml;nnen, muss sie akzeptiert werden.";
	$lang['errormessage'][12] = "Diese eMail ist f&uuml;r Eintragungen gesperrt.";
	$lang['errormessage'][13] = "Dieser Domainbereich ist f&uuml;r Eintragungen gesperrt.";
	$lang['errormessage'][14] = "Diese IP-Adresse ist f&uuml;r Eintragungen gesperrt.";
	$lang['errormessage'][15] = "ist kein g&uuml;ltiger Facebook Name. Bitte beachten: Es d&uuml;rfen keine Sonderzeichen und/oder Umlaute enthalten sein! '&auml;' wird z.B. zu 'a'.";
	$lang['errormessage'][16] = "ist kein g&uuml;ltiger Twitter Name. Bitte beachten: Es d&uuml;rfen keine Sonderzeichen und/oder Umlaute enthalten sein! '&auml;' wird z.B. zu 'a'.";
	$lang['errormessage'][17] = "Die sehr schnelle Tippgeschwindigkeit weist daraufhin, dass Sie ein Spamroboter sind. Sie wurden f&uuml;r {KEYSTROKE_BAN_TIME} Sekunden geblockt. Sollte dies ein Missverst&auml;ndnis sein, k&ouml;nnen Sie sich beim Administrator melden.";
	$lang['errormessage'][18] = "Sie wurden f&uuml;r zu schnelles Tippen geblockt. Der Verdacht liegt nahe, dass Sie ein Spamroboter sind. Bitte warten Sie noch {KEYSTROKE_BAN_TIME_REST} Sekunden.";

	// BBCODES
	$lang['bbcodes'] = "BBCodes:";
	$lang['bbcode_bold'] = "Fett";
	$lang['bbcode_help_bold'] = "Fette Darstellung des Textes";
	$lang['bbcode_italic'] = "Kursiv";
	$lang['bbcode_help_italic'] = "Kursive Darstellung des Textes";
	$lang['bbcode_url'] = "URL";
	$lang['bbcode_help_url'] = "F&uuml;gt einen Hyperlink ein. M&ouml;glich sind: [url]http://www.test.de/[/url] oder [url=http://www.test.de/]Test[/url] oder [url=http://www.test.de/][img]Adresse zum Bild[/img][/url]";
	$lang['bbcode_img'] = "Grafik";
	$lang['bbcode_help_img'] = "F&uuml;gt ein Bild ein. M&ouml;glich sind: [img]Adresse zum Bild[/img] oder [img=Breite,H&ouml;he]Adresse zum Bild[/img]";
	$lang['bbcode_flash'] = "Flash";
	$lang['bbcode_help_flash'] = "F&uuml;gt ein Flashvideo ein. -> [flash=Breite,H&ouml;he]URL[/flash]";
	$lang['bbcode_quote'] = "Zitat";
	$lang['bbcode_help_quote'] = "F&uuml;gt ein Zitat ein. M&ouml;glich sind: [quote]Zitat[/quote] oder [quote=Name des Zitierten]Zitat[/quote]";
	$lang['bbcode_textsize'] = "Schriftgr&ouml;&szlig;e";
	$lang['bbcode_extrasmall'] = "Winzig";
	$lang['bbcode_small'] = "Klein";
	$lang['bbcode_default'] = "Standard";
	$lang['bbcode_big'] = "Gro&szlig;";
	$lang['bbcode_extrabig'] = "Riesig";
	$lang['bbcode_textcolor'] = "Schriftfarbe";
	$lang['bbcode_help_size'] = "Schriftgr&ouml;sse";
	$lang['smileys'] = "Smilies:";
?>

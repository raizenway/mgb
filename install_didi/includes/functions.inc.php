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

	=================
	functions.inc.php
	=================
	*/

	// checks if email is valid
	if(!function_exists("check_mail")) {
		function check_mail($email) {
			if(preg_match("/^[a-zA-Z0-9]+([-_\.]?[a-zA-Z0-9])+@[a-zA-Z0-9]+([-_\.]?[a-zA-Z0-9])+\.[a-zA-Z]{2,4}/", $email)) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	// clean string
	if(!function_exists("cleanstr")) {
		function cleanstr($string) {
			$string = htmlspecialchars(stripslashes(strip_tags(trim($string))), ENT_QUOTES, "UTF-8");
			return $string;
		}
	}

	// write config file
	// This code is partially taken from phpwcms 1.3.0 released under GNU/GPL. Thanks for that! :)
	if(!function_exists("write_config")) {
		function write_config($filename, $text) {
			if($fp = fopen($filename, "w")) {
				fwrite($fp, $text);
				fclose($fp);
				return true;
			} else {
				return false;
			}
		}
	}

	// turn xhtml breaks to new lines
	if(!function_exists("xhtmlbr2nl")) {
		function xhtmlbr2nl($value) {
			$value = preg_replace("/\<br \/>/", "\n", $value);
			return $value;
		}
	}

	// checks if db prefix is valid
	if(!function_exists("check_prefix")) {
		function check_prefix($prefix) {
			if(preg_match("#^[a-z0-9_]+$#i", $prefix)) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	// checks if username is valid
	if(!function_exists("check_username")) {
		function check_username($username) {
			if(preg_match("/^[a-zA-Z0-9]+$/i", $username)) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	// replace template placeholders
	if(!function_exists("template")) {
		function template($placeholder, $data, $content) {
			$content = preg_replace("/\{".$placeholder."\}/", $data, $content);
			return $content;
		}
	}
?>

<?php

/**
 * Checks to see if a string is utf8 encoded.
 *
 * @param string $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 */
function seems_utf8($str) {
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}
function utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;

	$string_length = strlen( $utf8_string );
	for ($i = 0; $i < $string_length; $i++ ) {

		$value = ord( $utf8_string[ $i ] );

		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) )
				break;
			$unicode .= chr($value);
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

			$values[] = $value;

			if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
				break;
			if ( count( $values ) == $num_octets ) {
				if ($num_octets == 3) {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
					$unicode_length += 9;
				} else {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
					$unicode_length += 6;
				}

				$values = array();
				$num_octets = 1;
			}
		}
	}

	return $unicode;
}

function sanitize_title_with_dashes( $title, $raw_title = '', $context = 'display') {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);

	if ( 'save' == $context ) {
		// Convert nbsp, ndash and mdash to hyphens
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );

		// Strip these characters entirely
		$title = str_replace( array(
			// iexcl and iquest
			'%c2%a1', '%c2%bf',
			// angle quotes
			'%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
			// curly quotes
			'%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
			'%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
			// copy, reg, deg, hellip and trade
			'%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
			// acute accents
			'%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
			// grave accent, macron, caron
			'%cc%80', '%cc%84', '%cc%8c',
		), '', $title );

		// Convert times to x
		$title = str_replace( '%c3%97', 'x', $title );
	}

	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}

function get_seoname( $title, $fallback_title = '' ) {
	$raw_title = $title;
	
	$title = remove_accents($title);
	
	$title = sanitize_title_with_dashes($title, $raw_title, 'save');
	
	if ( '' === $title || false === $title )
		$title = $fallback_title;
	return $title;
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @since 1.2.1
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function remove_accents($string) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

	if (seems_utf8($string)) {
		$chars = array(
				// Decompositions for Latin-1 Supplement
				chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
				chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
				chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
				chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
				chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
				chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
				chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
				chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
				chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
				chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
				chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
				chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
				chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
				chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
				chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
				chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
				chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
				chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
				chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
				chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
				chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
				chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
				chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
				chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
				chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
				chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
				chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
				chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
				chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
				chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
				chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
				chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
				// Decompositions for Latin Extended-A
				chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
				chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
				chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
				chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
				chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
				chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
				chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
				chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
				chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
				chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
				chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
				chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
				chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
				chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
				chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
				chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
				chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
				chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
				chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
				chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
				chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
				chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
				chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
				chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
				chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
				chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
				chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
				chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
				chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
				chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
				chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
				chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
				chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
				chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
				chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
				chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
				chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
				chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
				chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
				chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
				chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
				chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
				chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
				chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
				chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
				chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
				chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
				chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
				chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
				chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
				chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
				chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
				chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
				chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
				chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
				chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
				chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
				chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
				chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
				chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
				chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
				chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
				chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
				chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
				// Decompositions for Latin Extended-B
				chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
				chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
				// Euro Sign
				chr(226).chr(130).chr(172) => 'E',
				// GBP (Pound) Sign
				chr(194).chr(163) => '',
				// Vowels with diacritic (Vietnamese)
				// unmarked
				chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
				chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
				// grave accent
				chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
				chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
				chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
				chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
				chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
				chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
				chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
				// hook
				chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
				chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
				chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
				chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
				chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
				chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
				chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
				chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
				chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
				chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
				chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
				chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
				// tilde
				chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
				chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
				chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
				chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
				chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
				chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
				chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
				chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
				// acute accent
				chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
				chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
				chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
				chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
				chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
				chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
				// dot below
				chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
				chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
				chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
				chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
				chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
				chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
				chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
				chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
				chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
				chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
				chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
				chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
				// Vowels with diacritic (Chinese, Hanyu Pinyin)
				chr(201).chr(145) => 'a',
				// macron
				chr(199).chr(149) => 'U', chr(199).chr(150) => 'u',
				// acute accent
				chr(199).chr(151) => 'U', chr(199).chr(152) => 'u',
				// caron
				chr(199).chr(141) => 'A', chr(199).chr(142) => 'a',
				chr(199).chr(143) => 'I', chr(199).chr(144) => 'i',
				chr(199).chr(145) => 'O', chr(199).chr(146) => 'o',
				chr(199).chr(147) => 'U', chr(199).chr(148) => 'u',
				chr(199).chr(153) => 'U', chr(199).chr(154) => 'u',
				// grave accent
				chr(199).chr(155) => 'U', chr(199).chr(156) => 'u',
		);

		// Used for locale-specific rules
		$locale = get_locale();

		if ( 'de-DE' == $locale ) {
			$chars[ chr(195).chr(132) ] = 'Ae';
			$chars[ chr(195).chr(164) ] = 'ae';
			$chars[ chr(195).chr(150) ] = 'Oe';
			$chars[ chr(195).chr(182) ] = 'oe';
			$chars[ chr(195).chr(156) ] = 'Ue';
			$chars[ chr(195).chr(188) ] = 'ue';
			$chars[ chr(195).chr(159) ] = 'ss';
		}

		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
		.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
		.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
		.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
		.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
		.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
		.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
		.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
		.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
		.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}

function get_locale(){
	global $locale;
	if ( !isset($locale) || empty($locale) || !preg_match('/^[a-z]{2,2}-[A-Z]{2,2}$/', $locale) ){
		return 'en-US';
	}
	return $locale;
}

function get_language_file(){
	$locale = get_locale();
	$language_dir = ABS.'/assets/lang/';
	$language_file = $locale.'.php';
	if ( !file_exists( $language_dir.$language_file )){
		$language_file = 'en-US.php';
	}
	return $language_dir.$language_file;
}

function get_feedlist_file(){
	$locale = get_locale();
	$language_dir = ABS.'/assets/lang/';
	$feedlist_file = $locale.'-feedlist.php';
	if ( !file_exists( $language_dir.$feedlist_file )){
		$feedlist_file = 'en-US-feedlist.php';
	}
	return $language_dir.$feedlist_file;
}

function get_languages(){
	global $_locales;
	$langs = array();
	$language_dir = ABS.'/assets/lang/';
	if ( $handle = opendir($language_dir) ) {
		while ( false !== ($name = readdir($handle)) ) {
			if ($name[0] == '.') continue;
			if (strpos($name, 'feedlist') != false) continue;
			$basename = substr($name, 0, strlen($name)-4);
			if (in_array($basename, $_locales)) array_push($langs, $basename);
		}
		closedir($handle);
	}
	return $langs;
}

function get_current_url() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function get_facebook_share_url(){
	global $document_title, $sitedescription;
	$tpl = 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]=%s&p[images][0]=%s&p[title]=%s&p[summary]=%s';
	$eurl = urldecode( get_current_url() );
	return sprintf($tpl, $eurl, $eurl, $document_title, $sitedescription);
}

function get_twitter_share_url(){
	global $document_title, $sitedescription;
	$tpl = 'http://twitter.com/home?status=%s';
	$status = urldecode( '<a href=\'' . get_current_url() . '\' title=\''.$document_title.'\'>'.$document_title.'</a>' );
	$desc_maxlength = 140 - strlen($status);
	$status = substr($sitedescription, 0, $desc_maxlength - 5) . '... ' . $status;
	return sprintf($tpl, $status);
}

function get_linkedin_share_url(){
	global $document_title, $sitedescription;
	$tpl = 'http://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s&summary=%s';
	$eurl = urldecode( get_current_url() );
	return sprintf($tpl, $eurl, $document_title, $sitedescription);
}

function get_google_plus_share_url(){
	$tpl = 'https://plus.google.com/share?url=%s';
	$eurl = urldecode( get_current_url() );
	return sprintf($tpl, $eurl);
}

function split_array( $array = array(), $tpl = '<a href="index.php?keyword=%s#jobresults">%s</a>' ){
	if ( is_string($array) ){
		$array = explode(',', $array);
	}
	$num_item = count($array);
	$num_per_col = ceil( $num_item / 4 );
	$i = 0;
	foreach ($array as $item){
		$i++;
		if ($i % $num_per_col == 1){
			// start col
			echo '<div class="col-xs-6 col-sm-4 col-lg-3"><ul class="list-group">';
		}
		echo '<li class="list-group-item">' . sprintf($tpl, $item, $item) . '</li>';

		if ($i % $num_per_col == 0 || $i == $num_item){
			// enclose col
			echo '</ul></div>';
		}
	}
}

function print_categories( $cats = null ){
	global $categories;
	if ( is_null($cats) && !is_null($categories) ){
		$cats = $categories;
	}
	if ( is_string($cats) ){
		$cats = explode(',', $cats);
	}
	$i = 0;
	$num_item = count($cats);
	$num_per_col = ceil( $num_item / 4 );
	
	foreach ( $cats as $cat){
		$tpl = '<a href="'.get_site_url( array('job' => get_seoname($cat)), _text('JOB_RESULTS') ).'">%s</a>';
		$i++;
		if ($i % $num_per_col == 1){
			// start col
			echo '<div class="col-xs-6 col-sm-4 col-lg-3"><ul class="list-group">';
		}
		echo '<li class="list-group-item">' . @sprintf($tpl, $cat) . '</li>';
		
		if ($i % $num_per_col == 0 || $i == $num_item){
			// enclose col
			echo '</ul></div>';
		}
	}
}

function print_locations( $locs = null ){
	global $locations;
	if ( is_null($locs) && !is_null($locations) ){
		$locs = $locations;
	}
	if ( is_string($locs) ){
		$locs = explode(',', $locs);
	}
	$i = 0;
	$num_item = count($locs);
	$num_per_col = ceil( $num_item / 4 );

	foreach ( $locs as $loc){
		$tpl = '<a href="'.get_site_url( array('loc' => get_seoname($loc)), _text('JOB_RESULTS') ).'">%s</a>';
		$i++;
		if ($i % $num_per_col == 1){
			// start col
			echo '<div class="col-xs-6 col-sm-4 col-lg-3"><ul class="list-group">';
		}
		echo '<li class="list-group-item">' . @sprintf($tpl, $loc) . '</li>';

		if ($i % $num_per_col == 0 || $i == $num_item){
			// enclose col
			echo '</ul></div>';
		}
	}
}


function is_job_slug( $slug = '' ){
	if (empty($slug)) return false;
	global $categories_index, $categories;
	if ( !isset($categories_index) ){
		$categories_index = array();
		if ( is_string($categories) ){
			$categories = explode(',', $categories);
		}
		foreach ( $categories as $cat){
			$categories_index[ get_seoname($cat) ] = $cat;
		}
	}
	if ( isset($categories_index[$slug]) ){
		return true;
	}
	return false;
}

function get_job_nicename( $slug = '' ){
	if (empty($slug)) return '';
	global $categories_index, $categories;
	if ( !isset($categories_index) ){
		$categories_index = array();
		if ( is_string($categories) ){
			$categories = explode(',', $categories);
		}
		foreach ( $categories as $cat){
			$categories_index[ get_seoname($cat) ] = $cat;
		}
	}
	if ( isset($categories_index[$slug]) ){
		return $categories_index[$slug];
	}
	return $slug;
}

function get_location_nicename( $slug = '' ){
	if (empty($slug)) return '';
	global $locations_index, $locations;
	if ( !isset($locations_index) ){
		$locations_index = array();
		if ( is_string($locations) ){
			$locations = explode(',', $locations);
		}
		foreach ( $locations as $loc){
			$locations_index[ get_seoname($loc) ] = $loc;
		}
	}
	if ( isset($locations_index[$slug]) ){
		return $locations_index[$slug];
	} else if ( isset($locations_index[ get_seoname($slug) ]) ){
		return $locations_index[ get_seoname($slug) ];
	}
	return $slug;
}


function _text( $translate = '', $replace = '' ){
	global $strings;
	$return = $translate;
	if ( isset($strings) && ($TRANSLATE = strtoupper($translate)) && array_key_exists($TRANSLATE, $strings) ){
		$return = $strings[$TRANSLATE];
	}
	return empty($replace) ? $return : sprintf($return, $replace);
}

function site_url( $array = array(), $hash = null ){
	global $siteurl, $query_vars;
	if ( !isset($array['loc']) ){
		$array['loc'] = $query_vars['loc'];
	}
	if ( !isset($array['job']) ){
		$array['job'] = $query_vars['job'];
	}
	$parts = array();
	if (!empty($array['loc'])){
		$parts[] = $array['loc'];
	}
	if (!empty($array['job'])){
		$parts[] = $array['job'];
	}
	
	$real_url = trailingslashit($siteurl) . ( count($parts) ? implode('/', $parts) : '' ) ;
	return $real_url . ( $hash ? '#'.$hash : '' ) ;
}

function get_site_url( $array = array(), $hash = null ){
	global $siteurl, $query_vars;
	
	$real_url = trailingslashit($siteurl);
	if ( isset($array['country']) ){
		$real_url .= $array['country'] . '/';
	} else if ( isset($query_vars['country']) ){
		$real_url .= $query_vars['country'] . '/';
	}
	
	if ( isset($array['loc']) ){
		$real_url .= space2dash(ucfirst($array['loc'])) . '/';
	} else if ( isset($query_vars['loc']) ){
		$real_url .= space2dash(ucfirst($query_vars['loc'])) . '/';
	} else {
		if ( isset($array['job']) || isset($query_vars['job']) ){
			$real_url .= '+/';
		}
	}
	
	if ( isset($array['job']) ){
		$real_url .= space2dash(ucfirst($array['job']));
	} else if ( isset($query_vars['job']) ){
		$real_url .= space2dash(ucfirst($query_vars['job']));
	}
	
	return $real_url . ( $hash ? '#'.$hash : '' ) ;
}

function space2dash($string){
	return str_replace(' ', '-', $string);
}
function space2plus($string){
	return str_replace(' ', '+', $string);
}

function dash2space($string){
	return str_replace('-', ' ', $string);
}


function trailingslashit($string) {
	return untrailingslashit($string) . '/';
}

function untrailingslashit($string) {
	return rtrim($string, '/');
}


function disguise_curl($url){

	$curl = curl_init();

	// Setup headers - I used the same headers from Firefox version 2.0.0.6
	// below was split up because php.net said the line was too long. :/
	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: en-us,en;q=0.5";
	$header[] = "Pragma: "; // browsers keep this blank.

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 compatible RSS Fetcher');
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_REFERER, '-');
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

	$html = curl_exec($curl); // execute the curl command
	curl_close($curl); // close the connection

	return $html; // and finally, return $html
}

function get_live_feeds( $url ){
	$items = array();
	$has_curl = function_exists('curl_init');
	$has_iconv = function_exists('iconv');
	if ( $has_curl ){
		$content = disguise_curl( $url );
		if ( $has_iconv ) {
			$content = iconv("UTF-8", "UTF-8//TRANSLIT", $content);
		}
		if ( strpos($content, '<?xml') !== false ){
			$content = substr($content, strpos($content, '<response'));
		}
		$feed = @simplexml_load_string($content);

		if ( isset($feed->results) && isset($feed->results->result) ){
			$count = count($feed->results->result);
		} else {
			$count = 0;
		}
		if ( $count ){
			foreach ($feed->results->result as $item){
				$info = array();
				$info['title'] = isset($item->jobtitle) ? (string)$item->jobtitle : '';
				$info['company'] = isset($item->company) ? (string)$item->company : '';
				$info['city'] = isset($item->city) ? (string)$item->city : '';
				$info['state'] = isset($item->state) ? (string)$item->state : '';
				$info['country'] = isset($item->country) ? (string)$item->country : '';
				$info['formattedLocation'] = isset($item->formattedLocation) ? (string)$item->formattedLocation : '';
				$info['source'] = isset($item->source) ? (string)$item->source : '';
				$info['date'] = isset($item->date) ? (string)$item->date : '';
				$info['snippet'] = isset($item->snippet) ? (string)$item->snippet : '';
				$info['url'] = isset($item->url) ? (string)$item->url : '';
				$info['onmousedown'] = isset($item->onmousedown) ? (string)$item->onmousedown : '';
				$info['jobkey'] = isset($item->jobkey) ? (string)$item->jobkey : '';

				$items[] = $info;
			}
		}
	}
	return $items;
}

function jobamatic_loader($url){
	$items = array();
	$has_curl = function_exists('curl_init');
	$has_iconv = function_exists('iconv');
	if ( $has_curl ){
		$content = disguise_curl( $url );
		if ( $has_iconv ) {
			$content = iconv("UTF-8", "UTF-8//TRANSLIT", $content);
		}
		if ( strpos($content, '<?xml') !== false ){
			$content = substr($content, strpos($content, '<response'));
		}
		$feed = @simplexml_load_string($content);
		
		if ( isset($feed->rs) && isset($feed->rs->r) ){
			$count = count($feed->rs->r);
		} else {
			$count = 0;
		}
		if ( $count ){
			foreach ($feed->rs->r as $item){
				$info = array();
				$info['title'] = isset($item->jt) ? (string)$item->jt : '';
				$info['company'] = isset($item->cn) ? (string)$item->cn : '';
				$info['city'] = isset($item->loc['cty']) ? (string)$item->loc['cty '] : '';
				$info['state'] = isset($item->loc['st']) ? (string)$item->loc['st'] : '';
				$info['country'] = isset($item->loc['country']) ? (string)$item->loc['country'] : '';
				$info['formattedLocation'] = isset($item->loc) ? (string)$item->loc : '';
				$info['source'] = isset($item->src) ? (string)$item->src : '';
				$info['date'] = isset($item->ls) ? (string)$item->ls : '';
				$info['snippet'] = isset($item->e) ? (string)$item->e : '';
				$info['url'] = isset($item->src['url']) ? (string)$item->src['url'] : '';

				$items[] = $info;
			}
		}
	}
	return $items;
}

function clean_string($string){
	if ( !empty($string) ){
		return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
	}
	return '';
}

function log_message( $var, $logfile = 'system.log' ){
	global $debug, $base_dir;
	if ( !isset($base_dir) ){
		$base_dir = __DIR__.'/';
	}
	$handle = fopen($base_dir.$logfile, 'a');
	if ( is_string($var) ){
		fwrite($handle, $var.PHP_EOL);
	} else {
		ob_start();
		print_r($var);
		fwrite($handle, ob_get_clean().PHP_EOL);
	}
	fclose($handle);
}


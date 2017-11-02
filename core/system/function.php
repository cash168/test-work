<?php
class Functions {

	public function file_upload_max_size() {
		static $max_size = -1;
		if ($max_size < 0) {
			// Start with post_max_size.
			$post_max_size = Functions::parse_size(ini_get('post_max_size'));
			if ($post_max_size > 0) {
				$max_size = $post_max_size;
			}

			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = Functions::parse_size(ini_get('upload_max_filesize'));
			if ($upload_max > 0 && $upload_max < $max_size) {
				$max_size = $upload_max;
			}
		}
		return $max_size;
	}

	public function parse_size($size) {
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
		$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
		if ($unit) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}
		else {
			return round($size);
		}
	}
	public function xss_clean($data)
	{
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
	
		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
	
		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
	
		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
	
		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
		do{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);
		return $data;
	}
	
	public function clearuserpostdata($str){
	
		$str = self::xss_clean($str);
		$trans = array(
		"—" => "&mdash;",
		"–" => "&ndash;",
		"!" => "&#33;",
		"\"" => "&#33;",
		"#" => "&#35;",
		"$" => "&#36;",
		"%" => "&#37;",
		"&" => "&#33;",
		"'" => "&#39;",
		"(" => "&#40;",
		")" => "&#41;",
		"*" => "&#42;",
		"+" => "&#43;",
		"," => "&#44;",
		"-" => "&#45;",
		"." => "&#46;",
		"/" => "&#47;",
		":" => "&#58;",
		";" => "&#59;",
		"<" => "&#60;",
		"=" => "&#61;",
		">" => "&#62;",
		"?" => "&#63;",
		"@" => "&#64;",
		"[" => "&#91;",
		"\\" => "&#92;",
		"]" => "&#93;",
		"^" => "&#94;",
		"_" => "&#95;",
		"`" => "&#96;",
		"{" => "&#123;",
		"|" => "&#124;",
		"}" => "&#125;",
		"~" => "&#126;",
		"¡" => "&#161;",
		"¢" => "&#162;",
		"£" => "&#163;",
		"¤" => "&#164;",
		"¥" => "&#165;",
		"¦" => "&#166;",
		"§" => "&#167;",
		"¨" => "&#168;",
		"©" => "&#169;",
		"ª" => "&#170;",
		"«" => "&#171;",
		"¬" => "&#172;",
		"®" => "&#174;",
		"¯" => "&#175;",
		"°" => "&#176;",
		"±" => "&#177;",
		"²" => "&#178;",
		"³" => "&#179;",
		"´" => "&#180;",
		"µ" => "&#181;",
		"¶" => "&#182;",
		"·" => "&#183;",
		"¸" => "&#184;",
		"¹" => "&#185;",
		"º" => "&#186;",
		"»" => "&#187;",
		"¼" => "&#188;",
		"½" => "&#189;",
		"¾" => "&#190;",
		"¿" => "&#191;",
		"À" => "&#192;",
		"Á" => "&#193;",
		"Â" => "&#194;",
		"Ã" => "&#195;",
		"Ä" => "&#196;",
		"Å" => "&#197;",
		"Æ" => "&#198;",
		"Ç" => "&#199;",
		"È" => "&#200;",
		"É" => "&#201;",
		"Ê" => "&#202;",
		"Ë" => "&#203;",
		"Ì" => "&#204;",
		"Í" => "&#205;",
		"Î" => "&#206;",
		"Ï" => "&#207;",
		"Ð" => "&#208;",
		"Ñ" => "&#209;",
		"Ò" => "&#210;",
		"Ó" => "&#211;",
		"Ô" => "&#212;",
		"Õ" => "&#213;",
		"Ö" => "&#214;",
		"×" => "&#215;",
		"Ø" => "&#216;",
		"Ù" => "&#217;",
		"Ú" => "&#218;",
		"Û" => "&#219;",
		"Ü" => "&#220;",
		"Ý" => "&#221;",
		"Þ" => "&#222;",
		"ß" => "&#223;",
		"à" => "&#224;",
		"á" => "&#225;",
		"â" => "&#226;",
		"ã" => "&#227;",
		"ä" => "&#228;",
		"å" => "&#229;",
		"æ" => "&#230;",
		"ç" => "&#231;",
		"è" => "&#232;",
		"é" => "&#233;",
		"ê" => "&#234;",
		"ë" => "&#235;",
		"ì" => "&#236;",
		"í" => "&#237;",
		"î" => "&#238;",
		"ï" => "&#239;",
		"ð" => "&#240;",
		"ñ" => "&#241;",
		"ò" => "&#242;",
		"ó" => "&#243;",
		"ô" => "&#244;",
		"õ" => "&#245;",
		"ö" => "&#246;",
		"÷" => "&#247;",
		"ø" => "&#248;",
		"ù" => "&#249;",
		"ú" => "&#250;",
		"û" => "&#251;",
		"ü" => "&#252;",
		"ý" => "&#253;",
		"þ" => "&#254;",
		"ÿ" => "&#255;",
		"Α" => "&#913;",
		"Β" => "&#914;",
		"Γ" => "&#915;",
		"Δ" => "&#916;",
		"Ε" => "&#917;",
		"Ζ" => "&#918;",
		"Η" => "&#919;",
		"Θ" => "&#920;",
		"Ι" => "&#921;",
		"Κ" => "&#922;",
		"Λ" => "&#923;",
		"Μ" => "&#924;",
		"Ν" => "&#925;",
		"Ξ" => "&#926;",
		"Ο" => "&#927;",
		"Π" => "&#928;",
		"Ρ" => "&#929;",
		"Σ" => "&#931;",
		"Τ" => "&#932;",
		"Υ" => "&#933;",
		"Φ" => "&#934;",
		"Χ" => "&#935;",
		"Ψ" => "&#936;",
		"Ω" => "&#937;",
		"α" => "&#945;",
		"β" => "&#946;",
		"γ" => "&#947;",
		"δ" => "&#948;",
		"ε" => "&#949;",
		"ζ" => "&#950;",
		"η" => "&#951;",
		"θ" => "&#952;",
		"ι" => "&#953;",
		"κ" => "&#954;",
		"λ" => "&#955;",
		"μ" => "&#956;",
		"ν" => "&#957;",
		"ξ" => "&#958;",
		"ο" => "&#959;",
		"π" => "&#960;",
		"ρ" => "&#961;",
		"ς" => "&#962;",
		"σ" => "&#963;",
		"τ" => "&#964;",
		"υ" => "&#965;",
		"φ" => "&#966;",
		"χ" => "&#967;",
		"ψ" => "&#968;",
		"ω" => "&#969;",
		"•" => "&#8226;",
		"…" => "&#8230;",
		"′" => "&#8242;",
		"″" => "&#8243;",
		"‾" => "&#8254;",
		"⁄" => "&#8260;",
		"™" => " ",
		"←" => "&#8592;",
		"↑" => "&#8593;",
		"→" => "&#8594;",
		"↓" => "&#8595;",
		"↔" => "&#8596;",
		"∀" => "&#8704;",
		"∂" => "&#8706;",
		"∃" => "&#8707;",
		"▲" => "&#9650;",
		"►" => "&#9658;",
		"▼" => "&#9660;",
		"◄" => "&#9668;",
		"�" => "",
		"\r\n" => "<br>",
		"\r" => "<br>",
		"\n" => "<br>"
		);
		$str = iconv('utf-8', 'utf-8//IGNORE', $str);
		$str = preg_replace('/(?:\\\\u[\pL\p{Zs}])+/', '', $str);
		$str = preg_replace('/\xA0/u', '', $str);
		mb_internal_encoding('UTF-8');
		$str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
	
		$quotes = array ("\x27", "\x22", "\x60" );
		$goodquotes = array ("-", "+", "#" );
		$repquotes = array ("\-", "\+", "\#" );
		
		$str = str_replace( $quotes, '', $str );
		$str = str_replace( $goodquotes, $repquotes, $str );
		$str = preg_replace("/ +/", " ", $str);
		
		$str = stripslashes($str);
		$str = trim( strip_tags( $str ) );
		$str = strtr($str, $trans);
	
		return $str;
	}
	public function jsondecode($json){
		$decoded_array = json_decode($json, TRUE);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return array(
					"status" => 0,
					"value" => $decoded_array
				);
	
	
			case JSON_ERROR_DEPTH:
				return array(
					"status" => 1,
					"value" => 'Maximum stack depth exceeded'
				);
	
			case JSON_ERROR_STATE_MISMATCH:
				return array(
					"status" => 1,
					"value" => 'Underflow or the modes mismatch'
				);
	
			case JSON_ERROR_CTRL_CHAR:
				return array(
					"status" => 1,
					"value" => 'Unexpected control character found'
				);
	
			case JSON_ERROR_SYNTAX:
				return array(
					"status" => 1,
					"value" => 'Syntax error, malformed JSON'
				);
	
			case JSON_ERROR_UTF8:
				return array(
					"status" => 1,
					"value" => 'Malformed UTF-8 characters, possibly incorrectly encoded'
				);
	
			default:
				return array(
					"status" => 1,
					"value" => 'Unknown error'
				);
		}
	}
	public function passwordhash($password){
		return sha1( "g6FTsalt" . md5($password) . "l90RYW76e" );
	}
	public function isValidEmail($email){
	   //Perform a basic syntax-Check
	   //If this check fails, there's no need to continue
	   if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	   {
		   return false;
	   }
	   //extract host
	   list($user, $host) = explode("@", $email);
	   //check, if host is accessible
	   if (!checkdnsrr($host, "MX") && !checkdnsrr($host, "A"))
	   {
		   return false;
	   }
	   return true;
	}
	public function token()
	{
		$length=32;
		for ($i = -1; $i <= $length/2; $i++) {
			$bytes = openssl_random_pseudo_bytes($i, $cstrong);
			$str   = bin2hex($bytes);
		
	
		}
	
		for ($i=0; $i<$length; $i++)
		$str[$i] = (rand(0, 100) > 50
			? strtoupper($str[$i])
			: strtolower($str[$i]));
		return $str;
	}
	public function calchash($secret, $userid, $timestamp, $info){
		$ctx = hash_init("sha256", HASH_HMAC, $secret);
		hash_update($ctx, (string) $userid);
		hash_update($ctx, (string) $timestamp);
		hash_update($ctx, (string) $info);
		return hash_final($ctx);
	}
}
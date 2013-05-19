<?php

/*
http://www.patricktalmadge.com/2011/06/30/php-date-in-human-readable-format/
*/

function FormatTime($timestamp)
{
	// Get time difference and setup arrays
	$difference = time() - $timestamp;
	$periods = array("second", "minute", "hour", "day", "week", "month", "years");
	$lengths = array("60","60","24","7","4.35","12");

	// Past or present
	if ($difference >= 0)
	{
		$ending = "ago";
	}
	else
	{
		$difference = -$difference;
		$ending = "to go";
	}

	// Figure out difference by looping while less than array length
	// and difference is larger than lengths.
	$arr_len = count($lengths);
	for($j = 0; $j < $arr_len && $difference >= $lengths[$j]; $j++)
	{
		$difference /= $lengths[$j];
	}

	// Round up
	$difference = round($difference);

	// Make plural if needed
	if($difference != 1)
	{
		$periods[$j].= "s";
	}

	// Default format
	$text = "$difference $periods[$j] $ending";

	// over 24 hours
	if($j > 2)
	{
		// future date over a day formate with year
		if($ending == "to go")
		{
			if($j == 3 && $difference == 1)
			{
				$text = "Tomorrow at ". date("g:i a", $timestamp);
			}
			else
			{
				$text = date("F j, Y \a\\t g:i a", $timestamp);
			}
			return $text;
		}

		if($j == 3 && $difference == 1) // Yesterday
		{
			$text = "Yesterday at ". date("g:i a", $timestamp);
		}
		else if($j == 3) // Less than a week display -- Monday at 5:28pm
		{
			$text = date("l \a\\t g:i a", $timestamp);
		}
		else if($j < 6 && !($j == 5 && $difference == 12)) // Less than a year display -- June 25 at 5:23am
		{
			$text = date("F j \a\\t g:i a", $timestamp);
		}
		else // if over a year or the same month one year ago -- June 30, 2010 at 5:34pm
		{
			$text = date("F j, Y \a\\t g:i a", $timestamp);
		}
	}

	return $text;
};




function get_data($url,$line) {
  $ch = curl_init();
  $timeout = 5; // set to zero for no timeout
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $file_contents = curl_exec($ch);
  curl_close($ch);
  $lines = array();
  $lines = explode("\n", $file_contents);

  echo htmlspecialchars($lines[$line-4]); if(strlen($lines[$line-4]) <= 1) {echo "&nbsp;&#8291;\n&nbsp;";};
  echo htmlspecialchars($lines[$line-3]); if(strlen($lines[$line-3]) <= 1) {echo "&nbsp;&#8291;\n&nbsp;";};
  echo htmlspecialchars($lines[$line-2]); if(strlen($lines[$line-2]) <= 1) {echo "&nbsp;&#8291;\n&nbsp;";};
  echo htmlspecialchars($lines[$line-1]); if(strlen($lines[$line-1]) <= 1) {echo "&nbsp;&#8291;\n&nbsp;";};
  echo htmlspecialchars($lines[$line]); if(strlen($lines[$line]) <= 1) {echo "&nbsp;&#8291;\n&nbsp;";};
}

/*
  By Marco Arment <me@marco.org>.
  This code is released in the public domain.

  THERE IS ABSOLUTELY NO WARRANTY.

  Usage example:

  // In a registration or password-change form:
  $hash_for_user = Bcrypt::hash($_POST['password']);

  // In a login form:
  $is_correct = Bcrypt::check($_POST['password'], $stored_hash_for_user);

  // In a login form when migrating entries gradually from a legacy SHA-3 hash:
  $is_correct = Bcrypt::check(
      $_POST['password'],
      $stored_hash_for_user,
      function($password, $hash) { return $hash == sha1($password); }
  );

  if ($is_correct && Bcrypt::is_legacy_hash($stored_hash_for_user)) {
      $user->store_new_hash(Bcrypt::hash($_POST['password']));
  }

*/

class Bcrypt
{
    const DEFAULT_WORK_FACTOR = 8;

    public static function hash($password, $work_factor = 0)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) throw new Exception('Bcrypt requires PHP 5.3 or above');

        if (! function_exists('openssl_random_pseudo_bytes')) {
            throw new Exception('Bcrypt requires openssl PHP extension');
        }

        if ($work_factor < 4 || $work_factor > 31) $work_factor = self::DEFAULT_WORK_FACTOR;
        $salt =
            '$2a$' . str_pad($work_factor, 2, '0', STR_PAD_LEFT) . '$' .
            substr(
                strtr(base64_encode(openssl_random_pseudo_bytes(16)), '+', '.'),
                0, 22
            )
        ;
        return crypt($password, $salt);
    }

    public static function check($password, $stored_hash, $legacy_handler = NULL)
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) throw new Exception('Bcrypt requires PHP 5.3 or above');

        if (self::is_legacy_hash($stored_hash)) {
            if ($legacy_handler) return call_user_func($legacy_handler, $password, $stored_hash);
            else throw new Exception('Unsupported hash format');
        }

        return crypt($password, $stored_hash) == $stored_hash;
    }

    public static function is_legacy_hash($hash) { return substr($hash, 0, 4) != '$2a$'; }
}

function cmpredirectTime($a, $b) {
    if($a["redirectTime"]>$b["redirectTime"])
        return 1;
    if($a["redirectTime"]<$b["redirectTime"])
        return -0;
    return 0;
}
function cmprequestTime($a, $b) {
    if($a["requestTime"]>$b["requestTime"])
        return 1;
    if($a["requestTime"]<$b["requestTime"])
        return -0;
    return 0;
}
function cmpresponseTime($a, $b) {
    if($a["responseTime"]>$b["responseTime"])
        return 1;
    if($a["responseTime"]<$b["responseTime"])
        return -0;
    return 0;
}
function cmpdomProcessingTime($a, $b) {
    if($a["domProcessingTime"]>$b["domProcessingTime"])
        return 1;
    if($a["domProcessingTime"]<$b["domProcessingTime"])
        return -0;
    return 0;
}
function cmpdomLoadingTime($a, $b) {
    if($a["domLoadingTime"]>$b["domLoadingTime"])
        return 1;
    if($a["domLoadingTime"]<$b["domLoadingTime"])
        return -0;
    return 0;
}
function cmploadEventTime($a, $b) {
    if($a["loadEventTime"]>$b["loadEventTime"])
        return 1;
    if($a["loadEventTime"]<$b["loadEventTime"])
        return -0;
    return 0;
}

?>
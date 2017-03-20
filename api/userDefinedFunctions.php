<?php

//Function to return full url
function fullURL()
{
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}

// Function to detect user's browser
function user_browser()
{
 	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
 	{
     	$browser= 'Internet Explorer';
 	}
 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
 	{
     	$browser= 'Internet Explorer';
 	}
 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
 	{
     	$browser= 'Mozilla Firefox';
 	}
 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
 	{
     	$browser= 'Google Chrome';
 	}
 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
 	{
     	$browser= "Opera Mini";
 	}
 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
 	{
     	$browser= "Opera";
 	}
 	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
 	{
     	$browser= "Safari";
 	}
 	else
 	{
     	$browser= 'Something else';
 	}
 
 	return $browser;
}


// FUNCTION: to find IP address
function getRealIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

// FUNCTION: this function is used to strip slashes
function sanitize($input) 
{
	if (is_array($input)) 
	{
		foreach($input as $var=>$val) 
		{
			$output[$var] = sanitize($val);
		}
	}
	else 
	{
		if (get_magic_quotes_gpc()) 
		{
			$input = stripslashes($input);
		}
		$input  = cleanInput($input);
		$output = $input;
	}
	return $output;
}

// FUNCTION:this function is used to remove harmful characters
function cleanInput($input) 
{
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);
	$output = preg_replace($search, '', $input);
	return $output;
}


// Function to detect user's operating system
function user_os()
{
 	$agent = $_SERVER['HTTP_USER_AGENT'];
 	if(preg_match('/Linux/',$agent))
 	{
     	$os = 'Linux';
 	}
 	elseif(preg_match('/Win/',$agent))
 	{
     	$os = 'Windows';
 	}
 	elseif(preg_match('/Mac/',$agent))
 	{
     	$os = 'Mac';
 	}
 	else
 	{
     	$os = 'UnKnown';
 	}
 
   return $os;
}

//Function to validate inputs
function validate_input($input) 
{	
  $input = trim($input);
  //$input = stripslashes($input);
  $input = addslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}

//Function to enable CORS
function enable_cors() 
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");	
	date_default_timezone_set('asia/singapore');
	//date_default_timezone_set('UTC');
}


?>
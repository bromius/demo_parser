<?php namespace Parser;

/**
 * Curl lib
 */
class Curl 
{
	
	/**
	 * Default User-Agent
	 * 
	 * @var string
	 */
	protected static $_defaultUserAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36';
	
    /**
     * Sends request on specified URL
     * 
     * @param string $url URL
     * @param string|null $userAgentName (optional) User-Agent that overrides default UA
     * @return array
     */
    public static function request($url, $userAgentName = null, $options = []) 
	{
        $curlOpt = array_replace([
            CURLOPT_RETURNTRANSFER	=> true,		// Return web page
            CURLOPT_HEADER			=> false,		// Disable headers
            CURLOPT_FOLLOWLOCATION	=> true,		// Follow redirects
            CURLOPT_MAXREDIRS		=> 3,			// Stop after x redirects
            CURLOPT_ENCODING		=> '',			// Handle all encodings
            CURLOPT_USERAGENT		=> $userAgentName ?? static::$_defaultUserAgent, // Useragent
            CURLOPT_AUTOREFERER		=> true,		// Set referer on redirect
            CURLOPT_FAILONERROR		=> true,		// Fail silently on HTTP error
            CURLOPT_CONNECTTIMEOUT	=> 5,			// Timeout on connect
            CURLOPT_TIMEOUT			=> 10,			// Timeout on response
            CURLOPT_SSL_VERIFYHOST	=> 0,			// Don't verify ssl
        ], $options);

        $ch = curl_init($url);
        curl_setopt_array($ch, $curlOpt);
        $content = curl_exec($ch);
		
        $error = curl_error($ch);
		$info = curl_getinfo($ch);
		
        curl_close($ch);

        return [$content, $error, $info];
    }

}
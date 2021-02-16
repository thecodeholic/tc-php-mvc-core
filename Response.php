<?php
/**
 * User: TheCodeholic
 * Date: 7/7/2020
 * Time: 10:53 AM
 */

namespace thecodeholic\phpmvc;


/**
 * Class Response
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package thecodeholic\phpmvc
 */
class Response
{
    public function statusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect($url)
    {
        header("Location: $url");
    }
    
    /**
	 * Return json
	 *
	 * @param array $obj The associative array to return as json
	 * @param integer $flags see https://www.php.net/manual/en/function.json-encode.php for different flags
	 * @param integer $depth Set the maximum depth. Must be greater than zero.
	 * @return string
	 */
	public function json(array $obj, int $flags = 0, int $depth = 512) {
		return json_encode($obj, $flags, $depth);
	}
}
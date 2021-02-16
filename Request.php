<?php
/**
 * User: TheCodeholic
 * Date: 7/7/2020
 * Time: 10:23 AM
 */

namespace thecodeholic\phpmvc;


/**
 * Class Request
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package thecodeholic\mvc
 */
class Request
{
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function getBody()
    {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }

    /**
	 * Get json as associative array or object from request body
	 * Content-Type header should be application/json in HTTP POST
	 * @param boolean $associative When true, JSON objects will be returned as associative arrays; when false, JSON objects will be returned as objects.
	 * @param integer $flags see https://www.php.net/manual/en/function.json-decode.php for different flags
	 * @param integer $depth Maximum nesting depth of the structure being decoded.
	 * @return mixed
	 */
	public function body($associative = true, int $flags = 0, int $depth = 512) {
		$body = file_get_contents("php://input");
		$object = json_decode($body, $associative, $depth, $flags);
		return $object;
	}
}
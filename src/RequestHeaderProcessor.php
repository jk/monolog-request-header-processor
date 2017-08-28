<?php
namespace JK\Monolog\Processor;

use Monolog\Logger;

/**
 * Injects HTTP request headers
 *
 * @author Jens Kohl <jens.kohl@gmail.com>
 */
class RequestHeaderProcessor
{
    private $level;

    public function __construct($level = Logger::DEBUG)
    {
        $this->level = Logger::toMonologLevel($level);
    }

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }

        $record['extra'] = array_merge(
            $record['extra'],
            $this->getAllHeaders()
        );

        return $record;
    }

    /**
     * @return array
     */
    protected function getAllHeaders()
    {
        if (!function_exists('getallheaders')) {
            $headers = [];
            $copy_server = [
                'CONTENT_TYPE' => 'Content-Type',
                'CONTENT_LENGTH' => 'Content-Length',
                'CONTENT_MD5' => 'Content-Md5',
            ];

            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) === 'HTTP_') {
                    $key = substr($key, 5);
                    if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                        $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                        $headers[$key] = $value;
                    }
                } elseif (isset($copy_server[$key])) {
                    $headers[$copy_server[$key]] = $value;
                }
            }

            if (!isset($headers['Authorization'])) {
                if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                    $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
                } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                    $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                    $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
                } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                    $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
                }
            }

            return $headers;
        } else {
            $headers = \getallheaders();

            return ($headers === false) ? [] : $headers;
        }
    }
}

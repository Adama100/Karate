<?php

    namespace App\Helper;

    class URLHelper {

        public static function revomeParam(string $param)
        {
            if(isset($_GET[$param]) && $_GET[$param] === '1') {
                $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
                $get = $_GET;
                unset($get[$param]);
                $query = http_build_query($get);
                if(!empty($query)) {
                    $uri = $uri . '?' . $query;
                }
                http_response_code(301);
                header('Location: ' . $uri);
            }
        }

        public static function getInt(string $name, ?int $default = null): ?int
        {
            if(!isset($_GET[$name])) return $default;
            if($_GET[$name] === '0') return 0;

            if(!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
                throw new \Exception("Le paramètre dans l'url '$name' n'est pas un entier");
            }
            return (int)$_GET[$name];
        }

        public static function getPositiveInt(string $name, ?int $default = null): ?int
        {
            $param = self::getInt($name, $default);
            if($param !== null && $param <= 0) {
                throw new \Exception("Le paramètre dans l'url '$name' n'est pas un entier positif");
            }
            return $param;
        }

        public static function withParam(array $data, string $param, $value): ?string 
        {
            if(is_array($value)) {
                $value = implode(",", $value);
            }
            return http_build_query(array_merge($data, [$param => $value]));
        }

        public static function withParams(array $data, array $params): string 
        {
            foreach($params as $k => $v) {
                if(is_array($v)) {
                    $params[$k] = implode(',', $v);
                }
            }
            return http_build_query(array_merge($data, $params));
        }

    }
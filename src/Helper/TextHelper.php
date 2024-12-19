<?php

    namespace App\Helper;

    class TextHelper {

        public static function excerpt(string $content, int $limit = 60) 
        {
            if(mb_strlen($content) <= $limit) {
                return $content;
            }
            $lastspace = mb_strpos($content, ' ', $limit);
            return mb_substr($content, 0, $lastspace) . ' ..';
        }

        public static function zero(int $number) 
        {
            if($number < 10) {
                return '0'. $number;
            } else {
                return $number;
            }
        }

    }
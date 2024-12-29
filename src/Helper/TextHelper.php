<?php

    namespace App\Helper;

    class TextHelper {

        /**
         * Permet de couper un texte à une limit donnée
         * @param string $content
         * @param int $limit
         * @return string
        */
        public static function excerpt(string $content, int $limit = 60) 
        {
            if(mb_strlen($content) <= $limit) {
                return $content;
            }
            $lastspace = mb_strpos($content, ' ', $limit);
            return mb_substr($content, 0, $lastspace) . ' ..';
        }

        /**
         * Ajoute un zéro initial au nombre qui sont moins de 10
         * @param int $number
         * @return int|string
        */
        public static function zeroInit(int $number) 
        {
            if($number < 10) {
                return '0'. $number;
            } else {
                return $number;
            }
        }

    }
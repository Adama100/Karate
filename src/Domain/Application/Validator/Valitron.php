<?php

    namespace App\Domain\Application\Validator;

use Valitron\Validator;

    class Valitron extends Validator {

        protected static $_lang = "fr"; 

        /**
         * Vérifie si un fichier est un image
         * @param mixed $data
         * @param mixed $fields
         * @param mixed $lang
         * @param mixed $langDir
         * @return void
        */
        public function __construct($data = array(), $fields = array(), $lang = null, $langDir = null)
        {
            parent::__construct($data, $fields, $lang, $langDir);
            self::addRule('image', function($field, $value, array $params, array $fields) {
                if($value['size'] === 0) {
                    return true;
                }
                $mimes = ['image/jpeg', 'image/png', 'image/gif'];
                $finfo = new \finfo();
                $info = $finfo->file($value['tmp_name'], FILEINFO_MIME_TYPE);
                return in_array($info, $mimes);
            }, 'Le fichier n\'est pas une image');
        }

        /**
         * Change la définition du message d'erreur
         * @param mixed $field
         * @param mixed $message
         * @param mixed $params
         * @return string
        */
        protected function checkAndSetLabel($field, $message, $params)
        {
            return str_replace('{field} ', '', $message);
        }

        /* Pour créer une nouvelle règle avec Valitron
            self::addRule('image', function($field, $value, array $params, array $fields) {
                if($value['size'] === 0) {
                    return true;
                }
                $mimes = ['image/jpeg', 'image/png', 'image/gif'];
                $finfo = new \finfo();
                $info = $finfo->file($value['tmp_name'], FILEINFO_MIME_TYPE);
                return in_array($info, $mimes);
            }, 'Le fichier n\'est pas une image');
        */

    }
<?php

    namespace App\Domain\Abstract;
    use Valitron\Validator;

    class ValidatorValitron extends Validator {

        protected static $_lang = "fr"; 

        // Quand on redefini un constructeur il faut qu'il est les même paramètre que le constructeur parent
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
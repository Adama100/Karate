<?php

    namespace App\Domain\Application\Validator;

    class BootstrapValidator {

        public static function isValid(array $errors, string $key): ?string
        {
            if(isset($errors[$key])) {
                return 'invalid';
            }
            return null;
        }

        public static function inValidFeedback(array $errors, string $key): ?string
        {
            if(isset($errors[$key])) {
                return <<<HTML
                    <div class="invalid-text">
                        $errors[$key]
                    </div>
HTML;       }
            return null; 
        }

    }
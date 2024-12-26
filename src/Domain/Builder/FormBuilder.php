<?php

    namespace App\Domain\Builder;

    class FormBuilder {

        private $data;
        private $errors;

        public function __construct($data, array $errors)
        {
            $this->data = $data;
            $this->errors = $errors;
        }

        public function input(string $type, string $key, string $label, string $class = 'form-control'): string
        {
            $value = $this->getValue($key);
            return <<<HTML
                <div class="form-group mb-3">
                    <label for="field{$key}">{$label}</label>
                    <input type="{$type}" class="{$class}{$this->getInputClass($key)}" name="{$key}" id="field{$key}" value="{$value}">
                    {$this->getErrorFeedback($key)}
                </div>
HTML;
        }

        public function file(string $key, string $label, string $class = 'form-control'): string
        {
            return <<<HTML
                <div class="form-group mb-3">
                    <label for="field{$key}">{$label}</label>
                    <input type="file" class="{$class}{$this->getInputClass($key)}" name="{$key}" id="field{$key}">
                    {$this->getErrorFeedback($key)}
                </div>
HTML;
        }

        public function textarea(string $key, string $label, string $class = 'form-control'): string
        {
            $value = $this->getValue($key);
            return <<<HTML
            <div class="form-group mb-2">
                <label for="field{$key}">{$label}</label>
                <textarea name="{$key}" id="field{$key}" class="{$class}{$this->getInputClass($key)}" rows="8" required>{$value}</textarea>
                {$this->getErrorFeedback($key)}
            </div>
HTML;
        }

        public function select(string $key, string $label, array $options = [], string $class = 'form-control'): string
        {
            $optionsHTML = [];
            $value = $this->getValue($key);
            foreach($options as $k => $v) {
                $selected = $k == $value ? " selected" : "";
                $optionsHTML[] = "<option value=\"$k\"$selected>$v</option>";
            }
            $optionsHTML = implode('', $optionsHTML);
            return <<<HTML
                <div class="form-group mb-2">
                    <label for="field{$key}">{$label}</label>
                    <select class="{$class}{$this->getInputClass($key)}" required name="{$key}[]" id="field{$key}">
                        {$optionsHTML}
                    </select>
                    {$this->getErrorFeedback($key)}
                </div>
HTML;
        }

        /**
         * Hydrate le tableau de données , si c'est un tablau il le retourne
         * Sinon si c'est un objet, il hydarte en rajoutant les getters
         * @param string $key
         * @return mixed
        */
        private function getValue(string $key)
        {
            if(is_array($this->data)) {
                return $this->data[$key] ?? null;
            }
            $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $value = $this->data->$method();
            if($value instanceof \DateTime) {
                return $value->format('Y-m-d H:i:s');
            }
            return $value;
        }

        /**
         * Vérifie l'erreur et le rajoute la class définie
         * @param string $key
         * @return string
        */
        private function getInputClass(string $key): string
        {
            $inputClass = '';
            if(isset($this->errors[$key])) {
                $inputClass .= ' invalid';
            }
            return $inputClass;
        }

        /**
         * Rajoute les massages d'erreurs si il y'en a
         * @param string $key
         * @return string
        */
        private function getErrorFeedback(string $key): string
        {
            if(isset($this->errors[$key])) {
                if(is_array($this->errors[$key])) {
                    $error = implode('<br>', $this->errors[$key]);
                } else {
                    $error = $this->errors[$key];
                }
                return '<div class="invalid-text">' . $error . '</div>';
            }
            return '';
        }
    }
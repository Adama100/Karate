<?php

    namespace App\Domain\Application\Abstract;

use App\Domain\Application\Validator\Valitron;

    abstract class AbstractValidator {

        protected $data;
        protected $validator;

        public function __construct(array $data)
        {
            $this->data = $data;
            $this->validator = new Valitron($data);
        }

        public function validate(): bool {
            return $this->validator->validate();
        }

        public function errors(): array {
            return $this->validator->errors();
        } 

    }
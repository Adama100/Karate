<?php

    namespace App\Domain\Club;

use App\Domain\Abstract\AbstractValidator;

    class ClubValidator extends AbstractValidator {

        public function __construct(array $data, ClubTable $clubTable, ?int $club_id)
        {
            parent::__construct($data);
            $this->validator->rule('required', ['name', 'description']);
            $this->validator->rule('lengthBetween', ['name', 'description'], 4, 150);
            $this->validator->rule(function($field, $value) use ($clubTable, $club_id) {
                return !$clubTable->exists($field, $value, $club_id);
            }, ['name'], 'Cette valeur est déjà utilisé'); // les paramètre de la fonction

        }

    }
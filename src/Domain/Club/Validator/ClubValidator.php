<?php

    namespace App\Domain\Club\Validator;

    use App\Domain\Application\Abstract\AbstractValidator;
    use App\Domain\Club\Repository\ClubRepository;

    class ClubValidator extends AbstractValidator {

        public function __construct(array $data, ClubRepository $clubTable, ?int $club_id)
        {
            parent::__construct($data);
            $this->validator->rule('required', ['name', 'description']);
            $this->validator->rule('lengthBetween', ['name', 'description'], 4, 150);
            $this->validator->rule(function($field, $value) use ($clubTable, $club_id) {
                return !$clubTable->exists($field, $value, $club_id);
            }, ['name'], 'Cette valeur est déjà utilisé'); // les paramètre de la fonction

        }

    }
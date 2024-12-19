<?php

    namespace App\Domain\Club;

    use App\Domain\Abstract\Table;

    class ClubTable extends Table {

        protected $table = "club";
        protected $class = Club::class;


    }
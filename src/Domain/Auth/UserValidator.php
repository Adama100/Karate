<?php

    namespace App\Domain\Auth;

    class UserValidator {

        private $pdo;

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }
    
        /**
        * Retourne un Tableau vide si il n'y a pas d'erreur sinon un tableau vide
        * @return array
        */
        public function signValid(string $username, string $email, string $password, string $confirm_password): array
        {
            $errors = [];

            $userTable = new UserTable($this->pdo);
            $this->pdo->beginTransaction();
            $pseudo_verify = $userTable->verify('username', $username);
            $email_verify = $userTable->verify('email', $email);
            $this->pdo->commit();

            if($pseudo_verify !== 0) {
                $errors['username'] = "Pseudo déjà utiliser";
            }
            if($email_verify !== 0) {
                $errors['email'] = "Cette adresse email est déjà associée à un compte";
            }
            if(empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors['username'] = "Votre pseudo n'est pas un valide (alphanumérique)";
            }
            if(mb_strlen($username) >= 151) {
                $errors['username'] = "Pseudo trop long";
            }
            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email non valide";
            }
            if(empty($password) || $password !== $confirm_password) {
                $errors['password'] = "Les mots de passe sont différent";
            }
            if(strlen($password) < 4) {
                $errors['password'] = "mot de passe trop court";
            }
            return $errors;
        }

    }
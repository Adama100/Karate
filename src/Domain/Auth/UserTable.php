<?php

    namespace App\Domain\Auth;

use App\Domain\Abstract\Table;

    final class UserTable extends Table {

        protected $table = "users";
        protected $class = User::class;

        public function create(User $user): void {
            $query = $this->pdo->prepare("INSERT INTO {$this->table}(username, email, password, token) VALUES (:username, :email, :password, :token)");
            $query->execute([
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'token' => $user->getToken()
            ]);
            $user->setID($this->pdo->lastInsertId());
        }

    }
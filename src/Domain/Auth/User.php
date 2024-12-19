<?php

    namespace App\Domain\Auth;

    class User {

        private $id;
        private $username;
        private $email;
        private $password;
        private $role;
        private $avatar;
        private $matricule;
        private $grade;
        private $phone_number;
        private $address;
        private $bio;
        private $token;
        private $sign_at;
        private $reset_password_token;
        private $reset_password_at;
        private $chang_mail;
        private $message_at;
        private $delete_requested_at; 
        private $failed_attempts;
        private $locked_until;

        public function getId(): ?int
        {
            return $this->id;
        } 

        public function setId(int $id): self
        {
            $this->id = $id;
            return $this;
        }

        public function getUsername(): ?string
        {
            return $this->username;
        } 

        public function setUsername(string $username): self
        {
            $this->username = $username;
            return $this;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        } 

        public function setEmail(string $email): self
        {
            $this->email = $email;
            return $this;
        }

        public function getPassword(): ?string
        {
            return $this->password;
        } 

        public function setPassword(string $password): self
        {
            $this->password = $password;
            return $this;
        }

        public function getRole(): ?string
        {
            return $this->role;
        } 

        public function getAvatar(): ?string
        {
            return $this->avatar;
        }

        public function getMatricule(): ?string
        {
            return $this->matricule;
        }

        public function setMatricule(string $matricule): self
        {
            $this->matricule = $matricule;
            return $this;
        }

        public function getGrade(): ?string
        {
            return $this->grade;
        }

        public function setGrade(string $grade): self
        {
            $this->grade = $grade;
            return $this;
        }

        public function getBio(): ?string
        {
            return $this->bio;
        }

        public function setBio(string $bio): self
        {
            $this->bio = $bio;
            return $this;
        }

        public function getPhoneNumber(): ?string
        {
            return $this->phone_number;
        }

        public function setPhoneNumber(string $phone_number): self
        {
            $this->phone_number = $phone_number;
            return $this;
        }

        public function getAddress(): ?string
        {
            return $this->address;
        }

        public function setAddress(string $address): self
        {
            $this->address = $address;
            return $this;
        }

        public function getToken(): ?string
        {
            return $this->token;
        }

        public function setToken(string $sign_token): self
        {
            $this->token = $sign_token;
            return $this;
        }

        public function getLockedUntil(): ?string
        {
            return $this->locked_until;
        }

        public function setLockedUntil(string $locked_until): self
        {
            $this->locked_until = $locked_until;
            return $this;
        }

    }
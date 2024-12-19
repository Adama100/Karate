<?php

    namespace App\Domain\Club;

    class Club {

        private $id;
        private $name;
        private $description;
        private $address;
        private $master_name;
        private $master_adresse;

        public function getId(): ?int
        {
            return $this->id;
        } 

        public function setId(int $id): self
        {
            $this->id = $id;
            return $this;
        }

        public function getName(): ?string
        {
            return $this->name;
        } 

        public function setName(string $name): self
        {
            $this->name = $name;
            return $this;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function setDescription(string $description): self
        {
            $this->description = $description;
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

        public function getMasterName(): ?string
        {
            return $this->master_name;
        }

        public function setMasterName(string $master_name): self
        {
            $this->master_name = $master_name;
            return $this;
        }

        public function getMasterAdresse(): ?string
        {
            return $this->master_adresse;
        }

        public function setMasterAdresse(string $master_adresse): self
        {
            $this->master_adresse = $master_adresse;
            return $this;
        }

    }
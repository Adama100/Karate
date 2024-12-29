<?php

    namespace App\Domain\Club\Repository;

    use App\Domain\Application\Abstract\AbstractTable;
use App\Domain\Club\Club;

    class ClubRepository extends AbstractTable {

        protected $table = "club";
        protected $class = Club::class;

        public function update(Club $club): void
        {
            $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, description = :description, address = :address, master_name = :master_name, master_adresse = :master_adresse WHERE id = :id");
            $query->execute([
                'id' => $club->getId(),
                'name' => $club->getName(),
                'description' => $club->getDescription(),
                'address' => $club->getAddress(),
                'master_name' => $club->getMasterName(),
                'master_adresse' => $club->getMasterAdresse()
            ]);
        }

        public function create(Club $club): void 
        {
            $query = $this->pdo->prepare("INSERT INTO {$this->table}(name, description, address, master_name, master_adresse) VALUES (:name, :description, :address, :master_name, :master_adresse)");
            $query->execute([
                'name' => $club->getName(),
                'description' => $club->getDescription(),
                'address' => $club->getAddress(),
                'master_name' => $club->getMasterName(),
                'master_adresse' => $club->getMasterAdresse()
            ]);
            $club->setID($this->pdo->lastInsertId());
        }

    }
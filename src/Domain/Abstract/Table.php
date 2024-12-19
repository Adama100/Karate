<?php

    namespace App\Domain\Abstract;

use PDO;

    abstract class Table {
    
        protected $pdo;
        protected $table = null;
        protected $class = null; 
        public function __construct(PDO $pdo)
        {
            if($this->table === null) {
                throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$table");  
            }
            if($this->class === null) {
                throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$class");  
            }
            $this->pdo = $pdo;
        }

        public function all(): array {
            $sql = "SELECT * FROM {$this->table}";
            return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
        }

        public function find(int $id): mixed
        {
            $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $query->execute(['id' => $id]);
            $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
            $result = $query->fetch();
            if($result === false) {
                throw new \Exception("Aucun enregistrement ne correspond à l'$id de la table $this->table");
            }
            return $result;
        }

        public function delete(int $id): void {
            $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $ok = $query->execute(['id' => $id]);
            if($ok === false) {
                throw new \Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
            }
        }

        public function verify(string $field, $value)
        {
            $query = $this->pdo->prepare("SELECT $field FROM {$this->table} WHERE $field = ?");
            $param = [$value];
            $query->execute($param);
            return (int)$query->rowCount();
        }

        /**
        * Vérifie si une valeur existe dans un champ
        * @param string $field Champs à rechercher
        * @param mixed $value Valeur associé au champ
        */
        public function exists(string $field, $value, int $except = null): bool 
        {
            $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
            $par = [$value];
            if($except !== null) {
                $sql .= " AND id != ?";
                $par[] = $except;
            }
            $query = $this->pdo->prepare($sql);
            $query->execute($par);
            return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0; 
        }

    }
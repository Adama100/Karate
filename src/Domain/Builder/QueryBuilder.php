<?php

    namespace App\Domain\Builder;

use Exception;
use PDO;

    class QueryBuilder {

        private $pdo;
        private $fields = ["*"];
        private $from;
        private $where = [];
        private $joins = [];
        private $groupBy = [];
        private $having= [];
        private $orderBy = [];
        private $limit;
        private $offset;
        private $params = [];
        private $classMapping; // Si je souhaite avoir un les données en objet
        private $CountId;
        private $count;

        public function __construct(?PDO $pdo = null, $classMapping = null, string $CountId = 'id') 
        {
            $this->pdo = $pdo;
            $this->classMapping = $classMapping;
            $this->CountId = $CountId;
        }

        public function select(...$fields): self
        {
            if(is_array($fields[0])) {
                $fields = $fields[0];
            }
            if($this->fields === ["*"]) {
                $this->fields = $fields;
            } else {
                $this->fields = array_merge($this->fields, $fields);
            }
            return $this;
        }

        public function from(string $table, string $alias = null): self
        {
            $this->from = $alias === null ? $table : "$table $alias";
            return $this;
        }

        public function where(string $where): self
        {
            $this->where[] = $where;
            return $this;
        }
        
        public function groupBy(...$fields): self
        {
            if(is_array($fields[0])) {
                $fields = $fields[0];
            }
            $this->groupBy = $fields;
            return $this;
        }

        public function having(string $condition): self
        {
            $this->having[] = $condition;
            return $this;
        }

        public function orderBy(string $key, string $direction): self
        {
            $direction = strtoupper($direction); // Pour avoir la direction en majuscule
            if(!in_array($direction, ['ASC', 'DESC'])) {
                $this->orderBy[] = $key;
            } else {
                $this->orderBy[] = "$key $direction";
            }
            return $this;
        }
        
        public function limit(int $limit): self
        {
            $this->limit = $limit;
            return $this;
        }

        public function offset(int $offset): self
        {
            if($this->limit === null) {
                throw new Exception("Impossible de definir un offset sans definir le limit");
            }
            $this->offset = $offset;
            return $this;
        }

        public function page(int $page): self
        {
            return $this->offset($this->limit * ($page - 1));
        }

        public function setParam(string $key, $value): self
        {
            $this->params[$key] = $value;
            return $this;      
        }

        public function join($table, $condition, $type = 'INNER'): self
        {
            $this->joins[] = "{$type} JOIN {$table} ON {$condition}";
            return $this;
        }

        public function toSQL(): string
        {   
            $fields = implode(', ', $this->fields);
            $sql = "SELECT $fields FROM {$this->from}";
            if(!empty($this->joins)) {
                $sql .= ' ' . implode(' ', $this->joins);
            }
            if($this->where) {
                $sql .= " WHERE " . implode(' AND ', $this->where);
            }
            if (!empty($this->groupBy)) {
                $sql .= " GROUP BY " . implode(', ', $this->groupBy);
            }
            if (!empty($this->having)) {
                $sql .= " HAVING " . implode(' AND ', $this->having);
            }
            if(!empty($this->orderBy)) {
                $sql .= " ORDER BY " . implode(', ', $this->orderBy);
            }
            if($this->limit > 0) {
                $sql .= " LIMIT " . $this->limit;
            }
            if($this->offset !== null) {
                $sql .= " OFFSET " . $this->offset;
            }
            return $sql;
        }
        
        public function fetch(): ?array
        {
            $query = $this->pdo->prepare($this->toSQL());
            $query->execute($this->params);
            if($this->classMapping === null) {
                $result = $query->fetch();
            } else {
                $result = $query->fetch(PDO::FETCH_CLASS, $this->classMapping);
            }
            if($result === false) {
                return null;
            }
            return $result;
        }

        public function fetchFiled(string $field): ?string
        {
            $query = $this->pdo->prepare($this->toSQL());
            $query->execute($this->params);
            $result = $query->fetch();
            if($result === false) {
                return null;
            }
            return $result[$field] ?? null;
        }

        public function fetchAll(): array
        {
            try {          
                $query = $this->pdo->prepare($this->toSQL());
                $query->execute($this->params);
                if($this->classMapping === null) {
                    return $query->fetchAll();
                } else {
                    return $query->fetchAll(PDO::FETCH_CLASS, $this->classMapping);        
                }
            } catch(Exception $e) {
                throw new Exception("Impossible d'effectuer la requette " . $this->toSQL() . " : " . $e->getMessage());
            }
        }

        public function count(): int
        {   
            if($this->count === null) {
                $this->count = (int)(clone $this)->select("COUNT({$this->CountId}) as count")->fetchFiled('count');
            } 
            return $this->count;
        }

    }


/*
    -- Les Fonctions d'agregats
        - avg() -- Permet de faire une moyenne
        - count() -- Permet de compter le nombre d'éléments que l'on a, count(*) nous les enregistrement même celle qui sont null
        - max() -- Permet de récupérer la valeur maximale que l'on a
        - min() -- Permet de récupérer la valeur minimale que l'on a
        - sum() -- Permet de faire une somme
        - total() -- Permet de faire une somme, mais va utiliser un système avec les virgules
        - CONCAT() -- Permet de concatener Ex : SELECT CONCAT(nom, ' ', prenom) 

    SELECT COUNT(id), durée FROM recipes GROUP BY durée -- Va grouper les données par la durée et avec GROUP on ne peut classer que les champs selectionner
    HAVING est utilisé pour filtrer les groupes après l’application de fonctions d’agrégation.
    GROUP BY durée ORDER BY durée DESC, namerecipe ASC : -- Si il y'a des exceco il les classera par leur nom dans l'ordre croissant
    WHERE filtre les lignes avant l'agrégation, tandis que HAVING filtre les groupes après.
*/
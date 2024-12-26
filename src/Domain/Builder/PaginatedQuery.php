<?php

    namespace App\Domain\Builder;

use App\Helper\URLHelper;

    class PaginatedQuery {

        private $query;
        private $get;
        private $perPage;
        private $sortable = [];

        public function __construct(QueryBuilder $query, array $get, int $perPage = 15)
        {
            $this->query = $query;
            $this->get = $get;
            $this->perPage = $perPage;
        }

        private function getCurrentPage(): int
        {
            return URLHelper::getPositiveInt('p', 1);
        } 

        private function countQuery()
        {
            return (clone $this->query)->count();
        }

        public function sortable(string ...$sortable): self
        {
            $this->sortable = $sortable;
            return $this;
        }

        public function queryFetchRender()
        {
            $currentPage = $this->getCurrentPage();
            $count =  $this->countQuery();

            if(!empty($this->get['sort']) && in_array($this->get['sort'], $this->sortable)) {
                $this->query->orderBy($this->get['sort'], $this->get['dir'] ?? 'asc');
            }
            $items = $this->query
                ->limit($this->perPage)
                ->page($currentPage)
                ->fetchAll();

            $pages = ceil($count / $this->perPage);
            $error = null;
            if($currentPage > $pages) {
                // Solution à faire sortir l'erreur
                if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    $error = 'Aucune donnée trouvé';
                } else {
                    $error = 'Aucune donnée trouvé';
                }
            }
            return [$items, $pages, $error];
        }

        public function previousLink(int $pages)
        {
            $currentPage = $this->getCurrentPage();
            if($pages > 1 && $currentPage > 1) {
                $link = URLHelper::withParam($this->get, 'p', $currentPage - 1);
                return <<<HTML
                    <a href="?{$link}" class="btn btn-primary">&laquo;</a>
HTML;
            }
        }

        public function nextLink(int $pages)
        {
            $currentPage = $this->getCurrentPage();
            if($pages > 1 && $currentPage < $pages) {
                $link = URLHelper::withParam($this->get, 'p', $currentPage + 1);
                return <<<HTML
                <a href="?{$link}" class="btn btn-primary">&raquo;</a>
HTML;
            } 
        }

        public function chiffreLink(int $pages)
        {
            $currentPage = $this->getCurrentPage();
            $range = 2;
            ?>
            <?php if($pages <= 1): ?>
                <?php // Pas de pagination si une seule page ?>
            <?php elseif($pages <= 7): ?>

                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <?php if($i == $currentPage): ?>
                        <a class='btn btn-light active'><?= $i ?></a> 
                    <?php else: ?>
                        <a class='btn btn-primary' href="?<?= URLHelper::withParam($this->get, 'p', $i) ?>"><?= $i ?></a>
                    <?php endif ?>
                <?php endfor; ?>

            <?php else: ?>

                <?php if($currentPage > 1 + $range): // Si plus de 7 pages ?>
                    <a class='btn btn-primary' href="?<?= URLHelper::withParam($this->get, 'p', 1) ?>">1</a>
                <?php endif; ?>
                <?php if($currentPage > 2 + $range): ?>
                    <div>...</div>
                <?php endif; ?>

                <?php for($i = max(1, $currentPage - $range); $i <= min($pages, $currentPage + $range); $i++): ?>
                    <?php if($i == $currentPage): ?>
                        <a class='btn btn-light active'><?= $i ?></a>
                    <?php else: ?>
                        <a class='btn btn-primary' href="?<?= URLHelper::withParam($this->get, 'p', $i) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($currentPage < $pages - $range): ?>
                    <?php if($currentPage < $pages - $range - 1): ?>
                        <div>...</div>
                    <?php endif; ?>
                    <a class='btn btn-primary' href="?<?= URLHelper::withParam($this->get, 'p', $pages) ?>"><?= $pages ?></a>
                <?php endif; ?>

            <?php endif ?>
            <?php
        }

    }
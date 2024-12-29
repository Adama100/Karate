<?php

    namespace App\Infrastructure\Breadcrumb;

    class Breadcrumb {

        private $crumbs = [];

        /**
         * Ajoute un Crumb
         * @param string $title
         * @param string $url
         * @return \App\Infrastructure\Breadcrumb\Breadcrumb
        */
        public function addCrumb(string $title, string $url): self
        {
            $this->crumbs[] = ['title' => $title, 'url' => $url];
            return $this;
        }

        /**
         * Rend un crumb en chaîne de caractère
         * @return string
        */
        public function render(): string
        {
            $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
            foreach ($this->crumbs as $index => $crumb) {
                if ($index === count($this->crumbs) - 1) {
                    # Dernier élément sans lien
                    $html .= '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($crumb['title']) . '</li>';
                } else {
                    # Éléments avec lien
                    $html .= '<li class="breadcrumb-item"><a href="' . htmlspecialchars($crumb['url']) . '">' . htmlspecialchars($crumb['title']) . '</a></li>';
                }
            }
            $html .= '</ol></nav>';
            return $html;
        }

    }
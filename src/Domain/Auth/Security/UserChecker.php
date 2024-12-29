<?php

    namespace App\Domain\Auth\Security;

use App\App;
use App\Domain\Application\Session\Flash;

    class UserChecker {

        /**
         * Vérifie si l'utilisateur n'est pas connecté
         * @param string $path
         * @return void
        */
        public static function UserNotConnect(string $path) 
        {
            $user = App::getAuth()->user();
            if($user === null) {
                Flash::flash('danger', 'Vous devez vous connecter pour avoir accès à cet espace');
                header('Location: ' . $path);
                exit;
            }
        }

        /**
         * Empêche l'utilisateur connecté d'acceder a une section
         * @param string $path
         * @return void
        */
        public static function UserConnect(string $path) 
        {
            $user = App::getAuth()->user();
            if($user !== null) {
                header('Location: ' . $path);
                exit;
            }
        }

        /**
         * Utilisateur avec le role admin et users
         * @param string $path
         * @param mixed $flash
         * @return void
        */
        public static function AdminCheck(string $path, $flash = true) 
        {
            try {
                App::getAuth()->requireRole('super admin', 'admin');
            } catch(\Exception $e) {
                if($flash === true) {
                    Flash::flash('danger', $e->getMessage());
                    header('Location: ' . $path); exit;
                }
                header('Location: ' . $path); exit;
            }
        }

        /**
         * Utilisateur avec le role super admin, admin et users
         * @param string $path
         * @return void
        */
        public static function SuperAdminCheck(string $path, $flash = true) 
        {
            try {
                App::getAuth()->requireRole('super admin');
            } catch(\Exception $e) {
                if($flash === true) {
                    Flash::flash('danger', $e->getMessage());
                    header('Location: ' . $path); exit;
                }
                header('Location: ' . $path); exit;
            }
        }

    }
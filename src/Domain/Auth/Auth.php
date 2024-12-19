<?php

    namespace App\Domain\Auth;

use App\Session;
use PDO;

    class Auth {

        private $pdo;
        private $session;

        public function __construct(PDO $pdo, array &$session)
        {   
            $this->pdo = $pdo;
            $this->session = &$session; // Une reference au tableau de session
        }

        /**
         * Récupère les informations de l'utilisateur dans la base de données
         * @return bool|object|null
        */
        public function user(): ?User
        {
            $id = $this->session['USER'] ?? null;
            if($id === null) {
                return null;
            }
            $check = $this->pdo->prepare('SELECT * FROM users WHERE id = ? AND sign_at IS NOT NULL');
            $check->execute([$id]);
            $user = $check->fetchObject(User::class);
            return $user ?: null; 
        }

        /**
         * Vérifie le role de l'utilisateur
         * @param string[] $roles
         * @throws \Exception
         * @return void
        */
        public function requireRole(string ...$roles): void
        {
            $user = $this->user();
            if ($user === null) {
                throw new \Exception("Vous n'êtes pas connecter");
            }
            if(!in_array($user->getRole(), $roles)) { 
                throw new \Exception("l'accès à cet espace vous est interdit, vous n'avez pas le rôle suffisant");               
            }
        }

        /**
         * Vérifie la connexion de l'utilisateur
         * @param string $username
         * @param string $password
         * @param string $remember
         * @throws \Exception
         * @return mixed
        */
        public function login(string $username, string $password, string $remember = null): ?User
        {
            $username = trim($username);

            $check = $this->pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND sign_at IS NULL');
            $check->execute([
                'username' => $username,
            ]);
            $check->setFetchMode(PDO::FETCH_CLASS, User::class);
            $user = $check->fetch();
            if($user !== false) {
                throw new \Exception("Veuiller confirmer votre inscription par l'email que vous avez reçu ou opter pour l'option mot de passe oublié");
            }

            $check = $this->pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND sign_at IS NOT NULL');
            $check->execute([
                'username' => $username,
            ]);
            $check->setFetchMode(PDO::FETCH_CLASS, User::class);
            $user = $check->fetch();
            if($user === false) {
                return null;
            }

            if ($user->getLockedUntil() && strtotime($user->getLockedUntil()) > time()) {
                throw new \Exception('Votre compte est bloqué jusqu\'au ' . $user->getLockedUntil() . ', avant de réssayer ou vous pouvez opter pour l\'option mot de passe oublié');
            }

            if(password_verify($password, $user->getPassword())) {
                if($remember !== null) {
                    setcookie('auth', $user->getId() . '--' . sha1($user->getUsername() . $user->getPassword() . $_SERVER['REMOTE_ADDR']),
                    [
                        'domain' => 'localhost',
                        'path' => '/',
                        'expires' => time() + 3600*24*3,
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                }

                // Débloquer le compte
                $unlock = $this->pdo->prepare("UPDATE users SET locked_until = NULL WHERE id = ?");
                $unlock->execute([$user->getId()]);
                $deleteLoginAttempts = $this->pdo->prepare('DELETE FROM login_attempts WHERE user_id = ?');
                $deleteLoginAttempts->execute([$user->getId()]);

                $this->session['USER'] = $user->getId();
                return $user;

            } else {

                $stmt = $this->pdo->prepare("SELECT COUNT(*) as attempt_count FROM login_attempts WHERE user_id = ? AND attempt_time > NOW() - INTERVAL 10 MINUTE
                ");
                $stmt->execute([$user->getId()]);
                $failedAttempts = $stmt->fetch()['attempt_count'];

                if ($failedAttempts >= 3) {

                    $stmt = $this->pdo->prepare("UPDATE users SET locked_until = NOW() + INTERVAL 10 MINUTE WHERE id = ?");
                    $stmt->execute([$user->getId()]);
                    throw new \Exception('Votre compte a été temporairement bloqué après plusieurs tentatives échouées. Un email de déblocage vous a été envoyé');

                } else {
                    $stmt = $this->pdo->prepare("INSERT INTO login_attempts (user_id) VALUES (?)");
                    $stmt->execute([$user->getId()]);
                }
            }
            return null;
        }

        /**
         * Vérifie une cookie existant
         * @param \PDO $pdo
         * @return void
        */
        public static function cookie(PDO $pdo) {
            if(session_status() === PHP_SESSION_NONE) {
                Session::getSession();
            }
            if(isset($_COOKIE['auth']) && !isset($_SESSION['USER'])) {
                $auth = $_COOKIE['auth'];
                $auth = explode('--', $auth);
                $user = $pdo->prepare('SELECT * FROM users WHERE id = ?');
                $user->execute(array($auth[0]));
                $user = $user->fetch();
                $key = sha1($user['username'] . $user['password'] . $_SERVER['REMOTE_ADDR']);
                if($key == $auth[1]) {
                    $_SESSION['USER'] = $user['id'];
                    setcookie('auth', $user['id'] . '--' . $key,
                    [
                        'domain' => 'localhost',
                        'path' => '/',
                        'expires' => time() + 3600*24*3,
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                } else {setcookie('auth', '', time() - 3600, '/', 'localhost', true, true);}
            }
        }

    }
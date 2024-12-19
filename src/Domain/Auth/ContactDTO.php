<?php

    namespace App\Domain\Auth;

use App\Helper\MailHelper;
use App\Session;
use Exception;
use PDO;

    class ContactDTO {

        private $pdo;
        private $username;
        private $message;

        public function __construct(PDO $pdo, string $username, string $message)
        {
            $this->pdo = $pdo;
            $this->username = $username;
            $this->message = $message;
        }

        public function contactDTO(int $user_id)
        {
            $contact = $this->pdo->prepare('SELECT message_at FROM users WHERE id = ?');
            $contact->execute([$user_id]);
            $count = $contact->fetchColumn();

            if($count && strtotime($count) > strtotime('-1 day')) {
                Session::flash('danger', 'Vous avez déjà envoyer un message aujourd\'hui, Veuillez ressayez dans 24 heures');    
            } else {
                $email = strtolower(strip_tags($this->username));
                $message = nl2br(strip_tags($this->message));
                define('CONTACT_MAIL', $email);
                define('CONTACT_MESSAGE', $message);

                if (!empty($email) && !empty($message)) {

                    $stmt = $this->pdo->prepare('UPDATE users SET message_at = NOW() WHERE id = ?');
                    $stmt->execute([$user_id]);
                    $message = str_replace("\'","'",$message);
                    $sujet = "Demande de contact PagneMarket";
                    $messages = MailHelper::getPage('../templates/mails/contact/contact_mail.php');
                    try {
                        $mail = new MailHelper($email, $sujet, $messages);
                        $mail->contact();
                        Session::flash('success', "Message bien réçu ,Nous allons vous repondre dans les plus bref délai.");
                    } catch(Exception $e) { Session::flash('danger', "Message non envoyé: Erreur connexion"); }
                } else { Session::flash('danger', "Veuiller remplir le formulaire"); }
            }
        }
    }
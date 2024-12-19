<?php

    namespace App\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

    class MailHelper {

        public $email;
        public $sujet;
        public $messages;

        public function __construct(string $email, string $sujet, string $messages)
        {
            $this->email = $email;
            $this->sujet = $sujet;
            $this->messages = $messages;
        }

        /**
         * Permet d'envoyer des demandes de contact des utilisateurs à l'administrateur
         * @return void
        */
        public function contact() {
            $mail = new PHPMailer(true);
            // $mail->isSendmail();
            $mail->isSMTP(); 

            // Mailpit
            $mail->Host = 'localhost'; //'smtp.gmail.com'; Configurer le serveur SMTP pour envoyer
            $mail->Port = 1025; // 587; Port TCP auquel se connecter; utilisez 587 si vous définissez `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->SMTPAuth = false; //$mail->SMTPAuth = true; Activer SMTP
            $mail->SMTPSecure = false; // PHPMailer::ENCRYPTION_STARTTLS; Activer le crytage TLS implicite
            //$mail->Username = bakayokoadama507@gmail.com; Nom d'utilisateur SMTP
            //$mail->Password = 'humnoaixdpfjymlt'; Mot de passe SMTP

            $mail->CharSet = "UFT-8";
            $mail->setFrom($this->email);
            $mail->addAddress('bakayokoadama507@gmail.com');
            $mail->addReplyTo($this->email);
            // $mail->addAddress('joe@example.net', 'Joe User'); Si on veut envoyer à d'autre, le nom est optionel

            $mail->isHTML(true);
            $mail->Subject = $this->sujet;
            $mail->Body = $this->messages;
            $mail->AltBody = $this->messages;
            $mail->send();
        }

        public function token()
        {
            $mail = new PHPMailer(true);
            // $mail->isSendmail();
            $mail->isSMTP();

            // Mailpit
            $mail->Host = 'localhost';
            $mail->Port = 1025;
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;

            // $mail->Host       = 'smtp.gmail.com';
            // $mail->SMTPAuth   = true;
            // $mail->Username   = 'bakayokoadama507@gmail.com';
            // $mail->Password   = 'humnoaixdpfjymlt';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->Port       = 587;

            $mail->CharSet = "UFT-8";
            $mail->setFrom($this->email);
            $mail->addAddress($this->email);
            $mail->addReplyTo('bakayokoadama507@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = $this->sujet;
            $mail->Body = $this->messages;
            $mail->AltBody = $this->messages;
            $mail->send();
        }

        public static function getPage($url) 
        {
            ob_start();
            require($url);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

    }
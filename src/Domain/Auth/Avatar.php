<?php

    namespace App\Domain\Auth;

use Exception;
use finfo;

    class Avatar {

        private $pdo;
        private $user;
        private $router;

        public function __construct(\PDO $pdo, User $user, &$router)
        {
            $this->pdo = $pdo;
            $this->user = $user;
            $this->router = &$router;
        }

        /**
         * Avatar
         * @param array $file Ici le tableau est $_FILES['Nom de formulaire']
         * @return void
        */
        public function avatar(array $file)
        {
            $uploadDir = UPLOAD_PATH . DIRECTORY_SEPARATOR .'users/' . $this->user->getId() . '/';
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ext = new finfo();
            $extension = $ext->file($file['tmp_name'], FILEINFO_MIME_TYPE);
            $mimes = ['image/jpeg', 'image/png', 'image/gif'];
            $filename = uniqid("", true) . '.jpg';
            $poid = 5000000;
            $uploadFile = $uploadDir . $filename;

            if (in_array($extension, $mimes)) {
                if ($file["size"] <= $poid) {
                    if(move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {

                        $this->resizeCenter($uploadFile);
                        if($this->user->getAvatar() !== null && file_exists($uploadDir . $this->user->getAvatar())) {
                            unlink($uploadDir . $this->user->getAvatar());
                        }
                        $item = $this->pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                        $item->execute([$filename, $this->user->getId()]);
                        header('Location: ' . $this->router->generate('profile'));

                    } else { header('Location: ' . $this->router->generate('profile')); }
                } else { throw new Exception('Désolé, le fichier est trop volumineux'); }
            } else { throw new Exception('Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés'); }
        }

        /**
         * Redimenssionne l'image
         * @param mixed $uploadFile
         * @return void
        */
        private function resizeCenter($uploadFile) 
        {
            list($width, $height, $type) = getimagesize($uploadFile);
            $newWidth = 400;
            $newHeight = 400;

            // Calcule des coordonnées pour un crop centré
            if($width > $height) {
                $x_offset = ($width - $height) / 2;
                $y_offset = 0;
                $smallest_side = $height;
            }  else {
                $x_offset = 0;
                $y_offset = ($height - $width) / 2;
                $smallest_side = $width;
            }

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);

            switch ($type) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($uploadFile);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($uploadFile);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($uploadFile);
                    break; 
                default:
                    throw new Exception('Impossible de redimensionner cette image');
            }

            imagecopyresampled($newImage, $image, 0, 0, $x_offset, 0, $newWidth, $newHeight, $smallest_side, $smallest_side);
            switch ($type) {
                case IMAGETYPE_JPEG:
                    header('Content-Type: image/jpeg');
                    imagejpeg($newImage, $uploadFile, 75);
                    break;
                case IMAGETYPE_PNG:
                    header('Content-Type: image/png');
                    imagepng($newImage, $uploadFile, 8);
                    break;
                case IMAGETYPE_GIF:
                    header('Content-Type: image/gif');
                    imagegif($newImage, $uploadFile);
                    break;
            }
            imagedestroy($image);
            imagedestroy($newImage);
        }

        /**
         * Supprime l'avatar si le fichier existe
         * @param int $id
         * @param mixed $filename
         * @return void
        */
        public static function detach(int $id, ?string $filename): void
        {
            if(file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $filename)) {
                unlink(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $filename);
            }
        }

        private function NTECH($uploadFile) 
        {
            list($width, $height, $type) = getimagesize($uploadFile);

            switch($width <=> $height) {
                case -1: // Portrait
                    $squareSize = $width;
                    $srcx = 0;
                    $srcY = ($height - $width) / 2;
                    break;
                case 0: // Carre
                    $squareSize = $width;
                    $srcx = 0;
                    $srcY = 0;
                    break;
                case 1: // Paysage
                    $squareSize = $height;
                    $srcx = ($width - $height) / 2;
                    $srcY = 0;
                    break;
            }
            $newImage = imagecreatetruecolor($width, $width);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);

            switch ($type) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($uploadFile);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($uploadFile);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($uploadFile);
                    break; 
                default:
                    header('Location: ' . $this->router->generate('profile')); exit;
            }

            imagecopyresampled($newImage, $image, 0, 0, $srcx, $srcY, $width, $width, $squareSize, $squareSize);
            switch ($type) {
                case IMAGETYPE_JPEG:
                    header('Content-Type: image/jpeg');
                    imagejpeg($newImage, $uploadFile, 75);
                    break;
                case IMAGETYPE_PNG:
                    header('Content-Type: image/png');
                    imagepng($newImage, $uploadFile, 8);
                    break;
                case IMAGETYPE_GIF:
                    header('Content-Type: image/gif');
                    imagegif($newImage, $uploadFile);
                    break;
            }
            imagedestroy($image);
            imagedestroy($newImage);
        }

    }
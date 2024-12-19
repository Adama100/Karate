<?php

http_response_code(404);
header("X-Robots-Tag: noindex, nofollow", true); // Empêche les robots d’indexation d’indexer cette page, ce qui est recommandé pour les pages d’erreur 404 afin de ne pas les afficher dans les résultats de recherche.

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
</head>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: Gotham, Montserrat, "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        min-height: 100vh;
    }

    h1 {
        font-size: 52px;
        font-weight: 700;
        line-height: 1.2;
        color: #000;
        margin-bottom: 1rem;
    }

    h1 strong {
        color: #4869ee;
    }

    p {
        font-size: 22px;
        color: #121c42;
        margin: 0 0 2rem;
    }

    a {
        text-decoration: none;
        padding: 9px 16px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0px 1px 2px rgba(36, 57, 141, 0.4);
        border-radius: 4px;
        line-height: 1.6;
        font-weight: bold;
        color: #ffffff;
        cursor: pointer;
        transition: filter 0.3s, background 0.3s, color 0.3s;
        font-size: 0.9em;
        filter: brightness(1);
        white-space: nowrap;
        background: #4869ee;
        border: 1px solid #4869ee;
    }
</style>
<body>

    <div class="container">
        <h1>
            La page est<br>
            <strong>Introuvable &#41;&#58;</strong>
        </h1>
        <p>La page que vous avez demandée n'existe pas</p>
        <a href="<?= $r->generate('index') ?>">Revenir sur le site</a>
    </div>

</body>
</html>
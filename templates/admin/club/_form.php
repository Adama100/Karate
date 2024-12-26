<?php

use App\Domain\Security\TokenCSRF;
?>

<form action="" method="post" enctype="multipart/form-data" id="myForm">
    <?= TokenCSRF::getFormField() ?>
    <?= $form->input('text', 'name', 'Nom') ?>
    <?= $form->input('text', 'description', 'Description') ?>
    <?= $form->input('text', 'address', 'Adresse') ?>
    <?= $form->input('text', 'master_name', 'Nom du maître') ?>
    <?= $form->input('text', 'master_adresse', 'Adresse du maître') ?>

    <button type="submit" class="btn btn-primary">
        <?php if($club->getId() !== null): ?>
            Modifier
        <?php else: ?>
            Créer
        <?php endif; ?>
    </button>
</form>

<?php

use App\Domain\Security\TokenCSRF;
?>

<form action="" method="post" enctype="multipart/form-data" id="myForm">



    <?= TokenCSRF::getFormField() ?>
    <button type="submit" class="btn btn-primary">
        <?php if($club->getId() !== null): ?>
            Modifier
        <?php else: ?>
            Créer
        <?php endif; ?>
    </button>
</form>

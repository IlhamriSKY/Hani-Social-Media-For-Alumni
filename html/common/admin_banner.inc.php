<?php

/*! Hani Halo Alumni v1  */

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

?>

<?php

if (APP_DEMO) {

    ?>

    <div class="card">
        <div class="card-body collapse show">
            <h4 class="card-title">Demo version!</h4>
            <p class="card-text">Warning! Enabled demo version mode! The changes you've made will not be saved.</p>
        </div>
    </div>

    <?php
}
?>
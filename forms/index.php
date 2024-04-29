<?php
require_once "./templates/header.php";
?>

<!-- Validate if file exist -->
<?php
    if (isset($_GET["v"]) && !empty($_GET["v"])) {
        require_once "./views/".$_GET["v"].".php";
    }
?>

<!-- USE AJAX -->
<?php
require_once "./templates/footer.php";
?>
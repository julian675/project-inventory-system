<?php
session_start();
session_unset();
session_destroy();
header("Location: /project-inventory-system/index.php");
exit();
?>

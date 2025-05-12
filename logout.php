<?php
session_start();
session_unset();
session_destroy();
header("Location: /new_exp/index.php");
exit();
?>

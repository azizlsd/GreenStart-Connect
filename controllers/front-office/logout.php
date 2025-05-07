<?php
session_start();
session_destroy();
header("Location: http://localhost/startup2/view/front-office/login.html");
exit;
?>

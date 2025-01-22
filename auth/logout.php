<?php
session_start();
session_unset();
session_destroy();

header('Location: /airdrops/auth/login.php');
exit;

<?php
require 'auth.php';
session_unset();
session_destroy();
header('Location: ../signin.php');
exit();

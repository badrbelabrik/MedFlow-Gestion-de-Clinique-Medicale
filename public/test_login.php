<?php

require_once __DIR__ . "/../src/Controller/AuthController.php";

$auth = new AuthController();

echo $auth->login("test@gmail.com", "1234");
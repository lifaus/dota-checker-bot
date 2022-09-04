<?php

require_once './src/api/OpenDota.php';
require_once './src/telegram/api/Telegram.php';
require_once './src/bot/MessagesChecker.php';
require_once './src/Server.php';

$server = new Server();
$server->start();

?>
<?php include("vendor/autoload.php");

$main = new \yandex\alisa\Alisa();
$messageStart = "Добро пожаловать!";
$main
    ->addStartMessage($messageStart)
    ->setCaseSensitive(false)
    ->setBlocksActions(true)
    ->setSpellerCorrect(true)
    ->listen();

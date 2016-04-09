<?php


require __DIR__."/../vendor/autoload.php";

use Slim\Models\Db;

$dsn = "mysql:dbname=cv;host=127.0.0.1";
$pdo = new PDO($dsn, "root", "");
$db = new Db($pdo);
$user = $db->user("email", "1111@gmail.com");
//$user->email = "111@gmail.com";
$user->save();
//$user->delete();

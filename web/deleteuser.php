<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';

// Format output to display Norwegian months
setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$username = getUsername();

$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo $twig->render("error.html.index", ["message" => pg_last_error()]);
  die();
}

$result = pg_query_params("DELETE FROM user_account WHERE username = $1", [
  $username,
]);

if (!$result) {
  echo $twig->render("error.html.index", ["message" => pg_last_error()]);
  die();
}

echo $twig->render("delete_user_success.html.twig");
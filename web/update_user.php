<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

if (in_array("", $_POST, true)) {
  echo $twig->render('error.html.twig');
  die();
}

// All good, upate the user

$username = getUsername();
$fullName = $_POST["fullname"];
$phone = $_POST["phone"];
$email = $_POST["email"];

$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
$result = pg_query_params(
  $connection,
  "UPDATE user_account SET fullname=$2, email = $3, phone = $4 WHERE username = $1",
  [$username, $fullName, $email, $phone],
);
if (!$result) {
  echo $twig->render('error.html.twig', ["message" => pg_last_error()]);
} else {
  echo $twig->render('update_user_success.html.twig');
}

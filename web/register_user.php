<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

// Make sure there are no null values
if (in_array("", $_POST, true)) {
  echo $twig->render('error.html.twig');
} else {
  // All good, create the user and add to the databse
  $username = getUsername();
  $fullName = $_POST["fullname"];
  $phone = $_POST["phone"];
  $email = $_POST["email"];

  $connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
  $connection = pg_connect($connectionString);
  $result = pg_query_params(
    $connection,
    "INSERT INTO user_account (username, fullname, email, phone) VALUES ($1, $2, $3, $4)",
    [$username, $fullName, $email, $phone],
  );
  if (!$result) {
    echo $twig->render('error.html.twig');
  } else {
    echo $twig->render('new_user_success.html.twig');
  }
}

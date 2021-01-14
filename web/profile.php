<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

// Connect to the database
$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo $twig->render("error.html.twig");
  die();
}

$username = getUsername();
$result = pg_query_params(
  $connection,
  "SELECT username FROM user_account WHERE username = $1",
  [$username],
);

// Close the connection to the databse
pg_close();

// If the user is not already registered, send them to the sign-up form
if (is_null(pg_fetch_array($result)[0])) {
  echo $twig->render('new_user.html.twig');
  die();
}


$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo $twig->render("error.html.twig");
  die();
}
$username = getUsername();
$result = pg_query_params("SELECT * FROM user_account WHERE username = $1", [$username]);
pg_close();

if ($result) {
  echo $twig->render("user_profile.html.twig", ["user"=>pg_fetch_array($result, NULL, PGSQL_ASSOC)]);
} else {
  echo $twig->render("error.html.twig");
}
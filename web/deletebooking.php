<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';

// Format output to display Norwegian months
setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$id = $_GET["id"];

$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo "An error occurred.\n";
  die();
}

// Get the username, make sure the user is authorized to delete this event
$usernameResult = pg_query_params(
  "SELECT username FROM booking WHERE id = $1",
  [$id],
);
$username = pg_fetch_row($usernameResult)[0];

if ($username != getUsername()) {
  echo $twig->render("error.html.twig", [
    "message" => "Du har ikke tillatelse til å slette denne bookingen.",
  ]);
  die();
}

$result = pg_query_params("DELETE FROM booking WHERE id = $1", [$id]);
if (!$result) {
  echo $twig->render("error.html.twig", [
    "message" => "Vi fikk ikke kontakt med databasen.",
  ]);
  die();
}

echo $twig->render("delete_success.html.twig");

<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';
include '../include/get_calendar_labels.php';
include '../include/get_events.php';

setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo $twig->render('error.html.twig');
  die();
}

$username = getUsername();
$title = $_POST["title"];
$description = $_POST["description"];
$startTime = $_POST["date"] . " " . $_POST["starttime"];
$endTime = $_POST["date"] . " " . $_POST["endtime"];

$result = pg_query_params(
  "INSERT INTO booking (username, title, description, starttime, endtime) VALUES ($1, $2, $3, $4, $5)",
  [$username, $title, $description, $startTime, $endTime],
);
pg_close();

if ($result) {
  echo $twig->render("new_booking_success.html.twig", ["title"=>$title]);
} else {
  echo $twig->render("error.html.twig", ["message"=>"Fikk ikke lagt inn arrangementet."]);
}

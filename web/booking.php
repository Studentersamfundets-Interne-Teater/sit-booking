<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';
include '../include/get_calendar_labels.php';
include '../include/get_events.php';

// Format output to display Norwegian months
setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$eventId = $_GET["id"];
if (!$eventId) {
  echo $twig->render("error.html.twig", [
    "message" => "Forsøkte å laste inn siden uten en event-ID.",
  ]);
  die();
}

// Connect to the database
$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo "An error occurred.\n";
  die();
}

$result = pg_query_params("SELECT * FROM booking WHERE id = $1", [$eventId]);
$booking = pg_fetch_array($result, null, PGSQL_ASSOC);
pg_close();

if (!$result or !$booking) {
  echo $twig->render("error.html.twig", [
    "message" => "Fant ikke forespørselen.",
  ]);
  die();
}

$connection = pg_connect($connectionString);
$userResult = pg_query_params("SELECT * FROM user_account WHERE username = $1", [$booking["username"]]);
$booker = pg_fetch_array($userResult, null, PGSQL_ASSOC);

preg_match('/^\d{4}-\d{2}-\d{2}/', $booking["starttime"], $date);
$dateArray = explode('-', $date[0]);
$dayNumber = $dateArray[2];
$monthName = DateTime::createFromFormat('!m', ($dateArray[1]))->format('F');

preg_match('/\d{2}:\d{2}/', $booking["starttime"], $prettyStartTime);
$prettyStartTime = $prettyStartTime[0];
preg_match('/\d{2}:\d{2}/', $booking["endtime"], $prettyEndTime);
$prettyEndTime = $prettyEndTime[0];

// echo var_dump($dateArray);
$booking["date"] = $dayNumber . ". " . $monthName;
$booking["prettyStartTime"] = $prettyStartTime;
$booking["prettyEndTime"] = $prettyEndTime;

echo $twig->render("single_booking.html.twig", ["booking" => $booking, "booker" => $booker, "currentUser" => getUsername()]);

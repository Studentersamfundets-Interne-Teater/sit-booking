<?php

require_once '../../vendor/autoload.php';
include '../../include/db_config.php';
include '../../include/get_username.php';
include '../../include/get_calendar_labels.php';
include '../../include/get_events.php';
include '../../include/parse_dates.php';

// Format output to display Norwegian months
setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

$loader = new \Twig\Loader\FilesystemLoader('../../templates');
$twig = new \Twig\Environment($loader);

$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo $twig->render("error.html.twig", ["message"=>pg_last_error()]);
  die();
}

$id = $_GET["id"];
$decision = $_GET["decision"];

pg_query_params("UPDATE booking SET status = $1 WHERE id = $2", [$decision, $id]);

$result = pg_query_params("SELECT title, email FROM booking WHERE id = $1", [$id]);
$booking = pg_fetch_array($result, null, PGSQL_ASSOC);
$title = $booking["data"];
$userEmail = $booking["email"];

$headers = 'From: sit-booking@samfundet.no' . "\r\n" .
    'Reply-To: sit-booking@samfundet.no' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$emailText = "Din booking ${title} har fått status ${decision}.";

mail($userEmail, "Din booking ${title} er oppdatert", $emailText, $headers);

echo $twig->render("admin_response.html.twig", ["title"=>$title, "status"=>$decision]);
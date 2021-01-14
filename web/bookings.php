<?php
require_once '../vendor/autoload.php';
include '../include/db_config.php';
include '../include/get_username.php';
include '../include/get_calendar_labels.php';
include '../include/get_events.php';
include '../include/parse_dates.php';

// Format output to display Norwegian months
setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

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


// Connect to the database
$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo "An error occurred.\n";
  die();
}

$result = pg_query_params(
  $connection,
  "SELECT * FROM booking WHERE username = $1 AND endtime > now()::timestamp ORDER BY starttime",
  [$username],
);

if (!$result) {
  echo $twig->render("error.html.twig", ["message" => pg_last_error()]);
  die();
}

$bookings = pg_fetch_all($result);

// Give each booking entry some nice date and time strings
foreach ($bookings as &$booking) {
  parseBooking($booking);
}


echo $twig->render("user_bookings.html.twig", ["bookings"=>$bookings]);
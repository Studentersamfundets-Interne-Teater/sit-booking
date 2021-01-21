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

// Connect to the database
$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  echo $twig->render("error.html.twig");
  die();
}

// Check if the user is already registered
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

// Get query parameters to pass on to calendar
$year = is_null($_GET["year"]) ? intval(date("Y")) : intval($_GET["year"]);
$week = is_null($_GET["week"]) ? intval(date("W")) : intval($_GET["week"]);
$weeks = getWeeksWithDatesInYear($year);
$weeks[$week]["selected"] = true;
$monthLabel = getMonthLabel($year, $week);
$dayLabels = getDayLabels($year, $week);

// Get events
$connection = pg_connect($connectionString);
if (!$connection) {
  echo "An error occurred.\n";
  die();
}
$padZero = $week < 10 ? "0" : "";
$startTime = strftime("%Y-%m-%d", strtotime($year . "W" . $padZero . $week));
$endTime = strftime(
  "%Y-%m-%d",
  strtotime($year . "W" . $padZero . $week . " + 7 days"),
);
$connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
$connection = pg_connect($connectionString);
if (!$connection) {
  return pg_last_error();
  die();
}
$result = pg_query_params(
  $connection,
  "SELECT * FROM booking WHERE starttime > $1 AND endtime < $2 AND (status = 'approved' OR status = 'pending')",
  [$startTime, $endTime],
);
$events = pg_fetch_all($result);
if (!$events) {
  $events = [];
}

// Bool used to indicate whether or not to highlight current weekday:
$currentWeek = $week == date("W");

// Get correct format for next week and previous week to pass on to buttons above calendar
$nextWeekQuery = strftime(
  "?week=%V&year=%Y",
  strtotime($year . "W" . $padZero . $week . " + 7 days"),
);
$prevWeekQuery = strftime(
  "?week=%V&year=%Y",
  strtotime($year . "W" . $padZero . $week . " - 7 days"),
);

echo $twig->render('calendar.html.twig', [
  "year" => $year,
  "weeks" => $weeks,
  "monthLabel" => $monthLabel,
  "dayLabels" => $dayLabels,
  "events" => $events,
  "currentWeek" => $currentWeek,
  "nextWeekQuery" => $nextWeekQuery,
  "prevWeekQuery" => $prevWeekQuery,
]);

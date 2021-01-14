<?php
require_once '../../vendor/autoload.php';
include '../../include/get_username.php';
include '../../include/get_calendar_labels.php';
include '../../include/get_events.php';
include '../../include/parse_dates.php';

// Format output to display Norwegian months
setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);

$loader = new \Twig\Loader\FilesystemLoader('../../templates');
$twig = new \Twig\Environment($loader);

function getBookingsWithStatus($status) {
  global $twig;
  global $dbhost;
  global $dbname;
  global $dbuser;
  global $dbpassword;
  $connectionString = "host=$dbhost dbname=$dbname user=$dbuser password=$dbpassword";
  $connection = pg_connect($connectionString);
  if (!$connection) {
    echo $twig->render("error.html.twig", ["message"=>pg_last_error()]);
    die();
  }
  $result = pg_query_params("SELECT * FROM booking WHERE status = $1 AND starttime > now()::timestamp ORDER BY starttime", [$status]);
  pg_close();
  if (!$result) {
    echo $twig->render("error.html.twig", ["message"=>pg_last_error()]);
    die();
  }
  $bookings = pg_fetch_all($result, PGSQL_ASSOC);
  foreach ($bookings as &$booking) {
    parseBooking($booking);
  }
  return $bookings;
}


$pendingBookings = getBookingsWithStatus('pending');
$approvedBookings = getBookingsWithStatus('approved');
$rejectedBookings = getBookingsWithStatus('rejected');
echo $twig->render(
  "admin_page.html.twig",
  [
    "pendingBookings"=>$pendingBookings,
    "approvedBookings"=>$approvedBookings,
    "rejectedBookings"=>$rejectedBookings
    ]
  );
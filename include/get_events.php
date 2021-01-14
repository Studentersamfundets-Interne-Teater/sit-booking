<?php

include __DIR__ . '/db_config.php';

function getEvents($year, $week)
{
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
    "SELECT * FROM booking WHERE starttime > $1 AND endtime < $2",
    [$startTime, $endTime],
  );
  $events = pg_fetch_all($result);
  return $events;
}

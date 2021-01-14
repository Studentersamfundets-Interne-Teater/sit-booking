<?php
// Generate the options to be displayed in the week <select />
// element for any given year

function getIsoWeeksInYear($year)
{
  // https://stackoverflow.com/a/9018728/5637137
  // In short, this function utilises the fact that
  // invalid dates for DateTime rolls over to the
  // correct date. If there is not 53 weeks in a year,
  // the week number of $date will be 1
  $date = new DateTime();
  $date->setISODate($year, 53);
  return $date->format("W") === "53" ? 53 : 52;
}

function getWeeksWithDatesInYear($year)
{
  // Format output to display Norwegian months
  setlocale(LC_ALL, ["nb", "nob", "no", "nor", "no_NO", "no_NB"]);
  $numberOfWeeks = getIsoWeeksInYear($year);
  $weeks = [];
  for ($i = 1; $i <= $numberOfWeeks; $i++) {
    // Week numbers must be specified with two charactes: Add a 0 in front if needed
    $padZero = $i < 10 ? "0" : "";

    // Use strftime to get the dates in the correct format
    // %e == day of month, %b = short name of month
    $weekStartsOn = strftime(
      '%e. %b',
      strtotime("${year}W" . $padZero . $i)
    );
    $weekEndsOn = strftime(
      '%e. %b',
      strtotime("${year}W" . $padZero . $i . " + 6 days"),
    );
    $weekStartsOn = trim($weekStartsOn);
    $weekEndsOn = trim($weekEndsOn);

    // Check if the week starts and ends in the same month.
    // If so, remove the first mention of the month name as it is redundant.
    if (substr($weekStartsOn, -3) === substr($weekEndsOn, -3)) {
      $weekStartsOn = substr($weekStartsOn, 0, -4); // -4 to also remove space
    }

    // Each week is returned as a list, as we later want to specify that one
    // of them is selected. E.g. if week 1 is selected, we set
    // $weeks[1]["selected"]=>true;
    $weeks[$i] = ["value"=>$i, "description"=>"Uke $i (${weekStartsOn}–${weekEndsOn})"];
  }
  return $weeks;
}

function getMonthLabel($year, $week)
{
  $padZero = ($week < 10 ? "0" : "");
  $firstDayOfWeekMonth = strftime("%B", strtotime("${year}W" . $padZero . $week));
  $lastDayOfWeekMonth = strftime("%B", strtotime("${year}W" . $padZero . $week . " + 6 days"));
  if ($firstDayOfWeekMonth === $lastDayOfWeekMonth) {
    return ucfirst($firstDayOfWeekMonth);
  }
  return ucfirst($firstDayOfWeekMonth) . "–" . $lastDayOfWeekMonth;
}

function getDayLabels($year, $week)
{
  $padZero = ($week < 10 ? "0" : "");
  $days = [];
  $days["mon"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week)));
  $days["tue"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week . " + 1 day")));
  $days["wed"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week . " + 2 days")));
  $days["thu"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week . " + 3 days")));
  $days["fri"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week . " + 4 days")));
  $days["sat"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week . " + 5 days")));
  $days["sun"] = trim(strftime("%e", strtotime("${year}W" . $padZero . $week . " + 6 days")));
  return $days;
}
<?php
function parseBooking(&$booking) {
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
}
<?php

function getUsername()
{
  // This is just a silly mock of a more sophisticated function to
  // get the username used to login to the site
  $verboseUsername = $_SERVER["REMOTE_USER"];//"huhuhu@SAMFUNDET.AD";
  $regularExpression = "/^(.+)@.*/"; // Captures anything before the '@'-sign
  preg_match($regularExpression, $verboseUsername, $matchesArray);
  $username = $matchesArray[1];
  return $username;
}
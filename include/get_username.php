<?php

function getUsername()
{
  // get the username used to login to the site
  $verboseUsername = $_SERVER["REMOTE_USER"]; //"username@SAMFUNDET.AD";
  if (is_null($verboseUsername) and $_SERVER["REMOTE_ADDR"] == "10.0.2.2") {
    // We are in dev mode
    return "vagrant";
  }
  $regularExpression = "/^(.+)@.*/"; // Captures anything before the '@'-sign
  preg_match($regularExpression, $verboseUsername, $matchesArray);
  $username = $matchesArray[1];
  return $username;
}

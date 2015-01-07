<?php
require_once('nest.class.php');
require_once('Leftronic.php');
// Parse the config file 
require 'config.php';

// Your Nest username and password.
echo $username;
echo $password;

// The timezone you're in.
// See http://php.net/manual/en/timezones.php for the possible values.
date_default_timezone_set('Europe/London');
// Here's how to use this class:
$nest = new Nest($username, $password);

echo "Device information:\n";
$infos = $nest->getDeviceInfo($devices_serials[0]);
//jlog($infos);
echo "----------\n\n";

echo "Current temperature:\n";
printf("%.02f degrees %s\n", $infos->current_state->temperature, $infos->scale);
echo "----------\n\n";



// Push information to Leftronic
echo $apiKey;
$update = new Leftronic($apiKey);

$update->pushNumber("myNumberStream", 123456);

<?php
require_once('nest.class.php');
// Your Nest username and password.
$username = 'someemail@email.com';
$password = 'Something other than 1234 right?';
// The timezone you're in.
// See http://php.net/manual/en/timezones.php for the possible values.
date_default_timezone_set('Europe/London');
// Here's how to use this class:
$nest = new Nest($username, $password);

echo "Device information:\n";
$infos = $nest->getDeviceInfo($devices_serials[0]);
jlog($infos);
echo "----------\n\n";

echo "Current temperature:\n";
printf("%.02f degrees %s\n", $infos->current_state->temperature, $infos->scale);
echo "----------\n\n";



// Push information to Leftronic
$update = new Leftronic("MY_ACCESS_KEY");

$update->pushNumber("myNumberStream", 123456);

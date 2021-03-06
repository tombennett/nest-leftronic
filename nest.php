<?php
require_once('nest.class.php');
require_once('leftronic.php');

// Parse the config file
require 'config.php';

// The timezone you're in.
// See http://php.net/manual/en/timezones.php for the possible values.
date_default_timezone_set('Europe/London');
// Here's how to use this class:
$nest = new Nest($nestUsername, $nestPassword);

$infos = $nest->getDeviceInfo();

$temp_current =  $infos->current_state->temperature;
$temp_target = $infos->target->temperature;
$humidity_current = $infos->current_state->humidity;
$heat = $infos->current_state->heat;
$time_to_target_timestamp = $infos->target->time_to_target;
$time_to_target_seconds = $time_to_target_timestamp - time();
$time_to_target_minutes = $time_to_target_seconds / 60;

if($time_to_target_timestamp==0) {
	$time_to_target_minutes = 0;
}
// Wunderground details
$json_string = file_get_contents("http://api.wunderground.com/api/$wundergroundApiKey/geolookup/conditions/q/$wundergroundLocation");
  $parsed_json = json_decode($json_string);
  $location = $parsed_json->{'location'}->{'city'};
  $temp_outside = $parsed_json->{'current_observation'}->{'temp_c'};
  $humidity_outside = $parsed_json->{'current_observation'}->{'relative_humidity'};
  $humidity_outside = rtrim($humidity_outside, "%");
  $current_observation_image = $parsed_json->{'current_observation'}->{'icon_url'};

// Push information to Leftronic
$update = new Leftronic($leftronicAccessKey);
$update->pushNumber("temp_current", $temp_current);
$update->pushNumber("temp_target", $temp_target);
$update->pushNumber("temp_outside", $temp_outside);
$update->pushNumber("temp_current_value", $temp_current);
$update->pushNumber("temp_target_value", $temp_target);
$update->pushNumber("temp_outside_value", $temp_outside);
$update->pushNumber("humidity_inside", $humidity_current);
$update->pushNumber("humidity_outside", $humidity_outside);
$update->pushImage("current_observation_image", $current_observation_image);

if($heat=="true") {
    $update->pushNumber("heat_on", 1);
    $update->pushNumber("heat_on_traffic", 1);
} else {
    $update->pushNumber("heat_on", 0);
    $update->pushNumber("heat_on_traffic", 0);
}

$update->pushNumber("time_to_target", $time_to_target_minutes);

/* Helper functions */
function json_format($json) { 
    $tab = "  "; 
    $new_json = ""; 
    $indent_level = 0; 
    $in_string = false; 
    $json_obj = json_decode($json); 
    if($json_obj === false) 
        return false; 
    $json = json_encode($json_obj); 
    $len = strlen($json); 
    for($c = 0; $c < $len; $c++) 
    { 
        $char = $json[$c]; 
        switch($char) 
        { 
            case '{': 
            case '[': 
                if(!$in_string) 
                { 
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1); 
                    $indent_level++; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case '}': 
            case ']': 
                if(!$in_string) 
                { 
                    $indent_level--; 
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case ',': 
                if(!$in_string) 
                { 
                    $new_json .= ",\n" . str_repeat($tab, $indent_level); 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case ':': 
                if(!$in_string) 
                { 
                    $new_json .= ": "; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case '"': 
                if($c > 0 && $json[$c-1] != '\\') 
                { 
                    $in_string = !$in_string; 
                } 
            default: 
                $new_json .= $char; 
                break;                    
        } 
    } 
    return $new_json; 
}
function jlog($json) {
    if (!is_string($json)) {
        $json = json_encode($json);
    }
    echo json_format($json) . "\n";
}
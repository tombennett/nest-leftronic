<?php
require_once('nest.class.php');
require_once('leftronic.php');

// Parse the config file
require 'config.php';

// The timezone you're in.
// See http://php.net/manual/en/timezones.php for the possible values.
date_default_timezone_set('Europe/London');
// Here's how to use this class:
$nest = new Nest($username, $password);

echo "Device information:\n";
$infos = $nest->getDeviceInfo();
jlog($infos);
echo "----------\n\n";

$temp_current =  $infos->current_state->temperature;
$temp_target = $infos->target->temperature;

// Wunderground details
$json_string = file_get_contents("http://api.wunderground.com/api/$wunderground/geolookup/conditions/q/england/amersham.json");
  $parsed_json = json_decode($json_string);
  $location = $parsed_json->{'location'}->{'city'};
  $temp_f = $parsed_json->{'current_observation'}->{'temp_c'};
  echo "Current temperature in ${location} is: ${temp_c}\n";



// Push information to Leftronic
$update = new Leftronic($apiKey);
$update->pushNumber("temp_current", $temp_current);
$update->pushNumber("temp_target", $temp_target);


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
# nest-leftronic

This is a PHP script to grab heating data from Nest and local weather info from Wunderground, and push it out to a Leftronic dashboard for reporting

## Set up
* Requires PHP5
* Clone the repository, and rename config.php.example to config.php
* Update config.php with your credentials
* TODO: Leftronic stream name requirements
* Run test.php to update Leftronic and dump out values to the console
* When you're happy, set up a cron job to run on a schedule (I use the below to run every 5 mins)

	*/5 * * * * php ~/git/nest-leftronic/nest.php

## Acknowledgments + Source Libraries
* https://github.com/gboudreau/nest-api
* https://www.leftronic.com/api/#php
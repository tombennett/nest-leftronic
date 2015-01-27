# nest-leftronic

This is a PHP script to grab heating data from Nest and local weather info from Wunderground, and push it out to a Leftronic dashboard for reporting

## Configuration
### Configure the Pi from scratch
Install the Raspbian OS as per the user guide here: http://www.raspberrypi.org/help/noobs-setup/

Configure the wireless
	sudo nano /etc/network/interfaces
To configure you wireless network you want to modify the file such that it looks like the following:
	auto lo
	iface lo inet loopback
	iface eth0 inet dhcp

	allow-hotplug wlan0
	auto wlan0

	iface wlan0 inet dhcp
	   wpa-ssid "Your Network SSID"
	   wpa-psk "Your Password"
At this point everything is configured – all we need to do is reload the network interfaces. This can be done by running the following command (warning: if you are connected using a remote connection it will disconnect now):
	sudo service networking reload

Install sudo
	su -
	apt-get install sudo

Add user nest to sudo group
	sudo useradd nest
	sudo passwd SOMESUPERSTRONGPASSWORD
	sudo adduser nest sudo

Create a homedirectory and set permissions
	sudo mkdir /home/nest
	sudo chown nest:users /home/nest

Install PHP
	sudo apt-get install php5-common libapache2-mod-php5 php5-cli php5-curl

Install git
	sudo apt-get install git-core

### Set up the script
Clone repository from github
	mkdir git
	cd git
	git clone https://github.com/tombennett/nest-leftronic.git

Git pull the repository
	cd ~/git/nest-leftronic
	git pull

Rename config.php.example to config.php and enter your own credentials / settings
	cd nest-leftronic
	cp config.php.example config.php
	nano config.php

Test run the php script to check output
	php test.php

When you're happy, set up a cron job to run on a schedule (I use the below to run every 5 mins)
	crontab -e
	*/5 * * * * php ~/git/nest-leftronic/nest.php

## Acknowledgments + Source Libraries
* https://github.com/gboudreau/nest-api
* https://www.leftronic.com/api/#php
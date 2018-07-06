# server status
 Shows server uptime, load, S.M.A.R.T status, and backup status

## smart status configuration

- install smartmontools package

- (optional) set up smartd to ensure adequate monitoring and email notifications: https://www.howtoforge.com/checking-hard-disk-sanity-with-smartmontools-debian-ubuntu

- specify drives for smart status in config.php ($config['smart']).

- run (crontab as root) tools/smart.php once an hour to update status displayed on ob server.

## disk usage configuration

- specify drives for disk usage in config.php ($config['usage']).
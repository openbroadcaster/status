---
layout: default
title: index
---

* TOC
{:toc}

<a name="dashboard"></a>

## server status
{:toc}

Shows server uptime, load, S.M.A.R.T status, and backup status
initial configuration

    copy config.sample.php to config.php

## smart status configuration

    install smartmontools package

    (optional) set up smartd to ensure adequate monitoring and email notifications: https://www.howtoforge.com/checking-hard-disk-sanity-with-smartmontools-debian-ubuntu

    specify drives for smart status in config.php ($config['smart']).

    run tools/smart.php once an hour (crontab as root) to update status displayed on ob server.

## disk usage configuration

    specify drives for disk usage in config.php ($config['usage']).

## backup command configuration

    specify backup descriptions and commands in config.php ($config['backup']).

    run commands tools/backup.php COMMAND_INDEX on appropriate schequle (crontab as root).
    

![ Status](img/status.png ){: .status}



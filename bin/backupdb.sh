#!/bin/sh

date=`/bin/date +%Y%m%d%H%M`
/Applications/MAMP/Library/bin/mysqldump -u root --password=root lifesupport_dev > lifesupport_dev-$date.sql

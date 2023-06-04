#!/bin/bash
#
# Display first two lines of multiline Laravel log items.
#

cd /home/USER/website/code/storage/logs/ || exit 10

grep -A 1 '^\[[0-9]\{4\}' "laravel-$(date "+%Y-%m-%d").log"

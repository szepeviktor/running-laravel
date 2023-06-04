#!/bin/bash
#
# Check working tree and files of composer packages.
#
# CRON.D        :59 *  * * *  USER	/home/USER/website/tripwire-fake.sh

# Exclude a period
#test 1500000000 -gt "$(date +%s)" && exit 0

git --git-dir=/home/USER/Repo/.git --work-tree=/home/USER/website/code status --porcelain

/usr/local/bin/composer --working-dir=/home/USER/website/code install \
    --prefer-dist --no-dev --classmap-authoritative --no-suggest --no-scripts --dry-run 2>&1 \
    | grep -q -F -x 'Nothing to install or update'

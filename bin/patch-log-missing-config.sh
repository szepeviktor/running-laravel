#!/bin/bash
#
# Patch framework to log reading of non-existent configuration keys.
#
# VERSION       :0.1.0
# DATE          :2020-08-30
# AUTHOR        :Viktor Szépe <viktor@szepe.net>
# LICENSE       :The MIT License (MIT)
# URL           :https://github.com/szepeviktor/debian-server-tools
# BASH-VERSION  :4.2+

LARAVEL_CONFIG_REPOSITORY="vendor/laravel/framework/src/Illuminate/Config/Repository.php"

test -r "${LARAVEL_CONFIG_REPOSITORY}"

# shellcheck disable=SC2016
sed -i -e '/ Arr::get(/i if (!$this->has($key)) \\Illuminate\\Support\\Facades\\Log::notice("Missing configuration key: ".$key); // FIXME' \
    "${LARAVEL_CONFIG_REPOSITORY}"

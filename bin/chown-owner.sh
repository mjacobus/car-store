#!/bin/bash

user="sojogosgratis"
group="psacln"

if (($# == 0)); then
    exit 0
fi

if (($# == 3)); then
    group=$3
fi

if (($# >= 2)); then
    user=$2
fi

if (($# >= 1)); then
    path=$1
fi


cmd="/bin/chown -R $user:$group $path"

echo -e "Executing $cmd\n"

$($cmd)

exit $?

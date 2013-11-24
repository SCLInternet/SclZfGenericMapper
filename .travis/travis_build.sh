#!/bin/bash

### Check for license headers
LICENSE=0

LICENSE_HEADER="<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */"

for i in `find src tests -name '*.php'`; do
    diff <(echo "$LICENSE_HEADER") <(head -7 "$i");

    if [ "$?" -ne "0" ]; then
        echo "Missing or invalid license header in \"$i\""
        let LICENSE=1
    fi
done

### Display results
EXIT=0

echo
echo "#### RESULTS:"

if [ "$LICENSE" -ne "0" ]; then
    echo "**** License header check failed"
    EXIT=1
fi

exit $EXIT

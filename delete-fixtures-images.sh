#!/bin/bash
if [ -d /web/images/items ]; then
    rm -rf web/images/items/
    mkdir web/images/items/
    chmod 755 web/images/items/
fi

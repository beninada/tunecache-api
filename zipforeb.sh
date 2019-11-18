#!/bin/bash

rm ../tunecache-default.zip
zip ../tunecache-default.zip -r * .[^.]* -x "vendor/*" -x ".git/*"

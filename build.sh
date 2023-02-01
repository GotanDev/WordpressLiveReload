#!/bin/bash 

mkdir target
version=$(cat livereload.php | grep "Version:" | cut -d ":" -f 2 | tr -d " ")

zip target/theme_livereload-$version.zip *.js *.php  README.md LICENSE

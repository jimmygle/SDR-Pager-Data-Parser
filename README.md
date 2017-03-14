# Pager Parser

[SDR](https://en.wikipedia.org/wiki/Software-defined_radio) fun! 

## Parsing

Here's how to get things parsed into a MySQL database:

1) Create database and insert schema.sql and then update config.php with correct credentials.
2) Update config.php with correct raw log file to parse.
3) Run `php parser.php`

## View Results

Here's how to view the messages in an HTML formatted table:

1) Start local PHP web server (in this dir): `php -S 0.0.0.0:1337`
2) Open http://[your_ip]:1337/show.php

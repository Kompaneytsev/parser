## before start
```bash
composer install
```
```bash
composer before-start
```
and fill this file with actual credentials
```bash
.env.local
```
apply migrations from migration dir

## start server
run server with docker
```bash
composer docker-start
```

## parse data
```bash
composer parse-data
```

## show data
go http://127.0.0.1:5005/supermetrics_stats.php

## stop server
```ctrl+c``` and
```bash
composer docker-stop
```

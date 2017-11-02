<?php
$version="v1.0.0.".time();
$registerTimeout=60*60*6; //sec

//smarty config
$smartyDir='/var/www/test/core/smarty';
$smartyTemplateDir='/var/www/test/core/templates';
$smartysetCompileDir='/var/www/test/core/templates_c';
$smartysetCacheDir='/var/www/test/core/cache';
$smartycaching=false;
$smartycache_lifetime=120;
$smartydebugging=false;

//base site url
$baseUrl="http://localhost/test";

//MySQL
$mysqlServer="DBSERVER";
$mysqlUser="BDUSER";
$mysqlPass="DBPASSWORD";
$mysqlDB="DBNAME";

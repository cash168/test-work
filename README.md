
TEST WORK
-------

**Example:**

www home dir: **/var/www/test/public**
core dir: **/var/www/test/core**
Base URL: **http://localhost/test/**

Installation
-------

    cd /var/www/test/
    git clone https://github.com/cash168/test.git

Configure
-------

1. Edit /var/www/test/public/coreconfig.php set core directory:
  
	 **$coreDir='/var/www/test/core';**

2. Edit /var/www/test/core/config.php set smarty directory, site URL and mysql settings:
 
	 **//smarty config**
	**$smartyDir='/var/www/test/core/smarty';**
	**$smartyTemplateDir='/var/www/test/core/templates';**
	**$smartysetCompileDir='/var/www/test/core/templates_c';**
	**$smartysetCacheDir='/var/www/test/core/cache';**
	**$smartycaching=false;**
	**$smartycache_lifetime=120;**
	**$smartydebugging=false;**
	**//base site url**
	**$baseUrl='http://localhost/test';**
	**//MySQL**
	**$mysqlServer="DBSERVER";**
	**$mysqlUser="BDUSER";**
	**$mysqlPass="DBPASSWORD";**
	**$mysqlDB="test";**
3. Create DB **test** and import test.sql

4. Open URL http://localhost/test/ and login
	 **user:** test
	 **password:** test
 

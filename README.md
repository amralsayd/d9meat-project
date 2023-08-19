## clone and setup the project
 ```bash
git clone https://github.com/amralsayd/d9meat-project.git
cd d9meat-project/
composer install
cp web/sites/default/example.settings.php web/sites/default/settings.php
```

## [import DB in /database folder]
## [change DB Credentials in settings files]
```php
$databases['default']['default'] = array (
  'database' => '<db_name>',
  'username' => '<db_user>',
  'password' => '<db_pass>',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\mysql\\Driver\\Database\\mysql',
  'driver' => 'mysql',
  'autoload' => 'core/modules/mysql/src/Driver/Database/mysql/',
);
```

## [Add record in web server virtual hosts]
	ex : xampp
	file path: apache\conf\extra
```html
	<VirtualHost d9meat-project.local.com:80>
	  ServerAdmin webmaster@dummy-host2.example.com
	  DocumentRoot "C:/xampp/htdocs/d9meat-project/web"
	  ServerName d9meat-project.local.com
	  ServerAlias www.d9meat-project.local.com
	  ErrorLog "logs/d9meat-project.local.com-error.log"
	  CustomLog "logs/d9meat-project.local.com-access.log" common
	</VirtualHost>
```	
	restart the web server


## [Add record in hosts. file]
	ex: win10
	file path : C:\Windows\System32\drivers\etc
	127.0.0.1			d9meat-project.local.com
	
## [Run the project]
http://d9meat-project.local.com/api/pages

## [Admin account]
user:admin
password:admin



## [Repo]
[amralsayd] https://github.com/amralsayd/d9meat-project/tree/main
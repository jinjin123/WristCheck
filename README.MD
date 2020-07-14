# Install

- Init

```
docker-compose up -d --build
docker-compose exec php-fpm bash
cd /var/www/html&&composer install
```

- Mysql (Import [Datebase](https://drive.google.com/file/d/1HpMvgpYpUpjFs0HO4lyFsCKW2c7qM06l/view?usp=sharing))

```
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=wristcheck
MYSQL_USER=wristcheck
MYSQL_PASSWORD=wristcheck
```

- Edit /sites/default/settings.php

```
$databases['default']['default'] = array (
  'database' => 'wristcheck',
  'username' => 'wristcheck',
  'password' => 'wristcheck',
  'prefix' => '',
  'host' => 'mysql',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);
```


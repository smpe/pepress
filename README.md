# pepress

Demonstration project.

## Installation

First, be sure that all dependencies are installed:
  * Apache httpd 2.x.x / Nginx 1.x.x
  * PHP (5.3.x/5.4.x/5.5.x)
    * php_curl
    * php_mbstring
    * php_pdo
    * php_pdo_mysql
    * php_amqp [+]
    * php_gd2 [+]
    * php_imagick [+]
    * php_memcache [+]
    * php_redis [+]
  * PHP-FPM
  * MySQL (5.1/5.5)

Get the source:

```bash
git clone https://github.com/smpe/pepress.git pepress
git clone https://github.com/smpe/Smpe.git pepress/library/Smpe
```

Create database:

```sql
CREATE DATABASE `support` CHARACTER SET utf8 COLLATE utf8_general_ci
```

Create tables:

```bash
mysql -hlocalhost -uroot -ppass --quick --default-character-set=utf8 support < /path/to/pepress/library/Support/Support.sql
```

Type the address in web browser location box:

```bash
http://localhost/pepress/public/
```

[Feedback](https://github.com/smpe/Smpe/issues)

# Copyright

Copyright 2015 The Smpe Authors. All rights reserved.

Use of this source code is governed by a BSD-style license that can be found in the LICENSE file.

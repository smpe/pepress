# pepress

Demonstration project.

Usage:

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

[Feedback](http://github.com/smpe)

# Copyright

Copyright 2015 The Smpe Authors. All rights reserved.

Use of this source code is governed by a BSD-style license that can be found in the LICENSE file.

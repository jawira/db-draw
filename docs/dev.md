# Dev notes

## Phing targets

![phing targets](images/build.png)

## Running tests

PHPUnit needs a mysql database to run tests.

This is handled by `docker-composer`, nevertheless you can customize this host:

```console
phing phpunit:run -Ddb.host=172.25.0.2
```

## Sample databases

<dl>
<dt>Sakila Spatial Sample Database Schema</dt>
<dd>Copyright (c) 2014, Oracle Corporation<br>
Source: https://github.com/datacharmer/test_db/blob/master/sakila/sakila-mv-schema.sql
</dd>
<dt>Employees Sample Database</dt>
<dd>Copyright © 2008, 2021, Oracle and/or its affiliates.<br>
Source: https://dev.mysql.com/doc/employee/en/</dd>
<dt>MySQL Sample Database classicmodels</dt>
<dd>Source: http://www.mysqltutorial.org/mysql-sample-database.aspx</dd>
</dl>

More: https://www3.ntu.edu.sg/home/ehchua/programming/sql/SampleDatabases.html

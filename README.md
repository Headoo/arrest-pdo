Arrest-PDO
=========

About
-----

Arrest-PDO is a "plug-n-play" and secure RESTful API for your favorite SGBDR. It actually supports Mysql, PostgreSql, SQLite, MsSql Server, Oracle, Firebird, Cubrid, Ibm, Informix, 4D and ODBC.
Arrest-PDO is a fully completed and tested project based on Arrest-Mysql from Gil Bitron, https://github.com/gilbitron/Arrest-MySQL.
It provides IP access control to secure your api access to people or a group of people you want.
It also provides HTTP methods access control, to only allow access to specific methods for your api. Available HTTP methods are: GET,POST,PUT,DELETE.
Easy to customize, it comes with two environments for rapid deployment on your server and unit testing: 'prod' and 'test' environments.

This repository is fully tested with CodeCeption and respect PSR coding standards.

![Image](https://insight.sensiolabs.com/projects/8e837109-602d-4fa1-b83a-8778aa19fc8f/big.png?raw=true)


Requirements
------------

Require PHP version 5.4 or greater and Mysql 5 or greater.


Installation
------------

1. Put Arrest-PDO on your server and target Apache root directory to the web folder.
2. Apply chmod 0755 to all the project.
3. Create an empty database "test" 
4. In the "test" database, create a "test" table
5. In the "test" table, add these specific columns (id, username, email).


Configuration
-------------

The configuration is done in three quick steps, you have to configure the database and ip configuration for prod and test environments.

- PROD environment:
```
    - Open "app/prod/conf/database.ini", and fill in your informations.
    - Open "app/prod/conf/ips.ini", and fill in authorized ip (separated by comma) for prod environment.
```

- TEST environment:
```
    - Open "app/test/conf/database.ini", and fill in your informations.
    - Open "app/test/conf/ips.ini", and fill in authorized ip (separated by comma) for test environment.
```

- Codeception test configuration for acceptance testing:
```
    - Open "tests/acceptance.suite.yml", and fill in your informations.
        * If you're using a specific port for your url, add the specific port for "PhpBrowser url and REST url".
        * Set login and password of your database
```

SGBDR Configuration
-------------------

If you're using SQLite, Oracle, Ibm, Firebird, Informix or Odbc database system, inside your database.ini environment, just specify your dsn like this:
```
    sqlite_path_to_db=/opt/databases/mydb.sq3 

    oracle_path_to_db =dbname=//localhost:1521/mydb 
    
    ibm_path_to_db=DRIVER={IBM DB2 ODBC DRIVER};DATABASE=testdb;HOSTNAME=11.22.33.444;PORT=56789;PROTOCOL=TCPIP;
    
    firebird_path_to_db=dbname=localhost:/var/lib/firebird/2.5/data/employee.fdb
    
    informix_path_to_db=host=host.domain.com;service=9800;database=common_db;server=ids_server;protocol=onsoctcp;EnableScrollableCursors=1
    
    odbc_path_to_db=DRIVER={IBM DB2 ODBC DRIVER};HOSTNAME=localhost;PORT=50000;DATABASE=SAMPLE;PROTOCOL=TCPIP;UID=db2inst1;PWD=ibmdb2; 
```



Run acceptance Tests with CodeCeption
-------------------------------------

Before anything, you will have to test the correct execution of Arrest-PDO in your environment.
For that, you will have to run acceptance tests written with codeception.

Open your console line command at your root project and type

```
    php codecept.phar run
```     

It will test for POST, GET, PUT and DELETE methods in the "test" table, and must return "OK" to the four tests.

NB: If any error occured:

- Try to download again the codecept.phar library
``` 
    wget http://codeception.com/codecept.phar .
    OR
    http://codeception.com/thanks
```

- Repeat all the previous steps.



Access control
--------------

Arrest-PDO provides IP access control, but can do further. Let's see this.

For ip access control, in "app/{environment}/conf/ips.ini", you can specify any ip addresses you want, other ip addresses will be rejected.
```
    allowed_ips=127.0.0.1,0.0.0.1
```

You can also allow access to specific tables of your database, so you can keep sensistive datas saved. open "web/index.php" file.
```
    $pma->allowedTables(array('test')); //add any tables you want to be accessible from the api
```

Another good access control concerns Http methods.
Maybe you just need to Insert datas in your tables, and don't want anyone to GET, PUT or DELETE on it.
You are free to specify forbidden Http Methods. Open "web/index.php" file.
```
    $pma->forbiddenMethods(array('GET', 'PUT', 'DELETE'));
```


Tables with specific primary keys
---------------------------------

By convention, a table primary key is called "id", but some tables may have a different name.

Arrest-PDO helps you to easily specify these use cases.

Open "web/index.php", and specify in an associative array, the table and its primary key.
```
    $database->setCustomPkFieldsPerTable(array('test' => 'testid'));
```



MORE Documentation
------------------

For example lets suppose you have set up Arrest-PDO at http://api.example.com and your database has a table in it called "users". To get a list of customers you would simply need to do:

```GET http://api.example.com/users```

Where "users" is the table name. As a response you would get a JSON formatted list of customers. Or say you only want to get one customer, then you would do this:

```GET http://api.example.com/users/123```

Where "123" here is the ID of the customer. For more information on using Arrest MySQL see the Usage section below.

To put this into practice below are some example of how you would use Arrest-PDO:

```
// Get all rows from the "users" table
GET http://api.example.com/users
// Get a single row from the "users" table (where "123" is the ID)
GET http://api.example.com/users/123
// Get 50 rows from the "users" table
GET http://api.example.com/users?limit=50
// Get 50 rows from the "users" table ordered by the "date" field
GET http://api.example.com/users?limit=50&order_by=date&order=desc

// Create a new row in the "users" table where the POST data corresponds to the database fields
POST http://api.example.com/users

// Update customer "123" in the "users" table where the PUT data corresponds to the database fields
PUT http://api.example.com/users/123

// Delete customer "123" from the "users" table
DELETE http://api.example.com/users/123
```

All responses are in the JSON format. For example a GET response from the "users" table might look like:

```json
[
    {
        "id": "114",
        "username": "Edouard Kombo",
        "email": "edouard.kombo@gmail.com"
    },
    ...
]
```

Successful POST, PUT, and DELETE responses will look like

```json
{
    "status": "success",
    "content": {
        "message": "Request successfully done!",
        "code": 200
    }
}
```

Errors are in the format:

```json
{
    "status": "error",
    "content": {
        "message": "No result found or bad request!",
        "code": 204
    }
}
```

The following codes and message are avaiable:

* 200 Success
* 204 No Content
* 404 Not Found




Contributing
-------------

If you want to help me improve this bundle, please make sure it conforms to the PSR coding standard. The easiest way to contribute is to work on a checkout of the repository, or your own fork, rather than an installed version.



Issues
------

Bug reports and feature requests can be submitted on the [Github issues tracker](https://github.com/Headoo/Arrest-PDO/issues).

For further informations, contact tech@headoo.com.

PHP MYSQL API
=============

About
-----

Php-Mysql-Api "plug-n-play" RESTful API for your MySQL database, including ip addresses access control.
It provides a REST API that maps directly to your database stucture instantly.

This repository is fully tested with CodeCeption.


Requirements
------------

Require PHP version 5.4 or greater and Mysql 5 or greater.


Installation
------------

Simply put these files into a folder somewhere on your server. Then edit "database.ini", "ips.ini" and fill in your database details, allowed ip addresses and you are good to go.


Run acceptance Tests with CodeCeption
-------------------------------------

1. Create new database and new table. Name each of them "test".

2. Inside "test" table, add 3 columns (id, username, email).

3. Open "tests/acceptance.suite.yml" and fill in the correct parameters to connect to your database (Be sure to specify ports if needed).
```
    modules:
        enabled: [Db, PhpBrowser, AcceptanceHelper]
            config:
                PhpBrowser:
                    url: 'http://localhost/' #If port != 80, use 'http://localhost:myport'
                Db:
                    dsn: 'mysql:host=localhost;dbname=test' #If port != 80, use 'mysql:host=localhost:myport;dbname=test'
                    user: 'root'
                    password: ''
                    dump: 'tests/_data/dump.sql'
                    populate: true
                    cleanup: false
```     

4. if you're using a different port than 80, please specify it in "tests/acceptance/test*Cept.php"

5. Open your console and run
```php codecept.phar run```

NB: When you're testing, be sure that "database.ini" configurations match codeception database parameters.


IP Access control
------------------

To allow access to specific ip addresses, open "ips.ini" file, and fill each ip address after a comma, like this:
```
    allowed_ips=127.0.0.1,0.0.0.1
```

You can also allow some tables to be requested. The necessary line is in "index.php" file.
```
$pma->allowedTables(array('test'));
```

Another good option, you can specify which http method will be forbidden
```
$pma->forbiddenMethods(array('GET', 'PUT', 'DELETE'));
```


Change database configs
-----------------------

When you have finished with tests, you can change database configurations.
Open "database.ini" file, and fill each ip address after a comma, like this:
```
    server=localhost
    database=MYDATABASE
    username=root
    password=
    verbose=false
```


MORE Documentation
------------------

For example lets suppose you have set up php-mysql-api at http://api.example.com and your database has a table in it called "users". To get a list of customers you would simply need to do:

```GET http://api.example.com/users```

Where "users" is the table name. As a response you would get a JSON formatted list of customers. Or say you only want to get one customer, then you would do this:

```GET http://api.example.com/users/123```

Where "123" here is the ID of the customer. For more information on using Arrest MySQL see the Usage section below.

To put this into practice below are some example of how you would use php-mysql-api:

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
    "success": {
        "message": "Success",
        "code": 200
    }
}
```

Errors are in the format:

```json
{
    "error": {
        "message": "No Content",
        "code": 204
    }
}
```

The following codes and message are avaiable:

* 200 Success
* 204 No Content
* 404 Not Found


By default, primary key fields are nammed "id", when you have tables with a different name than "id", you can customize them in index.php file, like this.
```
    $database   = new \src\Database($directory . 'database.ini');
    $database->connect();
    $database->setCustomPkFieldsPerTable(array(TABLE => PK));
    $database->mapDatabase();
    $database->max_queries = 10;
``` 


Contributing
-------------

If you want to help me improve this bundle, please make sure it conforms to the PSR coding standard. The easiest way to contribute is to work on a checkout of the repository, or your own fork, rather than an installed version.



Issues
------

Bug reports and feature requests can be submitted on the [Github issues tracker](https://github.com/headoo/php-mysql-api/issues).

For further informations, contact me directly at edouard.kombo@gmail.com or tech@headoo.com.

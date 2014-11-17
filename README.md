PHP MYSQL API
=============

About
-----

Php-Mysql-Api is a tested "plug-n-play" RESTful API for your MySQL database with ip addresses access control.
It provides a REST API that maps directly to your database stucture with no configuation.

This repository is tested with CodeCeption.


Requirements
------------

Require PHP version 5.4 or greater, Mysql 5 or greater.


Installation
------------

Simply put these files into a folder somewhere on your server. Then edit "database.ini", "ips.ini" and fill in your database details, allowed ip addresses and you are good to go.


Run acceptance Tests with CodeCeption
-------------------------------------

1. Create a "test" database, with a "test" table.

2. Inside "test" table, add columns "id, username, email".

3. Open "tests/acceptance.suite.yml" and fill in the correct parameters to connect to your database (Be sure to specify ports if needed).
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

4. if you're a port different than 80, please specify it in "tests/acceptance/test*Cept.php"

4. Open your console and run 
    php codecept.phar run


Documentation
-------------

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

// Create a new row in the "customers" table where the POST data corresponds to the database fields
POST http://api.example.com/users

// Update customer "123" in the "customers" table where the PUT data corresponds to the database fields
PUT http://api.example.com/users/123

// Delete customer "123" from the "customers" table
DELETE http://api.example.com/users/123
```

All responses are in the JSON format. For example a GET response from the "customers" table might look like:

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


Contributing
-------------

If you want to help me improve this bundle, please make sure it conforms to the PSR coding standard. The easiest way to contribute is to work on a checkout of the repository, or your own fork, rather than an installed version.



Issues
------

Bug reports and feature requests can be submitted on the [Github issues tracker](https://github.com/headoo/php-mysql-api/issues).

For further informations, contact me directly at edouard.kombo@gmail.com or tech@headoo.com.

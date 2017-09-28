Secret Server
===================
This is a sample application that implementing a non-production ready **Secret Server API**. You can create a secret which can be queried a limited time and optionally you can specify a Time-To-Live.

Hosted Demo: https://api.secret.pety.me/

----------

Install
-------------
You need the following to run this sample application:

 - PHP >= 5.6.4
 - OpenSSL PHP Extension
 - PDO PHP Extension
 - Mbstring PHP Extension
 - MySQL Server

Run the following commands in your webroot:

    $ git clone https://github.com/netwarex/secret-server .
    $ cd secret-server
    $ composer install
Now please edit the **.env** file for your MySQL database, then run migrations:

    $ php artisan migrate

Usage
-------------

You can add a secret to your database via send a POST request.

    POST /secret
Parameters:

 - secret - The stored secret data (255 chars. max.)
 - expireAfterViews - The number of request before the secret is invalidated. Needs to be greater than zero.
 - expireAfter - You can specify a TTL in minutes. Use 0 for no TTL.

Response:
The server will respond with the object if every parameter was validated.

    {
    "secretText": "string",
    "remainingViews": "10",
    "expiresAt": "2017-09-28T14:05:00.000Z",
    "hash": "hash-string",
    "createdAt": "2017-09-28T14:00:00.000Z"
    }
If something wrong the following error code will shown:

    {
    "code": 405,
    "message": "Invalid input"
    }

You can also specify the output format via the 'format' GET parameter.

E.g.: `POST /secret?format=xml`

Supported outputs are:

 - json
 - xml

You can retrieve a secret from your database via send a GET request.

    GET /secret/hash-string
Response:

    {
    "hash":"hash-string",
    "secretText":"string",
    "createdAt":"2017-09-28T14:00:00.000Z",
    "expiresAt":"2017-09-28T14:05:00.000Z",
    "remainingViews":9
    }

If the requested secret is not exists or it was invalidated because the count of views or expiration the following error message will appears:

    {
    "code":404,
    "message":"Secret not found"
    }
You can also specify the format for this request too, the same way.

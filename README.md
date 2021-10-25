Chin Chan Pu API Game
========================

ChinChanPu game api provides the necessary endpoints to store in sessions and generate the results.
It's based in symfony flex 5.3

Requirements
------------

* PHP 7.4 or higher;
* JSON extension;
* and the [usual Symfony application requirements][1].

Installation
------------

Clone the repository and install dependencies with composer

```bash
git clone https://github.com/vgpastor/chinchanpu-back
composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader
```

Alternatively, you can install all development dependencies:

```bash
composer install
```

Usage
-----

There's no need to configure anything to run the application. If you have
[installed Symfony][4] binary, run this command:

```bash
$ cd chinchanpu-back/
$ symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][3] like Nginx or
Apache to run the application.

Tests
-----

Execute this command to run tests:

```bash
$ cd chinchanpu-back/
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[4]: https://symfony.com/download

EndPoints
------------
All request and response it's necessary that use JSON format

**Generate game**

Return a result based in the choice of player.

* **URL**

  /generate

* **Method:**

  `POST`

*  **Body**

   `{ player=[rock|scissors|paper] }`

* **Success Response:**

    * **Code:** 201 <br />
      **Content:** `{
      "uid": "9877a503-4ffa-4d09-8e20-537f18202bdc",
      "dateOfGame": 1635158309,
      "enemy": "rock",
      "player": "rock",
      "winner": 0
      }`

* **Error Response:**

    * **Code:** 400 BAD REQUEST <br />
      **Content:** `{"error": "Player selection it's not allowed"}`


**Get History of game**

Return the results of the player.

* **URL**

  /history

* **Method:**

  `GET`

*  **URL Params**

   **optional:**

   `limit=[integer]`


* **Success Response:**

    * **Code:** 200 <br />
      **Content:** `[
      {
      "uid": "9877a503-4ffa-4d09-8e20-537f18202bdc",
      "dateOfGame": 1635158309,
      "enemy": "rock",
      "player": "rock",
      "winner": 0
      }
      ]`

**Delete History of game**

Delete the results of the player.

* **URL**

  /history

* **Method:**

  `DELETE`

* **Success Response:**

    * **Code:** 202 <br />

    

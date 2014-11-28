Qandidate Assessment - Hangman API
==================================

# Installation
The project is using Symfony HttpFoundation for the Request/Response part, this vendor dir has been commited to the repo, but the "standard" way to install this dependency would be via Composer.

# Database
The project is using a MySQL database, and with the default config, these are the connection settings:
- *host*: localhost
- *DB name*: hangman
- *user*: hangman_site
- *password*: h4ngm4n_s1t3!!

These are the queries needed to create the user, database and tables:

```
CREATE USER 'hangman_site'@'localhost' IDENTIFIED BY 'h4ngm4n_s1t3!!';
CREATE DATABASE hangman;
GRANT DELETE, INSERT, SELECT, UPDATE ON hangman.* TO 'hangman_site'@'localhost';
CREATE TABLE games (
    id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Game Id',
    full_word VARCHAR(50) NOT NULL COMMENT 'Word to be guessed',
    word VARCHAR(50) NOT NULL COMMENT 'Word being guessed, with dots for unguessed chars',
    max_tries TINYINT UNSIGNED NOT NULL COMMENT 'Maximum tries that the user had when the game started',
    tries_left TINYINT UNSIGNED NOT NULL COMMENT 'Tries that the user has left on this game'
) ENGINE=InnoDb CHARSET=latin1 COMMENT 'Stores games data';
```

You can find the same queries in the "app/resources/create_db.sql" file in the repo.

# Using the API

There are four possible calls you can do to the API:

Create a new game:

- You can do it making a post request to "/games". Example:

```
$ curl -X POST api.qandidate.dev/games
{"id":"4","word":"..................","tries_left":11,"status":"busy"}
```

Get game info:

- Make a GET request to /games/:id, where id is the game id. Example

```
$ curl -X GET api.qandidate.dev/games/4
{"id":"4","word":"..................","tries_left":"11","status":"busy"}
```

Play the game:

- Make a POST request to /game/:id. Make sure to send a "char=X" along with your post vars. Example:

```
$ curl -X POST -d "char=b" api.qandidate.dev/games/4
{"id":"4","word":"...........b......","tries_left":"11","status":"busy"}
```

List all games:

- Make a GET request to /games. Example:

```
$ curl -X GET api.qandidate.dev/app.php/games
[{"id":"1","word":".......","tries_left":"11","status":"busy"},{"id":"2","word":".e..e...","tries_left":"10","status":"busy"},{"id":"3","word":"..a........","tries_left":"10","status":"busy"},{"id":"4","word":"...........b......","tries_left":"11","status":"busy"}]
```
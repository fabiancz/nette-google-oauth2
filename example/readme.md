## Usage

This example is intended to use with Nette sandbox.

Install Nette sandbox by composer:

```
$ composer create-project nette/sandbox yourProject
```

This project is fork, so you can add it to yourProject by editing composer.json:
  - insert repositories section:

```neon
"repositories": [
      {
          "type": "vcs",
          "url": "https://github.com/fabiancz/nette-google-oauth2"
      }
  ],
```
  - add require:

```neon
"fabiancz/nette-google-oauth2": "@dev"
```

Create API acces on Google APIs console: https://code.google.com/apis/console. In Redirect URIs specify http://localhost/homepage/google-auth. You have to run whole application directly on localhost! Google doesn't support own domains (f.ex.: http://myProject.l). Add credentials to config.neon.

Replace these files in yourProject:
- app/presenters/HomepagePresenter.php
- app/templates/Homepage/default.latte
- app/config/config.neon

Disable loading of config.neon.localhost by removing corresponding line in bootstrap.php

Create database and table "user" and add credentials to config.neon

```mysql
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `google_id` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Visit your http://localhost and login by Google!:)

## Troubleshooting

```php
Nette\InvalidStateException

Ambiguous class App\HomepagePresenter ...
```

Remove libs/ dir from definition of RobotLoader paths. This line: ```php->addDirectory(__DIR__ . '/../libs')```

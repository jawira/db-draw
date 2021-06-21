# 📐 DB Draw

**Generate an ER diagram from your existing database.**

[![Latest Stable Version](http://poser.pugx.org/jawira/db-draw/v)](https://packagist.org/packages/jawira/db-draw)
[![.gitattributes](http://poser.pugx.org/jawira/db-draw/gitattributes)](https://packagist.org/packages/jawira/db-draw)
[![composer.lock](http://poser.pugx.org/jawira/db-draw/composerlock)](https://packagist.org/packages/jawira/db-draw)
[![License](http://poser.pugx.org/jawira/db-draw/license)](https://packagist.org/packages/jawira/db-draw)

## How to use

Because **DB Draw** is a library, you have to use it as a dependency and instantiate it in your project.

In order to create a diagram you have to follow these steps:

1. Your application must provide a valid [doctrine/dbal](https://github.com/doctrine/dbal) connection.
2. Instantiate `\Jawira\DbDraw\DbDraw` using your _dbal connection_.
3. Choose your diagram size (`mini`, `midi`, `maxi`) and call `DbDraw::generatePuml`.
4. Then `DbDraw::generatePuml` will return a [PlantUML diagram](https://plantuml.com/ie-diagram).
5. Is up to you to convert the _puml_ diagram to another image format (e.g. _png_ or _svg_). I suggest to
   use [jawira/plantuml-client](https://github.com/jawira/plantuml-client).

Example:

```php
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\PlantUmlClient\{Client, Format};

// a. Some logic to retrieve $connection (\Doctrine\DBAL\Connection)
$connectionParams = ['url'    => 'mysql://admin:pass1234@127.0.0.1/db-draw',
                     'driver' => 'pdo_mysql'];
$connection = DriverManager::getConnection($connectionParams);

// b. Generating PlantUML diagram
$dbDiagram = new DbDraw($connection);
$puml      = $dbDiagram->generatePuml(DbDraw::MIDI);
file_put_contents('database.puml', $puml);

// c. Converting & saving png image
$client = new Client();
$png    = $client->generateImage($puml, Format::PNG);
file_put_contents('database.png', $png);
```

## Diagram sizes

```php
$dbDraw = new DbDraw($connection);
$mini   = $dbDraw->generatePuml(DbDraw::MINI); // only table names
$midi   = $dbDraw->generatePuml(DbDraw::MIDI); // like mini with columns
$maxi   = $dbDraw->generatePuml(DbDraw::MAXI); // like midi with views
```

![mini-diagram](resources/output/mini.png)

![midi-diagram](resources/output/midi.png)

![maxi-diagram](resources/output/maxi.png)

## Installing

```console
composer require jawira/db-draw
```

## Contributing

If you liked this project, ⭐ [star it on GitHub](https://github.com/jawira/db-draw).

## License

This library is licensed under the [MIT license](LICENSE.md).

***

## Packages from jawira

<dl>

<dt>
    <a href="https://packagist.org/packages/jawira/plantuml-client"> jawira/plantuml-client
    <img alt="GitHub stars" src="https://badgen.net/github/stars/jawira/plantuml-client?icon=github"/></a>
</dt>
<dd>Convert PlantUML diagrams into images.</dd>

<dt>
    <a href="https://packagist.org/packages/jawira/plantuml-encoding"> jawira/plantuml-encoding
    <img alt="GitHub stars" src="https://badgen.net/github/stars/jawira/plantuml-encoding?icon=github"/></a>
</dt>
<dd>PlantUML encoding functions.</dd>

<dt>
    <a href="https://packagist.org/packages/jawira/plantuml">jawira/plantuml
    <img alt="GitHub stars" src="https://badgen.net/github/stars/jawira/plantuml?icon=github"/></a>
</dt>
<dd>Provides PlantUML executable and plantuml.jar</dd>

<dt><a href="https://packagist.org/packages/jawira/">more...</a></dt>
</dl>

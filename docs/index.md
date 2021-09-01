
**`DbDraw` is a PHP library, it allows you to generate a ER diagram from an existing database.**

**You can generate diagram from different sizes and themes.**

![crt-amber](images/theme-crt-amber.png)

## Installing

```console
composer require jawira/db-draw
```

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

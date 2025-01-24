<?php

namespace Jawira\DbDrawTests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\DoctrineDiagramContracts\Size;
use PHPUnit\Framework\TestCase;
use function file_put_contents;

class ThemeTest extends TestCase
{

  private Connection $connection;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $connectionParams = [
      'user' => 'groot',
      'password' => 'groot',
      'host' => '127.0.0.1',
      'port' => 33060,
      'dbname' => 'institute',
      'charset' => 'utf8mb4',
      'driver' => 'pdo_mysql',
      'serverVersion' => '8.2',
    ];
    $this->connection = DriverManager::getConnection($connectionParams);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
  }

  /**
   * @covers       \Jawira\DbDraw\DbDraw
   * @covers       \Jawira\DbDraw\Relational\Column
   * @covers       \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers       \Jawira\DbDraw\Relational\Diagram\Maxi
   * @covers       \Jawira\DbDraw\Relational\Entity
   * @covers       \Jawira\DbDraw\Relational\Raw
   * @covers       \Jawira\DbDraw\Relational\Relationship
   * @covers       \Jawira\DbDraw\Relational\Views::__construct
   * @covers       \Jawira\DbDraw\Relational\Views::__toString
   * @covers       \Jawira\DbDraw\Relational\Views::generateHeaderAndFooter
   * @covers       \Jawira\DbDraw\Relational\Views::generateViews
   * @covers       \Jawira\DbDraw\Toolbox
   * @dataProvider themeProvider
   * @testdox      Diagram with theme $theme
   */
  public function testTheme($theme)
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(Size::Maxi, $theme);
    file_put_contents("./resources/output/theme-{$theme}.puml", $puml);
    $this->assertIsString($puml);
    $this->assertStringContainsString("!theme {$theme}", $puml);
  }

  public function themeProvider(): array
  {
    return [
      ['_none_'],
      ['amiga'],
      ['aws-orange'],
      ['black-knight'],
      ['bluegray'],
      ['blueprint'],
      ['carbon-gray'],
      ['cerulean'],
      ['cerulean-outline'],
      ['cloudscape-design'],
      ['crt-amber'],
      ['crt-green'],
      ['cyborg'],
      ['cyborg-outline'],
      ['hacker'],
      ['lightgray'],
      ['mars'],
      ['materia'],
      ['materia-outline'],
      ['metal'],
      ['mimeograph'],
      ['minty'],
      ['mono'],
      ['plain'],
      ['reddress-darkblue'],
      ['reddress-darkgreen'],
      ['reddress-darkorange'],
      ['reddress-darkred'],
      ['reddress-lightblue'],
      ['reddress-lightgreen'],
      ['reddress-lightorange'],
      ['reddress-lightred'],
      ['sandstone'],
      ['silver'],
      ['sketchy'],
      ['sketchy-outline'],
      ['spacelab'],
      ['spacelab-white'],
      ['superhero'],
      ['superhero-outline'],
      ['toy'],
      ['united'],
      ['vibrant'],
    ];
  }
}

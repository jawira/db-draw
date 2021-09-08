<?php

namespace Jawira\DbDrawTests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\DbDraw\Theme;
use PHPUnit\Framework\TestCase;
use function file_put_contents;
use const PHP_EOL;

class DiagramTest extends TestCase
{
  /**
   * @var Connection
   */
  protected $connection;
  protected $dbHost;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $this->dbHost     = getenv('DB_HOST') ?: 'mysql';
    $connectionParams = ['url' => "mysql://groot:groot@{$this->dbHost}/institute", 'driver' => 'pdo_mysql'];
    $this->connection = DriverManager::getConnection($connectionParams);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Relational\Diagram\Mini
   * @covers \Jawira\DbDraw\Relational\Entity
   * @covers \Jawira\DbDraw\Relational\Raw
   * @covers \Jawira\DbDraw\Relational\Relationship
   * @covers \Jawira\DbDraw\Toolbox
   */
  public function testMiniDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MINI);
    file_put_contents('./resources/output/mini.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringContainsString('title institute', $puml);
    $this->assertStringContainsString('entity Assistant', $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Relational\Column
   * @covers \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Relational\Diagram\Maxi
   * @covers \Jawira\DbDraw\Relational\Diagram\Midi
   * @covers \Jawira\DbDraw\Relational\Entity
   * @covers \Jawira\DbDraw\Relational\Raw
   * @covers \Jawira\DbDraw\Relational\Relationship
   * @covers \Jawira\DbDraw\Toolbox
   */
  public function testMidiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MIDI);
    file_put_contents('./resources/output/midi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringContainsString('faculty_id: integer', $puml);
    $this->assertStringContainsString('* pin: string', $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Relational\Column
   * @covers \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Relational\Diagram\Maxi
   * @covers \Jawira\DbDraw\Relational\Entity
   * @covers \Jawira\DbDraw\Relational\Raw
   * @covers \Jawira\DbDraw\Relational\Relationship
   * @covers \Jawira\DbDraw\Relational\Views
   * @covers \Jawira\DbDraw\Toolbox
   */
  public function testMaxiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MAXI);
    file_put_contents('./resources/output/maxi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringContainsString('entity introductory_courses', $puml);
    $this->assertStringContainsString('entity students_with_no_card', $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
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
    $puml   = $drawer->generatePuml(DbDraw::MAXI, $theme);
    file_put_contents("./resources/output/theme-{$theme}.puml", $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringContainsString("!theme {$theme}", $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }

  public function themeProvider(): array
  {
    return [[Theme::AMIGA],
            [Theme::BLACK_KNIGHT],
            [Theme::BLUEGRAY],
            [Theme::BLUEPRINT],
            [Theme::CERULEAN],
            [Theme::CERULEAN_OUTLINE],
            [Theme::CRT_AMBER],
            [Theme::CRT_GREEN],
            [Theme::CYBORG],
            [Theme::CYBORG_OUTLINE],
            [Theme::HACKER],
            [Theme::LIGHTGRAY],
            [Theme::MATERIA],
            [Theme::MATERIA_OUTLINE],
            [Theme::METAL],
            [Theme::MIMEOGRAPH],
            [Theme::MINTY],
            [Theme::PLAIN],
            [Theme::RESUME_LIGHT],
            [Theme::SANDSTONE],
            [Theme::SILVER],
            [Theme::SKETCHY],
            [Theme::SKETCHY_OUTLINE],
            [Theme::SPACELAB],
            [Theme::SUPERHERO],
            [Theme::SUPERHERO_OUTLINE],
            [Theme::TOY],
            [Theme::UNITED],
            [Theme::VIBRANT],];
  }
}

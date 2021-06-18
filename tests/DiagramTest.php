<?php

namespace Jawira\DbDraw\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use PHPUnit\Framework\TestCase;

class DiagramTest extends TestCase
{
  /**
   * @var Connection
   */
  protected $connection;
  protected $dbHost;

  public function __construct()
  {
    parent::__construct();
    $this->dbHost     = getenv('DB_HOST') ?: 'mysql';
    $connectionParams = ['url' => "mysql://root:groot@{$this->dbHost}/db-draw",];
    $this->connection = DriverManager::getConnection($connectionParams);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw::draw
   */
  public function testMiniDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MINI);
    file_put_contents('./resources/output/mini.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw::draw
   */
  public function testMidiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MIDI);
    file_put_contents('./resources/output/midi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }
  /**
   * @covers \Jawira\DbDraw\DbDraw::draw
   */
  public function testMaxiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MAXI);
    file_put_contents('./resources/output/maxi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }
}

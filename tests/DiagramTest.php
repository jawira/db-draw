<?php

namespace Jawira\DbVisualizer\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbVisualizer\DbVisualizer;
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
    $connectionParams = ['url' => "mysql://root:groot@{$this->dbHost}/db-visualizer",];
    $this->connection = DriverManager::getConnection($connectionParams);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
  }

  /**
   * @covers \Jawira\DbVisualizer\DbVisualizer::draw
   */
  public function testDbVisualizer()
  {
    $drawer = new DbVisualizer($this->connection);
    $puml   = $drawer->generatePuml(DbVisualizer::MINI);
    file_put_contents("./resources/output/db-visualizer.puml", $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }
}

<?php

namespace Jawira\DbVisualizer\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Jawira\DbVisualizer\DbVisualizer;
use Jawira\DbVisualizer\Tests\Type\EnumType;
use PHPUnit\Framework\TestCase;

class DiagramTest extends TestCase
{
  /**
   * @var Connection
   */
  protected $connection;
  protected $dbHost;
  protected $dbSchema;

  public function __construct()
  {
    parent::__construct();
    $this->dbHost     = getenv('DB_HOST') ?: 'database';
    $this->dbSchema   = getenv('DB_SCHEMA') ?: 'sakila';
    $connectionParams = array(
      'url' => "mysql://root:groot@{$this->dbHost}/{$this->dbSchema}",
    );

    $this->connection = DriverManager::getConnection($connectionParams);
//    Type::addType('geometry', GeometryType::class);
    Type::addType('enum', EnumType::class);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('Enum', 'enum');
  }

  /**
   * @covers \Jawira\DbVisualizer\DbVisualizer::draw
   */
  public function testDbVisualizer()
  {
    $drawer = new DbVisualizer($this->connection);
    $puml   = $drawer->draw();
    file_put_contents("./resources/output/{$this->dbSchema}.puml", $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }
}

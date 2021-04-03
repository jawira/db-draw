<?php

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

  public function setUp(): void
  {
    $connectionParams = array(
      'url' => 'mysql://root:groot@database/employees',
    );

    $this->connection = DriverManager::getConnection($connectionParams);
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
    file_put_contents('./employees.puml', $puml);
    $this->assertIsString($puml);
    $this->assertStringContainsString('dept_manager', $puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }
}

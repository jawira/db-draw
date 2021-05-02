<?php


namespace Jawira\DbVisualizer\Relational\Diagram;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Jawira\DbVisualizer\Relational\Entity;
use Jawira\DbVisualizer\Relational\Raw;
use Jawira\DbVisualizer\Relational\Relationship;
use function array_map;
use function array_merge;
use function array_reduce;

abstract class AbstractDiagram
{

  /**
   * Things to put at the beginning of the diagram
   *
   * @var Raw[]
   */
  protected $beginning = [];

  /**
   * Things to put at the ending of the diagram
   *
   * @var Raw[]
   */
  protected $ending = [];

  /**
   * DB entities (tables)
   *
   * @var Entity[]
   */
  protected $entities = [];

  /**
   * DB relationships
   *
   * @var Relationship[]
   */
  protected $relationships = [];

  /**
   * @var Connection
   */
  protected $connection;

  /**
   * @return $this
   */
  abstract public function process();

  /**
   * @param Connection $connection
   * @return AbstractDiagram
   */
  public function setConnection(Connection $connection): AbstractDiagram
  {
    $this->connection = $connection;
    return $this;
  }

  /**
   * @param Table[] $tables
   */
  protected function generateEntities(array $tables): void
  {
    $createEntity = function (Table $table) {
      $entity = new Entity($table);
      $entity->generateHeaderAndFooter();
      return $entity;
    };

    $this->entities = array_map($createEntity, $tables);
  }

  /**
   * @param Table[] $tables
   */
  protected function generateRelationships(array $tables): void
  {
    $foreignKeys         = [];
    $retrieveForeignKeys = function (Table $table) use (&$foreignKeys) {
      $foreignKeys = array_merge($foreignKeys, $table->getForeignKeys());
    };
    array_map($retrieveForeignKeys, $tables);
    $createRelationship  = function (ForeignKeyConstraint $foreignKeyConstraint) {
      return new Relationship($foreignKeyConstraint);
    };
    $this->relationships = array_map($createRelationship, $foreignKeys);
  }

  protected function generateHeaderAndFooter(Connection $connection): void
  {
    $this->beginning[] = new Raw('@startuml');
    $this->beginning[] = new Raw('hide empty members');
    $this->beginning[] = new Raw('hide circle');
    $this->beginning[] = new Raw('skinparam ArrowColor #333');
    $this->beginning[] = new Raw('skinparam ArrowThickness 1.5');
    $this->beginning[] = new Raw('skinparam ClassBackgroundColor White-APPLICATION');
    $this->beginning[] = new Raw('skinparam ClassBorderColor LightSlateGray');
    $this->beginning[] = new Raw('skinparam ClassBorderThickness 1');
    $this->beginning[] = new Raw('skinparam MinClassWidth 150');
    $this->beginning[] = new Raw('skinparam LineType Ortho');
    $this->beginning[] = new Raw('skinparam Shadowing false');
    $this->beginning[] = new Raw('title ' . $connection->getDatabase());
    $this->ending[]    = new Raw('@enduml');
  }


  public function __toString(): string
  {
    $puml = array_reduce($this->beginning, '\\Jawira\\DbVisualizer\\Toolbox::reducer', '');
    $puml = array_reduce($this->entities, '\\Jawira\\DbVisualizer\\Toolbox::reducer', $puml);
    $puml = array_reduce($this->relationships, '\\Jawira\\DbVisualizer\\Toolbox::reducer', $puml);
    $puml = array_reduce($this->ending, '\\Jawira\\DbVisualizer\\Toolbox::reducer', $puml);

    return $puml;
  }
}

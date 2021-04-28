<?php


namespace Jawira\DbVisualizer;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Jawira\DbVisualizer\Element\ElementInterface;
use Jawira\DbVisualizer\Element\Entity;
use Jawira\DbVisualizer\Element\Raw;
use Jawira\DbVisualizer\Element\Relationship;
use function array_map;
use function array_merge;
use function array_reduce;
use function strval;

/**
 * Class Diagram
 *
 * @package Jawira\DbVisualizer
 */
class Diagram
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
   * @var string
   */
  protected $title;

  public function __construct(AbstractSchemaManager $schemaManager)
  {
    $this->retrieveEntities($schemaManager->listTables());
    $this->retrieveRelationships($schemaManager->listTables());
  }

  /**
   * @param Table[] $tables
   */
  protected function retrieveEntities(array $tables): void
  {
    $createEntity = function ($table) {
      return new Entity($table);
    };

    $this->entities = array_map($createEntity, $tables);
  }

  /**
   * @param Table[] $tables
   */
  protected function retrieveRelationships(array $tables): void
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

  public function __toString()
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
    $this->beginning[] = new Raw('title ' . $this->getTitle());
    $this->ending[]    = new Raw('@enduml');

    $puml = array_reduce($this->beginning, [static::class, 'reducer'], '');
    $puml = array_reduce($this->entities, [static::class, 'reducer'], $puml);
    $puml = array_reduce($this->relationships, [static::class, 'reducer'], $puml);
    $puml = array_reduce($this->ending, [static::class, 'reducer'], $puml);

    return $puml;
  }

  protected static function reducer(string $carry, ElementInterface $element): string
  {
    return $carry . strval($element);
  }

  /**
   * @return string
   */
  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @param string $title
   *
   * @return \Jawira\DbVisualizer\Diagram
   */
  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }
}

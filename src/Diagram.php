<?php


namespace Jawira\DbVisualizer;

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

  public function __construct()
  {
    $this->beginning[] = new Raw('@startuml');
    $this->beginning[] = new Raw('left to right direction');
    $this->beginning[] = new Raw('hide empty members');
    $this->beginning[] = new Raw('hide circle');
    $this->beginning[] = new Raw('skinparam ClassBorderColor #333');
    $this->beginning[] = new Raw('skinparam ArrowColor #333');
    $this->beginning[] = new Raw('skinparam ClassBackgroundColor AntiqueWhite');
    $this->beginning[] = new Raw('skinparam linetype ortho');
    $this->beginning[] = new Raw('skinparam shadowing false');
    $this->ending[]    = new Raw('@enduml');
  }

  /**
   * @param Table[] $tables
   */
  public function retrieveEntities(array $tables): void
  {
    $createEntity = function ($table) {
      return new Entity($table);
    };

    $this->entities = array_map($createEntity, $tables);
  }

  /**
   * @param Table[] $tables
   */
  public function retrieveRelationships(array $tables): void
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
}

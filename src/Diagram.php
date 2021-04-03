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
   * @var Raw[]
   */
  protected $header = [];

  /**
   * @var Raw[]
   */
  protected $footer = [];

  /**
   * @var Entity[]
   */
  protected $entities = [];

  /**
   * @var Relationship[]
   */
  protected $relationships = [];

  public function __construct()
  {
    $this->header[] = new Raw('@startuml');
    $this->header[] = new Raw('hide circle');
    $this->header[] = new Raw('skinparam linetype ortho');
    $this->header[] = new Raw('skinparam shadowing false');
    $this->footer[] = new Raw('@enduml');
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
    $puml = array_reduce($this->header, [static::class, 'reducer'], '');
    $puml = array_reduce($this->entities, [static::class, 'reducer'], $puml);
    $puml = array_reduce($this->relationships, [static::class, 'reducer'], $puml);
    $puml = array_reduce($this->footer, [static::class, 'reducer'], $puml);
    return $puml;
  }

  protected static function reducer(string $carry, ElementInterface $element): string
  {
    return $carry . strval($element);
  }
}

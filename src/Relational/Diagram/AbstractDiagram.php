<?php


namespace Jawira\DbDraw\Relational\Diagram;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Jawira\DbDraw\Relational\Entity;
use Jawira\DbDraw\Relational\Raw;
use Jawira\DbDraw\Relational\Relationship;
use Jawira\DbDraw\Relational\Views;
use function array_map;
use function array_merge;
use function array_reduce;
use function strval;

/**
 * @author  Jawira Portugal
 */
abstract class AbstractDiagram
{
  /**
   * Things to put at the beginning of the diagram
   *
   * @var \Jawira\DbDraw\Relational\Raw[]
   */
  protected $beginning = [];

  /**
   * Things to put at the ending of the diagram
   *
   * @var \Jawira\DbDraw\Relational\Raw[]
   */
  protected $ending = [];

  /**
   * DB entities (tables)
   *
   * @var \Jawira\DbDraw\Relational\Entity[]
   */
  protected $entities = [];

  /**
   * DB relationships
   *
   * @var \Jawira\DbDraw\Relational\Relationship[]
   */
  protected $relationships = [];

  /**
   * @var Connection
   */
  protected $connection;

  /**
   * @var \Jawira\DbDraw\Relational\Views
   */
  protected $views;

  /**
   * @var null|string
   */
  protected $theme;

  /**
   * @return $this
   */
  abstract public function process();

  /**
   * @param Connection $connection
   *
   * @return AbstractDiagram
   */
  public function setConnection(Connection $connection): AbstractDiagram
  {
    $this->connection = $connection;

    return $this;
  }

  public function setTheme(?string $theme): AbstractDiagram
  {
    $this->theme = $theme;

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
    $createRelationship  = fn(ForeignKeyConstraint $foreignKeyConstraint) => new Relationship($foreignKeyConstraint);
    $this->relationships = array_map($createRelationship, $foreignKeys);
  }

  protected function generateHeaderAndFooter(Connection $connection, ?string $theme=null): self
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
    $this->beginning[] = new Raw('skinparam PackageBackgroundColor #eee');
    $this->beginning[] = new Raw('skinparam PackageBorderColor #eee');
    $this->beginning[] = new Raw('skinparam PackageFontStyle normal');
    $this->beginning[] = new Raw('title ' . $connection->getDatabase());
    if ($theme) {
      $this->beginning[] = new Raw('!theme ' . $theme);
    }
    $this->ending[] = new Raw('@enduml');

    return $this;
  }

  /**
   * @param \Doctrine\DBAL\Schema\View[] $views
   */
  public function generateViews(array $views): self
  {
    $this->views = new Views($views);
    $this->views->generateHeaderAndFooter()->generateViews();

    return $this;
  }

  public function __toString(): string
  {
    $puml = array_reduce($this->beginning, '\\Jawira\\DbDraw\\Toolbox::reducer', '');
    $puml = array_reduce($this->entities, '\\Jawira\\DbDraw\\Toolbox::reducer', $puml);
    $puml = array_reduce($this->relationships, '\\Jawira\\DbDraw\\Toolbox::reducer', $puml);
    $puml .= strval($this->views);
    $puml = array_reduce($this->ending, '\\Jawira\\DbDraw\\Toolbox::reducer', $puml);

    return $puml;
  }
}

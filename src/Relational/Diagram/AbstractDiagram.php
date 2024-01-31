<?php declare(strict_types=1);


namespace Jawira\DbDraw\Relational\Diagram;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Jawira\DbDraw\Relational\Entity;
use Jawira\DbDraw\Relational\Raw;
use Jawira\DbDraw\Relational\Relationship;
use Jawira\DbDraw\Relational\Views;
use function array_map;
use function array_reduce;
use function strval;

/**
 * @author  Jawira Portugal
 */
abstract class AbstractDiagram implements \Stringable
{
  /**
   * Things to put at the beginning of the diagram
   *
   * @var \Jawira\DbDraw\Relational\Raw[]
   */
  protected array $beginning = [];

  /**
   * Things to put at the ending of the diagram
   *
   * @var \Jawira\DbDraw\Relational\Raw[]
   */
  protected array $ending = [];

  /**
   * DB entities (tables)
   *
   * @var \Jawira\DbDraw\Relational\Entity[]
   */
  protected array $entities = [];

  /**
   * DB relationships
   *
   * @var \Jawira\DbDraw\Relational\Relationship[]
   */
  protected array $relationships = [];
  protected Connection $connection;
  protected ?Views $views = null;
  protected ?string $theme = null;

  /**
   * @return $this
   */
  abstract public function process();

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
    foreach ($tables as $table) {
      foreach ($table->getForeignKeys() as $foreignKey) {
        $this->relationships[] = new Relationship($table, $foreignKey);
      }
    }
  }

  protected function generateHeaderAndFooter(Connection $connection, ?string $theme = null): self
  {
    $this->beginning[] = new Raw('@startuml');
    $this->beginning[] = new Raw('hide empty members');
    $this->beginning[] = new Raw('hide circle');
    $this->beginning[] = new Raw('skinparam MinClassWidth 150');
    $this->beginning[] = new Raw('skinparam LineType Ortho');
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

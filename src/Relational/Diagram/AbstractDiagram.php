<?php declare(strict_types=1);


namespace Jawira\DbDraw\Relational\Diagram;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use Jawira\DbDraw\Relational\Entity;
use Jawira\DbDraw\Relational\Raw;
use Jawira\DbDraw\Relational\Relationship;
use Jawira\DbDraw\Relational\Views;
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
  /**
   * List of tables and views to ignore.
   *
   * @var string[]
   */
  protected array $exclude = [];
  protected ?Views $views = null;
  protected string $theme;

  public function __construct(protected readonly Connection $connection)
  {
  }

  /**
   * @return $this
   */
  abstract public function process();

  public function setTheme(string $theme): self
  {
    $this->theme = $theme;

    return $this;
  }

  /**
   * @param string[] $exclude
   */
  public function setExclude(array $exclude): self
  {
    $this->exclude = $exclude;

    return $this;
  }

  /**
   * @param Table[] $tables
   */
  protected function generateEntities(array $tables): void
  {
    foreach ($tables as $table) {
      if ($this->skipTable($table)) {
        continue;
      }
      $entity = new Entity($table);
      $entity->generateHeaderAndFooter();

      $this->entities[] = $entity;
    }
  }

  /**
   * @param Table[] $tables
   */
  protected function generateRelationships(array $tables): void
  {
    foreach ($tables as $table) {
      if ($this->skipTable($table)) {
        continue;
      }
      foreach ($table->getForeignKeys() as $foreignKey) {
        if ($this->skipForeignKey($foreignKey)) {
          continue;
        }
        $this->relationships[] = new Relationship($table, $foreignKey);
      }
    }
  }

  protected function generateHeaderAndFooter(Connection $connection, string $theme): self
  {
    $this->beginning[] = new Raw('@startuml');
    $this->beginning[] = new Raw('hide empty members');
    $this->beginning[] = new Raw('hide circle');
    $this->beginning[] = new Raw('skinparam MinClassWidth 150');
    $this->beginning[] = new Raw('skinparam LineType Ortho');
    $this->beginning[] = new Raw('title ' . $connection->getDatabase());
    $this->beginning[] = new Raw('!theme ' . $theme);
    $this->ending[]    = new Raw('@enduml');

    return $this;
  }

  /**
   * @param View[] $views
   */
  public function generateViews(array $views): self
  {
    $views       = array_filter($views, fn(View $v) => !$this->skipView($v));
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

  /**
   * Tells if provided table must be hidden in diagram.
   *
   * This function is supposed to be used with "exclude" feature.
   */
  protected function skipTable(Table $table): bool
  {
    return in_array($table->getName(), $this->exclude, true);
  }

  /**
   * Tells if foreign table name must be hidden in diagram.
   */
  protected function skipForeignKey(ForeignKeyConstraint $foreignKey): bool
  {
    return in_array($foreignKey->getForeignTableName(), $this->exclude, true);
  }

  protected function skipView(View $view): bool
  {
    return in_array($view->getName(), $this->exclude, true);
  }
}

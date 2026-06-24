<?php declare(strict_types=1);


namespace Jawira\DbDraw\Service;


use Doctrine\DBAL\Connection;
use Jawira\DbDraw\Element\Entity;
use Jawira\DbDraw\Element\Raw;
use Jawira\DbDraw\Element\Relationship;
use Jawira\DbDraw\Element\View;

/**
 * Convert DoctrineORM elements to PlantUML format.
 *
 * @author  Jawira Portugal
 */
class PlantUmlWriter
{
  private ElementFilter $elementFilter;

  public function __construct(private readonly Connection $connection)
  {
    $this->elementFilter = new ElementFilter();
  }

  /**
   * @param string[] $include
   * @param string[] $exclude
   * @return \Jawira\DbDraw\Element\Entity[]
   */
  public function generateEntities(array $include, array $exclude): array
  {
    $entities = [];
    $tables   = $this->connection->createSchemaManager()->listTables();
    foreach ($tables as $table) {
      if ($this->elementFilter->skipTable($table, $include, $exclude)) {
        continue;
      }
      $entity = new Entity($table);
      $entity->generateHeaderAndFooter();

      $entities[] = $entity;
    }

    return $entities;
  }

  /**
   * @param string[] $include
   * @param string[] $exclude
   * @return \Jawira\DbDraw\Element\Relationship[]
   */
  public function generateRelationships(array $include, array $exclude): array
  {
    $relationships = [];
    $tables        = $this->connection->createSchemaManager()->listTables();
    foreach ($tables as $table) {
      if ($this->elementFilter->skipTable($table, $include, $exclude)) {
        continue;
      }
      foreach ($table->getForeignKeys() as $foreignKey) {
        if ($this->elementFilter->skipForeignKey($foreignKey, $include, $exclude)) {
          continue;
        }
        $relationships[] = new Relationship($table, $foreignKey);
      }
    }

    return $relationships;
  }

  /**
   * @return \Jawira\DbDraw\Element\Raw[]
   */
  public function generateHeader(string $theme): array
  {
    $lines   = [];
    $lines[] = new Raw('@startuml');
    $lines[] = new Raw('hide empty members');
    $lines[] = new Raw('hide circle');
    $lines[] = new Raw('skinparam MinClassWidth 150');
    $lines[] = new Raw('skinparam LineType Ortho');
    $lines[] = new Raw('title ' . $this->connection->getDatabase());
    $lines[] = new Raw('!theme ' . $theme);

    return $lines;
  }

  /**
   * @return \Jawira\DbDraw\Element\Raw[]
   */
  public function generateFooter(): array
  {
    return [new Raw('@enduml')];
  }

  /**
   * @param string[] $include
   * @param string[] $exclude
   * @return \Jawira\DbDraw\Element\ElementInterface[]
   */
  public function generateViews(array $include, array $exclude): array
  {
    $dbalViews = $this->connection->createSchemaManager()->listViews();
    $views     = [];
    $views[]   = new Raw('package "views" {');
    foreach ($dbalViews as $dbalView) {
      if ($this->elementFilter->skipView($dbalView, $include, $exclude)) {
        continue;
      }
      $views[] = new View($dbalView);
    }
    $views[] = new Raw('}');

    return $views;
  }
}

<?php


namespace Jawira\DbVisualizer\Relational\Diagram;


use Jawira\DbVisualizer\Relational\Entity;
use function array_map;

class Maxi extends AbstractDiagram
{
  /**
   * @return $this
   */
  public function process()
  {
    $this->generateHeaderAndFooter($this->connection);
    $this->generateEntities($this->connection->getSchemaManager()->listTables());
    array_map(function (Entity $entity) {
      $entity->generateColumns();
    }, $this->entities);
    $this->generateRelationships($this->connection->getSchemaManager()->listTables());
    $this->generateViews($this->connection->getSchemaManager()->listViews());

    return $this;
  }
}

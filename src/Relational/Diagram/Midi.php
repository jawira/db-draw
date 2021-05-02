<?php


namespace Jawira\DbVisualizer\Relational\Diagram;


use Jawira\DbVisualizer\Relational\Entity;
use function array_map;

class Midi extends AbstractDiagram
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

    return $this;
  }
}

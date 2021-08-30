<?php


namespace Jawira\DbDraw\Relational\Diagram;


use Jawira\DbDraw\Relational\Entity;
use function array_map;

/**
 * @author  Jawira Portugal
 */
class Midi extends AbstractDiagram
{

  /**
   * @return $this
   */
  public function process()
  {
    $this->generateHeaderAndFooter($this->connection, $this->theme);
    $this->generateEntities($this->connection->getSchemaManager()->listTables());
    array_map(function (Entity $entity) {
      $entity->generateColumns();
    }, $this->entities);
    $this->generateRelationships($this->connection->getSchemaManager()->listTables());

    return $this;
  }
}

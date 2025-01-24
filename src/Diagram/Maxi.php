<?php declare(strict_types=1);

namespace Jawira\DbDraw\Diagram;

use Jawira\DbDraw\Element\Entity;
use function array_map;

/**
 * @author  Jawira Portugal
 */
class Maxi extends AbstractDiagram
{
  public function process(): self
  {
    $this->generateHeaderAndFooter($this->connection, $this->theme);
    $this->generateEntities($this->connection->createSchemaManager()->listTables());
    array_map(function (Entity $entity) {
      $entity->generateColumns();
    }, $this->entities);
    $this->generateRelationships($this->connection->createSchemaManager()->listTables());
    $this->generateViews($this->connection->createSchemaManager()->listViews());

    return $this;
  }
}

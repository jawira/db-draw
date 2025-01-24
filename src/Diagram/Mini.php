<?php declare(strict_types=1);

namespace Jawira\DbDraw\Diagram;

/**
 * @author  Jawira Portugal
 */
class Mini extends AbstractDiagram
{
  public function process(): self
  {
    $this->generateHeaderAndFooter($this->connection, $this->theme);
    $this->generateEntities($this->connection->createSchemaManager()->listTables());
    $this->generateRelationships($this->connection->createSchemaManager()->listTables());

    return $this;
  }
}

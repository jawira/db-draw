<?php

namespace Jawira\DbDraw\Relational\Diagram;

/**
 * @author  Jawira Portugal
 */
class Mini extends AbstractDiagram
{
  /**
   * @return $this
   */
  public function process()
  {
    $this->generateHeaderAndFooter($this->connection, $this->theme);
    $this->generateEntities($this->connection->getSchemaManager()->listTables());
    $this->generateRelationships($this->connection->getSchemaManager()->listTables());

    return $this;
  }
}

<?php


namespace Jawira\DbVisualizer\Relational\Diagram;


class Mini extends AbstractDiagram
{
  public function process()
  {
    $this->generateHeaderAndFooter($this->connection);
    $this->generateEntities($this->connection->getSchemaManager()->listTables());
    $this->generateRelationships($this->connection->getSchemaManager()->listTables());

    return $this;
  }
}

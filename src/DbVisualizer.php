<?php

namespace Jawira\DbVisualizer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use function strval;

class DbVisualizer
{
  /**
   * @var Connection
   */
  protected $connection;

  public function __construct(Connection $connection)
  {
    $this->connection = $connection;
  }

  /**
   * @throws \Doctrine\DBAL\Exception
   * @return string
   */
  public function generatePuml()
  {
    /** @var AbstractSchemaManager $schemaManager */
    $schemaManager = $this->connection->getSchemaManager();
    $diagram       = new Diagram($schemaManager);
    $diagram->setTitle($this->connection->getDatabase());

    return strval($diagram);
  }
}

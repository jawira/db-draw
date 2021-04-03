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
   * @return string
   * @throws \Doctrine\DBAL\Exception
   */
  public function draw()
  {
    /** @var AbstractSchemaManager $schemaManager */
    $schemaManager = $this->connection->getSchemaManager();
    $diagram       = new Diagram();
    $diagram->retrieveEntities($schemaManager->listTables());
    $diagram->retrieveRelationships($schemaManager->listTables());

    return strval($diagram);
  }

}

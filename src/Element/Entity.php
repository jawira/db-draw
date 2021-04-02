<?php

namespace Jawira\DbVisualizer\Element;

use \Doctrine\DBAL\Schema\Table;
use function sprintf;

class Entity
{
  /**
   * @var \Doctrine\DBAL\Schema\Table
   */
  protected $table;

  public function __construct(Table $table)
  {
    $this->table = $table;
  }

  public function __toString()
  {
    return sprintf('entity %s', $this->table->getName());
  }
}

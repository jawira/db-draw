<?php

namespace Jawira\DbVisualizer\Element;

use \Doctrine\DBAL\Schema\Table;
use function sprintf;
use const PHP_EOL;

class Entity implements ElementInterface
{
  /**
   * @var \Doctrine\DBAL\Schema\Table
   */
  protected $table;

  public function __construct(Table $table)
  {
    $this->table = $table;
  }

  public function __toString(): string
  {
    return sprintf('entity %s { }', $this->table->getName()) . PHP_EOL;
  }
}

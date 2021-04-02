<?php

namespace Jawira\DbVisualizer\Element;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use function sprintf;
use const PHP_EOL;

class Relationship implements ElementInterface
{
  /**
   * @var \Doctrine\DBAL\Schema\Table
   */
  protected $table;

  /**
   * @var ForeignKeyConstraint
   */
  protected $foreignKeyConstraint;


  public function __construct(ForeignKeyConstraint $foreignKeyConstraint)
  {
    $this->foreignKeyConstraint = $foreignKeyConstraint;
  }

  public function __toString(): string
  {
    return sprintf('%s }-- %s', $this->foreignKeyConstraint->getLocalTableName(), $this->foreignKeyConstraint->getForeignTableName()) . PHP_EOL;;
  }
}

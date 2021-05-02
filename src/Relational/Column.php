<?php

namespace Jawira\DbVisualizer\Relational;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;

use function vsprintf;
use const PHP_EOL;

/**
 * @package Jawira\DbVisualizer\Relational
 */
class Column implements ElementInterface
{
  /**
   * @var DoctrineColumn
   */
  protected $doctrineColumn;

  public function __construct(DoctrineColumn $doctrineColumn)
  {
    $this->doctrineColumn = $doctrineColumn;
  }

  public function __toString(): string
  {
    $format = '%s %s: %s';
    $chunks = [$this->doctrineColumn->getNotnull() ? '*' : '',
               $this->doctrineColumn->getName(),
               $this->doctrineColumn->getType()->getName(),];

    return vsprintf($format, $chunks) . PHP_EOL;
  }
}

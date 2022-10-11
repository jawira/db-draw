<?php

namespace Jawira\DbDraw\Relational;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;

use function vsprintf;
use const PHP_EOL;

/**
 * @author  Jawira Portugal
 */
class Column implements ElementInterface
{
  protected DoctrineColumn $doctrineColumn;

  public function __construct(DoctrineColumn $doctrineColumn)
  {
    $this->doctrineColumn = $doctrineColumn;
  }

  public function __toString(): string
  {
    $format = '%s %s: %s';
    $chunks = [
      $this->doctrineColumn->getNotnull() ? '*' : '',
      $this->doctrineColumn->getName(),
      $this->doctrineColumn->getType()->getName(),
    ];

    return vsprintf($format, $chunks) . PHP_EOL;
  }
}

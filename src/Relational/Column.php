<?php declare(strict_types=1);

namespace Jawira\DbDraw\Relational;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;

use function vsprintf;
use const PHP_EOL;

/**
 * @author  Jawira Portugal
 */
class Column implements ElementInterface
{
  public function __construct(protected DoctrineColumn $doctrineColumn)
  {
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

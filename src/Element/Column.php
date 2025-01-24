<?php declare(strict_types=1);

namespace Jawira\DbDraw\Element;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Doctrine\DBAL\Types\Type;

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
      Type::getTypeRegistry()->lookupName($this->doctrineColumn->getType()),
    ];

    return vsprintf($format, $chunks) . PHP_EOL;
  }
}

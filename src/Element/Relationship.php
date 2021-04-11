<?php

namespace Jawira\DbVisualizer\Element;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Index;
use function array_filter;
use function array_intersect;
use function array_search;
use function count;
use function Jawira\TheLostFunctions\array_search_callback;
use function sprintf;
use function vsprintf;
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
    $this->table                = $foreignKeyConstraint->getLocalTable();
    $this->foreignKeyConstraint = $foreignKeyConstraint;
  }

  public function isOneToOne(): bool
  {
    $localColumns = $this->foreignKeyConstraint->getColumns();

    // Find indexes for FK constraint only
    $filterLocalIndexes = function (Index $index) use ($localColumns) {
      return $localColumns == $index->getColumns();
    };

    $localIndexes = array_filter($this->table->getIndexes(), $filterLocalIndexes);

    $findUnique = function (Index $index) {
      if ($index->isPrimary()) {
        return false;
      }


      return $index->isUnique();
    };

    $uniqueIndex = array_search_callback($localIndexes, $findUnique);

    return $uniqueIndex instanceof Index;
  }

  public function __toString(): string
  {
    $chuncks                 = [];
    $chuncks['localTable']   = $this->foreignKeyConstraint->getLocalTableName();
    $chuncks['localMax']     = $this->isOneToOne() ? '|' : '}';
    $chuncks['localMin']     = '|';
    $chuncks['foreignTable'] = $this->foreignKeyConstraint->getForeignTableName();

    return vsprintf('%s %s%s-- %s', $chuncks) . PHP_EOL;
  }
}

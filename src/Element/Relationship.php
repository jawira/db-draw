<?php declare(strict_types=1);

namespace Jawira\DbDraw\Element;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use function array_filter;
use function array_reduce;
use function Jawira\TheLostFunctions\array_search_callback;
use function vsprintf;
use const PHP_EOL;

/**
 * @author  Jawira Portugal
 */
class Relationship implements ElementInterface
{
  public function __construct(protected Table $table, protected ForeignKeyConstraint $foreignKeyConstraint)
  {
  }

  protected function fkIsUnique(): bool
  {
    $localColumns = $this->foreignKeyConstraint->getLocalColumns();

    // Find indexes for FK constraint only
    $filterLocalIndexes = fn(Index $index): bool => $localColumns === $index->getColumns();

    $localIndexes = array_filter($this->table->getIndexes(), $filterLocalIndexes);

    $findUnique = function (Index $index): bool {
      if ($index->isPrimary()) {
        return false;
      }

      return $index->isUnique();
    };

    $uniqueIndex = array_search_callback($localIndexes, $findUnique);

    return $uniqueIndex instanceof Index;
  }

  protected function fkIsNullable(): bool
  {
    $localColumns = $this->foreignKeyConstraint->getLocalColumns();

    $reducer = function (bool $carry, string $column): bool {
      $isNullable = !$this->table->getColumn($column)->getNotnull();

      return $carry && $isNullable;
    };

    return array_reduce($localColumns, $reducer, true);
  }

  public function __toString(): string
  {
    $chuncks                 = [];
    $chuncks['localTable']   = $this->table->getName();
    $chuncks['localMax']     = $this->fkIsUnique() ? '|' : '}';
    $chuncks['remoteMin']    = $this->fkIsNullable() ? 'o' : '|';
    $chuncks['foreignTable'] = $this->foreignKeyConstraint->getForeignTableName();

    return vsprintf('%s %so--%s| %s', $chuncks) . PHP_EOL;
  }
}

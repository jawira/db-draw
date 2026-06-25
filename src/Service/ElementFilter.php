<?php declare(strict_types=1);

namespace Jawira\DbDraw\Service;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use function boolval;
use function count;
use function fnmatch;
use const FNM_NOESCAPE;

/**
 * Contains the logic to filter tables.
 *
 * @author  Jawira Portugal
 */
class ElementFilter
{
  /**
   * Tells if provided table must be hidden in diagram.
   *
   * This function is supposed to be used with "exclude" feature.
   *
   * @param string[] $include
   * @param string[] $exclude
   */
  public function skipTable(Table $element, array $include, array $exclude): bool
  {
    return $this->skipElement($element->getName(), $include, $exclude);
  }

  /**
   * Tells if foreign table name must be hidden in diagram.
   *
   * @param string[] $include
   * @param string[] $exclude
   */
  public function skipForeignKey(ForeignKeyConstraint $foreignKey, array $include, array $exclude): bool
  {
    return $this->skipElement($foreignKey->getForeignTableName(), $include, $exclude);
  }

  /**
   * @param string[] $include
   * @param string[] $exclude
   */
  public function skipView(View $view, array $include, array $exclude): bool
  {
    return $this->skipElement($view->getName(), $include, $exclude);
  }

  /**
   * @param string[] $include
   * @param string[] $exclude
   */
  private function skipElement(string $elementName, array $include, array $exclude): bool
  {
    // This variable exists to avoid automatic refactoring by tools like PHPStan
    // and Rector, allowing to see "Include" and "Exclude" blocks clearly.
    $defaultValue = false;

    // Include
    $includeHasContent = boolval(count($include));
    $elementInInclude  = $this->isElementInArray($elementName, $include);
    if ($includeHasContent && !$elementInInclude) {
      return true;
    }

    // Exclude
    $excludeHasContent = boolval(count($exclude));
    $elementInExclude  = $this->isElementInArray($elementName, $exclude);
    if ($excludeHasContent && $elementInExclude) {
      return true;
    }

    return $defaultValue;
  }

  /**
   * Checks if an element matches a table pattern.
   *
   * @param string[] $tables
   */
  private function isElementInArray(string $elementName, array $tables): bool
  {
    foreach ($tables as $table) {
      if (fnmatch($table, $elementName, FNM_NOESCAPE)) {
        return true;
      }
    }

    return false;
  }
}

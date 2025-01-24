<?php declare(strict_types=1);

namespace Jawira\DbDraw\Service;

use Jawira\DbDraw\Element\ElementInterface;
use function strval;

/**
 * @author  Jawira Portugal
 */
class Toolbox
{
  /**
   * Callback for `array_reduce()`
   */
  public static function reducer(string $carry, ElementInterface $element): string
  {
    return $carry . strval($element);
  }
}

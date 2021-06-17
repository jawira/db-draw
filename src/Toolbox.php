<?php


namespace Jawira\DbDraw;


use Jawira\DbDraw\Relational\ElementInterface;
use function strval;

class Toolbox
{
  /**
   * Callback for `array_reduce()`
   *
   * @param string           $carry
   * @param ElementInterface $element
   * @return string
   */
  public static function reducer(string $carry, ElementInterface $element): string
  {
    return $carry . strval($element);
  }
}

<?php declare(strict_types=1);

namespace Jawira\DbDraw\Service;

use Jawira\DbDraw\Element\ElementInterface;
use function strval;

/**
 * Toolbox.
 *
 * @author  Jawira Portugal
 */
class Toolbox
{
  /**
   * Takes an array of {@see \Jawira\DbDraw\Element\ElementInterface} objects and convert them to PlantUML code.
   *
   * @param \Jawira\DbDraw\Element\ElementInterface[] $elements
   */
  public function reduceElements(array $elements): string
  {
    $reducer = function (string $carry, ElementInterface $component): string {
      return $carry . strval($component);
    };

    return array_reduce($elements, $reducer, '');
  }
}

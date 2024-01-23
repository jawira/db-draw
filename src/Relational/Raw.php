<?php declare(strict_types=1);

namespace Jawira\DbDraw\Relational;

use const PHP_EOL;

/**
 * Raw element is printed as is.
 *
 * @author  Jawira Portugal
 */
class Raw implements ElementInterface
{
  /**
   * Raw constructor.
   */
  public function __construct(protected string $raw)
  {
  }

  public function __toString(): string
  {
    return $this->raw . PHP_EOL;
  }
}

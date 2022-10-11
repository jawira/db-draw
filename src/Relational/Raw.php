<?php

namespace Jawira\DbDraw\Relational;

use const PHP_EOL;

/**
 * Raw element is printed as is.
 *
 * @author  Jawira Portugal
 */
class Raw implements ElementInterface
{
  protected string $raw;

  /**
   * Raw constructor.
   * @param string $raw
   */
  public function __construct(string $raw)
  {
    $this->raw = $raw;
  }

  public function __toString(): string
  {
    return $this->raw . PHP_EOL;
  }
}

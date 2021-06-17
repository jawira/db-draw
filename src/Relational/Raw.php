<?php

namespace Jawira\DbDraw\Relational;

use const PHP_EOL;

/**
 * Raw element is printed as is.
 *
 * @package Jawira\DbDraw\Relational
 */
class Raw implements ElementInterface
{
  /**
   * @var string
   */
  protected $raw;

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

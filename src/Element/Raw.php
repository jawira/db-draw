<?php


namespace Jawira\DbVisualizer\Element;


use function assert;
use function is_string;

/**
 * Raw element
 *
 * Raw element is printed as is.
 *
 * @package Jawira\DbVisualizer\Element
 */
class Raw
{
  /**
   * @var string
   */
  protected $raw;

  /**
   * Raw constructor.
   * @param string $raw
   */
  public function __construct($raw)
  {
    assert(is_string($raw));
    $this->raw = $raw;
  }

  public function __toString()
  {
    return $this->raw;
  }
}

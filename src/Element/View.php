<?php declare(strict_types=1);

namespace Jawira\DbDraw\Element;

use Doctrine\DBAL\Schema\View as DbalView;

/**
 * @author  Jawira Portugal
 */
class View implements ElementInterface
{
  public function __construct(private readonly DbalView $view)
  {
  }

  public function __toString(): string
  {
    return sprintf('entity %s { }', $this->view->getName()) . PHP_EOL;
  }
}

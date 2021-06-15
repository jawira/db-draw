<?php

namespace Jawira\DbVisualizer\Relational;

use Doctrine\DBAL\Schema\View;
use function array_map;
use function array_reduce;
use function strval;

/**
 * Raw element is printed as is.
 *
 * @package Jawira\DbVisualizer\Relational
 */
class Views implements ElementInterface
{
  /**
   * @var \Doctrine\DBAL\Schema\View[]
   */
  protected $doctrineViews;
  /**
   * @var \Jawira\DbVisualizer\Relational\Raw
   */
  protected $header;
  /**
   * @var \Jawira\DbVisualizer\Relational\Raw
   */
  protected $footer;
  /**
   * @var \Jawira\DbVisualizer\Relational\Raw[]
   */
  protected $views = [];

  /**
   * Views constructor.
   *
   * @param \Doctrine\DBAL\Schema\View[] $views
   */
  public function __construct(array $views)
  {
    $this->doctrineViews = $views;
  }

  public function generateHeaderAndFooter(): self
  {
    $this->header = new Raw('package "views" {');
    $this->footer = new Raw('}');

    return $this;
  }

  public function generateViews(): self
  {
    $this->views = array_map(function (View $view) {
      return new Raw(sprintf('entity %s { }', $view->getName()));
    }, $this->doctrineViews);

    return $this;
  }

  public function __toString(): string
  {
    $puml = strval($this->header);
    $puml = array_reduce($this->views, '\\Jawira\\DbVisualizer\\Toolbox::reducer', $puml);
    $puml .= strval($this->footer);

    return $puml;
  }
}

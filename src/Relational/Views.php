<?php

namespace Jawira\DbDraw\Relational;

use Doctrine\DBAL\Schema\View;
use function array_map;
use function array_reduce;
use function strval;

/**
 * @author  Jawira Portugal
 */
class Views implements ElementInterface
{
  /**
   * @var \Doctrine\DBAL\Schema\View[]
   */
  protected $doctrineViews;
  /**
   * @var \Jawira\DbDraw\Relational\Raw
   */
  protected $header;
  /**
   * @var \Jawira\DbDraw\Relational\Raw
   */
  protected $footer;
  /**
   * @var \Jawira\DbDraw\Relational\Raw[]
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
    $puml = array_reduce($this->views, '\\Jawira\\DbDraw\\Toolbox::reducer', $puml);
    $puml .= strval($this->footer);

    return $puml;
  }
}

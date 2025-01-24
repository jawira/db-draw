<?php declare(strict_types=1);

namespace Jawira\DbDraw\Element;

use Doctrine\DBAL\Schema\View;
use Jawira\DbDraw\Service\Toolbox;
use function array_map;
use function array_reduce;
use function strval;

/**
 * @author  Jawira Portugal
 */
class Views implements ElementInterface
{
  /** @var \Jawira\DbDraw\Element\Raw[] */
  protected array $views = [];
  protected Raw $header;
  protected Raw $footer;


  /**
   * Views constructor.
   *
   * @param \Doctrine\DBAL\Schema\View[] $doctrineViews
   */
  public function __construct(protected array $doctrineViews)
  {
  }

  public function generateHeaderAndFooter(): self
  {
    $this->header = new Raw('package "views" {');
    $this->footer = new Raw('}');

    return $this;
  }

  public function generateViews(): self
  {
    $this->views = array_map(fn(View $view) => new Raw(sprintf('entity %s { }', $view->getName())), $this->doctrineViews);

    return $this;
  }

  public function __toString(): string
  {
    $puml = strval($this->header);
    $puml = array_reduce($this->views, Toolbox::reducer(...), $puml);
    $puml .= strval($this->footer);

    return $puml;
  }
}

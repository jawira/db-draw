<?php declare(strict_types=1);

namespace Jawira\DbDraw\Relational;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use \Doctrine\DBAL\Schema\Table;
use function array_filter;
use function array_map;
use function array_merge;
use function array_reduce;
use function in_array;
use function sprintf;
use function strval;

/**
 * @author  Jawira Portugal
 */
class Entity implements ElementInterface
{
  /** @var ElementInterface[] */
  protected array $columns = [];
  protected Raw $header;
  protected Raw $footer;

  public function __construct(protected Table $table)
  {
  }

  public function generateHeaderAndFooter(): self
  {
    $this->header = new Raw(sprintf('entity %s {', $this->table->getName()));
    $this->footer = new Raw('}');

    return $this;
  }

  public function generateColumns(): self
  {
    $pkNames    = $this->table->getPrimaryKey()?->getColumns() ?? [];
    $allColumns = $this->table->getColumns();

    $pkOnly       = fn(DoctrineColumn $column): bool => in_array($column->getName(), $pkNames);
    $exceptPk     = fn(DoctrineColumn $column): bool => !$pkOnly($column);
    $instantiator = fn(DoctrineColumn $column) => new Column($column);

    $pk            = array_filter($allColumns, $pkOnly);
    $columns       = array_filter($allColumns, $exceptPk);
    $this->columns = array_merge(array_map($instantiator, $pk), [new Raw('--')], array_map($instantiator, $columns));

    return $this;
  }

  public function __toString(): string
  {
    $puml = strval($this->header);
    $puml = array_reduce($this->columns, '\\Jawira\\DbDraw\\Toolbox::reducer', $puml);
    $puml .= strval($this->footer);

    return $puml;
  }
}

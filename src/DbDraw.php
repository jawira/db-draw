<?php declare(strict_types=1);

namespace Jawira\DbDraw;

use Doctrine\DBAL\Connection;
use Jawira\DoctrineDiagramContracts\DiagramGeneratorInterface;
use Jawira\DoctrineDiagramContracts\Size;
use Jawira\DoctrineDiagramContracts\Theme;
use function strval;

/**
 * @author  Jawira Portugal
 */
class DbDraw implements DiagramGeneratorInterface
{
  public function __construct(private readonly Connection $connection)
  {
  }

  /**
   * Generate ER diagram.
   *
   * @param string[] $exclude List of tables and views to exclude.
   */
  public function generatePuml(Size $size, string|Theme $theme, array $include, array $exclude): string
  {
    $diagram = match ($size) {
      Size::Mini => new Diagram\Mini($this->connection),
      Size::Midi => new Diagram\Midi($this->connection),
      Size::Maxi => new Diagram\Maxi($this->connection),
    };
    if ($theme instanceof Theme) {
      $theme = $theme->value;
    }

    $diagram->setTheme($theme)
            ->setExclude($exclude)
            ->process();

    return strval($diagram);
  }
}

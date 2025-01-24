<?php declare(strict_types=1);

namespace Jawira\DbDraw;

use Doctrine\DBAL\Connection;
use Jawira\DbDraw\Diagram\Maxi;
use Jawira\DbDraw\Diagram\Midi;
use Jawira\DbDraw\Diagram\Mini;
use Jawira\DoctrineDiagramContracts\DiagramGeneratorInterface;
use Jawira\DoctrineDiagramContracts\Size;
use Jawira\DoctrineDiagramContracts\Theme;
use function is_string;
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
  public function generatePuml(string|Size $size, string|Theme $theme, array $exclude = []): string
  {
    if (is_string($size)) {
      $size = Size::from($size);
    }

    $diagramClass = match ($size) {
      Size::Mini => Mini::class,
      Size::Midi => Midi::class,
      Size::Maxi => Maxi::class,
    };

    $theme = $theme instanceof Theme ? $theme->value : $theme;
    $diagram = new $diagramClass($this->connection);
    $diagram->setTheme($theme)
            ->setExclude($exclude)
            ->process();

    return strval($diagram);
  }
}

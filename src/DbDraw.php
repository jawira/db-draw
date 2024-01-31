<?php declare(strict_types=1);

namespace Jawira\DbDraw;

use Doctrine\DBAL\Connection;
use Jawira\DbDraw\Relational\Diagram\AbstractDiagram;
use Jawira\DbDraw\Relational\Diagram\Maxi;
use Jawira\DbDraw\Relational\Diagram\Midi;
use Jawira\DbDraw\Relational\Diagram\Mini;
use function strval;

/**
 * @author  Jawira Portugal
 */
class DbDraw
{
  public const MINI = 'mini';
  public const MIDI = 'midi';
  public const MAXI = 'maxi';

  /**
   * @var Connection
   */
  protected $connection;

  public function __construct(Connection $connection)
  {
    $this->connection = $connection;
  }

  /**
   * @param string[]  $exclude
   */
  public function generatePuml(string $size, string $theme = Theme::NONE, array $exclude = []): string
  {
    $diagram = $this->resolveDiagram($size);
    $diagram->setTheme($theme)
            ->setExclude($exclude)
            ->setConnection($this->connection)
            ->process();

    return strval($diagram);
  }

  /**
   * Instantiate proper diagram according to provided size.
   */
  protected function resolveDiagram(string $size): AbstractDiagram
  {
    return match ($size) {
      self::MINI => new Mini(),
      self::MIDI => new Midi(),
      self::MAXI => new Maxi(),
      default => throw new DbDrawException(sprintf("Invalid diagram size, must be '%s', '%s', or '%s'.", self::MINI, self::MIDI, self::MAXI)),
    };
  }
}

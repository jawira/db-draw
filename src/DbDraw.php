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

  public function generatePuml(string $size, ?string $theme = null): string
  {
    $diagram = $this->resolveDiagram($size);
    $diagram->setTheme($theme)
            ->setConnection($this->connection)
            ->process();

    return strval($diagram);
  }

  /**
   * @throws \Jawira\DbDraw\DbDrawException
   */
  protected function resolveDiagram(string $size): AbstractDiagram
  {
    $diagram = match ($size) {
      self::MINI => new Mini(),
      self::MIDI => new Midi(),
      self::MAXI => new Maxi(),
      default => throw new DbDrawException('Invalid diagram size'),
    };

    return $diagram;
  }
}

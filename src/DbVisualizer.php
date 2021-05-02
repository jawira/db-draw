<?php

namespace Jawira\DbVisualizer;

use Doctrine\DBAL\Connection;
use Jawira\DbVisualizer\Relational\Diagram\AbstractDiagram;
use Jawira\DbVisualizer\Relational\Diagram\Maxi;
use Jawira\DbVisualizer\Relational\Diagram\Midi;
use Jawira\DbVisualizer\Relational\Diagram\Mini;
use function strval;

class DbVisualizer
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
   * @return string
   * @throws \Doctrine\DBAL\Exception
   */
  public function generatePuml(string $size)
  {
    $diagram = $this->resolveDiagram($size)->setConnection($this->connection)->process();

    return strval($diagram);
  }

  /**
   * @throws \Jawira\DbVisualizer\DbVisualizerException
   */
  protected function resolveDiagram(string $size): AbstractDiagram
  {
    switch ($size) {
      case self::MINI:
        $diagram = new Mini();
        break;
      case self::MIDI:
        $diagram = new Midi();
        break;
      case self::MAXI:
        $diagram = new Maxi();
        break;
      default:
        throw new DbVisualizerException('Invalid diagram size');
    }

    return $diagram;
  }
}

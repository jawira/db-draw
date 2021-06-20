<?php

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
   * @return string
   */
  public function generatePuml(string $size)
  {
    $diagram = $this->resolveDiagram($size)->setConnection($this->connection)->process();

    return strval($diagram);
  }

  /**
   * @throws \Jawira\DbDraw\DbDrawException
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
        throw new DbDrawException('Invalid diagram size');
    }

    return $diagram;
  }
}

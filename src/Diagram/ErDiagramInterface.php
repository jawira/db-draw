<?php declare(strict_types=1);

namespace Jawira\DbDraw\Diagram;

use Doctrine\DBAL\Connection;

interface ErDiagramInterface
{
  public function __construct(Connection $connection);

  /**
   * @param string[] $include
   * @param string[] $exclude
   */
  public function process(string $theme, array $include, array $exclude): string;
}

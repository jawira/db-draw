<?php declare(strict_types=1);

namespace Jawira\DbDraw\Diagram;

use Doctrine\DBAL\Connection;
use Jawira\DbDraw\Service\PlantUmlWriter;
use Jawira\DbDraw\Service\Toolbox;

/**
 * @author Jawira Portugal
 */
class Mini implements ErDiagramInterface
{
  private readonly PlantUmlWriter $plantUmlWriter;
  /**
   * @var \Jawira\DbDraw\Service\Toolbox
   */
  private readonly Toolbox $toolbox;

  public function __construct(Connection $connection)
  {
    $this->plantUmlWriter = new PlantUmlWriter($connection);
    $this->toolbox        = new Toolbox();
  }

  /**
   * @param string[] $include
   * @param string[] $exclude
   */
  public function process(string $theme, array $include, array $exclude): string
  {
    $header        = $this->plantUmlWriter->generateHeader($theme);
    $entities      = $this->plantUmlWriter->generateEntities($include, $exclude);
    $relationships = $this->plantUmlWriter->generateRelationships($include, $exclude);
    $footer        = $this->plantUmlWriter->generateFooter();

    return $this->toolbox->reduceElements([...$header, ...$entities, ...$relationships, ...$footer]);
  }
}

<?php


namespace Jawira\DbVisualizer;

use Jawira\DbVisualizer\Element\Raw;
use Jawira\DbVisualizer\Element\Entity;
use function array_map;
use function array_reduce;
use function strval;
use const PHP_EOL;

/**
 * Class Diagram
 *
 * @package Jawira\DbVisualizer
 */
class Diagram
{

  /**
   * @var Raw
   */
  protected $start;

  /**
   * @var Raw
   */
  protected $end;

  /**
   * @var Entity[]
   */
  protected $tables = [];

  public function __construct()
  {
    $this->start = new Raw('@startuml');
    $this->end   = new Raw('@enduml');
  }

  public function retrieveEntities(array $listTables)
  {
    $createEntity = function ($table) {
      return new Entity($table);
    };

    $this->tables = array_map($createEntity, $listTables);
  }

  public function __toString()
  {
    $tableReducer = function ($carry, Entity $table) {
      return $carry . strval($table) . PHP_EOL;
    };

    $puml = strval($this->start) . PHP_EOL;
    $puml .= array_reduce($this->tables, $tableReducer, '');
    $puml .= strval($this->end) . PHP_EOL;
    return $puml;
  }
}

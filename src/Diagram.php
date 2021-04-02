<?php


namespace Jawira\DbVisualizer;

use Jawira\DbVisualizer\Element\ElementInterface;
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
   * @var Raw[]
   */
  protected $header = [];

  /**
   * @var Raw[]
   */
  protected $footer = [];

  /**
   * @var Entity[]
   */
  protected $entities = [];

  public function __construct()
  {
    $this->header[] = new Raw('@startuml');
    $this->header[] = new Raw('hide circle');
    $this->header[] = new Raw('skinparam linetype ortho');
    $this->footer[] = new Raw('@enduml');
  }

  public function retrieveEntities(array $listTables)
  {
    $createEntity = function ($table) {
      return new Entity($table);
    };

    $this->entities = array_map($createEntity, $listTables);
  }

  public function __toString()
  {
    $puml = array_reduce($this->header, [self::class, 'reducer'], '');
    $puml = array_reduce($this->entities, [self::class, 'reducer'], $puml);
    $puml = array_reduce($this->footer, [self::class, 'reducer'], $puml);
    return $puml;
  }

  public static function reducer(string $carry, ElementInterface $element): string
  {
    return $carry . strval($element);
  }
}

<?php


namespace Jawira\DbVisualizer\Tests\Type;


use Doctrine\DBAL\Types\StringType;

class EnumType extends StringType
{
  public function getName()
  {
    return 'enum';
  }
}

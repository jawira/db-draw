<?php


namespace Jawira\DbVisualizer\Tests\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CreditCard
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\Column(type="string")
   */
  protected $number;

  /**
   * @ORM\Column(type="string",length=4)
   */
  protected $pin;

}

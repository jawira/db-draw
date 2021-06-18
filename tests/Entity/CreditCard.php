<?php


namespace Jawira\DbDraw\Tests\Entity;


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
   * @ORM\Column(type="string", length=16)
   */
  protected $ownerFullName;

  /**
   * @ORM\Column(type="string", length=16)
   */
  protected $number;

  /**
   * @ORM\Column(type="string",length=4)
   */
  protected $pin;

  /**
   * @ORM\Column(type="date_immutable")
   */
  protected $expirationDate;
}

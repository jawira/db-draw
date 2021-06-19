<?php

namespace Jawira\DbDraw\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Student
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
  protected $username;

  /**
   * @ORM\Column(type="string")
   */
  protected $password;

  /**
   * @ORM\OneToOne(targetEntity="CreditCard")
   * @ORM\JoinColumn(nullable=true)
   */
  protected $creditCard;

  /**
   * @ORM\OneToOne(targetEntity="Person")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $details;
}

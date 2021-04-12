<?php

namespace Jawira\DbVisualizer\Tests\Entity;

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
   * @ORM\Column(type="string")
   */
  protected $firstName;

  /**
   * @ORM\Column(type="string")
   */
  protected $lastName;

  /**
   * @ORM\Column(type="datetime", nullable=true)
   */
  protected $birthDate;

  /**
   * @ORM\OneToOne(targetEntity="CreditCard")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $creditCard;

}

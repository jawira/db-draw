<?php

namespace Jawira\DbVisualizer\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Person
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
   * @ORM\Column (type="string",length=200)
   */
  protected $email;
}

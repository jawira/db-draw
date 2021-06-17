<?php

namespace Jawira\DbDraw\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Teacher
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\OneToOne(targetEntity="Person")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $details;
}

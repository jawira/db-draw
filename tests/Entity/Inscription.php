<?php

namespace Jawira\DbDraw\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Inscription
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $createdAt;

  /**
   * @ORM\ManyToOne(targetEntity="Student")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $student;

  /**
   * @ORM\ManyToMany(targetEntity="Session")
   */
  protected $session;
}

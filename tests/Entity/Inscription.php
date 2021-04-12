<?php

namespace Jawira\DbVisualizer\Tests\Entity;

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
  protected $user;

  /**
   * @ORM\ManyToMany(targetEntity="Course")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $courses;
}

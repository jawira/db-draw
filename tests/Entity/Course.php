<?php


namespace Jawira\DbVisualizer\Tests\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Course
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
  protected $name;

  /**
   * @ORM\ManyToOne(targetEntity="Faculty")
   */
  protected $faculty;

  /**
   * @ORM\ManyToOne(targetEntity="Course")
   * @ORM\JoinColumn(nullable=true)
   */
  protected $required;
}

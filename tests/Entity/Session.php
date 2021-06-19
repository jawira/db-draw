<?php


namespace Jawira\DbDraw\Tests\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Session
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\Column(type="integer")
   */
  protected $academicYear;

  /**
   * @ORM\Column(type="date")
   */
  protected $firstLesson;

  /**
   * @ORM\Column(type="date")
   */
  protected $lastLesson;

  /**
   * @ORM\Column(type="string",length=8)
   */
  protected $code;

  /**
   * @ORM\ManyToOne(targetEntity="Course")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $course;

  /**
   * @ORM\ManyToOne(targetEntity="Teacher")
   * @ORM\JoinColumn(nullable=false)
   */
  protected $teacher;

  /**
   * @ORM\ManyToOne(targetEntity="Assistant")
   * @ORM\JoinColumn(nullable=true)
   */
  protected $assistant;
}

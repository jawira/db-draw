<?php

namespace Jawira\DbDrawTests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\DbDraw\Theme;
use PHPUnit\Framework\TestCase;
use function file_put_contents;
use const PHP_EOL;

class DiagramTest extends TestCase
{
  private $entityCreditCard = <<<PLANTUML
    entity CreditCard {
    * id: integer
    --
    * ownerFullName: string
    * number: string
    * pin: string
    * expirationDate: date
    }
    PLANTUML;


  private $entityAssistant = <<<PLANTUML
    entity Assistant {
    * id: integer
    --
    * details_id: integer
    }
    PLANTUML;
  private $entityInscriptionSession = <<<PLANTUML
    entity inscription_session {
    * inscription_id: integer
    * session_id: integer
    --
    }
    PLANTUML;

  private $entityTeacher = <<<PLANTUML
    entity Teacher {
    * id: integer
    --
    * details_id: integer
    }
    PLANTUML;


  private $entityStudent = <<<PLANTUML
    entity Student {
    * id: integer
    --
    * details_id: integer
    * username: string
    * password: string
     creditCard_id: integer
    }
    PLANTUML;


  private $entitySession = <<<PLANTUML
    entity Session {
    * id: integer
    --
    * course_id: integer
    * teacher_id: integer
     assistant_id: integer
    * academicYear: integer
    * firstLesson: date
    * lastLesson: date
    * code: string
    }
    PLANTUML;


  private $entityPerson = <<<PLANTUML
    entity Person {
    * id: integer
    --
    * firstName: string
    * lastName: string
     birthDate: datetime
    * email: string
    }
    PLANTUML;


  private $entityInscription = <<<PLANTUML
    entity Inscription {
    * id: integer
    --
    * student_id: integer
    * createdAt: datetime
    }
    PLANTUML;

  private $entityFaculty = <<<PLANTUML
    entity Faculty {
    * id: integer
    --
    * name: string
    }
    PLANTUML;

  private $entityCourse = <<<PLANTUML
    entity Course {
    * id: integer
    --
     faculty_id: integer
     required_id: integer
    * name: string
    }
    PLANTUML;

  private $relationAssistantPerson = "Assistant |o--|| Person";
  private $relationInscriptoinSessionInscription = "inscription_session }o--|| Inscription";
  private $relationInsciptionSessionSession = "inscription_session }o--|| Session";
  private $relationTeacherPerson = "Teacher |o--|| Person";
  private $relationStudentCreditCard = "Student |o--o| CreditCard";
  private $relationStudentPerson = "Student |o--|| Person";
  private $relationSessionTeacher = "Session }o--|| Teacher";
  private $relationSessionCourse = "Session }o--|| Course";
  private $relationSessionAssistant = "Session }o--o| Assistant";
  private $relationIscriptionStudent = "Inscription }o--|| Student";
  private $relationCourseFaculty = "Course }o--o| Faculty";
  private $relationCourseCourse = "Course }o--o| Course";

  private $views = <<<PLANTUML
    package "views" {
    entity introductory_courses { }
    entity students_with_no_card { }
    }
    PLANTUML;

  private Connection $connection;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $connectionParams = ['url' => "mysql://groot:groot@127.0.0.1:33060/institute", 'driver' => 'pdo_mysql'];
    $this->connection = DriverManager::getConnection($connectionParams);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Relational\Diagram\Mini
   * @covers \Jawira\DbDraw\Relational\Entity
   * @covers \Jawira\DbDraw\Relational\Raw
   * @covers \Jawira\DbDraw\Relational\Relationship
   * @covers \Jawira\DbDraw\Toolbox
   */
  public function testMiniDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MINI);
    file_put_contents('./resources/output/mini.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(940, mb_strlen($puml));
    $this->assertStringContainsString($this->relationAssistantPerson, $puml);
    $this->assertStringContainsString($this->relationInscriptoinSessionInscription, $puml);
    $this->assertStringContainsString($this->relationInsciptionSessionSession, $puml);
    $this->assertStringContainsString($this->relationTeacherPerson, $puml);
    $this->assertStringContainsString($this->relationStudentCreditCard, $puml);
    $this->assertStringContainsString($this->relationStudentPerson, $puml);
    $this->assertStringContainsString($this->relationSessionTeacher, $puml);
    $this->assertStringContainsString($this->relationSessionCourse, $puml);
    $this->assertStringContainsString($this->relationSessionAssistant, $puml);
    $this->assertStringContainsString($this->relationIscriptionStudent, $puml);
    $this->assertStringContainsString($this->relationCourseFaculty, $puml);
    $this->assertStringContainsString($this->relationCourseCourse, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Relational\Column
   * @covers \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Relational\Diagram\Maxi
   * @covers \Jawira\DbDraw\Relational\Diagram\Midi
   * @covers \Jawira\DbDraw\Relational\Entity
   * @covers \Jawira\DbDraw\Relational\Raw
   * @covers \Jawira\DbDraw\Relational\Relationship
   * @covers \Jawira\DbDraw\Toolbox
   */
  public function testMidiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MIDI);
    file_put_contents('./resources/output/midi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(1690, mb_strlen($puml));
    $this->assertStringContainsString($this->entityCourse, $puml);
    $this->assertStringContainsString($this->entityAssistant, $puml);
    $this->assertStringContainsString($this->entityInscriptionSession, $puml);
    $this->assertStringContainsString($this->entityFaculty, $puml);
    $this->assertStringContainsString($this->entityCreditCard, $puml);
    $this->assertStringContainsString($this->entityPerson, $puml);
    $this->assertStringContainsString($this->entityInscription, $puml);
    $this->assertStringContainsString($this->entitySession, $puml);
    $this->assertStringContainsString($this->entityStudent, $puml);
    $this->assertStringContainsString($this->entityTeacher, $puml);
    $this->assertStringContainsString($this->relationAssistantPerson, $puml);
    $this->assertStringContainsString($this->relationInscriptoinSessionInscription, $puml);
    $this->assertStringContainsString($this->relationInsciptionSessionSession, $puml);
    $this->assertStringContainsString($this->relationTeacherPerson, $puml);
    $this->assertStringContainsString($this->relationStudentCreditCard, $puml);
    $this->assertStringContainsString($this->relationStudentPerson, $puml);
    $this->assertStringContainsString($this->relationSessionTeacher, $puml);
    $this->assertStringContainsString($this->relationSessionCourse, $puml);
    $this->assertStringContainsString($this->relationSessionAssistant, $puml);
    $this->assertStringContainsString($this->relationIscriptionStudent, $puml);
    $this->assertStringContainsString($this->relationCourseFaculty, $puml);
    $this->assertStringContainsString($this->relationCourseCourse, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Relational\Column
   * @covers \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Relational\Diagram\Maxi
   * @covers \Jawira\DbDraw\Relational\Entity
   * @covers \Jawira\DbDraw\Relational\Raw
   * @covers \Jawira\DbDraw\Relational\Relationship
   * @covers \Jawira\DbDraw\Relational\Views
   * @covers \Jawira\DbDraw\Toolbox
   */
  public function testMaxiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MAXI);
    file_put_contents('./resources/output/maxi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(1770, mb_strlen($puml));

    $this->assertStringContainsString($this->entityCourse, $puml);
    $this->assertStringContainsString($this->entityAssistant, $puml);
    $this->assertStringContainsString($this->entityInscriptionSession, $puml);
    $this->assertStringContainsString($this->entityFaculty, $puml);
    $this->assertStringContainsString($this->entityCreditCard, $puml);
    $this->assertStringContainsString($this->entityPerson, $puml);
    $this->assertStringContainsString($this->entityInscription, $puml);
    $this->assertStringContainsString($this->entitySession, $puml);
    $this->assertStringContainsString($this->entityStudent, $puml);
    $this->assertStringContainsString($this->entityTeacher, $puml);
    $this->assertStringContainsString($this->relationAssistantPerson, $puml);
    $this->assertStringContainsString($this->relationInscriptoinSessionInscription, $puml);
    $this->assertStringContainsString($this->relationInsciptionSessionSession, $puml);
    $this->assertStringContainsString($this->relationTeacherPerson, $puml);
    $this->assertStringContainsString($this->relationStudentCreditCard, $puml);
    $this->assertStringContainsString($this->relationStudentPerson, $puml);
    $this->assertStringContainsString($this->relationSessionTeacher, $puml);
    $this->assertStringContainsString($this->relationSessionCourse, $puml);
    $this->assertStringContainsString($this->relationSessionAssistant, $puml);
    $this->assertStringContainsString($this->relationIscriptionStudent, $puml);
    $this->assertStringContainsString($this->relationCourseFaculty, $puml);
    $this->assertStringContainsString($this->relationCourseCourse, $puml);
    $this->assertStringContainsString($this->views, $puml);
  }

  /**
   * @covers       \Jawira\DbDraw\DbDraw
   * @covers       \Jawira\DbDraw\Relational\Column
   * @covers       \Jawira\DbDraw\Relational\Diagram\AbstractDiagram
   * @covers       \Jawira\DbDraw\Relational\Diagram\Maxi
   * @covers       \Jawira\DbDraw\Relational\Entity
   * @covers       \Jawira\DbDraw\Relational\Raw
   * @covers       \Jawira\DbDraw\Relational\Relationship
   * @covers       \Jawira\DbDraw\Relational\Views::__construct
   * @covers       \Jawira\DbDraw\Relational\Views::__toString
   * @covers       \Jawira\DbDraw\Relational\Views::generateHeaderAndFooter
   * @covers       \Jawira\DbDraw\Relational\Views::generateViews
   * @covers       \Jawira\DbDraw\Toolbox
   * @dataProvider themeProvider
   * @testdox      Diagram with theme $theme
   */
  public function testTheme($theme)
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MAXI, $theme);
    file_put_contents("./resources/output/theme-{$theme}.puml", $puml);
    $this->assertIsString($puml);
    $this->assertStringStartsWith('@startuml' . PHP_EOL, $puml);
    $this->assertStringContainsString("!theme {$theme}", $puml);
    $this->assertStringEndsWith('@enduml' . PHP_EOL, $puml);
  }

  public function themeProvider(): array
  {
    return [
      [Theme::AMIGA],
      [Theme::BLACK_KNIGHT],
      [Theme::BLUEGRAY],
      [Theme::BLUEPRINT],
      [Theme::CERULEAN],
      [Theme::CERULEAN_OUTLINE],
      [Theme::CRT_AMBER],
      [Theme::CRT_GREEN],
      [Theme::CYBORG],
      [Theme::CYBORG_OUTLINE],
      [Theme::HACKER],
      [Theme::LIGHTGRAY],
      [Theme::MATERIA],
      [Theme::MATERIA_OUTLINE],
      [Theme::METAL],
      [Theme::MIMEOGRAPH],
      [Theme::MINTY],
      [Theme::PLAIN],
      [Theme::SANDSTONE],
      [Theme::SILVER],
      [Theme::SKETCHY],
      [Theme::SKETCHY_OUTLINE],
      [Theme::SPACELAB],
      [Theme::SUPERHERO],
      [Theme::SUPERHERO_OUTLINE],
      [Theme::TOY],
      [Theme::UNITED],
      [Theme::VIBRANT],
    ];
  }
}

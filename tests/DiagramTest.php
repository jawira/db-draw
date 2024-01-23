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
  /**
   * @var Connection
   */
  protected $connection;

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
    $expected = <<<'PUML'
      @startuml
      hide empty members
      hide circle
      skinparam ArrowColor #333
      skinparam ArrowThickness 1.5
      skinparam ClassBackgroundColor White-APPLICATION
      skinparam ClassBorderColor LightSlateGray
      skinparam ClassBorderThickness 1
      skinparam MinClassWidth 150
      skinparam LineType Ortho
      skinparam Shadowing false
      skinparam PackageBackgroundColor #eee
      skinparam PackageBorderColor #eee
      skinparam PackageFontStyle normal
      title institute
      entity Assistant {
      }
      entity Course {
      }
      entity inscription_session {
      }
      entity CreditCard {
      }
      entity Teacher {
      }
      entity Student {
      }
      entity Faculty {
      }
      entity Session {
      }
      entity Inscription {
      }
      entity Person {
      }
      Assistant |o--|| Person
      Course }o--o| Faculty
      Course }o--o| Course
      inscription_session }o--|| Inscription
      inscription_session }o--|| Session
      Teacher |o--|| Person
      Student |o--o| CreditCard
      Student |o--|| Person
      Session }o--|| Teacher
      Session }o--|| Course
      Session }o--o| Assistant
      Inscription }o--|| Student
      @enduml
      PUML;
    $drawer   = new DbDraw($this->connection);
    $puml     = $drawer->generatePuml(DbDraw::MINI);
    file_put_contents('./resources/output/mini.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(940, mb_strlen($puml));
    $this->assertStringContainsString($expected, $puml);
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
    $expected = <<<'PUML'
      @startuml
      hide empty members
      hide circle
      skinparam ArrowColor #333
      skinparam ArrowThickness 1.5
      skinparam ClassBackgroundColor White-APPLICATION
      skinparam ClassBorderColor LightSlateGray
      skinparam ClassBorderThickness 1
      skinparam MinClassWidth 150
      skinparam LineType Ortho
      skinparam Shadowing false
      skinparam PackageBackgroundColor #eee
      skinparam PackageBorderColor #eee
      skinparam PackageFontStyle normal
      title institute
      entity Assistant {
      * id: integer
      --
      * details_id: integer
      }
      entity Course {
      * id: integer
      --
       faculty_id: integer
       required_id: integer
      * name: string
      }
      entity inscription_session {
      * inscription_id: integer
      * session_id: integer
      --
      }
      entity CreditCard {
      * id: integer
      --
      * ownerFullName: string
      * number: string
      * pin: string
      * expirationDate: date
      }
      entity Teacher {
      * id: integer
      --
      * details_id: integer
      }
      entity Student {
      * id: integer
      --
      * details_id: integer
      * username: string
      * password: string
       creditCard_id: integer
      }
      entity Faculty {
      * id: integer
      --
      * name: string
      }
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
      entity Inscription {
      * id: integer
      --
      * student_id: integer
      * createdAt: datetime
      }
      entity Person {
      * id: integer
      --
      * firstName: string
      * lastName: string
       birthDate: datetime
      * email: string
      }
      Assistant |o--|| Person
      Course }o--o| Faculty
      Course }o--o| Course
      inscription_session }o--|| Inscription
      inscription_session }o--|| Session
      Teacher |o--|| Person
      Student |o--o| CreditCard
      Student |o--|| Person
      Session }o--|| Teacher
      Session }o--|| Course
      Session }o--o| Assistant
      Inscription }o--|| Student
      @enduml
      PUML;

    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MIDI);
    file_put_contents('./resources/output/midi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(1690, mb_strlen($puml));
    $this->assertStringContainsString($expected, $puml);
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
    $expected = <<<'PUML'
      @startuml
      hide empty members
      hide circle
      skinparam ArrowColor #333
      skinparam ArrowThickness 1.5
      skinparam ClassBackgroundColor White-APPLICATION
      skinparam ClassBorderColor LightSlateGray
      skinparam ClassBorderThickness 1
      skinparam MinClassWidth 150
      skinparam LineType Ortho
      skinparam Shadowing false
      skinparam PackageBackgroundColor #eee
      skinparam PackageBorderColor #eee
      skinparam PackageFontStyle normal
      title institute
      entity Assistant {
      * id: integer
      --
      * details_id: integer
      }
      entity Course {
      * id: integer
      --
       faculty_id: integer
       required_id: integer
      * name: string
      }
      entity inscription_session {
      * inscription_id: integer
      * session_id: integer
      --
      }
      entity CreditCard {
      * id: integer
      --
      * ownerFullName: string
      * number: string
      * pin: string
      * expirationDate: date
      }
      entity Teacher {
      * id: integer
      --
      * details_id: integer
      }
      entity Student {
      * id: integer
      --
      * details_id: integer
      * username: string
      * password: string
       creditCard_id: integer
      }
      entity Faculty {
      * id: integer
      --
      * name: string
      }
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
      entity Inscription {
      * id: integer
      --
      * student_id: integer
      * createdAt: datetime
      }
      entity Person {
      * id: integer
      --
      * firstName: string
      * lastName: string
       birthDate: datetime
      * email: string
      }
      Assistant |o--|| Person
      Course }o--o| Faculty
      Course }o--o| Course
      inscription_session }o--|| Inscription
      inscription_session }o--|| Session
      Teacher |o--|| Person
      Student |o--o| CreditCard
      Student |o--|| Person
      Session }o--|| Teacher
      Session }o--|| Course
      Session }o--o| Assistant
      Inscription }o--|| Student
      package "views" {
      entity introductory_courses { }
      entity students_with_no_card { }
      }
      @enduml
      PUML;

    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(DbDraw::MAXI);
    file_put_contents('./resources/output/maxi.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(1770, mb_strlen($puml));
    $this->assertStringContainsString($expected, $puml);
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

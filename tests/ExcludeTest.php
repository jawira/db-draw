<?php

namespace Jawira\DbDrawTests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\DbDrawTests\Parts\Entities;
use Jawira\DbDrawTests\Parts\EntityNames;
use Jawira\DbDrawTests\Parts\Relations;
use Jawira\DbDrawTests\Parts\Views;
use Jawira\DoctrineDiagramContracts\Size;
use Jawira\DoctrineDiagramContracts\Theme;
use PHPUnit\Framework\TestCase;
use function file_put_contents;

class ExcludeTest extends TestCase
{

  private Connection $connection;

  public function __construct(?string $name = null, array $data = [], $dataName = '')
  {
    parent::__construct($name, $data, $dataName);
    $connectionParams = [
      'user' => 'groot',
      'password' => 'groot',
      'host' => '127.0.0.1',
      'port' => 33060,
      'dbname' => 'institute',
      'charset' => 'utf8mb4',
      'driver' => 'pdo_mysql',
      'serverVersion' => '8.2',
    ];
    $this->connection = DriverManager::getConnection($connectionParams);
    $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Diagram\Mini
   * @covers \Jawira\DbDraw\Element\Entity
   * @covers \Jawira\DbDraw\Element\Raw
   * @covers \Jawira\DbDraw\Element\Relationship
   * @covers \Jawira\DbDraw\Service\Toolbox
   */
  public function testMiniDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(Size::Mini, Theme::Toy, exclude: ['Course', 'Assistant']);
    file_put_contents('./resources/output/mini-exclude.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(490, mb_strlen($puml));
    $this->assertStringNotContainsString(EntityNames::Course, $puml);
    $this->assertStringNotContainsString(EntityNames::Assistant, $puml);
    $this->assertStringContainsString(EntityNames::InscriptionSession, $puml);
    $this->assertStringContainsString(EntityNames::Faculty, $puml);
    $this->assertStringContainsString(EntityNames::CreditCard, $puml);
    $this->assertStringContainsString(EntityNames::Person, $puml);
    $this->assertStringContainsString(EntityNames::Inscription, $puml);
    $this->assertStringContainsString(EntityNames::Session, $puml);
    $this->assertStringContainsString(EntityNames::Student, $puml);
    $this->assertStringContainsString(EntityNames::Teacher, $puml);
    $this->assertStringNotContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptoinSessionInscription, $puml);
    $this->assertStringContainsString(Relations::InsciptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringNotContainsString(Relations::SessionCourse, $puml);
    $this->assertStringNotContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::IscriptionStudent, $puml);
    $this->assertStringNotContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringNotContainsString(Relations::CourseCourse, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Element\Column
   * @covers \Jawira\DbDraw\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Diagram\Maxi
   * @covers \Jawira\DbDraw\Diagram\Midi
   * @covers \Jawira\DbDraw\Element\Entity
   * @covers \Jawira\DbDraw\Element\Raw
   * @covers \Jawira\DbDraw\Element\Relationship
   * @covers \Jawira\DbDraw\Service\Toolbox
   */
  public function testMidiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(Size::Midi, '_none_', exclude: ['Session', 'Student']);
    file_put_contents('./resources/output/midi-exclude.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(900, mb_strlen($puml));
    $this->assertStringContainsString(Entities::Course, $puml);
    $this->assertStringContainsString(Entities::Assistant, $puml);
    $this->assertStringContainsString(Entities::InscriptionSession, $puml);
    $this->assertStringContainsString(Entities::Faculty, $puml);
    $this->assertStringContainsString(Entities::CreditCard, $puml);
    $this->assertStringContainsString(Entities::Person, $puml);
    $this->assertStringContainsString(Entities::Inscription, $puml);
    $this->assertStringNotContainsString(Entities::Session, $puml);
    $this->assertStringNotContainsString(Entities::Student, $puml);
    $this->assertStringContainsString(Entities::Teacher, $puml);
    $this->assertStringContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptoinSessionInscription, $puml);
    $this->assertStringNotContainsString(Relations::InsciptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringNotContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringNotContainsString(Relations::StudentPerson, $puml);
    $this->assertStringNotContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringNotContainsString(Relations::SessionCourse, $puml);
    $this->assertStringNotContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringNotContainsString(Relations::IscriptionStudent, $puml);
    $this->assertStringContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);
  }

  /**
   * @covers \Jawira\DbDraw\DbDraw
   * @covers \Jawira\DbDraw\Element\Column
   * @covers \Jawira\DbDraw\Diagram\AbstractDiagram
   * @covers \Jawira\DbDraw\Diagram\Maxi
   * @covers \Jawira\DbDraw\Element\Entity
   * @covers \Jawira\DbDraw\Element\Raw
   * @covers \Jawira\DbDraw\Element\Relationship
   * @covers \Jawira\DbDraw\Element\Views
   * @covers \Jawira\DbDraw\Service\Toolbox
   */
  public function testMaxiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml('maxi', 'crt-amber', exclude: ['CreditCard', 'students_with_no_card']);
    file_put_contents('./resources/output/maxi-exclude.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(1300, mb_strlen($puml));

    $this->assertStringContainsString(Entities::Course, $puml);
    $this->assertStringContainsString(Entities::Assistant, $puml);
    $this->assertStringContainsString(Entities::InscriptionSession, $puml);
    $this->assertStringContainsString(Entities::Faculty, $puml);
    $this->assertStringNotContainsString(Entities::CreditCard, $puml);
    $this->assertStringContainsString(Entities::Person, $puml);
    $this->assertStringContainsString(Entities::Inscription, $puml);
    $this->assertStringContainsString(Entities::Session, $puml);
    $this->assertStringContainsString(Entities::Student, $puml);
    $this->assertStringContainsString(Entities::Teacher, $puml);

    $this->assertStringContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptoinSessionInscription, $puml);
    $this->assertStringContainsString(Relations::InsciptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringNotContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringContainsString(Relations::SessionCourse, $puml);
    $this->assertStringContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::IscriptionStudent, $puml);
    $this->assertStringContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);

    $this->assertStringContainsString(Views::ENTITY_INTRODUCTORY_COURSES, $puml);
    $this->assertStringNotContainsString(Views::ENTITY_STUDENTS_WITH_NO_CARD, $puml);
  }
}

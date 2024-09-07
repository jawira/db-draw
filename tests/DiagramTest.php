<?php

namespace Jawira\DbDrawTests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Jawira\DbDraw\DbDraw;
use Jawira\DbDrawTests\Parts\Entities;
use Jawira\DbDrawTests\Parts\EntityNames;
use Jawira\DbDrawTests\Parts\Relations;
use Jawira\DbDrawTests\Parts\Views;
use PHPUnit\Framework\TestCase;
use function file_put_contents;

class DiagramTest extends TestCase
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
    $this->assertGreaterThan(630, mb_strlen($puml));
    $this->assertStringContainsString(EntityNames::Course, $puml);
    $this->assertStringContainsString(EntityNames::Assistant, $puml);
    $this->assertStringContainsString(EntityNames::InscriptionSession, $puml);
    $this->assertStringContainsString(EntityNames::Faculty, $puml);
    $this->assertStringContainsString(EntityNames::CreditCard, $puml);
    $this->assertStringContainsString(EntityNames::Person, $puml);
    $this->assertStringContainsString(EntityNames::Inscription, $puml);
    $this->assertStringContainsString(EntityNames::Session, $puml);
    $this->assertStringContainsString(EntityNames::Student, $puml);
    $this->assertStringContainsString(EntityNames::Teacher, $puml);
    $this->assertStringContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptoinSessionInscription, $puml);
    $this->assertStringContainsString(Relations::InsciptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringContainsString(Relations::SessionCourse, $puml);
    $this->assertStringContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::IscriptionStudent, $puml);
    $this->assertStringContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);
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
    $this->assertGreaterThan(1380, mb_strlen($puml));
    $this->assertStringContainsString(Entities::Course, $puml);
    $this->assertStringContainsString(Entities::Assistant, $puml);
    $this->assertStringContainsString(Entities::InscriptionSession, $puml);
    $this->assertStringContainsString(Entities::Faculty, $puml);
    $this->assertStringContainsString(Entities::CreditCard, $puml);
    $this->assertStringContainsString(Entities::Person, $puml);
    $this->assertStringContainsString(Entities::Inscription, $puml);
    $this->assertStringContainsString(Entities::Session, $puml);
    $this->assertStringContainsString(Entities::Student, $puml);
    $this->assertStringContainsString(Entities::Teacher, $puml);
    $this->assertStringContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptoinSessionInscription, $puml);
    $this->assertStringContainsString(Relations::InsciptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringContainsString(Relations::SessionCourse, $puml);
    $this->assertStringContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::IscriptionStudent, $puml);
    $this->assertStringContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);
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
    $this->assertGreaterThan(1460, mb_strlen($puml));

    $this->assertStringContainsString(Entities::Course, $puml);
    $this->assertStringContainsString(Entities::Assistant, $puml);
    $this->assertStringContainsString(Entities::InscriptionSession, $puml);
    $this->assertStringContainsString(Entities::Faculty, $puml);
    $this->assertStringContainsString(Entities::CreditCard, $puml);
    $this->assertStringContainsString(Entities::Person, $puml);
    $this->assertStringContainsString(Entities::Inscription, $puml);
    $this->assertStringContainsString(Entities::Session, $puml);
    $this->assertStringContainsString(Entities::Student, $puml);
    $this->assertStringContainsString(Entities::Teacher, $puml);

    $this->assertStringContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptoinSessionInscription, $puml);
    $this->assertStringContainsString(Relations::InsciptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringContainsString(Relations::SessionCourse, $puml);
    $this->assertStringContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::IscriptionStudent, $puml);
    $this->assertStringContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);
    $this->assertStringContainsString(Views::ALL, $puml);
  }
}

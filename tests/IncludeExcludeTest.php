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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function file_put_contents;
use function mb_strlen;

#[CoversClass(\Jawira\DbDraw\DbDraw::class)]
#[CoversClass(\Jawira\DbDraw\Diagram\Maxi::class)]
#[CoversClass(\Jawira\DbDraw\Diagram\Midi::class)]
#[CoversClass(\Jawira\DbDraw\Diagram\Mini::class)]
#[CoversClass(\Jawira\DbDraw\Element\Column::class)]
#[CoversClass(\Jawira\DbDraw\Element\Entity::class)]
#[CoversClass(\Jawira\DbDraw\Element\Raw::class)]
#[CoversClass(\Jawira\DbDraw\Element\Relationship::class)]
#[CoversClass(\Jawira\DbDraw\Element\View::class)]
#[CoversClass(\Jawira\DbDraw\Service\ElementFilter::class)]
#[CoversClass(\Jawira\DbDraw\Service\PlantUmlWriter::class)]
#[CoversClass(\Jawira\DbDraw\Service\Toolbox::class)]
class IncludeExcludeTest extends TestCase
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

  public function testMiniDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(
      Size::Mini,
      Theme::Toy,
      ['Assistant', 'Course', 'InscriptionSession', 'Faculty', 'CreditCard', 'Person', 'Inscription', 'Student'],
      ['Course', 'Assistant'],
    );
    file_put_contents(__DIR__ . '/../resources/output/mini-include-exclude.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(200, mb_strlen($puml));
    // Entities
    $this->assertStringNotContainsString(EntityNames::Course, $puml);
    $this->assertStringNotContainsString(EntityNames::Assistant, $puml);
    $this->assertStringNotContainsString(EntityNames::InscriptionSession, $puml);
    $this->assertStringContainsString(EntityNames::Faculty, $puml);
    $this->assertStringContainsString(EntityNames::CreditCard, $puml);
    $this->assertStringContainsString(EntityNames::Person, $puml);
    $this->assertStringContainsString(EntityNames::Inscription, $puml);
    $this->assertStringNotContainsString(EntityNames::Session, $puml);
    $this->assertStringContainsString(EntityNames::Student, $puml);
    $this->assertStringNotContainsString(EntityNames::Teacher, $puml);
    // Relations
    $this->assertStringNotContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringNotContainsString(Relations::InscriptionSessionInscription, $puml);
    $this->assertStringNotContainsString(Relations::InscriptionSessionSession, $puml);
    $this->assertStringNotContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringNotContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringNotContainsString(Relations::SessionCourse, $puml);
    $this->assertStringNotContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::InscriptionStudent, $puml);
    $this->assertStringNotContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringNotContainsString(Relations::CourseCourse, $puml);
    // Views
    $this->assertStringNotContainsString(Views::ENTITY_INTRODUCTORY_COURSES, $puml);
    $this->assertStringNotContainsString(Views::ENTITY_STUDENTS_WITH_NO_CARD, $puml);
  }

  public function testMidiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(
      Size::Midi,
      '_none_',
      ['inscription_session', 'Inscription', 'Student', 'Person', 'Teacher', 'Session', 'Course', 'Faculty'],
      ['Assistant', 'Faculty'],
    );
    file_put_contents(__DIR__ . '/../resources/output/midi-include-exclude.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(170, mb_strlen($puml));
    // Entities
    $this->assertStringContainsString(Entities::Course, $puml);
    $this->assertStringNotContainsString(Entities::Assistant, $puml);
    $this->assertStringContainsString(Entities::InscriptionSession, $puml);
    $this->assertStringNotContainsString(Entities::Faculty, $puml);
    $this->assertStringNotContainsString(Entities::CreditCard, $puml);
    $this->assertStringContainsString(Entities::Person, $puml);
    $this->assertStringContainsString(Entities::Inscription, $puml);
    $this->assertStringContainsString(Entities::Session, $puml);
    $this->assertStringContainsString(Entities::Student, $puml);
    $this->assertStringContainsString(Entities::Teacher, $puml);
    // Relations
    $this->assertStringNotContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringContainsString(Relations::InscriptionSessionInscription, $puml);
    $this->assertStringContainsString(Relations::InscriptionSessionSession, $puml);
    $this->assertStringContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringNotContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringContainsString(Relations::StudentPerson, $puml);
    $this->assertStringContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringContainsString(Relations::SessionCourse, $puml);
    $this->assertStringNotContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringContainsString(Relations::InscriptionStudent, $puml);
    $this->assertStringNotContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);
    // Views
    $this->assertStringNotContainsString(Views::ENTITY_INTRODUCTORY_COURSES, $puml);
    $this->assertStringNotContainsString(Views::ENTITY_STUDENTS_WITH_NO_CARD, $puml);
  }

  public function testMaxiDiagram()
  {
    $drawer = new DbDraw($this->connection);
    $puml   = $drawer->generatePuml(
      Size::Maxi,
      'crt-amber',
      ['Session', 'Course', 'Faculty', 'Student', 'Teacher', 'inscription_session', 'introductory_courses'],
      ['Teacher', 'CreditCard', 'Student', 'students_with_no_card', 'inscription_session'],
    );
    file_put_contents(__DIR__ . '/../resources/output/maxi-include-exclude.puml', $puml);
    $this->assertIsString($puml);
    $this->assertGreaterThan(570, mb_strlen($puml));
    // Entities
    $this->assertStringContainsString(Entities::Course, $puml);
    $this->assertStringNotContainsString(Entities::Assistant, $puml);
    $this->assertStringNotContainsString(Entities::InscriptionSession, $puml);
    $this->assertStringContainsString(Entities::Faculty, $puml);
    $this->assertStringNotContainsString(Entities::CreditCard, $puml);
    $this->assertStringNotContainsString(Entities::Person, $puml);
    $this->assertStringNotContainsString(Entities::Inscription, $puml);
    $this->assertStringContainsString(Entities::Session, $puml);
    $this->assertStringNotContainsString(Entities::Student, $puml);
    $this->assertStringNotContainsString(Entities::Teacher, $puml);
    // Relations
    $this->assertStringNotContainsString(Relations::AssistantPerson, $puml);
    $this->assertStringNotContainsString(Relations::InscriptionSessionInscription, $puml);
    $this->assertStringNotContainsString(Relations::InscriptionSessionSession, $puml);
    $this->assertStringNotContainsString(Relations::TeacherPerson, $puml);
    $this->assertStringNotContainsString(Relations::StudentCreditCard, $puml);
    $this->assertStringNotContainsString(Relations::StudentPerson, $puml);
    $this->assertStringNotContainsString(Relations::SessionTeacher, $puml);
    $this->assertStringContainsString(Relations::SessionCourse, $puml);
    $this->assertStringNotContainsString(Relations::SessionAssistant, $puml);
    $this->assertStringNotContainsString(Relations::InscriptionStudent, $puml);
    $this->assertStringContainsString(Relations::CourseFaculty, $puml);
    $this->assertStringContainsString(Relations::CourseCourse, $puml);
    // Views
    $this->assertStringContainsString(Views::ENTITY_INTRODUCTORY_COURSES, $puml);
    $this->assertStringNotContainsString(Views::ENTITY_STUDENTS_WITH_NO_CARD, $puml);
  }
}

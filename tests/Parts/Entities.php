<?php

namespace Jawira\DbDrawTests\Parts;

class Entities
{

  public const CreditCard = <<<PLANTUML
    entity CreditCard {
    * id: integer
    --
    * ownerFullName: string
    * number: string
    * pin: string
    * expirationDate: date
    }
    PLANTUML;


  public const Assistant = <<<PLANTUML
    entity Assistant {
    * id: integer
    --
    * details_id: integer
    }
    PLANTUML;
  public const InscriptionSession = <<<PLANTUML
    entity inscription_session {
    * inscription_id: integer
    * session_id: integer
    --
    }
    PLANTUML;

  public const Teacher = <<<PLANTUML
    entity Teacher {
    * id: integer
    --
    * details_id: integer
    }
    PLANTUML;


  public const Student = <<<PLANTUML
    entity Student {
    * id: integer
    --
    * details_id: integer
    * username: string
    * password: string
     creditCard_id: integer
    }
    PLANTUML;


  public const Session = <<<PLANTUML
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


  public const Person = <<<PLANTUML
    entity Person {
    * id: integer
    --
    * firstName: string
    * lastName: string
     birthDate: datetime
    * email: string
    }
    PLANTUML;


  public const Inscription = <<<PLANTUML
    entity Inscription {
    * id: integer
    --
    * student_id: integer
    * createdAt: datetime
    }
    PLANTUML;

  public const Faculty = <<<PLANTUML
    entity Faculty {
    * id: integer
    --
    * name: string
    }
    PLANTUML;

  public const Course = <<<PLANTUML
    entity Course {
    * id: integer
    --
     faculty_id: integer
     required_id: integer
    * name: string
    }
    PLANTUML;

}

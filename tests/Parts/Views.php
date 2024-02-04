<?php

namespace Jawira\DbDrawTests\Parts;

class Views
{
  public const ALL = <<<PLANTUML
    package "views" {
    entity introductory_courses { }
    entity students_with_no_card { }
    }
    PLANTUML;

  public const ENTITY_INTRODUCTORY_COURSES = 'entity introductory_courses { }';
  public const ENTITY_STUDENTS_WITH_NO_CARD = 'entity students_with_no_card { }';
}

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

  public const INTRODUCTORY = 'entity introductory_courses { }';
  public const STUDENTS = 'entity students_with_no_card { }';
}

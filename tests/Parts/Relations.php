<?php

namespace Jawira\DbDrawTests\Parts;

class Relations
{

  public const AssistantPerson = "Assistant |o--|| Person";
  public const InscriptionSessionInscription = "inscription_session }o--|| Inscription";
  public const InscriptionSessionSession = "inscription_session }o--|| Session";
  public const TeacherPerson = "Teacher |o--|| Person";
  public const StudentCreditCard = "Student |o--o| CreditCard";
  public const StudentPerson = "Student |o--|| Person";
  public const SessionTeacher = "Session }o--|| Teacher";
  public const SessionCourse = "Session }o--|| Course";
  public const SessionAssistant = "Session }o--o| Assistant";
  public const InscriptionStudent = "Inscription }o--|| Student";
  public const CourseFaculty = "Course }o--o| Faculty";
  public const CourseCourse = "Course }o--o| Course";

}

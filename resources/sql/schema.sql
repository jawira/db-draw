create table if not exists CreditCard
(
    id             int auto_increment
        primary key,
    ownerFullName  varchar(16) not null,
    number         varchar(16) not null,
    pin            varchar(4)  not null,
    expirationDate date        not null
)
    collate = utf8_unicode_ci;

create table if not exists Faculty
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
)
    collate = utf8_unicode_ci;

create table if not exists Course
(
    id          int auto_increment
        primary key,
    faculty_id  int          null,
    required_id int          null,
    name        varchar(255) not null,
    constraint FK_11326A8F680CAB68
        foreign key (faculty_id) references Faculty (id),
    constraint FK_11326A8FDD3DFC3F
        foreign key (required_id) references Course (id)
)
    collate = utf8_unicode_ci;

create index IDX_11326A8F680CAB68
    on Course (faculty_id);

create index IDX_11326A8FDD3DFC3F
    on Course (required_id);

create table if not exists Person
(
    id        int auto_increment
        primary key,
    firstName varchar(255) not null,
    lastName  varchar(255) not null,
    birthDate datetime     null,
    email     varchar(200) not null
)
    collate = utf8_unicode_ci;

create table if not exists Assistant
(
    id         int auto_increment
        primary key,
    details_id int not null,
    constraint UNIQ_4068FE72BB1A0722
        unique (details_id),
    constraint FK_4068FE72BB1A0722
        foreign key (details_id) references Person (id)
)
    collate = utf8_unicode_ci;

create table if not exists Student
(
    id            int auto_increment
        primary key,
    details_id    int          not null,
    username      varchar(255) not null,
    password      varchar(255) not null,
    creditCard_id int          null,
    constraint UNIQ_789E96AF4D74A55E
        unique (creditCard_id),
    constraint UNIQ_789E96AFBB1A0722
        unique (details_id),
    constraint FK_789E96AF4D74A55E
        foreign key (creditCard_id) references CreditCard (id),
    constraint FK_789E96AFBB1A0722
        foreign key (details_id) references Person (id)
)
    collate = utf8_unicode_ci;

create table if not exists Inscription
(
    id         int auto_increment
        primary key,
    student_id int      not null,
    createdAt  datetime not null,
    constraint FK_D80C7901CB944F1A
        foreign key (student_id) references Student (id)
)
    collate = utf8_unicode_ci;

create index IDX_D80C7901CB944F1A
    on Inscription (student_id);

create table if not exists Teacher
(
    id         int auto_increment
        primary key,
    details_id int not null,
    constraint UNIQ_7F4B9F49BB1A0722
        unique (details_id),
    constraint FK_7F4B9F49BB1A0722
        foreign key (details_id) references Person (id)
)
    collate = utf8_unicode_ci;

create table if not exists Session
(
    id           int auto_increment
        primary key,
    course_id    int        not null,
    teacher_id   int        not null,
    assistant_id int        null,
    academicYear int        not null,
    firstLesson  date       not null,
    lastLesson   date       not null,
    code         varchar(8) not null,
    constraint FK_1FF9EC4841807E1D
        foreign key (teacher_id) references Teacher (id),
    constraint FK_1FF9EC48591CC992
        foreign key (course_id) references Course (id),
    constraint FK_1FF9EC48E05387EF
        foreign key (assistant_id) references Assistant (id)
)
    collate = utf8_unicode_ci;

create index IDX_1FF9EC4841807E1D
    on Session (teacher_id);

create index IDX_1FF9EC48591CC992
    on Session (course_id);

create index IDX_1FF9EC48E05387EF
    on Session (assistant_id);

create table if not exists inscription_session
(
    inscription_id int not null,
    session_id     int not null,
    primary key (inscription_id, session_id),
    constraint FK_F99523385DAC5993
        foreign key (inscription_id) references Inscription (id)
            on delete cascade,
    constraint FK_F9952338613FECDF
        foreign key (session_id) references Session (id)
            on delete cascade
)
    collate = utf8_unicode_ci;

create index IDX_F99523385DAC5993
    on inscription_session (inscription_id);

create index IDX_F9952338613FECDF
    on inscription_session (session_id);

create view introductory_courses as
select *
from Course
where (required_id is null);

create view students_with_no_card as
select *
from Student
where (creditCard_id is null);

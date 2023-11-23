create or replace table docker.articles
(
    Id              int auto_increment
        primary key,
    Titre           varchar(50) null,
    Description     text        null,
    DatePublication date        null,
    Auteur          varchar(50) null,
    ImageRepository          varchar(7) null,
    ImageFileName          varchar(16) null
)
    collate = utf8mb3_unicode_ci;

create or replace table docker.users
(
    Id        int auto_increment
        primary key,
    NomPrenom varchar(50)  null,
    Email     varchar(50)  null,
    Password  varchar(255) null,
    Role      varchar(50)  null
);
create table link_document_users
(
    id            int auto_increment
        primary key,
    id_user       int                  null,
    id_document   int                  null,
    scadenza      date                 null,
    numero        text                 null,
    attach_front  text                 null,
    attach_back   text                 null,
    attach_master text                 null,
    abilis        tinyint(1) default 0 null,
    rejected      tinyint(1) default 0 null,
    cause         text                 null
);

create table link_specs_users
(
    id          int auto_increment
        primary key,
    id_user     int  null,
    id_document int  null,
    scadenza    date null,
    attach      text null
);

create table type_document
(
    id                   int auto_increment
        primary key,
    nome                 varchar(255)         null,
    scadenza             tinyint(1) default 0 null,
    numero               tinyint(1) default 0 null,
    required             tinyint(1) default 0 null,
    require_attach_front tinyint(1) default 0 null,
    require_attach_back  tinyint(1) default 0 null
);

create table type_specs
(
    id             int auto_increment
        primary key,
    nome           varchar(255)         null,
    require_attach tinyint(1) default 0 null,
    scadenza       tinyint(1) default 0 null
);

create table users
(
    id              int auto_increment
        primary key,
    usr             varchar(255)         null,
    nome            varchar(255)         null,
    cognome         varchar(255)         null,
    pwd             varchar(255)         null,
    abilis          tinyint(1) default 0 null,
    adm             tinyint(1) default 0 null,
    master          tinyint(1) default 0 null,
    mail            varchar(255)         not null,
    assocode        varchar(255)         null,
    tel             varchar(255)         null,
    CF              varchar(255)         null,
    nascita         date                 null,
    nascita_citta   varchar(255)         null,
    nascita_pr      varchar(2)           null,
    indirizzo       text                 null,
    indirizzo_cap             varchar(255)         null,
    indirizzo_citta varchar(255)         null,
    indirizzo_pr    varchar(2)           null,
    numero_socio    varchar(255)         null,
    operativo       tinyint(1) default 0 null,
    dimesso         tinyint(1) default 0 null,
    art39           tinyint(1) default 0 null,
    constraint users_mail_uindex
        unique (mail),
    constraint users_usr_uindex
        unique (usr)
);



<?php

// Users table
DATABASE->query("create table if not exists users
(
    id          int auto_increment
        primary key,
    username    varchar(255)            not null,
    email       varchar(255)            not null,
    password    varchar(255)            not null,
    authToken   varchar(255) default '' not null,
    verifyToken varchar(255) default '' not null,
    resetToken  varchar(255) default '' null,
    timeout     bigint       default 0  not null,
    authTimeout bigint       default 0  null,
    constraint users_id_uindex
        unique (id)
);");

// Items table
DATABASE->query("create table if not exists items
(
    id           int auto_increment
        primary key,
    name         varchar(255)         not null,
    description  text                 null,
    price        double     default 1 not null,
    image        varchar(255)         not null,
    creationDate datetime             not null,
    vendorId     int                  not null,
    hidden       tinyint(1) default 0 null,
    constraint items_id_uindex
        unique (id),
    constraint items_users_id_fk
        foreign key (vendorId) references users (id)
            on delete cascade
);");

// Transactions table
DATABASE->query("create table if not exists transactions
(
    id               int auto_increment
        primary key,
    client_id        int                         not null,
    item_id          int                         not null,
    vendor_id        int                         not null,
    price            double                      not null,
    status           varchar(255) default 'paid' not null,
    date             datetime                    not null,
    stripe_intent_id varchar(255)                not null,
    constraint transactions_id_uindex
        unique (id),
    constraint transactions_items_id_fk
        foreign key (item_id) references items (id),
    constraint transactions_users_id_fk
        foreign key (client_id) references users (id),
    constraint transactions_users_id_fk_2
        foreign key (vendor_id) references users (id)
);");

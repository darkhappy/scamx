<?php

// Verify if tables exist
$query = DATABASE->query("SHOW TABLES LIKE 'users'");

if ($query === false || $query->rowCount() === 0) {
  // Users table
  DATABASE->query("create table users
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

  // Set basic users
  $basicPassword = password_hash("vim-my-beloved", PASSWORD_DEFAULT);
  $query = DATABASE->prepare("insert into users (username, email, password) values ('vim', 'vim@darkh.app', ? )");
  $query->execute([$basicPassword]);

  $basicPassword = password_hash("bs-my-beloved", PASSWORD_DEFAULT);
  $query = DATABASE->prepare("insert into users (username, email, password) values ('bs', 'bs@darkh.app', ? )");
  $query->execute([$basicPassword]);

  // Items table
  DATABASE->query("create table items
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

  // Set basic items
  $date = date("Y-m-d H:i:s");
  $query = DATABASE->prepare(
    "insert into items (name, description, price, image, creationDate, vendorId) values ('Vim', 'Vim is a text editor for programmers', 42.0, 'yuki.jpeg', ?, 1);"
  );
  $query->execute([$date]);

  $query = DATABASE->prepare(
    "insert into items (name, description, price, image, creationDate, vendorId) values ('Bash', 'Bash is a shell for programmers', 42.0, 'yuki.jpeg', ?, 2);"
  );
  $query->execute([$date]);

  // Transactions table
  DATABASE->query("create table transactions
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
}

create table users
(
    id         int unsigned auto_increment
        primary key,
    email      varchar(255)         not null,
    full_name  varchar(255)         not null,
    is_active  tinyint(1) default 0 not null,
    created_at datetime             not null,
    constraint email
        unique (email)
);

create index is_active
    on users (is_active);


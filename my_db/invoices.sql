create table invoices
(
    id      int unsigned auto_increment
        primary key,
    amount  decimal(10, 4)    null,
    user_id int unsigned      null,
    status  tinyint default 1 not null,
    constraint invoices_ibfk_1
        foreign key (user_id) references users (id)
            on update cascade on delete set null
);

create index user_id
    on invoices (user_id);


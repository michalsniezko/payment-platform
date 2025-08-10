create table emails
(
    id         int unsigned auto_increment
        primary key,
    subject    text             null,
    status     tinyint unsigned null,
    text_body  longtext         null,
    html_body  longtext         null,
    meta       json             null,
    created_at datetime         null,
    sent_at    datetime         null
);


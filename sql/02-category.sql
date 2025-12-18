create table category
(
    id    integer not null
        constraint category_pk
            primary key autoincrement,
    name text not null
);

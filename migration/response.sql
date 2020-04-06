CREATE TABLE response
(
    id        int unsigned auto_increment NOT NULL,
    url       varchar(255),
    code      smallint unsigned,
    body      text,
    status    enum ('processed', 'unprocessed'),
    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME,
    PRIMARY KEY (id)
);
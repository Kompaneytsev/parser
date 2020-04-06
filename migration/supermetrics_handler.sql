CREATE TABLE supermetrics_handler
(
    id        int unsigned auto_increment NOT NULL,
    token     varchar(255),
    created_at DATETIME DEFAULT NOW(),
    PRIMARY KEY (id)
);
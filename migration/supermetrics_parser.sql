CREATE TABLE supermetrics_user
(
    id          int unsigned auto_increment NOT NULL,
    external_id varchar(255)                NOT NULL UNIQUE,
    name        varchar(255)                NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

CREATE TABLE supermetrics_message
(
    id           int unsigned auto_increment NOT NULL,
    external_id  varchar(255)                NOT NULL UNIQUE,
    user_id      int unsigned                NOT NULL,
    message      text                        NOT NULL,
    type         varchar(255)                NOT NULL,
    created_time DATETIME                    NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES supermetrics_user (id)
);
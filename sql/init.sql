DROP table ma_activities;
CREATE TABLE ma_activities
(
    id         CHAR(36) PRIMARY KEY,
    title      VARCHAR(255) NULL,
    content    TEXT         NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL
);

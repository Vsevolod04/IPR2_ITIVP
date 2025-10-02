CREATE DATABASE api_db;

USE api_db;

CREATE TABLE api_keys (
	id INT PRIMARY KEY auto_increment,
    api_key VARCHAR(255) UNIQUE,
    user_id INT,
    is_active BOOL default true
);

CREATE TABLE courses (
	id INT PRIMARY KEY auto_increment,
    title VARCHAR(255),
    instructor VARCHAR(255),
    duration_hours INT,
    price DECIMAL(10,2),
    change_user INT,
	FOREIGN KEY  (change_user) REFERENCES api_keys (id)
);
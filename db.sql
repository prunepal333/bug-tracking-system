CREATE DATABASE IF NOT EXISTS javra123_bugtracker;
USE javra123_bugtracker;
CREATE TABLE IF NOT EXISTS Bugs(
    id   INT AUTO_INCREMENT UNIQUE NOT NULL,
    title   varchar(55) NOT NULL UNIQUE,
    description    varchar(255),
    status  ENUM('pending', 'open', 'closed', 'trash'),
    disclosable TINYINT,
    PRIMARY KEY(id),
    CONSTRAINT reporterId FOREIGN KEY (id) REFERENCES Users(id)
);
CREATE TABLE IF NOT EXISTS Users(
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    email varchar(255) NOT NULL,
    pass_hash    varchar(255) NOT NULL,
    role    ENUM('admin', 'engineer', 'reporter'),
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS Profiles(
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    fname varchar(100),
    lname varchar(100),
    contact varchar(15),
    qualification varchar(20),
    PRIMARY KEY(id),
    CONSTRAINT userId FOREIGN KEY (id) REFERENCES Users(id)
);
CREATE TABLE IF NOT EXISTS Products(
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    name varchar(100) NOT NULL,
    description varchar(1000),
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS Bugs_Products(
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    bug_id INT,
    product_id INT,
    PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS Bugs_Assignee(
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    bug_id INT,
    assignee_id INT
);
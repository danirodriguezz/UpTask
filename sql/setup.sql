DROP DATABASE IF EXISTS uptask_mvc;

CREATE DATABASE uptask_mvc;

USE uptask_mvc;


CREATE TABLE usuarios(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(60) NOT NULL,
    token VARCHAR(15),
    confirmado TINYINT(1)   
)
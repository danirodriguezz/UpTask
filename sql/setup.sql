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
);

CREATE TABLE proyectos(
    id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    proyecto VARCHAR(255),
    url VARCHAR(32),
    propietarioid INT NOT NULL,
    FOREIGN KEY (propietarioid) REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE tareas(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nombre VARCHAR(255),
    estado TINYINT(1),
    proyectoId INT(11) NOT NULL,
    FOREIGN KEY (proyectoId) REFERENCES proyectos(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
CREATE DATABASE empo;

USE empo;

CREATE TABLE centro(
id_centro INT NOT NULL AUTO_INCREMENT,
nif VARCHAR(20) NOT NULL UNIQUE,
nombre VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL,
biografia TEXT,
descripcion TEXT,
imagen_personal VARCHAR(255),
email VARCHAR(50),
CONSTRAINT pk_centro PRIMARY KEY(id_centro)
);

CREATE TABLE curso(
id_curso INT NOT NULL AUTO_INCREMENT,
nombre VARCHAR(50) NOT NULL,
id_centro INT NOT NULL,
CONSTRAINT pk_curso PRIMARY KEY(id_curso),
CONSTRAINT fk_curso_centro FOREIGN KEY(id_centro) REFERENCES centro(id_centro)
);

CREATE TABLE usuario(
id_usuario INT NOT NULL AUTO_INCREMENT,
nif VARCHAR(20) NOT NULL UNIQUE,
nombre VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL,
imagen_personal VARCHAR(255),
email VARCHAR(50),
biografia TEXT,
id_centro INT NOT NULL,
CONSTRAINT pk_usuaro PRIMARY KEY(id_usuario),
CONSTRAINT fk_usuario_centro FOREIGN KEY(id_centro) REFERENCES centro(id_centro)
);

CREATE TABLE profesor(
id_profesor INT NOT NULL AUTO_INCREMENT,
id_usuario INT NOT NULL,
CONSTRAINT pk_profesor PRIMARY KEY(id_profesor),
CONSTRAINT fk_profesor_usuario FOREIGN KEY(id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE alumno(
id_alumno INT NOT NULL AUTO_INCREMENT,
id_usuario INT NOT NULL,
id_curso INT NOT NULL,
CONSTRAINT pk_alumno PRIMARY KEY(id_alumno),
CONSTRAINT fk_alumno_usuario FOREIGN KEY(id_usuario) REFERENCES usuario(id_usuario),
CONSTRAINT fk_alumno_curso FOREIGN KEY(id_curso) REFERENCES curso(id_curso)
);

CREATE TABLE asignatura(
id_asignatura INT NOT NULL AUTO_INCREMENT,
nombre VARCHAR(50) NOT NULL,
id_curso INT NOT NULL,
CONSTRAINT pk_asignatura PRIMARY KEY(id_asignatura),
CONSTRAINT fk_asignatura_curso FOREIGN KEY(id_curso) REFERENCES curso(id_curso)
);

CREATE TABLE profesor_vs_asignatura(
id_profesor INT NOT NULL,
id_asignatura INT NOT NULL,
CONSTRAINT fk_profesor_vs_asignatura_profesor FOREIGN KEY(id_profesor) REFERENCES profesor(id_profesor),
CONSTRAINT fk_profesor_vs_asignatura_asignatura FOREIGN KEY(id_asignatura) REFERENCES asignatura(id_asignatura)
);

CREATE TABLE post(
id_post INT NOT NULL AUTO_INCREMENT,
titulo VARCHAR(50) NOT NULL,
cuerpo TEXT NOT NULL,
fecha DATE NOT NULL,
cerrado BOOLEAN NOT NULL,
id_alumno INT NOT NULL,
CONSTRAINT pk_post PRIMARY KEY(id_post),
CONSTRAINT fk_post_alumno FOREIGN KEY(id_alumno) REFERENCES alumno(id_alumno)
);

CREATE TABLE tema(
id_tema INT NOT NULL AUTO_INCREMENT,
nombre VARCHAR(50) NOT NULL UNIQUE,
CONSTRAINT pk_tema PRIMARY KEY(id_tema)
);

CREATE TABLE post_vs_tema(
id_post INT NOT NULL,
id_tema INT NOT NULL,
CONSTRAINT fk_post_vs_tema_post FOREIGN KEY(id_post) REFERENCES post(id_post),
CONSTRAINT fk_post_vs_tema_tema FOREIGN KEY(id_tema) REFERENCES tema(id_tema)
);

CREATE TABLE respuesta(
id_respuesta INT NOT NULL AUTO_INCREMENT,
asunto VARCHAR(50) NOT NULL,
texto TEXT NOT NULL,
fecha DATE NOT NULL,
id_post INT NOT NULL,
id_usuario INT NOT NULL,
id_respuesta_padre INT NOT NULL,
CONSTRAINT pk_respuesta PRIMARY KEY(id_respuesta),
CONSTRAINT fk_respuesta_post FOREIGN KEY(id_post) REFERENCES post(id_post),
CONSTRAINT fk_respuesta_usuario FOREIGN KEY(id_usuario) REFERENCES usuario(id_usuario),
CONSTRAINT fk_respuesta_padre FOREIGN KEY(id_respuesta_padre) REFERENCES respuesta(id_respuesta)
);

CREATE TABLE respuesta_reportada(
id_reporte INT NOT NULL AUTO_INCREMENT,
id_respuesta INT NOT NULL,
tipo_reporte VARCHAR(50) NOT NULL,
descripcion TEXT,
id_usuario INT NOT NULL,
CONSTRAINT pk_respuesta_reportada PRIMARY KEY (id_reporte),
CONSTRAINT fk_respuesta_reportada_respuesta FOREIGN KEY (id_respuesta) REFERENCES respuesta(id_respuesta),
CONSTRAINT fk_respuesta_reportada_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE respuesta_guardada(
id_guardado INT NOT NULL AUTO_INCREMENT,
id_respuesta INT NOT NULL,
id_usuario INT NOT NULL,
CONSTRAINT pk_id_guardado PRIMARY KEY (id_guardado),
CONSTRAINT fk_respuesta_guardada_respuesta FOREIGN KEY (id_respuesta) REFERENCES respuesta(id_respuesta),
CONSTRAINT fk_respuesta_guardada_usuario FOREIGN KEY (Id_usuario) REFERENCES usuario(id_usuario)
);
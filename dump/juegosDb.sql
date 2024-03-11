-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2023 a las 11:11:58
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `log` varchar(240) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `juegos` (
  `id` varchar(10) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `plataforma` varchar(10) NOT NULL,
  `anno` varchar(4) NOT NULL,
  `nota` varchar(2) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('1',1,'FC 24','Ps5','2023', '5', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('2',1,'BLACK OPS III','PS4','2015', '8', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('3',1,'ROCKET LEAGUE','PS4','2015', '7', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('4',1,'GTA V','PS4','2013', '9.5', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('5',1,'GOLF IT','PC','2023', '9', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('6',1,'CLASH ROYALE','MOVIL','2016', '10', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('7',1,'SPIDERMAN 2','Ps5','2023', '9', NULL),
INSERT INTO `juegos` (`id`,`id_usuario`, `nombre`, `plataforma`, `anno`, `nota`,`imagen`) VALUES ('8',1,'GOD OF WAR','Ps5','2022', '8', NULL),


CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL COMMENT 'clave principal',
  `email` varchar(150) NOT NULL,
  `password` varchar(240) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL,
  `token` varchar(240) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='tabla de usuarios';



INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`,`imagen`,`disponible`, `token`) VALUES (1,'jredondop@ejemplo.com', '6e89996fccb6f42b37b173f362194d498f34092696528a3ea26289371058ce18', 'javier', NULL, 1, NULL);


ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `juegos` ADD PRIMARY KEY (`id`);


ALTER TABLE `usuarios`ADD PRIMARY KEY (`id`);


ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;


ALTER TABLE `juegos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clave principal', AUTO_INCREMENT=12;
COMMIT;



-- Ruta Docker Desltop: 172.19.0.4

/*

{
    "email" : "rafi@ejemplo.com",
    "password" : "rafi",
    "nombre" : "rafi",
    "disponible":1,
    "imagen" : "NULL"
}

*/
-- Servidor: localhost
-- Tiempo de generación: 13-04-2021 a las 18:43:36
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 8.0.3

CREATE TABLE `roles` (
  `id` int AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME
);

INSERT INTO `roles`(`id`, `name`) VALUES (1, 'ADMINISTRADOR');
INSERT INTO `roles`(`id`, `name`) VALUES (2, 'MIEMBRO');


CREATE TABLE `users` (
  `id` int AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME,
  `id_rol` int(11) NOT NULL,
  FOREIGN KEY (`id_rol`) REFERENCES roles(`id`)
);

INSERT INTO `users`(`id`, `username`, `email`, `password`, `id_rol`) VALUES (1, 'jperez', 'jperez@gmail.com', MD5('jperez'), 1);
INSERT INTO `users`(`id`, `username`, `email`, `password`, `id_rol`) VALUES (2, 'aflores', 'aflores@gmail.com', MD5('aflores'), 1);
INSERT INTO `users`(`id`, `username`, `email`, `password`, `id_rol`) VALUES (3, 'mlopez', 'mlopez@gmail.com', MD5('mlopez'), 2);
INSERT INTO `users`(`id`, `username`, `email`, `password`, `id_rol`) VALUES (4, 'uromero', 'uromero@gmail.com', MD5('uromero'), 2);
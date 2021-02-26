-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-02-2021 a las 15:51:44
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_projecte04`
--
CREATE DATABASE IF NOT EXISTS `bd_projecte04` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bd_projecte04`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_categoria`
--

CREATE TABLE `tbl_categoria` (
  `Id_categoria` int(5) NOT NULL,
  `Nom_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_categoria`
--

INSERT INTO `tbl_categoria` (`Id_categoria`, `Nom_categoria`) VALUES
(74, 'Aceptan perros'),
(73, 'Comida a domicilio'),
(72, 'VIP');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_comentari`
--

CREATE TABLE `tbl_comentari` (
  `Id_comentari` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL,
  `Id_usuari` int(5) NOT NULL,
  `Comentari` text COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_comentari`
--

INSERT INTO `tbl_comentari` (`Id_comentari`, `Id_restaurant`, `Id_usuari`, `Comentari`) VALUES
(2, 1, 4, 'Muy bueno, volveremos 100%.'),
(3, 3, 5, 'Horrible, jamás volvería…');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cuina`
--

CREATE TABLE `tbl_cuina` (
  `Id_cuina` int(5) NOT NULL,
  `Nom_cuina` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_cuina`
--

INSERT INTO `tbl_cuina` (`Id_cuina`, `Nom_cuina`) VALUES
(1, 'Japonés'),
(2, 'Argentino'),
(3, 'Chino'),
(4, 'Mediterráneo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_favorit`
--

CREATE TABLE `tbl_favorit` (
  `Id_favorit` int(5) NOT NULL,
  `Id_usuari` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_favorit`
--

INSERT INTO `tbl_favorit` (`Id_favorit`, `Id_usuari`, `Id_restaurant`) VALUES
(10, 6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_imatge`
--

CREATE TABLE `tbl_imatge` (
  `Id_imatge` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL,
  `Id_usuari` int(5) DEFAULT NULL,
  `Ruta_Text_Imatge` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Titol` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_imatge`
--

INSERT INTO `tbl_imatge` (`Id_imatge`, `Id_restaurant`, `Id_usuari`, `Ruta_Text_Imatge`, `Titol`) VALUES
(5, 6, 6, 'uploads/SFResjZNOsBQzpebYl65S7M7JFXZmOmAifmu3uoz.jpg', 'L\'home dels Nassos'),
(6, 7, 6, 'uploads/Oijsxi9DxfD8mDYnVADdVvr8FfEiBLTSjbrOO6i7.jpg', 'Fismuler'),
(7, 12, 6, 'uploads/j6zWl3NpgPhAnlp7MCwigpF6D0hd4A3N7Op7We53.jpg', 'Kibotaberna'),
(8, 10, 6, 'uploads/DTt2WlWiCMNjPXO5RyqPF6KMBlgNMrS3HKAbFT7Z.jpg', 'Ziqi'),
(9, 11, 6, 'uploads/h0sIvehzepazG0el0w27pYntZdrp7jJdZsqhrKTp.jpg', 'Da Zhong'),
(10, 13, 6, 'uploads/1YlAkpyBPgNE0exEuS0lHC1rZBbQQLeMpneCzoDp.jpg', 'disfrutar'),
(11, 14, 6, 'uploads/A70ROpZHMfsHQNvs68dJgprKU5d2ttDU6pkSLIXX.jpg', 'Casa Paloma'),
(12, 15, 6, 'uploads/OB0XT71bsCDg0S2QnM63ckz6tuSouMhbrWkID8Bx.jpg', 'ekubo'),
(13, 16, 6, 'uploads/Uk3eWn4cg3OZnIkaqJjLLBnAbPTxjz93NWPtBB2K.jpg', 'El Argentino'),
(14, 17, 6, 'uploads/9El2WYnOmAKFz3oPSY882fguBMMjn6WgfSkNUSqq.jpg', 'Sato i Tanaka'),
(15, 18, 6, 'uploads/W3UL3Sm5VRQ3C6EylURG4ju1yirKbiDkXLvlZd6h.jpg', 'Slow & Low'),
(16, 19, 6, 'uploads/0G5LGeYvzeVxWHByk4MYH1k2upXPO1uCNlCHwNcs.jpg', 'El calafate'),
(17, 20, 6, 'uploads/aldPih1AJJhQNSUv5XDPcRJV372kC2kqYFwIvqsK.jpg', 'Patagonia Beet & Wine'),
(18, 21, 6, 'uploads/pTMNFAvyo22wZJcD07bbzc6BuFJ0uq3Ce397a3Yu.jpg', 'Chi Nanit'),
(19, 22, 6, 'uploads/SjDEZFjheudQ4VriiYozo7EtNOfPum1EVcu1rUpm.jpg', 'Shanghai'),
(20, 23, 6, 'uploads/Eop498rhFHWbS00soL4DuXwmD0lkbrBFSfunuEz4.jpg', 'Makes Taperia'),
(21, 24, 6, 'uploads/ql223WJIN1GhwCtjEiLGSWQ8TvDndJAxWmEzLqem.jpg', 'Liuyishou@gerente.com'),
(22, 25, 6, 'uploads/oqJwAySGc7XWBHg60vljvRxU2NQAL3ctcjEfIyrO.jpg', '9reinas@gerente.com'),
(23, 26, 6, 'uploads/IvVYCZK73GSDLh3V0yHqfWCi54wFtoauqQLCBkCv.jpg', 'sichuan'),
(24, 27, 6, 'uploads/XTPbmjqBetioVtdv6CTXZpsDTUE7WHAZtBse1DXL.jpg', 'minamo'),
(26, 2, 6, 'uploads/vn3jmouCUGZY4BO82trxcC0eLjoB8PxQxAlB7nVW.jpg', 'Al punt'),
(27, 3, 6, 'uploads/Yf5QmFvwcycp46QyPjTNmhXqKdOSDBJ75ktCknKm.jpg', 'Koy Shunka'),
(28, 1, 6, 'uploads/YMAxACfVtKCdcaYyOPEMJFQ427BCauE6275nI35F.jpg', 'Cuina Deu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_restaurant`
--

CREATE TABLE `tbl_restaurant` (
  `Id_restaurant` int(5) NOT NULL,
  `Nom_restaurant` varchar(250) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Valoracio` decimal(2,1) DEFAULT NULL,
  `Ciutat_restaurant` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Adreca_restaurant` varchar(250) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `CP_restaurant` varchar(5) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Preu_mitja_restaurant` decimal(6,2) NOT NULL,
  `Correu_gerent_restaurant` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Nom_gerent_restaurant` varchar(200) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `Descripcio_restaurant` text COLLATE utf8mb4_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_restaurant`
--

INSERT INTO `tbl_restaurant` (`Id_restaurant`, `Nom_restaurant`, `Valoracio`, `Ciutat_restaurant`, `Adreca_restaurant`, `CP_restaurant`, `Preu_mitja_restaurant`, `Correu_gerent_restaurant`, `Nom_gerent_restaurant`, `Descripcio_restaurant`) VALUES
(1, 'Cuina Deu', '5.0', 'L\'Hospitalet de Llobregat', 'Passatge oliveres, 11', '08904', '25.00', 'cuinadeu@gerente.com', 'Peter', 'El restaurante Cuina Deu, cocina una amplia gama de platos provenientes de china.'),
(2, 'Al punt', NULL, 'L\'Hospitalet de Llobregat', 'Carrer de Girona, 51', '08009', '10.00', 'alpunt@gerente.com', 'Oscar', 'Recetas mediterráneas y argentinas, una combinación explosiva y exquisita.'),
(3, 'Koy Shunka', '2.0', 'Barcelona', 'C/Copons, 7', '08002', '30.00', 'koyshunka@gerente.com', 'Lee', 'Comida japonesa, preparada y pulida por los mejores chefs japoneses.'),
(6, 'L\'home dels Nassos', NULL, 'L\'Hospitalet de Llobregat', 'Carrer de Melcior de Palau, 62', '08028', '10.00', 'lopez@gerente.com', 'Micheal', 'Es un restaurante de los más original y creativo. Si te interesa probar platos que nunca pensaste en probar, el restaurante L\'Home dels Nassos es el indicado.'),
(7, 'Fismuler', NULL, 'Barcelona', 'Carrer de Rec Comtal,17', '08003', '14.00', 'mrfis@gerente.com', 'Tom', 'Tiene el mejor producto fresco, es por eso que lo presentan en platos muy creativos, pero tradicionales a la vez.'),
(10, 'Ziqi', NULL, 'Barcelona', 'Av. De Mistral, 44', '08015', '9.99', 'Ziqi@gerente.com', 'Angela', 'Mezclan lo mejor de la comida japonesa con un carácter auténtico y cercano.'),
(11, 'Da Zhong', NULL, 'Barcelona', 'Carrer d\'Alí Bei, 34', '08013', '13.00', 'Lee@gerente.com', 'Sora', 'Se puede encontrar una increíble variedad de platos tan exóticos como desconocidos'),
(12, 'Kibotaberna', NULL, 'L\'Hospitalet de Llobregat', 'Carrer de Sant Eusebi, 38', '08907', '12.00', 'kibo@gerente.com', 'Kim', 'Lo cuidadoso selección de los ingredientes permite lograr el sabor de sus creación propias, originales, donde hay tradición, pero también innovación.'),
(13, 'Disfrutar', NULL, 'Barcelona', 'Carrer de Sant Eusebi, 38', '08006', '14.00', 'disfrutar@gerente.com', 'Marcos', 'Unos platos exquisitos, que os darán ganas de volver a pedir.'),
(14, 'Casa Paloma', NULL, 'Barcelona', 'Carrer Casanova, 209', '08021', '10.49', 'casapaloma@gerente.com', 'Trevor', 'Acumula fama gracias a que ofrece muchas variedades de carne; Entre carne de Irlanda hasta carne Gallega.'),
(15, 'Ekubo', NULL, 'Barcelona', 'Carrer del Comte d\'Urgell, 108', '08011', '9.00', 'ekubo@gerente.com', 'Jun', 'Una infinidad de sabores exóticos que te harán trasladarte al mismísimo corazón de Japón.'),
(16, 'El Argentino', NULL, 'L\'Hospitalet de Llobregat', 'Carrer del Doctor Martí Julià, 167', '08903', '7.00', 'elargentino@gerente.com', 'Patrick', 'De las mejores carnes de Argentina, son especialistas en las carnes de la Pampa a la brasa.'),
(17, 'Sato i Tanaka', NULL, 'Barcelona', 'Carrer Bruc, 79', '08009', '12.00', 'satoitanaka@gerente.com', 'Sato', 'Si lo que buscáis pedir son unos Nigiris, este restaurante es el indicado, lo hacen de una manera tan suculenta, que hará que vuelvas a pedir.'),
(18, 'Slow & Low', NULL, 'Barcelona', 'Carrer Comte Borrell, 119', '08015', '11.00', 'SlowLow@gerente.com', 'Roy', 'Se considera ecléctico porque adopta ideas de diversas culturas y gastronomias, aunque su concepto culiniario es el mediterranio.'),
(19, 'El calafate', NULL, 'Barcelona', 'Carrer de París, 145', '08036', '10.50', 'elcalafate@gerente.com', 'Olivia', 'Una comida argentina exquisita, con una carne de alta calidad para disfrutarla en casa.'),
(20, 'Patagonia Beet & Wine', NULL, 'Barcelona', 'Gran Vía de les Corts Catalanes, 660', '08010', '9.00', 'patagonia@gerente.com', 'Jimmy', 'Una de los mejores restaurante de comida argentina de Barcelona, su carne bien sazonada con un sabor espectacular.'),
(21, 'Xinés Feliç', NULL, 'L\'Hospitalet de Llobregat', 'Carrer Santa Eulàlia, 234', '08902', '14.00', 'Chinanit@gerente.com', 'Wan', 'Tiene unos quince platos diferentes en la carta; entre  shaomai hasta su especialidad, los Dumplings.'),
(22, 'Shanghai', NULL, 'Barcelona', 'C/ Bisbe Sivilla, 48', '08022', '20.00', 'Shangai@gerente.com', 'Lei', 'La carta de este restaurante cada día te sorprende más, porque varia según la temporada. Cada plato se realiza de una forma delicada y exquisita.'),
(23, 'Makes Taperia', NULL, 'Barcelona', 'Avenida de Madrid, 160', '08028', '8.00', 'makestaperia@gerente.com', 'Rebecca', 'Unos buenos platos de lo mejor del mediterráneo; Sabroso, exquisito, etc...'),
(24, 'Liuyishou hotpot', NULL, 'Barcelona', 'C. del Consell de Cent, 303', '08007', '6.90', 'Liuyishou@gerente.com', 'Sara', 'Carnes, mariscos o verduras en Hotpot chino con caldos de sabores diferentes, para cualquier gusto.'),
(25, '9 Reinas', NULL, 'Barcelona', 'Carrer de València, 267', '08007', '13.00', '9Reinas@gerente.com', 'Louis', 'Recetas Argentinas y carnes del mundo, cada bocado hará que vayas directamente a Argentina.'),
(26, 'Sichuan', NULL, 'Barcelona', 'Carrer de la Diputació, 172', '08011', '6.00', 'sichuan@gerente.com', 'Toni', 'Autentica comida china, donde su mayor especialidad son los sichuan.'),
(27, 'Minamo', NULL, 'Barcelona', 'Carrer del Bruc, 65', '08009', '10.20', 'minamo@gerente.com', 'Shiko', 'Elegante cocinado japonés, uno de los mejores en preparar sushi; Un gran sabor y una exquisita presentación.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_rol`
--

CREATE TABLE `tbl_rol` (
  `Id_rol` int(5) NOT NULL,
  `Nom_rol` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_rol`
--

INSERT INTO `tbl_rol` (`Id_rol`, `Nom_rol`) VALUES
(1, 'admin'),
(2, 'estandard');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tag`
--

CREATE TABLE `tbl_tag` (
  `Id_tag` int(5) NOT NULL,
  `Nom_tag` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_tag`
--

INSERT INTO `tbl_tag` (`Id_tag`, `Nom_tag`) VALUES
(32, 'bueno'),
(39, 'caro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tag_intermitja`
--

CREATE TABLE `tbl_tag_intermitja` (
  `Id_tag_restaurant` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL,
  `Id_tag` int(5) NOT NULL,
  `Id_usuari` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_tag_intermitja`
--

INSERT INTO `tbl_tag_intermitja` (`Id_tag_restaurant`, `Id_restaurant`, `Id_tag`, `Id_usuari`) VALUES
(36, 1, 32, 6),
(57, 10, 32, 6),
(58, 14, 32, 6),
(60, 1, 39, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tipus_categoria`
--

CREATE TABLE `tbl_tipus_categoria` (
  `Id_tipus_categoria` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL,
  `Id_categoria` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tipus_cuina`
--

CREATE TABLE `tbl_tipus_cuina` (
  `Id_tipus_cuina` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL,
  `Id_cuina` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_tipus_cuina`
--

INSERT INTO `tbl_tipus_cuina` (`Id_tipus_cuina`, `Id_restaurant`, `Id_cuina`) VALUES
(41, 27, 1),
(62, 2, 1),
(63, 2, 3),
(64, 3, 1),
(65, 6, 4),
(66, 7, 4),
(67, 10, 1),
(68, 11, 3),
(69, 12, 1),
(70, 13, 3),
(71, 14, 4),
(72, 15, 1),
(73, 16, 2),
(74, 17, 1),
(75, 18, 4),
(76, 19, 2),
(77, 20, 2),
(78, 21, 3),
(79, 22, 3),
(80, 23, 4),
(81, 24, 3),
(82, 25, 2),
(83, 26, 3),
(93, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuari`
--

CREATE TABLE `tbl_usuari` (
  `Id_usuari` int(5) NOT NULL,
  `Id_rol` int(5) NOT NULL,
  `Correu_usuari` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Pwd_usuari` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `Nom_usuari` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `Cognom_usuari` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_usuari`
--

INSERT INTO `tbl_usuari` (`Id_usuari`, `Id_rol`, `Correu_usuari`, `Pwd_usuari`, `Nom_usuari`, `Cognom_usuari`) VALUES
(3, 1, 'admin@gmail.com', '1234', 'Admin', '-'),
(4, 2, 'alex-rodri@gmail.es', '1234', 'Alex', 'Rodriguez'),
(5, 2, 'juditcava@gmail.com', '1234', 'Judit', 'Castedo'),
(6, 2, 'xavijvives@gmail.com', '1234', 'Xavi', 'Jaramillo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_valoracio`
--

CREATE TABLE `tbl_valoracio` (
  `Id_valoracio` int(5) NOT NULL,
  `Id_restaurant` int(5) NOT NULL,
  `Id_usuari` int(5) NOT NULL,
  `Valoracio` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tbl_valoracio`
--

INSERT INTO `tbl_valoracio` (`Id_valoracio`, `Id_restaurant`, `Id_usuari`, `Valoracio`) VALUES
(4, 1, 4, 5),
(5, 3, 5, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`Id_categoria`),
  ADD UNIQUE KEY `Nom_Categoria_Unico` (`Nom_categoria`);

--
-- Indices de la tabla `tbl_comentari`
--
ALTER TABLE `tbl_comentari`
  ADD PRIMARY KEY (`Id_comentari`),
  ADD KEY `FK_Comentari_Restaurant` (`Id_restaurant`),
  ADD KEY `FK_Comentari_Usuari` (`Id_usuari`);

--
-- Indices de la tabla `tbl_cuina`
--
ALTER TABLE `tbl_cuina`
  ADD PRIMARY KEY (`Id_cuina`);

--
-- Indices de la tabla `tbl_favorit`
--
ALTER TABLE `tbl_favorit`
  ADD PRIMARY KEY (`Id_favorit`),
  ADD KEY `Id_usuari` (`Id_usuari`),
  ADD KEY `Id_restaurant` (`Id_restaurant`);

--
-- Indices de la tabla `tbl_imatge`
--
ALTER TABLE `tbl_imatge`
  ADD PRIMARY KEY (`Id_imatge`),
  ADD KEY `FK_Imatge_Restaurant` (`Id_restaurant`),
  ADD KEY `FK_Imatge_Usuari` (`Id_usuari`);

--
-- Indices de la tabla `tbl_restaurant`
--
ALTER TABLE `tbl_restaurant`
  ADD PRIMARY KEY (`Id_restaurant`);

--
-- Indices de la tabla `tbl_rol`
--
ALTER TABLE `tbl_rol`
  ADD PRIMARY KEY (`Id_rol`);

--
-- Indices de la tabla `tbl_tag`
--
ALTER TABLE `tbl_tag`
  ADD PRIMARY KEY (`Id_tag`);

--
-- Indices de la tabla `tbl_tag_intermitja`
--
ALTER TABLE `tbl_tag_intermitja`
  ADD PRIMARY KEY (`Id_tag_restaurant`),
  ADD KEY `Id_restaurant` (`Id_restaurant`),
  ADD KEY `Id_tag` (`Id_tag`),
  ADD KEY `Id_usuari` (`Id_usuari`);

--
-- Indices de la tabla `tbl_tipus_categoria`
--
ALTER TABLE `tbl_tipus_categoria`
  ADD PRIMARY KEY (`Id_tipus_categoria`),
  ADD KEY `Id_restaurant` (`Id_restaurant`,`Id_categoria`),
  ADD KEY `Id_categoria` (`Id_categoria`);

--
-- Indices de la tabla `tbl_tipus_cuina`
--
ALTER TABLE `tbl_tipus_cuina`
  ADD PRIMARY KEY (`Id_tipus_cuina`),
  ADD KEY `FK_tipus_cuina_restaurant` (`Id_restaurant`),
  ADD KEY `FK_tipus_cuina_cuina` (`Id_cuina`);

--
-- Indices de la tabla `tbl_usuari`
--
ALTER TABLE `tbl_usuari`
  ADD PRIMARY KEY (`Id_usuari`),
  ADD KEY `FK_usuari_rol` (`Id_rol`);

--
-- Indices de la tabla `tbl_valoracio`
--
ALTER TABLE `tbl_valoracio`
  ADD PRIMARY KEY (`Id_valoracio`),
  ADD KEY `FK_valoracio_restaurant` (`Id_restaurant`),
  ADD KEY `FK_valoracio_usuari` (`Id_usuari`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `Id_categoria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `tbl_comentari`
--
ALTER TABLE `tbl_comentari`
  MODIFY `Id_comentari` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_cuina`
--
ALTER TABLE `tbl_cuina`
  MODIFY `Id_cuina` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_favorit`
--
ALTER TABLE `tbl_favorit`
  MODIFY `Id_favorit` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbl_imatge`
--
ALTER TABLE `tbl_imatge`
  MODIFY `Id_imatge` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `tbl_restaurant`
--
ALTER TABLE `tbl_restaurant`
  MODIFY `Id_restaurant` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `tbl_rol`
--
ALTER TABLE `tbl_rol`
  MODIFY `Id_rol` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_tag`
--
ALTER TABLE `tbl_tag`
  MODIFY `Id_tag` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `tbl_tag_intermitja`
--
ALTER TABLE `tbl_tag_intermitja`
  MODIFY `Id_tag_restaurant` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `tbl_tipus_categoria`
--
ALTER TABLE `tbl_tipus_categoria`
  MODIFY `Id_tipus_categoria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tbl_tipus_cuina`
--
ALTER TABLE `tbl_tipus_cuina`
  MODIFY `Id_tipus_cuina` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT de la tabla `tbl_usuari`
--
ALTER TABLE `tbl_usuari`
  MODIFY `Id_usuari` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tbl_valoracio`
--
ALTER TABLE `tbl_valoracio`
  MODIFY `Id_valoracio` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_comentari`
--
ALTER TABLE `tbl_comentari`
  ADD CONSTRAINT `FK_Comentari_Restaurant` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`),
  ADD CONSTRAINT `FK_Comentari_Usuari` FOREIGN KEY (`Id_usuari`) REFERENCES `tbl_usuari` (`Id_usuari`);

--
-- Filtros para la tabla `tbl_favorit`
--
ALTER TABLE `tbl_favorit`
  ADD CONSTRAINT `tbl_favorit_ibfk_1` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_favorit_ibfk_2` FOREIGN KEY (`Id_usuari`) REFERENCES `tbl_usuari` (`Id_usuari`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_imatge`
--
ALTER TABLE `tbl_imatge`
  ADD CONSTRAINT `FK_Imatge_Restaurant` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`),
  ADD CONSTRAINT `FK_Imatge_Usuari` FOREIGN KEY (`Id_usuari`) REFERENCES `tbl_usuari` (`Id_usuari`);

--
-- Filtros para la tabla `tbl_tag_intermitja`
--
ALTER TABLE `tbl_tag_intermitja`
  ADD CONSTRAINT `tbl_tag_intermitja_ibfk_1` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_tag_intermitja_ibfk_2` FOREIGN KEY (`Id_tag`) REFERENCES `tbl_tag` (`Id_tag`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_tag_intermitja_ibfk_3` FOREIGN KEY (`Id_usuari`) REFERENCES `tbl_usuari` (`Id_usuari`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_tipus_categoria`
--
ALTER TABLE `tbl_tipus_categoria`
  ADD CONSTRAINT `tbl_tipus_categoria_ibfk_1` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`),
  ADD CONSTRAINT `tbl_tipus_categoria_ibfk_2` FOREIGN KEY (`Id_categoria`) REFERENCES `tbl_categoria` (`Id_categoria`);

--
-- Filtros para la tabla `tbl_tipus_cuina`
--
ALTER TABLE `tbl_tipus_cuina`
  ADD CONSTRAINT `FK_tipus_cuina_cuina` FOREIGN KEY (`Id_cuina`) REFERENCES `tbl_cuina` (`Id_cuina`),
  ADD CONSTRAINT `FK_tipus_cuina_restaurant` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`);

--
-- Filtros para la tabla `tbl_usuari`
--
ALTER TABLE `tbl_usuari`
  ADD CONSTRAINT `FK_usuari_rol` FOREIGN KEY (`Id_rol`) REFERENCES `tbl_rol` (`Id_rol`);

--
-- Filtros para la tabla `tbl_valoracio`
--
ALTER TABLE `tbl_valoracio`
  ADD CONSTRAINT `FK_valoracio_restaurant` FOREIGN KEY (`Id_restaurant`) REFERENCES `tbl_restaurant` (`Id_restaurant`),
  ADD CONSTRAINT `FK_valoracio_usuari` FOREIGN KEY (`Id_usuari`) REFERENCES `tbl_usuari` (`Id_usuari`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

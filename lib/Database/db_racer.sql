-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 04-Jul-2019 às 16:58
-- Versão do servidor: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_racer`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_corrida`
--

CREATE TABLE `tb_corrida` (
  `cd_corrida` int(11) NOT NULL,
  `vl_corrida` decimal(10,2) DEFAULT NULL,
  `vl_distancia` float DEFAULT NULL,
  `dt_hr_inicio` datetime NOT NULL,
  `dt_hr_termino` datetime DEFAULT NULL,
  `bln_status_pagamento` tinyint(1) NOT NULL,
  `bln_status_corrida` tinyint(1) NOT NULL,
  `cd_motorista` int(11) NOT NULL,
  `cd_passageiro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_motorista`
--

CREATE TABLE `tb_motorista` (
  `cd_motorista` int(11) NOT NULL,
  `nm_motorista` varchar(180) NOT NULL,
  `dt_nascimento` date NOT NULL,
  `nm_sexo` varchar(10) NOT NULL,
  `cd_documento` varchar(15) NOT NULL,
  `nm_modelo` varchar(50) NOT NULL,
  `bln_status` tinyint(1) NOT NULL,
  `bln_status_corrida` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_passageiro`
--

CREATE TABLE `tb_passageiro` (
  `cd_passageiro` int(11) NOT NULL,
  `nm_passageiro` varchar(150) NOT NULL,
  `dt_nascimento` date NOT NULL,
  `nm_sexo` varchar(10) NOT NULL,
  `cd_documento` varchar(15) NOT NULL,
  `bln_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_corrida`
--
ALTER TABLE `tb_corrida`
  ADD PRIMARY KEY (`cd_corrida`),
  ADD KEY `cd_motorista_fk` (`cd_motorista`),
  ADD KEY `cd_passageiro_fk` (`cd_passageiro`) USING BTREE;

--
-- Indexes for table `tb_motorista`
--
ALTER TABLE `tb_motorista`
  ADD PRIMARY KEY (`cd_motorista`),
  ADD UNIQUE KEY `cd_documento` (`cd_documento`);

--
-- Indexes for table `tb_passageiro`
--
ALTER TABLE `tb_passageiro`
  ADD PRIMARY KEY (`cd_passageiro`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_corrida`
--
ALTER TABLE `tb_corrida`
  MODIFY `cd_corrida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `tb_motorista`
--
ALTER TABLE `tb_motorista`
  MODIFY `cd_motorista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tb_passageiro`
--
ALTER TABLE `tb_passageiro`
  MODIFY `cd_passageiro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--
--
-- Limitadores para a tabela `tb_corrida`
--
ALTER TABLE `tb_corrida`
  ADD CONSTRAINT `cd_motorista_fk` FOREIGN KEY (`cd_motorista`) REFERENCES `tb_motorista` (`cd_motorista`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cd_passageiro_fk` FOREIGN KEY (`cd_passageiro`) REFERENCES `tb_passageiro` (`cd_passageiro`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/11/2025 às 03:12
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `univesp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `sm_empresas`
--

CREATE TABLE `sm_empresas` (
  `codEmp` int(11) NOT NULL,
  `cnpj` bigint(14) UNSIGNED ZEROFILL NOT NULL,
  `fkEndereco` int(11) DEFAULT NULL,
  `nome` varchar(180) NOT NULL,
  `data_abertura` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` char(11) DEFAULT NULL,
  `foto` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `sm_empresas`
--

INSERT INTO `sm_empresas` (`codEmp`, `cnpj`, `fkEndereco`, `nome`, `data_abertura`, `email`, `telefone`, `foto`) VALUES
(1, 45787660000100, 1, 'MUNICIPIO DE SUMARE', NULL, 'adm@xyz.com.br', '1999999997', 'logotipo.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sm_enderecos`
--

CREATE TABLE `sm_enderecos` (
  `codEnd` int(11) NOT NULL,
  `cep` char(8) DEFAULT NULL,
  `rua` varchar(150) DEFAULT NULL,
  `num` char(5) DEFAULT NULL,
  `bairro` varchar(150) DEFAULT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `cidade` varchar(64) DEFAULT NULL,
  `uf` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO','EX') DEFAULT NULL,
  `data_criada` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `sm_enderecos`
--

INSERT INTO `sm_enderecos` (`codEnd`, `cep`, `rua`, `num`, `bairro`, `complemento`, `cidade`, `uf`, `data_criada`) VALUES
(1, '13170002', 'Rua Dom Barreto', '1303', 'Jardim Nova Veneza ', 'terreo', 'SUMARE', 'SP', '2025-11-02 19:43:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sm_pessoas`
--

CREATE TABLE `sm_pessoas` (
  `codPes` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` bigint(11) UNSIGNED ZEROFILL NOT NULL,
  `email` varchar(100) NOT NULL,
  `celular` char(11) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `fkEndereco` int(11) DEFAULT NULL,
  `estado_civil` enum('SOLTEIRO','CASADO','DIVORCIADO','SEPARADO','VIUVO','DESQUITADO','AMASIADO') DEFAULT NULL,
  `escolaridade` enum('analfabeto','ensino_fundamental_incompleto','ensino_fundamental_completo','ensino_medio_incompleto','ensino_medio_completo','ensino_superior_incompleto','ensino_superior_completo','especializacao','mestrado','doutorado','pos_doutorado') DEFAULT NULL,
  `profissao` varchar(100) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `data_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `sm_pessoas`
--

INSERT INTO `sm_pessoas` (`codPes`, `nome`, `cpf`, `email`, `celular`, `data_nasc`, `sexo`, `fkEndereco`, `estado_civil`, `escolaridade`, `profissao`, `data_cadastro`, `data_update`) VALUES
(1, 'Fulano da Silva Sauro', 12345678909, 'fulano@teste.com', '19997777070', '1989-05-18', 'M', NULL, 'SOLTEIRO', 'ensino_superior_completo', NULL, '2025-11-05 20:52:00', '2025-11-04 01:09:16'),
(6, 'Ciclano da Silsa Sauro', 00000000191, 'ciclano@teste.com', '19997777073', '1991-04-18', 'M', NULL, 'CASADO', 'ensino_medio_completo', NULL, '2025-11-05 20:52:00', '2025-11-04 01:09:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sm_usuarios`
--

CREATE TABLE `sm_usuarios` (
  `codUser` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `login` bigint(14) UNSIGNED ZEROFILL NOT NULL COMMENT 'CPF | CNPJ',
  `senha` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `perfil` tinyint(1) DEFAULT 9,
  `ativado` tinyint(1) NOT NULL DEFAULT 1,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `sm_usuarios`
--

INSERT INTO `sm_usuarios` (`codUser`, `nome`, `login`, `senha`, `email`, `perfil`, `ativado`, `data_cadastro`) VALUES
(1, 'BELTRANO SAURO', 00000000000191, '202cb962ac59075b964b07152d234b70', 'ti@teste.com', 9, 1, '2025-11-02 19:48:49'),
(2, 'SUPORTE TI', 00012345678909, '202cb962ac59075b964b07152d234b70', '2201121@aluno.univesp.br', 1, 1, '2025-11-02 21:48:49');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `sm_empresas`
--
ALTER TABLE `sm_empresas`
  ADD PRIMARY KEY (`codEmp`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD KEY `fk_empr_end` (`fkEndereco`);

--
-- Índices de tabela `sm_enderecos`
--
ALTER TABLE `sm_enderecos`
  ADD PRIMARY KEY (`codEnd`);

--
-- Índices de tabela `sm_pessoas`
--
ALTER TABLE `sm_pessoas`
  ADD PRIMARY KEY (`codPes`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `fk_pes_end` (`fkEndereco`);

--
-- Índices de tabela `sm_usuarios`
--
ALTER TABLE `sm_usuarios`
  ADD PRIMARY KEY (`codUser`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `sm_empresas`
--
ALTER TABLE `sm_empresas`
  MODIFY `codEmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `sm_enderecos`
--
ALTER TABLE `sm_enderecos`
  MODIFY `codEnd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `sm_pessoas`
--
ALTER TABLE `sm_pessoas`
  MODIFY `codPes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `sm_usuarios`
--
ALTER TABLE `sm_usuarios`
  MODIFY `codUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `sm_empresas`
--
ALTER TABLE `sm_empresas`
  ADD CONSTRAINT `fk_empr_end` FOREIGN KEY (`fkEndereco`) REFERENCES `sm_enderecos` (`codEnd`);

--
-- Restrições para tabelas `sm_pessoas`
--
ALTER TABLE `sm_pessoas`
  ADD CONSTRAINT `fk_pes_end` FOREIGN KEY (`fkEndereco`) REFERENCES `sm_enderecos` (`codEnd`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

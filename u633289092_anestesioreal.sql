-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 16/08/2026 às 17:05
-- Versão do servidor: 11.8.8-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u633289092_anestesioreal`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `anestesista_id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `procedimento_id` int(11) DEFAULT NULL,
  `data_agendamento` date NOT NULL,
  `hora_agendamento` time NOT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('agendado','confirmado','em_andamento','concluido','cancelado') DEFAULT 'agendado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `paciente_id`, `anestesista_id`, `instituicao_id`, `procedimento_id`, `data_agendamento`, `hora_agendamento`, `observacoes`, `status`, `created_at`, `updated_at`) VALUES
(16, 215, 26, 15, 1, '2026-04-23', '18:54:00', '', 'agendado', '2026-04-23 20:53:49', '2026-04-23 20:53:49'),
(17, 217, 15, 15, 4, '2026-06-24', '10:50:00', 'remarcado', 'agendado', '2026-04-29 11:37:19', '2026-04-29 11:37:19'),
(18, 227, 563, 10, 2, '2026-06-19', '10:20:00', '', 'agendado', '2026-06-19 23:57:03', '2026-06-19 23:57:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chamados_suporte`
--

CREATE TABLE `chamados_suporte` (
  `id` int(11) NOT NULL,
  `numero_chamado` varchar(20) DEFAULT NULL,
  `instituicao_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `prioridade` enum('baixa','media','alta','critica') DEFAULT 'media',
  `status` enum('aberto','em_andamento','resolvido','fechado') DEFAULT 'aberto',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `chamados_suporte`
--

INSERT INTO `chamados_suporte` (`id`, `numero_chamado`, `instituicao_id`, `usuario_id`, `titulo`, `descricao`, `prioridade`, `status`, `created_at`, `updated_at`) VALUES
(2, 'TKT-20251008-000001', 7, 4, 'meu chamado', 'aldjfalkjdfljalsdkjfljasdlkf ajf laldklka ajf lajlf lkalf lalfk alksflk jaslkflkasdjflkaslkjfl', '', 'aberto', '2025-10-08 14:31:10', '2025-10-08 14:38:44'),
(3, 'TKT-20251107-E3B328', 7, 9, 'teste', 'teste', '', 'aberto', '2025-11-07 18:45:12', '2025-11-07 18:45:12'),
(4, 'TKT-20251107-3316E8', 7, 9, 'teste2', 'testando', 'alta', 'aberto', '2025-11-07 18:45:51', '2025-11-07 18:45:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes_sistema`
--

CREATE TABLE `configuracoes_sistema` (
  `chave` varchar(100) NOT NULL,
  `valor` varchar(255) NOT NULL DEFAULT '0',
  `descricao` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `configuracoes_sistema`
--

INSERT INTO `configuracoes_sistema` (`chave`, `valor`, `descricao`, `updated_at`) VALUES
('limite_pacientes_assinatura_ativo', '0', '1 = bloqueia cadastro ao atingir limite gratuito ou do plano; 0 = cadastro liberado', '2026-06-20 20:07:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `consentimentos`
--

CREATE TABLE `consentimentos` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `procedimento_id` int(11) DEFAULT NULL,
  `anestesista_id` int(11) DEFAULT NULL,
  `aceite_termos` tinyint(1) NOT NULL,
  `aceite_procedimento` tinyint(1) NOT NULL,
  `aceite_anestesia` tinyint(1) NOT NULL,
  `aceite_riscos` tinyint(1) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `data_aceite` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `consentimentos`
--

INSERT INTO `consentimentos` (`id`, `paciente_id`, `instituicao_id`, `procedimento_id`, `anestesista_id`, `aceite_termos`, `aceite_procedimento`, `aceite_anestesia`, `aceite_riscos`, `observacoes`, `ip_address`, `user_agent`, `data_aceite`, `created_at`) VALUES
(5, 43, 7, 4, NULL, 1, 1, 1, 1, '', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-10-12 20:34:58', '2025-10-12 20:34:58'),
(6, 45, 7, 4, NULL, 1, 1, 1, 1, '', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-10-13 03:57:23', '2025-10-13 03:57:23'),
(7, 50, 7, 4, NULL, 1, 1, 1, 1, '', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-10-13 04:26:13', '2025-10-13 04:26:13'),
(8, 53, 11, 2, NULL, 1, 1, 1, 1, '', '2804:14d:4cdc:9bfb:d457:8174:3477:c6d4', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-10-14 15:35:02', '2025-10-14 15:35:02'),
(9, 56, 11, 3, 12, 1, 1, 1, 1, 'nao sei, so to testando', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15', '2025-10-14 16:51:06', '2025-10-14 16:51:06'),
(10, 57, 11, 1, 12, 1, 1, 1, 1, '', '2804:14d:4cdc:9bfb:65d6:2985:8690:cd1e', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-10-15 17:26:32', '2025-10-15 17:26:32'),
(11, 61, 7, 2, NULL, 1, 1, 1, 1, '', '2804:d51:475d:8200:e04f:85e0:66e6:2ceb', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-10-16 03:17:42', '2025-10-16 03:17:42'),
(12, 61, 7, 2, NULL, 1, 1, 1, 1, '', '2804:d51:475d:8200:e04f:85e0:66e6:2ceb', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-10-16 03:43:28', '2025-10-16 03:43:28'),
(13, 62, 11, 3, NULL, 1, 1, 1, 1, '', '200.194.249.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.69 Mobile/15E148 Safari/604.1', '2025-10-16 19:15:08', '2025-10-16 19:15:08'),
(14, 63, 11, 2, NULL, 1, 1, 1, 1, 'A', '2804:18:1966:7af1:21e7:c280:d7d8:38fd', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.69 Mobile/15E148 Safari/604.1', '2025-10-16 19:17:13', '2025-10-16 19:17:13'),
(15, 65, 11, 3, NULL, 1, 1, 1, 1, '', '2804:14d:4cd1:9733:95b6:f018:1607:b6aa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-17 11:05:11', '2025-10-17 11:05:11'),
(16, 85, 15, 1, 15, 1, 1, 1, 1, '', '2804:14d:4cd1:9733:cff1:da88:587d:443c', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-10-20 14:59:09', '2025-10-20 14:59:09'),
(17, 86, 7, 3, NULL, 1, 1, 1, 1, '', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-10-20 20:25:57', '2025-10-20 20:25:57'),
(18, 87, 7, 2, 20, 1, 1, 1, 1, '', '2804:389:f063:b8df:d88f:6a9d:4146:cf77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-10-20 20:36:23', '2025-10-20 20:36:23'),
(19, 88, 7, 1, 20, 1, 1, 1, 1, '', '2804:389:f2b0:1aec:c1f6:c8b8:9573:f609', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.96 Mobile/15E148 Safari/604.1', '2025-10-20 20:48:13', '2025-10-20 20:48:13'),
(20, 93, 17, 1, 22, 1, 1, 1, 1, '', '2804:389:f2ab:a46c:1544:3340:6368:97ba', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-10-24 00:12:56', '2025-10-24 00:12:56'),
(21, 96, 17, 4, 22, 1, 1, 1, 1, '', '2804:389:f2ab:a46c:1544:3340:6368:97ba', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-10-24 01:30:55', '2025-10-24 01:30:55'),
(22, 110, 15, 1, 21, 1, 1, 1, 1, '', '177.84.145.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/141.0.3537.99 Version/18.0 Mobile/15E148 Safari/604.1', '2025-11-06 18:05:49', '2025-11-06 18:05:49'),
(23, 120, 17, 2, 22, 1, 1, 1, 1, '', '2804:d51:475d:8200:d55e:a9f4:1a42:332a', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', '2025-11-17 03:28:00', '2025-11-17 03:28:00'),
(24, 121, 17, 1, 22, 1, 1, 1, 1, '', '2804:14d:4cdc:9bfb:699f:d38c:a8dc:a29', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-11-18 02:31:49', '2025-11-18 02:31:49'),
(25, 122, 17, 4, 22, 1, 1, 1, 1, '', '2804:14d:4cdc:9bfb:dc31:f795:27db:1f71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', '2025-11-20 16:06:28', '2025-11-20 16:06:28'),
(26, 140, 15, 1, 15, 1, 1, 1, 1, '', '2804:18:10f9:7d56:d11a:649e:60f8:179f', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/396.0.833910942 Mobile/15E148 Safari/604.1', '2025-11-27 19:19:01', '2025-11-27 19:19:01'),
(27, 143, 15, 2, 25, 1, 1, 1, 1, '', '2804:7f4:c027:badf:cdd2:ee16:bc99:2e30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-28 20:15:25', '2025-11-28 20:15:25'),
(28, 155, 15, 1, 21, 1, 1, 1, 1, '', '2804:18:1126:e40:6069:5214:bb63:c08e', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2025-12-15 13:21:29', '2025-12-15 13:21:29'),
(29, 157, 15, 1, 21, 1, 1, 1, 1, '', '179.127.139.25', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2025-12-16 17:44:53', '2025-12-16 17:44:53'),
(30, 186, 15, 1, 21, 1, 1, 1, 1, '', '200.132.64.74', 'Mozilla/5.0 (Android 16; Mobile; rv:146.0) Gecko/146.0 Firefox/146.0', '2026-01-07 12:33:07', '2026-01-07 12:33:07'),
(31, 185, 15, 1, 21, 1, 1, 1, 1, '', '2804:85fc:102:e000:6c06:5b91:1518:7904', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.151 Mobile/15E148 Safari/604.1', '2026-01-07 12:34:42', '2026-01-07 12:34:42'),
(32, 188, 15, 1, 21, 1, 1, 1, 1, '', '2804:18:196a:8e8e:549c:fff:fed6:e713', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-13 23:05:10', '2026-01-13 23:05:10'),
(33, 187, 15, 1, 21, 1, 1, 1, 1, '', '2804:1790:9218:6c00:d8b6:823b:7ef5:5ffd', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-14 17:19:04', '2026-01-14 17:19:04'),
(34, 195, 15, 1, 21, 1, 1, 1, 1, '', '179.219.5.71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-03-02 19:21:56', '2026-03-02 19:21:56'),
(35, 196, 15, 1, 21, 1, 1, 1, 1, '', '2804:2a4c:6409:1300:402:31e3:e35d:49d5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '2026-03-10 22:22:42', '2026-03-10 22:22:42'),
(36, 198, 15, 1, 21, 1, 1, 1, 1, '', '2804:d51:562d:9d00:8b:9b4c:ca57:246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-30 15:35:12', '2026-03-30 15:35:12'),
(37, 199, 15, 1, 21, 1, 1, 1, 1, '', '2804:18:196b:af37:18a1:e7c2:ea7:1716', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-31 12:00:48', '2026-03-31 12:00:48'),
(38, 202, 15, 1, 21, 1, 1, 1, 1, '', '2804:14d:4ca0:9acf:15b2:3fd0:46e0:d414', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-17 18:57:31', '2026-04-17 18:57:31'),
(39, 205, 15, 1, 21, 1, 1, 1, 1, '', '2804:14d:4c85:1543:30e5:9e9a:92ab:b9b1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', '2026-04-20 13:15:04', '2026-04-20 13:15:04'),
(40, 215, 15, NULL, 15, 1, 1, 1, 1, '', '2804:389:f28b:9ffa:7d64:ddfd:ac4e:3d6c', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_7_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/147.0.7727.99 Mobile/15E148 Safari/604.1', '2026-04-23 20:49:46', '2026-04-23 20:49:46'),
(41, 214, 15, NULL, NULL, 1, 1, 1, 1, '', '2a02:26f7:e502:5807:0:4000:0:9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-23 20:54:22', '2026-04-23 20:54:22'),
(42, 217, 15, NULL, 15, 1, 1, 1, 1, '', '2804:d51:87de:f100:a1:f71a:3821:30ca', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', '2026-04-29 11:30:47', '2026-04-29 11:30:47'),
(43, 218, 15, NULL, 15, 1, 1, 1, 1, '', '2804:d51:47ee:3a00:e42c:5a10:3b90:9dd9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-04-29 11:31:30', '2026-04-29 11:31:30'),
(44, 219, 15, NULL, 15, 1, 1, 1, 1, '', '2804:388:c421:739b:d96d:fd80:99c7:9c52', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_7_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/147.0.7727.99 Mobile/15E148 Safari/604.1', '2026-04-29 11:32:28', '2026-04-29 11:32:28'),
(45, 227, 10, 2, NULL, 1, 1, 1, 1, 'TESTE DE INFORMAÇÕES', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 23:55:33', '2026-06-19 23:55:33'),
(46, 232, 15, 1, 21, 1, 1, 1, 1, '', '2804:14d:4c89:1322:2d3f:72e8:aed4:951b', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-25 12:39:10', '2026-06-25 12:39:10'),
(47, 248, 15, 3, 21, 1, 1, 1, 1, '', '2804:d51:4768:9400:2514:823f:6212:9d41', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Safari/605.1.15', '2026-07-31 18:09:39', '2026-07-31 18:09:39'),
(48, 250, 15, 1, 21, 1, 1, 1, 1, '', '2804:7f4:c013:cac2:e1ea:387c:cc58:8864', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', '2026-08-03 17:44:20', '2026-08-03 17:44:20'),
(49, 252, 15, 1, 21, 1, 1, 1, 1, '', '2804:7f4:c012:4fc0:b43d:3c1b:d404:bc3b', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', '2026-08-09 16:37:34', '2026-08-09 16:37:34'),
(50, 254, 15, 1, 21, 1, 1, 1, 1, '', '177.67.35.123', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-14 23:48:11', '2026-08-14 23:48:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `demonstracoes`
--

CREATE TABLE `demonstracoes` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `cargo_funcao` varchar(255) NOT NULL,
  `instituicao` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `quantidade_medicos` varchar(50) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `status` enum('pendente','contatado','convertido','cancelado') DEFAULT 'pendente',
  `observacoes_internas` text DEFAULT NULL,
  `responsavel_contato` varchar(255) DEFAULT NULL,
  `data_contato` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `demonstracoes`
--

INSERT INTO `demonstracoes` (`id`, `nome_completo`, `cargo_funcao`, `instituicao`, `email`, `telefone`, `quantidade_medicos`, `mensagem`, `status`, `observacoes_internas`, `responsavel_contato`, `data_contato`, `created_at`, `updated_at`) VALUES
(1, 'José Eduardo Souza', 'Gestor', 'José Eduardo Souza', 'edu.uefs@gmail.com', '(51) 98160-6986', '11-50', '', 'pendente', NULL, NULL, NULL, '2025-10-21 00:20:23', '2025-10-21 00:20:23'),
(2, 'José Eduardo Souza', 'Médico Responsável', 'José Eduardo Souza', 'edu.uefs@gmail.com', '(51) 98160-6986', '11-50', 'a', 'pendente', NULL, NULL, NULL, '2025-10-21 00:21:23', '2025-10-21 00:21:23'),
(3, 'Cassiano', 'medico', 'HPS', 'edu.uefs@gmail.com', '(51) 98160-6986', '51-100', '', 'pendente', NULL, NULL, NULL, '2025-10-21 00:23:27', '2025-10-21 00:23:27'),
(4, 'Anderson de Freitas', 'Coordenador', 'HRAV', 'andersondefreitas@gmail.com', '(47) 98400-6688', '1-10', 'Precisamos de um bom\r\nProduto para avaliação pré anestésica, termos de consentimento e orientações e fácil acesso online', 'pendente', NULL, NULL, NULL, '2026-05-06 11:58:52', '2026-05-06 11:58:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicoes`
--

CREATE TABLE `instituicoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `endereco` text DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `slug` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `responsavel` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `instituicoes`
--

INSERT INTO `instituicoes` (`id`, `nome`, `cnpj`, `email`, `senha_hash`, `endereco`, `telefone`, `status`, `ativo`, `created_at`, `updated_at`, `slug`, `logo_path`, `foto_path`, `responsavel`, `cargo`) VALUES
(7, '- Antigo', '92.815.000/0001-68', 'santacasantigo@gmail.com', '$2y$10$hoXFsuYL5KR.3dtpd7JhE.dTGBy5kC9y0WDsRDVzVQoTte7MQ8XUq', '', '(51) 98160-6986', 'ativo', 1, '2025-09-28 22:49:33', '2025-10-17 16:37:53', 'hospital-santa-casa', 'public/uploads/instituicoes/instituicao_1759100160_d18f7e243d90a503.png', 'public/uploads/instituicoes/instituicao_1759100160_d18f7e243d90a503.png', 'Rafael Seitenfus', 'Gestor'),
(8, 'Sistema Administrativo', '00.000.000/0001-00', 'admin@sistema.com', '.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'ativo', 1, '2025-09-29 04:28:44', '2025-09-29 04:28:44', 'sistema-administrativo', NULL, NULL, NULL, NULL),
(10, 'Intituiçao testes', '86.583.911/0001-04', 'hospital@gmail.com', '$2y$10$3q1InQaLM.g4cMIqsLuTquLh9tedsMYSa1L2e.P5aO4SqHGDZEmLe', 'Avenida Engenheiro Ary de Abreu Lima, 30, Apartamento 403, Jardim Europa, Porto Alegre/RS, CEP: 91360-070', '(51) 98160-6986', 'ativo', 1, '2025-10-08 10:03:12', '2025-10-08 10:03:12', 'intitui-ao-testes', NULL, NULL, 'Jaadsajfojolkj jlajfljalks', 'Gestor'),
(15, 'Santa Casa de Porto Alegre', '92.963.560/0001-60', 'santacasa@anestisio.com', '$2y$10$RRiJOnRPOuHgotoi5UVMYeBihhC2wccEDkHmRTFC0GKCx/ez2PzlG', 'Rua Professor Annes Dias, 295, Centro Histórico, Porto Alegre/RS, CEP: 90020-090', '(51) 3214-8000', 'ativo', 1, '2025-10-17 16:37:31', '2025-10-17 19:21:38', 'santa-casa-de-porto-alegre', 'public/uploads/instituicoes/instituicao_1760728898_c5c2635d7d6e6ef2.png', 'public/uploads/instituicoes/instituicao_1760728898_c5c2635d7d6e6ef2.png', 'Rafael Seitenfus', 'Médico Responsável'),
(17, 'Pipac Brasil', '28.427.978/0001-53', 'pipac@gmail.com', '$2y$10$4ERP2hOfUcNeGwHKxOed1.SAsbrdE022ZkXcpyUsx7Qp29uBr8otq', 'Avenida Ipiranga, 6681, Raiar, Partenon, Porto Alegre/RS, CEP: 90619-900', '(51) 3320-3694', 'ativo', 1, '2025-10-21 00:31:37', '2025-10-21 00:33:27', 'pipac-brasil', 'public/uploads/instituicoes/instituicao_1761006697_1f69e32a7a292a63.png', 'public/uploads/instituicoes/instituicao_1761006697_1f69e32a7a292a63.png', 'Rafael Seitenfus', 'Gestor'),
(18, 'Hospital São Lucas da PUCRS', '88.630.413/0002-81', 'rafasei@puc.com', '$2y$10$oWpx5t0LnsQKeMKIoAMjjuf8gd7onZ4qmoBHKSegiJ.IXH7JG7AAK', 'Avenida Ipiranga, 6690, Partenon, Porto Alegre/RS, CEP: 90610-001', '(51) 3320-3000', 'ativo', 1, '2025-10-24 11:51:20', '2025-10-24 11:53:21', 'hospital-s-o-lucas-da-pucrs', 'public/uploads/instituicoes/instituicao_1761306680_89ce5955e75d6050.jpeg', 'public/uploads/instituicoes/instituicao_1761306680_89ce5955e75d6050.jpeg', 'Rafael Seitenfus', 'Médico Responsável'),
(19, 'NutriCheck Instituição Médica', '53.187.756/0001-58', 'nutricheck@gmail.com', '$2y$10$EH0hBVc.QgPUXiErzhSkfePIF8HK9o96Hf3i8WsRFxdemySWxExHy', 'Avenida Ipiranga, 6681, Raiar, Partenon, Porto Alegre/RS, CEP: 90619-900', '', 'ativo', 1, '2026-04-29 10:27:49', '2026-04-29 10:28:58', 'nutricheck-institui-o-m-dica', 'public/uploads/instituicoes/instituicao_1777458538_eaa4712192b50eaa.png', 'public/uploads/instituicoes/instituicao_1777458538_eaa4712192b50eaa.png', 'Rafael Seitenfus', 'Gestor');

-- --------------------------------------------------------

--
-- Estrutura para tabela `jornada_paciente`
--

CREATE TABLE `jornada_paciente` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `etapa` varchar(50) NOT NULL,
  `status` enum('pendente','concluida','pulada') DEFAULT 'pendente',
  `data_inicio` timestamp NULL DEFAULT NULL,
  `data_conclusao` timestamp NULL DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `jornada_paciente`
--

INSERT INTO `jornada_paciente` (`id`, `paciente_id`, `etapa`, `status`, `data_inicio`, `data_conclusao`, `observacoes`, `created_at`, `updated_at`) VALUES
(40, 33, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2025-10-10 20:14:48', '2025-10-10 20:14:48'),
(41, 33, 'selfie', 'pendente', NULL, NULL, NULL, '2025-10-10 20:14:48', '2025-10-10 20:14:48'),
(42, 33, 'video', 'pendente', NULL, NULL, NULL, '2025-10-10 20:14:48', '2025-10-10 20:14:48'),
(43, 33, 'questionario', 'pendente', NULL, NULL, NULL, '2025-10-10 20:14:48', '2025-10-10 20:14:48'),
(44, 33, 'autorizacao', 'pendente', NULL, NULL, NULL, '2025-10-10 20:14:48', '2025-10-10 20:14:48'),
(84, 66, '', '', NULL, NULL, NULL, '2025-10-17 17:10:02', '2025-10-17 17:10:02'),
(104, 91, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2025-10-23 23:22:39', '2025-10-23 23:22:39'),
(105, 91, 'selfie', 'pendente', NULL, NULL, NULL, '2025-10-23 23:22:39', '2025-10-23 23:22:39'),
(106, 91, 'video', 'pendente', NULL, NULL, NULL, '2025-10-23 23:22:39', '2025-10-23 23:22:39'),
(107, 91, 'questionario', 'pendente', NULL, NULL, NULL, '2025-10-23 23:22:39', '2025-10-23 23:22:39'),
(108, 91, 'autorizacao', 'pendente', NULL, NULL, NULL, '2025-10-23 23:22:39', '2025-10-23 23:22:39'),
(109, 92, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2025-10-23 23:24:50', '2025-10-23 23:24:50'),
(110, 92, 'selfie', 'pendente', NULL, NULL, NULL, '2025-10-23 23:24:50', '2025-10-23 23:24:50'),
(111, 92, 'video', 'pendente', NULL, NULL, NULL, '2025-10-23 23:24:50', '2025-10-23 23:24:50'),
(112, 92, 'questionario', 'pendente', NULL, NULL, NULL, '2025-10-23 23:24:50', '2025-10-23 23:24:50'),
(113, 92, 'autorizacao', 'pendente', NULL, NULL, NULL, '2025-10-23 23:24:50', '2025-10-23 23:24:50'),
(116, 95, '', '', NULL, NULL, NULL, '2025-10-24 00:21:37', '2025-10-24 00:21:37'),
(119, 99, '', '', NULL, NULL, NULL, '2025-10-24 17:41:36', '2025-10-24 17:41:36'),
(123, 109, '', '', NULL, NULL, NULL, '2025-11-05 21:37:54', '2025-11-05 21:37:54'),
(124, 110, '', '', NULL, NULL, NULL, '2025-11-06 17:32:54', '2025-11-06 17:32:54'),
(125, 111, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2025-11-07 18:48:15', '2025-11-07 18:48:15'),
(126, 111, 'selfie', 'pendente', NULL, NULL, NULL, '2025-11-07 18:48:15', '2025-11-07 18:48:15'),
(127, 111, 'video', 'pendente', NULL, NULL, NULL, '2025-11-07 18:48:15', '2025-11-07 18:48:15'),
(128, 111, 'questionario', 'pendente', NULL, NULL, NULL, '2025-11-07 18:48:15', '2025-11-07 18:48:15'),
(129, 111, 'autorizacao', 'pendente', NULL, NULL, NULL, '2025-11-07 18:48:15', '2025-11-07 18:48:15'),
(167, 139, '', '', NULL, NULL, NULL, '2025-11-27 19:05:28', '2025-11-27 19:05:28'),
(168, 140, '', '', NULL, NULL, NULL, '2025-11-27 19:07:48', '2025-11-27 19:07:48'),
(169, 143, '', '', NULL, NULL, NULL, '2025-11-28 19:43:52', '2025-11-28 19:43:52'),
(170, 146, '', '', NULL, NULL, NULL, '2025-12-02 14:14:23', '2025-12-02 14:14:23'),
(171, 147, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2025-12-03 15:53:19', '2025-12-03 15:53:19'),
(172, 147, 'selfie', 'pendente', NULL, NULL, NULL, '2025-12-03 15:53:19', '2025-12-03 15:53:19'),
(173, 147, 'video', 'pendente', NULL, NULL, NULL, '2025-12-03 15:53:19', '2025-12-03 15:53:19'),
(174, 147, 'questionario', 'pendente', NULL, NULL, NULL, '2025-12-03 15:53:19', '2025-12-03 15:53:19'),
(175, 147, 'autorizacao', 'pendente', NULL, NULL, NULL, '2025-12-03 15:53:19', '2025-12-03 15:53:19'),
(176, 148, '', '', NULL, NULL, NULL, '2025-12-15 13:01:44', '2025-12-15 13:01:44'),
(177, 155, '', '', NULL, NULL, NULL, '2025-12-15 13:07:00', '2025-12-15 13:07:00'),
(178, 157, '', '', NULL, NULL, NULL, '2025-12-16 13:27:00', '2025-12-16 13:27:00'),
(179, 158, '', '', NULL, NULL, NULL, '2025-12-16 23:57:27', '2025-12-16 23:57:27'),
(186, 165, '', '', NULL, NULL, NULL, '2025-12-17 00:53:13', '2025-12-17 00:53:13'),
(187, 166, '', '', NULL, NULL, NULL, '2025-12-17 01:00:10', '2025-12-17 01:00:10'),
(188, 167, '', '', NULL, NULL, NULL, '2025-12-17 01:04:56', '2025-12-17 01:04:56'),
(189, 168, '', '', NULL, NULL, NULL, '2025-12-17 01:12:33', '2025-12-17 01:12:33'),
(190, 169, '', '', NULL, NULL, NULL, '2025-12-17 01:19:40', '2025-12-17 01:19:40'),
(191, 170, '', '', NULL, NULL, NULL, '2025-12-17 01:21:18', '2025-12-17 01:21:18'),
(192, 171, '', '', NULL, NULL, NULL, '2025-12-18 00:52:06', '2025-12-18 00:52:06'),
(193, 172, '', '', NULL, NULL, NULL, '2025-12-18 00:53:45', '2025-12-18 00:53:45'),
(194, 173, '', '', NULL, NULL, NULL, '2025-12-18 00:56:04', '2025-12-18 00:56:04'),
(197, 176, '', '', NULL, NULL, NULL, '2025-12-18 16:51:17', '2025-12-18 16:51:17'),
(198, 178, '', '', NULL, NULL, NULL, '2025-12-19 14:14:51', '2025-12-19 14:14:51'),
(199, 185, '', '', NULL, NULL, NULL, '2026-01-06 21:54:15', '2026-01-06 21:54:15'),
(200, 186, '', '', NULL, NULL, NULL, '2026-01-07 12:20:29', '2026-01-07 12:20:29'),
(201, 187, '', '', NULL, NULL, NULL, '2026-01-13 21:56:36', '2026-01-13 21:56:36'),
(202, 188, '', '', NULL, NULL, NULL, '2026-01-13 22:29:23', '2026-01-13 22:29:23'),
(203, 189, '', '', NULL, NULL, NULL, '2026-01-19 16:45:00', '2026-01-19 16:45:00'),
(204, 193, '', '', NULL, NULL, NULL, '2026-01-26 18:08:39', '2026-01-26 18:08:39'),
(205, 194, '', '', NULL, NULL, NULL, '2026-02-09 18:45:45', '2026-02-09 18:45:45'),
(206, 195, '', '', NULL, NULL, NULL, '2026-03-02 19:09:08', '2026-03-02 19:09:08'),
(207, 196, '', '', NULL, NULL, NULL, '2026-03-10 20:56:14', '2026-03-10 20:56:14'),
(208, 198, '', '', NULL, NULL, NULL, '2026-03-30 15:15:44', '2026-03-30 15:15:44'),
(209, 199, '', '', NULL, NULL, NULL, '2026-03-31 11:50:55', '2026-03-31 11:50:55'),
(210, 200, '', '', NULL, NULL, NULL, '2026-04-09 17:34:33', '2026-04-09 17:34:33'),
(211, 201, '', '', NULL, NULL, NULL, '2026-04-10 16:16:30', '2026-04-10 16:16:30'),
(212, 202, '', '', NULL, NULL, NULL, '2026-04-16 20:18:11', '2026-04-16 20:18:11'),
(213, 203, '', '', NULL, NULL, NULL, '2026-04-17 20:14:07', '2026-04-17 20:14:07'),
(214, 204, '', '', NULL, NULL, NULL, '2026-04-20 12:53:14', '2026-04-20 12:53:14'),
(215, 205, '', '', NULL, NULL, NULL, '2026-04-20 13:02:13', '2026-04-20 13:02:13'),
(216, 207, '', '', NULL, NULL, NULL, '2026-04-20 14:51:47', '2026-04-20 14:51:47'),
(222, 221, '', '', NULL, NULL, NULL, '2026-05-25 14:32:37', '2026-05-25 14:32:37'),
(223, 222, '', '', NULL, NULL, NULL, '2026-05-25 14:32:38', '2026-05-25 14:32:38'),
(224, 223, '', '', NULL, NULL, NULL, '2026-05-25 14:32:39', '2026-05-25 14:32:39'),
(225, 224, '', '', NULL, NULL, NULL, '2026-06-02 17:15:06', '2026-06-02 17:15:06'),
(226, 225, '', '', NULL, NULL, NULL, '2026-06-05 17:10:30', '2026-06-05 17:10:30'),
(227, 226, '', '', NULL, NULL, NULL, '2026-06-09 11:47:32', '2026-06-09 11:47:32'),
(228, 227, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2026-06-19 23:33:01', '2026-06-19 23:33:01'),
(229, 227, 'selfie', 'pendente', NULL, NULL, NULL, '2026-06-19 23:33:01', '2026-06-19 23:33:01'),
(230, 227, 'video', 'pendente', NULL, NULL, NULL, '2026-06-19 23:33:01', '2026-06-19 23:33:01'),
(231, 227, 'questionario', 'pendente', NULL, NULL, NULL, '2026-06-19 23:33:01', '2026-06-19 23:33:01'),
(232, 227, 'autorizacao', 'concluida', NULL, NULL, NULL, '2026-06-19 23:33:01', '2026-06-19 23:55:33'),
(233, 228, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2026-06-20 20:08:53', '2026-06-20 20:08:53'),
(234, 228, 'selfie', 'pendente', NULL, NULL, NULL, '2026-06-20 20:08:53', '2026-06-20 20:08:53'),
(235, 228, 'video', 'pendente', NULL, NULL, NULL, '2026-06-20 20:08:53', '2026-06-20 20:08:53'),
(236, 228, 'questionario', 'pendente', NULL, NULL, NULL, '2026-06-20 20:08:53', '2026-06-20 20:08:53'),
(237, 228, 'autorizacao', 'pendente', NULL, NULL, NULL, '2026-06-20 20:08:53', '2026-06-20 20:08:53'),
(238, 230, '', '', NULL, NULL, NULL, '2026-06-24 12:42:56', '2026-06-24 12:42:56'),
(239, 231, '', '', NULL, NULL, NULL, '2026-06-24 22:34:47', '2026-06-24 22:34:47'),
(240, 232, '', '', NULL, NULL, NULL, '2026-06-25 12:09:33', '2026-06-25 12:09:33'),
(241, 233, '', '', NULL, NULL, NULL, '2026-06-25 12:13:52', '2026-06-25 12:13:52'),
(242, 234, '', '', NULL, NULL, NULL, '2026-06-26 20:47:58', '2026-06-26 20:47:58'),
(243, 235, '', '', NULL, NULL, NULL, '2026-06-27 20:08:54', '2026-06-27 20:08:54'),
(244, 236, '', '', NULL, NULL, NULL, '2026-07-06 13:07:47', '2026-07-06 13:07:47'),
(245, 237, '', '', NULL, NULL, NULL, '2026-07-06 13:36:43', '2026-07-06 13:36:43'),
(246, 238, '', '', NULL, NULL, NULL, '2026-07-13 13:21:07', '2026-07-13 13:21:07'),
(247, 239, '', '', NULL, NULL, NULL, '2026-07-13 13:33:29', '2026-07-13 13:33:29'),
(248, 240, '', '', NULL, NULL, NULL, '2026-07-30 17:43:46', '2026-07-30 17:43:46'),
(249, 241, '', '', NULL, NULL, NULL, '2026-07-31 15:48:26', '2026-07-31 15:48:26'),
(250, 242, '', '', NULL, NULL, NULL, '2026-07-31 15:48:28', '2026-07-31 15:48:28'),
(251, 243, '', '', NULL, NULL, NULL, '2026-07-31 15:48:29', '2026-07-31 15:48:29'),
(252, 244, '', '', NULL, NULL, NULL, '2026-07-31 17:19:10', '2026-07-31 17:19:10'),
(253, 245, '', '', NULL, NULL, NULL, '2026-07-31 17:19:18', '2026-07-31 17:19:18'),
(254, 246, '', '', NULL, NULL, NULL, '2026-07-31 17:19:27', '2026-07-31 17:19:27'),
(255, 247, '', '', NULL, NULL, NULL, '2026-07-31 17:19:30', '2026-07-31 17:19:30'),
(256, 248, 'termo_lgpd', 'pendente', NULL, NULL, NULL, '2026-07-31 17:31:26', '2026-07-31 17:31:26'),
(257, 248, 'selfie', 'pendente', NULL, NULL, NULL, '2026-07-31 17:31:26', '2026-07-31 17:31:26'),
(258, 248, 'video', 'pendente', NULL, NULL, NULL, '2026-07-31 17:31:26', '2026-07-31 17:31:26'),
(259, 248, 'questionario', 'pendente', NULL, NULL, NULL, '2026-07-31 17:31:26', '2026-07-31 17:31:26'),
(260, 248, 'autorizacao', 'concluida', NULL, NULL, NULL, '2026-07-31 17:31:26', '2026-07-31 18:09:39'),
(261, 249, '', '', NULL, NULL, NULL, '2026-08-03 17:22:49', '2026-08-03 17:22:49'),
(262, 250, '', '', NULL, NULL, NULL, '2026-08-03 17:22:50', '2026-08-03 17:22:50'),
(263, 251, '', '', NULL, NULL, NULL, '2026-08-09 15:30:08', '2026-08-09 15:30:08'),
(264, 252, '', '', NULL, NULL, NULL, '2026-08-09 15:30:09', '2026-08-09 15:30:09'),
(265, 253, '', '', NULL, NULL, NULL, '2026-08-11 16:39:30', '2026-08-11 16:39:30'),
(266, 254, '', '', NULL, NULL, NULL, '2026-08-14 22:58:07', '2026-08-14 22:58:07'),
(267, 255, '', '', NULL, NULL, NULL, '2026-08-14 23:24:01', '2026-08-14 23:24:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_ativade`
--

CREATE TABLE `logs_ativade` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `logs_ativade`
--

INSERT INTO `logs_ativade` (`id`, `usuario_id`, `paciente_id`, `acao`, `detalhes`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(22, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 04:49:08'),
(23, 3, NULL, 'PERMISSOES_SALVAS', 'Permissões de páginas atualizadas', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 04:50:01'),
(24, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 04:58:32'),
(25, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:03:57'),
(26, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:04:22'),
(27, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:04:39'),
(28, 3, NULL, 'PERMISSOES_SALVAS', 'Permissões de páginas atualizadas', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:04:54'),
(29, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:04:57'),
(30, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:05:07'),
(31, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:10:34'),
(32, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:10:47'),
(33, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 05:21:08'),
(34, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 09:37:48'),
(35, 3, NULL, 'PERMISSOES_SALVAS', 'Permissões de páginas atualizadas', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 09:38:14'),
(36, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 09:38:20'),
(38, 6, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 16:57:04'),
(39, 6, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 16:57:20'),
(40, 6, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 16:57:31'),
(41, 6, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:00:35'),
(42, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:00:47'),
(43, 4, NULL, 'exclusao_paciente', 'Paciente excluído: Maria Santos', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:12:18'),
(44, 4, NULL, 'exclusao_paciente', 'Paciente excluído: João Silva', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:12:21'),
(45, 4, NULL, 'cadastro_paciente', 'Paciente cadastrado: José Eduardo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:36:49'),
(46, 4, NULL, 'exclusao_paciente', 'Paciente excluído: José Eduardo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:40:11'),
(47, 4, NULL, 'cadastro_paciente', 'Paciente cadastrado: José Eduardo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:40:26'),
(48, 4, NULL, 'exclusao_paciente', 'Paciente excluído: José Eduardo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:41:47'),
(49, 4, NULL, 'cadastro_paciente', 'Paciente cadastrado: José Eduardo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:42:01'),
(50, 4, NULL, 'inativacao_paciente', 'Paciente inativado: José Eduardoeditado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:59:16'),
(51, 4, NULL, 'inativacao_paciente', 'Paciente inativado: José Eduardoeditado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 17:59:23'),
(52, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:42:33'),
(53, 4, NULL, 'inativacao_paciente', 'Paciente inativado: José Eduardoeditado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:42:42'),
(54, 4, NULL, 'reativacao_paciente', 'Paciente reativado: José Eduardoeditado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:49:19'),
(55, 4, NULL, 'inativacao_paciente', 'Paciente inativado: José Eduardoeditado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:49:22'),
(56, 4, NULL, 'reativacao_paciente', 'Paciente reativado: José Eduardoeditado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:53:28'),
(57, 4, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 11 alocado para anestesista ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:55:14'),
(58, 4, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 11 desalocado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 18:55:21'),
(59, 4, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 11 alocado para anestesista ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:06:31'),
(60, 4, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 11 desalocado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:09:38'),
(61, 4, NULL, 'cadastro_paciente', 'Paciente cadastrado: Renato', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:11:34'),
(62, 4, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 12 alocado para anestesista ID 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:12:15'),
(63, 4, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 11 alocado para anestesista ID 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:12:23'),
(64, 4, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 12 desalocado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:12:30'),
(65, 4, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 12 alocado para anestesista ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:12:34'),
(66, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:21:06'),
(67, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:21:22'),
(68, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:28:28'),
(70, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 19:50:28'),
(71, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 20:03:08'),
(72, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 20:04:34'),
(73, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-29 20:04:47'),
(74, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-30 00:33:19'),
(75, 4, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 12 desalocado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-30 00:38:20'),
(76, 4, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 12 alocado para anestesista ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-30 00:38:26'),
(77, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-30 00:38:59'),
(78, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-30 00:39:34'),
(79, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', NULL, '2025-09-30 00:40:42'),
(80, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 13:10:13'),
(81, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 13:19:14'),
(82, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 19:25:01'),
(83, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 19:38:33'),
(84, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-07 19:41:34'),
(85, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-07 19:42:18'),
(86, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 19:50:20'),
(87, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 19:50:36'),
(88, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 19:50:58'),
(89, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 20:53:33'),
(90, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 20:58:40'),
(91, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-07 20:59:55'),
(92, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 21:08:25'),
(93, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-07 21:20:06'),
(94, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 22:24:43'),
(95, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 22:25:01'),
(96, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-07 22:25:06'),
(97, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 02:06:54'),
(98, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 02:22:24'),
(101, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 02:25:46'),
(102, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 11:10:25'),
(105, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:26:05'),
(106, 4, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 23 desalocado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:38:08'),
(107, 4, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 22 desalocado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:38:11'),
(108, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:39:48'),
(109, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:39:56'),
(110, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:40:17'),
(113, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:51:50'),
(114, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 13:52:39'),
(115, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 14:22:48'),
(116, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 14:27:54'),
(117, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 14:28:01'),
(118, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 15:40:44'),
(119, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 15:42:14'),
(120, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:10:52'),
(121, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:11:08'),
(122, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:12:07'),
(123, 4, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:12:37'),
(124, 4, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:13:00'),
(125, 3, NULL, 'login', 'Login realizado com sucesso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:14:47'),
(126, 3, NULL, 'logout', 'Logout realizado', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-08 16:18:01'),
(127, 3, NULL, 'login', 'Login realizado com sucesso', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 18:38:17'),
(128, 3, NULL, 'logout', 'Logout realizado', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 18:42:35'),
(129, 3, NULL, 'login', 'Login realizado com sucesso', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 19:43:18'),
(130, 4, NULL, 'login', 'Login realizado com sucesso', '201.54.143.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, '2025-10-09 19:57:12'),
(131, 4, NULL, 'logout', 'Logout realizado', '201.54.143.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, '2025-10-09 19:57:26'),
(132, 9, NULL, 'login', 'Login realizado com sucesso', '201.54.159.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 20:06:19'),
(133, 9, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 32 alocado para anestesista ID 9', '201.54.159.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 20:14:56'),
(134, 9, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 32 desalocado', '201.54.159.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 20:15:14'),
(135, 9, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 32 alocado para anestesista ID 9', '201.54.159.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 20:15:26'),
(136, 3, NULL, 'logout', 'Logout realizado', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 20:23:57'),
(137, 3, NULL, 'login', 'Login realizado com sucesso', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-09 20:25:43'),
(138, 9, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 17:37:00'),
(139, 9, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 23 desalocado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 17:51:12'),
(140, 9, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 23 alocado para anestesista ID 9', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 17:52:15'),
(141, 9, NULL, 'inativacao_paciente', 'Paciente inativado: paciente web', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 18:06:22'),
(142, 9, NULL, 'reativacao_paciente', 'Paciente reativado: paciente web', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 18:06:42'),
(143, 9, NULL, 'cadastro_paciente', 'Paciente cadastrado: Gabriel Gael ', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 20:14:48'),
(144, 9, NULL, 'cadastro_paciente', 'Paciente cadastrado: Victor Martin ', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 20:31:31'),
(145, 9, NULL, 'login', 'Login realizado com sucesso', '201.54.159.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-10 23:03:08'),
(146, 9, NULL, 'login', 'Login realizado com sucesso', '179.129.240.86', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', NULL, '2025-10-10 23:58:23'),
(147, 4, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-12 18:57:32'),
(148, 4, NULL, 'cadastro_paciente', 'Paciente cadastrado: joao', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-12 19:17:07'),
(149, 4, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-12 19:21:51'),
(150, 3, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:4058:cd0e:dc30:bd5e', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-12 19:22:11'),
(151, 3, NULL, 'login', 'Login realizado com sucesso', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:23:35'),
(152, 3, NULL, 'logout', 'Logout realizado', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:24:51'),
(153, 3, NULL, 'login', 'Login realizado com sucesso', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:28:50'),
(154, 3, NULL, 'logout', 'Logout realizado', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:30:56'),
(155, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:8452:259d:ac:9d33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:33:56'),
(156, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:8452:259d:ac:9d33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:38:26'),
(157, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:8452:259d:ac:9d33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 14:57:18'),
(158, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:8452:259d:ac:9d33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-14 15:55:36'),
(159, 3, NULL, 'login', 'Login realizado com sucesso', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-15 21:04:02'),
(160, 3, NULL, 'login', 'Login realizado com sucesso', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-15 22:01:40'),
(161, 3, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:5c1d:6b10:efa0:f3a4', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-15 23:55:09'),
(162, 3, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:e04f:85e0:66e6:2ceb', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-16 03:13:37'),
(163, 3, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 18:54:12'),
(164, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 18:55:52'),
(165, 3, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 19:04:32'),
(166, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 19:08:00'),
(167, 3, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 19:08:34'),
(168, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 19:09:24'),
(171, 3, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 19:32:35'),
(172, 3, NULL, 'logout', 'Logout realizado', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 20:00:39'),
(173, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 20:26:00'),
(174, 3, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 20:29:18'),
(175, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-16 20:40:37'),
(178, 4, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 15:51:05'),
(179, 4, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 15:55:04'),
(180, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 15:55:15'),
(181, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 16:01:36'),
(182, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 16:27:45'),
(183, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 16:39:08'),
(184, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 16:40:14'),
(185, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 16:42:22'),
(186, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 16:44:48'),
(187, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 17:04:22'),
(188, 15, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 17:04:31'),
(189, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 17:06:28'),
(190, 15, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 17:06:32'),
(191, 15, NULL, 'login', 'Login realizado com sucesso', '200.132.64.55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 17:07:23'),
(192, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 17:13:52'),
(193, 15, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 70 desalocado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:25:31'),
(194, 15, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 68 desalocado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:25:40'),
(195, 15, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 67 desalocado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:25:43'),
(196, 15, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:34:06'),
(197, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:34:27'),
(198, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:298d:ead4:785a:43bc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:35:10'),
(199, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 18:48:39'),
(200, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:02:39'),
(201, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:15:06'),
(202, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:17:10'),
(203, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:17:53'),
(204, 21, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:19:43'),
(205, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:20:01'),
(206, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:1c9f:f306:1589:ddb9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 19:32:46'),
(207, 21, NULL, 'login', 'Login realizado com sucesso', '189.15.0.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 20:16:45'),
(208, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cd1:9733:95b6:f018:1607:b6aa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-17 22:26:09'),
(209, 15, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 66 desalocado', '2804:14d:4cd1:9733:95b6:f018:1607:b6aa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-17 22:26:32'),
(210, 15, NULL, 'login', 'Login realizado com sucesso', '200.248.60.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 22:54:06'),
(211, 15, NULL, 'logout', 'Logout realizado', '200.248.60.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 22:57:28'),
(212, 3, NULL, 'login', 'Login realizado com sucesso', '200.248.60.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-17 22:58:28'),
(213, 3, NULL, 'login', 'Login realizado com sucesso', '189.6.233.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-18 23:01:01'),
(214, 3, NULL, 'logout', 'Logout realizado', '189.6.233.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-18 23:03:41'),
(215, 3, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:d9eb:5a12:7014:d682', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-19 16:48:01'),
(216, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-20 17:49:30'),
(217, 3, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-20 18:24:47'),
(218, 3, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-20 18:25:05'),
(219, 3, NULL, 'login', 'Login realizado com sucesso', '2804:389:f063:b8df:d88f:6a9d:4146:cf77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-10-20 21:08:28'),
(220, 3, NULL, 'logout', 'Logout realizado', '2804:389:f063:b8df:d88f:6a9d:4146:cf77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-10-20 21:09:04'),
(221, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:55e6:6777:c09f:f003', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-21 00:23:49'),
(222, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:55e6:6777:c09f:f003', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-21 00:36:55'),
(223, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:55e6:6777:c09f:f003', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-21 00:43:44'),
(224, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:55e6:6777:c09f:f003', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-21 00:51:35'),
(225, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:c056:6840:5d05:ecdf', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-21 16:07:09'),
(226, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:88c4:595f:8564:9de9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-22 12:41:04'),
(227, 3, NULL, 'login', 'Login realizado com sucesso', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-23 17:51:49'),
(228, 3, NULL, 'login', 'Login realizado com sucesso', '201.54.143.200', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-10-23 17:51:50'),
(229, 9, NULL, 'login', 'Login realizado com sucesso', '2804:7f4:c027:ce54:fd2c:4dec:b571:589f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-23 23:14:31'),
(230, 9, NULL, 'cadastro_paciente', 'Paciente cadastrado: Aparecida', '2804:7f4:c027:ce54:fd2c:4dec:b571:589f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-23 23:22:39'),
(231, 9, NULL, 'cadastro_paciente', 'Paciente cadastrado: Maria ', '2804:7f4:c027:ce54:fd2c:4dec:b571:589f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-23 23:24:50'),
(232, 9, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 92 alocado para anestesista ID 9', '2804:7f4:c027:ce54:fd2c:4dec:b571:589f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-23 23:27:16'),
(233, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:b1b1:9d57:c98b:1ee7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 11:45:22'),
(234, 15, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:b1b1:9d57:c98b:1ee7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 11:48:22'),
(235, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:b1b1:9d57:c98b:1ee7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 11:48:39'),
(236, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:b1b1:9d57:c98b:1ee7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 11:52:07'),
(237, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1921:e90:b541:69d3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 16:39:52'),
(238, 21, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:1921:e90:b541:69d3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 16:42:22'),
(239, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1921:e90:b541:69d3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-24 16:43:02'),
(240, 21, NULL, 'logout', 'Logout realizado', '179.129.235.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-25 00:28:22'),
(241, 3, NULL, 'login', 'Login realizado com sucesso', '2804:389:f2ab:332c:d4fe:e10d:acb1:67ca', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 23:40:28'),
(242, 3, NULL, 'logout', 'Logout realizado', '2804:389:f2ab:332c:d4fe:e10d:acb1:67ca', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 23:41:42'),
(243, 9, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-07 18:44:13'),
(244, 9, NULL, 'cadastro_paciente', 'Paciente cadastrado: Arthur', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-07 18:48:15'),
(245, 9, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-11-07 18:50:47'),
(246, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:09:53'),
(247, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:26:39'),
(248, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:28:03'),
(249, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:32:05'),
(250, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:32:29'),
(251, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:39:17'),
(252, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:40:28'),
(253, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:40:48'),
(254, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:43:27'),
(255, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:43:50'),
(256, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-19 22:44:09'),
(257, 22, NULL, 'login', 'Login realizado com sucesso', '2804:389:f2bf:58fc:1016:3f34:b443:56ce', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-19 23:22:34'),
(258, 22, NULL, 'login', 'Login realizado com sucesso', '2804:389:f2bf:58fc:1016:3f34:b443:56ce', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-19 23:24:13');
INSERT INTO `logs_ativade` (`id`, `usuario_id`, `paciente_id`, `acao`, `detalhes`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(259, 22, NULL, 'logout', 'Logout realizado', '2804:389:f2bf:58fc:1016:3f34:b443:56ce', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-19 23:24:22'),
(260, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-19 23:41:43'),
(261, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-19 23:41:49'),
(262, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-19 23:42:02'),
(263, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-19 23:42:24'),
(264, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-19 23:42:33'),
(265, 22, NULL, 'logout', 'Logout realizado', '189.72.7.78', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-19 23:47:26'),
(266, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-20 00:02:12'),
(267, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-20 00:03:27'),
(268, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-20 00:03:36'),
(269, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-20 00:03:48'),
(270, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:05:46'),
(271, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:09:09'),
(272, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:09:20'),
(273, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:10:09'),
(274, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:10:09'),
(275, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:10:33'),
(276, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-20 00:15:16'),
(277, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:15:34'),
(278, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:16:24'),
(279, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:17:27'),
(280, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:17:37'),
(281, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:19:07'),
(282, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:19:21'),
(283, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:20:03'),
(284, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:20:08'),
(285, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-20 00:38:22'),
(286, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:39:39'),
(287, 22, NULL, 'logout', 'Logout realizado', '2804:d51:475d:8200:a487:447a:91d3:1909', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:39:50'),
(288, 22, NULL, 'login', 'Login realizado com sucesso', '179.68.28.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:44:27'),
(289, 22, NULL, 'logout', 'Logout realizado', '179.68.28.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:44:36'),
(290, 22, NULL, 'login', 'Login realizado com sucesso', '179.68.28.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:44:52'),
(291, 22, NULL, 'login', 'Login realizado com sucesso', '179.68.28.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:45:03'),
(292, 22, NULL, 'login', 'Login realizado com sucesso', '2804:389:f2bf:58fc:1016:3f34:b443:56ce', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/138.0.7204.119 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:47:10'),
(293, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:48:25'),
(294, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:48:27'),
(295, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:48:40'),
(296, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:48:51'),
(297, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:49:37'),
(298, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:49:40'),
(299, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 00:50:32'),
(300, 22, NULL, 'login', 'Login realizado com sucesso', '2a02:26f7:e52c:5807:0:4000:0:8', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:51:02'),
(301, 22, NULL, 'logout', 'Logout realizado', '2a02:26f7:e52c:5807:0:4000:0:8', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', NULL, '2025-11-20 00:51:21'),
(302, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 01:42:46'),
(303, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 01:42:56'),
(304, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 01:42:57'),
(305, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 01:43:01'),
(306, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:94aa:f14f:71dd:cb2d', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 01:43:20'),
(307, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:ad5d:81e0:23c:433e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 13:24:52'),
(308, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:ad5d:81e0:23c:433e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 13:25:04'),
(309, 22, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:ad5d:81e0:23c:433e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 15:43:27'),
(310, 22, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:ad5d:81e0:23c:433e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-20 15:44:05'),
(311, 9, NULL, 'login', 'Login realizado com sucesso', '2804:7f4:c027:fb35:bcdf:5f3d:63ef:9411', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-21 15:01:37'),
(312, 22, NULL, 'login', 'Login realizado com sucesso', '2804:d51:475d:8200:292e:bd84:f061:b5a3', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 17:47:34'),
(313, 22, NULL, 'cadastro_paciente', 'Paciente cadastrado: joao', '2804:d51:475d:8200:292e:bd84:f061:b5a3', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 17:48:19'),
(314, 22, NULL, 'cadastro_paciente', 'Paciente cadastrado: eeeee', '2804:d51:475d:8200:292e:bd84:f061:b5a3', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 18:07:55'),
(315, 22, NULL, 'cadastro_paciente', 'Paciente cadastrado: pe de cabra', '2804:d51:475d:8200:292e:bd84:f061:b5a3', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 18:10:46'),
(316, 22, NULL, 'cadastro_paciente', 'Paciente cadastrado: teste 2222', '2804:d51:475d:8200:292e:bd84:f061:b5a3', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 18:35:52'),
(317, 22, NULL, 'cadastro_paciente', 'Paciente cadastrado: 444444', '2804:d51:475d:8200:292e:bd84:f061:b5a3', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 18:37:00'),
(318, 22, NULL, 'inativacao_paciente', 'Paciente inativado: joao', '189.72.7.78', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 19:27:41'),
(319, 22, NULL, 'reativacao_paciente', 'Paciente reativado: joao', '189.72.7.78', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Safari/605.1.15', NULL, '2025-11-22 19:27:46'),
(320, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:70bd:e61:4f39:4409', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-27 19:00:09'),
(321, 15, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-27 19:01:17'),
(322, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:70bd:e61:4f39:4409', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-27 19:01:37'),
(323, 9, NULL, 'login', 'Login realizado com sucesso', '2804:7f4:c027:badf:cdd2:ee16:bc99:2e30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-28 19:28:21'),
(324, 9, NULL, 'logout', 'Logout realizado', '2804:7f4:c027:badf:cdd2:ee16:bc99:2e30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-11-28 19:33:18'),
(326, 22, NULL, 'login', 'Login realizado com sucesso', '191.32.50.114', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-12-02 14:00:42'),
(327, 4, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:dcea:3251:4ef0:f94b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-12-03 15:52:37'),
(328, 4, NULL, 'cadastro_paciente', 'Paciente cadastrado: José Eduardo', '2804:14d:4cdc:9bfb:dcea:3251:4ef0:f94b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-12-03 15:53:19'),
(329, 22, NULL, 'login', 'Login realizado com sucesso', '200.152.6.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2025-12-14 13:12:34'),
(330, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:dcf6:2ac8:8bb6:5abc', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2025-12-16 13:04:50'),
(331, 21, NULL, 'login', 'Login realizado com sucesso', '2804:0:3000:92:24e8:9e83:25a0:7e21', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-12-16 16:48:57'),
(332, 21, NULL, 'logout', 'Logout realizado', '2804:0:3000:92:24e8:9e83:25a0:7e21', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-12-16 16:57:07'),
(333, 21, NULL, 'login', 'Login realizado com sucesso', '2804:0:3000:92:24e8:9e83:25a0:7e21', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', NULL, '2025-12-16 18:03:27'),
(334, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:f5fe:4427:cfc1:c29b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2025-12-18 14:47:22'),
(335, 21, NULL, 'logout', 'Logout realizado', '200.194.249.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2025-12-18 18:08:06'),
(336, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:40af:c74a:55da:3d7e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2025-12-22 16:08:14'),
(337, 26, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:1cb1:5fc1:5df:984f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-05 15:07:20'),
(338, 26, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:1cb1:5fc1:5df:984f', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-05 15:07:34'),
(339, 21, NULL, 'login', 'Login realizado com sucesso', '2a09:bac3:da9:1319::1e7:d3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', NULL, '2026-01-07 12:34:56'),
(340, 26, NULL, 'login', 'Login realizado com sucesso', '2804:1b2:9441:16cb:616d:af6b:5be9:8f8e', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 22:43:03'),
(341, 21, NULL, 'login', 'Login realizado com sucesso', '2804:14c:7d86:863a:2ca7:64a2:c183:a57b', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', NULL, '2026-02-28 18:01:48'),
(342, 21, NULL, 'login', 'Login realizado com sucesso', '200.194.249.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-09 17:44:11'),
(343, 15, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cd6:48bd:43c4:e5fe:7227:ccb5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-20 22:44:01'),
(344, 15, NULL, 'logout', 'Logout realizado', '2804:14d:4cd6:48bd:43c4:e5fe:7227:ccb5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-20 22:56:47'),
(347, NULL, NULL, '208', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:14d:4cdc:9bfb:d857:c000:95bf:41ca', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 11:53:37'),
(348, NULL, NULL, '210', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:14d:4cdc:9bfb:b865:c24f:4dde:eaa7', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 12:16:14'),
(349, NULL, NULL, '211', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:14d:4cdc:9bfb:b865:c24f:4dde:eaa7', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 12:31:59'),
(350, NULL, NULL, '212', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:14d:4cdc:9bfb:b865:c24f:4dde:eaa7', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 12:44:13'),
(353, NULL, NULL, '213', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '200.194.249.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 19:05:23'),
(354, NULL, NULL, '214', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2a02:26f7:e530:5807:0:4000:0:d', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 20:39:00'),
(355, NULL, NULL, '215', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:389:f28b:9ffa:9d04:73cf:d5e8:cf04', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.7 Mobile/15E148 Safari/604.1', NULL, '2026-04-23 20:39:01'),
(356, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:5046:535a:5d1:939b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-29 10:22:31'),
(357, 3, NULL, 'logout', 'Logout realizado', '2804:14d:4cdc:9bfb:5046:535a:5d1:939b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-29 10:29:09'),
(358, 3, NULL, 'login', 'Login realizado com sucesso', '2804:14d:4cdc:9bfb:5046:535a:5d1:939b', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-29 10:30:57'),
(359, NULL, NULL, '217', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:d51:87de:f100:a1:f71a:3821:30ca', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', NULL, '2026-04-29 11:19:34'),
(360, NULL, NULL, '218', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:d51:47ee:3a00:e42c:5a10:3b90:9dd9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', NULL, '2026-04-29 11:20:25'),
(361, NULL, NULL, '219', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:388:c421:739b:d96d:fd80:99c7:9c52', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.7 Mobile/15E148 Safari/604.1', NULL, '2026-04-29 11:20:57'),
(362, NULL, NULL, '220', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:7f4:c9c2:28a8:9179:bb0f:e27d:4f71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/147.0.7727.99 Mobile/15E148 Safari/604.1', NULL, '2026-04-29 11:24:19'),
(363, 21, NULL, 'login', 'Login realizado com sucesso', '2804:1e68:4001:5570:fc67:7fe2:8e97:39d0', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', NULL, '2026-06-18 18:34:00'),
(364, 21, NULL, 'login', 'Login realizado com sucesso', '2804:7f4:c013:e685:4444:d4b:5466:1acd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-18 18:36:55'),
(365, 21, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-18 19:08:21'),
(366, 5, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:16:47'),
(367, 21, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:17:19'),
(368, 563, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:23:44'),
(369, 563, NULL, 'inativacao_paciente', 'Paciente inativado: Teste12', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:29:41'),
(370, 563, NULL, 'reativacao_paciente', 'Paciente reativado: Teste12', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:29:46'),
(371, 563, NULL, 'logout', 'Logout realizado', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:31:56'),
(372, 563, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:32:17'),
(373, 563, NULL, 'cadastro_paciente', 'Paciente cadastrado: Joao', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:33:01'),
(374, 563, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-19 23:56:11'),
(375, 21, NULL, 'login', 'Login realizado com sucesso', '2804:1e68:4001:8f1c:d926:987e:c2a5:9c0a', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-20 19:40:35'),
(376, 21, NULL, 'cadastro_paciente', 'Paciente cadastrado: José Eduardo', '2804:1e68:4001:8f1c:d926:987e:c2a5:9c0a', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-20 20:08:53'),
(377, 21, NULL, 'logout', 'Logout realizado', '2804:1e68:4001:8f1c:d926:987e:c2a5:9c0a', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-20 20:14:56'),
(378, 21, NULL, 'login', 'Login realizado com sucesso', '2804:1e68:4001:8f1c:d926:987e:c2a5:9c0a', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-20 20:16:57'),
(379, 21, NULL, 'login', 'Login realizado com sucesso', '2804:1e68:4001:cb51:4d91:17f2:f782:c6ce', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-21 18:25:34'),
(380, 21, NULL, 'PACIENTE_ALOCADO', 'Paciente ID 228 alocado para anestesista ID 26', '2804:1e68:4001:cb51:4d91:17f2:f782:c6ce', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-21 18:27:00'),
(381, 21, NULL, 'PACIENTE_DESALOCADO', 'Paciente ID 228 desalocado', '2804:1e68:4001:cb51:4d91:17f2:f782:c6ce', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-21 18:27:09'),
(382, NULL, NULL, '229', 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', '2804:389:f29a:9a9:acb7:79d2:23c2:fd62', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', NULL, '2026-06-21 18:32:18'),
(383, 5, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-24 00:00:58'),
(384, 5, NULL, 'logout', 'Logout realizado', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-24 00:01:28'),
(385, 5, NULL, 'login', 'Login realizado com sucesso', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-24 00:01:53'),
(386, 5, NULL, 'logout', 'Logout realizado', '177.101.218.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', NULL, '2026-06-24 00:02:27'),
(387, 21, NULL, 'login', 'Login realizado com sucesso', '2804:214:859b:f257:f98b:b979:8e33:b2fd', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', NULL, '2026-07-31 17:23:20'),
(388, 21, NULL, 'cadastro_paciente', 'Paciente cadastrado: Joao', '2804:214:859b:f257:f98b:b979:8e33:b2fd', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', NULL, '2026-07-31 17:31:26'),
(389, 21, NULL, 'login', 'Login realizado com sucesso', '2804:d51:4768:9400:2514:823f:6212:9d41', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Safari/605.1.15', NULL, '2026-07-31 17:56:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `anestesista_id` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `sobrenome` varchar(255) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `sexo` enum('M','F','O') DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `procedimento` text DEFAULT NULL,
  `procedimento_id` int(11) DEFAULT NULL,
  `data_procedimento` date DEFAULT NULL,
  `hora_procedimento` time DEFAULT NULL,
  `status` enum('cadastrado','autorizado','finalizado','inativo','ativo','questionario_respondido','questionario_incompleto') NOT NULL DEFAULT 'cadastrado',
  `questionario_status` enum('nao_iniciado','incompleto','completo') NOT NULL DEFAULT 'nao_iniciado',
  `questionario_percentual` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `questionario_videos_respondidos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `questionario_total_videos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `questionario_ultimo_video` varchar(255) DEFAULT NULL,
  `questionario_atualizado_em` datetime DEFAULT NULL,
  `inativo` tinyint(1) DEFAULT 0,
  `classificacao_ia` enum('baixo_risco','medio_risco','alto_risco') DEFAULT NULL,
  `link_acesso` varchar(255) DEFAULT NULL,
  `token_acesso` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `medico_id` int(11) DEFAULT NULL,
  `necessita_orientacao_pre_anestesica` tinyint(1) DEFAULT 0,
  `paciente_alto_risco` tinyint(1) DEFAULT 0,
  `criado_via_qr` tinyint(1) DEFAULT 0,
  `qr_code_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `instituicao_id`, `anestesista_id`, `nome`, `sobrenome`, `cpf`, `data_nascimento`, `sexo`, `telefone`, `email`, `endereco`, `procedimento`, `procedimento_id`, `data_procedimento`, `hora_procedimento`, `status`, `questionario_status`, `questionario_percentual`, `questionario_videos_respondidos`, `questionario_total_videos`, `questionario_ultimo_video`, `questionario_atualizado_em`, `inativo`, `classificacao_ia`, `link_acesso`, `token_acesso`, `created_at`, `updated_at`, `medico_id`, `necessita_orientacao_pre_anestesica`, `paciente_alto_risco`, `criado_via_qr`, `qr_code_id`) VALUES
(33, 7, 6, 'Gabriel Gael ', 'Lucca Pires', '791.127.190-99', '2000-10-06', 'M', '(51) 93883-9287', 'gabriel_gael_pires@cvc.com.br', NULL, NULL, 2, '2006-10-25', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente/acesso/aa008eefd72b49642b82e8c97faef1cf5869eb895cafbb0c620310f70e804f2b', 'aa008eefd72b49642b82e8c97faef1cf5869eb895cafbb0c620310f70e804f2b', '2025-10-10 20:14:48', '2025-11-20 16:10:41', 9, 0, 0, 0, NULL),
(66, 15, NULL, 'Ana', 'Fleig', '01716930052', '2025-04-01', 'F', '47992097675', 'anacarolinafleig@gmail.com', NULL, NULL, 1, '2025-10-31', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 1, NULL, '/paciente_acesso.php?token=c5e2f512011a23b0a8efbba086294b3dc902164d61314f71dd3078f4e0f6271e', 'c5e2f512011a23b0a8efbba086294b3dc902164d61314f71dd3078f4e0f6271e', '2025-10-17 17:10:02', '2026-01-13 17:52:36', NULL, 0, 0, 1, 15),
(91, 7, 9, 'Aparecida', ' Caroline Moura', '302.953.522-30', '1970-02-22', 'F', '(97) 93687-5992', 'aparecida_moura@tilapiareal.com.br', NULL, NULL, 2, '2025-11-07', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente/acesso/b519cc30e98c48dd4de0f674dacf3a46ed4956b6f7d73c1bc8134e24e42d20d9', 'b519cc30e98c48dd4de0f674dacf3a46ed4956b6f7d73c1bc8134e24e42d20d9', '2025-10-23 23:22:39', '2025-11-20 16:10:41', 9, 0, 0, 0, NULL),
(92, 7, 9, 'Maria ', 'Eugenia', '554.780.770-20', '1971-01-03', 'F', '(51) 99969-7013', 'cavalheiromariaeugenia@gmail.com', NULL, NULL, 1, '2025-11-07', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente/acesso/3d3a9b9429f2adc2d8c5b5113f971cec0547da6b1460df10a22e183a3513a5ad', '3d3a9b9429f2adc2d8c5b5113f971cec0547da6b1460df10a22e183a3513a5ad', '2025-10-23 23:24:50', '2025-11-20 16:10:41', 9, 0, 0, 0, NULL),
(95, 17, 22, 'Maria Eugenia', 'Cavalheiro', '55478077020', '1971-01-03', 'F', '51999697013', 'cavalheiromariaeugenia@gmail.com', NULL, NULL, 4, '2025-11-12', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente_video.php?token=064e1e0b63401736b8c99c8a42a4f3f1be62f1028fdfb8c275c52ec7ec962563', '064e1e0b63401736b8c99c8a42a4f3f1be62f1028fdfb8c275c52ec7ec962563', '2025-10-24 00:21:37', '2025-11-20 16:10:41', NULL, 0, 0, 1, 21),
(99, 15, 15, 'ANA', 'SERAFIM', '82619670004', '1978-03-31', 'F', '51999140051', 'anaelisa1978@gmail.com', NULL, NULL, 1, '2025-11-14', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente_video.php?token=2a0ed9bc67e87c978bc9fbf0c3baffc23cd7b20768413e6d14a41d01e48a247e', '2a0ed9bc67e87c978bc9fbf0c3baffc23cd7b20768413e6d14a41d01e48a247e', '2025-10-24 17:41:36', '2025-11-20 16:10:41', NULL, 0, 0, 1, 19),
(109, 15, 21, 'Hermes José', 'De Almeida Lopes', '29523575015', '1957-11-23', 'M', '51982302060', 'hjal57@gmail.com', NULL, NULL, 1, '2025-11-20', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente_video.php?token=5f44ae1341120ec4c312ef26fc13292b0bd863f531974e4e8f2e5cccb63b5e9f', '5f44ae1341120ec4c312ef26fc13292b0bd863f531974e4e8f2e5cccb63b5e9f', '2025-11-05 21:37:54', '2025-11-20 16:10:41', NULL, 0, 0, 1, 19),
(110, 15, 21, 'Fernanda', 'Elesbão', '00999143018', '1986-10-15', 'F', '51999628476', 'fernandaelesba@hotmail.com', NULL, NULL, 1, '2025-11-08', NULL, '', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente_video.php?token=054100cfbc2ec33993a1e7daafff32bd48352efac3e1c76036281d3da56df138', '054100cfbc2ec33993a1e7daafff32bd48352efac3e1c76036281d3da56df138', '2025-11-06 17:32:54', '2025-11-20 16:10:41', NULL, 0, 0, 1, 19),
(111, 7, 9, 'Arthur', 'Paciente', '023.185.500-18', '2006-07-22', 'M', '(51) 99778-2202', 'cavalheiro.arthur2006@gmail.com', NULL, NULL, 1, '2025-11-06', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 15, NULL, '2025-11-20 16:10:41', 0, NULL, '/paciente/acesso/e5078f3ca55ba8a5a0494762f5c7119f77898b86d78f0a02dac8313c2f7675fd', 'e5078f3ca55ba8a5a0494762f5c7119f77898b86d78f0a02dac8313c2f7675fd', '2025-11-07 18:48:15', '2025-11-20 16:10:41', 9, 1, 1, 0, NULL),
(139, 15, 15, 'Daniel', 'Goncalves', '81868537072', '1980-01-28', 'M', '51991038912', 'danielgoncalvesalves@hotmail.com', NULL, NULL, 3, '2025-11-29', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=6abacd7aeefeba115cf4d107da711d15962833b6a8f48b78ecac2d4a4175711a', '6abacd7aeefeba115cf4d107da711d15962833b6a8f48b78ecac2d4a4175711a', '2025-11-27 19:05:28', '2025-11-27 19:05:28', NULL, 0, 0, 1, 15),
(140, 15, 15, 'Marcus', 'De Paula', '38137208020', '1966-03-24', 'M', '04151981828616', 'marcusdepaula66@gmail.com', NULL, NULL, 1, '2025-11-27', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2025-11-27 19:18:28', 0, NULL, '/paciente_video.php?token=e81461770f6675955de1ff6bfb81553eb42b50f8fc8b8f8dd82a50f5f327afd4', 'e81461770f6675955de1ff6bfb81553eb42b50f8fc8b8f8dd82a50f5f327afd4', '2025-11-27 19:07:48', '2025-11-27 19:19:01', NULL, 0, 0, 1, 15),
(143, 15, NULL, 'Arthur', 'Paciente', '11111111111', '2006-07-22', 'M', '51999999999', 'tui.cavalheiro2006@gmail.com', NULL, NULL, 2, '2025-11-29', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2025-11-28 20:15:10', 1, NULL, '/paciente_video.php?token=76b160be2db4f7e76e4111e2d2b9536b666be313597ef5c05d528c4fc0d58e00', '76b160be2db4f7e76e4111e2d2b9536b666be313597ef5c05d528c4fc0d58e00', '2025-11-28 19:43:52', '2026-01-13 17:52:22', NULL, 0, 0, 1, 25),
(146, 17, 22, 'RAFAEL', 'REHM', '88888888888', '1978-02-02', 'M', '51984663366', 'rafaelrehm@hotmail.com', NULL, NULL, 3, '2025-12-02', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=a8c03fc1a7094c0a1300fc35aa46df91afe6c6b0db7066db8046aa053ac9c6a1', 'a8c03fc1a7094c0a1300fc35aa46df91afe6c6b0db7066db8046aa053ac9c6a1', '2025-12-02 14:14:23', '2025-12-02 14:14:23', NULL, 0, 0, 1, 21),
(147, 7, 4, 'José Eduardo', 'Souza', '016.584.487-89', '1986-06-04', 'M', '(51) 98106-6986', 'edu.uefs@gmail.com', NULL, NULL, 2, '2025-12-03', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente/acesso/b788a834403ce1c378825700b3feb000271882130d2a5db119106fcd03fb8bc6', 'b788a834403ce1c378825700b3feb000271882130d2a5db119106fcd03fb8bc6', '2025-12-03 15:53:19', '2025-12-03 15:53:19', 4, 0, 0, 0, NULL),
(148, 15, 21, 'Maria Virgínia', 'Schilling', '16731646091', '1948-07-19', 'F', '51999505507', 'rafavellinho@hotmail.com', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 1, NULL, '/paciente_video.php?token=d18bcc32afdcd630a76b216e2de87f4cfd49d52bca1af7f406a22ade693b4ab1', 'd18bcc32afdcd630a76b216e2de87f4cfd49d52bca1af7f406a22ade693b4ab1', '2025-12-15 13:01:44', '2026-01-13 17:52:59', NULL, 0, 0, 1, 19),
(155, 15, 21, 'Maria Virgínia', 'Schilling', '01255567058', '1948-07-19', 'F', '51999505507', 'rafavellinho@hotmail.com', NULL, NULL, 1, '2025-12-16', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2025-12-15 13:20:45', 0, NULL, '/paciente_video.php?token=da8e155f18118f42cb78698a0b0c51f8e78308a6943d90a813e083717382cb3f', 'da8e155f18118f42cb78698a0b0c51f8e78308a6943d90a813e083717382cb3f', '2025-12-15 13:07:00', '2025-12-15 13:21:29', NULL, 0, 0, 1, 19),
(157, 15, 21, 'Michael', 'Schuster', '00908566000', '1985-03-19', 'M', '51996890849', 'michaelbiscoito92@gmail.com', NULL, NULL, 1, '2026-01-09', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2025-12-16 17:41:35', 0, NULL, '/paciente_video.php?token=22312efdaeddb96c5e4a43496810456c28699cbafcb1dd9909f213c14c67722b', '22312efdaeddb96c5e4a43496810456c28699cbafcb1dd9909f213c14c67722b', '2025-12-16 13:27:00', '2025-12-16 17:44:53', NULL, 0, 0, 1, 19),
(158, 17, 22, 'Gilson', 'do Nascimento Lima', '94871671020', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=1bd9a3a9d1a4c10bdc98d05dc7f7518bd74d2bc6e407bf5ee5d3605f13da536d', '1bd9a3a9d1a4c10bdc98d05dc7f7518bd74d2bc6e407bf5ee5d3605f13da536d', '2025-12-16 23:57:27', '2025-12-16 23:57:27', NULL, 0, 0, 1, 21),
(165, 17, 22, 'teste 6', 'Lima', '58982680063', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=eb3a234d53c819cc2107d1f9e8b54b6be9139f8ab24beca45fc9016210d06942', 'eb3a234d53c819cc2107d1f9e8b54b6be9139f8ab24beca45fc9016210d06942', '2025-12-17 00:53:13', '2025-12-17 00:53:13', NULL, 0, 0, 1, 21),
(166, 17, 22, 'teste7', 'Lima', '62747379019', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=5bf73f4e2f6c697667d149ce6f566457d9b481d29d73b92610bcbbdd10a704cf', '5bf73f4e2f6c697667d149ce6f566457d9b481d29d73b92610bcbbdd10a704cf', '2025-12-17 01:00:10', '2025-12-17 01:00:10', NULL, 0, 0, 1, 21),
(167, 17, 22, 'teste8', 'Lima', '87069911068', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=70a429c5443f4845a3ecf6e5942be7b0163155e122beee91fe1663d2ef965235', '70a429c5443f4845a3ecf6e5942be7b0163155e122beee91fe1663d2ef965235', '2025-12-17 01:04:56', '2025-12-17 01:04:56', NULL, 0, 0, 1, 21),
(168, 17, 22, 'teste9', 'lima', '56868003080', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=7e016374b2c8e66db4470e7f6a29e91c969dcaccdc17cce1ab41d716fc4d35c2', '7e016374b2c8e66db4470e7f6a29e91c969dcaccdc17cce1ab41d716fc4d35c2', '2025-12-17 01:12:33', '2025-12-17 01:12:33', NULL, 0, 0, 1, 21),
(169, 17, 22, 'teste9', 'lima', '82131697007', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=43d9db6b44e720ab664ec7fd3ba749f434eacf878d9174bc6bd9dff6fb1f9377', '43d9db6b44e720ab664ec7fd3ba749f434eacf878d9174bc6bd9dff6fb1f9377', '2025-12-17 01:19:40', '2025-12-17 01:19:40', NULL, 0, 0, 1, 21),
(170, 17, 22, 'teste10', 'lima', '81655544098', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=072bae36e88941ae9fceceeb83bcbe0fb420f1d8b5ee45226a61073f2589b8f2', '072bae36e88941ae9fceceeb83bcbe0fb420f1d8b5ee45226a61073f2589b8f2', '2025-12-17 01:21:18', '2025-12-17 01:21:18', NULL, 0, 0, 1, 21),
(171, 17, 22, 'Teste 11', 'Lima', '38316707063', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-17', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=1472d93b0f281bee2d394aa56f473509ef08ff630b8e03fa98f7a4c2067a3c0d', '1472d93b0f281bee2d394aa56f473509ef08ff630b8e03fa98f7a4c2067a3c0d', '2025-12-18 00:52:06', '2025-12-18 00:52:06', NULL, 0, 0, 1, 21),
(172, 17, 22, 'Teste 12', 'Lima', '49527837073', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-17', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=2f67a2d7f76c799c69a89ea4e03447100a01d4fa42371e127a029b0196d39073', '2f67a2d7f76c799c69a89ea4e03447100a01d4fa42371e127a029b0196d39073', '2025-12-18 00:53:45', '2025-12-18 00:53:45', NULL, 0, 0, 1, 21),
(173, 17, 22, 'Teste12', 'Lima', '45141951030', '1979-03-09', 'M', '51984450605', 'gnasc@yahoo.com.br', NULL, NULL, 1, '2025-12-17', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=ffc71ca1658383637bcd65a7bc4b167ae85fbdaefeb5ba11ee1ba777773af951', 'ffc71ca1658383637bcd65a7bc4b167ae85fbdaefeb5ba11ee1ba777773af951', '2025-12-18 00:56:04', '2026-06-19 23:29:46', NULL, 0, 0, 1, 21),
(176, 15, 21, 'RAFAEL', 'REHM', '93262256072', '1978-02-22', 'M', '51984663366', 'rafaelrehm@hotmail.com', NULL, NULL, 3, '2025-12-18', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 1, NULL, '/paciente_video.php?token=f3b252fbe9b7fd6a53119284cde90595984f24ba16c80a03e78c85316935c576', 'f3b252fbe9b7fd6a53119284cde90595984f24ba16c80a03e78c85316935c576', '2025-12-18 16:51:17', '2026-01-13 17:52:13', NULL, 0, 0, 1, 19),
(178, 15, 21, 'Gilberto Santos da cruz', 'Cruz', '42145511091', '1962-11-03', 'M', '51999166814', 'gilbertosantoscruzz@gmail.com', NULL, NULL, 1, '2026-01-23', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=9ee7d835d65e105d849e8142e73b739f94d54f45a600499d0637f26699359776', '9ee7d835d65e105d849e8142e73b739f94d54f45a600499d0637f26699359776', '2025-12-19 14:14:51', '2025-12-19 14:14:51', NULL, 0, 0, 1, 19),
(185, 15, 21, 'Patrícia', 'Lumertz kras', '94262748049', '1980-04-04', 'F', '51997516302', 'pattykras34@gmail.com', NULL, NULL, 1, '2026-01-20', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-01-07 12:33:23', 0, NULL, '/paciente_video.php?token=2307cf77cb927dc4a08090235f14cbae714be8a0ba191ae1d224475759fe6b01', '2307cf77cb927dc4a08090235f14cbae714be8a0ba191ae1d224475759fe6b01', '2026-01-06 21:54:15', '2026-01-07 12:34:42', NULL, 0, 0, 1, 19),
(186, 15, 21, 'renato antoniazzi', 'antoniazzi', '26243415015', '1956-11-09', 'M', '51999822039', 'antoniazzi.renato@gmail.com', NULL, NULL, 1, '2026-01-10', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-01-07 12:32:23', 0, NULL, '/paciente_video.php?token=28f9e946abd79c23a638a00d174a8b447081323a0f9b14864e659248ee126a67', '28f9e946abd79c23a638a00d174a8b447081323a0f9b14864e659248ee126a67', '2026-01-07 12:20:29', '2026-01-07 12:33:07', NULL, 0, 0, 1, 19),
(187, 15, 21, 'Rosicleia', 'Silva soeiro', '00694434329', '1983-11-08', 'F', '51998094787', 'soeirorosy9@gmail.com', NULL, NULL, 1, '2026-02-03', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-01-14 17:13:15', 0, NULL, '/paciente_video.php?token=52aa40178c42a78108c2f94ec0d1b0b47ab4e5e9f877c000e58eaba60c23a03b', '52aa40178c42a78108c2f94ec0d1b0b47ab4e5e9f877c000e58eaba60c23a03b', '2026-01-13 21:56:36', '2026-01-14 17:19:04', NULL, 0, 0, 1, 19),
(188, 15, 21, 'Ana Paula', 'Ribeiro Rangel de Castro', '89902602053', '1976-11-18', 'F', '54996663833', 'nanah1976@gmail.com', NULL, NULL, 1, '2026-01-28', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-01-13 23:03:45', 0, NULL, '/paciente_video.php?token=d8df127e5ac1cd24599604d644a1a72e2a23b2d74e36ae01a1b0879870e5b9f7', 'd8df127e5ac1cd24599604d644a1a72e2a23b2d74e36ae01a1b0879870e5b9f7', '2026-01-13 22:29:23', '2026-01-13 23:05:10', NULL, 0, 0, 1, 19),
(189, 15, 21, 'Cavalcante', 'Peres Paiva', '46868500025', '1962-10-08', 'M', '55999594953', 'cavalcantejunyor@hotmail.com', NULL, NULL, 1, '2026-01-21', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=41a47781bc3b8de3c7b16099e2983b095b17f51b78da89960e35e5ede37671cf', '41a47781bc3b8de3c7b16099e2983b095b17f51b78da89960e35e5ede37671cf', '2026-01-19 16:45:00', '2026-01-19 16:45:00', NULL, 0, 0, 1, 19),
(193, 15, 21, 'Ângela', 'Toscani Elesbão', '49857177034', '1967-10-01', 'F', '51984994164', 'clinicaseitenfus@gmail.com', NULL, NULL, 1, '2026-02-03', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=cc6f32135cb15a46012765533622f290868bb63e6f17b1579b60950b453d9c1a', 'cc6f32135cb15a46012765533622f290868bb63e6f17b1579b60950b453d9c1a', '2026-01-26 18:08:39', '2026-01-26 18:08:39', NULL, 0, 0, 1, 19),
(194, 15, 21, 'Ines de Togni', 'Togni', '55997295087', '1964-05-31', 'F', '51991643074', 'inesdetogni@gmail.com', NULL, NULL, 1, '2026-02-10', NULL, 'questionario_respondido', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-02-09 22:56:50', 0, NULL, '/paciente_video.php?token=bf84c00e8aca97cb3bc413da9f87e6667284d137ba4570ee834282dcc6c1c75b', 'bf84c00e8aca97cb3bc413da9f87e6667284d137ba4570ee834282dcc6c1c75b', '2026-02-09 18:45:45', '2026-02-09 22:56:50', NULL, 0, 0, 1, 19),
(195, 15, 21, 'Jéssica', 'Holz', '02553230001', '1988-06-22', 'F', '51996444166', 'jessiholz@hotmail.com', NULL, NULL, 1, '2026-03-04', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-03-02 19:21:33', 0, NULL, '/paciente_video.php?token=e3b45d1d3cf6df556d62828a3e5ea8084e634fc66ffac74a02c370c0ad8b0dd2', 'e3b45d1d3cf6df556d62828a3e5ea8084e634fc66ffac74a02c370c0ad8b0dd2', '2026-03-02 19:09:08', '2026-03-02 19:21:56', NULL, 0, 0, 1, 19),
(196, 15, 21, 'Estela', 'Pires', '04836765005', '2000-10-28', 'F', '51992263884', 'estelavitoriop@hotmail.com', NULL, NULL, 1, '2026-03-20', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-03-10 22:22:06', 0, NULL, '/paciente_video.php?token=6cde89227d07057fae1058656d877aa6ae484d9a918f95574c310ca7916a7196', '6cde89227d07057fae1058656d877aa6ae484d9a918f95574c310ca7916a7196', '2026-03-10 20:56:14', '2026-03-10 22:22:42', NULL, 0, 0, 1, 19),
(198, 15, 21, 'Cesar', 'Sant\'Anna de Lima', '25577255015', '1956-04-30', 'M', '54999731478', 'joselanelima@hotmail.com', NULL, NULL, 1, '2026-04-07', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-03-30 15:33:25', 0, NULL, '/paciente_video.php?token=f95ddbe3b3a302fb88139be8f11cf1939f8ce4c180c72c71957fa89ff6b1158d', 'f95ddbe3b3a302fb88139be8f11cf1939f8ce4c180c72c71957fa89ff6b1158d', '2026-03-30 15:15:44', '2026-03-30 15:35:12', NULL, 0, 0, 1, 19),
(199, 15, 21, 'Maurício', 'Carvalho', '05524028006', '2006-09-30', 'M', '51997196068', 'carvalhomauricio341@gmail.com', NULL, NULL, 1, '2026-04-07', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-03-31 12:00:20', 0, NULL, '/paciente_video.php?token=9ddcaf8d024d5cb4b45be985ca843e46161fef06e596e839b14fdcdee3c38f85', '9ddcaf8d024d5cb4b45be985ca843e46161fef06e596e839b14fdcdee3c38f85', '2026-03-31 11:50:55', '2026-03-31 12:00:48', NULL, 0, 0, 1, 19),
(200, 15, 21, 'Gentil', 'Carraro', '06834469087', '1948-10-09', 'M', '54999915007', 'rafasei@hotmail.com', NULL, NULL, 1, '2026-04-30', NULL, 'questionario_respondido', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-09 17:44:58', 0, NULL, '/paciente_video.php?token=46bf6316c458ef946c0e7fff32ff1d354fa403fb3fc414733bc02da8a7f57b54', '46bf6316c458ef946c0e7fff32ff1d354fa403fb3fc414733bc02da8a7f57b54', '2026-04-09 17:34:33', '2026-04-09 17:44:58', NULL, 0, 0, 1, 19),
(201, 15, 21, 'Ligia', 'Lincho', '28340175068', '2026-10-08', 'F', '53981518062', 'ligialincho@hotmail.com', NULL, NULL, 1, '2026-04-25', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=8ad9a9e6027f0e016891672e51dc6e7a122f0fa7771aabb42fc792726ae46ead', '8ad9a9e6027f0e016891672e51dc6e7a122f0fa7771aabb42fc792726ae46ead', '2026-04-10 16:16:30', '2026-04-10 16:16:30', NULL, 0, 0, 1, 19),
(202, 15, 21, 'Helena Mariza', 'Xavier Bayne', '28462424020', '1945-08-31', 'F', '51999657212', 'patybayne@uol.com.br', NULL, NULL, 1, '2026-05-01', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-17 18:55:34', 0, NULL, '/paciente_video.php?token=328f39d5075f189c41e454e05b8eafc386d7ea35c3bd59c54350a6dd8c029774', '328f39d5075f189c41e454e05b8eafc386d7ea35c3bd59c54350a6dd8c029774', '2026-04-16 20:18:11', '2026-04-17 18:57:31', NULL, 0, 0, 1, 19),
(203, 15, 21, 'Cristiane Pereira', 'Alves Genro', '01194465021', '1981-05-04', 'F', '51991161812', 'agenro@gmail.com', NULL, NULL, 1, '2026-04-28', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=8e8b3a039333b4f50a8cdf91fef21ab424c287ac367b3234288354c5e89be575', '8e8b3a039333b4f50a8cdf91fef21ab424c287ac367b3234288354c5e89be575', '2026-04-17 20:14:07', '2026-04-17 20:14:07', NULL, 0, 0, 1, 19),
(204, 15, 21, 'Martha', 'Macedo Sittoni', '89494350049', '1975-09-30', 'F', '51995140207', 'msittoni@cmtadv.com.br', NULL, NULL, 1, '2026-05-05', NULL, 'questionario_respondido', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-23 12:57:55', 0, NULL, '/paciente_video.php?token=99c5c4e437d5cd729d9a62dfe7ac35657a344436bad59cd81f51ed27219d8858', '99c5c4e437d5cd729d9a62dfe7ac35657a344436bad59cd81f51ed27219d8858', '2026-04-20 12:53:14', '2026-04-23 12:57:55', NULL, 0, 0, 1, 19),
(205, 15, 21, 'Priscila', 'Carletti Da Silva', '01338088076', '1986-10-01', 'F', '51993479900', 'priscilajandrey@hotmail.com', NULL, NULL, 1, '2026-05-05', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-20 13:14:12', 0, NULL, '/paciente_video.php?token=78dda3392ab05ace448c7eb6f9cc3da01cfee17b458f0fa22dca4fd7c878f30a', '78dda3392ab05ace448c7eb6f9cc3da01cfee17b458f0fa22dca4fd7c878f30a', '2026-04-20 13:02:13', '2026-04-20 13:15:04', NULL, 0, 0, 1, 19),
(207, 15, 21, 'Djalma', 'Castilho', '00769800068', '1946-10-29', 'M', '53984034071', 'djalmacast@gmail.com', NULL, NULL, 1, '2026-05-05', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=43c861c6332d4f7954a9c0054ae0bd3e063aa5e3e8f23ce7d9198855d7191a28', '43c861c6332d4f7954a9c0054ae0bd3e063aa5e3e8f23ce7d9198855d7191a28', '2026-04-20 14:51:47', '2026-04-20 14:51:47', NULL, 0, 0, 1, 19),
(214, 15, NULL, 'Etielle', NULL, '014.911.360-90', '1987-03-23', 'F', '(51) 98222-2234', 'etiellesonaglio@gmail.com', 'Rua B', NULL, NULL, NULL, NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-23 20:53:44', 1, NULL, '/paciente/acesso/444e122b17019178353a5bc6f8f5f5adbc320d0e2068557f670dcecdde01cd69', '444e122b17019178353a5bc6f8f5f5adbc320d0e2068557f670dcecdde01cd69', '2026-04-23 20:39:00', '2026-04-29 11:09:18', NULL, 0, 0, 1, NULL),
(215, 15, NULL, 'Larissa da Rosa Feix', NULL, '008.570.900-03', '2020-04-23', 'F', '(51) 99192-8811', 'feixlarissa@gmail.com', 'Rua B', NULL, NULL, NULL, NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-23 20:49:05', 1, NULL, '/paciente/acesso/eb35fcea6862dd36874859e8f60b7614b0557007efe4f577d9cbdbeb70a7b8fc', 'eb35fcea6862dd36874859e8f60b7614b0557007efe4f577d9cbdbeb70a7b8fc', '2026-04-23 20:39:01', '2026-04-29 11:09:13', NULL, 0, 0, 1, NULL),
(217, 15, 15, 'Cristine Molinari Brum', NULL, '016.728.040-60', '1998-08-10', 'F', '(54) 99990-4773', 'cristinebrum@gmail.com', '', NULL, NULL, NULL, NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-29 11:30:33', 0, NULL, '/paciente/acesso/78aefea15508d4f0c59cd6a135847ffd3f7a863858a3b00f4435824b97325f18', '78aefea15508d4f0c59cd6a135847ffd3f7a863858a3b00f4435824b97325f18', '2026-04-29 11:19:34', '2026-04-29 11:30:47', NULL, 0, 0, 1, NULL),
(219, 15, 15, 'Larissa da Rosa Feix', NULL, '008.570.900-03', '1984-08-19', 'F', '(51) 99192-8811', 'feixlarissa@gmail.com', 'Rua.', NULL, NULL, NULL, NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-29 11:31:03', 0, NULL, '/paciente/acesso/078edc79fc2a5009c0c5ebe597d15a28bb081ea9ac18fba667e0e3b8c195f69c', '078edc79fc2a5009c0c5ebe597d15a28bb081ea9ac18fba667e0e3b8c195f69c', '2026-04-29 11:20:57', '2026-04-29 11:32:28', NULL, 0, 0, 1, NULL),
(220, 15, 15, 'Rafael Rehm', NULL, '932.622.560-72', '1978-02-22', 'M', '(51) 98466-3366', 'rafaelrehm@hotmail.com', 'Rua Matias José Bins, 1320\r\nCasa', NULL, NULL, NULL, NULL, 'questionario_respondido', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-04-29 11:41:05', 1, NULL, '/paciente/acesso/6b503474ca851660621d44d67b47d94e50837ff97867ebc273cc576927c4ce80', '6b503474ca851660621d44d67b47d94e50837ff97867ebc273cc576927c4ce80', '2026-04-29 11:24:19', '2026-04-29 11:48:06', NULL, 0, 0, 1, NULL),
(221, 15, 21, 'Flávio Augusto', 'Rossi', '44725515000', '1963-08-03', 'M', '51984305450', 'carla-adriana-camargo@hotmail.com', NULL, NULL, 1, '2026-05-26', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=24c36ebb25a3dbd9fd95a3b0f73a50de59d20bebbc03e7172cc19a983d600681', '24c36ebb25a3dbd9fd95a3b0f73a50de59d20bebbc03e7172cc19a983d600681', '2026-05-25 14:32:37', '2026-05-25 14:32:37', NULL, 0, 0, 1, 19),
(222, 15, 21, 'Flávio Augusto', 'Rossi', '44725515000', '1963-08-03', 'M', '51984305450', 'carla-adriana-camargo@hotmail.com', NULL, NULL, 1, '2026-05-26', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=a59f52a8f7f106a25d12d64a21b921705f24f03002b26c5590b28e48c70a1a31', 'a59f52a8f7f106a25d12d64a21b921705f24f03002b26c5590b28e48c70a1a31', '2026-05-25 14:32:38', '2026-05-25 14:32:38', NULL, 0, 0, 1, 19),
(223, 15, 21, 'Flávio Augusto', 'Rossi', '44725515000', '1963-08-03', 'M', '51984305450', 'carla-adriana-camargo@hotmail.com', NULL, NULL, 1, '2026-05-26', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=758ca23102eec0edfdfc1c8904a47d99ca2d016818237a2f6c7cb074cb8d9aea', '758ca23102eec0edfdfc1c8904a47d99ca2d016818237a2f6c7cb074cb8d9aea', '2026-05-25 14:32:39', '2026-05-25 14:32:39', NULL, 0, 0, 1, 19),
(224, 15, 21, 'Abner', 'Gomes dos Santos', '99308843000', '1980-12-04', 'M', '51999848259', 'abnerggomes@gmail.com', NULL, NULL, 1, '2026-06-17', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=bfa6e2002dbe4e9acc9a53fa1c3693a6a7dc2f6a2a56e125d90b6705fcd4b6d3', 'bfa6e2002dbe4e9acc9a53fa1c3693a6a7dc2f6a2a56e125d90b6705fcd4b6d3', '2026-06-02 17:15:06', '2026-06-02 17:15:06', NULL, 0, 0, 1, 19),
(225, 15, 21, 'Aline', 'Rambow', '93880561087', '1980-04-20', 'F', '51991513068', 'aline_rambow@hotmail.com', NULL, NULL, 1, '2026-06-19', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=b20f2aa957ed254990485588daab62d2632b8211eae2cfad19dc65997dcbcc07', 'b20f2aa957ed254990485588daab62d2632b8211eae2cfad19dc65997dcbcc07', '2026-06-05 17:10:30', '2026-06-05 17:10:30', NULL, 0, 0, 1, 19),
(226, 15, 21, 'Aline', 'Rambow', '93880561087', '1980-04-20', 'F', '51991513068', 'aline_rambow@hotmail.com', NULL, NULL, 1, '2026-06-19', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=2a48284cfa3cea9e4a4a9040da869e8ad7476a4d9415188ce81c26e213540402', '2a48284cfa3cea9e4a4a9040da869e8ad7476a4d9415188ce81c26e213540402', '2026-06-09 11:47:32', '2026-06-09 11:47:32', NULL, 0, 0, 1, 19),
(227, 10, NULL, 'Joao', 'Machado', '029.893.190-77', '1994-08-12', 'M', '(51) 98423-0938', 'joaob042@gmail.com', NULL, NULL, 2, '2026-06-20', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-06-19 23:55:01', 0, NULL, '/paciente/acesso/61580ce8f0d0f242b7bd18166f1c9939fb18c65d97a6eebb45559bd1cc5bd4df', '61580ce8f0d0f242b7bd18166f1c9939fb18c65d97a6eebb45559bd1cc5bd4df', '2026-06-19 23:33:01', '2026-06-19 23:55:33', 563, 0, 0, 0, NULL),
(228, 15, NULL, 'José Eduardo', 'Souza', '987.987.967-77', '1986-06-04', 'M', '(51) 98106-6986', 'edu.uefs@gmail.com', NULL, NULL, 2, '2022-12-12', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente/acesso/f9501df88cced8f6cc5650d7376983f5a0aa5852c9c92e8dfaf70582a8fd1b1c', 'f9501df88cced8f6cc5650d7376983f5a0aa5852c9c92e8dfaf70582a8fd1b1c', '2026-06-20 20:08:53', '2026-06-21 18:27:09', 21, 0, 0, 0, NULL),
(229, 15, 21, 'jose eduaro', NULL, '237.931.180-37', '2026-06-22', 'M', '(51) 98106-6986', 'edu.uefs@gmail.com', '', NULL, NULL, NULL, NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente/acesso/305638d9a0fb8fa34fd0c4c55064ce9e99615fc4317043beaa7aa681eeb31546', '305638d9a0fb8fa34fd0c4c55064ce9e99615fc4317043beaa7aa681eeb31546', '2026-06-21 18:32:18', '2026-06-21 18:32:18', NULL, 0, 0, 1, NULL),
(230, 15, 21, 'Jessica', 'Santos De Paula', '84728388034', '1992-12-22', 'F', '51993523979', 'jepaula4@gmail.com', NULL, NULL, 1, '2026-07-09', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=34ed3f560438c1c8c48eebfc7c57b13e8285320443b99b37904163af10ced0e0', '34ed3f560438c1c8c48eebfc7c57b13e8285320443b99b37904163af10ced0e0', '2026-06-24 12:42:56', '2026-06-24 12:42:56', NULL, 0, 0, 1, 19),
(231, 15, 21, 'Jessica', 'Santos De Paula', '84728388034', '1992-12-22', 'F', '51993523979', 'jepaula4@gmail.com', NULL, NULL, 1, '2026-07-09', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=f221825ae80dd3b5795d3c3e4c37d6763acc8d63b75bc81bc5fb28a2b3394273', 'f221825ae80dd3b5795d3c3e4c37d6763acc8d63b75bc81bc5fb28a2b3394273', '2026-06-24 22:34:47', '2026-06-24 22:34:47', NULL, 0, 0, 1, 19),
(232, 15, 21, 'Eduardo', 'Oliveira de Andrade', '02192661908', '1977-05-27', 'M', '51990091890', 'dudeboa2017@gmail.com', NULL, NULL, 1, '2026-07-07', NULL, '', 'incompleto', 93, 14, 15, 'Vídeo 15 - Exames Disponíveis', '2026-06-25 12:35:48', 0, NULL, '/paciente_video.php?token=3a9f8055a0cca867f3be30765b92df2a411a8cffb7a87b8c0b155e68a5266722', '3a9f8055a0cca867f3be30765b92df2a411a8cffb7a87b8c0b155e68a5266722', '2026-06-25 12:09:33', '2026-06-25 12:39:10', NULL, 0, 0, 1, 19),
(233, 15, 21, 'Claudia', 'Cardoso Dulinski', '28562577049', '1958-04-10', 'F', '51993326862', 'claudiacdulinski@gmail.com', NULL, NULL, 1, '2026-07-10', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=6d32bce3bea1f618f5d2aa2c92bc6cc78dbe3a7f47145accafb6d06382a97aa2', '6d32bce3bea1f618f5d2aa2c92bc6cc78dbe3a7f47145accafb6d06382a97aa2', '2026-06-25 12:13:52', '2026-06-25 12:13:52', NULL, 0, 0, 1, 19),
(234, 15, 21, 'Claudia', 'Cardoso Dulinski', '28562577049', '1958-04-10', 'F', '51993326862', 'claudiacdulinski@gmail.com', NULL, NULL, 1, '2026-07-11', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=af05a94036dffbe2f2a514dc7748a80c6d724f0da63e3fad1373a44822e4d669', 'af05a94036dffbe2f2a514dc7748a80c6d724f0da63e3fad1373a44822e4d669', '2026-06-26 20:47:58', '2026-06-26 20:47:58', NULL, 0, 0, 1, 19),
(235, 15, 21, 'Amanda', 'Benites Moreira Sangbusch', '03331134031', '1993-06-08', 'F', '51999966962', 'amandabenitesmoreira@gmail.com', NULL, NULL, 1, '2026-06-30', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=18e4f789a9dc23035e4fc340eef7ac0e596967bcd910d968f9f079181a768bf9', '18e4f789a9dc23035e4fc340eef7ac0e596967bcd910d968f9f079181a768bf9', '2026-06-27 20:08:54', '2026-06-27 20:08:54', NULL, 0, 0, 1, 19),
(236, 15, 21, 'Sérgio Antonio', 'Magnus', '20969830025', '1959-03-20', 'M', '51993023065', 'betemagnus@gmail.com', NULL, NULL, 1, '2026-07-14', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=299185811894dd7868f6886a6349387fdf626a93032b4129d2ef5e0b6a630623', '299185811894dd7868f6886a6349387fdf626a93032b4129d2ef5e0b6a630623', '2026-07-06 13:07:47', '2026-07-06 13:07:47', NULL, 0, 0, 1, 19),
(237, 15, 21, 'Sérgio Antonio', 'Magnus', '20969830025', '1959-03-20', 'M', '51993023065', 'betemagnus@gmail.com', NULL, NULL, 1, '2026-07-14', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=d63bbbd6c2b571ae041e82c7c4bcb543f26518ba7bb7e93efb67fca0dc47a2b1', 'd63bbbd6c2b571ae041e82c7c4bcb543f26518ba7bb7e93efb67fca0dc47a2b1', '2026-07-06 13:36:43', '2026-07-06 13:36:43', NULL, 0, 0, 1, 19),
(238, 15, 21, 'Liane', 'Moura', '96273011087', '2026-05-13', 'F', '51992461182', 'lilicatmoura@gmail.com', NULL, NULL, 1, '2026-07-24', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=3e05e1d3051976ca462bab7c79902f2edc64428d4b4a945aeb5f3ddb652b3b71', '3e05e1d3051976ca462bab7c79902f2edc64428d4b4a945aeb5f3ddb652b3b71', '2026-07-13 13:21:07', '2026-07-13 13:21:07', NULL, 0, 0, 1, 19),
(239, 15, 21, 'Liane', 'Moura', '96273011087', '2026-05-13', 'F', '51992461182', 'lilicatmoura@gmail.com', NULL, NULL, 1, '2026-07-24', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=4ec558ecba93030c534819e9ad5d38f381d92040eeff5de7ace2a800bb6d2369', '4ec558ecba93030c534819e9ad5d38f381d92040eeff5de7ace2a800bb6d2369', '2026-07-13 13:33:29', '2026-07-13 13:33:29', NULL, 0, 0, 1, 19),
(240, 15, 21, 'José Edimar', 'Wiatroski Nunes', '55873456020', '1968-06-08', 'M', '51996766969', 'mardelinunes@gmail.com', NULL, NULL, 1, '2026-08-16', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=f3cb9598d7836feff488fc741a1f0f59c21ef4f043a010f5a55db4d7ba0e049c', 'f3cb9598d7836feff488fc741a1f0f59c21ef4f043a010f5a55db4d7ba0e049c', '2026-07-30 17:43:46', '2026-07-30 17:43:46', NULL, 0, 0, 1, 19),
(241, 15, 21, 'Maria Beatriz', 'Braga', '30926300091', '1959-03-20', 'F', '51998081284', 'tizabraga@yahoo.com.br', NULL, NULL, 1, '2026-08-04', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=fda20d6d10be6355797a45f8966daf83ad6044a65351e8e71a179d2f69be1dea', 'fda20d6d10be6355797a45f8966daf83ad6044a65351e8e71a179d2f69be1dea', '2026-07-31 15:48:26', '2026-07-31 15:48:26', NULL, 0, 0, 1, 19),
(242, 15, 21, 'Maria Beatriz', 'Braga', '30926300091', '1959-03-20', 'F', '51998081284', 'tizabraga@yahoo.com.br', NULL, NULL, 1, '2026-08-04', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=d3d2e05c9d9198240b884224e58c78d99d3c8afc71b568933abe49648585cd52', 'd3d2e05c9d9198240b884224e58c78d99d3c8afc71b568933abe49648585cd52', '2026-07-31 15:48:28', '2026-07-31 15:48:28', NULL, 0, 0, 1, 19),
(243, 15, 21, 'Maria Beatriz', 'Braga', '30926300091', '1959-03-20', 'F', '51998081284', 'tizabraga@yahoo.com.br', NULL, NULL, 1, '2026-08-04', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=ace11133fad7fe0bf879cdb401197a450dd208b8883d87324b8d3a4393024da5', 'ace11133fad7fe0bf879cdb401197a450dd208b8883d87324b8d3a4393024da5', '2026-07-31 15:48:29', '2026-07-31 15:48:29', NULL, 0, 0, 1, 19),
(244, 15, 21, 'Marta', 'Dal Molin Pegoraro', '40835820068', '1962-08-02', 'F', '54996068973', 'equilibrio54@gmail.com', NULL, NULL, 1, '2026-08-05', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=f3febed69ddfce070b75095b86ecba425277249b69692f72785d5669ef2823a4', 'f3febed69ddfce070b75095b86ecba425277249b69692f72785d5669ef2823a4', '2026-07-31 17:19:10', '2026-07-31 17:19:10', NULL, 0, 0, 1, 19),
(245, 15, 21, 'Marta', 'Dal Molin Pegoraro', '40835820068', '1962-08-02', 'F', '54996068973', 'equilibrio54@gmail.com', NULL, NULL, 1, '2026-08-05', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=294299bd93a689c4037c0cc4c61719285311689514bf9c44a802cc164a33cd6b', '294299bd93a689c4037c0cc4c61719285311689514bf9c44a802cc164a33cd6b', '2026-07-31 17:19:18', '2026-07-31 17:19:18', NULL, 0, 0, 1, 19),
(246, 15, 21, 'Marta', 'Dal Molin Pegoraro', '40835820068', '1962-08-02', 'F', '54996068973', 'equilibrio54@gmail.com', NULL, NULL, 1, '2026-08-05', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=4670955f84a367a2175c227a4cf3c2c86bd77bc6f485707c84b6089a5702820d', '4670955f84a367a2175c227a4cf3c2c86bd77bc6f485707c84b6089a5702820d', '2026-07-31 17:19:27', '2026-07-31 17:19:27', NULL, 0, 0, 1, 19),
(247, 15, 21, 'Marta', 'Dal Molin Pegoraro', '40835820068', '1962-08-02', 'F', '54996068973', 'equilibrio54@gmail.com', NULL, NULL, 1, '2026-08-05', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=9b8306ed612cb2a782aaf7ecbb176d2562eabefd92f473c17c427a2a9c4be222', '9b8306ed612cb2a782aaf7ecbb176d2562eabefd92f473c17c427a2a9c4be222', '2026-07-31 17:19:30', '2026-07-31 17:19:30', NULL, 0, 0, 1, 19),
(248, 15, 21, 'Joao', 'Machado', '023.898.530-05', '2026-07-31', 'M', '(51) 98423-0938', 'joaob007@hotmail.com', NULL, NULL, 3, '2026-07-31', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-07-31 18:09:18', 0, NULL, '/paciente/acesso/6f25780a29f8d0224c0a51123319726b8c8a813e43a19a34e4ee1fa553c4c888', '6f25780a29f8d0224c0a51123319726b8c8a813e43a19a34e4ee1fa553c4c888', '2026-07-31 17:31:26', '2026-07-31 18:09:39', 21, 0, 0, 0, NULL),
(249, 15, 21, 'Maria Beatriz', 'Braga', '30926300091', '1959-03-20', 'F', '51998081284', 'tizabraga@yahoo.com.br', NULL, NULL, 1, '2026-08-04', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=5e75b37d771f234bdb6fc3db5d4e65324ae5b4a701207e96c6dd8aab02a7cf68', '5e75b37d771f234bdb6fc3db5d4e65324ae5b4a701207e96c6dd8aab02a7cf68', '2026-08-03 17:22:49', '2026-08-03 17:22:49', NULL, 0, 0, 1, 19),
(250, 15, 21, 'Maria Beatriz', 'Braga', '30926300091', '1959-03-20', 'F', '51998081284', 'tizabraga@yahoo.com.br', NULL, NULL, 1, '2026-08-04', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-08-03 17:43:27', 0, NULL, '/paciente_video.php?token=17ea5bb519334d7f4c9f887b184d463ca3029fe2ce94c96dbf0a6a52fceb6220', '17ea5bb519334d7f4c9f887b184d463ca3029fe2ce94c96dbf0a6a52fceb6220', '2026-08-03 17:22:50', '2026-08-03 17:44:20', NULL, 0, 0, 1, 19),
(251, 15, 21, 'Silvino', 'Munhoz Filho', '26554062068', '1957-05-02', 'M', '51995828333', 'ligiagrmunhoz@gmail.com', NULL, NULL, 1, '2026-08-24', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=a16bea8a69cf213b9dee378424f3ef73ee4df77090c76e9e26a0a4acf83d3e4c', 'a16bea8a69cf213b9dee378424f3ef73ee4df77090c76e9e26a0a4acf83d3e4c', '2026-08-09 15:30:08', '2026-08-09 15:30:08', NULL, 0, 0, 1, 19),
(252, 15, 21, 'Silvino', 'Munhoz Filho', '26554062068', '1957-05-02', 'M', '51995828333', 'ligiagrmunhoz@gmail.com', NULL, NULL, 1, '2026-08-24', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-08-09 16:37:18', 0, NULL, '/paciente_video.php?token=6778d87f472c47ec5332f8229b8f06244b59ef95ad3881e46c43e3be7df3d1a3', '6778d87f472c47ec5332f8229b8f06244b59ef95ad3881e46c43e3be7df3d1a3', '2026-08-09 15:30:09', '2026-08-09 16:37:34', NULL, 0, 0, 1, 19),
(253, 15, 21, 'Igor', 'Pereira da Silva', '03022327021', '1996-07-14', 'M', '55996616660', 'igordasilva15069@gmail.com', NULL, NULL, 1, '2026-08-19', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=11e6bd60d12e5bf21dfe5548d570c2d4564f0e9ba124b5bb2f3264d6fbd921f9', '11e6bd60d12e5bf21dfe5548d570c2d4564f0e9ba124b5bb2f3264d6fbd921f9', '2026-08-11 16:39:30', '2026-08-11 16:39:30', NULL, 0, 0, 1, 19),
(254, 15, 21, 'Marcelo', 'Brandão Dourado', '73771120004', '1971-11-13', 'M', '51996556250', 'marcelo_dourado@hotmail.com', NULL, NULL, 1, '2026-08-28', NULL, '', 'completo', 100, 15, 15, 'Vídeo 15 - Exames Disponíveis', '2026-08-14 23:46:51', 0, NULL, '/paciente_video.php?token=c34df6ab99cbc95306695b4e1a48545cb67d718eb20249644c83941fd7f4f6b8', 'c34df6ab99cbc95306695b4e1a48545cb67d718eb20249644c83941fd7f4f6b8', '2026-08-14 22:58:07', '2026-08-14 23:48:11', NULL, 0, 0, 1, 19),
(255, 15, 21, 'Marcelo', 'Brandão Dourado', '73771120004', '1971-11-13', 'M', '51996556250', 'marcelo_dourado@hotmail.com', NULL, NULL, 1, '2026-08-28', NULL, 'cadastrado', 'nao_iniciado', 0, 0, 0, NULL, NULL, 0, NULL, '/paciente_video.php?token=aa28443fe997e9fda9364bf35053cc9c4fd33155a8ec54e9a1a0e8f0235e00f3', 'aa28443fe997e9fda9364bf35053cc9c4fd33155a8ec54e9a1a0e8f0235e00f3', '2026-08-14 23:24:01', '2026-08-14 23:24:01', NULL, 0, 0, 1, 19);

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente_anestesistas`
--

CREATE TABLE `paciente_anestesistas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `anestesista_id` int(11) NOT NULL,
  `data_atribuicao` timestamp NOT NULL DEFAULT current_timestamp(),
  `observacoes` text DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `paciente_anestesistas`
--

INSERT INTO `paciente_anestesistas` (`id`, `paciente_id`, `anestesista_id`, `data_atribuicao`, `observacoes`, `status`, `created_at`, `updated_at`) VALUES
(26, 66, 15, '2025-10-17 17:10:02', NULL, 'inativo', '2025-10-17 17:10:02', '2025-10-17 22:26:32'),
(38, 92, 9, '2025-10-23 23:27:16', NULL, 'ativo', '2025-10-23 23:27:16', '2025-10-23 23:27:16'),
(41, 95, 22, '2025-10-24 00:21:37', NULL, 'ativo', '2025-10-24 00:21:37', '2025-10-24 00:21:37'),
(44, 99, 21, '2025-10-24 17:41:36', NULL, 'inativo', '2025-10-24 17:41:36', '2025-10-28 00:00:32'),
(48, 99, 15, '2025-10-28 00:00:38', NULL, 'ativo', '2025-10-28 00:00:38', '2025-10-28 00:00:38'),
(49, 109, 21, '2025-11-05 21:37:54', NULL, 'ativo', '2025-11-05 21:37:54', '2025-11-05 21:37:54'),
(50, 110, 21, '2025-11-06 17:32:54', NULL, 'ativo', '2025-11-06 17:32:54', '2025-11-06 17:32:54'),
(63, 139, 15, '2025-11-27 19:05:28', NULL, 'ativo', '2025-11-27 19:05:28', '2025-11-27 19:05:28'),
(64, 140, 15, '2025-11-27 19:07:48', NULL, 'ativo', '2025-11-27 19:07:48', '2025-11-27 19:07:48'),
(66, 146, 22, '2025-12-02 14:14:23', NULL, 'ativo', '2025-12-02 14:14:23', '2025-12-02 14:14:23'),
(67, 148, 21, '2025-12-15 13:01:44', NULL, 'ativo', '2025-12-15 13:01:44', '2025-12-15 13:01:44'),
(68, 155, 21, '2025-12-15 13:07:00', NULL, 'ativo', '2025-12-15 13:07:00', '2025-12-15 13:07:00'),
(69, 157, 21, '2025-12-16 13:27:00', NULL, 'ativo', '2025-12-16 13:27:00', '2025-12-16 13:27:00'),
(70, 158, 22, '2025-12-16 23:57:27', NULL, 'ativo', '2025-12-16 23:57:27', '2025-12-16 23:57:27'),
(77, 165, 22, '2025-12-17 00:53:13', NULL, 'ativo', '2025-12-17 00:53:13', '2025-12-17 00:53:13'),
(78, 166, 22, '2025-12-17 01:00:10', NULL, 'ativo', '2025-12-17 01:00:10', '2025-12-17 01:00:10'),
(79, 167, 22, '2025-12-17 01:04:56', NULL, 'ativo', '2025-12-17 01:04:56', '2025-12-17 01:04:56'),
(80, 168, 22, '2025-12-17 01:12:33', NULL, 'ativo', '2025-12-17 01:12:33', '2025-12-17 01:12:33'),
(81, 169, 22, '2025-12-17 01:19:40', NULL, 'ativo', '2025-12-17 01:19:40', '2025-12-17 01:19:40'),
(82, 170, 22, '2025-12-17 01:21:18', NULL, 'ativo', '2025-12-17 01:21:18', '2025-12-17 01:21:18'),
(83, 171, 22, '2025-12-18 00:52:06', NULL, 'ativo', '2025-12-18 00:52:06', '2025-12-18 00:52:06'),
(84, 172, 22, '2025-12-18 00:53:45', NULL, 'ativo', '2025-12-18 00:53:45', '2025-12-18 00:53:45'),
(85, 173, 22, '2025-12-18 00:56:04', NULL, 'ativo', '2025-12-18 00:56:04', '2025-12-18 00:56:04'),
(88, 176, 21, '2025-12-18 16:51:17', NULL, 'ativo', '2025-12-18 16:51:17', '2025-12-18 16:51:17'),
(89, 178, 21, '2025-12-19 14:14:51', NULL, 'ativo', '2025-12-19 14:14:51', '2025-12-19 14:14:51'),
(90, 185, 21, '2026-01-06 21:54:15', NULL, 'ativo', '2026-01-06 21:54:15', '2026-01-06 21:54:15'),
(91, 186, 21, '2026-01-07 12:20:29', NULL, 'ativo', '2026-01-07 12:20:29', '2026-01-07 12:20:29'),
(92, 187, 21, '2026-01-13 21:56:36', NULL, 'ativo', '2026-01-13 21:56:36', '2026-01-13 21:56:36'),
(93, 188, 21, '2026-01-13 22:29:23', NULL, 'ativo', '2026-01-13 22:29:23', '2026-01-13 22:29:23'),
(94, 189, 21, '2026-01-19 16:45:00', NULL, 'ativo', '2026-01-19 16:45:00', '2026-01-19 16:45:00'),
(95, 193, 21, '2026-01-26 18:08:39', NULL, 'ativo', '2026-01-26 18:08:39', '2026-01-26 18:08:39'),
(96, 194, 21, '2026-02-09 18:45:45', NULL, 'ativo', '2026-02-09 18:45:45', '2026-02-09 18:45:45'),
(97, 195, 21, '2026-03-02 19:09:08', NULL, 'ativo', '2026-03-02 19:09:08', '2026-03-02 19:09:08'),
(98, 196, 21, '2026-03-10 20:56:14', NULL, 'ativo', '2026-03-10 20:56:14', '2026-03-10 20:56:14'),
(99, 198, 21, '2026-03-30 15:15:44', NULL, 'ativo', '2026-03-30 15:15:44', '2026-03-30 15:15:44'),
(100, 199, 21, '2026-03-31 11:50:55', NULL, 'ativo', '2026-03-31 11:50:55', '2026-03-31 11:50:55'),
(101, 200, 21, '2026-04-09 17:34:33', NULL, 'ativo', '2026-04-09 17:34:33', '2026-04-09 17:34:33'),
(102, 201, 21, '2026-04-10 16:16:30', NULL, 'ativo', '2026-04-10 16:16:30', '2026-04-10 16:16:30'),
(103, 202, 21, '2026-04-16 20:18:11', NULL, 'ativo', '2026-04-16 20:18:11', '2026-04-16 20:18:11'),
(104, 203, 21, '2026-04-17 20:14:07', NULL, 'ativo', '2026-04-17 20:14:07', '2026-04-17 20:14:07'),
(105, 204, 21, '2026-04-20 12:53:14', NULL, 'ativo', '2026-04-20 12:53:14', '2026-04-20 12:53:14'),
(106, 205, 21, '2026-04-20 13:02:13', NULL, 'ativo', '2026-04-20 13:02:13', '2026-04-20 13:02:13'),
(107, 207, 21, '2026-04-20 14:51:47', NULL, 'ativo', '2026-04-20 14:51:47', '2026-04-20 14:51:47'),
(113, 214, 15, '2026-04-23 20:39:00', NULL, 'inativo', '2026-04-23 20:39:00', '2026-04-23 20:52:41'),
(114, 215, 15, '2026-04-23 20:39:01', NULL, 'inativo', '2026-04-23 20:39:01', '2026-04-23 20:52:33'),
(115, 217, 15, '2026-04-29 11:19:34', NULL, 'ativo', '2026-04-29 11:19:34', '2026-04-29 11:19:34'),
(117, 219, 15, '2026-04-29 11:20:57', NULL, 'ativo', '2026-04-29 11:20:57', '2026-04-29 11:20:57'),
(118, 220, 15, '2026-04-29 11:24:19', NULL, 'ativo', '2026-04-29 11:24:19', '2026-04-29 11:24:19'),
(120, 221, 21, '2026-05-25 14:32:37', NULL, 'ativo', '2026-05-25 14:32:37', '2026-05-25 14:32:37'),
(121, 222, 21, '2026-05-25 14:32:38', NULL, 'ativo', '2026-05-25 14:32:38', '2026-05-25 14:32:38'),
(122, 223, 21, '2026-05-25 14:32:39', NULL, 'ativo', '2026-05-25 14:32:39', '2026-05-25 14:32:39'),
(123, 224, 21, '2026-06-02 17:15:06', NULL, 'ativo', '2026-06-02 17:15:06', '2026-06-02 17:15:06'),
(124, 225, 21, '2026-06-05 17:10:30', NULL, 'ativo', '2026-06-05 17:10:30', '2026-06-05 17:10:30'),
(125, 226, 21, '2026-06-09 11:47:32', NULL, 'ativo', '2026-06-09 11:47:32', '2026-06-09 11:47:32'),
(126, 228, 26, '2026-06-21 18:27:00', NULL, 'inativo', '2026-06-21 18:27:00', '2026-06-21 18:27:09'),
(127, 229, 21, '2026-06-21 18:32:18', NULL, 'ativo', '2026-06-21 18:32:18', '2026-06-21 18:32:18'),
(128, 230, 21, '2026-06-24 12:42:56', NULL, 'ativo', '2026-06-24 12:42:56', '2026-06-24 12:42:56'),
(129, 231, 21, '2026-06-24 22:34:47', NULL, 'ativo', '2026-06-24 22:34:47', '2026-06-24 22:34:47'),
(130, 232, 21, '2026-06-25 12:09:33', NULL, 'ativo', '2026-06-25 12:09:33', '2026-06-25 12:09:33'),
(131, 233, 21, '2026-06-25 12:13:52', NULL, 'ativo', '2026-06-25 12:13:52', '2026-06-25 12:13:52'),
(132, 234, 21, '2026-06-26 20:47:58', NULL, 'ativo', '2026-06-26 20:47:58', '2026-06-26 20:47:58'),
(133, 235, 21, '2026-06-27 20:08:54', NULL, 'ativo', '2026-06-27 20:08:54', '2026-06-27 20:08:54'),
(134, 236, 21, '2026-07-06 13:07:47', NULL, 'ativo', '2026-07-06 13:07:47', '2026-07-06 13:07:47'),
(135, 237, 21, '2026-07-06 13:36:43', NULL, 'ativo', '2026-07-06 13:36:43', '2026-07-06 13:36:43'),
(136, 238, 21, '2026-07-13 13:21:07', NULL, 'ativo', '2026-07-13 13:21:07', '2026-07-13 13:21:07'),
(137, 239, 21, '2026-07-13 13:33:29', NULL, 'ativo', '2026-07-13 13:33:29', '2026-07-13 13:33:29'),
(138, 240, 21, '2026-07-30 17:43:46', NULL, 'ativo', '2026-07-30 17:43:46', '2026-07-30 17:43:46'),
(139, 241, 21, '2026-07-31 15:48:26', NULL, 'ativo', '2026-07-31 15:48:26', '2026-07-31 15:48:26'),
(140, 242, 21, '2026-07-31 15:48:28', NULL, 'ativo', '2026-07-31 15:48:28', '2026-07-31 15:48:28'),
(141, 243, 21, '2026-07-31 15:48:29', NULL, 'ativo', '2026-07-31 15:48:29', '2026-07-31 15:48:29'),
(142, 244, 21, '2026-07-31 17:19:10', NULL, 'ativo', '2026-07-31 17:19:10', '2026-07-31 17:19:10'),
(143, 245, 21, '2026-07-31 17:19:18', NULL, 'ativo', '2026-07-31 17:19:18', '2026-07-31 17:19:18'),
(144, 246, 21, '2026-07-31 17:19:27', NULL, 'ativo', '2026-07-31 17:19:27', '2026-07-31 17:19:27'),
(145, 247, 21, '2026-07-31 17:19:30', NULL, 'ativo', '2026-07-31 17:19:30', '2026-07-31 17:19:30'),
(146, 249, 21, '2026-08-03 17:22:49', NULL, 'ativo', '2026-08-03 17:22:49', '2026-08-03 17:22:49'),
(147, 250, 21, '2026-08-03 17:22:50', NULL, 'ativo', '2026-08-03 17:22:50', '2026-08-03 17:22:50'),
(148, 251, 21, '2026-08-09 15:30:08', NULL, 'ativo', '2026-08-09 15:30:08', '2026-08-09 15:30:08'),
(149, 252, 21, '2026-08-09 15:30:09', NULL, 'ativo', '2026-08-09 15:30:09', '2026-08-09 15:30:09'),
(150, 253, 21, '2026-08-11 16:39:30', NULL, 'ativo', '2026-08-11 16:39:30', '2026-08-11 16:39:30'),
(151, 254, 21, '2026-08-14 22:58:07', NULL, 'ativo', '2026-08-14 22:58:07', '2026-08-14 22:58:07'),
(152, 255, 21, '2026-08-14 23:24:01', NULL, 'ativo', '2026-08-14 23:24:01', '2026-08-14 23:24:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente_video_estatisticas`
--

CREATE TABLE `paciente_video_estatisticas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `total_videos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `videos_respondidos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `percentual_conclusao` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` enum('nao_iniciado','incompleto','completo') NOT NULL DEFAULT 'nao_iniciado',
  `ultimo_video_id` varchar(50) DEFAULT NULL,
  `ultimo_video_titulo` varchar(255) DEFAULT NULL,
  `data_primeira_resposta` datetime DEFAULT NULL,
  `data_ultima_resposta` datetime DEFAULT NULL,
  `videos_pendentes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `paciente_video_estatisticas`
--

INSERT INTO `paciente_video_estatisticas` (`id`, `paciente_id`, `total_videos`, `videos_respondidos`, `percentual_conclusao`, `status`, `ultimo_video_id`, `ultimo_video_titulo`, `data_primeira_resposta`, `data_ultima_resposta`, `videos_pendentes`, `created_at`, `updated_at`) VALUES
(1, 33, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(5, 91, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(6, 92, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(7, 111, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(8, 66, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(13, 99, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(17, 109, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(18, 110, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(21, 95, 15, 0, 0.00, 'nao_iniciado', NULL, NULL, NULL, NULL, NULL, '2025-11-20 15:15:31', '2025-11-20 15:15:31'),
(37, 140, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2025-11-27 19:09:07', '2025-11-27 19:18:26', '[]', '2025-11-27 19:18:28', '2025-11-27 19:18:28'),
(38, 143, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2025-11-28 19:46:22', '2025-11-28 20:15:09', '[]', '2025-11-28 20:15:10', '2025-11-28 20:15:10'),
(39, 155, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2025-12-15 13:09:15', '2025-12-15 13:20:43', '[]', '2025-12-15 13:20:45', '2025-12-15 13:20:45'),
(40, 157, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2025-12-16 17:29:28', '2025-12-16 17:41:30', '[]', '2025-12-16 17:41:35', '2025-12-16 17:41:35'),
(42, 186, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-01-07 12:21:39', '2026-01-07 12:32:20', '[]', '2026-01-07 12:32:23', '2026-01-07 12:32:23'),
(43, 185, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-01-07 12:19:35', '2026-01-07 12:33:21', '[]', '2026-01-07 12:33:23', '2026-01-07 12:33:23'),
(44, 188, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-01-13 22:53:19', '2026-01-13 23:03:32', '[]', '2026-01-13 23:03:45', '2026-01-13 23:03:45'),
(45, 187, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-01-14 16:45:35', '2026-01-14 17:12:59', '[]', '2026-01-14 17:13:15', '2026-01-14 17:13:15'),
(46, 194, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-02-09 22:45:19', '2026-02-09 22:56:40', '[]', '2026-02-09 22:56:50', '2026-02-09 22:56:50'),
(47, 195, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-03-02 19:10:30', '2026-03-02 19:21:32', '[]', '2026-03-02 19:21:33', '2026-03-02 19:21:33'),
(48, 196, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-03-10 22:14:58', '2026-03-10 22:22:05', '[]', '2026-03-10 22:22:06', '2026-03-10 22:22:06'),
(49, 198, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-03-30 15:21:35', '2026-03-30 15:33:23', '[]', '2026-03-30 15:33:25', '2026-03-30 15:33:25'),
(50, 199, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-03-31 11:51:46', '2026-03-31 12:00:18', '[]', '2026-03-31 12:00:20', '2026-03-31 12:00:20'),
(51, 200, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-09 17:36:49', '2026-04-09 17:43:51', '[]', '2026-04-09 17:44:58', '2026-04-09 17:44:58'),
(52, 202, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-17 18:38:58', '2026-04-17 18:55:29', '[]', '2026-04-17 18:55:34', '2026-04-17 18:55:34'),
(53, 205, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-20 13:03:43', '2026-04-20 13:14:09', '[]', '2026-04-20 13:14:12', '2026-04-20 13:14:12'),
(54, 204, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-23 12:46:42', '2026-04-23 12:57:52', '[]', '2026-04-23 12:57:55', '2026-04-23 12:57:55'),
(55, 215, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-23 20:40:08', '2026-04-23 20:49:03', '[]', '2026-04-23 20:49:05', '2026-04-23 20:49:05'),
(56, 214, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-23 20:42:09', '2026-04-23 20:53:42', '[]', '2026-04-23 20:53:44', '2026-04-23 20:53:44'),
(57, 217, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-29 11:22:49', '2026-04-29 11:30:32', '[]', '2026-04-29 11:30:33', '2026-04-29 11:30:33'),
(59, 219, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-29 11:22:35', '2026-04-29 11:31:02', '[]', '2026-04-29 11:31:03', '2026-04-29 11:31:03'),
(60, 220, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-04-29 11:32:47', '2026-04-29 11:41:03', '[]', '2026-04-29 11:41:05', '2026-04-29 11:41:05'),
(61, 227, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-06-19 23:34:12', '2026-06-19 23:54:45', '[]', '2026-06-19 23:55:01', '2026-06-19 23:55:01'),
(62, 232, 15, 14, 93.33, 'incompleto', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-06-25 12:16:20', '2026-06-25 12:35:44', '[{\"id\":\"video_14\",\"title\":\"Vídeo 14 - Classificação de Mallampati\"}]', '2026-06-25 12:35:48', '2026-06-25 12:35:48'),
(63, 248, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-07-31 18:01:56', '2026-07-31 18:09:17', '[]', '2026-07-31 18:09:18', '2026-07-31 18:09:18'),
(64, 250, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-08-03 17:24:23', '2026-08-03 17:43:25', '[]', '2026-08-03 17:43:27', '2026-08-03 17:43:27'),
(65, 252, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-08-09 16:22:16', '2026-08-09 16:37:15', '[]', '2026-08-09 16:37:18', '2026-08-09 16:37:18'),
(66, 254, 15, 15, 100.00, 'completo', 'video_15', 'Vídeo 15 - Exames Disponíveis', '2026-08-14 23:26:36', '2026-08-14 23:46:48', '[]', '2026-08-14 23:46:51', '2026-08-14 23:46:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente_video_respostas`
--

CREATE TABLE `paciente_video_respostas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `video_id` varchar(50) NOT NULL COMMENT 'ID do vídeo (ex: video_1, video_2)',
  `video_title` varchar(255) DEFAULT NULL COMMENT 'Título do vídeo',
  `video_ordem` int(11) DEFAULT NULL COMMENT 'Ordem do vídeo na sequência (1-15)',
  `question_id` varchar(50) DEFAULT NULL COMMENT 'ID da pergunta (ex: v1_q1, v2_q1)',
  `question_index` int(11) NOT NULL COMMENT 'Índice da pergunta dentro do vídeo (começa em 1)',
  `question_text` text NOT NULL COMMENT 'Texto completo da pergunta',
  `question_title` varchar(255) DEFAULT NULL COMMENT 'Título da pergunta (ex: Pergunta 1 de 4)',
  `answer` text NOT NULL COMMENT 'Resposta do paciente (pode ser texto, Sim/Não, ou JSON para múltiplas escolhas)',
  `answer_type` varchar(20) NOT NULL DEFAULT 'boolean' COMMENT 'Tipo de resposta: boolean, text, choice, checkbox',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Data/hora em que a resposta foi registrada',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Data/hora da última atualização',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP do paciente ao responder',
  `user_agent` text DEFAULT NULL COMMENT 'Navegador/dispositivo usado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Respostas dos pacientes aos vídeos da entrevista pré-anestésica';

--
-- Despejando dados para a tabela `paciente_video_respostas`
--

INSERT INTO `paciente_video_respostas` (`id`, `paciente_id`, `video_id`, `video_title`, `video_ordem`, `question_id`, `question_index`, `question_text`, `question_title`, `answer`, `answer_type`, `created_at`, `updated_at`, `ip_address`, `user_agent`) VALUES
(350, 140, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2025-11-27 19:09:07', '2025-11-27 19:09:07', NULL, NULL),
(351, 140, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2025-11-27 19:09:55', '2025-11-27 19:09:55', NULL, NULL),
(352, 140, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não tive', 'text', '2025-11-27 19:10:12', '2025-11-27 19:10:12', NULL, NULL),
(353, 140, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2025-11-27 19:10:40', '2025-11-27 19:10:40', NULL, NULL),
(354, 140, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2025-11-27 19:10:54', '2025-11-27 19:10:54', NULL, NULL),
(355, 140, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2025-11-27 19:10:55', '2025-11-27 19:10:55', NULL, NULL),
(356, 140, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2025-11-27 19:10:59', '2025-11-27 19:10:59', NULL, NULL),
(357, 140, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2025-11-27 19:11:25', '2025-11-27 19:11:25', NULL, NULL),
(358, 140, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2025-11-27 19:11:27', '2025-11-27 19:11:27', NULL, NULL),
(359, 140, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2025-11-27 19:11:30', '2025-11-27 19:11:30', NULL, NULL),
(360, 140, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2025-11-27 19:11:33', '2025-11-27 19:11:33', NULL, NULL),
(361, 140, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2025-11-27 19:12:08', '2025-11-27 19:12:08', NULL, NULL),
(362, 140, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2025-11-27 19:12:10', '2025-11-27 19:12:10', NULL, NULL),
(363, 140, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2025-11-27 19:12:12', '2025-11-27 19:12:12', NULL, NULL),
(364, 140, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2025-11-27 19:12:14', '2025-11-27 19:12:14', NULL, NULL),
(365, 140, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Não tenho', 'text', '2025-11-27 19:12:45', '2025-11-27 19:12:45', NULL, NULL),
(366, 140, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2025-11-27 19:13:04', '2025-11-27 19:13:04', NULL, NULL),
(367, 140, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2025-11-27 19:13:06', '2025-11-27 19:13:06', NULL, NULL),
(368, 140, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2025-11-27 19:13:08', '2025-11-27 19:13:08', NULL, NULL),
(369, 140, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2025-11-27 19:13:17', '2025-11-27 19:13:17', NULL, NULL),
(370, 140, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2025-11-27 19:13:41', '2025-11-27 19:13:41', NULL, NULL),
(371, 140, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2025-11-27 19:13:42', '2025-11-27 19:13:42', NULL, NULL),
(372, 140, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2025-11-27 19:13:58', '2025-11-27 19:13:58', NULL, NULL),
(373, 140, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2025-11-27 19:14:00', '2025-11-27 19:14:00', NULL, NULL),
(374, 140, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2025-11-27 19:14:04', '2025-11-27 19:14:04', NULL, NULL),
(375, 140, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2025-11-27 19:14:32', '2025-11-27 19:14:32', NULL, NULL),
(376, 140, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2025-11-27 19:14:34', '2025-11-27 19:14:34', NULL, NULL),
(377, 140, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2025-11-27 19:14:56', '2025-11-27 19:14:56', NULL, NULL),
(378, 140, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2025-11-27 19:14:59', '2025-11-27 19:14:59', NULL, NULL),
(379, 140, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Naprix', 'text', '2025-11-27 19:15:27', '2025-11-27 19:15:27', NULL, NULL),
(380, 140, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2025-11-27 19:15:29', '2025-11-27 19:15:29', NULL, NULL),
(381, 140, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2025-11-27 19:16:04', '2025-11-27 19:16:04', NULL, NULL),
(382, 140, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2025-11-27 19:16:08', '2025-11-27 19:16:08', NULL, NULL),
(383, 140, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2025-11-27 19:16:12', '2025-11-27 19:16:12', NULL, NULL),
(384, 140, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2025-11-27 19:16:38', '2025-11-27 19:16:38', NULL, NULL),
(385, 140, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2025-11-27 19:16:42', '2025-11-27 19:16:42', NULL, NULL),
(386, 140, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2025-11-27 19:17:09', '2025-11-27 19:17:09', NULL, NULL),
(387, 140, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Glicemia de Jejum\",\"Creatinina\"]', 'checkbox', '2025-11-27 19:18:26', '2025-11-27 19:18:26', NULL, NULL),
(388, 143, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2025-11-28 19:46:22', '2025-11-28 19:46:22', NULL, NULL),
(389, 143, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2025-11-28 19:48:32', '2025-11-28 19:48:32', NULL, NULL),
(390, 143, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'muita dor nas articulações', 'text', '2025-11-28 19:48:49', '2025-11-28 19:48:49', NULL, NULL),
(391, 143, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2025-11-28 19:49:19', '2025-11-28 19:49:19', NULL, NULL),
(392, 143, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2025-11-28 19:49:19', '2025-11-28 19:49:19', NULL, NULL),
(393, 143, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2025-11-28 19:49:19', '2025-11-28 19:49:19', NULL, NULL),
(394, 143, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2025-11-28 19:49:20', '2025-11-28 19:49:20', NULL, NULL),
(395, 143, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2025-11-28 19:49:45', '2025-11-28 19:49:45', NULL, NULL),
(396, 143, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Sim', 'boolean', '2025-11-28 19:49:46', '2025-11-28 19:49:46', NULL, NULL),
(397, 143, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Sim', 'boolean', '2025-11-28 19:49:48', '2025-11-28 19:49:48', NULL, NULL),
(398, 143, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Sim', 'boolean', '2025-11-28 19:49:50', '2025-11-28 19:49:50', NULL, NULL),
(399, 143, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Sim', 'boolean', '2025-11-28 19:51:55', '2025-11-28 19:51:55', NULL, NULL),
(400, 143, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Sim', 'boolean', '2025-11-28 19:51:57', '2025-11-28 19:51:57', NULL, NULL),
(401, 143, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Sim', 'boolean', '2025-11-28 19:51:58', '2025-11-28 19:51:58', NULL, NULL),
(402, 143, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Sim', 'boolean', '2025-11-28 19:51:58', '2025-11-28 19:51:58', NULL, NULL),
(403, 143, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'caganeira', 'text', '2025-11-28 19:52:38', '2025-11-28 19:52:38', NULL, NULL),
(404, 143, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Sim', 'boolean', '2025-11-28 20:04:43', '2025-11-28 20:04:43', NULL, NULL),
(405, 143, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2025-11-28 20:04:44', '2025-11-28 20:04:44', NULL, NULL),
(406, 143, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Sim', 'boolean', '2025-11-28 20:04:44', '2025-11-28 20:04:44', NULL, NULL),
(407, 143, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2025-11-28 20:04:46', '2025-11-28 20:04:46', NULL, NULL),
(408, 143, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Sim', 'boolean', '2025-11-28 20:07:42', '2025-11-28 20:07:42', NULL, NULL),
(409, 143, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Sim', 'boolean', '2025-11-28 20:07:44', '2025-11-28 20:07:44', NULL, NULL),
(410, 143, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2025-11-28 20:08:14', '2025-11-28 20:08:14', NULL, NULL),
(411, 143, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Sim', 'boolean', '2025-11-28 20:08:15', '2025-11-28 20:08:15', NULL, NULL),
(412, 143, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2025-11-28 20:08:15', '2025-11-28 20:08:15', NULL, NULL),
(413, 143, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Sim', 'boolean', '2025-11-28 20:10:41', '2025-11-28 20:10:41', NULL, NULL),
(414, 143, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2025-11-28 20:10:41', '2025-11-28 20:10:41', NULL, NULL),
(415, 143, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'amendoim', 'text', '2025-11-28 20:10:48', '2025-11-28 20:10:48', NULL, NULL),
(416, 143, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2025-11-28 20:11:09', '2025-11-28 20:11:09', NULL, NULL),
(417, 143, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2025-11-28 20:11:11', '2025-11-28 20:11:11', NULL, NULL),
(418, 143, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Sim', 'boolean', '2025-11-28 20:11:13', '2025-11-28 20:11:13', NULL, NULL),
(419, 143, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2025-11-28 20:13:09', '2025-11-28 20:13:09', NULL, NULL),
(420, 143, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2025-11-28 20:13:11', '2025-11-28 20:13:11', NULL, NULL),
(421, 143, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '50kg', 'text', '2025-11-28 20:13:17', '2025-11-28 20:13:17', NULL, NULL),
(422, 143, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2025-11-28 20:13:19', '2025-11-28 20:13:19', NULL, NULL),
(423, 143, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2025-11-28 20:13:44', '2025-11-28 20:13:44', NULL, NULL),
(424, 143, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2025-11-28 20:13:48', '2025-11-28 20:13:48', NULL, NULL),
(425, 143, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2025-11-28 20:14:25', '2025-11-28 20:14:25', NULL, NULL),
(426, 143, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Nenhum\"]', 'checkbox', '2025-11-28 20:15:09', '2025-11-28 20:15:09', NULL, NULL),
(427, 146, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2025-12-02 14:15:51', '2025-12-02 14:15:51', NULL, NULL),
(428, 146, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2025-12-02 14:16:24', '2025-12-02 14:16:24', NULL, NULL),
(429, 146, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Endo', 'text', '2025-12-02 14:16:29', '2025-12-02 14:16:29', NULL, NULL),
(430, 146, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:02', '2025-12-02 14:17:02', NULL, NULL),
(431, 146, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:03', '2025-12-02 14:17:03', NULL, NULL),
(432, 146, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:03', '2025-12-02 14:17:03', NULL, NULL),
(433, 146, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:04', '2025-12-02 14:17:04', NULL, NULL),
(434, 146, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:53', '2025-12-02 14:17:53', NULL, NULL),
(435, 146, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:53', '2025-12-02 14:17:53', NULL, NULL),
(436, 146, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:54', '2025-12-02 14:17:54', NULL, NULL),
(437, 146, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Sim', 'boolean', '2025-12-02 14:17:54', '2025-12-02 14:17:54', NULL, NULL),
(438, 155, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2025-12-15 13:09:15', '2025-12-15 13:09:15', NULL, NULL),
(439, 155, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2025-12-15 13:10:14', '2025-12-15 13:10:14', NULL, NULL),
(440, 155, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Única cirurgia que eu realizei foi retirada de miomas ano passado. Não tive qualquer problema', 'text', '2025-12-15 13:10:57', '2025-12-15 13:10:57', NULL, NULL),
(441, 155, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2025-12-15 13:11:25', '2025-12-15 13:11:25', NULL, NULL),
(442, 155, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2025-12-15 13:11:28', '2025-12-15 13:11:28', NULL, NULL),
(443, 155, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2025-12-15 13:11:30', '2025-12-15 13:11:30', NULL, NULL),
(444, 155, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2025-12-15 13:11:32', '2025-12-15 13:11:32', NULL, NULL),
(445, 155, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2025-12-15 13:11:55', '2025-12-15 13:11:55', NULL, NULL),
(446, 155, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2025-12-15 13:11:57', '2025-12-15 13:11:57', NULL, NULL),
(447, 155, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2025-12-15 13:12:00', '2025-12-15 13:12:00', NULL, NULL),
(448, 155, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2025-12-15 13:12:01', '2025-12-15 13:12:01', NULL, NULL),
(449, 155, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2025-12-15 13:12:35', '2025-12-15 13:12:35', NULL, NULL),
(450, 155, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2025-12-15 13:12:36', '2025-12-15 13:12:36', NULL, NULL),
(451, 155, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2025-12-15 13:12:38', '2025-12-15 13:12:38', NULL, NULL),
(452, 155, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2025-12-15 13:12:39', '2025-12-15 13:12:39', NULL, NULL),
(453, 155, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Não tenho nenhuma doença e não faço uso de nenhum medicamento', 'text', '2025-12-15 13:13:18', '2025-12-15 13:13:18', NULL, NULL),
(454, 155, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2025-12-15 13:13:36', '2025-12-15 13:13:36', NULL, NULL),
(455, 155, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2025-12-15 13:13:37', '2025-12-15 13:13:37', NULL, NULL),
(456, 155, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2025-12-15 13:13:39', '2025-12-15 13:13:39', NULL, NULL),
(457, 155, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2025-12-15 13:13:41', '2025-12-15 13:13:41', NULL, NULL),
(458, 155, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2025-12-15 13:14:02', '2025-12-15 13:14:02', NULL, NULL),
(459, 155, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2025-12-15 13:14:05', '2025-12-15 13:14:05', NULL, NULL),
(460, 155, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2025-12-15 13:14:20', '2025-12-15 13:14:20', NULL, NULL),
(461, 155, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2025-12-15 13:14:21', '2025-12-15 13:14:21', NULL, NULL),
(462, 155, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2025-12-15 13:14:24', '2025-12-15 13:14:24', NULL, NULL),
(463, 155, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2025-12-15 13:14:51', '2025-12-15 13:14:51', NULL, NULL),
(464, 155, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2025-12-15 13:14:53', '2025-12-15 13:14:53', NULL, NULL),
(465, 155, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2025-12-15 13:15:25', '2025-12-15 13:15:25', NULL, NULL),
(466, 155, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2025-12-15 13:15:27', '2025-12-15 13:15:27', NULL, NULL),
(467, 155, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2025-12-15 13:15:29', '2025-12-15 13:15:29', NULL, NULL),
(468, 155, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2025-12-15 13:16:06', '2025-12-15 13:16:06', NULL, NULL),
(469, 155, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2025-12-15 13:16:08', '2025-12-15 13:16:08', NULL, NULL),
(470, 155, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2025-12-15 13:16:09', '2025-12-15 13:16:09', NULL, NULL),
(471, 155, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2025-12-15 13:16:35', '2025-12-15 13:16:35', NULL, NULL),
(472, 155, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2025-12-15 13:16:38', '2025-12-15 13:16:38', NULL, NULL),
(473, 155, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2025-12-15 13:17:23', '2025-12-15 13:17:23', NULL, NULL),
(474, 155, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Eletrocardiograma\"]', 'checkbox', '2025-12-15 13:20:43', '2025-12-15 13:20:43', NULL, NULL),
(475, 157, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2025-12-16 17:29:28', '2025-12-16 17:29:28', NULL, NULL),
(476, 157, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2025-12-16 17:30:00', '2025-12-16 17:30:00', NULL, NULL),
(477, 157, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2025-12-16 17:30:25', '2025-12-16 17:30:25', NULL, NULL),
(478, 157, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2025-12-16 17:30:27', '2025-12-16 17:30:27', NULL, NULL),
(479, 157, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2025-12-16 17:30:28', '2025-12-16 17:30:28', NULL, NULL),
(480, 157, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2025-12-16 17:30:31', '2025-12-16 17:30:31', NULL, NULL),
(481, 157, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2025-12-16 17:31:04', '2025-12-16 17:31:04', NULL, NULL),
(482, 157, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2025-12-16 17:31:06', '2025-12-16 17:31:06', NULL, NULL),
(483, 157, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Sim', 'boolean', '2025-12-16 17:31:21', '2025-12-16 17:31:21', NULL, NULL),
(484, 157, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2025-12-16 17:31:23', '2025-12-16 17:31:23', NULL, NULL),
(485, 157, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2025-12-16 17:31:58', '2025-12-16 17:31:58', NULL, NULL),
(486, 157, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2025-12-16 17:32:15', '2025-12-16 17:32:15', NULL, NULL),
(487, 157, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2025-12-16 17:32:20', '2025-12-16 17:32:20', NULL, NULL),
(488, 157, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2025-12-16 17:32:28', '2025-12-16 17:32:28', NULL, NULL),
(489, 157, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Doença não tenho', 'text', '2025-12-16 17:33:22', '2025-12-16 17:33:22', NULL, NULL),
(490, 157, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2025-12-16 17:33:42', '2025-12-16 17:33:42', NULL, NULL),
(491, 157, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2025-12-16 17:33:44', '2025-12-16 17:33:44', NULL, NULL),
(492, 157, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2025-12-16 17:33:45', '2025-12-16 17:33:45', NULL, NULL),
(493, 157, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2025-12-16 17:33:52', '2025-12-16 17:33:52', NULL, NULL),
(494, 157, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2025-12-16 17:34:13', '2025-12-16 17:34:13', NULL, NULL),
(495, 157, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2025-12-16 17:34:15', '2025-12-16 17:34:15', NULL, NULL),
(496, 157, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2025-12-16 17:34:31', '2025-12-16 17:34:31', NULL, NULL),
(497, 157, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2025-12-16 17:34:33', '2025-12-16 17:34:33', NULL, NULL),
(498, 157, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2025-12-16 17:35:00', '2025-12-16 17:35:00', NULL, NULL),
(499, 157, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2025-12-16 17:35:29', '2025-12-16 17:35:29', NULL, NULL),
(500, 157, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2025-12-16 17:35:30', '2025-12-16 17:35:30', NULL, NULL),
(501, 157, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2025-12-16 17:35:50', '2025-12-16 17:35:50', NULL, NULL),
(502, 157, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2025-12-16 17:35:53', '2025-12-16 17:35:53', NULL, NULL),
(503, 157, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2025-12-16 17:35:56', '2025-12-16 17:35:56', NULL, NULL),
(504, 157, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2025-12-16 17:36:27', '2025-12-16 17:36:27', NULL, NULL),
(505, 157, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2025-12-16 17:36:30', '2025-12-16 17:36:30', NULL, NULL),
(506, 157, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2025-12-16 17:36:34', '2025-12-16 17:36:34', NULL, NULL),
(507, 157, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2025-12-16 17:37:00', '2025-12-16 17:37:00', NULL, NULL),
(508, 157, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2025-12-16 17:37:06', '2025-12-16 17:37:06', NULL, NULL),
(509, 157, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2025-12-16 17:37:54', '2025-12-16 17:37:54', NULL, NULL),
(510, 157, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Eletrocardiograma\",\"KPTT\",\"Creatinina\",\"Hemograma\",\"Tempo de Protrombina\"]', 'checkbox', '2025-12-16 17:41:30', '2025-12-16 17:41:30', NULL, NULL),
(550, 176, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2025-12-18 16:52:44', '2025-12-18 16:52:44', NULL, NULL),
(551, 176, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2025-12-18 16:53:15', '2025-12-18 16:53:15', NULL, NULL),
(552, 176, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Ok', 'text', '2025-12-18 16:53:20', '2025-12-18 16:53:20', NULL, NULL),
(553, 176, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2025-12-18 16:53:43', '2025-12-18 16:53:43', NULL, NULL),
(554, 176, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Sim', 'boolean', '2025-12-18 16:53:44', '2025-12-18 16:53:44', NULL, NULL),
(555, 176, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Sim', 'boolean', '2025-12-18 16:53:45', '2025-12-18 16:53:45', NULL, NULL),
(556, 176, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Sim', 'boolean', '2025-12-18 16:53:45', '2025-12-18 16:53:45', NULL, NULL),
(557, 176, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2025-12-18 16:54:36', '2025-12-18 16:54:36', NULL, NULL),
(558, 176, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Sim', 'boolean', '2025-12-18 16:54:37', '2025-12-18 16:54:37', NULL, NULL),
(559, 176, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Sim', 'boolean', '2025-12-18 16:54:39', '2025-12-18 16:54:39', NULL, NULL),
(560, 176, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Sim', 'boolean', '2025-12-18 16:54:39', '2025-12-18 16:54:39', NULL, NULL),
(561, 176, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Sim', 'boolean', '2025-12-18 16:56:38', '2025-12-18 16:56:38', NULL, NULL),
(562, 176, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Sim', 'boolean', '2025-12-18 16:56:38', '2025-12-18 16:56:38', NULL, NULL),
(563, 176, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Sim', 'boolean', '2025-12-18 16:56:39', '2025-12-18 16:56:39', NULL, NULL),
(564, 176, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'K', 'text', '2025-12-18 16:57:00', '2025-12-18 16:57:00', NULL, NULL),
(565, 176, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Sim', 'boolean', '2025-12-18 16:57:22', '2025-12-18 16:57:22', NULL, NULL),
(566, 176, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2025-12-18 16:57:22', '2025-12-18 16:57:22', NULL, NULL),
(567, 176, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Sim', 'boolean', '2025-12-18 16:57:23', '2025-12-18 16:57:23', NULL, NULL),
(568, 176, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Sim', 'boolean', '2025-12-18 16:57:23', '2025-12-18 16:57:23', NULL, NULL),
(569, 176, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Sim', 'boolean', '2025-12-18 16:57:43', '2025-12-18 16:57:43', NULL, NULL),
(570, 176, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Sim', 'boolean', '2025-12-18 16:57:44', '2025-12-18 16:57:44', NULL, NULL),
(571, 176, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2025-12-18 16:58:06', '2025-12-18 16:58:06', NULL, NULL),
(572, 176, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Sim', 'boolean', '2025-12-18 16:58:06', '2025-12-18 16:58:06', NULL, NULL),
(573, 176, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2025-12-18 16:58:07', '2025-12-18 16:58:07', NULL, NULL),
(581, 185, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-01-07 12:19:35', '2026-01-07 12:19:35', NULL, NULL),
(582, 185, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-01-07 12:20:35', '2026-01-07 12:20:35', NULL, NULL),
(583, 185, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Nenhum problema', 'text', '2026-01-07 12:20:59', '2026-01-07 12:20:59', NULL, NULL),
(584, 185, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-01-07 12:21:25', '2026-01-07 12:21:25', NULL, NULL),
(585, 185, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-01-07 12:21:28', '2026-01-07 12:21:28', NULL, NULL),
(586, 185, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-01-07 12:21:30', '2026-01-07 12:21:30', NULL, NULL),
(587, 185, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-01-07 12:21:33', '2026-01-07 12:21:33', NULL, NULL),
(588, 186, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-01-07 12:21:39', '2026-01-07 12:21:39', NULL, NULL),
(589, 185, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-01-07 12:21:57', '2026-01-07 12:21:57', NULL, NULL),
(590, 185, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-01-07 12:22:02', '2026-01-07 12:22:02', NULL, NULL),
(591, 185, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-01-07 12:22:05', '2026-01-07 12:22:05', NULL, NULL),
(592, 185, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-01-07 12:22:07', '2026-01-07 12:22:07', NULL, NULL),
(593, 186, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-01-07 12:22:13', '2026-01-07 12:22:13', NULL, NULL),
(594, 186, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'sangramento', 'text', '2026-01-07 12:22:28', '2026-01-07 12:22:28', NULL, NULL),
(595, 185, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-01-07 12:22:42', '2026-01-07 12:22:42', NULL, NULL),
(596, 185, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-01-07 12:22:44', '2026-01-07 12:22:44', NULL, NULL),
(597, 185, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-01-07 12:22:47', '2026-01-07 12:22:47', NULL, NULL),
(598, 185, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-01-07 12:22:49', '2026-01-07 12:22:49', NULL, NULL),
(599, 186, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-01-07 12:22:53', '2026-01-07 12:22:53', NULL, NULL),
(600, 186, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Sim', 'boolean', '2026-01-07 12:22:55', '2026-01-07 12:22:55', NULL, NULL),
(601, 186, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-01-07 12:22:57', '2026-01-07 12:22:57', NULL, NULL),
(602, 186, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-01-07 12:22:59', '2026-01-07 12:22:59', NULL, NULL),
(603, 186, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2026-01-07 12:23:22', '2026-01-07 12:23:22', NULL, NULL),
(604, 186, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-01-07 12:23:25', '2026-01-07 12:23:25', NULL, NULL),
(605, 186, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-01-07 12:23:29', '2026-01-07 12:23:29', NULL, NULL),
(606, 186, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-01-07 12:23:31', '2026-01-07 12:23:31', NULL, NULL),
(607, 186, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Sim', 'boolean', '2026-01-07 12:24:06', '2026-01-07 12:24:06', NULL, NULL),
(608, 186, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-01-07 12:24:29', '2026-01-07 12:24:29', NULL, NULL),
(609, 186, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-01-07 12:24:33', '2026-01-07 12:24:33', NULL, NULL),
(610, 186, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-01-07 12:24:37', '2026-01-07 12:24:37', NULL, NULL),
(611, 186, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'sp', 'text', '2026-01-07 12:25:19', '2026-01-07 12:25:19', NULL, NULL),
(612, 186, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-01-07 12:25:37', '2026-01-07 12:25:37', NULL, NULL),
(613, 186, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-01-07 12:25:39', '2026-01-07 12:25:39', NULL, NULL),
(614, 186, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Sim', 'boolean', '2026-01-07 12:25:41', '2026-01-07 12:25:41', NULL, NULL),
(615, 186, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-01-07 12:25:44', '2026-01-07 12:25:44', NULL, NULL),
(616, 186, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Sim', 'boolean', '2026-01-07 12:26:09', '2026-01-07 12:26:09', NULL, NULL),
(617, 186, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-01-07 12:26:13', '2026-01-07 12:26:13', NULL, NULL),
(618, 186, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-01-07 12:26:29', '2026-01-07 12:26:29', NULL, NULL),
(619, 186, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-01-07 12:26:31', '2026-01-07 12:26:31', NULL, NULL),
(620, 186, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-01-07 12:26:35', '2026-01-07 12:26:35', NULL, NULL),
(621, 186, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-01-07 12:27:04', '2026-01-07 12:27:04', NULL, NULL),
(622, 186, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2026-01-07 12:27:06', '2026-01-07 12:27:06', NULL, NULL),
(623, 186, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'camarao', 'text', '2026-01-07 12:27:18', '2026-01-07 12:27:18', NULL, NULL),
(624, 185, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Sou celíaca, intolerante a lactose, gastrite, tive um tumor de duodeno (adenocarcinoma) em fevereiro de 2025 com mestastase nos linfonodos depois de 3 meses da cirurgia.', 'text', '2026-01-07 12:27:30', '2026-01-07 12:27:30', NULL, NULL),
(625, 186, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-01-07 12:27:44', '2026-01-07 12:27:44', NULL, NULL),
(626, 186, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-01-07 12:27:47', '2026-01-07 12:27:47', NULL, NULL),
(627, 185, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-01-07 12:27:48', '2026-01-07 12:27:48', NULL, NULL),
(628, 185, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-01-07 12:27:50', '2026-01-07 12:27:50', NULL, NULL),
(629, 185, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Sim', 'boolean', '2026-01-07 12:27:55', '2026-01-07 12:27:55', NULL, NULL),
(630, 186, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'enalapril', 'text', '2026-01-07 12:28:00', '2026-01-07 12:28:00', NULL, NULL);
INSERT INTO `paciente_video_respostas` (`id`, `paciente_id`, `video_id`, `video_title`, `video_ordem`, `question_id`, `question_index`, `question_text`, `question_title`, `answer`, `answer_type`, `created_at`, `updated_at`, `ip_address`, `user_agent`) VALUES
(631, 185, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-01-07 12:28:03', '2026-01-07 12:28:03', NULL, NULL),
(632, 186, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Sim', 'boolean', '2026-01-07 12:28:06', '2026-01-07 12:28:06', NULL, NULL),
(633, 185, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-01-07 12:28:25', '2026-01-07 12:28:25', NULL, NULL),
(634, 185, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-01-07 12:28:27', '2026-01-07 12:28:27', NULL, NULL),
(635, 185, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-01-07 12:28:42', '2026-01-07 12:28:42', NULL, NULL),
(636, 185, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-01-07 12:28:44', '2026-01-07 12:28:44', NULL, NULL),
(637, 185, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-01-07 12:28:53', '2026-01-07 12:28:53', NULL, NULL),
(638, 186, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-01-07 12:28:59', '2026-01-07 12:28:59', NULL, NULL),
(639, 186, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-01-07 12:29:02', '2026-01-07 12:29:02', NULL, NULL),
(640, 186, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2026-01-07 12:29:04', '2026-01-07 12:29:04', NULL, NULL),
(641, 185, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-01-07 12:29:24', '2026-01-07 12:29:24', NULL, NULL),
(642, 185, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-01-07 12:29:28', '2026-01-07 12:29:28', NULL, NULL),
(643, 185, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-01-07 12:29:49', '2026-01-07 12:29:49', NULL, NULL),
(644, 186, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Sim', 'boolean', '2026-01-07 12:29:51', '2026-01-07 12:29:51', NULL, NULL),
(645, 185, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2026-01-07 12:29:53', '2026-01-07 12:29:53', NULL, NULL),
(646, 185, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-01-07 12:29:58', '2026-01-07 12:29:58', NULL, NULL),
(647, 185, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-01-07 12:30:32', '2026-01-07 12:30:32', NULL, NULL),
(648, 185, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-01-07 12:30:40', '2026-01-07 12:30:40', NULL, NULL),
(649, 185, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-01-07 12:30:43', '2026-01-07 12:30:43', NULL, NULL),
(650, 186, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Não', 'boolean', '2026-01-07 12:30:59', '2026-01-07 12:30:59', NULL, NULL),
(651, 185, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-01-07 12:31:08', '2026-01-07 12:31:08', NULL, NULL),
(652, 185, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-01-07 12:31:18', '2026-01-07 12:31:18', NULL, NULL),
(653, 186, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-01-07 12:31:26', '2026-01-07 12:31:26', NULL, NULL),
(654, 185, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-01-07 12:32:13', '2026-01-07 12:32:13', NULL, NULL),
(655, 186, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"KPTT\",\"Glicemia de Jejum\",\"Eletrocardiograma\"]', 'checkbox', '2026-01-07 12:32:20', '2026-01-07 12:32:20', NULL, NULL),
(656, 185, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\"]', 'checkbox', '2026-01-07 12:33:21', '2026-01-07 12:33:21', NULL, NULL),
(657, 188, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-01-13 22:53:19', '2026-01-13 22:53:19', NULL, NULL),
(658, 188, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-01-13 22:53:54', '2026-01-13 22:53:54', NULL, NULL),
(659, 188, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Cesariana, duas. Nenhum problema', 'text', '2026-01-13 22:54:09', '2026-01-13 22:54:09', NULL, NULL),
(660, 188, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-01-13 22:54:33', '2026-01-13 22:54:33', NULL, NULL),
(661, 188, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-01-13 22:54:36', '2026-01-13 22:54:36', NULL, NULL),
(662, 188, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-01-13 22:54:37', '2026-01-13 22:54:37', NULL, NULL),
(663, 188, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-01-13 22:54:41', '2026-01-13 22:54:41', NULL, NULL),
(664, 188, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-01-13 22:55:08', '2026-01-13 22:55:08', NULL, NULL),
(665, 188, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-01-13 22:55:10', '2026-01-13 22:55:10', NULL, NULL),
(666, 188, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-01-13 22:55:12', '2026-01-13 22:55:12', NULL, NULL),
(667, 188, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-01-13 22:55:14', '2026-01-13 22:55:14', NULL, NULL),
(668, 188, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-01-13 22:55:48', '2026-01-13 22:55:48', NULL, NULL),
(669, 188, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-01-13 22:55:50', '2026-01-13 22:55:50', NULL, NULL),
(670, 188, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-01-13 22:55:55', '2026-01-13 22:55:55', NULL, NULL),
(671, 188, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-01-13 22:55:58', '2026-01-13 22:55:58', NULL, NULL),
(672, 188, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Tenho fibromialgia', 'text', '2026-01-13 22:56:29', '2026-01-13 22:56:29', NULL, NULL),
(673, 188, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-01-13 22:56:53', '2026-01-13 22:56:53', NULL, NULL),
(674, 188, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-01-13 22:56:54', '2026-01-13 22:56:54', NULL, NULL),
(675, 188, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-01-13 22:56:56', '2026-01-13 22:56:56', NULL, NULL),
(676, 188, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-01-13 22:56:59', '2026-01-13 22:56:59', NULL, NULL),
(677, 188, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-01-13 22:57:18', '2026-01-13 22:57:18', NULL, NULL),
(678, 188, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-01-13 22:57:19', '2026-01-13 22:57:19', NULL, NULL),
(679, 188, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-01-13 22:57:35', '2026-01-13 22:57:35', NULL, NULL),
(680, 188, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-01-13 22:57:37', '2026-01-13 22:57:37', NULL, NULL),
(681, 188, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-01-13 22:57:40', '2026-01-13 22:57:40', NULL, NULL),
(682, 188, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-01-13 22:58:15', '2026-01-13 22:58:15', NULL, NULL),
(683, 188, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-01-13 22:58:17', '2026-01-13 22:58:17', NULL, NULL),
(684, 188, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-01-13 22:59:11', '2026-01-13 22:59:11', NULL, NULL),
(685, 188, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-01-13 22:59:14', '2026-01-13 22:59:14', NULL, NULL),
(686, 188, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Naltrexona 4mg', 'text', '2026-01-13 22:59:26', '2026-01-13 22:59:26', NULL, NULL),
(687, 188, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-01-13 22:59:44', '2026-01-13 22:59:44', NULL, NULL),
(688, 188, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-01-13 23:00:24', '2026-01-13 23:00:24', NULL, NULL),
(689, 188, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-01-13 23:00:30', '2026-01-13 23:00:30', NULL, NULL),
(690, 188, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-01-13 23:00:34', '2026-01-13 23:00:34', NULL, NULL),
(691, 188, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-01-13 23:01:02', '2026-01-13 23:01:02', NULL, NULL),
(692, 188, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-01-13 23:01:19', '2026-01-13 23:01:19', NULL, NULL),
(693, 188, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-01-13 23:02:20', '2026-01-13 23:02:20', NULL, NULL),
(694, 188, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Tempo de Protrombina\",\"Creatinina\",\"Eletrocardiograma\",\"Glicemia de Jejum\",\"KPTT\"]', 'checkbox', '2026-01-13 23:03:32', '2026-01-13 23:03:32', NULL, NULL),
(695, 187, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-01-14 16:45:35', '2026-01-14 16:45:35', NULL, NULL),
(696, 187, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-01-14 16:46:20', '2026-01-14 16:46:20', NULL, NULL),
(697, 187, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não houve problema', 'text', '2026-01-14 16:46:55', '2026-01-14 16:46:55', NULL, NULL),
(698, 187, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-01-14 16:47:24', '2026-01-14 16:47:24', NULL, NULL),
(699, 187, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-01-14 16:47:44', '2026-01-14 16:47:44', NULL, NULL),
(700, 187, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-01-14 16:47:48', '2026-01-14 16:47:48', NULL, NULL),
(701, 187, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-01-14 16:47:52', '2026-01-14 16:47:52', NULL, NULL),
(702, 187, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-01-14 16:48:20', '2026-01-14 16:48:20', NULL, NULL),
(703, 187, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-01-14 16:48:24', '2026-01-14 16:48:24', NULL, NULL),
(704, 187, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-01-14 16:48:29', '2026-01-14 16:48:29', NULL, NULL),
(705, 187, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-01-14 16:48:32', '2026-01-14 16:48:32', NULL, NULL),
(706, 187, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-01-14 16:49:11', '2026-01-14 16:49:11', NULL, NULL),
(707, 187, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Sim', 'boolean', '2026-01-14 16:49:26', '2026-01-14 16:49:26', NULL, NULL),
(708, 187, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-01-14 16:49:29', '2026-01-14 16:49:29', NULL, NULL),
(709, 187, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-01-14 16:49:33', '2026-01-14 16:49:33', NULL, NULL),
(710, 187, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Não tenho outras doenças.\r\nContudo, ressalto que eu tinha doença de graves e precisei fazer uma tireoidectomia em 10 de fevereiro de 2024 uma vez que estava causando aceleração do meus batimentos cardíacos onde precisei fazer uso do medicamento ABLOK 25mg o qual ainda estou fazendo uso. Também faço uso do medicamento HEUTHYROX 100mcg após a tireoidectomia.', 'text', '2026-01-14 17:03:02', '2026-01-14 17:03:02', NULL, NULL),
(711, 187, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-01-14 17:03:24', '2026-01-14 17:03:24', NULL, NULL),
(712, 187, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-01-14 17:03:30', '2026-01-14 17:03:30', NULL, NULL),
(713, 187, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-01-14 17:03:35', '2026-01-14 17:03:35', NULL, NULL),
(714, 187, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-01-14 17:03:48', '2026-01-14 17:03:48', NULL, NULL),
(715, 187, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-01-14 17:04:13', '2026-01-14 17:04:13', NULL, NULL),
(716, 187, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-01-14 17:04:17', '2026-01-14 17:04:17', NULL, NULL),
(717, 187, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-01-14 17:04:34', '2026-01-14 17:04:34', NULL, NULL),
(718, 187, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-01-14 17:04:37', '2026-01-14 17:04:37', NULL, NULL),
(719, 187, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-01-14 17:04:42', '2026-01-14 17:04:42', NULL, NULL),
(720, 187, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-01-14 17:05:12', '2026-01-14 17:05:12', NULL, NULL),
(721, 187, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-01-14 17:05:15', '2026-01-14 17:05:15', NULL, NULL),
(722, 187, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-01-14 17:05:38', '2026-01-14 17:05:38', NULL, NULL),
(723, 187, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-01-14 17:05:44', '2026-01-14 17:05:44', NULL, NULL),
(724, 187, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'ABLOK ou ATENOLOU 25 mg\r\nEUTHYROX 100 mcg', 'text', '2026-01-14 17:08:03', '2026-01-14 17:08:03', NULL, NULL),
(725, 187, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-01-14 17:08:08', '2026-01-14 17:08:08', NULL, NULL),
(726, 187, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-01-14 17:08:47', '2026-01-14 17:08:47', NULL, NULL),
(727, 187, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-01-14 17:08:57', '2026-01-14 17:08:57', NULL, NULL),
(728, 187, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-01-14 17:09:02', '2026-01-14 17:09:02', NULL, NULL),
(729, 187, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-01-14 17:09:30', '2026-01-14 17:09:30', NULL, NULL),
(730, 187, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-01-14 17:09:40', '2026-01-14 17:09:40', NULL, NULL),
(731, 187, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-01-14 17:10:12', '2026-01-14 17:10:12', NULL, NULL),
(732, 187, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Glicemia de Jejum\",\"Eletrocardiograma\",\"KPTT\",\"Tempo de Protrombina\",\"Hemograma\",\"Creatinina\"]', 'checkbox', '2026-01-14 17:12:59', '2026-01-14 17:12:59', NULL, NULL),
(733, 194, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-02-09 22:45:19', '2026-02-09 22:45:19', NULL, NULL),
(734, 194, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-02-09 22:46:00', '2026-02-09 22:46:00', NULL, NULL),
(735, 194, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não', 'text', '2026-02-09 22:46:20', '2026-02-09 22:46:20', NULL, NULL),
(736, 194, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-02-09 22:46:44', '2026-02-09 22:46:44', NULL, NULL),
(737, 194, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Sim', 'boolean', '2026-02-09 22:46:52', '2026-02-09 22:46:52', NULL, NULL),
(738, 194, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-02-09 22:46:57', '2026-02-09 22:46:57', NULL, NULL),
(739, 194, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-02-09 22:47:01', '2026-02-09 22:47:01', NULL, NULL),
(740, 194, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2026-02-09 22:47:25', '2026-02-09 22:47:25', NULL, NULL),
(741, 194, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-02-09 22:47:29', '2026-02-09 22:47:29', NULL, NULL),
(742, 194, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-02-09 22:47:33', '2026-02-09 22:47:33', NULL, NULL),
(743, 194, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-02-09 22:47:35', '2026-02-09 22:47:35', NULL, NULL),
(744, 194, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-02-09 22:48:08', '2026-02-09 22:48:08', NULL, NULL),
(745, 194, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-02-09 22:48:13', '2026-02-09 22:48:13', NULL, NULL),
(746, 194, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-02-09 22:48:34', '2026-02-09 22:48:34', NULL, NULL),
(747, 194, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-02-09 22:48:34', '2026-02-09 22:48:34', NULL, NULL),
(748, 194, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Nada', 'text', '2026-02-09 22:48:51', '2026-02-09 22:48:51', NULL, NULL),
(749, 194, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-02-09 22:49:16', '2026-02-09 22:49:16', NULL, NULL),
(750, 194, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2026-02-09 22:49:20', '2026-02-09 22:49:20', NULL, NULL),
(751, 194, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-02-09 22:49:27', '2026-02-09 22:49:27', NULL, NULL),
(752, 194, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-02-09 22:49:32', '2026-02-09 22:49:32', NULL, NULL),
(753, 194, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-02-09 22:49:53', '2026-02-09 22:49:53', NULL, NULL),
(754, 194, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-02-09 22:49:57', '2026-02-09 22:49:57', NULL, NULL),
(755, 194, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-02-09 22:50:12', '2026-02-09 22:50:12', NULL, NULL),
(756, 194, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-02-09 22:50:14', '2026-02-09 22:50:14', NULL, NULL),
(757, 194, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-02-09 22:50:21', '2026-02-09 22:50:21', NULL, NULL),
(758, 194, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-02-09 22:50:47', '2026-02-09 22:50:47', NULL, NULL),
(759, 194, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-02-09 22:50:50', '2026-02-09 22:50:50', NULL, NULL),
(760, 194, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-02-09 22:51:10', '2026-02-09 22:51:10', NULL, NULL),
(761, 194, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-02-09 22:51:15', '2026-02-09 22:51:15', NULL, NULL),
(762, 194, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Losarta50 1cp manhã e noite \r\nSertralina50 1cp manhã \r\nAss', 'text', '2026-02-09 22:53:00', '2026-02-09 22:53:00', NULL, NULL),
(763, 194, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-02-09 22:53:08', '2026-02-09 22:53:08', NULL, NULL),
(764, 194, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-02-09 22:53:42', '2026-02-09 22:53:42', NULL, NULL),
(765, 194, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-02-09 22:53:47', '2026-02-09 22:53:47', NULL, NULL),
(766, 194, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '3k em 4mes', 'text', '2026-02-09 22:54:10', '2026-02-09 22:54:10', NULL, NULL),
(767, 194, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-02-09 22:54:15', '2026-02-09 22:54:15', NULL, NULL),
(768, 194, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-02-09 22:54:41', '2026-02-09 22:54:41', NULL, NULL),
(769, 194, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-02-09 22:54:44', '2026-02-09 22:54:44', NULL, NULL),
(770, 194, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-02-09 22:55:09', '2026-02-09 22:55:09', NULL, NULL),
(771, 194, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Tempo de Protrombina\",\"Creatinina\"]', 'checkbox', '2026-02-09 22:56:40', '2026-02-09 22:56:40', NULL, NULL),
(772, 195, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-03-02 19:10:30', '2026-03-02 19:10:30', NULL, NULL),
(773, 195, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-03-02 19:11:05', '2026-03-02 19:11:05', NULL, NULL),
(774, 195, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Nunca tive problemas.', 'text', '2026-03-02 19:11:32', '2026-03-02 19:11:32', NULL, NULL),
(775, 195, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-03-02 19:12:08', '2026-03-02 19:12:08', NULL, NULL),
(776, 195, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-03-02 19:12:10', '2026-03-02 19:12:10', NULL, NULL),
(777, 195, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-03-02 19:12:12', '2026-03-02 19:12:12', NULL, NULL),
(778, 195, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-03-02 19:12:15', '2026-03-02 19:12:15', NULL, NULL),
(779, 195, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-03-02 19:12:38', '2026-03-02 19:12:38', NULL, NULL),
(780, 195, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-03-02 19:12:40', '2026-03-02 19:12:40', NULL, NULL),
(781, 195, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-03-02 19:12:43', '2026-03-02 19:12:43', NULL, NULL),
(782, 195, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-03-02 19:12:44', '2026-03-02 19:12:44', NULL, NULL),
(783, 195, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-03-02 19:13:18', '2026-03-02 19:13:18', NULL, NULL),
(784, 195, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-03-02 19:13:19', '2026-03-02 19:13:19', NULL, NULL),
(785, 195, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-03-02 19:13:22', '2026-03-02 19:13:22', NULL, NULL),
(786, 195, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-03-02 19:13:24', '2026-03-02 19:13:24', NULL, NULL),
(787, 195, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Apenas Herpes zoster', 'text', '2026-03-02 19:14:16', '2026-03-02 19:14:16', NULL, NULL),
(788, 195, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Sim', 'boolean', '2026-03-02 19:14:36', '2026-03-02 19:14:36', NULL, NULL),
(789, 195, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-03-02 19:14:41', '2026-03-02 19:14:41', NULL, NULL),
(790, 195, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-03-02 19:14:43', '2026-03-02 19:14:43', NULL, NULL),
(791, 195, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-03-02 19:14:56', '2026-03-02 19:14:56', NULL, NULL),
(792, 195, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-03-02 19:15:16', '2026-03-02 19:15:16', NULL, NULL),
(793, 195, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-03-02 19:15:17', '2026-03-02 19:15:17', NULL, NULL),
(794, 195, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-03-02 19:15:32', '2026-03-02 19:15:32', NULL, NULL),
(795, 195, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-03-02 19:15:34', '2026-03-02 19:15:34', NULL, NULL),
(796, 195, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-03-02 19:15:38', '2026-03-02 19:15:38', NULL, NULL),
(797, 195, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-03-02 19:16:13', '2026-03-02 19:16:13', NULL, NULL),
(798, 195, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-03-02 19:16:14', '2026-03-02 19:16:14', NULL, NULL),
(799, 195, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-03-02 19:16:35', '2026-03-02 19:16:35', NULL, NULL),
(800, 195, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-03-02 19:16:37', '2026-03-02 19:16:37', NULL, NULL),
(801, 195, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Venlafaxina 300mg\r\nVoextor 20mg\r\nDonaren 50mg\r\nAdoless', 'text', '2026-03-02 19:17:22', '2026-03-02 19:17:22', NULL, NULL),
(802, 195, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-03-02 19:17:30', '2026-03-02 19:17:30', NULL, NULL),
(803, 195, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-03-02 19:18:02', '2026-03-02 19:18:02', NULL, NULL),
(804, 195, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-03-02 19:18:05', '2026-03-02 19:18:05', NULL, NULL),
(805, 195, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2026-03-02 19:18:07', '2026-03-02 19:18:07', NULL, NULL),
(806, 195, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-03-02 19:18:34', '2026-03-02 19:18:34', NULL, NULL),
(807, 195, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-03-02 19:19:21', '2026-03-02 19:19:21', NULL, NULL),
(808, 195, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-03-02 19:20:35', '2026-03-02 19:20:35', NULL, NULL),
(809, 195, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Tempo de Protrombina\",\"KPTT\",\"Glicemia de Jejum\",\"Eletrocardiograma\"]', 'checkbox', '2026-03-02 19:21:32', '2026-03-02 19:21:32', NULL, NULL),
(810, 196, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-03-10 22:14:58', '2026-03-10 22:14:58', NULL, NULL),
(811, 196, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-03-10 22:15:27', '2026-03-10 22:15:27', NULL, NULL),
(812, 196, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-03-10 22:15:56', '2026-03-10 22:15:56', NULL, NULL),
(813, 196, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-03-10 22:15:57', '2026-03-10 22:15:57', NULL, NULL),
(814, 196, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-03-10 22:15:57', '2026-03-10 22:15:57', NULL, NULL),
(815, 196, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-03-10 22:15:59', '2026-03-10 22:15:59', NULL, NULL),
(816, 196, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-03-10 22:16:19', '2026-03-10 22:16:19', NULL, NULL),
(817, 196, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-03-10 22:16:21', '2026-03-10 22:16:21', NULL, NULL),
(818, 196, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-03-10 22:16:22', '2026-03-10 22:16:22', NULL, NULL),
(819, 196, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-03-10 22:16:22', '2026-03-10 22:16:22', NULL, NULL),
(820, 196, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-03-10 22:16:54', '2026-03-10 22:16:54', NULL, NULL),
(821, 196, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-03-10 22:16:55', '2026-03-10 22:16:55', NULL, NULL),
(822, 196, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-03-10 22:16:56', '2026-03-10 22:16:56', NULL, NULL),
(823, 196, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-03-10 22:16:56', '2026-03-10 22:16:56', NULL, NULL),
(824, 196, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Não tenho', 'text', '2026-03-10 22:17:16', '2026-03-10 22:17:16', NULL, NULL),
(825, 196, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-03-10 22:17:32', '2026-03-10 22:17:32', NULL, NULL),
(826, 196, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-03-10 22:17:32', '2026-03-10 22:17:32', NULL, NULL),
(827, 196, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-03-10 22:17:33', '2026-03-10 22:17:33', NULL, NULL),
(828, 196, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-03-10 22:17:41', '2026-03-10 22:17:41', NULL, NULL),
(829, 196, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-03-10 22:18:01', '2026-03-10 22:18:01', NULL, NULL),
(830, 196, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-03-10 22:18:02', '2026-03-10 22:18:02', NULL, NULL),
(831, 196, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-03-10 22:18:16', '2026-03-10 22:18:16', NULL, NULL),
(832, 196, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-03-10 22:18:16', '2026-03-10 22:18:16', NULL, NULL),
(833, 196, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-03-10 22:18:17', '2026-03-10 22:18:17', NULL, NULL),
(834, 196, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-03-10 22:18:43', '2026-03-10 22:18:43', NULL, NULL),
(835, 196, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-03-10 22:18:44', '2026-03-10 22:18:44', NULL, NULL),
(836, 196, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-03-10 22:19:01', '2026-03-10 22:19:01', NULL, NULL),
(837, 196, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2026-03-10 22:19:02', '2026-03-10 22:19:02', NULL, NULL),
(838, 196, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-03-10 22:19:03', '2026-03-10 22:19:03', NULL, NULL),
(839, 196, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-03-10 22:19:33', '2026-03-10 22:19:33', NULL, NULL),
(840, 196, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-03-10 22:19:34', '2026-03-10 22:19:34', NULL, NULL),
(841, 196, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-03-10 22:19:35', '2026-03-10 22:19:35', NULL, NULL),
(842, 196, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não tive', 'text', '2026-03-10 22:15:34', '2026-03-10 22:15:34', NULL, NULL),
(843, 196, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-03-10 22:19:58', '2026-03-10 22:19:58', NULL, NULL),
(844, 196, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-03-10 22:20:02', '2026-03-10 22:20:02', NULL, NULL),
(845, 196, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-03-10 22:20:34', '2026-03-10 22:20:34', NULL, NULL),
(846, 196, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Eletrocardiograma\",\"Glicemia de Jejum\",\"Tempo de Protrombina\",\"Creatinina\",\"Hemograma\"]', 'checkbox', '2026-03-10 22:22:05', '2026-03-10 22:22:05', NULL, NULL),
(847, 198, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-03-30 15:21:35', '2026-03-30 15:21:35', NULL, NULL),
(848, 198, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-03-30 15:22:09', '2026-03-30 15:22:09', NULL, NULL),
(849, 198, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não houve', 'text', '2026-03-30 15:22:16', '2026-03-30 15:22:16', NULL, NULL),
(850, 198, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-03-30 15:22:39', '2026-03-30 15:22:39', NULL, NULL),
(851, 198, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-03-30 15:22:44', '2026-03-30 15:22:44', NULL, NULL),
(852, 198, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-03-30 15:22:46', '2026-03-30 15:22:46', NULL, NULL),
(853, 198, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-03-30 15:22:54', '2026-03-30 15:22:54', NULL, NULL),
(854, 198, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2026-03-30 15:23:27', '2026-03-30 15:23:27', NULL, NULL),
(855, 198, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-03-30 15:23:33', '2026-03-30 15:23:33', NULL, NULL),
(856, 198, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-03-30 15:23:37', '2026-03-30 15:23:37', NULL, NULL),
(857, 198, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-03-30 15:23:40', '2026-03-30 15:23:40', NULL, NULL),
(858, 198, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Sim', 'boolean', '2026-03-30 15:24:14', '2026-03-30 15:24:14', NULL, NULL);
INSERT INTO `paciente_video_respostas` (`id`, `paciente_id`, `video_id`, `video_title`, `video_ordem`, `question_id`, `question_index`, `question_text`, `question_title`, `answer`, `answer_type`, `created_at`, `updated_at`, `ip_address`, `user_agent`) VALUES
(859, 198, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-03-30 15:24:17', '2026-03-30 15:24:17', NULL, NULL),
(860, 198, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-03-30 15:24:22', '2026-03-30 15:24:22', NULL, NULL),
(861, 198, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Sim', 'boolean', '2026-03-30 15:24:28', '2026-03-30 15:24:28', NULL, NULL),
(862, 198, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Fiz ablação cardíaca em 2023 e atualmente faço quimioterapia pois tenho carcinomatose peritonial', 'text', '2026-03-30 15:25:38', '2026-03-30 15:25:38', NULL, NULL),
(863, 198, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-03-30 15:25:57', '2026-03-30 15:25:57', NULL, NULL),
(864, 198, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2026-03-30 15:25:59', '2026-03-30 15:25:59', NULL, NULL),
(865, 198, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-03-30 15:26:03', '2026-03-30 15:26:03', NULL, NULL),
(866, 198, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-03-30 15:26:10', '2026-03-30 15:26:10', NULL, NULL),
(867, 198, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-03-30 15:26:34', '2026-03-30 15:26:34', NULL, NULL),
(868, 198, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-03-30 15:26:37', '2026-03-30 15:26:37', NULL, NULL),
(869, 198, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-03-30 15:26:53', '2026-03-30 15:26:53', NULL, NULL),
(870, 198, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-03-30 15:26:56', '2026-03-30 15:26:56', NULL, NULL),
(871, 198, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-03-30 15:27:00', '2026-03-30 15:27:00', NULL, NULL),
(872, 198, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-03-30 15:27:29', '2026-03-30 15:27:29', NULL, NULL),
(873, 198, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-03-30 15:27:34', '2026-03-30 15:27:34', NULL, NULL),
(874, 198, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-03-30 15:27:56', '2026-03-30 15:27:56', NULL, NULL),
(875, 198, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-03-30 15:28:01', '2026-03-30 15:28:01', NULL, NULL),
(876, 198, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Carvedilol, vildagliptina,alopurinol,maxfer,mecobe,rosuvastatina,carvedilol, magnésio,insulina a noite', 'text', '2026-03-30 15:29:48', '2026-03-30 15:29:48', NULL, NULL),
(877, 198, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-03-30 15:30:06', '2026-03-30 15:30:06', NULL, NULL),
(878, 198, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-03-30 15:30:41', '2026-03-30 15:30:41', NULL, NULL),
(879, 198, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-03-30 15:30:44', '2026-03-30 15:30:44', NULL, NULL),
(880, 198, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '15 quilos', 'text', '2026-03-30 15:31:00', '2026-03-30 15:31:00', NULL, NULL),
(881, 198, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-03-30 15:31:04', '2026-03-30 15:31:04', NULL, NULL),
(882, 198, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-03-30 15:31:31', '2026-03-30 15:31:31', NULL, NULL),
(883, 198, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-03-30 15:31:39', '2026-03-30 15:31:39', NULL, NULL),
(884, 198, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-03-30 15:32:14', '2026-03-30 15:32:14', NULL, NULL),
(885, 198, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Glicemia de Jejum\"]', 'checkbox', '2026-03-30 15:33:23', '2026-03-30 15:33:23', NULL, NULL),
(886, 199, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-03-31 11:51:46', '2026-03-31 11:51:46', NULL, NULL),
(887, 199, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-03-31 11:52:19', '2026-03-31 11:52:19', NULL, NULL),
(888, 199, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não tive nenhuma problema', 'text', '2026-03-31 11:52:28', '2026-03-31 11:52:28', NULL, NULL),
(889, 199, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-03-31 11:52:53', '2026-03-31 11:52:53', NULL, NULL),
(890, 199, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-03-31 11:52:55', '2026-03-31 11:52:55', NULL, NULL),
(891, 199, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-03-31 11:52:56', '2026-03-31 11:52:56', NULL, NULL),
(892, 199, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-03-31 11:52:59', '2026-03-31 11:52:59', NULL, NULL),
(893, 199, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-03-31 11:53:23', '2026-03-31 11:53:23', NULL, NULL),
(894, 199, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-03-31 11:53:25', '2026-03-31 11:53:25', NULL, NULL),
(895, 199, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-03-31 11:53:27', '2026-03-31 11:53:27', NULL, NULL),
(896, 199, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-03-31 11:53:28', '2026-03-31 11:53:28', NULL, NULL),
(897, 199, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-03-31 11:54:03', '2026-03-31 11:54:03', NULL, NULL),
(898, 199, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-03-31 11:54:04', '2026-03-31 11:54:04', NULL, NULL),
(899, 199, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-03-31 11:54:08', '2026-03-31 11:54:08', NULL, NULL),
(900, 199, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-03-31 11:54:10', '2026-03-31 11:54:10', NULL, NULL),
(901, 199, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Não tenho', 'text', '2026-03-31 11:54:33', '2026-03-31 11:54:33', NULL, NULL),
(902, 199, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-03-31 11:54:51', '2026-03-31 11:54:51', NULL, NULL),
(903, 199, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-03-31 11:54:53', '2026-03-31 11:54:53', NULL, NULL),
(904, 199, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-03-31 11:54:57', '2026-03-31 11:54:57', NULL, NULL),
(905, 199, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-03-31 11:55:04', '2026-03-31 11:55:04', NULL, NULL),
(906, 199, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-03-31 11:55:26', '2026-03-31 11:55:26', NULL, NULL),
(907, 199, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-03-31 11:56:12', '2026-03-31 11:56:12', NULL, NULL),
(908, 199, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-03-31 11:56:28', '2026-03-31 11:56:28', NULL, NULL),
(909, 199, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-03-31 11:56:29', '2026-03-31 11:56:29', NULL, NULL),
(910, 199, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-03-31 11:56:33', '2026-03-31 11:56:33', NULL, NULL),
(911, 199, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-03-31 11:57:04', '2026-03-31 11:57:04', NULL, NULL),
(912, 199, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-03-31 11:57:06', '2026-03-31 11:57:06', NULL, NULL),
(913, 199, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-03-31 11:57:29', '2026-03-31 11:57:29', NULL, NULL),
(914, 199, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2026-03-31 11:57:31', '2026-03-31 11:57:31', NULL, NULL),
(915, 199, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-03-31 11:57:35', '2026-03-31 11:57:35', NULL, NULL),
(916, 199, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-03-31 11:58:05', '2026-03-31 11:58:05', NULL, NULL),
(917, 199, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-03-31 11:58:12', '2026-03-31 11:58:12', NULL, NULL),
(918, 199, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-03-31 11:58:13', '2026-03-31 11:58:13', NULL, NULL),
(919, 199, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-03-31 11:58:39', '2026-03-31 11:58:39', NULL, NULL),
(920, 199, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-03-31 11:58:45', '2026-03-31 11:58:45', NULL, NULL),
(921, 199, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe III', 'choice', '2026-03-31 11:59:23', '2026-03-31 11:59:23', NULL, NULL),
(922, 199, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Nenhum\"]', 'checkbox', '2026-03-31 12:00:18', '2026-03-31 12:00:18', NULL, NULL),
(923, 200, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-09 17:36:49', '2026-04-09 17:36:49', NULL, NULL),
(924, 200, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-09 17:37:21', '2026-04-09 17:37:21', NULL, NULL),
(925, 200, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'nao tive', 'text', '2026-04-09 17:37:37', '2026-04-09 17:37:37', NULL, NULL),
(926, 200, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-04-09 17:38:01', '2026-04-09 17:38:01', NULL, NULL),
(927, 200, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Sim', 'boolean', '2026-04-09 17:38:03', '2026-04-09 17:38:03', NULL, NULL),
(928, 200, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-09 17:38:05', '2026-04-09 17:38:05', NULL, NULL),
(929, 200, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-09 17:38:08', '2026-04-09 17:38:08', NULL, NULL),
(930, 200, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2026-04-09 17:38:32', '2026-04-09 17:38:32', NULL, NULL),
(931, 200, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-09 17:38:34', '2026-04-09 17:38:34', NULL, NULL),
(932, 200, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-09 17:38:34', '2026-04-09 17:38:34', NULL, NULL),
(933, 200, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Sim', 'boolean', '2026-04-09 17:38:36', '2026-04-09 17:38:36', NULL, NULL),
(934, 200, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-09 17:39:11', '2026-04-09 17:39:11', NULL, NULL),
(935, 200, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-09 17:39:12', '2026-04-09 17:39:12', NULL, NULL),
(936, 200, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-09 17:39:12', '2026-04-09 17:39:12', NULL, NULL),
(937, 200, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-09 17:39:31', '2026-04-09 17:39:31', NULL, NULL),
(938, 200, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'ee', 'text', '2026-04-09 17:39:34', '2026-04-09 17:39:34', NULL, NULL),
(939, 200, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-09 17:39:52', '2026-04-09 17:39:52', NULL, NULL),
(940, 200, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2026-04-09 17:39:53', '2026-04-09 17:39:53', NULL, NULL),
(941, 200, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-09 17:39:54', '2026-04-09 17:39:54', NULL, NULL),
(942, 200, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Sim', 'boolean', '2026-04-09 17:39:55', '2026-04-09 17:39:55', NULL, NULL),
(943, 200, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-09 17:40:17', '2026-04-09 17:40:17', NULL, NULL),
(944, 200, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Sim', 'boolean', '2026-04-09 17:40:18', '2026-04-09 17:40:18', NULL, NULL),
(945, 200, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-09 17:40:32', '2026-04-09 17:40:32', NULL, NULL),
(946, 200, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-09 17:40:32', '2026-04-09 17:40:32', NULL, NULL),
(947, 200, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-04-09 17:40:33', '2026-04-09 17:40:33', NULL, NULL),
(948, 200, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-09 17:41:03', '2026-04-09 17:41:03', NULL, NULL),
(949, 200, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-04-09 17:41:04', '2026-04-09 17:41:04', NULL, NULL),
(950, 200, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-04-09 17:41:22', '2026-04-09 17:41:22', NULL, NULL),
(951, 200, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-09 17:41:23', '2026-04-09 17:41:23', NULL, NULL),
(952, 200, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'ff', 'text', '2026-04-09 17:41:28', '2026-04-09 17:41:28', NULL, NULL),
(953, 200, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Sim', 'boolean', '2026-04-09 17:41:29', '2026-04-09 17:41:29', NULL, NULL),
(954, 200, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-09 17:42:04', '2026-04-09 17:42:04', NULL, NULL),
(955, 200, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-04-09 17:42:05', '2026-04-09 17:42:05', NULL, NULL),
(956, 200, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '1', 'text', '2026-04-09 17:42:09', '2026-04-09 17:42:09', NULL, NULL),
(957, 200, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-04-09 17:42:10', '2026-04-09 17:42:10', NULL, NULL),
(958, 200, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Sim', 'boolean', '2026-04-09 17:42:35', '2026-04-09 17:42:35', NULL, NULL),
(959, 200, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-09 17:42:37', '2026-04-09 17:42:37', NULL, NULL),
(960, 200, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe III', 'choice', '2026-04-09 17:43:02', '2026-04-09 17:43:02', NULL, NULL),
(961, 200, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Tempo de Protrombina\",\"Glicemia de Jejum\",\"Eletrocardiograma\",\"KPTT\",\"Creatinina\"]', 'checkbox', '2026-04-09 17:43:51', '2026-04-09 17:43:51', NULL, NULL),
(962, 202, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-17 18:38:58', '2026-04-17 18:38:58', NULL, NULL),
(963, 202, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-17 18:39:36', '2026-04-17 18:39:36', NULL, NULL),
(964, 202, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Não tive nenhum problema com as cirurgias anteriores', 'text', '2026-04-17 18:40:18', '2026-04-17 18:40:18', NULL, NULL),
(965, 202, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-04-17 18:40:46', '2026-04-17 18:40:46', NULL, NULL),
(966, 202, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-17 18:41:00', '2026-04-17 18:41:00', NULL, NULL),
(967, 202, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-17 18:41:02', '2026-04-17 18:41:02', NULL, NULL),
(968, 202, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-17 18:41:06', '2026-04-17 18:41:06', NULL, NULL),
(969, 202, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-17 18:41:33', '2026-04-17 18:41:33', NULL, NULL),
(970, 202, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-17 18:41:38', '2026-04-17 18:41:38', NULL, NULL),
(971, 202, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-17 18:41:43', '2026-04-17 18:41:43', NULL, NULL),
(972, 202, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-17 18:41:46', '2026-04-17 18:41:46', NULL, NULL),
(973, 202, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-17 18:42:22', '2026-04-17 18:42:22', NULL, NULL),
(974, 202, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-17 18:42:25', '2026-04-17 18:42:25', NULL, NULL),
(975, 202, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-17 18:42:29', '2026-04-17 18:42:29', NULL, NULL),
(976, 202, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-17 18:42:32', '2026-04-17 18:42:32', NULL, NULL),
(977, 202, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Infecção urinária repetitiva sem sintomas\r\nHérnia de hiato e gastrite tomo ezomeprazol diariamente', 'text', '2026-04-17 18:44:03', '2026-04-17 18:44:03', NULL, NULL),
(978, 202, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-17 18:45:02', '2026-04-17 18:45:02', NULL, NULL),
(979, 202, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-17 18:45:11', '2026-04-17 18:45:11', NULL, NULL),
(980, 202, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-17 18:45:14', '2026-04-17 18:45:14', NULL, NULL),
(981, 202, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-17 18:45:26', '2026-04-17 18:45:26', NULL, NULL),
(982, 202, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-17 18:45:48', '2026-04-17 18:45:48', NULL, NULL),
(983, 202, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-17 18:45:52', '2026-04-17 18:45:52', NULL, NULL),
(984, 202, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-17 18:46:09', '2026-04-17 18:46:09', NULL, NULL),
(985, 202, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-17 18:46:13', '2026-04-17 18:46:13', NULL, NULL),
(986, 202, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-04-17 18:46:19', '2026-04-17 18:46:19', NULL, NULL),
(987, 202, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-17 18:46:49', '2026-04-17 18:46:49', NULL, NULL),
(988, 202, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2026-04-17 18:46:56', '2026-04-17 18:46:56', NULL, NULL),
(989, 202, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'Sulfa\r\nAntiinflamatórios devido a úlcera que tive anteriormente', 'text', '2026-04-17 18:47:38', '2026-04-17 18:47:38', NULL, NULL),
(990, 202, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-04-17 18:48:00', '2026-04-17 18:48:00', NULL, NULL),
(991, 202, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-17 18:48:06', '2026-04-17 18:48:06', NULL, NULL),
(992, 202, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Ezomeprazol 20mg 1x dia\r\nCorus 25mg 2x dia\r\nAtenolol 25mg 1x dia\r\nRevoc 50mg 1x dia\r\nAdera D3 50.000 a cada 15 dias', 'text', '2026-04-17 18:49:54', '2026-04-17 18:49:54', NULL, NULL),
(993, 202, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-17 18:50:00', '2026-04-17 18:50:00', NULL, NULL),
(994, 202, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-04-17 18:50:33', '2026-04-17 18:50:33', NULL, NULL),
(995, 202, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-04-17 18:50:50', '2026-04-17 18:50:50', NULL, NULL),
(996, 202, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '8kg no início do aparecimento do câncer', 'text', '2026-04-17 18:51:27', '2026-04-17 18:51:27', NULL, NULL),
(997, 202, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-04-17 18:51:31', '2026-04-17 18:51:31', NULL, NULL),
(998, 202, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-17 18:51:58', '2026-04-17 18:51:58', NULL, NULL),
(999, 202, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-17 18:52:10', '2026-04-17 18:52:10', NULL, NULL),
(1000, 202, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-04-17 18:53:33', '2026-04-17 18:53:33', NULL, NULL),
(1001, 202, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Eletrocardiograma\",\"Glicemia de Jejum\",\"Tempo de Protrombina\"]', 'checkbox', '2026-04-17 18:55:29', '2026-04-17 18:55:29', NULL, NULL),
(1002, 205, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-20 13:03:43', '2026-04-20 13:03:43', NULL, NULL),
(1003, 205, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-20 13:04:19', '2026-04-20 13:04:19', NULL, NULL),
(1004, 205, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Fiz duas cesaria.\r\nNa última tive enjoo e falta de ar mas acredito ser pelo peso do bebê no momento.', 'text', '2026-04-20 13:05:06', '2026-04-20 13:05:06', NULL, NULL),
(1005, 205, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-20 13:05:30', '2026-04-20 13:05:30', NULL, NULL),
(1006, 205, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-20 13:05:33', '2026-04-20 13:05:33', NULL, NULL),
(1007, 205, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-20 13:05:34', '2026-04-20 13:05:34', NULL, NULL),
(1008, 205, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-20 13:05:37', '2026-04-20 13:05:37', NULL, NULL),
(1009, 205, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-20 13:06:01', '2026-04-20 13:06:01', NULL, NULL),
(1010, 205, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-20 13:06:03', '2026-04-20 13:06:03', NULL, NULL),
(1011, 205, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-20 13:06:06', '2026-04-20 13:06:06', NULL, NULL),
(1012, 205, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-20 13:06:08', '2026-04-20 13:06:08', NULL, NULL),
(1013, 205, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-20 13:06:41', '2026-04-20 13:06:41', NULL, NULL),
(1014, 205, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-20 13:06:43', '2026-04-20 13:06:43', NULL, NULL),
(1015, 205, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-20 13:06:45', '2026-04-20 13:06:45', NULL, NULL),
(1016, 205, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-20 13:06:48', '2026-04-20 13:06:48', NULL, NULL),
(1017, 205, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'A princípio não tenho nada além da neoplasia motivo do cateter', 'text', '2026-04-20 13:07:37', '2026-04-20 13:07:37', NULL, NULL),
(1018, 205, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-20 13:07:57', '2026-04-20 13:07:57', NULL, NULL),
(1019, 205, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-20 13:07:59', '2026-04-20 13:07:59', NULL, NULL),
(1020, 205, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-20 13:08:02', '2026-04-20 13:08:02', NULL, NULL),
(1021, 205, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Sim', 'boolean', '2026-04-20 13:08:31', '2026-04-20 13:08:31', NULL, NULL),
(1022, 205, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-20 13:08:52', '2026-04-20 13:08:52', NULL, NULL),
(1023, 205, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-20 13:08:54', '2026-04-20 13:08:54', NULL, NULL),
(1024, 205, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-20 13:09:09', '2026-04-20 13:09:09', NULL, NULL),
(1025, 205, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-20 13:09:10', '2026-04-20 13:09:10', NULL, NULL),
(1026, 205, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-20 13:09:21', '2026-04-20 13:09:21', NULL, NULL),
(1027, 205, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-20 13:09:48', '2026-04-20 13:09:48', NULL, NULL),
(1028, 205, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-04-20 13:09:51', '2026-04-20 13:09:51', NULL, NULL),
(1029, 205, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-04-20 13:10:14', '2026-04-20 13:10:14', NULL, NULL),
(1030, 205, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-20 13:10:20', '2026-04-20 13:10:20', NULL, NULL),
(1031, 205, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Anticoncepcional (desogestrel)', 'text', '2026-04-20 13:10:37', '2026-04-20 13:10:37', NULL, NULL),
(1032, 205, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-20 13:10:41', '2026-04-20 13:10:41', NULL, NULL),
(1033, 205, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-20 13:11:23', '2026-04-20 13:11:23', NULL, NULL),
(1034, 205, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-04-20 13:11:27', '2026-04-20 13:11:27', NULL, NULL),
(1035, 205, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-04-20 13:11:30', '2026-04-20 13:11:30', NULL, NULL),
(1036, 205, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-20 13:11:56', '2026-04-20 13:11:56', NULL, NULL),
(1037, 205, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-20 13:12:04', '2026-04-20 13:12:04', NULL, NULL),
(1038, 205, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-04-20 13:12:58', '2026-04-20 13:12:58', NULL, NULL),
(1039, 205, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Tempo de Protrombina\",\"KPTT\",\"Eletrocardiograma\",\"Glicemia de Jejum\"]', 'checkbox', '2026-04-20 13:14:09', '2026-04-20 13:14:09', NULL, NULL),
(1040, 207, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-20 19:34:27', '2026-04-20 19:34:27', NULL, NULL),
(1041, 207, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2026-04-20 19:35:16', '2026-04-20 19:35:16', NULL, NULL),
(1042, 207, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-04-20 19:35:45', '2026-04-20 19:35:45', NULL, NULL),
(1043, 207, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Sim', 'boolean', '2026-04-20 19:35:55', '2026-04-20 19:35:55', NULL, NULL),
(1044, 207, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Sim', 'boolean', '2026-04-20 19:36:09', '2026-04-20 19:36:09', NULL, NULL),
(1045, 207, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-20 19:36:30', '2026-04-20 19:36:30', NULL, NULL),
(1046, 207, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-20 19:37:06', '2026-04-20 19:37:06', NULL, NULL),
(1047, 207, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-20 19:37:10', '2026-04-20 19:37:10', NULL, NULL),
(1048, 207, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-20 19:37:20', '2026-04-20 19:37:20', NULL, NULL),
(1049, 207, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-20 19:37:20', '2026-04-20 19:37:20', NULL, NULL),
(1050, 207, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-20 19:38:02', '2026-04-20 19:38:02', NULL, NULL),
(1051, 207, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-20 19:38:10', '2026-04-20 19:38:10', NULL, NULL),
(1052, 207, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-20 19:38:22', '2026-04-20 19:38:22', NULL, NULL),
(1053, 207, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-20 19:38:30', '2026-04-20 19:38:30', NULL, NULL),
(1054, 207, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, '13stends em outras artérias.Operei duas carótidas,câncer de intestino,câncer na pálpebra,2avecs,tumor cerebral e retirei alguns câncer de pele.', 'text', '2026-04-20 19:43:41', '2026-04-20 19:43:41', NULL, NULL),
(1055, 207, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-20 19:44:06', '2026-04-20 19:44:06', NULL, NULL),
(1056, 207, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2026-04-20 19:44:11', '2026-04-20 19:44:11', NULL, NULL),
(1057, 207, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-20 19:44:13', '2026-04-20 19:44:13', NULL, NULL),
(1058, 207, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-20 19:44:24', '2026-04-20 19:44:24', NULL, NULL),
(1059, 207, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-20 19:44:48', '2026-04-20 19:44:48', NULL, NULL),
(1060, 207, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-20 19:44:53', '2026-04-20 19:44:53', NULL, NULL),
(1061, 207, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-20 19:45:21', '2026-04-20 19:45:21', NULL, NULL),
(1062, 207, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Sim', 'boolean', '2026-04-20 19:45:28', '2026-04-20 19:45:28', NULL, NULL),
(1063, 207, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-20 19:45:34', '2026-04-20 19:45:34', NULL, NULL),
(1064, 207, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Sim', 'boolean', '2026-04-20 19:46:09', '2026-04-20 19:46:09', NULL, NULL),
(1065, 207, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-04-20 19:46:16', '2026-04-20 19:46:16', NULL, NULL),
(1066, 207, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-04-20 19:46:50', '2026-04-20 19:46:50', NULL, NULL),
(1067, 207, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-20 19:47:02', '2026-04-20 19:47:02', NULL, NULL),
(1068, 204, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-23 12:46:42', '2026-04-23 12:46:42', NULL, NULL),
(1069, 204, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-23 12:47:19', '2026-04-23 12:47:19', NULL, NULL),
(1070, 204, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Sem problemas', 'text', '2026-04-23 12:47:25', '2026-04-23 12:47:25', NULL, NULL),
(1071, 204, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-23 12:47:51', '2026-04-23 12:47:51', NULL, NULL),
(1072, 204, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-23 12:47:53', '2026-04-23 12:47:53', NULL, NULL),
(1073, 204, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-23 12:47:54', '2026-04-23 12:47:54', NULL, NULL),
(1074, 204, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-23 12:47:58', '2026-04-23 12:47:58', NULL, NULL),
(1075, 204, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-23 12:48:27', '2026-04-23 12:48:27', NULL, NULL),
(1076, 204, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-23 12:48:29', '2026-04-23 12:48:29', NULL, NULL),
(1077, 204, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-23 12:48:32', '2026-04-23 12:48:32', NULL, NULL),
(1078, 204, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-23 12:48:33', '2026-04-23 12:48:33', NULL, NULL),
(1079, 204, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-23 12:49:11', '2026-04-23 12:49:11', NULL, NULL),
(1080, 204, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Sim', 'boolean', '2026-04-23 12:49:14', '2026-04-23 12:49:14', NULL, NULL),
(1081, 204, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-23 12:49:16', '2026-04-23 12:49:16', NULL, NULL),
(1082, 204, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-23 12:49:20', '2026-04-23 12:49:20', NULL, NULL),
(1083, 204, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Estou na menopausa, realizo reposição hormonal.', 'text', '2026-04-23 12:50:26', '2026-04-23 12:50:26', NULL, NULL),
(1084, 204, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-23 12:50:47', '2026-04-23 12:50:47', NULL, NULL),
(1085, 204, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-23 12:50:49', '2026-04-23 12:50:49', NULL, NULL),
(1086, 204, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-23 12:50:50', '2026-04-23 12:50:50', NULL, NULL),
(1087, 204, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-23 12:50:58', '2026-04-23 12:50:58', NULL, NULL);
INSERT INTO `paciente_video_respostas` (`id`, `paciente_id`, `video_id`, `video_title`, `video_ordem`, `question_id`, `question_index`, `question_text`, `question_title`, `answer`, `answer_type`, `created_at`, `updated_at`, `ip_address`, `user_agent`) VALUES
(1088, 204, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-23 12:51:19', '2026-04-23 12:51:19', NULL, NULL),
(1089, 204, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-23 12:51:21', '2026-04-23 12:51:21', NULL, NULL),
(1090, 204, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-23 12:51:37', '2026-04-23 12:51:37', NULL, NULL),
(1091, 204, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-23 12:51:39', '2026-04-23 12:51:39', NULL, NULL),
(1092, 204, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-23 12:51:43', '2026-04-23 12:51:43', NULL, NULL),
(1093, 204, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-23 12:52:12', '2026-04-23 12:52:12', NULL, NULL),
(1094, 204, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-04-23 12:52:15', '2026-04-23 12:52:15', NULL, NULL),
(1095, 204, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-23 12:52:41', '2026-04-23 12:52:41', NULL, NULL),
(1096, 204, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Puran, ultrogestan, reposição hormonal testosterona e estradiol.', 'text', '2026-04-23 12:53:56', '2026-04-23 12:53:56', NULL, NULL),
(1097, 204, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-23 12:54:00', '2026-04-23 12:54:00', NULL, NULL),
(1098, 204, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-23 12:54:41', '2026-04-23 12:54:41', NULL, NULL),
(1099, 204, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-04-23 12:54:44', '2026-04-23 12:54:44', NULL, NULL),
(1100, 204, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2026-04-23 12:54:54', '2026-04-23 12:54:54', NULL, NULL),
(1101, 204, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-23 12:55:22', '2026-04-23 12:55:22', NULL, NULL),
(1102, 204, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-23 12:55:27', '2026-04-23 12:55:27', NULL, NULL),
(1103, 204, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-04-23 12:56:39', '2026-04-23 12:56:39', NULL, NULL),
(1104, 204, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Eletrocardiograma\",\"Glicemia de Jejum\",\"Tempo de Protrombina\",\"KPTT\"]', 'checkbox', '2026-04-23 12:57:52', '2026-04-23 12:57:52', NULL, NULL),
(1105, 215, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-23 20:40:08', '2026-04-23 20:40:08', NULL, NULL),
(1106, 214, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-23 20:42:09', '2026-04-23 20:42:09', NULL, NULL),
(1107, 215, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-23 20:40:43', '2026-04-23 20:40:43', NULL, NULL),
(1108, 215, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Ok', 'text', '2026-04-23 20:40:48', '2026-04-23 20:40:48', NULL, NULL),
(1109, 215, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-23 20:41:12', '2026-04-23 20:41:12', NULL, NULL),
(1110, 215, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-23 20:41:13', '2026-04-23 20:41:13', NULL, NULL),
(1111, 215, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-23 20:41:14', '2026-04-23 20:41:14', NULL, NULL),
(1112, 215, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-23 20:41:23', '2026-04-23 20:41:23', NULL, NULL),
(1113, 215, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-23 20:41:47', '2026-04-23 20:41:47', NULL, NULL),
(1114, 215, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-23 20:41:48', '2026-04-23 20:41:48', NULL, NULL),
(1115, 215, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Sim', 'boolean', '2026-04-23 20:41:53', '2026-04-23 20:41:53', NULL, NULL),
(1116, 215, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-23 20:41:54', '2026-04-23 20:41:54', NULL, NULL),
(1117, 215, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-23 20:42:40', '2026-04-23 20:42:40', NULL, NULL),
(1118, 215, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-23 20:42:41', '2026-04-23 20:42:41', NULL, NULL),
(1119, 214, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-23 20:42:42', '2026-04-23 20:42:42', NULL, NULL),
(1120, 215, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-23 20:42:43', '2026-04-23 20:42:43', NULL, NULL),
(1121, 215, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-23 20:42:44', '2026-04-23 20:42:44', NULL, NULL),
(1122, 214, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Ablação', 'text', '2026-04-23 20:42:45', '2026-04-23 20:42:45', NULL, NULL),
(1123, 215, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Não tenho', 'text', '2026-04-23 20:43:07', '2026-04-23 20:43:07', NULL, NULL),
(1124, 214, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-23 20:43:12', '2026-04-23 20:43:12', NULL, NULL),
(1125, 214, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-23 20:43:14', '2026-04-23 20:43:14', NULL, NULL),
(1126, 214, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-23 20:43:15', '2026-04-23 20:43:15', NULL, NULL),
(1127, 214, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-23 20:43:17', '2026-04-23 20:43:17', NULL, NULL),
(1128, 215, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-23 20:43:27', '2026-04-23 20:43:27', NULL, NULL),
(1129, 215, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-23 20:43:31', '2026-04-23 20:43:31', NULL, NULL),
(1130, 215, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-23 20:43:32', '2026-04-23 20:43:32', NULL, NULL),
(1131, 215, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-23 20:43:34', '2026-04-23 20:43:34', NULL, NULL),
(1132, 214, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Sim', 'boolean', '2026-04-23 20:43:46', '2026-04-23 20:43:46', NULL, NULL),
(1133, 214, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-23 20:43:48', '2026-04-23 20:43:48', NULL, NULL),
(1134, 214, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-23 20:43:50', '2026-04-23 20:43:50', NULL, NULL),
(1135, 214, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-23 20:43:57', '2026-04-23 20:43:57', NULL, NULL),
(1136, 215, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-23 20:44:24', '2026-04-23 20:44:24', NULL, NULL),
(1137, 215, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-23 20:44:29', '2026-04-23 20:44:29', NULL, NULL),
(1138, 214, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-23 20:44:31', '2026-04-23 20:44:31', NULL, NULL),
(1139, 214, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Sim', 'boolean', '2026-04-23 20:44:34', '2026-04-23 20:44:34', NULL, NULL),
(1140, 214, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-23 20:44:36', '2026-04-23 20:44:36', NULL, NULL),
(1141, 214, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-23 20:44:38', '2026-04-23 20:44:38', NULL, NULL),
(1142, 215, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-23 20:44:43', '2026-04-23 20:44:43', NULL, NULL),
(1143, 215, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-23 20:44:45', '2026-04-23 20:44:45', NULL, NULL),
(1144, 215, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-23 20:44:47', '2026-04-23 20:44:47', NULL, NULL),
(1145, 214, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Nada', 'text', '2026-04-23 20:45:10', '2026-04-23 20:45:10', NULL, NULL),
(1146, 215, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Sim', 'boolean', '2026-04-23 20:45:14', '2026-04-23 20:45:14', NULL, NULL),
(1147, 215, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2026-04-23 20:45:15', '2026-04-23 20:45:15', NULL, NULL),
(1148, 215, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'Plasil ev', 'text', '2026-04-23 20:45:31', '2026-04-23 20:45:31', NULL, NULL),
(1149, 215, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-04-23 20:45:55', '2026-04-23 20:45:55', NULL, NULL),
(1150, 215, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-23 20:45:57', '2026-04-23 20:45:57', NULL, NULL),
(1151, 215, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Oooo', 'text', '2026-04-23 20:46:01', '2026-04-23 20:46:01', NULL, NULL),
(1152, 215, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-23 20:46:05', '2026-04-23 20:46:05', NULL, NULL),
(1153, 214, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-23 20:46:12', '2026-04-23 20:46:12', NULL, NULL),
(1154, 214, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-23 20:46:13', '2026-04-23 20:46:13', NULL, NULL),
(1155, 214, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-23 20:46:32', '2026-04-23 20:46:32', NULL, NULL),
(1156, 214, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-23 20:46:36', '2026-04-23 20:46:36', NULL, NULL),
(1157, 215, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-23 20:46:36', '2026-04-23 20:46:36', NULL, NULL),
(1158, 215, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-04-23 20:46:37', '2026-04-23 20:46:37', NULL, NULL),
(1159, 215, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '2', 'text', '2026-04-23 20:46:40', '2026-04-23 20:46:40', NULL, NULL),
(1160, 215, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2026-04-23 20:46:44', '2026-04-23 20:46:44', NULL, NULL),
(1161, 214, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-23 20:46:57', '2026-04-23 20:46:57', NULL, NULL),
(1162, 214, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-23 20:46:59', '2026-04-23 20:46:59', NULL, NULL),
(1163, 214, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-23 20:47:15', '2026-04-23 20:47:15', NULL, NULL),
(1164, 214, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-23 20:47:16', '2026-04-23 20:47:16', NULL, NULL),
(1165, 214, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-04-23 20:47:20', '2026-04-23 20:47:20', NULL, NULL),
(1166, 215, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-23 20:47:21', '2026-04-23 20:47:21', NULL, NULL),
(1167, 215, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-23 20:47:26', '2026-04-23 20:47:26', NULL, NULL),
(1168, 214, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-23 20:47:49', '2026-04-23 20:47:49', NULL, NULL),
(1169, 214, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2026-04-23 20:47:52', '2026-04-23 20:47:52', NULL, NULL),
(1170, 214, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'Cefalexina', 'text', '2026-04-23 20:48:07', '2026-04-23 20:48:07', NULL, NULL),
(1171, 215, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-04-23 20:48:09', '2026-04-23 20:48:09', NULL, NULL),
(1172, 214, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-04-23 20:48:39', '2026-04-23 20:48:39', NULL, NULL),
(1173, 214, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-23 20:48:43', '2026-04-23 20:48:43', NULL, NULL),
(1174, 214, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Venvance', 'text', '2026-04-23 20:48:49', '2026-04-23 20:48:49', NULL, NULL),
(1175, 214, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-23 20:48:51', '2026-04-23 20:48:51', NULL, NULL),
(1176, 215, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Tempo de Protrombina\",\"KPTT\",\"Eletrocardiograma\",\"Glicemia de Jejum\"]', 'checkbox', '2026-04-23 20:49:03', '2026-04-23 20:49:03', NULL, NULL),
(1177, 214, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-23 20:50:28', '2026-04-23 20:50:28', NULL, NULL),
(1178, 214, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-04-23 20:50:31', '2026-04-23 20:50:31', NULL, NULL),
(1179, 214, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2026-04-23 20:50:32', '2026-04-23 20:50:32', NULL, NULL),
(1180, 214, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-23 20:50:58', '2026-04-23 20:50:58', NULL, NULL),
(1181, 214, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-23 20:51:03', '2026-04-23 20:51:03', NULL, NULL),
(1182, 214, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-04-23 20:52:53', '2026-04-23 20:52:53', NULL, NULL),
(1183, 214, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Glicemia de Jejum\"]', 'checkbox', '2026-04-23 20:53:42', '2026-04-23 20:53:42', NULL, NULL),
(1184, 217, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-29 11:22:49', '2026-04-29 11:22:49', NULL, NULL),
(1185, 219, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-29 11:22:35', '2026-04-29 11:22:35', NULL, NULL),
(1186, 219, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-29 11:23:17', '2026-04-29 11:23:17', NULL, NULL),
(1187, 217, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-04-29 11:23:36', '2026-04-29 11:23:36', NULL, NULL),
(1188, 219, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, '.', 'text', '2026-04-29 11:23:37', '2026-04-29 11:23:37', NULL, NULL),
(1189, 217, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Nenhum problema', 'text', '2026-04-29 11:23:45', '2026-04-29 11:23:45', NULL, NULL),
(1190, 219, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-29 11:24:03', '2026-04-29 11:24:03', NULL, NULL),
(1192, 219, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-29 11:24:05', '2026-04-29 11:24:05', NULL, NULL),
(1193, 217, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-29 11:24:07', '2026-04-29 11:24:07', NULL, NULL),
(1194, 217, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-29 11:24:08', '2026-04-29 11:24:08', NULL, NULL),
(1195, 217, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-29 11:24:09', '2026-04-29 11:24:09', NULL, NULL),
(1196, 219, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-29 11:24:10', '2026-04-29 11:24:10', NULL, NULL),
(1197, 217, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-29 11:24:11', '2026-04-29 11:24:11', NULL, NULL),
(1198, 219, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-29 11:24:13', '2026-04-29 11:24:13', NULL, NULL),
(1199, 217, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-29 11:24:34', '2026-04-29 11:24:34', NULL, NULL),
(1200, 219, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-29 11:24:34', '2026-04-29 11:24:34', NULL, NULL),
(1201, 219, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-29 11:24:35', '2026-04-29 11:24:35', NULL, NULL),
(1202, 217, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-29 11:24:36', '2026-04-29 11:24:36', NULL, NULL),
(1204, 217, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-29 11:24:38', '2026-04-29 11:24:38', NULL, NULL),
(1205, 217, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-29 11:24:39', '2026-04-29 11:24:39', NULL, NULL),
(1206, 219, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-29 11:24:42', '2026-04-29 11:24:42', NULL, NULL),
(1207, 219, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-29 11:24:43', '2026-04-29 11:24:43', NULL, NULL),
(1209, 217, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-29 11:25:14', '2026-04-29 11:25:14', NULL, NULL),
(1210, 217, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-29 11:25:14', '2026-04-29 11:25:14', NULL, NULL),
(1212, 217, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-29 11:25:16', '2026-04-29 11:25:16', NULL, NULL),
(1213, 219, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-29 11:25:16', '2026-04-29 11:25:16', NULL, NULL),
(1214, 217, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-29 11:25:17', '2026-04-29 11:25:17', NULL, NULL),
(1216, 219, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-29 11:25:18', '2026-04-29 11:25:18', NULL, NULL),
(1218, 219, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-29 11:25:19', '2026-04-29 11:25:19', NULL, NULL),
(1220, 219, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-29 11:25:22', '2026-04-29 11:25:22', NULL, NULL),
(1221, 217, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Desconheço', 'text', '2026-04-29 11:25:39', '2026-04-29 11:25:39', NULL, NULL),
(1226, 219, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, '.', 'text', '2026-04-29 11:25:46', '2026-04-29 11:25:46', NULL, NULL),
(1227, 217, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-29 11:26:01', '2026-04-29 11:26:01', NULL, NULL),
(1228, 219, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-29 11:26:02', '2026-04-29 11:26:02', NULL, NULL),
(1229, 217, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-29 11:26:05', '2026-04-29 11:26:05', NULL, NULL),
(1230, 219, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-29 11:26:05', '2026-04-29 11:26:05', NULL, NULL),
(1231, 217, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-29 11:26:06', '2026-04-29 11:26:06', NULL, NULL),
(1232, 219, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-29 11:26:06', '2026-04-29 11:26:06', NULL, NULL),
(1233, 219, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-29 11:26:08', '2026-04-29 11:26:08', NULL, NULL),
(1234, 217, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-29 11:26:09', '2026-04-29 11:26:09', NULL, NULL),
(1239, 217, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-29 11:26:30', '2026-04-29 11:26:30', NULL, NULL),
(1240, 217, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-29 11:26:34', '2026-04-29 11:26:34', NULL, NULL),
(1241, 219, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-29 11:26:35', '2026-04-29 11:26:35', NULL, NULL),
(1242, 219, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-29 11:26:37', '2026-04-29 11:26:37', NULL, NULL),
(1243, 220, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-04-29 11:32:47', '2026-04-29 11:32:47', NULL, NULL),
(1245, 217, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-29 11:26:49', '2026-04-29 11:26:49', NULL, NULL),
(1246, 217, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-29 11:26:50', '2026-04-29 11:26:50', NULL, NULL),
(1247, 219, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-29 11:26:53', '2026-04-29 11:26:53', NULL, NULL),
(1248, 219, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-29 11:26:54', '2026-04-29 11:26:54', NULL, NULL),
(1249, 217, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-29 11:26:54', '2026-04-29 11:26:54', NULL, NULL),
(1250, 219, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-29 11:26:57', '2026-04-29 11:26:57', NULL, NULL),
(1255, 217, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-29 11:27:22', '2026-04-29 11:27:22', NULL, NULL),
(1257, 217, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-04-29 11:27:26', '2026-04-29 11:27:26', NULL, NULL),
(1259, 219, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-29 11:27:28', '2026-04-29 11:27:28', NULL, NULL),
(1260, 220, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2026-04-29 11:33:29', '2026-04-29 11:33:29', NULL, NULL),
(1261, 219, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2026-04-29 11:27:30', '2026-04-29 11:27:30', NULL, NULL),
(1262, 219, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'Plasil EV', 'text', '2026-04-29 11:27:40', '2026-04-29 11:27:40', NULL, NULL),
(1266, 217, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-04-29 11:27:46', '2026-04-29 11:27:46', NULL, NULL),
(1267, 217, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-29 11:27:47', '2026-04-29 11:27:47', NULL, NULL),
(1268, 220, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-04-29 11:34:20', '2026-04-29 11:34:20', NULL, NULL),
(1269, 220, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-04-29 11:34:21', '2026-04-29 11:34:21', NULL, NULL),
(1270, 220, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-04-29 11:34:22', '2026-04-29 11:34:22', NULL, NULL),
(1271, 220, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-04-29 11:34:23', '2026-04-29 11:34:23', NULL, NULL),
(1272, 219, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-04-29 11:27:58', '2026-04-29 11:27:58', NULL, NULL),
(1273, 219, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-04-29 11:28:00', '2026-04-29 11:28:00', NULL, NULL),
(1274, 219, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, '.', 'text', '2026-04-29 11:28:04', '2026-04-29 11:28:04', NULL, NULL),
(1275, 219, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-29 11:28:06', '2026-04-29 11:28:06', NULL, NULL),
(1278, 217, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Mecobê', 'text', '2026-04-29 11:28:11', '2026-04-29 11:28:11', NULL, NULL),
(1279, 217, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-29 11:28:14', '2026-04-29 11:28:14', NULL, NULL),
(1283, 219, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-29 11:28:38', '2026-04-29 11:28:38', NULL, NULL),
(1284, 219, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-04-29 11:28:40', '2026-04-29 11:28:40', NULL, NULL),
(1285, 219, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-04-29 11:28:47', '2026-04-29 11:28:47', NULL, NULL),
(1286, 217, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-29 11:28:48', '2026-04-29 11:28:48', NULL, NULL),
(1287, 217, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-04-29 11:28:50', '2026-04-29 11:28:50', NULL, NULL),
(1288, 217, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-04-29 11:28:51', '2026-04-29 11:28:51', NULL, NULL),
(1292, 219, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-29 11:29:12', '2026-04-29 11:29:12', NULL, NULL),
(1293, 217, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-29 11:29:15', '2026-04-29 11:29:15', NULL, NULL),
(1294, 217, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-29 11:29:17', '2026-04-29 11:29:17', NULL, NULL),
(1295, 219, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-29 11:29:39', '2026-04-29 11:29:39', NULL, NULL),
(1298, 217, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe IV', 'choice', '2026-04-29 11:29:42', '2026-04-29 11:29:42', NULL, NULL),
(1299, 220, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-04-29 11:34:46', '2026-04-29 11:34:46', NULL, NULL),
(1300, 220, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-04-29 11:34:47', '2026-04-29 11:34:47', NULL, NULL),
(1301, 220, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-04-29 11:34:48', '2026-04-29 11:34:48', NULL, NULL),
(1302, 220, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-04-29 11:34:49', '2026-04-29 11:34:49', NULL, NULL),
(1303, 219, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-04-29 11:30:05', '2026-04-29 11:30:05', NULL, NULL),
(1305, 217, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Glicemia de Jejum\"]', 'checkbox', '2026-04-29 11:30:32', '2026-04-29 11:30:32', NULL, NULL),
(1307, 219, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Tempo de Protrombina\",\"KPTT\",\"Eletrocardiograma\",\"Glicemia de Jejum\"]', 'checkbox', '2026-04-29 11:31:02', '2026-04-29 11:31:02', NULL, NULL),
(1308, 220, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-04-29 11:35:21', '2026-04-29 11:35:21', NULL, NULL),
(1309, 220, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-04-29 11:35:23', '2026-04-29 11:35:23', NULL, NULL),
(1310, 220, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-04-29 11:35:24', '2026-04-29 11:35:24', NULL, NULL),
(1311, 220, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-04-29 11:35:25', '2026-04-29 11:35:25', NULL, NULL),
(1312, 220, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Alergia respiratória', 'text', '2026-04-29 11:35:51', '2026-04-29 11:35:51', NULL, NULL),
(1313, 220, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-04-29 11:36:32', '2026-04-29 11:36:32', NULL, NULL),
(1314, 220, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-04-29 11:36:33', '2026-04-29 11:36:33', NULL, NULL),
(1315, 220, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-04-29 11:36:33', '2026-04-29 11:36:33', NULL, NULL),
(1316, 220, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-04-29 11:36:36', '2026-04-29 11:36:36', NULL, NULL),
(1317, 220, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-04-29 11:37:01', '2026-04-29 11:37:01', NULL, NULL),
(1318, 220, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-04-29 11:37:04', '2026-04-29 11:37:04', NULL, NULL),
(1319, 220, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-04-29 11:37:19', '2026-04-29 11:37:19', NULL, NULL),
(1320, 220, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-04-29 11:37:20', '2026-04-29 11:37:20', NULL, NULL),
(1321, 220, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-04-29 11:37:21', '2026-04-29 11:37:21', NULL, NULL),
(1322, 220, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-04-29 11:37:52', '2026-04-29 11:37:52', NULL, NULL),
(1323, 220, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-04-29 11:38:13', '2026-04-29 11:38:13', NULL, NULL),
(1324, 220, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-04-29 11:38:32', '2026-04-29 11:38:32', NULL, NULL),
(1325, 220, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2026-04-29 11:38:33', '2026-04-29 11:38:33', NULL, NULL),
(1326, 220, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-04-29 11:38:34', '2026-04-29 11:38:34', NULL, NULL),
(1327, 220, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-04-29 11:39:07', '2026-04-29 11:39:07', NULL, NULL),
(1328, 220, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-04-29 11:39:09', '2026-04-29 11:39:09', NULL, NULL),
(1329, 220, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-04-29 11:39:10', '2026-04-29 11:39:10', NULL, NULL),
(1330, 220, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-04-29 11:39:35', '2026-04-29 11:39:35', NULL, NULL),
(1331, 220, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-04-29 11:39:37', '2026-04-29 11:39:37', NULL, NULL),
(1332, 220, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-04-29 11:40:05', '2026-04-29 11:40:05', NULL, NULL),
(1333, 220, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Eletrocardiograma\",\"Glicemia de Jejum\"]', 'checkbox', '2026-04-29 11:41:03', '2026-04-29 11:41:03', NULL, NULL),
(1334, 227, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-06-19 23:34:12', '2026-06-19 23:34:12', NULL, NULL),
(1335, 227, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2026-06-19 23:34:43', '2026-06-19 23:34:43', NULL, NULL),
(1336, 227, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-06-19 23:35:06', '2026-06-19 23:35:06', NULL, NULL),
(1337, 227, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-06-19 23:35:06', '2026-06-19 23:35:06', NULL, NULL),
(1338, 227, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-06-19 23:35:07', '2026-06-19 23:35:07', NULL, NULL),
(1339, 227, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-06-19 23:35:08', '2026-06-19 23:35:08', NULL, NULL),
(1340, 227, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-06-19 23:35:32', '2026-06-19 23:35:32', NULL, NULL),
(1341, 227, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-06-19 23:35:33', '2026-06-19 23:35:33', NULL, NULL),
(1342, 227, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-06-19 23:35:33', '2026-06-19 23:35:33', NULL, NULL),
(1343, 227, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-06-19 23:35:34', '2026-06-19 23:35:34', NULL, NULL),
(1344, 227, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-06-19 23:36:15', '2026-06-19 23:36:15', NULL, NULL),
(1345, 227, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-06-19 23:36:16', '2026-06-19 23:36:16', NULL, NULL),
(1346, 227, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-06-19 23:36:16', '2026-06-19 23:36:16', NULL, NULL),
(1347, 227, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-06-19 23:36:16', '2026-06-19 23:36:16', NULL, NULL),
(1348, 227, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, '8888', 'text', '2026-06-19 23:36:35', '2026-06-19 23:36:35', NULL, NULL),
(1349, 227, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-06-19 23:37:20', '2026-06-19 23:37:20', NULL, NULL),
(1350, 227, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-06-19 23:37:20', '2026-06-19 23:37:20', NULL, NULL),
(1351, 227, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-06-19 23:37:20', '2026-06-19 23:37:20', NULL, NULL),
(1352, 227, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-06-19 23:37:21', '2026-06-19 23:37:21', NULL, NULL),
(1353, 227, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-06-19 23:38:20', '2026-06-19 23:38:20', NULL, NULL),
(1354, 227, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-06-19 23:38:20', '2026-06-19 23:38:20', NULL, NULL),
(1355, 227, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2026-06-19 23:38:47', '2026-06-19 23:38:47', NULL, NULL);
INSERT INTO `paciente_video_respostas` (`id`, `paciente_id`, `video_id`, `video_title`, `video_ordem`, `question_id`, `question_index`, `question_text`, `question_title`, `answer`, `answer_type`, `created_at`, `updated_at`, `ip_address`, `user_agent`) VALUES
(1356, 227, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-06-19 23:38:48', '2026-06-19 23:38:48', NULL, NULL),
(1357, 227, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-06-19 23:38:48', '2026-06-19 23:38:48', NULL, NULL),
(1358, 227, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-06-19 23:39:13', '2026-06-19 23:39:13', NULL, NULL),
(1359, 227, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-06-19 23:39:13', '2026-06-19 23:39:13', NULL, NULL),
(1360, 227, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-06-19 23:39:36', '2026-06-19 23:39:36', NULL, NULL),
(1361, 227, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2026-06-19 23:39:36', '2026-06-19 23:39:36', NULL, NULL),
(1362, 227, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-06-19 23:39:36', '2026-06-19 23:39:36', NULL, NULL),
(1363, 227, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-06-19 23:40:14', '2026-06-19 23:40:14', NULL, NULL),
(1364, 227, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-06-19 23:40:14', '2026-06-19 23:40:14', NULL, NULL),
(1365, 227, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-06-19 23:40:14', '2026-06-19 23:40:14', NULL, NULL),
(1366, 227, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-06-19 23:40:36', '2026-06-19 23:40:36', NULL, NULL),
(1367, 227, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Não', 'boolean', '2026-06-19 23:40:37', '2026-06-19 23:40:37', NULL, NULL),
(1368, 227, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-06-19 23:40:57', '2026-06-19 23:40:57', NULL, NULL),
(1369, 227, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Eletrocardiograma\"]', 'checkbox', '2026-06-19 23:54:45', '2026-06-19 23:54:45', NULL, NULL),
(1370, 232, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-06-25 12:16:20', '2026-06-25 12:16:20', NULL, NULL),
(1371, 232, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2026-06-25 12:17:10', '2026-06-25 12:17:10', NULL, NULL),
(1372, 232, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-06-25 12:17:36', '2026-06-25 12:17:36', NULL, NULL),
(1373, 232, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-06-25 12:17:40', '2026-06-25 12:17:40', NULL, NULL),
(1374, 232, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-06-25 12:17:42', '2026-06-25 12:17:42', NULL, NULL),
(1375, 232, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-06-25 12:17:46', '2026-06-25 12:17:46', NULL, NULL),
(1376, 232, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-06-25 12:18:11', '2026-06-25 12:18:11', NULL, NULL),
(1377, 232, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-06-25 12:18:15', '2026-06-25 12:18:15', NULL, NULL),
(1378, 232, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-06-25 12:18:22', '2026-06-25 12:18:22', NULL, NULL),
(1379, 232, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-06-25 12:18:25', '2026-06-25 12:18:25', NULL, NULL),
(1380, 232, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-06-25 12:19:01', '2026-06-25 12:19:01', NULL, NULL),
(1381, 232, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-06-25 12:19:05', '2026-06-25 12:19:05', NULL, NULL),
(1382, 232, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-06-25 12:19:13', '2026-06-25 12:19:13', NULL, NULL),
(1383, 232, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-06-25 12:19:17', '2026-06-25 12:19:17', NULL, NULL),
(1384, 232, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Nenhuma doença que eu saiba.', 'text', '2026-06-25 12:20:08', '2026-06-25 12:20:08', NULL, NULL),
(1385, 232, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-06-25 12:20:28', '2026-06-25 12:20:28', NULL, NULL),
(1386, 232, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-06-25 12:20:31', '2026-06-25 12:20:31', NULL, NULL),
(1387, 232, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-06-25 12:20:34', '2026-06-25 12:20:34', NULL, NULL),
(1388, 232, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-06-25 12:20:45', '2026-06-25 12:20:45', NULL, NULL),
(1389, 232, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-06-25 12:21:11', '2026-06-25 12:21:11', NULL, NULL),
(1390, 232, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-06-25 12:21:16', '2026-06-25 12:21:16', NULL, NULL),
(1391, 232, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Não', 'boolean', '2026-06-25 12:21:34', '2026-06-25 12:21:34', NULL, NULL),
(1392, 232, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-06-25 12:21:38', '2026-06-25 12:21:38', NULL, NULL),
(1393, 232, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Não', 'boolean', '2026-06-25 12:21:45', '2026-06-25 12:21:45', NULL, NULL),
(1394, 232, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-06-25 12:22:18', '2026-06-25 12:22:18', NULL, NULL),
(1395, 232, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-06-25 12:22:22', '2026-06-25 12:22:22', NULL, NULL),
(1396, 232, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-06-25 12:23:22', '2026-06-25 12:23:22', NULL, NULL),
(1397, 232, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Não', 'boolean', '2026-06-25 12:23:27', '2026-06-25 12:23:27', NULL, NULL),
(1398, 232, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-06-25 12:23:32', '2026-06-25 12:23:32', NULL, NULL),
(1399, 232, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-06-25 12:24:09', '2026-06-25 12:24:09', NULL, NULL),
(1400, 232, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-06-25 12:24:15', '2026-06-25 12:24:15', NULL, NULL),
(1401, 232, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-06-25 12:24:20', '2026-06-25 12:24:20', NULL, NULL),
(1402, 232, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-06-25 12:24:50', '2026-06-25 12:24:50', NULL, NULL),
(1403, 232, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-06-25 12:25:05', '2026-06-25 12:25:05', NULL, NULL),
(1404, 232, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Tempo de Protrombina\",\"Creatinina\"]', 'checkbox', '2026-06-25 12:35:44', '2026-06-25 12:35:44', NULL, NULL),
(1405, 248, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-07-31 18:01:56', '2026-07-31 18:01:56', NULL, NULL),
(1406, 248, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2026-07-31 18:02:27', '2026-07-31 18:02:27', NULL, NULL),
(1407, 248, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-07-31 18:02:50', '2026-07-31 18:02:50', NULL, NULL),
(1408, 248, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-07-31 18:02:52', '2026-07-31 18:02:52', NULL, NULL),
(1409, 248, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Sim', 'boolean', '2026-07-31 18:02:53', '2026-07-31 18:02:53', NULL, NULL),
(1410, 248, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Sim', 'boolean', '2026-07-31 18:02:59', '2026-07-31 18:02:59', NULL, NULL),
(1411, 248, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-07-31 18:03:16', '2026-07-31 18:03:16', NULL, NULL),
(1412, 248, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-07-31 18:03:18', '2026-07-31 18:03:18', NULL, NULL),
(1413, 248, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Sim', 'boolean', '2026-07-31 18:03:19', '2026-07-31 18:03:19', NULL, NULL),
(1414, 248, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Sim', 'boolean', '2026-07-31 18:03:22', '2026-07-31 18:03:22', NULL, NULL),
(1415, 248, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-07-31 18:03:55', '2026-07-31 18:03:55', NULL, NULL),
(1416, 248, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-07-31 18:03:56', '2026-07-31 18:03:56', NULL, NULL),
(1417, 248, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-07-31 18:03:57', '2026-07-31 18:03:57', NULL, NULL),
(1418, 248, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-07-31 18:03:57', '2026-07-31 18:03:57', NULL, NULL),
(1419, 248, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'tuberculose', 'text', '2026-07-31 18:04:19', '2026-07-31 18:04:19', NULL, NULL),
(1420, 248, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-07-31 18:04:40', '2026-07-31 18:04:40', NULL, NULL),
(1421, 248, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-07-31 18:04:41', '2026-07-31 18:04:41', NULL, NULL),
(1422, 248, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-07-31 18:04:42', '2026-07-31 18:04:42', NULL, NULL),
(1423, 248, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-07-31 18:04:45', '2026-07-31 18:04:45', NULL, NULL),
(1424, 248, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Sim', 'boolean', '2026-07-31 18:05:40', '2026-07-31 18:05:40', NULL, NULL),
(1425, 248, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Sim', 'boolean', '2026-07-31 18:05:41', '2026-07-31 18:05:41', NULL, NULL),
(1426, 248, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2026-07-31 18:05:55', '2026-07-31 18:05:55', NULL, NULL),
(1427, 248, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Sim', 'boolean', '2026-07-31 18:05:55', '2026-07-31 18:05:55', NULL, NULL),
(1428, 248, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-07-31 18:05:56', '2026-07-31 18:05:56', NULL, NULL),
(1429, 248, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Sim', 'boolean', '2026-07-31 18:06:37', '2026-07-31 18:06:37', NULL, NULL),
(1430, 248, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Sim', 'boolean', '2026-07-31 18:06:37', '2026-07-31 18:06:37', NULL, NULL),
(1431, 248, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q3', 3, 'Qual alergia você tem?', NULL, 'sim', 'text', '2026-07-31 18:06:42', '2026-07-31 18:06:42', NULL, NULL),
(1432, 248, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-07-31 18:07:02', '2026-07-31 18:07:02', NULL, NULL),
(1433, 248, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-07-31 18:07:02', '2026-07-31 18:07:02', NULL, NULL),
(1434, 248, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'sim', 'text', '2026-07-31 18:07:05', '2026-07-31 18:07:05', NULL, NULL),
(1435, 248, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Sim', 'boolean', '2026-07-31 18:07:06', '2026-07-31 18:07:06', NULL, NULL),
(1436, 248, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-07-31 18:07:36', '2026-07-31 18:07:36', NULL, NULL),
(1437, 248, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-07-31 18:07:36', '2026-07-31 18:07:36', NULL, NULL),
(1438, 248, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, 'hehehe', 'text', '2026-07-31 18:07:40', '2026-07-31 18:07:40', NULL, NULL),
(1439, 248, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Sim', 'boolean', '2026-07-31 18:07:43', '2026-07-31 18:07:43', NULL, NULL),
(1440, 248, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Sim', 'boolean', '2026-07-31 18:08:09', '2026-07-31 18:08:09', NULL, NULL),
(1441, 248, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-07-31 18:08:11', '2026-07-31 18:08:11', NULL, NULL),
(1442, 248, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe I', 'choice', '2026-07-31 18:08:32', '2026-07-31 18:08:32', NULL, NULL),
(1443, 248, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Tempo de Protrombina\",\"KPTT\",\"Eletrocardiograma\",\"Glicemia de Jejum\",\"Creatinina\"]', 'checkbox', '2026-07-31 18:09:17', '2026-07-31 18:09:17', NULL, NULL),
(1444, 250, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-08-03 17:24:23', '2026-08-03 17:24:23', NULL, NULL),
(1445, 250, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-08-03 17:24:56', '2026-08-03 17:24:56', NULL, NULL),
(1446, 250, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Nunca tive problemas com anestesias ou cirúrgicos', 'text', '2026-08-03 17:25:19', '2026-08-03 17:25:19', NULL, NULL),
(1447, 250, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-08-03 17:25:42', '2026-08-03 17:25:42', NULL, NULL),
(1448, 250, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-08-03 17:25:45', '2026-08-03 17:25:45', NULL, NULL),
(1449, 250, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-08-03 17:25:47', '2026-08-03 17:25:47', NULL, NULL),
(1450, 250, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-08-03 17:25:49', '2026-08-03 17:25:49', NULL, NULL),
(1451, 250, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-08-03 17:26:10', '2026-08-03 17:26:10', NULL, NULL),
(1452, 250, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-08-03 17:26:12', '2026-08-03 17:26:12', NULL, NULL),
(1453, 250, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-08-03 17:26:14', '2026-08-03 17:26:14', NULL, NULL),
(1454, 250, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-08-03 17:26:15', '2026-08-03 17:26:15', NULL, NULL),
(1455, 250, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-08-03 17:26:49', '2026-08-03 17:26:49', NULL, NULL),
(1456, 250, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Sim', 'boolean', '2026-08-03 17:26:50', '2026-08-03 17:26:50', NULL, NULL),
(1457, 250, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-08-03 17:26:53', '2026-08-03 17:26:53', NULL, NULL),
(1458, 250, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-08-03 17:26:54', '2026-08-03 17:26:54', NULL, NULL),
(1459, 250, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Hipotireoidismo, câncer de mama com metástase óssea e gástrica, câncer ovário com metástase peritônio, refluxo, neoplasia periférica.', 'text', '2026-08-03 17:29:06', '2026-08-03 17:29:06', NULL, NULL),
(1460, 250, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-08-03 17:29:25', '2026-08-03 17:29:25', NULL, NULL),
(1461, 250, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2026-08-03 17:29:27', '2026-08-03 17:29:27', NULL, NULL),
(1462, 250, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Sim', 'boolean', '2026-08-03 17:29:35', '2026-08-03 17:29:35', NULL, NULL),
(1463, 250, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-08-03 17:30:08', '2026-08-03 17:30:08', NULL, NULL),
(1464, 250, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-08-03 17:30:10', '2026-08-03 17:30:10', NULL, NULL),
(1465, 250, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2026-08-03 17:30:39', '2026-08-03 17:30:39', NULL, NULL),
(1466, 250, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-08-03 17:31:21', '2026-08-03 17:31:21', NULL, NULL),
(1467, 250, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-08-03 17:32:00', '2026-08-03 17:32:00', NULL, NULL),
(1468, 250, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-08-03 17:32:01', '2026-08-03 17:32:01', NULL, NULL),
(1469, 250, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-08-03 17:32:21', '2026-08-03 17:32:21', NULL, NULL),
(1470, 250, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-08-03 17:32:24', '2026-08-03 17:32:24', NULL, NULL),
(1471, 250, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Zezulah, Puran 112, dual, Coledue, plasil, domperidona, prebictal, reconter, forxiga, dexilant 60.', 'text', '2026-08-03 17:38:53', '2026-08-03 17:38:53', NULL, NULL),
(1472, 250, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-08-03 17:38:56', '2026-08-03 17:38:56', NULL, NULL),
(1473, 250, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-08-03 17:39:27', '2026-08-03 17:39:27', NULL, NULL),
(1474, 250, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-08-03 17:39:29', '2026-08-03 17:39:29', NULL, NULL),
(1475, 250, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '8 kg', 'text', '2026-08-03 17:39:42', '2026-08-03 17:39:42', NULL, NULL),
(1476, 250, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-08-03 17:39:45', '2026-08-03 17:39:45', NULL, NULL),
(1477, 250, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-08-03 17:40:10', '2026-08-03 17:40:10', NULL, NULL),
(1478, 250, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-08-03 17:40:16', '2026-08-03 17:40:16', NULL, NULL),
(1479, 250, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe II', 'choice', '2026-08-03 17:41:21', '2026-08-03 17:41:21', NULL, NULL),
(1480, 250, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Glicemia de Jejum\",\"Eletrocardiograma\"]', 'checkbox', '2026-08-03 17:43:25', '2026-08-03 17:43:25', NULL, NULL),
(1481, 252, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-08-09 16:22:16', '2026-08-09 16:22:16', NULL, NULL),
(1482, 252, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Não', 'boolean', '2026-08-09 16:22:46', '2026-08-09 16:22:46', NULL, NULL),
(1483, 252, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Não', 'boolean', '2026-08-09 16:23:28', '2026-08-09 16:23:28', NULL, NULL),
(1484, 252, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-08-09 16:23:32', '2026-08-09 16:23:32', NULL, NULL),
(1485, 252, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-08-09 16:23:36', '2026-08-09 16:23:36', NULL, NULL),
(1486, 252, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-08-09 16:23:40', '2026-08-09 16:23:40', NULL, NULL),
(1487, 252, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-08-09 16:24:04', '2026-08-09 16:24:04', NULL, NULL),
(1488, 252, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-08-09 16:24:08', '2026-08-09 16:24:08', NULL, NULL),
(1489, 252, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-08-09 16:24:13', '2026-08-09 16:24:13', NULL, NULL),
(1490, 252, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-08-09 16:24:16', '2026-08-09 16:24:16', NULL, NULL),
(1491, 252, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-08-09 16:24:51', '2026-08-09 16:24:51', NULL, NULL),
(1492, 252, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-08-09 16:24:55', '2026-08-09 16:24:55', NULL, NULL),
(1493, 252, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-08-09 16:25:02', '2026-08-09 16:25:02', NULL, NULL),
(1494, 252, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-08-09 16:25:06', '2026-08-09 16:25:06', NULL, NULL),
(1495, 252, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Recentemente fiz cirurgia para retirada de um tumor no intestino, em 07/04.\r\nTenho transtorno bipolar \r\nEste ano tive problemas vaculares, o que ocasionaram em mini avcs', 'text', '2026-08-09 16:28:12', '2026-08-09 16:28:12', NULL, NULL),
(1496, 252, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-08-09 16:28:36', '2026-08-09 16:28:36', NULL, NULL),
(1497, 252, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Sim', 'boolean', '2026-08-09 16:28:38', '2026-08-09 16:28:38', NULL, NULL),
(1498, 252, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Não', 'boolean', '2026-08-09 16:28:41', '2026-08-09 16:28:41', NULL, NULL),
(1499, 252, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-08-09 16:28:49', '2026-08-09 16:28:49', NULL, NULL),
(1500, 252, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-08-09 16:29:12', '2026-08-09 16:29:12', NULL, NULL),
(1501, 252, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-08-09 16:29:18', '2026-08-09 16:29:18', NULL, NULL),
(1502, 252, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2026-08-09 16:29:33', '2026-08-09 16:29:33', NULL, NULL),
(1503, 252, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Sim', 'boolean', '2026-08-09 16:29:36', '2026-08-09 16:29:36', NULL, NULL),
(1504, 252, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-08-09 16:29:39', '2026-08-09 16:29:39', NULL, NULL),
(1505, 252, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-08-09 16:30:19', '2026-08-09 16:30:19', NULL, NULL),
(1506, 252, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-08-09 16:31:06', '2026-08-09 16:31:06', NULL, NULL),
(1507, 252, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Não', 'boolean', '2026-08-09 16:31:34', '2026-08-09 16:31:34', NULL, NULL),
(1508, 252, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-08-09 16:31:37', '2026-08-09 16:31:37', NULL, NULL),
(1509, 252, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Copidogrel\r\nBesilato de anlodipino\r\nRosovastatina 20mg', 'text', '2026-08-09 16:33:10', '2026-08-09 16:33:10', NULL, NULL),
(1510, 252, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-08-09 16:33:25', '2026-08-09 16:33:25', NULL, NULL),
(1511, 252, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Sim', 'boolean', '2026-08-09 16:33:59', '2026-08-09 16:33:59', NULL, NULL),
(1512, 252, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Sim', 'boolean', '2026-08-09 16:34:03', '2026-08-09 16:34:03', NULL, NULL),
(1513, 252, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q3', 3, 'Caso tenha perdido peso, quanto quilos perdeu?', NULL, '20kg', 'text', '2026-08-09 16:34:09', '2026-08-09 16:34:09', NULL, NULL),
(1514, 252, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-08-09 16:34:12', '2026-08-09 16:34:12', NULL, NULL),
(1515, 252, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-08-09 16:34:42', '2026-08-09 16:34:42', NULL, NULL),
(1516, 252, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-08-09 16:35:14', '2026-08-09 16:35:14', NULL, NULL),
(1517, 252, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe III', 'choice', '2026-08-09 16:35:49', '2026-08-09 16:35:49', NULL, NULL),
(1518, 252, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Hemograma\",\"Creatinina\",\"Tempo de Protrombina\"]', 'checkbox', '2026-08-09 16:37:15', '2026-08-09 16:37:15', NULL, NULL),
(1519, 254, 'video_1', 'Vídeo 1 - Introdução', NULL, 'v1_q1', 1, 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?', NULL, 'Sim', 'boolean', '2026-08-14 23:26:36', '2026-08-14 23:26:36', NULL, NULL),
(1520, 254, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q1', 1, 'Você realizou algum procedimento anterior?', NULL, 'Sim', 'boolean', '2026-08-14 23:27:06', '2026-08-14 23:27:06', NULL, NULL),
(1521, 254, 'video_2', 'Vídeo 2 - Histórico Clínico', NULL, 'v2_q2', 2, 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.', NULL, 'Nenhum problema!', 'text', '2026-08-14 23:27:21', '2026-08-14 23:27:21', NULL, NULL),
(1522, 254, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q1', 1, 'Você tem pressão alta?', NULL, 'Sim', 'boolean', '2026-08-14 23:27:44', '2026-08-14 23:27:44', NULL, NULL),
(1523, 254, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q2', 2, 'Você sente dor no peito?', NULL, 'Não', 'boolean', '2026-08-14 23:27:45', '2026-08-14 23:27:45', NULL, NULL),
(1524, 254, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q3', 3, 'Você já teve infarto?', NULL, 'Não', 'boolean', '2026-08-14 23:27:47', '2026-08-14 23:27:47', NULL, NULL),
(1525, 254, 'video_3', 'Vídeo 3 - Avaliação Cardiovascular', NULL, 'v3_q4', 4, 'Você já precisou colocar molinhas no coração?', NULL, 'Não', 'boolean', '2026-08-14 23:27:48', '2026-08-14 23:27:48', NULL, NULL),
(1526, 254, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q1', 1, 'Você sente palpitação ou arritmia como descrito?', NULL, 'Não', 'boolean', '2026-08-14 23:28:08', '2026-08-14 23:28:08', NULL, NULL),
(1527, 254, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q2', 2, 'Você já teve desmaio no último mês?', NULL, 'Não', 'boolean', '2026-08-14 23:28:11', '2026-08-14 23:28:11', NULL, NULL),
(1528, 254, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q3', 3, 'Você teve convulsão ou possui histórico de convulsão?', NULL, 'Não', 'boolean', '2026-08-14 23:28:12', '2026-08-14 23:28:12', NULL, NULL),
(1529, 254, 'video_4', 'Vídeo 4 - Ritmo Cardíaco e Sintomas', NULL, 'v4_q4', 4, 'Você usa marcapasso?', NULL, 'Não', 'boolean', '2026-08-14 23:28:13', '2026-08-14 23:28:13', NULL, NULL),
(1530, 254, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q1', 1, 'Você tem diabetes?', NULL, 'Não', 'boolean', '2026-08-14 23:33:29', '2026-08-14 23:33:29', NULL, NULL),
(1531, 254, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q2', 2, 'Você tem problema de tireoide?', NULL, 'Não', 'boolean', '2026-08-14 23:33:31', '2026-08-14 23:33:31', NULL, NULL),
(1532, 254, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q3', 3, 'Você tem problema de rim (hemodiálise ou insuficiência renal)?', NULL, 'Não', 'boolean', '2026-08-14 23:33:30', '2026-08-14 23:33:30', NULL, NULL),
(1533, 254, 'video_5', 'Vídeo 5 - Revisão Cardiológica', NULL, 'v5_q4', 4, 'Você já teve ou tem hepatite grave?', NULL, 'Não', 'boolean', '2026-08-14 23:33:33', '2026-08-14 23:33:33', NULL, NULL),
(1534, 254, 'video_6', 'Vídeo 6 - Outras Condições', NULL, 'v6_q1', 1, 'Me conte sobre outras doenças que você pode ter.', NULL, 'Bronquite asmática.\r\nTDHA.\r\nDepressão.', 'text', '2026-08-14 23:35:06', '2026-08-14 23:35:06', NULL, NULL),
(1535, 254, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q1', 1, 'Você fuma atualmente?', NULL, 'Não', 'boolean', '2026-08-14 23:37:39', '2026-08-14 23:37:39', NULL, NULL),
(1536, 254, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q2', 2, 'Você já fumou no passado?', NULL, 'Não', 'boolean', '2026-08-14 23:37:35', '2026-08-14 23:37:35', NULL, NULL),
(1537, 254, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q3', 3, 'Você tem asma ou bronquite?', NULL, 'Sim', 'boolean', '2026-08-14 23:37:35', '2026-08-14 23:37:35', NULL, NULL),
(1538, 254, 'video_7', 'Vídeo 7 - Avaliação Respiratória', NULL, 'v7_q4', 4, 'Você sente falta de ar em repouso ou ao subir mais de 3 lances de escada/caminhar mais de 200 metros?', NULL, 'Não', 'boolean', '2026-08-14 23:37:37', '2026-08-14 23:37:37', NULL, NULL),
(1539, 254, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q1', 1, 'No seu dia a dia, você tosse sempre?', NULL, 'Não', 'boolean', '2026-08-14 23:38:00', '2026-08-14 23:38:00', NULL, NULL),
(1540, 254, 'video_8', 'Vídeo 8 - Sintomas Respiratórios Recentes', NULL, 'v8_q2', 2, 'Você teve gripe ou febre nos últimos 14 dias?', NULL, 'Não', 'boolean', '2026-08-14 23:38:03', '2026-08-14 23:38:03', NULL, NULL),
(1541, 254, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q1', 1, 'Você tem alguma doença neurológica?', NULL, 'Sim', 'boolean', '2026-08-14 23:38:55', '2026-08-14 23:38:55', NULL, NULL),
(1542, 254, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q2', 2, 'Você já teve AVC (derrame)?', NULL, 'Não', 'boolean', '2026-08-14 23:38:55', '2026-08-14 23:38:55', NULL, NULL),
(1543, 254, 'video_9', 'Vídeo 9 - Avaliação Neurológica e Psiquiátrica', NULL, 'v9_q3', 3, 'Você tem alguma doença psiquiátrica (como depressão, ansiedade ou bipolaridade)?', NULL, 'Sim', 'boolean', '2026-08-14 23:38:59', '2026-08-14 23:38:59', NULL, NULL),
(1544, 254, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q1', 1, 'Você já teve sangramento excessivo (no dentista ou cirurgia)?', NULL, 'Não', 'boolean', '2026-08-14 23:39:26', '2026-08-14 23:39:26', NULL, NULL),
(1545, 254, 'video_10', 'Vídeo 10 - Sangramento e Alergias', NULL, 'v10_q2', 2, 'Você tem alguma alergia?', NULL, 'Não', 'boolean', '2026-08-14 23:39:29', '2026-08-14 23:39:29', NULL, NULL),
(1546, 254, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q1', 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', NULL, 'Sim', 'boolean', '2026-08-14 23:39:49', '2026-08-14 23:39:49', NULL, NULL),
(1547, 254, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q2', 2, 'Você usa algum medicamento regularmente, todos os dias?', NULL, 'Sim', 'boolean', '2026-08-14 23:39:51', '2026-08-14 23:39:51', NULL, NULL),
(1548, 254, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q3', 3, 'Caso você use algum medicamento, descreva:', NULL, 'Venvanse 70mg (dimesilato de lisdexanfetamina).\r\nZapsy 30mg (mirtazapina).\r\nAymee 20mg (cloridrato de vilazodona).\r\nEsomeprazol magnésico 40mg.\r\nPolivitamínico do complexo B.', 'text', '2026-08-14 23:42:20', '2026-08-14 23:42:20', NULL, NULL),
(1549, 254, 'video_11', 'Vídeo 11 - Capacidade Física e Medicamentos', NULL, 'v11_q4', 4, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras?', NULL, 'Não', 'boolean', '2026-08-14 23:42:22', '2026-08-14 23:42:22', NULL, NULL),
(1550, 254, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q4', 4, 'Faz uso de bebida alcoólica regularmente?', NULL, 'Não', 'boolean', '2026-08-14 23:42:59', '2026-08-14 23:42:59', NULL, NULL),
(1551, 254, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q2', 2, 'Nos últimos seis meses, você perdeu peso?', NULL, 'Não', 'boolean', '2026-08-14 23:42:58', '2026-08-14 23:42:58', NULL, NULL),
(1552, 254, 'video_12', 'Vídeo 12 - Histórico Oncológico e Hábitos', NULL, 'v12_q1', 1, 'Você já teve câncer?', NULL, 'Não', 'boolean', '2026-08-14 23:42:54', '2026-08-14 23:42:54', NULL, NULL),
(1553, 254, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q1', 1, 'Você usa prótese dentária, como dentadura ou chapa?', NULL, 'Não', 'boolean', '2026-08-14 23:43:28', '2026-08-14 23:43:28', NULL, NULL),
(1554, 254, 'video_13', 'Vídeo 13 - Avaliação Odontológica e Mobilidade', NULL, 'v13_q2', 2, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura?', NULL, 'Sim', 'boolean', '2026-08-14 23:43:27', '2026-08-14 23:43:27', NULL, NULL),
(1555, 254, 'video_14', 'Vídeo 14 - Classificação de Mallampati', NULL, 'v14_q1', 1, 'Com base na imagem mostrada no vídeo, qual classe melhor representa sua situação?', NULL, 'Classe III', 'choice', '2026-08-14 23:43:49', '2026-08-14 23:43:49', NULL, NULL),
(1556, 254, 'video_15', 'Vídeo 15 - Exames Disponíveis', NULL, 'v15_q1', 1, 'Quais os exames que você já tem em mãos válidos pelos últimos 6 meses?', NULL, '[\"Tempo de Protrombina\",\"Hemograma\",\"Creatinina\",\"KPTT\",\"Eletrocardiograma\"]', 'checkbox', '2026-08-14 23:46:48', '2026-08-14 23:46:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente_video_sessoes`
--

CREATE TABLE `paciente_video_sessoes` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `video_atual` varchar(50) DEFAULT NULL COMMENT 'ID do vídeo atual (ex: video_1)',
  `video_atual_ordem` int(11) DEFAULT 1 COMMENT 'Ordem do vídeo atual (1-15)',
  `total_videos` int(11) DEFAULT 15 COMMENT 'Total de vídeos na entrevista',
  `videos_completados` int(11) DEFAULT 0 COMMENT 'Quantidade de vídeos completados',
  `progresso_percentual` decimal(5,2) DEFAULT 0.00 COMMENT 'Progresso em percentual (0.00 - 100.00)',
  `status` enum('iniciada','em_andamento','pausada','concluida','abandonada') DEFAULT 'iniciada' COMMENT 'Status atual da sessão',
  `iniciada_em` timestamp NULL DEFAULT current_timestamp() COMMENT 'Quando a sessão foi iniciada',
  `ultima_atividade` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Última atividade do paciente',
  `concluida_em` timestamp NULL DEFAULT NULL COMMENT 'Quando a entrevista foi concluída',
  `tempo_total_segundos` int(11) DEFAULT 0 COMMENT 'Tempo total gasto em segundos',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `dispositivo` varchar(50) DEFAULT NULL COMMENT 'mobile, tablet, desktop',
  `navegador` varchar(50) DEFAULT NULL COMMENT 'Chrome, Firefox, Safari, etc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sessões de visualização dos vídeos pelos pacientes';

-- --------------------------------------------------------

--
-- Estrutura para tabela `paginas_sistema`
--

CREATE TABLE `paginas_sistema` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `rota` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `paginas_sistema`
--

INSERT INTO `paginas_sistema` (`id`, `nome`, `rota`, `descricao`, `ativo`, `created_at`, `ordem`) VALUES
(1, 'equipe_anestesistas', '/equipe-anestesistas', 'Equipe de Anestesistas', 1, '2025-09-28 20:26:02', 4),
(2, 'dashboard', '/dashboard', 'Dashboard', 1, '2025-09-28 20:26:02', 1),
(3, 'pacientes', '/pacientes', 'Gestão de Pacientes', 1, '2025-09-28 20:26:02', 4),
(4, 'anestesistas', '/anestesistas', 'Gestão de Anestesistas', 1, '2025-09-28 20:26:02', 3),
(5, 'agendamentos', '/agendamentos', 'Agendamentos', 1, '2025-09-28 20:26:02', 6),
(6, 'instituicoes', '/instituicoes', 'Gestão de Instituição', 1, '2025-09-28 20:26:02', 2),
(7, 'permissionamento', '/permissionamento-paginas', 'Controle de Permissão', 1, '2025-09-28 20:26:02', 9),
(8, 'ajuda', '/ajuda', 'Central de Ajuda', 1, '2025-09-28 20:26:02', 7),
(9, 'classificacao_ia', '/classificacao-ia', 'Classificação por IA', 1, '2025-09-29 04:00:57', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_type` enum('instituicao','usuario') NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `user_type`, `user_id`, `token_hash`, `expires_at`, `used_at`, `created_at`) VALUES
(1, 'edu.uefs@gmail.com', 'usuario', 27, '0c16c0e67d600ab7db74bbe21e9482aeb9cefe12b188d7e46304234f78624687', '2026-04-23 12:21:23', NULL, '2026-04-23 11:21:23'),
(2, 'liegecimmich@gmail.com', 'usuario', 21, '45bd1944eccccac5ed07214c937a37e979f7784e39655a28b3696b32f03239b7', '2026-06-18 19:36:30', NULL, '2026-06-18 18:36:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfis`
--

CREATE TABLE `perfis` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfis`
--

INSERT INTO `perfis` (`id`, `nome`, `descricao`, `created_at`) VALUES
(1, 'instituicao', 'Instituição de saúde', '2025-09-28 20:06:05'),
(2, 'medico', 'Médico responsável', '2025-09-28 20:06:05'),
(3, 'anestesista', 'Anestesista responsável', '2025-09-28 20:06:05'),
(4, 'paciente', 'Paciente', '2025-09-28 20:06:05'),
(5, 'funcionario', 'Funcionário da instituição', '2025-09-29 03:57:17'),
(6, 'admin', 'Administrador do sistema', '2025-09-29 04:19:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes_paginas`
--

CREATE TABLE `permissoes_paginas` (
  `id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `pagina_id` int(11) NOT NULL,
  `permitido` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `permissoes_paginas`
--

INSERT INTO `permissoes_paginas` (`id`, `perfil_id`, `pagina_id`, `permitido`, `created_at`) VALUES
(289, 1, 5, 1, '2025-09-29 09:38:14'),
(290, 3, 5, 1, '2025-09-29 09:38:14'),
(291, 6, 5, 1, '2025-09-29 09:38:14'),
(292, 1, 8, 1, '2025-09-29 09:38:14'),
(293, 3, 8, 1, '2025-09-29 09:38:14'),
(294, 6, 8, 1, '2025-09-29 09:38:14'),
(295, 1, 4, 1, '2025-09-29 09:38:14'),
(296, 6, 4, 1, '2025-09-29 09:38:14'),
(297, 1, 9, 1, '2025-09-29 09:38:14'),
(298, 3, 9, 1, '2025-09-29 09:38:14'),
(299, 6, 9, 1, '2025-09-29 09:38:14'),
(300, 1, 2, 1, '2025-09-29 09:38:14'),
(301, 5, 2, 1, '2025-09-29 09:38:14'),
(302, 6, 2, 1, '2025-09-29 09:38:14'),
(303, 1, 1, 1, '2025-09-29 09:38:14'),
(304, 3, 1, 1, '2025-09-29 09:38:14'),
(305, 6, 1, 1, '2025-09-29 09:38:14'),
(306, 3, 6, 0, '2025-09-29 09:38:14'),
(307, 6, 6, 1, '2025-09-29 09:38:14'),
(308, 1, 3, 1, '2025-09-29 09:38:14'),
(309, 3, 3, 1, '2025-09-29 09:38:14'),
(310, 4, 3, 1, '2025-09-29 09:38:14'),
(311, 5, 3, 1, '2025-09-29 09:38:14'),
(312, 6, 3, 1, '2025-09-29 09:38:14'),
(313, 6, 7, 1, '2025-09-29 09:38:14'),
(314, 3, 2, 0, '2025-10-08 14:25:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `procedimentos`
--

CREATE TABLE `procedimentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `procedimentos`
--

INSERT INTO `procedimentos` (`id`, `nome`, `descricao`, `status`, `created_at`) VALUES
(1, 'Cirurgia geral', 'Procedimento cirúrgico geral', 'ativo', '2025-09-28 20:06:05'),
(2, 'Artroscopia de joelho', 'Cirurgia minimamente invasiva no joelho', 'ativo', '2025-09-28 20:06:05'),
(3, 'Cirurgia bariátrica', 'Cirurgia para redução de peso', 'ativo', '2025-09-28 20:06:05'),
(4, 'Cirurgia de catarata', 'Remoção da catarata ocular', 'ativo', '2025-09-28 20:06:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `anestesista_id` int(11) DEFAULT NULL,
  `tipo` enum('instituicao','anestesista','paciente') NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `url_publica` varchar(500) DEFAULT NULL,
  `arquivo_path` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `qr_codes`
--

INSERT INTO `qr_codes` (`id`, `instituicao_id`, `anestesista_id`, `tipo`, `codigo`, `url_publica`, `arquivo_path`, `ativo`, `created_at`, `updated_at`) VALUES
(2, 7, NULL, 'instituicao', '0d1f995146a440973912bbd87b2d65411ecf7991f0382cc88cef203fa8966e55', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=0d1f995146a440973912bbd87b2d65411ecf7991f0382cc88cef203fa8966e55', 'uploads/qr_codes/qr_instituicao_7_1759865677.png', 1, '2025-10-07 19:34:38', '2025-10-14 15:01:01'),
(3, 7, 4, 'anestesista', '91e542a4c88e41dd0c2d9f05b53990f866ea50987c49336fc075fab6756afb6e', 'http:///cadastro_paciente.php?token=91e542a4c88e41dd0c2d9f05b53990f866ea50987c49336fc075fab6756afb6e', 'uploads/qr_codes/qr_anestesista_4_1759865697.png', 1, '2025-10-07 19:34:58', '2025-10-07 19:34:58'),
(4, 7, 5, 'anestesista', 'dc345abee161ed8b8f9efd27a26bba3b05afa683b2dc8fce8f14705731668d17', 'http:///cadastro_paciente.php?token=dc345abee161ed8b8f9efd27a26bba3b05afa683b2dc8fce8f14705731668d17', 'uploads/qr_codes/qr_anestesista_5_1759865713.png', 1, '2025-10-07 19:35:14', '2025-10-07 19:35:14'),
(6, 10, NULL, 'instituicao', 'INST_10_1759917792_0d1a52195d41dfbf', 'http://localhost/p/intitui-ao-testes', 'uploads/qr_codes/qr_instituicao_10_1759917792.png', 1, '2025-10-08 10:03:13', '2025-10-08 10:03:13'),
(14, 15, NULL, 'instituicao', '678c5c8268088c2935026dec0cc1a4c733c83644238504b9ea22176afeb7bace', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=678c5c8268088c2935026dec0cc1a4c733c83644238504b9ea22176afeb7bace', 'uploads/qr_codes/qr_instituicao_15_1760719051.png', 1, '2025-10-17 16:37:31', '2025-10-17 16:37:31'),
(15, 15, 15, 'anestesista', 'd68f8c76f1ccb88966f2a4e847b2e9094ebe2019f8951af5574e885518acd131', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=d68f8c76f1ccb88966f2a4e847b2e9094ebe2019f8951af5574e885518acd131', 'uploads/qr_codes/qr_anestesista_15_1760720258.png', 1, '2025-10-17 16:57:38', '2025-10-17 16:57:38'),
(17, 7, 19, 'anestesista', 'a91213ec5b47cb7e0b66933f19abd5c32e846bd562d10404e9b082877c0dfca2', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=a91213ec5b47cb7e0b66933f19abd5c32e846bd562d10404e9b082877c0dfca2', 'uploads/qr_codes/qr_anestesista_19_1760727853.png', 1, '2025-10-17 19:04:13', '2025-10-17 19:04:13'),
(18, 7, 20, 'anestesista', 'e473a7982445510f63bd321473667d4ee91b5429211c013116c0ac3e9515a869', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=e473a7982445510f63bd321473667d4ee91b5429211c013116c0ac3e9515a869', 'uploads/qr_codes/qr_anestesista_20_1760727941.png', 1, '2025-10-17 19:05:41', '2025-10-17 19:05:41'),
(19, 15, 21, 'anestesista', '4027788b1bbd865a2388ff188f265a4d2de427e382cdb89e3bf725a901cfba32', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=4027788b1bbd865a2388ff188f265a4d2de427e382cdb89e3bf725a901cfba32', 'uploads/qr_codes/qr_anestesista_21_1760728356.png', 1, '2025-10-17 19:12:36', '2025-10-17 19:12:36'),
(20, 17, NULL, 'instituicao', 'b8b70165475747cf817f3854f18058e54892d317190afb9eda7b2b14280f8c8c', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=b8b70165475747cf817f3854f18058e54892d317190afb9eda7b2b14280f8c8c', 'uploads/qr_codes/qr_instituicao_17_1761006697.png', 1, '2025-10-21 00:31:37', '2025-10-21 00:31:37'),
(21, 17, 22, 'anestesista', 'ad4604cdeccb98dfb5b36d7fc54d72fce63d3a7de65a4135cccdd7c751d73925', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=ad4604cdeccb98dfb5b36d7fc54d72fce63d3a7de65a4135cccdd7c751d73925', 'uploads/qr_codes/qr_anestesista_22_1761007326.png', 1, '2025-10-21 00:42:06', '2025-10-21 00:42:06'),
(22, 18, NULL, 'instituicao', '8b4151d9cec17209b908be41f2d3fd7982c28c89631ebbf0e60ccc4d56de4c07', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=8b4151d9cec17209b908be41f2d3fd7982c28c89631ebbf0e60ccc4d56de4c07', 'uploads/qr_codes/qr_instituicao_18_1761306680.png', 1, '2025-10-24 11:51:20', '2025-10-24 11:51:20'),
(23, 18, 23, 'anestesista', '5286a9fd3252dedfa46b359ded816036bcd21a5fd6d4cb4c0b9187fb7a6ce19d', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=5286a9fd3252dedfa46b359ded816036bcd21a5fd6d4cb4c0b9187fb7a6ce19d', 'uploads/qr_codes/qr_anestesista_23_1761307189.png', 1, '2025-10-24 11:59:49', '2025-10-24 11:59:49'),
(24, 18, 24, 'anestesista', '8127daeeac4dcbee862df0dfd95227af155a570ef0eff782122cc276b7bf8141', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=8127daeeac4dcbee862df0dfd95227af155a570ef0eff782122cc276b7bf8141', 'uploads/qr_codes/qr_anestesista_24_1761307323.png', 1, '2025-10-24 12:02:03', '2025-10-24 12:02:03'),
(25, 15, 25, 'anestesista', 'b71f3bd270a48d3a9727477dafc908ce553d2f0f7623d998041df31e90c3160d', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=b71f3bd270a48d3a9727477dafc908ce553d2f0f7623d998041df31e90c3160d', 'uploads/qr_codes/qr_anestesista_25_1764358783.png', 1, '2025-11-28 19:39:43', '2025-11-28 19:39:43'),
(26, 15, 26, 'anestesista', 'a950ecefd45f0f580885d2cd494ed984988fdbc760ed8e8ea966af41b6aeca6e', 'https://anestesiocheck.com.br/cadastro_paciente.php?token=a950ecefd45f0f580885d2cd494ed984988fdbc760ed8e8ea966af41b6aeca6e', 'uploads/qr_codes/qr_anestesista_26_1767360925.png', 1, '2026-01-02 13:35:25', '2026-01-02 13:35:25'),
(27, 19, NULL, 'instituicao', 'INST_19_1777458469_6c4f7025765f78d2', 'http://anestesiocheck.com.br/p/nutricheck-institui-o-m-dica', 'uploads/qr_codes/qr_instituicao_19_1777458469.png', 1, '2026-04-29 10:27:50', '2026-04-29 10:27:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_chamados`
--

CREATE TABLE `respostas_chamados` (
  `id` int(11) NOT NULL,
  `chamado_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `resposta` text NOT NULL,
  `tipo` enum('usuario','suporte') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessoes_usuario`
--

CREATE TABLE `sessoes_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `sessao_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `token_sessao` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sessoes_usuario`
--

INSERT INTO `sessoes_usuario` (`id`, `usuario_id`, `instituicao_id`, `perfil_id`, `sessao_id`, `created_at`, `updated_at`, `token_sessao`, `ip_address`, `user_agent`, `expires_at`) VALUES
(5, 6, 7, 3, 'sess_68daba6d333635.29055102', '2025-09-29 16:57:17', '2025-09-29 16:57:17', '6bbcc72c7a57c844460883a486cf0b9ba5cc7a1cd0411ebd3185dbb8a0cf3319', NULL, NULL, '2025-09-30 13:57:17'),
(21, 3, 8, 6, 'sess_68f41cac1a1a19.79010170', '2025-10-18 23:03:08', '2025-10-18 23:03:08', 'a9d3d466eee8bfc50fcfcb99534ea3b00ee903dbb2b345bcfcd6f6d7c3036744', NULL, NULL, '2025-10-19 20:03:08'),
(32, 9, 7, 3, 'sess_6929f7d64eaa75.54203328', '2025-11-28 19:28:22', '2025-11-28 19:28:22', 'f7f83857769e8714985662be4787364d4c721043df6a311e736ffd5709a8af61', NULL, NULL, '2025-11-29 16:28:22'),
(35, 4, 7, 3, 'sess_69305cc5daa485.03090417', '2025-12-03 15:52:37', '2025-12-03 15:52:37', '3ee01de032f599e4408e118ee2e753e33f3db65b4a9ebac2732787eca20fc341', NULL, NULL, '2025-12-04 12:52:37'),
(36, 22, 17, 3, 'sess_693eb7c2aaa201.25178881', '2025-12-14 13:12:34', '2025-12-14 13:12:34', '3ae1a8e194931353a11c0a59c50e9fffb155cd35d9e54ec0e83a1227902f2624', NULL, NULL, '2025-12-15 10:12:34'),
(42, 26, 15, 3, 'sess_696032f80386b1.99661070', '2026-01-08 22:43:04', '2026-01-08 22:43:04', '357451a5bc97b390b170c12cb23e03e1e7af181a72a51efc97cfbc02c2df37c2', NULL, NULL, '2026-01-09 19:43:04'),
(45, 15, 15, 3, 'sess_69e6ac323a8248.36501418', '2026-04-20 22:44:02', '2026-04-20 22:44:02', 'd4490ec5644a5d72af395954d2dc323ca952723ff17e23d5bffead43ea9a8c45', NULL, NULL, '2026-04-21 19:44:02'),
(49, 563, 17, 3, 'sess_6a35d0f677ddb2.43327789', '2026-06-19 23:29:58', '2026-06-19 23:29:58', '5f4bef8fd0ed51197e84669548d26151609db2fe0214ee2214f39cafb092fc5d', NULL, NULL, '2026-06-20 20:29:58'),
(52, 21, 15, 3, 'sess_6a6cda0a8f61f1.46450400', '2026-07-31 17:23:22', '2026-07-31 17:23:22', '1fcbaba25b71d7215480cb3dbc935225358b280d6294ca057c20dbe7dda8afd4', NULL, NULL, '2026-08-01 14:23:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `instituicao_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `crm` varchar(20) DEFAULT NULL,
  `coren` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `instituicao_id`, `perfil_id`, `nome`, `email`, `senha_hash`, `cpf`, `crm`, `coren`, `telefone`, `qr_code`, `qr_code_path`, `foto_path`, `status`, `created_at`, `updated_at`) VALUES
(3, 8, 6, 'Administrador', 'admin@anestesia.com', '$2y$10$PdsPQuJU0GqMFMQ1/NxGpu5Xz1V8oW1fg48u/gvR.nBnMMPMhfftW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ativo', '2025-09-29 04:29:04', '2025-10-24 11:48:17'),
(4, 7, 3, 'anestesista_1', 'anestesista_1@gmail.com', '$2y$10$bywCOrc2Rr.v.ZCrk8fii.7Na3RUeV9NB/mhdOmJ4VJBY9zWmUD52', NULL, '224314', NULL, '(51) 98160-6986', 'ANEST_68da0cb57dffd_1759120565', 'uploads/qr_codes/qr_4_1759120566.png', 'uploads/anestesistas/68daba2e91d33_1759164974.jpg', 'ativo', '2025-09-29 04:36:05', '2025-09-29 16:56:14'),
(5, 7, 2, 'Dr. Carlos Medico', 'carlos@medico.com', '$2y$10$Xbzr.cdWXiazVOQPGQjjJeaasJZNeUhuPhh2F0MbnySX1DFxppxUi', NULL, 'CRM123', NULL, NULL, NULL, NULL, NULL, 'ativo', '2025-09-29 05:16:35', '2026-06-19 23:16:40'),
(6, 7, 3, 'anestesista_2', 'anestesista_2@gmail.com', '$2y$10$NzjFkyMD/2WMr/sdBkkf7OnMAen6BF7RIk8flTpIHhH1MXi.KHf7e', NULL, '979879', NULL, '(51) 98160-6986', 'ANEST_68daba4fcaf46_1759165007', 'uploads/qr_codes/qr_6_1759165008.png', 'uploads/anestesistas/68daba4fcafd6_1759165007.jpeg', 'ativo', '2025-09-29 16:56:47', '2025-09-29 16:56:48'),
(9, 7, 3, 'Arthu Testador', 'arthur_anestesista@gmail.com', '$2y$10$nzUSKpS.v/SgnJG2Xkx1Aupd7oG4DZjVGKqiFPp/cUXwdWfPTH1vu', NULL, '242', NULL, '(51) 91823-7193', 'ANEST_68e8143cbfe54_1760039996', 'uploads/qr_codes/qr_9_1760039997.png', 'uploads/anestesistas/68e8143cbfe77_1760039996.jfif', 'ativo', '2025-10-09 19:59:56', '2025-10-09 19:59:57'),
(15, 15, 3, 'Rafael Seitenfus', 'rafasei@hotmail.com', '$2y$10$PdsPQuJU0GqMFMQ1/NxGpu5Xz1V8oW1fg48u/gvR.nBnMMPMhfftW', NULL, '27881', NULL, '(51) 99808-0283', 'ANEST_69ea058b0a789_1776944523', 'uploads/qr_codes/qr_15_1776944523.png', 'uploads/anestesistas/69ea02d1f15fd_1776943825.png', 'ativo', '2025-10-17 16:57:37', '2026-04-23 11:42:03'),
(20, 7, 3, 'José Eduardo Souza', 'edu.uefs@testes.com', '$2y$10$Wz1dnUUaqSR8K081NjHAHOM6zxARXJ3lvB87tSGgxycuxHxtCK7bi', NULL, '12345', NULL, '(51) 98160-6986', 'e473a7982445510f63bd321473667d4ee91b5429211c013116c0ac3e9515a869', 'uploads/qr_codes/qr_anestesista_20_1760727941.png', 'uploads/anestesistas/68f293847d4f1_1760727940.jpg', 'ativo', '2025-10-17 19:05:40', '2025-10-24 11:57:10'),
(21, 15, 3, 'Liege Caroline Immich', 'liegecimmich@gmail.com', '$2y$10$Xbzr.cdWXiazVOQPGQjjJeaasJZNeUhuPhh2F0MbnySX1DFxppxUi', NULL, '34578', NULL, '(51) 99505-7144', 'ANEST_69ea05791a7eb_1776944505', 'uploads/qr_codes/qr_21_1776944505.png', 'uploads/anestesistas/69ea02df0e9b8_1776943839.jpeg', 'ativo', '2025-10-17 19:12:36', '2026-04-23 11:41:45'),
(22, 17, 3, 'Health Meeting 2025', 'feira@gmail.com', '$2y$10$2Eu8uTAJwcoIfZ8zL0flIuiGhQn7LTfWirQMvKathibYP47PwV80C', NULL, '000001', NULL, '', 'ad4604cdeccb98dfb5b36d7fc54d72fce63d3a7de65a4135cccdd7c751d73925', 'uploads/qr_codes/qr_anestesista_22_1761007326.png', 'uploads/anestesistas/68f6d6dd8ccf6_1761007325.jpg', 'ativo', '2025-10-21 00:42:05', '2025-10-21 00:42:06'),
(23, 18, 3, 'Pedro de Mendonça Lima Heck', 'pedroheck13@gmail.com', '$2y$10$tRCiKwiIqrEnWQqtpHDED.pGbb.FLTJJY1x8AgfMyRoJ2BOsmLyjq', NULL, '35791', NULL, '(51) 99995-5986', '5286a9fd3252dedfa46b359ded816036bcd21a5fd6d4cb4c0b9187fb7a6ce19d', 'uploads/qr_codes/qr_anestesista_23_1761307189.png', 'uploads/anestesistas/68fb6a34bed07_1761307188.jpg', 'ativo', '2025-10-24 11:59:48', '2025-10-24 11:59:49'),
(24, 18, 3, 'Newton Braga nuernberg ', 'Brittesnew@gmail.com', '$2y$10$5roJ7LdjS0eErYpgGhwyS.A1RJjsur0HmqEvSQrPX6c4pXvhb1B.q', NULL, '40268', NULL, '(53) 99921-4280', '8127daeeac4dcbee862df0dfd95227af155a570ef0eff782122cc276b7bf8141', 'uploads/qr_codes/qr_anestesista_24_1761307323.png', 'uploads/anestesistas/68fb6abb6b759_1761307323.jpg', 'ativo', '2025-10-24 12:02:03', '2025-10-24 12:02:03'),
(26, 15, 3, 'Ana elisa agostini Serafim', 'anaserafim78@gmail.com', '$2y$10$z06CrWHhFrdAIbRDXsMEsOoq5j0dWgw.UOV1ynO4JDrMoc6UShk1q', NULL, '000002', NULL, '(51) 99999-9999', 'ANEST_69ea05603f2ad_1776944480', 'uploads/qr_codes/qr_26_1776944481.png', 'uploads/anestesistas/69ea02ec315c6_1776943852.png', 'ativo', '2026-01-02 13:35:24', '2026-04-23 11:41:21'),
(563, 10, 3, 'Doutor Médico 1', 'suporte@evoluoit.com.br', '$2y$10$Xbzr.cdWXiazVOQPGQjjJeaasJZNeUhuPhh2F0MbnySX1DFxppxUi', '02989319077', '123456', '123456', '51984230938', NULL, NULL, NULL, 'ativo', '2026-06-19 20:22:14', '2026-06-19 23:31:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos_interativos`
--

CREATE TABLE `videos_interativos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `autor` varchar(255) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `duracao` int(11) DEFAULT 0 COMMENT 'Duração em segundos',
  `thumbnail` varchar(500) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `tipo` enum('pre_anestesico','educacional','orientacao','outro') DEFAULT 'pre_anestesico',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `videos_interativos`
--

INSERT INTO `videos_interativos` (`id`, `titulo`, `descricao`, `autor`, `filename`, `file_path`, `file_size`, `duracao`, `thumbnail`, `ativo`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'Orientações Pré-Anestésicas', 'Vídeo educativo com informações importantes sobre o procedimento anestésico e cuidados pré-operatórios..', 'Anestesiocheck', 'video_68ec83e5927692.74123909.mp4', '/video/uploads/video_68ec83e5927692.74123909.mp4', NULL, 300, NULL, 1, 'pre_anestesico', '2025-10-13 02:56:23', '2025-10-16 00:05:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `video_estatisticas`
--

CREATE TABLE `video_estatisticas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `total_visualizacoes` int(11) DEFAULT 0,
  `tempo_total_assistido` int(11) DEFAULT 0,
  `perguntas_respondidas` int(11) DEFAULT 0,
  `perguntas_corretas` int(11) DEFAULT 0,
  `percentual_acerto` decimal(5,2) DEFAULT 0.00,
  `video_concluido` tinyint(1) DEFAULT 0,
  `data_primeira_visualizacao` timestamp NULL DEFAULT NULL,
  `data_ultima_visualizacao` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `video_estatisticas`
--

INSERT INTO `video_estatisticas` (`id`, `paciente_id`, `video_id`, `total_visualizacoes`, `tempo_total_assistido`, `perguntas_respondidas`, `perguntas_corretas`, `percentual_acerto`, `video_concluido`, `data_primeira_visualizacao`, `data_ultima_visualizacao`, `created_at`, `updated_at`) VALUES
(319, 66, 1, 1, 0, 1, 0, 0.00, 0, '2025-10-17 17:27:48', '2025-10-17 17:27:48', '2025-10-17 17:27:48', '2025-10-17 17:28:49'),
(522, 95, 1, 2, 0, 18, 10, 55.56, 0, '2025-10-24 00:23:01', '2025-10-24 00:23:57', '2025-10-24 00:23:01', '2025-10-24 00:27:08'),
(683, 99, 1, 3, 0, 39, 1, 2.56, 1, '2025-10-24 17:42:16', '2025-10-24 19:00:25', '2025-10-24 17:42:16', '2025-10-24 19:00:25'),
(750, 109, 1, 12, 0, 55, 4, 7.27, 1, '2025-11-05 22:02:13', '2025-11-06 13:46:37', '2025-11-05 22:02:13', '2025-11-06 13:51:19'),
(817, 110, 1, 3, 0, 0, 0, 0.00, 0, '2025-11-06 17:34:27', '2025-11-06 18:02:41', '2025-11-06 17:34:27', '2025-11-06 18:02:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `video_perguntas`
--

CREATE TABLE `video_perguntas` (
  `id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `texto_pergunta` text NOT NULL,
  `tipo_pergunta` enum('multipla_escolha','verdadeiro_falso','texto_livre') NOT NULL DEFAULT 'multipla_escolha',
  `opcoes` text DEFAULT NULL COMMENT 'JSON com as opções de resposta',
  `resposta_correta` text DEFAULT NULL,
  `tempo_exibicao` int(11) NOT NULL COMMENT 'Tempo em segundos quando a pergunta aparece',
  `obrigatoria` tinyint(1) DEFAULT 1,
  `pontuacao` int(11) DEFAULT 1,
  `explicacao` text DEFAULT NULL COMMENT 'Explicação da resposta correta',
  `ordem` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `video_perguntas`
--

INSERT INTO `video_perguntas` (`id`, `video_id`, `texto_pergunta`, `tipo_pergunta`, `opcoes`, `resposta_correta`, `tempo_exibicao`, `obrigatoria`, `pontuacao`, `explicacao`, `ordem`, `created_at`) VALUES
(1, 1, 'Qual cirurgia você está planejando fazer?', 'multipla_escolha', '[\"Hérnia\", \"Endoscopia\", \"Pele\",\"Colonoscopia\", \"Tomografia\"]', NULL, 26, 1, 1, NULL, 1, '2025-10-13 02:56:23'),
(2, 1, 'Você já fez alguma cirurgia antes?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 29, 1, 1, NULL, 2, '2025-10-13 02:56:23'),
(3, 1, 'Se sim, houve algum problema ou complicação durante essa cirurgia?', 'texto_livre', NULL, NULL, 33, 1, 1, NULL, 3, '2025-10-13 02:56:23'),
(4, 1, 'Você tem pressão alta?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 36, 1, 1, NULL, 4, '2025-10-13 02:56:23'),
(5, 1, 'Você sente dor no peito?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 38, 1, 1, NULL, 5, '2025-10-13 02:56:23'),
(6, 1, 'Você já teve um infarto?\r\n', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 40, 1, 1, NULL, 6, '2025-10-16 00:18:03'),
(7, 1, 'Você sente falta de ar com frequência?', 'multipla_escolha', '[\"Sim\", \"Não\"]', '', 43, 1, 1, NULL, 7, '2025-10-15 23:22:30'),
(8, 1, 'Você tem palpitações ou arritmias, que é quando o coração fica acelerado ou descompassado?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 47, 1, 1, NULL, 8, '2025-10-15 23:22:30'),
(9, 1, 'Você tem algum stent, aquela molinha, no coração?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 49, 1, 1, NULL, 9, '2025-10-16 02:51:49'),
(10, 1, 'Você tem diabetes?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 51, 1, 1, NULL, 10, '2025-10-16 02:53:38'),
(11, 1, 'Você tem alguma doença na tireoide?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 54, 1, 1, NULL, 11, '2025-10-16 02:54:32'),
(12, 1, 'Você tem insuficiência renal?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 57, 1, 1, NULL, 12, '2025-10-16 02:57:30'),
(13, 1, 'Você faz hemodiálise? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 59, 1, 1, NULL, 13, '2025-10-16 02:58:19'),
(14, 1, 'Você tem asma ou bronquite?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 63, 1, 1, NULL, 14, '2025-10-16 02:59:35'),
(15, 1, 'Você fuma atualmente? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 65, 1, 1, NULL, 15, '2025-10-16 03:00:12'),
(16, 1, 'Você já fumou no passado?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 66, 1, 1, NULL, 16, '2025-10-16 03:03:53'),
(17, 1, 'Você tosse todos os dias?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 69, 1, 1, NULL, 17, '2025-10-16 03:05:01'),
(18, 1, 'Você teve gripe ou febre nos últimos 14 dias?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 72, 1, 1, NULL, 18, '2025-10-16 03:05:53'),
(19, 1, 'Você já teve desmaios?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 75, 1, 1, NULL, 19, '2025-10-20 18:41:47'),
(20, 1, 'Você já teve convulsões?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 77, 1, 1, NULL, 20, '2025-10-24 00:45:14'),
(21, 1, 'Você tem alguma doença neurológica?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 80, 1, 1, NULL, 21, '2025-10-24 00:45:14'),
(22, 1, 'Você já teve AVC, conhecido como derrame?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 84, 1, 1, NULL, 22, '2025-10-24 00:50:26'),
(23, 1, 'Você já teve hepatite?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 86, 1, 1, NULL, 23, '2025-10-24 00:50:26'),
(24, 1, 'Você já teve câncer? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 89, 1, 1, NULL, 24, '2025-10-24 00:53:00'),
(25, 1, 'Você tem alguma doença psiquiátrica, como depressão, ansiedade ou bipolaridade? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 92, 1, 1, NULL, 26, '2025-10-24 00:53:00'),
(26, 1, 'Você já teve sangramento excessivo, ou seja, aumentado, durante alguma cirurgia ou procedimento no dentista?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 96, 1, 1, NULL, 25, '2025-10-24 00:55:09'),
(27, 1, 'Você tem alguma outra doença que não mencionamos?', 'texto_livre', NULL, NULL, 96, 1, 1, NULL, 27, '2025-10-24 00:57:19'),
(28, 1, 'Você consegue caminhar duas quadras, que são uns 200 metros?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 98, 1, 1, NULL, 28, '2025-10-24 00:57:19'),
(29, 1, 'E quando você caminha essas duas quadras, ou mesmo em repouso, você sente dor no peito ou falta de ar?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 100, 1, 1, NULL, 29, '2025-10-24 00:58:35'),
(30, 1, 'Você consegue subir dois lances de escadas, que equivalem a dois andares?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 102, 1, 1, NULL, 30, '2025-10-24 00:58:35'),
(31, 1, 'E quando você sobe essas escadas, você sente dor no peito ou falta de ar, mesmo que seja no meio do caminho?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 106, 1, 1, NULL, 31, '2025-10-24 01:38:34'),
(32, 1, 'Você é capaz de correr pequenas distâncias, tipo uns 100 metros?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 107, 1, 1, NULL, 32, '2025-10-24 01:38:34'),
(33, 1, ' E quando você corre essas pequenas distâncias, você sente dor no peito ou falta de ar', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 110, 1, 1, NULL, 33, '2025-10-24 01:40:07'),
(34, 1, 'Você usa algum medicamento regularmente, todos os dias?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 112, 1, 1, NULL, 34, '2025-10-24 01:41:13'),
(35, 1, 'Se você usa, poderia escrever o nome do medicamento, a dose (quantos comprimidos) e quantas vezes ao dia você toma?', 'texto_livre', NULL, NULL, 114, 1, 1, NULL, 35, '2025-10-24 01:41:13'),
(36, 1, 'Você tem alguma alergia?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 116, 1, 1, NULL, 36, '2025-10-24 01:42:17'),
(37, 1, 'Se sim, você é alérgico a quê?', 'texto_livre', NULL, NULL, 118, 1, 1, NULL, 37, '2025-10-24 01:42:17'),
(38, 1, 'Nos últimos seis meses, você perdeu peso? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 119, 1, 1, NULL, 38, '2025-10-24 01:44:04'),
(39, 1, 'Se sim, quantos quilos você perdeu?', 'texto_livre', NULL, NULL, 120, 1, 1, NULL, 39, '2025-10-24 01:44:04'),
(40, 1, 'Na última semana, você reduziu a sua alimentação diária? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 123, 1, 1, NULL, 40, '2025-10-24 01:44:56'),
(41, 1, 'Se você reduziu, essa diminuição foi de: (até 20%, de 20 a 50%, ou acima de 50%?)', 'texto_livre', NULL, NULL, 125, 1, 1, NULL, 41, '2025-10-24 01:44:56'),
(42, 1, 'Você usa bebidas alcoólicas?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 42, '2025-10-24 19:47:22'),
(43, 1, 'Se sim, quais bebidas você costuma consumir? (Vinho, cerveja, uísque, cachaça, vodka, ou outra?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 43, '2025-10-24 19:47:22'),
(44, 1, 'E quantas doses por semana você consumiu no último mês?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 44, '2025-10-24 19:48:12'),
(45, 1, 'Você faz uso de drogas, como maconha, cocaína, anfetamina ou outras? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 45, '2025-10-24 19:48:12'),
(46, 1, 'Foi solicitado a você algum exame pré-operatório?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 46, '2025-10-24 19:48:55'),
(47, 1, 'Se você respondeu SIM para a pergunta anterior, por favor, lembre-se de levar todos esses exames no dia do procedimento médico', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 47, '2025-10-24 19:48:55'),
(48, 1, 'Você usa prótese dentária, como dentadura ou chapa?', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 48, '2025-10-24 19:49:56'),
(49, 1, 'Você consegue movimentar bem o seu pescoço para cima e para baixo, como mostra a figura? ', 'multipla_escolha', '[\"Sim\", \"Não\"]', NULL, 0, 1, 1, NULL, 49, '2025-10-24 19:49:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `video_respostas`
--

CREATE TABLE `video_respostas` (
  `id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `sessao_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `resposta` text NOT NULL,
  `correta` tinyint(1) DEFAULT 0,
  `tempo_resposta` int(11) DEFAULT NULL COMMENT 'Tempo em segundos que levou para responder',
  `tentativas` int(11) DEFAULT 1,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `video_respostas`
--

INSERT INTO `video_respostas` (`id`, `pergunta_id`, `sessao_id`, `paciente_id`, `resposta`, `correta`, `tempo_resposta`, `tentativas`, `answered_at`) VALUES
(237, 3, 80, 66, 'Nao', 0, 15, 1, '2025-10-17 17:28:49'),
(414, 1, 104, 95, 'Endoscopia', 0, 10, 1, '2025-10-24 00:24:36'),
(415, 2, 104, 95, 'Sim', 0, 5, 1, '2025-10-24 00:24:49'),
(416, 3, 104, 95, 'Nao', 0, 13, 1, '2025-10-24 00:25:09'),
(417, 4, 104, 95, 'Não', 0, 3, 1, '2025-10-24 00:25:21'),
(418, 5, 104, 95, 'Não', 0, 2, 1, '2025-10-24 00:25:28'),
(419, 6, 104, 95, 'Não', 0, 1, 1, '2025-10-24 00:25:33'),
(420, 7, 104, 95, 'Não', 0, 1, 1, '2025-10-24 00:25:40'),
(421, 8, 104, 95, 'Não', 0, 2, 1, '2025-10-24 00:25:45'),
(422, 9, 104, 95, 'Não', 1, 2, 1, '2025-10-24 00:25:54'),
(423, 10, 104, 95, 'Não', 1, 1, 1, '2025-10-24 00:26:01'),
(424, 11, 104, 95, 'Não', 1, 1, 1, '2025-10-24 00:26:08'),
(425, 12, 104, 95, 'Não', 1, 2, 1, '2025-10-24 00:26:13'),
(426, 13, 104, 95, 'Não', 1, 6, 1, '2025-10-24 00:26:22'),
(427, 14, 104, 95, 'Não', 1, 2, 1, '2025-10-24 00:26:31'),
(428, 15, 104, 95, 'Não', 1, 2, 1, '2025-10-24 00:26:41'),
(429, 17, 104, 95, 'Não', 1, 2, 1, '2025-10-24 00:26:53'),
(430, 18, 104, 95, 'Não', 1, 2, 1, '2025-10-24 00:27:02'),
(431, 19, 104, 95, 'Não', 1, 3, 1, '2025-10-24 00:27:08'),
(563, 1, 115, 99, 'Hérnia', 0, 20, 1, '2025-10-24 17:43:18'),
(564, 2, 115, 99, 'Sim', 0, 1, 1, '2025-10-24 17:44:09'),
(565, 3, 115, 99, 'Não', 0, 8, 1, '2025-10-24 17:45:01'),
(566, 5, 115, 99, 'Não', 0, 1, 1, '2025-10-24 17:45:38'),
(567, 6, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:45:45'),
(568, 7, 115, 99, 'Não', 0, 6, 1, '2025-10-24 17:45:55'),
(569, 8, 115, 99, 'Não', 0, 23, 1, '2025-10-24 17:46:26'),
(570, 9, 115, 99, 'Não', 0, 3, 1, '2025-10-24 17:46:33'),
(571, 10, 115, 99, 'Sim', 0, 0, 1, '2025-10-24 17:46:37'),
(572, 11, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:46:42'),
(573, 12, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:46:47'),
(574, 13, 115, 99, 'Não', 0, 1, 1, '2025-10-24 17:46:51'),
(575, 14, 115, 99, 'Sim', 0, 0, 1, '2025-10-24 17:46:57'),
(576, 15, 115, 99, 'Não', 0, 2, 1, '2025-10-24 17:47:02'),
(577, 16, 115, 99, 'Não', 0, 1, 1, '2025-10-24 17:47:06'),
(578, 17, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:47:11'),
(579, 18, 115, 99, 'Não', 0, 2, 1, '2025-10-24 17:47:18'),
(580, 19, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:47:23'),
(581, 20, 115, 99, 'Não', 0, 1, 1, '2025-10-24 17:47:27'),
(582, 21, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:47:32'),
(583, 22, 115, 99, 'Não', 0, 3, 1, '2025-10-24 17:47:43'),
(584, 23, 115, 99, 'Não', 0, 2, 1, '2025-10-24 17:47:49'),
(585, 24, 115, 99, 'Não', 0, 16, 1, '2025-10-24 17:48:46'),
(586, 25, 115, 99, 'Não', 0, 0, 1, '2025-10-24 17:49:15'),
(587, 27, 115, 99, 'Não', 0, 14, 1, '2025-10-24 18:03:22'),
(588, 28, 115, 99, 'Sim', 0, 2, 1, '2025-10-24 18:03:34'),
(589, 29, 115, 99, 'Não', 0, 3, 1, '2025-10-24 18:03:41'),
(590, 30, 115, 99, 'Sim', 0, 6, 1, '2025-10-24 18:03:50'),
(591, 31, 115, 99, 'Não', 0, 0, 1, '2025-10-24 18:03:56'),
(592, 32, 115, 99, 'Sim', 0, 3, 1, '2025-10-24 18:04:02'),
(593, 33, 115, 99, 'Não', 0, 11, 1, '2025-10-24 18:04:18'),
(594, 34, 115, 99, 'Sim', 0, 16, 1, '2025-10-24 18:04:37'),
(595, 35, 115, 99, 'Lexapro 10 mg noite', 1, 158, 1, '2025-10-24 18:07:25'),
(596, 36, 115, 99, 'Não', 0, 1, 1, '2025-10-24 18:07:44'),
(597, 37, 115, 99, 'Não', 0, 9, 1, '2025-10-24 18:07:58'),
(598, 38, 115, 99, 'Não', 0, 2, 1, '2025-10-24 18:08:03'),
(599, 39, 115, 99, '0', 0, 5, 1, '2025-10-24 18:08:11'),
(600, 40, 115, 99, 'Não', 0, 2, 1, '2025-10-24 18:08:18'),
(601, 41, 115, 99, '0', 0, 4, 1, '2025-10-24 18:08:25'),
(622, 43, 122, 109, 'Sim', 0, 2, 1, '2025-11-05 22:02:31'),
(623, 43, 123, 109, 'Sim', 0, 11, 1, '2025-11-05 22:03:21'),
(624, 45, 123, 109, 'Não', 0, 2, 1, '2025-11-05 22:03:26'),
(625, 43, 124, 109, 'Sim', 0, 42, 1, '2025-11-05 22:39:25'),
(626, 43, 126, 109, 'Sim', 0, 18, 1, '2025-11-05 22:41:10'),
(627, 43, 129, 109, 'Sim', 0, 10, 1, '2025-11-06 13:18:02'),
(628, 45, 129, 109, 'Não', 0, 2, 1, '2025-11-06 13:18:09'),
(629, 47, 129, 109, 'Não', 0, 5, 1, '2025-11-06 13:18:17'),
(630, 44, 130, 109, 'Não', 0, 4, 1, '2025-11-06 13:28:18'),
(631, 43, 131, 109, 'Sim', 0, 10, 1, '2025-11-06 13:29:10'),
(632, 45, 131, 109, 'Não', 0, 2, 1, '2025-11-06 13:29:19'),
(633, 47, 131, 109, 'Sim', 0, 7, 1, '2025-11-06 13:29:32'),
(634, 1, 131, 109, 'Hérnia', 0, 13, 1, '2025-11-06 13:30:15'),
(635, 2, 131, 109, 'Sim', 0, 1, 1, '2025-11-06 13:30:22'),
(636, 3, 131, 109, 'Não', 0, 10, 1, '2025-11-06 13:30:39'),
(637, 4, 131, 109, 'Sim', 0, 1, 1, '2025-11-06 13:30:52'),
(638, 5, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:30:58'),
(639, 6, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:03'),
(640, 7, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:09'),
(641, 8, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:17'),
(642, 9, 131, 109, 'Não', 0, 2, 1, '2025-11-06 13:31:25'),
(643, 10, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:30'),
(644, 11, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:37'),
(645, 12, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:43'),
(646, 13, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:48'),
(647, 14, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:31:55'),
(648, 15, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:00'),
(649, 16, 131, 109, 'Não', 0, 2, 1, '2025-11-06 13:32:05'),
(650, 17, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:11'),
(651, 18, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:17'),
(652, 19, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:23'),
(653, 20, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:28'),
(654, 21, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:34'),
(655, 22, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:40'),
(656, 23, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:46'),
(657, 24, 131, 109, 'Sim', 0, 1, 1, '2025-11-06 13:32:52'),
(658, 25, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:32:58'),
(659, 27, 131, 109, 'Não', 0, 7, 1, '2025-11-06 13:33:19'),
(660, 28, 131, 109, 'Sim', 0, 2, 1, '2025-11-06 13:33:28'),
(661, 29, 131, 109, 'Não', 0, 5, 1, '2025-11-06 13:33:37'),
(662, 30, 131, 109, 'Sim', 0, 5, 1, '2025-11-06 13:33:47'),
(663, 31, 131, 109, 'Não', 0, 1, 1, '2025-11-06 13:33:54'),
(664, 32, 131, 109, 'Não', 0, 3, 1, '2025-11-06 13:34:00'),
(665, 33, 131, 109, 'Não', 0, 22, 1, '2025-11-06 13:34:28'),
(666, 34, 131, 109, 'Sim', 0, 2, 1, '2025-11-06 13:34:38'),
(667, 35, 131, 109, 'Hidroclorotiazida 25, anlodipino 10, perindopril 10, nebivolol 5, famotidina  2x 40, rosuvastatina 5, carbolitium750 mg, lurasidona 40, fluvoxamina 200, tadalafila 5', 1, 287, 1, '2025-11-06 13:39:29'),
(668, 36, 131, 109, 'Não', 0, 3, 1, '2025-11-06 13:39:39'),
(669, 37, 131, 109, 'Não sou', 1, 24, 1, '2025-11-06 13:40:07'),
(670, 38, 131, 109, 'Não', 0, 3, 1, '2025-11-06 13:40:15'),
(671, 39, 131, 109, 'Não perdi', 1, 12, 1, '2025-11-06 13:40:31'),
(672, 40, 131, 109, 'Não', 0, 6, 1, '2025-11-06 13:40:43'),
(673, 41, 131, 109, 'Não reduziu', 1, 13, 1, '2025-11-06 13:41:17'),
(674, 49, 131, 109, 'Sim', 0, 8, 1, '2025-11-06 13:43:02'),
(675, 46, 130, 109, 'Não', 0, 2, 1, '2025-11-06 13:51:11'),
(676, 48, 130, 109, 'Não', 0, 3, 1, '2025-11-06 13:51:19');

--
-- Acionadores `video_respostas`
--
DELIMITER $$
CREATE TRIGGER `trg_after_insert_video_resposta` AFTER INSERT ON `video_respostas` FOR EACH ROW BEGIN
    -- Atualizar ou criar estatística
    INSERT INTO video_estatisticas 
        (paciente_id, video_id, perguntas_respondidas, perguntas_corretas, updated_at)
    SELECT 
        NEW.paciente_id,
        vs.video_id,
        1,
        CASE WHEN NEW.correta = 1 THEN 1 ELSE 0 END,
        NOW()
    FROM video_sessoes vs
    WHERE vs.id = NEW.sessao_id
    ON DUPLICATE KEY UPDATE
        perguntas_respondidas = perguntas_respondidas + 1,
        perguntas_corretas = perguntas_corretas + CASE WHEN NEW.correta = 1 THEN 1 ELSE 0 END,
        percentual_acerto = (perguntas_corretas * 100.0 / perguntas_respondidas),
        updated_at = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `video_sessoes`
--

CREATE TABLE `video_sessoes` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `session_token` varchar(100) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT 'unknown',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `ultima_posicao` int(11) DEFAULT 0 COMMENT 'Última posição em segundos',
  `tempo_total_assistido` int(11) DEFAULT 0 COMMENT 'Tempo total em segundos',
  `percentual_conclusao` decimal(5,2) DEFAULT 0.00,
  `status` enum('iniciada','em_andamento','pausada','concluida','abandonada') DEFAULT 'iniciada',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `video_sessoes`
--

INSERT INTO `video_sessoes` (`id`, `paciente_id`, `video_id`, `session_token`, `ip_address`, `user_agent`, `device_type`, `started_at`, `completed_at`, `ultima_posicao`, `tempo_total_assistido`, `percentual_conclusao`, `status`, `created_at`, `updated_at`) VALUES
(80, 66, 1, 'c31518dd776ebdf668adf358b95845cd675217f5f7aeaeea1ced1352b03f592e', '2804:0:3000:92:95dd:e9f8:8a00:1d', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', '2025-10-17 17:27:48', NULL, 0, 0, 0.00, 'em_andamento', '2025-10-17 17:27:48', '2025-10-17 17:31:12'),
(103, 95, 1, 'f6e53eac9d724a65e13bee6471155fbca42d0609d8a123af622fa5ff192fbe1d', '2804:7f4:c027:ce54:6077:f8c7:3638:b4f4', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.96 Mobile/15E148 Safari/604.1', 'mobile', '2025-10-24 00:23:01', NULL, 0, 0, 0.00, 'em_andamento', '2025-10-24 00:23:01', '2025-10-24 00:23:53'),
(104, 95, 1, 'aea75310f67910a2ac86cf6e9c18381c7a89320dedfdba312b665745ac31512e', '2804:7f4:c027:ce54:6077:f8c7:3638:b4f4', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.96 Mobile/15E148 Safari/604.1', 'mobile', '2025-10-24 00:23:57', NULL, 0, 0, 0.00, 'em_andamento', '2025-10-24 00:23:57', '2025-10-24 00:27:37'),
(115, 99, 1, '30e239491e71a152d2d11997f58ce3cb1840d89cb8bb140d212d1271e852a300', '2804:0:3000:92:8618:7574:c3ad:57fa', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'mobile', '2025-10-24 17:42:16', '2025-10-24 18:09:57', 0, 0, 100.00, 'concluida', '2025-10-24 17:42:16', '2025-10-24 18:09:57'),
(116, 99, 1, '94528a8dcffa8293ecf5492c6d251cd8120db9829059261a6b84230f19ab43e2', '177.47.173.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'desktop', '2025-10-24 19:00:25', NULL, 0, 0, 0.00, 'em_andamento', '2025-10-24 19:00:25', '2025-10-24 19:00:36'),
(122, 109, 1, '58a78d70c227fae81d0451e3bcda84cb1eaec9fa4253e22d4221f81d34091516', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-05 22:02:13', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 22:02:13', '2025-11-05 22:02:54'),
(123, 109, 1, '5389e144c41e918f8e6679b400fca93520c434c345bc2af16998afc913fa3ee4', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-05 22:03:00', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 22:03:00', '2025-11-05 22:38:37'),
(124, 109, 1, '34491cacbad56a2304605e6e344786561c4ce325e57f6a512aea57003d2869d4', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-05 22:38:22', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 22:38:22', '2025-11-05 22:39:23'),
(125, 109, 1, 'a31bddfbd2d132a7850001532bf5a8ca245147761f4362dc31e73eb9e7a4fd46', '168.90.226.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'desktop', '2025-11-05 22:38:27', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 22:38:27', '2025-11-05 22:38:49'),
(126, 109, 1, 'cffede80dab0b815ee3d0c7efd46046538fc4621092430aa8560e566453a6c1c', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-05 22:40:02', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 22:40:02', '2025-11-05 22:41:23'),
(127, 109, 1, '953202b7482abf0e6b0da21d9784922d1a8af6645e95bcb5add9fb89c579f051', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-05 22:41:31', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 22:41:31', '2025-11-05 22:54:36'),
(128, 109, 1, '3b448ccd9c3a860fb168c49f053a48e63153bfc297a15160bd16fb896c683c17', '187.19.162.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'desktop', '2025-11-05 23:41:42', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-05 23:41:42', '2025-11-05 23:41:57'),
(129, 109, 1, '3e7f62aa30053545d138b282f411b6ddfcbb922265fb634346fa44ded3e7ec08', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-06 13:17:36', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-06 13:17:36', '2025-11-06 13:18:26'),
(130, 109, 1, 'c8bf222687f049cc45ade8c56d58d358d814bcf8dc15d1377cc7145382447b11', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-06 13:27:56', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-06 13:27:56', '2025-11-06 13:51:47'),
(131, 109, 1, 'df8357207d8a59d91158bc8386d03892ed65471b80e1caf7cc800cba9173deba', '45.231.1.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'mobile', '2025-11-06 13:28:40', '2025-11-06 13:46:37', 0, 0, 0.00, 'em_andamento', '2025-11-06 13:28:40', '2025-11-06 13:48:10'),
(132, 110, 1, 'd38eba154dfef7ea6131ea21972344a44c8270e439940f58de57046edb29e8eb', '177.84.145.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/141.0.3537.99 Version/18.0 Mobile/15E148 Safari/604.1', 'mobile', '2025-11-06 17:34:27', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-06 17:34:27', '2025-11-06 17:47:47'),
(133, 110, 1, '114d01cd61a97843a0b248e1627466e8f5f7f6045fbfaffd98302943d8df9d76', '177.84.145.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/141.0.3537.99 Version/18.0 Mobile/15E148 Safari/604.1', 'mobile', '2025-11-06 17:48:46', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-06 17:48:46', '2025-11-06 17:52:27'),
(134, 110, 1, '1e17d8fdb6887bc6361d9d8b91dce8796693b4521b05120205d368b4e4dcbca3', '2804:18:1968:7e00:4513:550e:6a9c:1970', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/141.0.3537.99 Version/18.0 Mobile/15E148 Safari/604.1', 'mobile', '2025-11-06 18:02:41', NULL, 0, 0, 0.00, 'em_andamento', '2025-11-06 18:02:41', '2025-11-06 18:05:12');

--
-- Acionadores `video_sessoes`
--
DELIMITER $$
CREATE TRIGGER `trg_after_update_video_sessao` AFTER UPDATE ON `video_sessoes` FOR EACH ROW BEGIN
    IF NEW.status = 'concluida' AND OLD.status != 'concluida' THEN
        -- Atualizar estatísticas
        INSERT INTO video_estatisticas 
            (paciente_id, video_id, total_visualizacoes, tempo_total_assistido, video_concluido, data_ultima_visualizacao, updated_at)
        VALUES 
            (NEW.paciente_id, NEW.video_id, 1, NEW.tempo_total_assistido, 1, NOW(), NOW())
        ON DUPLICATE KEY UPDATE
            total_visualizacoes = total_visualizacoes + 1,
            tempo_total_assistido = tempo_total_assistido + NEW.tempo_total_assistido,
            video_concluido = 1,
            data_ultima_visualizacao = NOW(),
            updated_at = NOW();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_paciente_video_desempenho`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_paciente_video_desempenho` (
`paciente_id` int(11)
,`nome` varchar(255)
,`email` varchar(255)
,`video_id` int(11)
,`video_titulo` varchar(255)
,`status_sessao` enum('iniciada','em_andamento','pausada','concluida','abandonada')
,`percentual_conclusao` decimal(5,2)
,`total_respostas` bigint(21)
,`respostas_corretas` decimal(23,0)
,`percentual_acerto` decimal(31,5)
,`started_at` timestamp
,`completed_at` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_video_estatisticas_gerais`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_video_estatisticas_gerais` (
`video_id` int(11)
,`titulo` varchar(255)
,`tipo` enum('pre_anestesico','educacional','orientacao','outro')
,`total_pacientes` bigint(21)
,`total_sessoes` bigint(21)
,`media_conclusao` decimal(9,6)
,`sessoes_concluidas` decimal(23,0)
,`total_respostas` bigint(21)
,`respostas_corretas` decimal(23,0)
,`percentual_acerto_geral` decimal(31,5)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_pacientes_alto_risco`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_pacientes_alto_risco` (
`paciente_id` int(11)
,`paciente_nome` varchar(255)
,`paciente_email` varchar(255)
,`paciente_telefone` varchar(20)
,`instituicao_nome` varchar(255)
,`pergunta_critica` text
,`resposta` text
,`respondido_em` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_progresso_pacientes`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_progresso_pacientes` (
`paciente_id` int(11)
,`paciente_nome` varchar(255)
,`paciente_email` varchar(255)
,`instituicao_nome` varchar(255)
,`videos_respondidos` bigint(21)
,`total_respostas` bigint(21)
,`progresso_percentual` decimal(5,2)
,`status_sessao` enum('iniciada','em_andamento','pausada','concluida','abandonada')
,`iniciada_em` timestamp
,`concluida_em` timestamp
,`status_descricao` varchar(12)
,`tempo_decorrido_minutos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_respostas_completas`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_respostas_completas` (
`resposta_id` int(11)
,`paciente_id` int(11)
,`paciente_nome` varchar(255)
,`paciente_email` varchar(255)
,`paciente_cpf` varchar(14)
,`instituicao_nome` varchar(255)
,`video_id` varchar(50)
,`video_title` varchar(255)
,`video_ordem` int(11)
,`question_id` varchar(50)
,`question_index` int(11)
,`question_text` text
,`question_title` varchar(255)
,`answer` text
,`answer_type` varchar(20)
,`respondido_em` timestamp
,`ip_address` varchar(45)
,`tipo_resposta_descricao` varchar(22)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_respostas_por_video`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_respostas_por_video` (
`video_id` varchar(50)
,`video_title` varchar(255)
,`video_ordem` int(11)
,`total_pacientes_responderam` bigint(21)
,`total_respostas` bigint(21)
,`total_perguntas_diferentes` bigint(21)
,`primeira_resposta` timestamp
,`ultima_resposta` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_respostas_sim_nao`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_respostas_sim_nao` (
`video_id` varchar(50)
,`video_title` varchar(255)
,`question_text` text
,`total_sim` bigint(21)
,`total_nao` bigint(21)
,`total_respostas` bigint(21)
,`percentual_sim` decimal(26,2)
,`percentual_nao` decimal(26,2)
);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `anestesista_id` (`anestesista_id`),
  ADD KEY `instituicao_id` (`instituicao_id`),
  ADD KEY `procedimento_id` (`procedimento_id`);

--
-- Índices de tabela `chamados_suporte`
--
ALTER TABLE `chamados_suporte`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_chamado` (`numero_chamado`),
  ADD KEY `instituicao_id` (`instituicao_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `configuracoes_sistema`
--
ALTER TABLE `configuracoes_sistema`
  ADD PRIMARY KEY (`chave`);

--
-- Índices de tabela `consentimentos`
--
ALTER TABLE `consentimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_consentimentos_paciente_data` (`paciente_id`,`data_aceite`),
  ADD KEY `idx_consentimentos_instituicao_data` (`instituicao_id`,`data_aceite`);

--
-- Índices de tabela `demonstracoes`
--
ALTER TABLE `demonstracoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Índices de tabela `jornada_paciente`
--
ALTER TABLE `jornada_paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jornada_paciente` (`paciente_id`,`etapa`);

--
-- Índices de tabela `logs_ativade`
--
ALTER TABLE `logs_ativade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `link_acesso` (`link_acesso`),
  ADD UNIQUE KEY `token_acesso` (`token_acesso`),
  ADD KEY `instituicao_id` (`instituicao_id`),
  ADD KEY `anestesista_id` (`anestesista_id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `paciente_anestesistas`
--
ALTER TABLE `paciente_anestesistas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_paciente_anestesista` (`paciente_id`,`anestesista_id`),
  ADD KEY `idx_paciente_anestesistas_paciente` (`paciente_id`),
  ADD KEY `idx_paciente_anestesistas_anestesista` (`anestesista_id`),
  ADD KEY `idx_paciente_anestesistas_status` (`status`);

--
-- Índices de tabela `paciente_video_estatisticas`
--
ALTER TABLE `paciente_video_estatisticas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `paciente_video_respostas`
--
ALTER TABLE `paciente_video_respostas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_unique_resposta` (`paciente_id`,`video_id`,`question_index`),
  ADD KEY `idx_paciente` (`paciente_id`),
  ADD KEY `idx_video` (`video_id`),
  ADD KEY `idx_question` (`question_id`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_video_ordem` (`video_ordem`),
  ADD KEY `idx_paciente_video` (`paciente_id`,`video_id`);

--
-- Índices de tabela `paciente_video_sessoes`
--
ALTER TABLE `paciente_video_sessoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_paciente` (`paciente_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_iniciada` (`iniciada_em`),
  ADD KEY `idx_concluida` (`concluida_em`);

--
-- Índices de tabela `paginas_sistema`
--
ALTER TABLE `paginas_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD UNIQUE KEY `rota` (`rota`);

--
-- Índices de tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token_hash` (`token_hash`),
  ADD KEY `idx_email_type` (`email`,`user_type`);

--
-- Índices de tabela `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `permissoes_paginas`
--
ALTER TABLE `permissoes_paginas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_perfil_pagina` (`perfil_id`,`pagina_id`),
  ADD KEY `pagina_id` (`pagina_id`);

--
-- Índices de tabela `procedimentos`
--
ALTER TABLE `procedimentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `instituicao_id` (`instituicao_id`);

--
-- Índices de tabela `respostas_chamados`
--
ALTER TABLE `respostas_chamados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chamado_id` (`chamado_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `sessoes_usuario`
--
ALTER TABLE `sessoes_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `instituicao_id` (`instituicao_id`),
  ADD KEY `perfil_id` (`perfil_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `qr_code` (`qr_code`),
  ADD KEY `instituicao_id` (`instituicao_id`),
  ADD KEY `perfil_id` (`perfil_id`);

--
-- Índices de tabela `videos_interativos`
--
ALTER TABLE `videos_interativos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ativo` (`ativo`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- Índices de tabela `video_estatisticas`
--
ALTER TABLE `video_estatisticas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_paciente_video` (`paciente_id`,`video_id`),
  ADD KEY `idx_concluido` (`video_concluido`),
  ADD KEY `fk_video_estatisticas_video` (`video_id`);

--
-- Índices de tabela `video_perguntas`
--
ALTER TABLE `video_perguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_video` (`video_id`),
  ADD KEY `idx_tempo` (`tempo_exibicao`),
  ADD KEY `idx_video_perguntas_video_ordem` (`video_id`,`ordem`);

--
-- Índices de tabela `video_respostas`
--
ALTER TABLE `video_respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pergunta` (`pergunta_id`),
  ADD KEY `idx_sessao` (`sessao_id`),
  ADD KEY `idx_paciente` (`paciente_id`),
  ADD KEY `idx_correta` (`correta`),
  ADD KEY `idx_video_respostas_paciente_pergunta` (`paciente_id`,`pergunta_id`);

--
-- Índices de tabela `video_sessoes`
--
ALTER TABLE `video_sessoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `idx_paciente` (`paciente_id`),
  ADD KEY `idx_video` (`video_id`),
  ADD KEY `idx_token` (`session_token`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_video_sessoes_paciente_video` (`paciente_id`,`video_id`),
  ADD KEY `idx_video_sessoes_data` (`started_at`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `chamados_suporte`
--
ALTER TABLE `chamados_suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `consentimentos`
--
ALTER TABLE `consentimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `demonstracoes`
--
ALTER TABLE `demonstracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `jornada_paciente`
--
ALTER TABLE `jornada_paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT de tabela `logs_ativade`
--
ALTER TABLE `logs_ativade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;

--
-- AUTO_INCREMENT de tabela `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT de tabela `paciente_anestesistas`
--
ALTER TABLE `paciente_anestesistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de tabela `paciente_video_estatisticas`
--
ALTER TABLE `paciente_video_estatisticas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de tabela `paciente_video_respostas`
--
ALTER TABLE `paciente_video_respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1557;

--
-- AUTO_INCREMENT de tabela `paciente_video_sessoes`
--
ALTER TABLE `paciente_video_sessoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paginas_sistema`
--
ALTER TABLE `paginas_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `permissoes_paginas`
--
ALTER TABLE `permissoes_paginas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT de tabela `procedimentos`
--
ALTER TABLE `procedimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `respostas_chamados`
--
ALTER TABLE `respostas_chamados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sessoes_usuario`
--
ALTER TABLE `sessoes_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=564;

--
-- AUTO_INCREMENT de tabela `videos_interativos`
--
ALTER TABLE `videos_interativos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `video_estatisticas`
--
ALTER TABLE `video_estatisticas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=824;

--
-- AUTO_INCREMENT de tabela `video_perguntas`
--
ALTER TABLE `video_perguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `video_respostas`
--
ALTER TABLE `video_respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=677;

--
-- AUTO_INCREMENT de tabela `video_sessoes`
--
ALTER TABLE `video_sessoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

-- --------------------------------------------------------

--
-- Estrutura para view `view_paciente_video_desempenho`
--
DROP TABLE IF EXISTS `view_paciente_video_desempenho`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_paciente_video_desempenho`  AS SELECT `p`.`id` AS `paciente_id`, `p`.`nome` AS `nome`, `p`.`email` AS `email`, `v`.`id` AS `video_id`, `v`.`titulo` AS `video_titulo`, `vs`.`status` AS `status_sessao`, `vs`.`percentual_conclusao` AS `percentual_conclusao`, count(`vr`.`id`) AS `total_respostas`, sum(case when `vr`.`correta` = 1 then 1 else 0 end) AS `respostas_corretas`, CASE WHEN count(`vr`.`id`) > 0 THEN sum(case when `vr`.`correta` = 1 then 1 else 0 end) * 100.0 / count(`vr`.`id`) ELSE 0 END AS `percentual_acerto`, `vs`.`started_at` AS `started_at`, `vs`.`completed_at` AS `completed_at` FROM (((`pacientes` `p` join `video_sessoes` `vs` on(`p`.`id` = `vs`.`paciente_id`)) join `videos_interativos` `v` on(`vs`.`video_id` = `v`.`id`)) left join `video_respostas` `vr` on(`vs`.`id` = `vr`.`sessao_id`)) GROUP BY `p`.`id`, `p`.`nome`, `p`.`email`, `v`.`id`, `v`.`titulo`, `vs`.`id`, `vs`.`status`, `vs`.`percentual_conclusao`, `vs`.`started_at`, `vs`.`completed_at` ;

-- --------------------------------------------------------

--
-- Estrutura para view `view_video_estatisticas_gerais`
--
DROP TABLE IF EXISTS `view_video_estatisticas_gerais`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_video_estatisticas_gerais`  AS SELECT `v`.`id` AS `video_id`, `v`.`titulo` AS `titulo`, `v`.`tipo` AS `tipo`, count(distinct `vs`.`paciente_id`) AS `total_pacientes`, count(`vs`.`id`) AS `total_sessoes`, avg(`vs`.`percentual_conclusao`) AS `media_conclusao`, sum(case when `vs`.`status` = 'concluida' then 1 else 0 end) AS `sessoes_concluidas`, count(`vr`.`id`) AS `total_respostas`, sum(case when `vr`.`correta` = 1 then 1 else 0 end) AS `respostas_corretas`, CASE WHEN count(`vr`.`id`) > 0 THEN sum(case when `vr`.`correta` = 1 then 1 else 0 end) * 100.0 / count(`vr`.`id`) ELSE 0 END AS `percentual_acerto_geral` FROM ((`videos_interativos` `v` left join `video_sessoes` `vs` on(`v`.`id` = `vs`.`video_id`)) left join `video_respostas` `vr` on(`vs`.`id` = `vr`.`sessao_id`)) WHERE `v`.`ativo` = 1 GROUP BY `v`.`id`, `v`.`titulo`, `v`.`tipo` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_pacientes_alto_risco`
--
DROP TABLE IF EXISTS `vw_pacientes_alto_risco`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_pacientes_alto_risco`  AS SELECT DISTINCT `p`.`id` AS `paciente_id`, `p`.`nome` AS `paciente_nome`, `p`.`email` AS `paciente_email`, `p`.`telefone` AS `paciente_telefone`, `i`.`nome` AS `instituicao_nome`, `r`.`question_text` AS `pergunta_critica`, `r`.`answer` AS `resposta`, `r`.`created_at` AS `respondido_em` FROM ((`pacientes` `p` join `instituicoes` `i` on(`p`.`instituicao_id` = `i`.`id`)) join `paciente_video_respostas` `r` on(`p`.`id` = `r`.`paciente_id`)) WHERE `r`.`question_text` like '%infarto%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%AVC%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%convulsão%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%marcapasso%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%câncer%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%diabetes%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%rim%' AND `r`.`answer` = 'Sim' OR `r`.`question_text` like '%hepatite%' AND `r`.`answer` = 'Sim' ORDER BY `p`.`id` ASC, `r`.`created_at` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_progresso_pacientes`
--
DROP TABLE IF EXISTS `vw_progresso_pacientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_progresso_pacientes`  AS SELECT `p`.`id` AS `paciente_id`, `p`.`nome` AS `paciente_nome`, `p`.`email` AS `paciente_email`, `i`.`nome` AS `instituicao_nome`, count(distinct `r`.`video_id`) AS `videos_respondidos`, count(`r`.`id`) AS `total_respostas`, `s`.`progresso_percentual` AS `progresso_percentual`, `s`.`status` AS `status_sessao`, `s`.`iniciada_em` AS `iniciada_em`, `s`.`concluida_em` AS `concluida_em`, CASE WHEN `s`.`status` = 'concluida' THEN 'Completo' WHEN `s`.`status` = 'em_andamento' THEN 'Em Andamento' WHEN `s`.`status` = 'pausada' THEN 'Pausado' WHEN `s`.`status` = 'abandonada' THEN 'Abandonado' ELSE 'Não Iniciado' END AS `status_descricao`, timestampdiff(MINUTE,`s`.`iniciada_em`,coalesce(`s`.`concluida_em`,current_timestamp())) AS `tempo_decorrido_minutos` FROM (((`pacientes` `p` join `instituicoes` `i` on(`p`.`instituicao_id` = `i`.`id`)) left join `paciente_video_sessoes` `s` on(`p`.`id` = `s`.`paciente_id`)) left join `paciente_video_respostas` `r` on(`p`.`id` = `r`.`paciente_id`)) GROUP BY `p`.`id`, `p`.`nome`, `p`.`email`, `i`.`nome`, `s`.`progresso_percentual`, `s`.`status`, `s`.`iniciada_em`, `s`.`concluida_em` ORDER BY `p`.`id` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_respostas_completas`
--
DROP TABLE IF EXISTS `vw_respostas_completas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_respostas_completas`  AS SELECT `r`.`id` AS `resposta_id`, `r`.`paciente_id` AS `paciente_id`, `p`.`nome` AS `paciente_nome`, `p`.`email` AS `paciente_email`, `p`.`cpf` AS `paciente_cpf`, `i`.`nome` AS `instituicao_nome`, `r`.`video_id` AS `video_id`, `r`.`video_title` AS `video_title`, `r`.`video_ordem` AS `video_ordem`, `r`.`question_id` AS `question_id`, `r`.`question_index` AS `question_index`, `r`.`question_text` AS `question_text`, `r`.`question_title` AS `question_title`, `r`.`answer` AS `answer`, `r`.`answer_type` AS `answer_type`, `r`.`created_at` AS `respondido_em`, `r`.`ip_address` AS `ip_address`, CASE WHEN `r`.`answer_type` = 'boolean' AND `r`.`answer` = 'Sim' THEN 'Positivo' WHEN `r`.`answer_type` = 'boolean' AND `r`.`answer` = 'Não' THEN 'Negativo' ELSE 'Texto/Múltipla Escolha' END AS `tipo_resposta_descricao` FROM ((`paciente_video_respostas` `r` join `pacientes` `p` on(`r`.`paciente_id` = `p`.`id`)) join `instituicoes` `i` on(`p`.`instituicao_id` = `i`.`id`)) ORDER BY `r`.`paciente_id` ASC, `r`.`video_ordem` ASC, `r`.`question_index` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_respostas_por_video`
--
DROP TABLE IF EXISTS `vw_respostas_por_video`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_respostas_por_video`  AS SELECT `r`.`video_id` AS `video_id`, `r`.`video_title` AS `video_title`, `r`.`video_ordem` AS `video_ordem`, count(distinct `r`.`paciente_id`) AS `total_pacientes_responderam`, count(`r`.`id`) AS `total_respostas`, count(distinct `r`.`question_id`) AS `total_perguntas_diferentes`, min(`r`.`created_at`) AS `primeira_resposta`, max(`r`.`created_at`) AS `ultima_resposta` FROM `paciente_video_respostas` AS `r` GROUP BY `r`.`video_id`, `r`.`video_title`, `r`.`video_ordem` ORDER BY `r`.`video_ordem` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_respostas_sim_nao`
--
DROP TABLE IF EXISTS `vw_respostas_sim_nao`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u633289092_anestesioreal`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vw_respostas_sim_nao`  AS SELECT `r`.`video_id` AS `video_id`, `r`.`video_title` AS `video_title`, `r`.`question_text` AS `question_text`, count(case when `r`.`answer` = 'Sim' then 1 end) AS `total_sim`, count(case when `r`.`answer` = 'Não' then 1 end) AS `total_nao`, count(0) AS `total_respostas`, round(count(case when `r`.`answer` = 'Sim' then 1 end) * 100.0 / count(0),2) AS `percentual_sim`, round(count(case when `r`.`answer` = 'Não' then 1 end) * 100.0 / count(0),2) AS `percentual_nao` FROM `paciente_video_respostas` AS `r` WHERE `r`.`answer_type` = 'boolean' GROUP BY `r`.`video_id`, `r`.`video_title`, `r`.`question_text` ORDER BY `r`.`video_id` ASC, `r`.`question_index` ASC ;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`anestesista_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_3` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `agendamentos_ibfk_4` FOREIGN KEY (`procedimento_id`) REFERENCES `procedimentos` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `chamados_suporte`
--
ALTER TABLE `chamados_suporte`
  ADD CONSTRAINT `chamados_suporte_ibfk_1` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chamados_suporte_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `jornada_paciente`
--
ALTER TABLE `jornada_paciente`
  ADD CONSTRAINT `jornada_paciente_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `logs_ativade`
--
ALTER TABLE `logs_ativade`
  ADD CONSTRAINT `logs_ativade_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `logs_ativade_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Restrições para tabelas `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pacientes_ibfk_3` FOREIGN KEY (`anestesista_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `pacientes_ibfk_4` FOREIGN KEY (`medico_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `paciente_anestesistas`
--
ALTER TABLE `paciente_anestesistas`
  ADD CONSTRAINT `paciente_anestesistas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paciente_anestesistas_ibfk_2` FOREIGN KEY (`anestesista_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `paciente_video_estatisticas`
--
ALTER TABLE `paciente_video_estatisticas`
  ADD CONSTRAINT `paciente_video_estatisticas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `paciente_video_respostas`
--
ALTER TABLE `paciente_video_respostas`
  ADD CONSTRAINT `fk_resposta_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `paciente_video_sessoes`
--
ALTER TABLE `paciente_video_sessoes`
  ADD CONSTRAINT `fk_sessao_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `permissoes_paginas`
--
ALTER TABLE `permissoes_paginas`
  ADD CONSTRAINT `permissoes_paginas_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permissoes_paginas_ibfk_2` FOREIGN KEY (`pagina_id`) REFERENCES `paginas_sistema` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `respostas_chamados`
--
ALTER TABLE `respostas_chamados`
  ADD CONSTRAINT `respostas_chamados_ibfk_1` FOREIGN KEY (`chamado_id`) REFERENCES `chamados_suporte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `respostas_chamados_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `sessoes_usuario`
--
ALTER TABLE `sessoes_usuario`
  ADD CONSTRAINT `sessoes_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessoes_usuario_ibfk_2` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessoes_usuario_ibfk_3` FOREIGN KEY (`perfil_id`) REFERENCES `perfis` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`perfil_id`) REFERENCES `perfis` (`id`);

--
-- Restrições para tabelas `video_estatisticas`
--
ALTER TABLE `video_estatisticas`
  ADD CONSTRAINT `fk_video_estatisticas_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_video_estatisticas_video` FOREIGN KEY (`video_id`) REFERENCES `videos_interativos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `video_perguntas`
--
ALTER TABLE `video_perguntas`
  ADD CONSTRAINT `fk_video_perguntas_video` FOREIGN KEY (`video_id`) REFERENCES `videos_interativos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `video_respostas`
--
ALTER TABLE `video_respostas`
  ADD CONSTRAINT `fk_video_respostas_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_video_respostas_pergunta` FOREIGN KEY (`pergunta_id`) REFERENCES `video_perguntas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_video_respostas_sessao` FOREIGN KEY (`sessao_id`) REFERENCES `video_sessoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `video_sessoes`
--
ALTER TABLE `video_sessoes`
  ADD CONSTRAINT `fk_video_sessoes_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_video_sessoes_video` FOREIGN KEY (`video_id`) REFERENCES `videos_interativos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

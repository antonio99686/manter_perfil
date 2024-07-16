-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 16-Jul-2024 às 00:44
-- Versão do servidor: 8.0.31
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `manter_perfil`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `recuperar_senha`
--

DROP TABLE IF EXISTS `recuperar_senha`;
CREATE TABLE IF NOT EXISTS `recuperar_senha` (
  `email` varchar(255) NOT NULL,
  `token` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `data_criacao` datetime NOT NULL,
  `usado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `recuperar_senha`
--

INSERT INTO `recuperar_senha` (`email`, `token`, `data_criacao`, `usado`) VALUES
('antonio.2022324018@aluno.iffar.edu.br', 'b35c2c496e98fc8cb79ebd4299af3d870457cbe3f96abd695113b1bf7c263d37d62480bba74891f4429dc85a9188cffe89b4', '2024-06-24 20:08:51', 0),
('antonio.2022324018@aluno.iffar.edu.br', '2d5be7bb5994aabfb81774230b3c3f5e8cb18e60928dfb69897d244c313b5948347e97aa99eb72bc8a89bbe37377edd1e5ba', '2024-07-08 18:47:47', 0),
('antonio.2022324018@aluno.iffar.edu.br', 'a7c4ec1a2244f17381899f7adf4b7e8908cb5eed69e4bfaacb7490db0df9f48c14ed2d23d41e9ff174fec6e9daecbd779b05', '2024-07-08 18:49:12', 0),
('antonio.2022324018@aluno.iffar.edu.br', 'ea50f4efdd9ff4dc6d4b6fbb702bca0dd3d856d26a6bd53f674b9ea8e914316de70753a97473f7298522186827c11056637c', '2024-07-08 18:50:57', 0),
('antonio.2022324018@aluno.iffar.edu.br', 'c1d3c16a8344f96a66459d2fa6a42f5c9e0dee3844abcd0b5425c34a42c868ce2e18307866ea90494dd5e518753a38fac178', '2024-07-08 18:54:40', 0),
('antonio.2022324018@aluno.iffar.edu.br', '0550f3bfa79cd3ae944d4042cc33e9352f9432b1d93747f1404004ce61748eebaac260bd305269ca66cfde3f7203402e1330', '2024-07-15 15:46:32', 0),
('antonio.2022324018@aluno.iffar.edu.br', 'b783e6c1485bd9eee8abc27dbf5ca57422e2057fe6e6eeb6f4a7e04cce59e16acdc6a8a216f7994db34ffa9e7722c963df59', '2024-07-15 15:55:20', 0),
('antoniomongelo72@gmail.com', '966a1d93ac0d89355f0a8ad83fab905a238aeb7602b3d289d42873eef0d40b63da5b657b2660a1fbd19f9f9f14065c536942', '2024-07-15 16:02:58', 0),
('antonio.2022324018@aluno.iffar.edu.br', '11b51a079b4a2c3694b9bfc9ad7a7f77bff149d93d8496c3c12514672223f1ac659398815dfe365c765c0f4b13d1d96160e2', '2024-07-15 16:07:06', 0),
('antonio.2022324018@aluno.iffar.edu.br', 'a131323be8a62fa6928a7808bc0880147b7145ed14ecc92c1805a2788237328d5b3b4d91e73c103f2da900fd0b7c2c3260f3', '2024-07-15 16:07:07', 0),
('antonio.2022324018@aluno.iffar.edu.br', '41e8ee3976c707bba13b59dbac885fe67565eb0e172e762412f3c14c072bd95f63b681516fbd12a1984902eb111f1493fff7', '2024-07-15 16:39:39', 1),
('antonio.2022324018@aluno.iffar.edu.br', 'e1b6c20ef2209c42ff30f62aae3c13663fa068bafe9a2a1edd1ec38e8e27f9a96633c6e019f37c00ac925829c34c0f4555f7', '2024-07-15 21:05:27', 1),
('antonio.2022324018@aluno.iffar.edu.br', 'f371f495f43dde58ccaf78c904aabf08cb1ddde25a0cee8121274231fa678cef13dc8d1ae73320b0ce3274d2b6c91df5e414', '2024-07-15 21:13:31', 0),
('antonio.2022324018@aluno.iffar.edu.br', 'a0fa2ed797e400eccc323df98d3214cec8a7ccb215ad639fd202b96cb43fcfff3875d14a911c825b0551e23f2e9c0256b499', '2024-07-15 21:22:18', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `perfil_img` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_user`, `nome`, `senha`, `email`, `perfil_img`) VALUES
(4, 'gabriel mattes', '456', 'antonio.2022324018@aluno.iffar.edu.br', '4.jpeg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

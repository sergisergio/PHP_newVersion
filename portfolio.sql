-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 03 jan. 2020 à 15:40
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `PORTFOLIO`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numberPosts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `numberPosts`) VALUES
(1, 'PHP', 14),
(2, 'HTML-CSS', 1),
(3, 'Javascript', 1),
(4, 'WORDPRESS', 1);

-- --------------------------------------------------------

--
-- Structure de la table `category_posts`
--

CREATE TABLE `category_posts` (
  `category_id` int(11) NOT NULL,
  `posts_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_posts`
--

INSERT INTO `category_posts` (`category_id`, `posts_id`) VALUES
(1, 163),
(1, 164),
(1, 165),
(1, 166),
(1, 167),
(1, 168),
(1, 169),
(1, 170),
(1, 172),
(2, 3),
(3, 4),
(3, 5),
(4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_at` datetime NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `ppp` int(11) NOT NULL DEFAULT '2',
  `characters` int(11) NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `config`
--

INSERT INTO `config` (`id`, `ppp`, `characters`) VALUES
(1, 5, 500);

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

CREATE TABLE `connexion` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `connexion`
--

INSERT INTO `connexion` (`id`, `ip`, `username`) VALUES
(1, '::1', 'kjn'),
(2, '::1', 'philippeptraon@gmail.com'),
(3, '::1', 'philou'),
(4, '::1', 'philo');

-- --------------------------------------------------------

--
-- Structure de la table `description`
--

CREATE TABLE `description` (
  `id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `description`
--

INSERT INTO `description` (`id`, `image_id`, `title`, `content`, `subtitle`) VALUES
(1, 10, 'Qui suis-je ?', 'TEST5', 'Développeur PHP');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `style` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `project_id`, `url`, `alt`, `style`, `name`, `extension`) VALUES
(1, 1, 'festival.png', NULL, 'background: #FFF;', '', ''),
(2, 2, 'minichat.png', NULL, NULL, '', ''),
(3, 3, 'ChaletBLANC.png', NULL, NULL, '', ''),
(4, 4, 'logo-blanc.png', NULL, '', '', ''),
(6, 6, 'OnFaitNotreComHD.jpg', NULL, NULL, '', ''),
(7, 7, 'logo2.jpeg', NULL, NULL, '', ''),
(8, 8, 'archi1.jpg', NULL, NULL, '', ''),
(9, 9, 'snake.png', NULL, NULL, '', ''),
(10, NULL, 'Philippe.jpeg', NULL, NULL, NULL, NULL),
(11, 10, 'logo-Snowtricks.png', NULL, 'background: #FFF;', NULL, NULL),
(12, 11, 'logo_final.png', NULL, NULL, NULL, NULL),
(13, 12, 'NONO.PNG', NULL, NULL, NULL, NULL),
(14, NULL, 'b1-1.jpg', NULL, NULL, NULL, NULL),
(15, NULL, 'avatardefaut.png', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `links`
--

INSERT INTO `links` (`id`, `name`, `active`, `class`) VALUES
(1, 'PHP', 'active', 'in'),
(2, 'Javascript', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190721112628', '2019-07-21 11:26:41'),
('20190721130953', '2019-07-21 13:10:07'),
('20190721180140', '2019-07-21 18:01:52');

-- --------------------------------------------------------

--
-- Structure de la table `password_update`
--

CREATE TABLE `password_update` (
  `id` int(11) NOT NULL,
  `old_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnew_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirm_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `published` tinyint(1) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `img_id` int(11) DEFAULT NULL,
  `numberComments` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `created_at`, `content`, `published`, `user_id`, `img_id`, `numberComments`) VALUES
(1, 'Article 1 modifié', NULL, '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>', 1, 35, 14, 0),
(3, 'Article 2', NULL, '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>', 1, 35, 14, 0),
(4, 'nouveau', '2019-07-25 20:09:46', '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>', 1, 35, 14, 3),
(5, 'Nouvel article avec CKEDitor modifié', '2019-07-25 20:23:36', '<p><strong>Testons cela</strong></p>\r\n<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>\r\n<p><strong>Ajoutons une image</strong></p>\r\n\r\n<p><a href=\"https://img.lemde.fr/2019/04/22/0/191/1619/1079/688/0/60/0/e39da8d_2FIads9h8wB-0SwSgxVaVWsp.jpg\"><strong>https://img.lemde.fr/2019/04/22/0/191/1619/1079/688/0/60/0/e39da8d_2FIads9h8wB-0SwSgxVaVWsp.jpg</strong></a></p>\r\n\r\n<p><strong><img alt=\"\" src=\"https://img.lemde.fr/2019/04/22/0/191/1619/1079/688/0/60/0/e39da8d_2FIads9h8wB-0SwSgxVaVWsp.jpg\" style=\"height:459px; width:688px\" /></strong></p>', 1, 35, 14, 0),
(6, 'Article 5', '2019-08-16 09:23:31', '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.\r\n</p>', 1, 35, 14, 0),
(7, 'Article 6', '2019-08-16 09:23:50', '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>', 1, 35, 14, 0),
(8, 'Article 7', '2019-08-16 09:23:59', '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.&lt</p>', 1, 35, 14, 0),
(9, 'Article 8', '2019-08-16 09:24:11', '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>', 1, 35, 14, 0),
(10, 'Article 9', '2019-08-16 09:24:21', '<p>Aenean leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Sed posuere consectetur est at lobortis. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>', 1, 35, 14, 0),
(163, 'test', '2019-12-23 22:59:22', '<p>traon</p>', 1, 35, 14, 2),
(164, 'traon', '2019-12-23 23:22:12', '<p>traon</p>', 1, 35, 14, 0),
(165, 'traon', '2019-12-24 15:24:55', '<p>traon</p>', 1, 35, 14, 0),
(166, 'traon', '2019-12-24 15:25:09', '<p>traon</p>', 1, 35, 14, 0),
(167, 'traon', '2019-12-24 15:25:16', '<p>traon</p>', 1, 35, 14, 0),
(168, 'traon', '2019-12-24 15:25:23', '<p>traon</p>', 1, 35, 14, 0),
(169, 'azd', '2019-12-29 11:28:22', '&lt;p&gt;azd&lt;/p&gt;', 1, 56, NULL, 0),
(170, 'zadazd', '2019-12-29 11:30:25', '&lt;p&gt;aazd&lt;/p&gt;', 1, 56, NULL, 0),
(172, 'ecezc', '2019-12-29 11:32:33', '&lt;p&gt;zcedd&lt;/p&gt;', 1, 56, 14, 0);

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `link` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `img_id` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `link`, `created_at`, `img_id`, `published`) VALUES
(1, 'Projet3 Parcours Openclassrooms', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://festival.philippetraon.com/', NULL, 1, 1),
(2, 'Mini-Chat', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://chat.philippetraon.com/', NULL, 2, 1),
(3, 'Chalets & caviar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://chaletsetcaviar.philippetraon.com/', NULL, 3, 1),
(4, 'Mozaïque', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://mozaique.lille.free.fr/', NULL, 4, 1),
(6, 'Book Léa & Elliott', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://onfaitnotre.com/', NULL, 6, 1),
(7, 'Ty Marie', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://tymariewordpress2.philippetraon.com/', NULL, 7, 1),
(8, 'architecture', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://www.architecture.philippetraon.com/', NULL, 8, 1),
(9, 'snake', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam consectetur leo ac laoreet porta. Morbi maximus at velit non consequat. Mauris et lectus non quam pellentesque pellentesque. Cras et scelerisque eros, ut iaculis est. Etiam eu mollis nunc. Nunc volutpat sed mauris quis interdum. Sed gravida arcu sed sapien euismod cursus. Mauris ut turpis non arcu faucibus aliquet.', 'http://snake.philippetraon.com/', NULL, 9, 0),
(10, 'snowtricks', 'Projet Symfony', 'http://snowtricks-oc.herokuapp.com/', NULL, 11, 1),
(11, 'soltexpertiz', 'soltexpertiz', 'http://www.soltexpertiz.com/', NULL, 12, 1),
(12, 'nono', 'nono', 'http://leportfolio.philippetraon.com/', NULL, 13, 1);

-- --------------------------------------------------------

--
-- Structure de la table `projects_category`
--

CREATE TABLE `projects_category` (
  `projects_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projects_category`
--

INSERT INTO `projects_category` (`projects_id`, `category_id`) VALUES
(1, 2),
(2, 2),
(3, 4),
(4, 2),
(6, 2),
(7, 4),
(8, 2),
(9, 3),
(10, 1),
(11, 2),
(12, 2);

-- --------------------------------------------------------

--
-- Structure de la table `skill`
--

CREATE TABLE `skill` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `skill`
--

INSERT INTO `skill` (`id`, `name`, `level`, `user_id`) VALUES
(1, 'HTML', 80, 14),
(2, 'CSS', 85, 14),
(3, 'PHP', 70, 14),
(4, 'MySQL', 75, 14);

-- --------------------------------------------------------

--
-- Structure de la table `skill2`
--

CREATE TABLE `skill2` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `active` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `skill2`
--

INSERT INTO `skill2` (`id`, `name`, `content`, `active`, `class`) VALUES
(1, 'HTML/CSS/Bootstrap', '<p>Suite à de nombreuses lignes de code, de lectures de tutoriaux, de lectures de vidéo ainsi qu\'à ma formation, j\'ai pu acquérir de bonnes bases afin de construire un site. J\'ai ensuite continué sur Bootstrap et m\'intéresse à d\'autres librairies, pour mettre en place, par exemple un site en material design.</p>', 'active', 'in'),
(2, 'JS/jQuery/Angular', '<p>Mon premier langage de programmation : Javascript. Beaucoup de travail pour comprendre ce langage en natif avant de passer à jQuery. Je travaille également sur Angular7 et Ionic. Je m\'intéresse également à React ainsi qu\'à Node en parallèle.</p>', '', ''),
(3, 'PHP/Symfony', '<p>Au cours de ma première formation , j\'ai également eu des cours et des projets avec PHP et MySql : chat, espace membres, blog. Puis j\'ai décidé de me spécialiser dans ce domaine en apprenant Symfony.</p>', '', ''),
(4, 'CMS', '<p>Le plus connu et le plus utilisé étant Wordpress, je m\'intéresse plutôt à ce CMS (d\'autant plus pour PHP et SQL). Je me forme, aujourd\'hui, sur Drupal8.</p>', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `subcomment`
--

CREATE TABLE `subcomment` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `published_at` datetime NOT NULL,
  `validated` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sublink`
--

CREATE TABLE `sublink` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sublink`
--

INSERT INTO `sublink` (`id`, `url`, `name`, `link_id`) VALUES
(2, 'https://www.php.net/manual/fr/index.php', 'DOC PHP', 1),
(3, 'https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql', 'PHP Openclassrooms', 1),
(4, 'https://www.w3schools.com/php/default.asp', 'W3Schools', 1),
(5, 'https://developer.mozilla.org/fr/docs/Web/JavaScript', 'DOC Javascript', 2);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numberPosts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`id`, `name`, `numberPosts`) VALUES
(1, 'PHP', 0),
(3, 'tag', 0),
(6, 'Symfony', 0),
(7, 'Javascript', 0);

-- --------------------------------------------------------

--
-- Structure de la table `tag_posts`
--

CREATE TABLE `tag_posts` (
  `tag_id` int(11) NOT NULL,
  `posts_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tag_posts`
--

INSERT INTO `tag_posts` (`tag_id`, `posts_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `user_id`, `created_at`, `title`, `content`, `is_done`) VALUES
(5, 3, '2019-08-16 08:05:00', 'Admin', '<p>Pouvoir ajouter un projet avec image et cat&eacute;gories</p>', 1),
(6, 3, '2019-08-16 08:07:52', 'Admin', '<p>Ajouter une image &agrave; la bio</p>', 0),
(7, 3, '2019-08-16 08:08:43', 'Accueil', '<p>Bouton more (projets)</p>', 0),
(8, 3, '2019-08-16 08:09:25', 'Accueil', '<p>Front-end</p>', 0),
(9, 3, '2019-08-16 08:11:21', 'Admin', '<p>Ecrire qqchose sur la page d&#39;accueil</p>', 0),
(10, 3, '2019-08-16 08:12:37', 'Admin', '<p>possibilit&eacute; de bannir un utilisateur</p>', 0),
(11, 3, '2019-08-16 08:13:29', 'Blog', '<p>Pagination</p>', 0),
(12, 3, '2019-08-16 08:13:43', 'Blog', '<p>moteur de recherche</p>', 0),
(13, 3, '2019-08-16 09:42:31', 'Blog', '<p>Ajouter des tags aux articles</p>', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_created_date` datetime DEFAULT NULL,
  `token_expire_date` datetime DEFAULT NULL,
  `active` int(11) NOT NULL,
  `banned` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` longtext COLLATE utf8mb4_unicode_ci,
  `bio_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `token`, `token_created_date`, `token_expire_date`, `active`, `banned`, `created_at`, `avatar_id`, `street`, `code`, `city`, `phone`, `mobile`, `bio`, `bio_title`, `last_login`, `lastname`, `firstname`, `ip_address`) VALUES
(3, 'admin@admin.fr', 1, '$argon2id$v=19$m=65536,t=6,p=1$GEwLmEWib7jmDJedwXBNKA$PxjRezO/4sgt7YQce+aCHnKCo7L6qENbFM6sr3M5+Xg', 'admin', '', NULL, NULL, 1, 0, '2019-08-07 00:00:00', 15, NULL, NULL, NULL, NULL, NULL, 'bio', NULL, NULL, '', '', ''),
(14, 'ptraon@gmaidl.com', 1, '$argon2i$v=19$m=1024,t=2,p=2$cUI1d0Z6ZXdZbHBibVIxdg$A7/CoYAiakwBDLOjQjSQg5zxubjy9e8WH3meGSnCyCA', 'philipped', NULL, NULL, NULL, 1, 0, '2019-08-08 00:00:00', 15, '17 Place Saint-Pierre', 75018, 'PARIS', '01 42 76 03 81', '06 47 51 22 85', '<p>Test</p>\r\n\r\n<p>Depuis plusieurs ann&eacute;es, je m&#39;int&eacute;resse aux nouvelles technologies et me suis d&eacute;couvert une passion pour le d&eacute;veloppement. Ayant commenc&eacute; &agrave; d&eacute;velopper en autodidacte, j&#39;ai , par la suite, rejoint une formation G2R de 3 mois en 2017 suivi d&#39;un stage.</p>\r\n\r\n<p>J&#39;ai poursuivi cette formation en effectuant en 2018 le parcours PHP/Symfony chez Openclassrooms. Formation tr&egrave;s intense de 12 mois durant laquelle j&#39;ai valid&eacute; diff&eacute;rents projets (Wordpress, UML, PHP orient&eacute; objet, Symfony, API REST...) pour finalement valider mon dipl&ocirc;me RNCP2.</p>\r\n\r\n<p>Enfin, j&#39;ai int&eacute;gr&eacute; une p&eacute;pini&egrave;re Symfony (formation de 3 mois pour int&eacute;grer la soci&eacute;t&eacute; dans laquelle je suis maintenant): 3 mois sur l&#39;orient&eacute; objet, MongoDb, Redis, Symfony &eacute;videmment et un mois sur Angular7.</p>\r\n\r\n<p>Depuis mars, je suis ing&eacute;nieur d&#39;&eacute;tudes et d&eacute;veloppement.</p>\r\n\r\n<p>En parall&egrave;le, et durant les 3 premiers mois, je participe &agrave; des conf&eacute;rences SensioLabs.</p>\r\n\r\n<p>D&eacute;couvrant de plus en plus de choses dans cet univers, je me concentre pour l&#39;instant sur le PHP et Symfony ainsi que sur JavaScript et sur Angular, mais j&#39;essaie, en parall&egrave;le, de m&#39;int&eacute;resser au langage Python par exemple, Drupal, etc...</p>', '<h2>Qui suis-je ?</h2>', NULL, '', '', ''),
(35, 'ptraon@gmailn.com', 1, '$argon2i$v=19$m=1024,t=2,p=2$aEFLZFFnRnJkSi9SR3JtQg$EwSfuanPX0+kLW32Xt9okDCtMdIC20WQBN26J3YyMsQ', 'philippe', 'gs9ZKYFmxnGOXI5yxALM66R7NxkHRriR6YwNtP7QWAEkkE3GN55jEGmJyGdHHCSgiTHbD6uonYbOcA2nCp3cGp2EGpzTZ8oybERG', NULL, NULL, 1, 0, '2019-12-27 15:47:33', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '::1'),
(37, 'exemple@gmail.com', 0, '$argon2i$v=19$m=1024,t=2,p=2$Z0hPU2xQWTJ3QllWNXFDTQ$5SjjVOmy+6ul29oQjgu1DCzwpxrPO7XtjAWM1eV+AYE', 'exemple', 'hWIIhAKzwepgiYdy674TYeSd0YFNHfwaSTnoqtCGhYHLEg9XjdQrKqAW1kDH4oKVRN3Y530ZyXj9AIzKHkR6pY0xQ69EEyzSx1IO', '2019-12-28 09:58:44', '2019-12-29 09:58:44', 0, 0, '2019-12-28 08:58:44', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '::1'),
(56, 'ptraon@gmail.com', 1, '$argon2i$v=19$m=1024,t=2,p=2$Vm1ZZlNlLmNsNWtaRDNPcQ$ZffB38pM/quzSz8/SDTNP+WHaUVs3jOOOjQwpAMshhA', 'philou', NULL, NULL, NULL, 1, 0, '2019-12-28 18:58:57', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '::1');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category_posts`
--
ALTER TABLE `category_posts`
  ADD PRIMARY KEY (`category_id`,`posts_id`),
  ADD KEY `IDX_606EF5B312469DE2` (`category_id`),
  ADD KEY `IDX_606EF5B3D5E258C5` (`posts_id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CF675F31B` (`author_id`),
  ADD KEY `IDX_9474526C4B89032C` (`post_id`);

--
-- Index pour la table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `connexion`
--
ALTER TABLE `connexion`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `description`
--
ALTER TABLE `description`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6DE440263DA5256D` (`image_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C53D045F166D1F9C` (`project_id`);

--
-- Index pour la table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `password_update`
--
ALTER TABLE `password_update`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `img_id` (`img_id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projects_category`
--
ALTER TABLE `projects_category`
  ADD PRIMARY KEY (`projects_id`,`category_id`),
  ADD KEY `IDX_6CD1FA281EDE0F55` (`projects_id`),
  ADD KEY `IDX_6CD1FA2812469DE2` (`category_id`);

--
-- Index pour la table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5E3DE477A76ED395` (`user_id`);

--
-- Index pour la table `skill2`
--
ALTER TABLE `skill2`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `subcomment`
--
ALTER TABLE `subcomment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Index pour la table `sublink`
--
ALTER TABLE `sublink`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link_id` (`link_id`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag_posts`
--
ALTER TABLE `tag_posts`
  ADD PRIMARY KEY (`tag_id`,`posts_id`),
  ADD KEY `IDX_AAB1A3FCBAD26311` (`tag_id`),
  ADD KEY `IDX_AAB1A3FCD5E258C5` (`posts_id`);

--
-- Index pour la table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_527EDB25A76ED395` (`user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD KEY `UNIQ_8D93D64986383B10` (`avatar_id`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `connexion`
--
ALTER TABLE `connexion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `description`
--
ALTER TABLE `description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `password_update`
--
ALTER TABLE `password_update`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `skill2`
--
ALTER TABLE `skill2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `subcomment`
--
ALTER TABLE `subcomment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sublink`
--
ALTER TABLE `sublink`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `category_posts`
--
ALTER TABLE `category_posts`
  ADD CONSTRAINT `FK_606EF5B312469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_606EF5B3D5E258C5` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `description`
--
ALTER TABLE `description`
  ADD CONSTRAINT `FK_6DE440263DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `img_id` FOREIGN KEY (`img_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `projects_category`
--
ALTER TABLE `projects_category`
  ADD CONSTRAINT `FK_6CD1FA2812469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6CD1FA281EDE0F55` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `FK_5E3DE477A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `subcomment`
--
ALTER TABLE `subcomment`
  ADD CONSTRAINT `author_id` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_id` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Contraintes pour la table `sublink`
--
ALTER TABLE `sublink`
  ADD CONSTRAINT `link_id` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`);

--
-- Contraintes pour la table `tag_posts`
--
ALTER TABLE `tag_posts`
  ADD CONSTRAINT `FK_AAB1A3FCBAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AAB1A3FCD5E258C5` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64986383B10` FOREIGN KEY (`avatar_id`) REFERENCES `image` (`id`);

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 24 2025 г., 05:00
-- Версия сервера: 5.7.39
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `code-storm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('WebSite','WebApp','App','Bot','Learning','Excel','Finance') NOT NULL,
  `status` enum('active','on-hold','closed') NOT NULL,
  `icon` enum('webSite.svg','webApp.svg','phone.svg','bot.svg','learning.svg','excel.svg','finance.svg') NOT NULL,
  `icon_bg` enum('FFEAF8','DEEDFF','E9E0F5','FFF8EA','FFEEEC','D6E6FF','E9D9FF') NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `type`, `status`, `icon`, `icon_bg`, `created_by`) VALUES
(2, 'CodeStorm', 'Description', 'WebApp', 'active', 'webApp.svg', 'DEEDFF', 1),
(3, 'TechnoMentoHub', 'Learning platform', 'WebSite', 'on-hold', 'webSite.svg', 'FFEAF8', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `project_members`
--

CREATE TABLE `project_members` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `project_members`
--

INSERT INTO `project_members` (`project_id`, `user_id`) VALUES
(2, 1),
(2, 2),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` date DEFAULT NULL,
  `status` enum('to-do','in-progress','completed','closed') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `img` varchar(250) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `tag_bg` varchar(20) DEFAULT NULL,
  `completed_by` int(11) DEFAULT NULL,
  `completed_day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `created_by`, `created_date`, `deadline`, `status`, `title`, `description`, `img`, `tag`, `tag_bg`, `completed_by`, `completed_day`) VALUES
(1, 2, 1, '2025-02-23 15:30:47', '2025-02-27', 'to-do', 'Task 1', '', 'http://code-storm/media/tasks/task_67bb14f1111d14.84707356.jpg', 'backend', 'D9F0E4', NULL, NULL),
(2, 2, 1, '2025-02-23 15:32:12', '2025-02-28', 'to-do', 'Task 2', '', '', 'API', 'F9E2D3', NULL, NULL),
(3, 2, 1, '2025-02-23 15:33:09', '2025-02-28', 'to-do', 'Task 3', '', '', 'deployment', 'D5F4FF', NULL, NULL),
(4, 3, 1, '2025-02-23 15:35:02', '2025-02-28', 'to-do', 'Task 4', '', 'http://code-storm/media/tasks/task_67bb15ee361157.08517320.jpg', 'security', 'D4E1FF', NULL, NULL),
(5, 3, 1, '2025-02-23 15:35:23', '2025-02-28', 'to-do', 'Task 5', '', '', 'optimization', 'F1F9E8', NULL, NULL),
(6, 2, 1, '2025-02-23 16:40:39', '2026-02-07', 'to-do', 'Сделать что то', '', 'http://code-storm/media/tasks/task_67bb2551bc0ac9.90534991.jpg', 'testing', 'FFEBF0', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `task_assigned_to`
--

CREATE TABLE `task_assigned_to` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `task_assigned_to`
--

INSERT INTO `task_assigned_to` (`task_id`, `user_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(6, 1),
(6, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `task_tags`
--

CREATE TABLE `task_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `bkg` varchar(50) NOT NULL,
  `direction` enum('full-stack','back-end','front-end','designer','project-manager','team-lead') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `task_tags`
--

INSERT INTO `task_tags` (`id`, `name`, `bkg`, `direction`) VALUES
(3, 'backend', 'D9F0E4', 'back-end'),
(4, 'API', 'F9E2D3', 'back-end'),
(5, 'bug', 'FFCDD2', 'back-end'),
(6, 'feature', 'A7D8D8', 'back-end'),
(7, 'optimization', 'F1F9E8', 'back-end'),
(8, 'security', 'D4E1FF', 'back-end'),
(9, 'testing', 'FFEBF0', 'back-end'),
(10, 'deployment', 'D5F4FF', 'back-end'),
(11, 'other', 'D6E8D1', 'back-end'),
(12, 'UI/UX', 'FFEBE0', 'designer'),
(13, 'wireframe', 'D9E8F5', 'designer'),
(14, 'visual design', 'F4D1D9', 'designer'),
(15, 'branding', 'FFD8A6', 'designer'),
(16, 'responsive', 'D3F1F3', 'designer'),
(17, 'illustration', 'F9C9E0', 'designer'),
(18, 'animation', 'E9F8FF', 'designer'),
(19, 'other', 'D6E8D1', 'designer'),
(20, 'frontend', 'C9D9FF', 'front-end'),
(21, 'bug', 'FFCDD2', 'front-end'),
(22, 'API', 'F9E2D3', 'front-end'),
(23, 'feature', 'A7D8D8', 'front-end'),
(24, 'optimization', 'F1F9E8', 'front-end'),
(25, 'testing', 'FFEBF0', 'front-end'),
(26, 'deployment', 'D5F4FF', 'front-end'),
(27, 'other', 'D6E8D1', 'front-end'),
(28, 'backend', 'D9F0E4', 'full-stack'),
(29, 'frontend', 'C9D9FF', 'full-stack'),
(30, 'API', 'F9E2D3', 'full-stack'),
(31, 'bug', 'FFCDD2', 'full-stack'),
(32, 'feature', 'A7D8D8', 'full-stack'),
(33, 'optimization', 'F1F9E8', 'full-stack'),
(34, 'security', 'D4E1FF', 'full-stack'),
(35, 'testing', 'FFEBF0', 'full-stack'),
(36, 'deployment', 'D5F4FF', 'full-stack'),
(37, 'other', 'D6E8D1', 'full-stack'),
(38, 'planning', 'F4E1D2', 'project-manager'),
(39, 'sprint', 'D2F2D5', 'project-manager'),
(40, 'milestone', 'F1E4FF', 'project-manager'),
(41, 'priority', 'F9D9FF', 'project-manager'),
(42, 'stakeholder', 'E3F1FF', 'project-manager'),
(43, 'roadmap', 'FFF0D1', 'project-manager'),
(44, 'meeting', 'E1F3F3', 'project-manager'),
(45, 'documentation', 'FFF1E1', 'project-manager'),
(46, 'budgeting', 'D7F1FF', 'project-manager'),
(47, 'other', 'D6E8D1', 'project-manager'),
(48, 'backend', 'D9F0E4', 'team-lead'),
(49, 'API', 'F9E2D3', 'team-lead'),
(50, 'bug', 'FFCDD2', 'team-lead'),
(51, 'feature', 'A7D8D8', 'team-lead'),
(52, 'optimization', 'F1F9E8', 'team-lead'),
(53, 'security', 'D4E1FF', 'team-lead'),
(54, 'testing', 'FFEBF0', 'team-lead'),
(55, 'deployment', 'D5F4FF', 'team-lead'),
(56, 'other', 'D6E8D1', 'team-lead'),
(57, 'UI/UX', 'FFEBE0', 'team-lead'),
(58, 'wireframe', 'D9E8F5', 'team-lead'),
(59, 'visual design', 'F4D1D9', 'team-lead'),
(60, 'branding', 'FFD8A6', 'team-lead'),
(61, 'responsive', 'D3F1F3', 'team-lead'),
(62, 'illustration', 'F9C9E0', 'team-lead'),
(63, 'animation', 'E9F8FF', 'team-lead'),
(64, 'frontend', 'C9D9FF', 'team-lead'),
(65, 'planning', 'F4E1D2', 'team-lead'),
(66, 'sprint', 'D2F2D5', 'team-lead'),
(67, 'milestone', 'F1E4FF', 'team-lead'),
(68, 'priority', 'F9D9FF', 'team-lead'),
(69, 'stakeholder', 'E3F1FF', 'team-lead'),
(70, 'roadmap', 'FFF0D1', 'team-lead'),
(71, 'meeting', 'E1F3F3', 'team-lead'),
(72, 'documentation', 'FFF1E1', 'team-lead'),
(73, 'budgeting', 'D7F1FF', 'team-lead');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'media/default_photo.jpg',
  `can_assign_tasks` tinyint(1) NOT NULL DEFAULT '0',
  `github_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `photo`, `can_assign_tasks`, `github_link`, `linkedin_link`, `created_at`, `role`) VALUES
(1, 'khalil@gmail.com', '$2y$10$xGlwSbIl4.ekGEA3Zrq/Ve4/AbDqbcNPaiE.rGuMJBGFO3aYTCftS', 'Khalil', 'http://code-storm/media/users/avatar_67baff7c4ce905.76118532.jpg', 1, 'https://github.com/khal1l-0', 'https://linkedin.com/in/khal1l0', '2025-02-23 08:05:39', 'team-lead'),
(2, 'a@gmail.com', '$2y$10$T9hzi9TBz8kRoywLux0bZ.7vCw/Je.FaT2TxabEZYnFzu.3Yjjqm6', 'Oybek', 'http://code-storm/media/default_photo.jpg', 1, NULL, NULL, '2025-02-23 11:57:38', 'front-end');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_created_by` (`created_by`);

--
-- Индексы таблицы `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`project_id`,`user_id`),
  ADD KEY `idx_project_id` (`project_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_id` (`project_id`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Индексы таблицы `task_assigned_to`
--
ALTER TABLE `task_assigned_to`
  ADD PRIMARY KEY (`task_id`,`user_id`),
  ADD KEY `idx_task_id` (`task_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Индексы таблицы `task_tags`
--
ALTER TABLE `task_tags`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `task_tags`
--
ALTER TABLE `task_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `fk_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_task_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `task_assigned_to`
--
ALTER TABLE `task_assigned_to`
  ADD CONSTRAINT `fk_task_assigned_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_task_assigned_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

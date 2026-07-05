SET NAMES utf8mb4;

INSERT INTO `main` (`id`, `title`, `title_en`, `description`, `description_en`, `main__title`, `main__title_en`, `main__text`, `main__text_en`, `discord`, `logo_path`, `created_at`, `updated_at`) VALUES
(1, 'WoW Free server', 'WoW Free server', 'WotLK 3.3.5a private server', 'WotLK 3.3.5a private server', 'WoW Free server', 'WoW Free server', 'WotLK 3.3.5a, живой прогресс и аккуратный старт без лишнего шума.', 'WotLK 3.3.5a, steady progression and a clean start.', '#', 'powerpuffsite/images/main/logo.png', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `title_en` = VALUES(`title_en`),
  `description` = VALUES(`description`),
  `description_en` = VALUES(`description_en`),
  `main__title` = VALUES(`main__title`),
  `main__title_en` = VALUES(`main__title_en`),
  `main__text` = VALUES(`main__text`),
  `main__text_en` = VALUES(`main__text_en`),
  `discord` = VALUES(`discord`),
  `logo_path` = VALUES(`logo_path`);

INSERT INTO `realms` (`id`, `name`, `name_en`, `rate`, `version`, `description`, `description_en`, `proffesion`, `gold`, `rep`, `loot`, `honor`, `created_at`, `updated_at`) VALUES
(1, 'Xronus', 'Xronus', 'x5', 'lich', 'Классический Wrath of the Lich King 3.3.5a с комфортным стартом.', 'Classic Wrath of the Lich King 3.3.5a with a comfortable start.', 'x5', 'x5', 'x5', 'x5', 'x5', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `name_en` = VALUES(`name_en`),
  `rate` = VALUES(`rate`),
  `version` = VALUES(`version`),
  `description` = VALUES(`description`),
  `description_en` = VALUES(`description_en`);

INSERT INTO `news` (`id`, `date`, `text`, `text_en`, `content`, `content_en`, `images`, `created_at`, `updated_at`) VALUES
(1, '14.06.2026', 'Открытое тестирование началось', 'Open testing has begun', 'Мы подняли локальную тестовую сборку, подключили базу и готовим сайт к переносу на Laravel-основу.', 'We launched a local test build, connected the database, and prepared the site for the Laravel-based version.', 'xvrx-assets/images/title_texture.jpg', NOW(), NOW()),
(2, '13.06.2026', 'Подготовка игрового клиента', 'Game client preparation', 'Раздел скачивания будет использовать данные старой таблицы how_to_start и сохранит удобный путь старта.', 'The download section will use the old how_to_start table and keep a convenient start flow.', 'xvrx-assets/images/wotlk-page-bg-2.png', NOW(), NOW()),
(3, '12.06.2026', 'Рейтинг арены и персонажей', 'Arena and character ladder', 'Ладдер подключается к characters DB и показывает арены, убийства и время игры.', 'The ladder connects to the characters DB and shows arenas, kills, and playtime.', 'xvrx-assets/images/wotlk-page-bg-2.png', NOW(), NOW()),
(4, '11.06.2026', 'Северный поход уже близко', 'The northern campaign is close', 'Команда готовит первые игровые события, стартовые маршруты и награды для игроков, которые придут на тестовый запуск.', 'The team is preparing first in-game events, starter routes, and rewards for players joining the test launch.', 'xvrx-assets/images/wotlk-page-bg-2.png', NOW(), NOW()),
(5, '10.06.2026', 'Проверка стабильности реалма', 'Realm stability check', 'Мы прогоняем базовые сценарии входа, создания персонажей, телепортов и сохранения прогресса перед следующим этапом тестирования.', 'We are testing login, character creation, teleports, and progression persistence before the next testing phase.', 'xvrx-assets/images/title_texture.jpg', NOW(), NOW()),
(6, '09.06.2026', 'Подготовка личного кабинета', 'Account panel preparation', 'Личный кабинет получает единый визуальный стиль, разделы персонажей и голосования остаются подключенными к старой Laravel-схеме.', 'The account panel receives a unified visual style while characters and voting stay connected to the old Laravel schema.', 'xvrx-assets/images/wotlk-page-bg-2.png', NOW(), NOW()),
(7, '08.06.2026', 'Первые награды за активность', 'First activity rewards', 'Для тестового запуска готовятся бонусы за голосование, участие в проверках и первые найденные ошибки.', 'Test-launch rewards are being prepared for voting, participation, and first reported issues.', 'xvrx-assets/images/wotlk-page-bg-2.png', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `date` = VALUES(`date`),
  `text` = VALUES(`text`),
  `text_en` = VALUES(`text_en`),
  `content` = VALUES(`content`),
  `content_en` = VALUES(`content_en`),
  `images` = VALUES(`images`);

INSERT INTO `social_link` (`id`, `name`, `link`, `class`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Telegram', '#', 'social-rail-link ri-telegram-fill', 'ri-telegram-fill', 1, NOW(), NOW()),
(2, 'Discord', '#', 'social-rail-link ri-discord-fill', 'ri-discord-fill', 1, NOW(), NOW()),
(3, 'VK', '#', 'social-rail-link ri-vk-fill', 'ri-vk-fill', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `link` = VALUES(`link`),
  `class` = VALUES(`class`),
  `icon` = VALUES(`icon`),
  `is_active` = VALUES(`is_active`);

INSERT INTO `how_to_start` (`id`, `client_size`, `google_drive_url`, `google_drive_active`, `yandex_disk_url`, `yandex_disk_active`, `filemail_url`, `filemail_active`, `mega_url`, `mega_active`, `torrent_url`, `torrent_active`, `launcher_text_ru`, `launcher_text_en`, `launcher_url`, `launcher_description_ru`, `launcher_description_en`, `req_storage_min`, `req_storage_rec`, `req_windows_min`, `req_windows_rec`, `req_ram_min`, `req_ram_rec`, `req_gpu_min`, `req_gpu_rec`, `req_internet_min`, `req_internet_rec`, `created_at`, `updated_at`) VALUES
(1, '25.6 GB', '#', 1, '#', 1, '#', 0, '#', 1, '#', 1, 'Скачать лаунчер', 'Download launcher', '#', 'Лаунчер скоро будет доступен для тестирования.', 'The launcher will be available for testing soon.', '27 GB', '30 GB', 'Windows 7', 'Windows 10', '2 GB', '6 GB', '256 MB', '1024 MB', '10 Mbit/s', '50 Mbit/s', NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `client_size` = VALUES(`client_size`),
  `google_drive_url` = VALUES(`google_drive_url`),
  `yandex_disk_url` = VALUES(`yandex_disk_url`),
  `mega_url` = VALUES(`mega_url`),
  `torrent_url` = VALUES(`torrent_url`),
  `launcher_text_ru` = VALUES(`launcher_text_ru`),
  `launcher_text_en` = VALUES(`launcher_text_en`),
  `launcher_description_ru` = VALUES(`launcher_description_ru`),
  `launcher_description_en` = VALUES(`launcher_description_en`);

INSERT INTO `features` (`id`, `title_ru`, `title_en`, `description_ru`, `description_en`, `image`, `status`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'Стабильный WotLK', 'Stable WotLK', 'Основа 3.3.5a с понятным прогрессом и классическим темпом игры.', 'A 3.3.5a foundation with clear progression and classic game rhythm.', 'xvrx-assets/images/title_texture.jpg', 1, 1, NOW(), NOW()),
(2, 'Личный кабинет', 'Account panel', 'Регистрация, персонажи, голосования и управление аккаунтом.', 'Registration, characters, voting, and account management.', 'xvrx-assets/images/wotlk-page-bg-2.png', 1, 2, NOW(), NOW()),
(3, 'PvP рейтинг', 'PvP ladder', 'Арена, убийства и время игры читаются из characters базы.', 'Arena, kills, and playtime are read from the characters database.', 'xvrx-assets/images/wotlk-page-bg-2.png', 1, 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `title_ru` = VALUES(`title_ru`),
  `title_en` = VALUES(`title_en`),
  `description_ru` = VALUES(`description_ru`),
  `description_en` = VALUES(`description_en`),
  `image` = VALUES(`image`),
  `status` = VALUES(`status`),
  `sort` = VALUES(`sort`);

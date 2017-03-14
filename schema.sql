CREATE TABLE `logs` (
  `destination` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL,
  `protocol` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `format` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `baud` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

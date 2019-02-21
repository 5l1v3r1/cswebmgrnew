
-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `db_auth_group`
--

CREATE TABLE `db_auth_group` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `title` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `db_auth_group`
--

INSERT INTO `db_auth_group` (`id`, `title`, `status`, `rules`, `description`) VALUES
(1, 'administrators', 1, '1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,', '');

-- --------------------------------------------------------

--
-- Table structure for table `db_auth_group_access`
--

CREATE TABLE `db_auth_group_access` (
  `uid` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `db_auth_rule`
--

CREATE TABLE `db_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` char(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT '1',
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `db_auth_rule`
--

INSERT INTO `db_auth_rule` (`id`, `name`, `title`, `status`, `condition`, `type`, `description`) VALUES
(1000, 'Admin-Dashboard-index', 'index', 1, '', 1, 'Dashboard 100X'),
(1001, 'Admin-Dashboard-getDayData', 'Dashboard-getDayData', 1, '', 1, 'Dashboard 100X'),
(1002, 'Admin-Dashboard-getMonthData', 'getMonthData', 1, '', 1, ''),
(1003, 'Admin-Dashboard-getYearData', 'getYearData', 1, '', 1, ''),
(1004, 'Admin-Dashboard-showDataMoneyAnalysis', 'Dashboard-showDataMoneyAnalysis', 1, '', 1, ''),
(1005, 'Admin-Dashboard-getDayToDay', 'Admin-Dashboard-getDayToDay', 1, '', 1, ''),
(1006, 'Admin-Dashboard-getEachMonth', 'Admin-Dashboard-getEachMonth', 1, '', 1, ''),
(1007, 'Admin-Dashboard-getMonths', 'Admin-Dashboard-getMonths', 1, '', 1, ''),
(1008, 'Admin-Dashboard-getMonthsProfitPerDay', 'Admin-Dashboard-getMonthsProfitPerDay', 1, '', 1, ''),
(1009, 'Admin-Dashboard-getQdatas', 'Admin-Dashboard-getQdatas', 1, '', 1, ''),
(1010, 'Admin-Dashboard-getYearProfitPercent', 'Admin-Dashboard-getYearProfitPercent', 1, '', 1, ''),
(1011, 'Admin-Dashboard-getWeekData', 'Admin-Dashboard-getWeekData', 1, '', 1, '');



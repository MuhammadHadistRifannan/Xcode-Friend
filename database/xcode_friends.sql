-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 27 Agu 2026 pada 06.41
-- Versi server: 8.4.3
-- Versi PHP: 8.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `xcode_friends`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_accounts`
--

CREATE TABLE `jcow_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `fbid` bigint NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lastact` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` tinyint NOT NULL DEFAULT '0',
  `points` int NOT NULL,
  `avatar` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `blurbs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_permission` tinyint NOT NULL DEFAULT '0',
  `location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastlogin` int NOT NULL,
  `ipaddress` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chpass` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disabled` tinyint NOT NULL,
  `intr` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` tinyint NOT NULL,
  `about_me` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthyear` int NOT NULL,
  `birthmonth` tinyint NOT NULL,
  `birthday` tinyint NOT NULL,
  `hide_age` tinyint NOT NULL,
  `reg_code` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `forum_posts` int NOT NULL,
  `featured` tinyint NOT NULL,
  `roles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jcowsess` char(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wall_id` int NOT NULL,
  `followers` int NOT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `var1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var4` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var5` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var6` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var7` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hide_me` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_banned`
--

CREATE TABLE `jcow_banned` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip1` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip2` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip3` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip4` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL,
  `expired` int NOT NULL,
  `operator` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_blacks`
--

CREATE TABLE `jcow_blacks` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL DEFAULT '0',
  `bid` int NOT NULL DEFAULT '0',
  `bname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_cache`
--

CREATE TABLE `jcow_cache` (
  `ckey` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_chatbar`
--

CREATE TABLE `jcow_chatbar` (
  `id` bigint UNSIGNED NOT NULL,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent` datetime DEFAULT NULL,
  `recd` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_chatrooms`
--

CREATE TABLE `jcow_chatrooms` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `fid` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated` int NOT NULL,
  `created` int NOT NULL,
  `request_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_comments`
--

CREATE TABLE `jcow_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `target_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` int NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL,
  `stream_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_favorites`
--

CREATE TABLE `jcow_favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `fuid` int NOT NULL,
  `fapp` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fsid` int NOT NULL,
  `created` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_followers`
--

CREATE TABLE `jcow_followers` (
  `uid` int NOT NULL,
  `fid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_forums`
--

CREATE TABLE `jcow_forums` (
  `id` bigint UNSIGNED NOT NULL,
  `weight` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type_pic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `rules` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `forum_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `threads` int NOT NULL DEFAULT '0',
  `posts` int NOT NULL DEFAULT '0',
  `lastpostname` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastposttopicid` int NOT NULL DEFAULT '0',
  `lastposttopic` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastpostcreated` int NOT NULL DEFAULT '0',
  `moderator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fmembers` int NOT NULL DEFAULT '0',
  `image` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_roles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_roles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thread_roles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_roles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `moderators` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_forum_attachments`
--

CREATE TABLE `jcow_forum_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `pid` int NOT NULL,
  `tid` int NOT NULL,
  `uri` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `des` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `orginal_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `downloads` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_forum_polls`
--

CREATE TABLE `jcow_forum_polls` (
  `id` bigint UNSIGNED NOT NULL,
  `tid` int NOT NULL DEFAULT '0',
  `question` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` int NOT NULL DEFAULT '0',
  `options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `timeout` int NOT NULL DEFAULT '0',
  `options_per_user` tinyint NOT NULL DEFAULT '0',
  `voters` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_forum_posts`
--

CREATE TABLE `jcow_forum_posts` (
  `id` bigint UNSIGNED NOT NULL,
  `tid` bigint NOT NULL DEFAULT '0',
  `uid` int NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL DEFAULT '0',
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_first` tinyint NOT NULL DEFAULT '0',
  `attach` int NOT NULL DEFAULT '0',
  `bbcode_off` tinyint NOT NULL DEFAULT '0',
  `emote_off` tinyint NOT NULL DEFAULT '0',
  `got_attach` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_forum_subscribes`
--

CREATE TABLE `jcow_forum_subscribes` (
  `uid` int NOT NULL,
  `tid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_forum_threads`
--

CREATE TABLE `jcow_forum_threads` (
  `id` bigint UNSIGNED NOT NULL,
  `fid` int NOT NULL DEFAULT '0',
  `old_fid` int NOT NULL,
  `pid` int NOT NULL,
  `userid` int NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `posts` int NOT NULL DEFAULT '0',
  `closed` smallint NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `lastpostusername` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `lastpostcreated` int NOT NULL DEFAULT '0',
  `icon` tinyint NOT NULL DEFAULT '0',
  `thread_type` tinyint NOT NULL DEFAULT '0',
  `thread_lock` tinyint NOT NULL DEFAULT '0',
  `got_poll` tinyint NOT NULL DEFAULT '0',
  `got_attach` tinyint NOT NULL,
  `stressed` tinyint NOT NULL DEFAULT '0',
  `digg` int NOT NULL DEFAULT '0',
  `dugg` int NOT NULL DEFAULT '0',
  `votes` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_friends`
--

CREATE TABLE `jcow_friends` (
  `uid` int NOT NULL DEFAULT '0',
  `fid` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_friend_reqs`
--

CREATE TABLE `jcow_friend_reqs` (
  `uid` int NOT NULL DEFAULT '0',
  `fid` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `msg` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_groups`
--

CREATE TABLE `jcow_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `uri` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slogan` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creatorid` int NOT NULL,
  `creator` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `members` int NOT NULL,
  `created` int NOT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `private` tinyint NOT NULL,
  `needapproval` tinyint NOT NULL,
  `posts` int NOT NULL,
  `topics` int NOT NULL,
  `lastptime` int NOT NULL,
  `lastpname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `style_ids` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` char(2) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_categories`
--

CREATE TABLE `jcow_group_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groups` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_members`
--

CREATE TABLE `jcow_group_members` (
  `gid` int NOT NULL,
  `uid` int NOT NULL,
  `created` int NOT NULL,
  `nickname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about_me` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hide_profile` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_members_pending`
--

CREATE TABLE `jcow_group_members_pending` (
  `uid` int NOT NULL,
  `gid` int NOT NULL,
  `created` int NOT NULL,
  `ignored` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_polls`
--

CREATE TABLE `jcow_group_polls` (
  `id` bigint UNSIGNED NOT NULL,
  `tid` int NOT NULL DEFAULT '0',
  `question` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created` int NOT NULL DEFAULT '0',
  `options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `timeout` int NOT NULL DEFAULT '0',
  `options_per_user` tinyint NOT NULL DEFAULT '0',
  `voters` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_postcats`
--

CREATE TABLE `jcow_group_postcats` (
  `id` bigint UNSIGNED NOT NULL,
  `gid` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_posts`
--

CREATE TABLE `jcow_group_posts` (
  `id` bigint UNSIGNED NOT NULL,
  `gid` int NOT NULL,
  `tid` bigint NOT NULL DEFAULT '0',
  `uid` int NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rtid` int NOT NULL,
  `rid` int NOT NULL,
  `rname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL DEFAULT '0',
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `attach` int NOT NULL DEFAULT '0',
  `bbcode_off` tinyint NOT NULL DEFAULT '0',
  `emote_off` tinyint NOT NULL DEFAULT '0',
  `got_attach` tinyint NOT NULL,
  `topic` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_first` tinyint NOT NULL,
  `replies` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_group_topics`
--

CREATE TABLE `jcow_group_topics` (
  `id` bigint UNSIGNED NOT NULL,
  `gid` int NOT NULL DEFAULT '0',
  `old_fid` int NOT NULL,
  `pid` int NOT NULL,
  `uid` int NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `posts` int NOT NULL DEFAULT '0',
  `closed` smallint NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `lastpostusername` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `lastpostcreated` int NOT NULL DEFAULT '0',
  `icon` tinyint NOT NULL DEFAULT '0',
  `thread_type` tinyint NOT NULL DEFAULT '0',
  `thread_lock` tinyint NOT NULL DEFAULT '0',
  `got_poll` tinyint NOT NULL DEFAULT '0',
  `got_attach` tinyint NOT NULL,
  `stressed` tinyint NOT NULL DEFAULT '0',
  `digg` int NOT NULL DEFAULT '0',
  `dugg` int NOT NULL DEFAULT '0',
  `votes` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_gvars`
--

CREATE TABLE `jcow_gvars` (
  `gkey` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gvalue` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_invites`
--

CREATE TABLE `jcow_invites` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_langs`
--

CREATE TABLE `jcow_langs` (
  `lang_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lang_to` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_liked`
--

CREATE TABLE `jcow_liked` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `stream_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_menu`
--

CREATE TABLE `jcow_menu` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tab_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int NOT NULL DEFAULT '0',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `app` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `actived` tinyint NOT NULL DEFAULT '0',
  `type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `protected` tinyint NOT NULL,
  `allowed_roles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_messages`
--

CREATE TABLE `jcow_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` int NOT NULL DEFAULT '0',
  `to_id` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `hasread` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_messages_sent`
--

CREATE TABLE `jcow_messages_sent` (
  `id` bigint UNSIGNED NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` int NOT NULL DEFAULT '0',
  `to_id` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `hasread` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_modules`
--

CREATE TABLE `jcow_modules` (
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `actived` tinyint NOT NULL DEFAULT '0',
  `hooking` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_pages`
--

CREATE TABLE `jcow_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `uri` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` int NOT NULL,
  `views` int NOT NULL,
  `logo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `style_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `background` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `users` int NOT NULL,
  `updated` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_page_users`
--

CREATE TABLE `jcow_page_users` (
  `pid` int NOT NULL,
  `uid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_profiles`
--

CREATE TABLE `jcow_profiles` (
  `id` int NOT NULL,
  `style_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `background` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `videoid` int NOT NULL,
  `favorites` int NOT NULL,
  `views` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_profile_comments`
--

CREATE TABLE `jcow_profile_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `target_id` int NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL,
  `stream_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_reports`
--

CREATE TABLE `jcow_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hasread` tinyint NOT NULL,
  `created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_roles`
--

CREATE TABLE `jcow_roles` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_stories`
--

CREATE TABLE `jcow_stories` (
  `id` bigint UNSIGNED NOT NULL,
  `cid` int NOT NULL DEFAULT '0',
  `sticky` tinyint NOT NULL,
  `closed` tinyint NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `lastreply` int NOT NULL DEFAULT '0',
  `lastreplyuname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastreplyuid` int NOT NULL,
  `updated` int NOT NULL DEFAULT '0',
  `views` int NOT NULL,
  `comments` int NOT NULL,
  `stream_id` int NOT NULL,
  `app` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `digg` int NOT NULL,
  `dugg` int NOT NULL,
  `photos` int NOT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured` tinyint NOT NULL,
  `var1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `var2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `var3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `var4` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `var5` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `text1` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text2` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `blob1` blob NOT NULL,
  `rating` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_id` int NOT NULL,
  `page_type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_story_categories`
--

CREATE TABLE `jcow_story_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `gid` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int NOT NULL DEFAULT '0',
  `app` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `var1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var4` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `var5` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_story_cat_groups`
--

CREATE TABLE `jcow_story_cat_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_story_photos`
--

CREATE TABLE `jcow_story_photos` (
  `id` bigint UNSIGNED NOT NULL,
  `sid` int NOT NULL,
  `uri` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `des` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_streams`
--

CREATE TABLE `jcow_streams` (
  `id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `wall_id` int NOT NULL,
  `uid` int NOT NULL,
  `attachment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL,
  `type` tinyint NOT NULL,
  `app` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aid` int NOT NULL,
  `hide` tinyint NOT NULL,
  `likes` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_subscr`
--

CREATE TABLE `jcow_subscr` (
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` int NOT NULL,
  `timeline` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_tags`
--

CREATE TABLE `jcow_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_tag_ids`
--

CREATE TABLE `jcow_tag_ids` (
  `tid` int NOT NULL,
  `sid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_texts`
--

CREATE TABLE `jcow_texts` (
  `tkey` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tvalue` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_tmp`
--

CREATE TABLE `jcow_tmp` (
  `tkey` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tcontent` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_user_crafts`
--

CREATE TABLE `jcow_user_crafts` (
  `uid` int NOT NULL,
  `hash` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_var_cache`
--

CREATE TABLE `jcow_var_cache` (
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jcow_votes`
--

CREATE TABLE `jcow_votes` (
  `sid` int NOT NULL,
  `created` int NOT NULL,
  `rate` int NOT NULL,
  `uid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_27_080000_create_jcow_accounts_table', 1),
(5, '2026_08_27_080001_create_jcow_banned_table', 1),
(6, '2026_08_27_080002_create_jcow_blacks_table', 1),
(7, '2026_08_27_080003_create_jcow_cache_table', 1),
(8, '2026_08_27_080004_create_jcow_chatbar_table', 1),
(9, '2026_08_27_080005_create_jcow_chatrooms_table', 1),
(10, '2026_08_27_080006_create_jcow_comments_table', 1),
(11, '2026_08_27_080007_create_jcow_favorites_table', 1),
(12, '2026_08_27_080008_create_jcow_followers_table', 1),
(13, '2026_08_27_080009_create_jcow_forum_attachments_table', 1),
(14, '2026_08_27_080010_create_jcow_forum_polls_table', 1),
(15, '2026_08_27_080011_create_jcow_forum_posts_table', 1),
(16, '2026_08_27_080012_create_jcow_forum_subscribes_table', 1),
(17, '2026_08_27_080013_create_jcow_forum_threads_table', 1),
(18, '2026_08_27_080014_create_jcow_forums_table', 1),
(19, '2026_08_27_080015_create_jcow_friend_reqs_table', 1),
(20, '2026_08_27_080016_create_jcow_friends_table', 1),
(21, '2026_08_27_080017_create_jcow_group_categories_table', 1),
(22, '2026_08_27_080018_create_jcow_group_members_table', 1),
(23, '2026_08_27_080019_create_jcow_group_members_pending_table', 1),
(24, '2026_08_27_080020_create_jcow_group_polls_table', 1),
(25, '2026_08_27_080021_create_jcow_group_postcats_table', 1),
(26, '2026_08_27_080022_create_jcow_group_posts_table', 1),
(27, '2026_08_27_080023_create_jcow_group_topics_table', 1),
(28, '2026_08_27_080024_create_jcow_groups_table', 1),
(29, '2026_08_27_080025_create_jcow_gvars_table', 1),
(30, '2026_08_27_080026_create_jcow_invites_table', 1),
(31, '2026_08_27_080027_create_jcow_langs_table', 1),
(32, '2026_08_27_080028_create_jcow_liked_table', 1),
(33, '2026_08_27_080029_create_jcow_menu_table', 1),
(34, '2026_08_27_080030_create_jcow_messages_table', 1),
(35, '2026_08_27_080031_create_jcow_messages_sent_table', 1),
(36, '2026_08_27_080032_create_jcow_modules_table', 1),
(37, '2026_08_27_080033_create_jcow_page_users_table', 1),
(38, '2026_08_27_080034_create_jcow_pages_table', 1),
(39, '2026_08_27_080035_create_jcow_profile_comments_table', 1),
(40, '2026_08_27_080036_create_jcow_profiles_table', 1),
(41, '2026_08_27_080037_create_jcow_reports_table', 1),
(42, '2026_08_27_080038_create_jcow_roles_table', 1),
(43, '2026_08_27_080039_create_jcow_stories_table', 1),
(44, '2026_08_27_080040_create_jcow_story_cat_groups_table', 1),
(45, '2026_08_27_080041_create_jcow_story_categories_table', 1),
(46, '2026_08_27_080042_create_jcow_story_photos_table', 1),
(47, '2026_08_27_080043_create_jcow_streams_table', 1),
(48, '2026_08_27_080044_create_jcow_subscr_table', 1),
(49, '2026_08_27_080045_create_jcow_tag_ids_table', 1),
(50, '2026_08_27_080046_create_jcow_tags_table', 1),
(51, '2026_08_27_080047_create_jcow_texts_table', 1),
(52, '2026_08_27_080048_create_jcow_tmp_table', 1),
(53, '2026_08_27_080049_create_jcow_user_crafts_table', 1),
(54, '2026_08_27_080050_create_jcow_var_cache_table', 1),
(55, '2026_08_27_080051_create_jcow_votes_table', 1),
(56, '2026_08_27_080052_create_ratings_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `rating_id` int NOT NULL,
  `rating_num` int NOT NULL,
  `IP` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('rbXqDmWTjOyEylWiw5jVzuKaQV8HNyRkPrR7qFmM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ3Q2NTdTFTNFBKOUY1RXRDVEVUQUhSVk4wZnpiZ2NnOEo0OHBQZ2VWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9waG90b3NcL3VwbG9hZCIsInJvdXRlIjoicGhvdG9zLnVwbG9hZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1787811266),
('V9mQM5YlDzVG7kN3XPzdnXzU1aVpS2jBmKg6Tchs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIwUWVqQ2QxeWd6bklNbTFCT0F1WnhBdmREbG9SbFZmd1g4MzdFcGdCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC92aWRlbyIsInJvdXRlIjoidmlkZW8uaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1787804260),
('zylBYiq2TQjOQLghCYbTIcYI8claHquAXV1dXF6r', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.134.0 Chrome/148.0.7778.280 Electron/42.8.1 Safari/537.36', 'eyJfdG9rZW4iOiJNdkZ6OTJpQ08yRjBFY3VFazk0QTYzR2REQVJKNzgzdzU5cDNSeENGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1787800041);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `jcow_accounts`
--
ALTER TABLE `jcow_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_accounts_username` (`username`),
  ADD KEY `jcow_accounts_lastlogin` (`lastlogin`),
  ADD KEY `jcow_accounts_email` (`email`),
  ADD KEY `jcow_accounts_fbid` (`fbid`);

--
-- Indeks untuk tabel `jcow_banned`
--
ALTER TABLE `jcow_banned`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_blacks`
--
ALTER TABLE `jcow_blacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_blacks_uid` (`uid`,`bid`);

--
-- Indeks untuk tabel `jcow_chatbar`
--
ALTER TABLE `jcow_chatbar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_chatrooms`
--
ALTER TABLE `jcow_chatrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_chatrooms_uid` (`uid`,`fid`);

--
-- Indeks untuk tabel `jcow_comments`
--
ALTER TABLE `jcow_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_comments_target_id` (`target_id`),
  ADD KEY `jcow_comments_stream_id` (`stream_id`);

--
-- Indeks untuk tabel `jcow_favorites`
--
ALTER TABLE `jcow_favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_favorites_uid` (`uid`,`fuid`,`fsid`,`created`);

--
-- Indeks untuk tabel `jcow_followers`
--
ALTER TABLE `jcow_followers`
  ADD KEY `jcow_followers_uid` (`uid`,`fid`);

--
-- Indeks untuk tabel `jcow_forums`
--
ALTER TABLE `jcow_forums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_forums_belong_id` (`parent_id`),
  ADD KEY `jcow_forums_order_num` (`weight`),
  ADD KEY `jcow_forums_type_class` (`forum_type`);

--
-- Indeks untuk tabel `jcow_forum_attachments`
--
ALTER TABLE `jcow_forum_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_forum_attachments_pid` (`pid`),
  ADD KEY `jcow_forum_attachments_tid` (`tid`);

--
-- Indeks untuk tabel `jcow_forum_polls`
--
ALTER TABLE `jcow_forum_polls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_forum_polls_tid` (`tid`);

--
-- Indeks untuk tabel `jcow_forum_posts`
--
ALTER TABLE `jcow_forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_forum_posts_tid` (`tid`),
  ADD KEY `jcow_forum_posts_author_id` (`uid`);

--
-- Indeks untuk tabel `jcow_forum_subscribes`
--
ALTER TABLE `jcow_forum_subscribes`
  ADD KEY `jcow_forum_subscribes_uid` (`uid`,`tid`);

--
-- Indeks untuk tabel `jcow_forum_threads`
--
ALTER TABLE `jcow_forum_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_forum_threads_fid` (`fid`),
  ADD KEY `jcow_forum_threads_thread_type` (`thread_type`);

--
-- Indeks untuk tabel `jcow_friends`
--
ALTER TABLE `jcow_friends`
  ADD KEY `jcow_friends_uid` (`uid`,`fid`),
  ADD KEY `jcow_friends_fid` (`fid`);

--
-- Indeks untuk tabel `jcow_friend_reqs`
--
ALTER TABLE `jcow_friend_reqs`
  ADD KEY `jcow_friend_reqs_uid` (`uid`,`fid`);

--
-- Indeks untuk tabel `jcow_groups`
--
ALTER TABLE `jcow_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_groups_creatorid` (`creatorid`),
  ADD KEY `jcow_groups_uri` (`uri`);

--
-- Indeks untuk tabel `jcow_group_categories`
--
ALTER TABLE `jcow_group_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_group_members`
--
ALTER TABLE `jcow_group_members`
  ADD KEY `jcow_group_members_gid` (`gid`);

--
-- Indeks untuk tabel `jcow_group_members_pending`
--
ALTER TABLE `jcow_group_members_pending`
  ADD KEY `jcow_group_members_pending_uid` (`uid`,`gid`);

--
-- Indeks untuk tabel `jcow_group_polls`
--
ALTER TABLE `jcow_group_polls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_group_polls_tid` (`tid`);

--
-- Indeks untuk tabel `jcow_group_postcats`
--
ALTER TABLE `jcow_group_postcats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_group_postcats_gid` (`gid`);

--
-- Indeks untuk tabel `jcow_group_posts`
--
ALTER TABLE `jcow_group_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_group_posts_tid` (`tid`),
  ADD KEY `jcow_group_posts_uid` (`uid`),
  ADD KEY `jcow_group_posts_gid` (`gid`),
  ADD KEY `jcow_group_posts_rtid` (`rtid`),
  ADD KEY `jcow_group_posts_rid` (`rid`);

--
-- Indeks untuk tabel `jcow_group_topics`
--
ALTER TABLE `jcow_group_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_group_topics_gid` (`gid`);

--
-- Indeks untuk tabel `jcow_gvars`
--
ALTER TABLE `jcow_gvars`
  ADD KEY `jcow_gvars_gkey` (`gkey`);

--
-- Indeks untuk tabel `jcow_invites`
--
ALTER TABLE `jcow_invites`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_langs`
--
ALTER TABLE `jcow_langs`
  ADD KEY `jcow_langs_lang_from` (`lang_from`);

--
-- Indeks untuk tabel `jcow_liked`
--
ALTER TABLE `jcow_liked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_liked_uid` (`uid`),
  ADD KEY `jcow_liked_stream_id` (`stream_id`);

--
-- Indeks untuk tabel `jcow_menu`
--
ALTER TABLE `jcow_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_messages`
--
ALTER TABLE `jcow_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_messages_from_id` (`from_id`,`to_id`);

--
-- Indeks untuk tabel `jcow_messages_sent`
--
ALTER TABLE `jcow_messages_sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_messages_sent_from_id` (`from_id`,`to_id`);

--
-- Indeks untuk tabel `jcow_modules`
--
ALTER TABLE `jcow_modules`
  ADD KEY `jcow_modules_name` (`name`);

--
-- Indeks untuk tabel `jcow_pages`
--
ALTER TABLE `jcow_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_pages_uid` (`uid`),
  ADD KEY `jcow_pages_uri` (`uri`);

--
-- Indeks untuk tabel `jcow_page_users`
--
ALTER TABLE `jcow_page_users`
  ADD KEY `jcow_page_users_pid` (`pid`,`uid`);

--
-- Indeks untuk tabel `jcow_profiles`
--
ALTER TABLE `jcow_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_profile_comments`
--
ALTER TABLE `jcow_profile_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_profile_comments_stream_id` (`stream_id`);

--
-- Indeks untuk tabel `jcow_reports`
--
ALTER TABLE `jcow_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_roles`
--
ALTER TABLE `jcow_roles`
  ADD KEY `jcow_roles_id` (`id`);

--
-- Indeks untuk tabel `jcow_stories`
--
ALTER TABLE `jcow_stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_stories_app` (`app`),
  ADD KEY `jcow_stories_uid` (`uid`),
  ADD KEY `jcow_stories_page_id` (`page_id`),
  ADD KEY `jcow_stories_cid` (`cid`);

--
-- Indeks untuk tabel `jcow_story_categories`
--
ALTER TABLE `jcow_story_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_story_categories_app` (`app`),
  ADD KEY `jcow_story_categories_weight` (`weight`);

--
-- Indeks untuk tabel `jcow_story_cat_groups`
--
ALTER TABLE `jcow_story_cat_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jcow_story_photos`
--
ALTER TABLE `jcow_story_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_story_photos_sid` (`sid`);

--
-- Indeks untuk tabel `jcow_streams`
--
ALTER TABLE `jcow_streams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_streams_app` (`app`),
  ADD KEY `jcow_streams_aid` (`aid`);

--
-- Indeks untuk tabel `jcow_subscr`
--
ALTER TABLE `jcow_subscr`
  ADD KEY `jcow_subscr_id` (`id`),
  ADD KEY `jcow_subscr_uid` (`uid`);

--
-- Indeks untuk tabel `jcow_tags`
--
ALTER TABLE `jcow_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jcow_tags_name` (`name`);

--
-- Indeks untuk tabel `jcow_tag_ids`
--
ALTER TABLE `jcow_tag_ids`
  ADD KEY `jcow_tag_ids_tid` (`tid`,`sid`);

--
-- Indeks untuk tabel `jcow_texts`
--
ALTER TABLE `jcow_texts`
  ADD KEY `jcow_texts_tkey` (`tkey`);

--
-- Indeks untuk tabel `jcow_tmp`
--
ALTER TABLE `jcow_tmp`
  ADD KEY `jcow_tmp_tkey` (`tkey`);

--
-- Indeks untuk tabel `jcow_user_crafts`
--
ALTER TABLE `jcow_user_crafts`
  ADD KEY `jcow_user_crafts_uid` (`uid`,`created`);

--
-- Indeks untuk tabel `jcow_var_cache`
--
ALTER TABLE `jcow_var_cache`
  ADD KEY `jcow_var_cache_name` (`name`,`created`);

--
-- Indeks untuk tabel `jcow_votes`
--
ALTER TABLE `jcow_votes`
  ADD KEY `jcow_votes_sid` (`sid`,`uid`),
  ADD KEY `jcow_votes_created` (`created`),
  ADD KEY `jcow_votes_uid` (`uid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_accounts`
--
ALTER TABLE `jcow_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_banned`
--
ALTER TABLE `jcow_banned`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_blacks`
--
ALTER TABLE `jcow_blacks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_chatbar`
--
ALTER TABLE `jcow_chatbar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_chatrooms`
--
ALTER TABLE `jcow_chatrooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_comments`
--
ALTER TABLE `jcow_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_favorites`
--
ALTER TABLE `jcow_favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_forums`
--
ALTER TABLE `jcow_forums`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_forum_attachments`
--
ALTER TABLE `jcow_forum_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_forum_polls`
--
ALTER TABLE `jcow_forum_polls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_forum_posts`
--
ALTER TABLE `jcow_forum_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_forum_threads`
--
ALTER TABLE `jcow_forum_threads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_groups`
--
ALTER TABLE `jcow_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_group_categories`
--
ALTER TABLE `jcow_group_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_group_polls`
--
ALTER TABLE `jcow_group_polls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_group_postcats`
--
ALTER TABLE `jcow_group_postcats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_group_posts`
--
ALTER TABLE `jcow_group_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_group_topics`
--
ALTER TABLE `jcow_group_topics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_invites`
--
ALTER TABLE `jcow_invites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_liked`
--
ALTER TABLE `jcow_liked`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_menu`
--
ALTER TABLE `jcow_menu`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_messages`
--
ALTER TABLE `jcow_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_messages_sent`
--
ALTER TABLE `jcow_messages_sent`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_pages`
--
ALTER TABLE `jcow_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_profile_comments`
--
ALTER TABLE `jcow_profile_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_reports`
--
ALTER TABLE `jcow_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_stories`
--
ALTER TABLE `jcow_stories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_story_categories`
--
ALTER TABLE `jcow_story_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_story_cat_groups`
--
ALTER TABLE `jcow_story_cat_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_story_photos`
--
ALTER TABLE `jcow_story_photos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_streams`
--
ALTER TABLE `jcow_streams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jcow_tags`
--
ALTER TABLE `jcow_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

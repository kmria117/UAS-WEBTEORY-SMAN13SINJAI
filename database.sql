-- ============================================
-- DATABASE: db_sman13
-- Sistem Informasi SMAN 13 Sinjai
-- Import file ini di phpMyAdmin
-- ============================================

CREATE DATABASE IF NOT EXISTS db_sman13
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_sman13;

-- ── Tabel users ──────────────────────────────
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  nama       VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabel siswa ──────────────────────────────
DROP TABLE IF EXISTS siswa;
CREATE TABLE siswa (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  nis            VARCHAR(20)          NOT NULL UNIQUE,
  nama           VARCHAR(100)         NOT NULL,
  jenis_kelamin  ENUM('L','P')        NOT NULL,
  tanggal_lahir  DATE,
  kelas          ENUM('X','XI','XII') NOT NULL,
  jurusan        ENUM('IPA','IPS')    NOT NULL,
  foto           VARCHAR(255)         DEFAULT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Data awal: users (password = plain text, login.php support keduanya) ──
INSERT INTO users (username, password, nama) VALUES
('admin', 'admin123', 'Administrator'),
('guru',  'guru123',  'Guru');

-- ── Data awal: siswa ──────────────────────────
INSERT INTO siswa (nis, nama, jenis_kelamin, tanggal_lahir, kelas, jurusan) VALUES
('10234', 'Julfianti',     'P', '2008-03-15', 'XI',  'IPS'),
('10235', 'Siti Rahma',    'P', '2007-07-20', 'XII', 'IPS'),
('10236', 'Rizky Pratama', 'L', '2009-01-05', 'X',   'IPA');

-- seed_users.sql
-- Usage:
--   sqlite3 data/users.sqlite < seed_users.sql
-- This script creates the users table (if needed) and inserts demo users.
-- NOTE: password_hash column is seeded with plaintext demo passwords for convenience.
--       After import, run the provided PHP helper to hash them for secure storage.

PRAGMA foreign_keys = ON;
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  full_name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'User',
  created_at TEXT NOT NULL
);

-- Demo users (development). The password_hash column currently stores plaintext demo passwords.
-- Replace them with proper hashes before production.
INSERT OR IGNORE INTO users (full_name, email, password_hash, role, created_at) VALUES
('Jane Doe',        'jane@example.com',           'password',  'User',  '2025-01-03 10:00:00'),
('John Smith',      'john.smith@example.com',     'password',  'Admin', '2025-02-11 14:30:00'),
('Ana Torres',      'ana.torres@example.com',     'hello1234', 'User',  '2025-02-20 09:15:00'),
('Liam O''Connor',  'liam.oconnor@example.com',   'pwLiam2025', 'User', '2025-03-01 12:00:00'),
('Emma Müller',     'emma.mueller@example.com',   'emm@2025',  'User',  '2025-03-10 08:40:00'),
('Noah Silva',      'noah.silva@example.com',     'noahpwd9',  'User',  '2025-04-02 11:22:00'),
('Olivia Rossi',    'olivia.rossi@example.com',   'olivia777', 'User',  '2025-04-15 16:05:00'),
('Ethan Brown',     'ethan.brown@example.com',    'ethanPass', 'User',  '2025-05-01 07:50:00'),
('Ava Johnson',     'ava.johnson@example.com',    'avaSecure', 'User',  '2025-05-17 18:10:00'),
('Lucas Martin',    'lucas.martin@example.com',   'lucas12345','User',  '2025-06-03 13:33:00');

COMMIT;

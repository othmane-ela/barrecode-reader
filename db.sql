-- Create database
CREATE DATABASE client_db;

-- Switch to the new database
USE client_db;

-- Create table
CREATE TABLE client (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  tel VARCHAR(20) NOT NULL,
  code VARCHAR(50) NOT NULL UNIQUE
);
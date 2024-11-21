CREATE DATABASE IF NOT EXISTS GameManagementApp;

USE GameManagementApp;

CREATE TABLE IF NOT EXISTS Players (
    playerID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    joinDate DATE NOT NULL,
    age INT NULL,
    occupation VARCHAR(255) NULL,
    gender VARCHAR(50) NULL,
    city VARCHAR(255) NULL
);

CREATE TABLE IF NOT EXISTS Games (
    gameID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100),
    levelCount INT,
    description TEXT
);

CREATE TABLE IF NOT EXISTS PlayerGames (
    playerID INT,
    gameID INT,
    gamerTag VARCHAR(255) NOT NULL,
    hoursPlayed INT,
    lastPlayedDate DATE,
    joinDate DATE,
    currentLevel INT,
    PRIMARY KEY (playerID, gameID),
    FOREIGN KEY (playerID) REFERENCES Players(playerID) ON DELETE CASCADE,
    FOREIGN KEY (gameID) REFERENCES Games(gameID) ON DELETE CASCADE
);
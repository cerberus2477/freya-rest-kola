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



-- INSERT ----------------------------------------------------------------------

-- INSERT INTO Players (username, password, email, joinDate, age, occupation, gender, city)
-- VALUES 
-- ('Gamer_420', 'password123', 'gamer420@example.com', '2024-01-01', 28, 'Software Engineer', 'Male', 'New York'),
-- ('EpicNoodle', 'noodlesRlife', 'epicnoodle@example.com', '2024-02-15', 35, 'Chef', 'Female', 'San Francisco'),
-- ('Quest_Completed', 'completed123', 'questcompleted@example.com', '2024-03-20', 22, 'Student', 'Non-binary', 'Los Angeles'),
-- ('PixelatedPrincess', 'princess123', 'pixelatedprincess@example.com', '2023-12-11', 26, 'Graphic Designer', 'Female', 'Chicago'),
-- ('LagMaster5000', 'lagging4ever', 'lagmaster5000@example.com', '2023-10-05', 30, 'Game Developer', 'Male', 'Seattle'),
-- ('Captain_Crit', 'headshot123', 'captaincrit@example.com', '2024-04-10', 29, 'Marketing', 'Male', 'Miami'),
-- ('AFK_Wizard', 'backtospawn', 'afkwizard@example.com', '2024-05-22', 24, 'Musician', 'Female', 'Austin'),
-- ('DinoHunter69', 'rawr123', 'dinohunter69@example.com', '2024-06-18', 31, 'Veterinarian', 'Male', 'Dallas');

-- INSERT INTO Games (name, type, levelCount, description)
-- VALUES
-- ('Space Invaders 2.0', 'Arcade', 100, 'A modern twist on the classic arcade shooter, with more aliens and better graphics.'),
-- ('Dungeon Quest', 'RPG', 50, 'Embark on a fantasy adventure in a mysterious dungeon full of traps, treasures, and monsters.'),
-- ('Battle Royale: Legends', 'Shooter', 1, 'A fast-paced battle royale where only one player can survive to claim the victory.'),
-- ('Mystery Manor', 'Puzzle', 30, 'Solve intricate puzzles and unlock the secrets of a haunted mansion in this mysterious game.'),
-- ('Zombie Apocalypse', 'Survival', 50, 'Survive waves of zombies, scavenging for resources and building your shelter.'),
-- ('Space Race 3000', 'Racing', 15, 'Race across the galaxy in futuristic ships and avoid asteroids, black holes, and rival racers.');

-- INSERT INTO PlayerGames (playerID, gameID, gamerTag, hoursPlayed, lastPlayedDate, joinDate, currentLevel)
-- VALUES
-- (1, 1, 'Gamer_420_here', 50, '2024-07-15', '2024-01-01', 25),
-- (2, 2, 'imEpicNoodle', 120, '2024-07-01', '2024-02-15', 10),
-- (3, 3, 'Quest_UNCompleted', 60, '2024-06-30', '2024-03-20', 5),
-- (4, 4, 'PixelatedPrincess2', 80, '2024-07-10', '2023-12-11', 20),
-- (5, 5, 'LagMaster69', 200, '2024-07-12', '2023-10-05', 50),
-- (6, 6, 'imCaptain_Crit', 150, '2024-06-25', '2024-04-10', 15),
-- (7, 2, 'AFK_Wizard_9876', 45, '2024-07-07', '2024-05-22', 30),
-- (8, 5, 'DinoNuggies69', 300, '2024-07-13', '2024-06-18', 40);

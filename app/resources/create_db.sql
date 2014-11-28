CREATE USER 'hangman_site'@'localhost' IDENTIFIED BY 'h4ngm4n_s1t3!!';
CREATE DATABASE hangman;
GRANT DELETE, INSERT, SELECT, UPDATE ON hangman.* TO 'hangman_site'@'localhost';
CREATE TABLE games (
    id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Game Id',
    full_word VARCHAR(50) NOT NULL COMMENT 'Word to be guessed',
    word VARCHAR(50) NOT NULL COMMENT 'Word being guessed, with dots for unguessed chars',
    max_tries TINYINT UNSIGNED NOT NULL COMMENT 'Maximum tries that the user had when the game started',
    tries_left TINYINT UNSIGNED NOT NULL COMMENT 'Tries that the user has left on this game'
) ENGINE=InnoDb CHARSET=latin1 COMMENT 'Stores games data';
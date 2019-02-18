/* --------------- DATABASE --------------- */
DROP DATABASE SoundHub;
CREATE DATABASE SoundHub;


/* --------------- USERS --------------- */
CREATE TABLE SoundHub.Users (
  `UserID` int NOT NULL AUTO_INCREMENT UNIQUE,
  `UserName` varchar(50) NOT NULL UNIQUE,
  `UserHash` varchar(1000) NOT NULL,
  `UserPrivateKey` varchar(5000) NOT NULL,
  `UserPublicKey` varchar(5000) NOT NULL,
  `UserAvatar` varchar(1000),
  `UserBio` varchar(200),
  `UserEmail` varchar(100),
  `UserBirthday` DATE,
  `UserJoined` DATE,
  PRIMARY KEY (`UserId`)
);
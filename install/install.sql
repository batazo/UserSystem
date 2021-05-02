SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `Users` (
  `ID` int(8) NOT NULL,
  `UserName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `UserPassword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `UserRegTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'User registration date',
  `UserSecret` int(8) NOT NULL,
  `UserToken` int(8) NOT NULL,
  `UserAdminRole` int(1) DEFAULT NULL,
  `UserScore` int(8) NOT NULL DEFAULT 0,
  `UserSpeed` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `Users` (`ID`, `UserName`, `UserPassword`, `UserRegTime`, `UserSecret`, `UserToken`, `UserAdminRole`, `UserScore`, `UserSpeed`) VALUES
(100, 'Admin', 'YourHashedPassword', '1970-01-01 12:00:00', 00000000, 00000000, 1, 0, NULL);

ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `Users`
  MODIFY `ID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;
COMMIT;
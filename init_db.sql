DROP SCHEMA Library;
CREATE SCHEMA Library;
USE Library;

CREATE TABLE `Books` (
  `ISBN` varchar(45) NOT NULL,
  `Authors` varchar(100),
  `Title` varchar(200),
  `Publisher` varchar(45),
  `Year` year(4),
  `Pages` int,
  `Rating` decimal(2,1),
  `Total_ratings` int,
  `Status` varchar(45),
  PRIMARY KEY (`ISBN`),
  CONSTRAINT `Books_chk_1` CHECK (((`Status` = 'Wishlist') or (`Status` = 'Owned') or (`Status` = 'Read')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
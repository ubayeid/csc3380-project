-- Task 3: Using password_hash() and password_verify() with prepared statements
-- Database: acm72178

-- Create members3 table if not exists
CREATE TABLE IF NOT EXISTS `members3` (
  `MemberNo` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Deposit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`MemberNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create memberspw3 table if not exists
CREATE TABLE IF NOT EXISTS `memberspw3` (
  `MemberNo` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `PassWord` varchar(255) NOT NULL,
  `eMail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`MemberNo`),
  UNIQUE KEY `UserName` (`UserName`),
  CONSTRAINT `memberspw3_ibfk_1` FOREIGN KEY (`MemberNo`) REFERENCES `members3` (`MemberNo`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert test user Selva into members3
INSERT INTO members3 (MemberNo, FirstName, LastName, Deposit)
VALUES (10003, 'Selva', 'Kumar', 150)
ON DUPLICATE KEY UPDATE
    FirstName = 'Selva',
    LastName  = 'Kumar',
    Deposit   = 150;

-- Insert Selva into memberspw3
-- Password: thunder (hashed with password_hash())
INSERT INTO memberspw3 (UserName, PassWord, eMail, MemberNo)
VALUES (
    'Selva',
    '$2y$10$3466MfVY2QmlqS7HWcnrJuKRBXzwWxAt7Bmq/BQbjQdm47yoieTV.',
    'selva@example.com',
    10003
)
ON DUPLICATE KEY UPDATE
    PassWord = VALUES(PassWord),
    eMail    = VALUES(eMail),
    MemberNo = VALUES(MemberNo);


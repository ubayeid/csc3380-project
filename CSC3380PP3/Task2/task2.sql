-- Insert Selva into members2
INSERT INTO members2 (MemberNo, FirstName, LastName, Deposit)
VALUES (10003, 'Selva', 'Kumar', 150)
ON DUPLICATE KEY UPDATE
    FirstName = 'Selva',
    LastName  = 'Kumar',
    Deposit   = 150;

-- Insert Selva into memberspw2
INSERT INTO memberspw2 (UserName, PassWord, eMail, Salt, MemberNo)
VALUES (
    'Selva',
    '2d7127761d364b67f0268872291d53c0d957b258',
    'selva@example.com',
    'c5ba17f8b84c80b73096fb57b7849885',
    10003
)
ON DUPLICATE KEY UPDATE
    PassWord = VALUES(PassWord),
    eMail    = VALUES(eMail),
    Salt     = VALUES(Salt),
    MemberNo = VALUES(MemberNo);
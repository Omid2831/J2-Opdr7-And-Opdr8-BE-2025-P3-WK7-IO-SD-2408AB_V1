USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_get_instructor_by_id;
DELIMITER $$

CREATE PROCEDURE sp_get_instructor_by_id(IN p_instructor_id BIGINT)
BEGIN
    SELECT
        id,
        first_name,
        tussenvoegsel,
        last_name,
        mobile,
        datum_in_dienst,
        aantal_sterren
    FROM users
    WHERE id = p_instructor_id
        AND role = 'instructeur';
END $$

DELIMITER ;

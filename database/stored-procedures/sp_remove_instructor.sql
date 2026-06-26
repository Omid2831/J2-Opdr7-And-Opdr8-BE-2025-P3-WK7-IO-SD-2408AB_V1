USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_remove_instructor;
DELIMITER $$

CREATE PROCEDURE sp_remove_instructor(
    IN p_user_id BIGINT UNSIGNED,
    IN p_legacy_instructor_id INT UNSIGNED
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    UPDATE VoertuigInstructeur
    SET IsActief = 0,
        DatumGewijzigd = NOW()
    WHERE InstructeurId = p_legacy_instructor_id;

    UPDATE Instructeur
    SET IsActief = 0,
        DatumGewijzigd = NOW()
    WHERE Id = p_legacy_instructor_id;

    DELETE FROM vehicles WHERE instructor_id = p_user_id;

    DELETE FROM users WHERE id = p_user_id;

    COMMIT;
END $$

DELIMITER ;

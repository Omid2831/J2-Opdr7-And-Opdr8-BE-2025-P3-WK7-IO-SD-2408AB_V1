
USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_toggle_instructor_status;
DELIMITER $$

CREATE PROCEDURE sp_toggle_instructor_status(
    IN p_instructeur_id INT UNSIGNED,
    IN p_is_actief BIT
)
BEGIN
    DECLARE v_legacy_instructor_id INT UNSIGNED;
    
    -- Update instructor active status
    UPDATE Instructeur
    SET IsActief = p_is_actief,
        DatumGewijzigd = NOW()
    WHERE Id = p_instructeur_id;
    
    -- If deactivating, also deactivate all vehicle assignments
    IF p_is_actief = 0 THEN
        UPDATE VoertuigInstructeur
        SET IsActief = 0,
            DatumGewijzigd = NOW()
        WHERE InstructeurId = p_instructeur_id
          AND IsActief = 1;
    END IF;
END $$

DELIMITER ;

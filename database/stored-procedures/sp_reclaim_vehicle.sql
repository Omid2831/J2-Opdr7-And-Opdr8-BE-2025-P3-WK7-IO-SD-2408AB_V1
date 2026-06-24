USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_reclaim_vehicle;
DELIMITER $$

CREATE PROCEDURE sp_reclaim_vehicle(
    IN p_legacy_vehicle_id INT UNSIGNED,
    IN p_legacy_instructor_id INT UNSIGNED
)
BEGIN
    DECLARE v_kenteken VARCHAR(20);
    DECLARE v_user_id BIGINT UNSIGNED;

    SELECT Kenteken INTO v_kenteken FROM Voertuig WHERE Id = p_legacy_vehicle_id;

    SELECT u.id INTO v_user_id
    FROM users u
    INNER JOIN Instructeur i ON u.mobile = i.Mobiel
    WHERE i.Id = p_legacy_instructor_id;

    START TRANSACTION;

    UPDATE VoertuigInstructeur
    SET IsActief = 0,
        DatumGewijzigd = NOW()
    WHERE VoertuigId = p_legacy_vehicle_id
      AND IsActief = 1;

    UPDATE VoertuigInstructeur
    SET IsActief = 1,
        DatumGewijzigd = NOW()
    WHERE VoertuigId = p_legacy_vehicle_id
      AND InstructeurId = p_legacy_instructor_id;

    UPDATE vehicles
    SET instructor_id = v_user_id,
        updated_at = NOW()
    WHERE license_plate = v_kenteken
      AND instructor_id != v_user_id;

    COMMIT;
END $$

DELIMITER ;

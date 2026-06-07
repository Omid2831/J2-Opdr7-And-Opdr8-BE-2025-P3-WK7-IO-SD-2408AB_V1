
USE J2_Opdr7_BE;

DROP PROCEDURE IF EXISTS sp_remove_vehicle_assignment;
DELIMITER $$

CREATE PROCEDURE sp_remove_vehicle_assignment(IN p_vehicle_id BIGINT)
BEGIN
    UPDATE VoertuigInstructeur vi
    INNER JOIN Voertuig v ON v.Id = vi.VoertuigId
    INNER JOIN vehicles nv ON nv.license_plate = v.Kenteken
    SET vi.IsActief = 0,
        vi.DatumGewijzigd = NOW()
    WHERE nv.id = p_vehicle_id
      AND vi.IsActief = 1;

    DELETE FROM vehicles
    WHERE id = p_vehicle_id;
END $$

DELIMITER ;

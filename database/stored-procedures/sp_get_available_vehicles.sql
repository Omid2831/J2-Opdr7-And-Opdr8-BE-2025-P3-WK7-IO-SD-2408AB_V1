DROP PROCEDURE IF EXISTS sp_get_available_vehicles;

DELIMITER $$

CREATE PROCEDURE sp_get_available_vehicles(
    IN p_InstructeurId INT
)
BEGIN

    SELECT
        i.Id AS InstructeurId,
        i.Voornaam,
        i.Tussenvoegsel,
        i.Achternaam,
        i.DatumInDienst,
        i.AantalSterren,

        v.Id AS VoertuigId,
        tv.TypeVoertuig,
        v.Type,
        v.Kenteken,
        v.Bouwjaar,
        v.Brandstof,
        tv.Rijbewijscategorie

    FROM Instructeur i

    CROSS JOIN Voertuig v

    INNER JOIN TypeVoertuig tv
        ON v.TypeVoertuigId = tv.Id

    WHERE i.Id = p_InstructeurId
      AND v.IsActief = 1
      AND v.Id NOT IN
      (
          SELECT VoertuigId
          FROM VoertuigInstructeur
          WHERE IsActief = 1
      );

END$$

DELIMITER ;
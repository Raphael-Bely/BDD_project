-- Get restaurants within a specific radius from GPS coordinates, sorted by distance

SELECT restaurant_id, nom, adresse, 
       ST_Distance(coordonnees_gps::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) / 1000 AS distance_km
FROM restaurants
WHERE ST_DWithin(coordonnees_gps::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)
ORDER BY distance_km ASC

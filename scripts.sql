

CREATE FULLTEXT INDEX indiceNombrePokemon ON pokemon(nombre);
CREATE FULLTEXT INDEX indiceMovimientosNombre ON movimiento(nombre);
CREATE FULLTEXT INDEX indiceMovimientosDescripcion ON movimiento(descripcion);
CREATE FULLTEXT INDEX indiceNombreTipo ON tipo(nombre);


# .........................................................................

# hay un error en la base en la tabla 'evoluciona_de', en la cuál sale que Charizard evoluciona a Charmeleon
DELETE FROM evoluciona_de WHERE pokemon_origen = 6 AND pokemon_evolucionado = 5;

# ............................................................................

DELIMITER $$
DROP FUNCTION IF EXISTS preevolucionPokemon$$
CREATE FUNCTION preevolucionPokemon(numPokedex INT)
RETURNS INT
READS SQL DATA
BEGIN
	DECLARE numPokedexPreevolucion INT;

	SELECT ed.pokemon_origen
	INTO numPokedexPreevolucion
	FROM pokemon p LEFT JOIN evoluciona_de ed
		ON p.numero_pokedex = ed.pokemon_evolucionado
	WHERE p.numero_pokedex = numPokedex;

	RETURN numPokedexPreevolucion;
END $$

DROP FUNCTION IF EXISTS evolucionPokemon$$
CREATE FUNCTION evolucionPokemon(numPokedex INT)
RETURNS INT
READS SQL DATA
BEGIN
	DECLARE numPokedexEvolucion INT;

	SELECT ed.pokemon_evolucionado 
	INTO numPokedexEvolucion
	FROM pokemon p LEFT JOIN evoluciona_de ed
		ON p.numero_pokedex = ed.pokemon_origen
	WHERE p.numero_pokedex = numPokedex;

	RETURN numPokedexEvolucion;
END $$

DELIMITER ;

# .......................................................................................

DELIMITER $$

DROP PROCEDURE IF EXISTS insertarPokemon$$
CREATE PROCEDURE insertarPokemon(IN numPokemon INT, IN nombre VARCHAR(15),IN peso DOUBLE,
IN altura DOUBLE, IN ps INT, IN ataque INT, IN defensa INT, IN especial INT, IN velocidad INT)
BEGIN
	INSERT INTO pokemon (numero_pokedex, nombre, peso, altura)VALUES (numPokemon, nombre, peso, altura);
	INSERT INTO estadisticas_base (numero_pokedex, ps, ataque, defensa, especial, velocidad)VALUES (numPokemon, ps, ataque, defensa, especial, velocidad);
END $$

DELIMITER ;

# .......................................................................................

DELIMITER $$

DROP PROCEDURE IF EXISTS modificarPokemon$$
CREATE PROCEDURE modificarPokemon(IN numPokedex INT, IN varNombre VARCHAR(15),IN varPeso DOUBLE,
IN varAltura DOUBLE, IN varPs INT, IN varAtaque INT, IN varDefensa INT, IN varEspecial INT, IN varVelocidad INT)
BEGIN
	UPDATE pokemon 
	SET nombre = varNombre, peso = varPeso, altura = varAltura
	WHERE numero_pokedex = numPokedex;
	UPDATE estadisticas_base 
	SET ps = varPs, ataque = varAtaque, defensa = varDefensa, especial = varEspecial, velocidad = varVelocidad
	WHERE numero_pokedex = numPokedex;
END $$

DELIMITER ;


# ......................................................................................

DELIMITER $$
DROP PROCEDURE IF EXISTS eliminarPokemon$$
CREATE PROCEDURE eliminarPokemon(IN numPokedex INT)
BEGIN

	DECLARE EXIT HANDLER FOR 23000 ROLLBACK;

	START TRANSACTION;
	    DELETE FROM estadisticas_base WHERE numero_pokedex = numPokedex;
	
	    DELETE FROM pokemon WHERE numero_pokedex = numPokedex;
   	COMMIT;

END $$
DELIMITER ;


# ......................................................................................

DELIMITER $$
DROP PROCEDURE IF EXISTS depurar_datos_insercion_pokemon$$
CREATE PROCEDURE depurar_datos_insercion_pokemon( IN numPokedex int, IN nombre text, IN peso double,
IN altura double, OUT numPokedexSalida int, OUT nombreSalida text, OUT pesoSalida double, OUT alturaSalida double)
BEGIN
    SET numPokedexSalida = numPokedex ;
    SET nombreSalida = nombre ;
    SET pesoSalida = peso ;
    SET alturaSalida = altura ;
    IF numPokedexSalida IN (SELECT p.numero_pokedex FROM pokemon p)  OR numPokedexSalida < 0 THEN
        SELECT p.numero_pokedex INTO numPokedexSalida FROM pokemon p ORDER BY p.numero_pokedex DESC LIMIT 1;
       	SET numPokedexSalida = numPokedexSalida +1;
    END IF;

    SET nombreSalida = TRIM(nombreSalida);
    IF LENGTH(nombreSalida) > 15 THEN
        SET nombreSalida = LEFT(nombreSalida, 15);
    END IF;

    IF pesoSalida < 0 THEN
        SET pesoSalida = 0;
    END IF;

    IF alturaSalida < 0 THEN
        SET alturaSalida = 0;
    END IF;

END$$
DELIMITER ;

# ..........................................................................

DELIMITER $$
DROP PROCEDURE IF EXISTS depurar_datos_insercion_estadisticas$$
CREATE PROCEDURE depurar_datos_insercion_estadisticas( IN numPokedex int, IN ps INT, IN ataque INT, IN defensa INT, IN especial INT, IN velocidad INT,
OUT numPokedexSalida int, OUT psSalida INT, OUT ataqueSalida INT, OUT defensaSalida INT, OUT especialSalida INT, OUT velocidadSalida INT)
BEGIN
    SET numPokedexSalida = numPokedex ;
    SET psSalida = ps ;
    SET ataqueSalida = ataque ;
   	SET defensaSalida = defensa ;
 	SET especialSalida = especial ;
 	SET velocidadSalida = velocidad ;
 
    IF numPokedexSalida IN (SELECT eb.numero_pokedex FROM estadisticas_base eb)  OR numPokedexSalida < 0 THEN
        SELECT eb.numero_pokedex INTO numPokedexSalida FROM estadisticas_base eb ORDER BY eb.numero_pokedex DESC LIMIT 1;
       	SET numPokedexSalida = numPokedexSalida +1;
    END IF;

    IF psSalida < 0 THEN
        SET psSalida = 0;
    END IF;

    IF ataqueSalida < 0 THEN
        SET ataqueSalida = 0;
    END IF;
   
   IF defensaSalida < 0 THEN
        SET defensaSalida = 0;
    END IF;
   
   IF especialSalida < 0 THEN
        SET especialSalida = 0;
    END IF;
   
   IF velocidadSalida < 0 THEN
        SET velocidadSalida = 0;
    END IF;

END$$
DELIMITER ;


# .......................................................................................

DELIMITER $$
DROP TRIGGER IF EXISTS depurarDatosPokemonInserccion$$
CREATE TRIGGER depurarDatosPokemonInserccion BEFORE INSERT
ON pokemon FOR EACH ROW
BEGIN 
	DECLARE numPokedexNuevo INT;
	DECLARE nombreNuevo TEXT;
	DECLARE pesoNuevo DOUBLE;
	DECLARE alturaNuevo DOUBLE;
	
	CALL depurar_datos_insercion_pokemon(NEW.numero_pokedex, NEW.nombre, NEW.peso, NEW.altura,
	numPokedexNuevo, nombreNuevo, pesoNuevo, alturaNuevo); 
	SET NEW.numero_pokedex = numPokedexNuevo;
	SET NEW.nombre = nombreNuevo;
	SET NEW.peso = pesoNuevo;
	SET NEW.altura = alturaNuevo;

END $$

DELIMITER $$
DROP TRIGGER IF EXISTS depurarDatosEstadisticasInserccion$$
CREATE TRIGGER depurarDatosEstadisticasInserccion BEFORE INSERT
ON estadisticas_base FOR EACH ROW
BEGIN 
	DECLARE numPokedexNuevo INT;
	DECLARE psNuevo INT;
	DECLARE ataqueNuevo INT;
	DECLARE defensaNuevo INT;
	DECLARE especialNuevo INT;
	DECLARE velocidadNuevo INT;
	CALL depurar_datos_insercion_estadisticas(NEW.numero_pokedex, NEW.ps, NEW.ataque, NEW.defensa, NEW.especial, NEW.velocidad,
	numPokedexNuevo, psNuevo, ataqueNuevo, defensaNuevo, especialNuevo, velocidadNuevo); 
	SET NEW.numero_pokedex = numPokedexNuevo;
	SET NEW.ps = psNuevo;
	SET NEW.ataque = ataqueNuevo;
	SET NEW.defensa = defensaNuevo;
	SET NEW.especial = especialNuevo;
	SET NEW.velocidad = velocidadNuevo;
END $$



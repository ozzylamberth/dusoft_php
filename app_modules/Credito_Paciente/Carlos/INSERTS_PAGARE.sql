INSERT INTO puntos_credito_paciente VALUES (1, '01', '01', 'PAGAR?');
INSERT INTO userpermisos_credito_paciente VALUES (1, 2);

INSERT INTO pagare_cuenta VALUES (21, 2900, 'CC', '29900616', NULL, NULL, NULL, '2004-12-02', '2004-12-31', 150000.00, '02', NULL, '1', 2, '2004-12-02 00:00:00', 30000.00);
INSERT INTO pagare_cuenta VALUES (23, 2900, 'MS', '69', NULL, NULL, NULL, '2004-12-01', '2004-12-31', 200000.00, '02', NULL, '2', 2, '2004-12-12 00:00:00', 25000.00);
INSERT INTO pagare_cuenta VALUES (24, 423, 'CC', '29900616', NULL, NULL, NULL, '2004-12-03', '2005-02-25', NULL, '02', NULL, '2', 2, '2004-12-04 00:00:00', NULL);
INSERT INTO pagare_cuenta VALUES (26, 423, 'CC', '16363885', NULL, NULL, NULL, '2004-12-04', '2004-12-04', NULL, '02', NULL, '1', 2, '2004-12-04 00:00:00', NULL);

INSERT INTO system_modulos VALUES ('Credito_Paciente', 'app', 'Modulo para la administraci?n de los pagar?s.', 1.00, '', '1', '1', '1');
INSERT INTO system_menus VALUES (65, 'CREDITO PACIENTE', 'Modulo para la elaboraci?n de los pagar?s', '0');
INSERT INTO system_menus_items VALUES (97, 65, 'CR?DITO PARA PACIENTES', 'app', 'Credito_Paciente', 'user', 'main', 'M?dulo para el Cr?dito a los Pacientes', 0);
INSERT INTO system_usuarios_menus VALUES (65, 1);
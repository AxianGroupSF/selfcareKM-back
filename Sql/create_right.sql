-- Company
	INSERT INTO `right` (`code`, `label`)
	VALUES ('CREATE_COMPANY', 'Création société'),
        ('EDIT_COMPANY', 'Modification société'),
		('READ_COMPANY', 'Lecture société'),
		('STATUS_COMPANY', 'Activer/Desactiver société');

-- User
	INSERT INTO `right` (`code`, `label`)
	VALUES ('CREATE_USER', 'Création utilisateur'),
        ('EDIT_USER', 'Modification utilisateur'),
		('READ_USER', 'Lecture utilisateur'),
		('STATUS_USER', 'Activer/Desactiver utilisateur');

-- Msisdn Fleet
	INSERT INTO `right` (`code`, `label`)
	VALUES ('CREATE_MSISDNFLEET', 'Création ligne flotte (MSISDN)'),
        ('EDIT_MSISDNFLEET', 'Modification ligne flotte (MSISDN)'),
		('READ_MSISDNFLEET', 'Lecture ligne flotte (MSISDN)'),
		('REPORT_MSISDNFLEET', 'Génération rapports');

-- Billing
	INSERT INTO `right` (`code`, `label`)
	VALUES 
		('CREATE_BILLING', 'Création facture'),
		('READ_BILLING', 'Lecture facture'),
		('EDIT_BILLING', 'Modification facture'),
		('DOWN_BILLING', 'Télécharger facture');
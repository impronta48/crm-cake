##da eseguire su igas_bikesqsuare
UPDATE persone
SET created = '1900-01-01'
WHERE CAST(created AS CHAR(20)) = '0000-00-00 00:00:00';
UPDATE persone
SET modified = '1900-01-01'
WHERE CAST(modified AS CHAR(20)) = '0000-00-00 00:00:00';
ALTER TABLE persone
MODIFY created datetime NULL DEFAULT CURRENT_TIMESTAMP,
  MODIFY modified datetime NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `persone`
ADD `LuogoDiNascita` varchar(255) NULL
AFTER `DataDiNascita`,
  ADD `azienda_id` int DEFAULT NULL,
  ADD `mailchimp_hash` text,
  ADD `PecEmail` varchar(255) DEFAULT NULL,
  ADD `accetta_trattamento_dati` tinyint(1) NOT NULL DEFAULT '0',
  ADD `clausole_vessatorie` tinyint(1) NOT NULL DEFAULT '0',
  ADD `accetta_contratto_partner` tinyint(1) DEFAULT NULL,
  ADD `accetta_newsletter` tinyint(1) DEFAULT NULL,
  ADD `ip_optin` varchar(45) DEFAULT NULL,
  ADD `date_optin` datetime DEFAULT NULL;
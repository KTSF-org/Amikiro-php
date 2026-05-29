-- Migration : ajout des paramètres mail dans la table Config
-- À exécuter une seule fois sur la base KTSF
-- Compatible MariaDB 10.3+

ALTER TABLE `Config`
    ADD COLUMN `mailHost`     VARCHAR(255) NOT NULL DEFAULT 'smtp-relay.brevo.com' AFTER `viewerCount`,
    ADD COLUMN `mailPort`     SMALLINT     NOT NULL DEFAULT 587                    AFTER `mailHost`,
    ADD COLUMN `mailUser`     VARCHAR(255) NOT NULL DEFAULT ''                     AFTER `mailPort`,
    ADD COLUMN `mailPass`     VARCHAR(255) NOT NULL DEFAULT ''                     AFTER `mailUser`,
    ADD COLUMN `mailFrom`     VARCHAR(255) NOT NULL DEFAULT ''                     AFTER `mailPass`,
    ADD COLUMN `mailFromName` VARCHAR(255) NOT NULL DEFAULT 'Amikiro'              AFTER `mailFrom`;

-- Schema mis à jour pour authentification + données par utilisateur
CREATE TABLE `Utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(190) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Solde` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `solde` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_solde_user` (`user_id`),
  CONSTRAINT `fk_solde_user` FOREIGN KEY (`user_id`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Creance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nom_prenom` varchar(255) DEFAULT NULL,
  `montant_creance` decimal(10,2) DEFAULT NULL,
  `ancien_solde` decimal(10,2) DEFAULT '0.00',
  `nouveau_solde` decimal(10,2) DEFAULT NULL,
  `date_emprunt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_remboursement` varchar(20) DEFAULT NULL,
  `statut` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_creance_user` (`user_id`),
  CONSTRAINT `fk_creance_user` FOREIGN KEY (`user_id`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Transaction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nom_prenom` varchar(255) DEFAULT NULL,
  `montant_trans` decimal(10,2) DEFAULT NULL,
  `ancien_solde` decimal(10,2) DEFAULT NULL,
  `nouveau_solde` decimal(10,2) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `date_transaction` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_transaction_user` (`user_id`),
  CONSTRAINT `fk_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `Utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

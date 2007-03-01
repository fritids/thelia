-- phpMyAdmin SQL Dump
-- version 2.6.2-Debian-3sarge1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Vendredi 08 Décembre 2006 à 18:05
-- Version du serveur: 4.0.24
-- Version de PHP: 4.3.10-16
-- 
-- Base de données: `thelia`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `accessoire`
-- 

CREATE TABLE `accessoire` (
  `id` int(11) NOT NULL auto_increment,
  `produit` int(11) NOT NULL default '0',
  `accessoire` int(11) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `accessoire`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `administrateur`
-- 

CREATE TABLE `administrateur` (
  `id` int(11) NOT NULL auto_increment,
  `identifiant` text NOT NULL,
  `motdepasse` text NOT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `niveau` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `administrateur`
-- 

INSERT INTO `administrateur` VALUES (1, 'admin', PASSWORD('admin'), 'Admin', 'Admin', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `adresse`
-- 

CREATE TABLE `adresse` (
  `id` int(11) NOT NULL auto_increment,
  `libelle` varchar(120) NOT NULL default '',
  `client` int(11) NOT NULL default '0',
  `raison` smallint(6) NOT NULL default '0',
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse1` varchar(40) NOT NULL default '',
  `adresse2` varchar(40) NOT NULL default '',
  `adresse3` varchar(40) NOT NULL default '',
  `cpostal` varchar(10) NOT NULL default '',
  `ville` varchar(30) NOT NULL default '',
  `pays` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `adresse`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `boutique`
-- 

CREATE TABLE `boutique` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `url` text NOT NULL,
  `actif` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `boutique`
-- 

INSERT INTO `boutique` VALUES (1, 'boutique', '', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `caracdisp`
-- 

CREATE TABLE `caracdisp` (
  `id` int(11) NOT NULL auto_increment,
  `caracteristique` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `caracdisp`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `caracdispdesc`
-- 

CREATE TABLE `caracdispdesc` (
  `id` int(11) NOT NULL auto_increment,
  `caracdisp` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `caracdispdesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `caracteristique`
-- 

CREATE TABLE `caracteristique` (
  `id` int(11) NOT NULL auto_increment,
  `boutique` int(11) NOT NULL default '0',
  `affiche` int(11) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `caracteristique`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `caracteristiquedesc`
-- 

CREATE TABLE `caracteristiquedesc` (
  `id` int(11) NOT NULL auto_increment,
  `caracteristique` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `caracteristiquedesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `caracval`
-- 

CREATE TABLE `caracval` (
  `id` int(11) NOT NULL auto_increment,
  `produit` int(11) NOT NULL default '0',
  `caracteristique` int(11) NOT NULL default '0',
  `caracdisp` int(11) NOT NULL default '0',
  `valeur` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `caracval`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `client`
-- 

CREATE TABLE `client` (
  `id` int(11) NOT NULL auto_increment,
  `ref` text NOT NULL,
  `raison` smallint(6) NOT NULL default '0',
  `entreprise` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse1` varchar(40) NOT NULL default '',
  `adresse2` varchar(40) NOT NULL default '',
  `adresse3` varchar(40) NOT NULL default '',
  `cpostal` varchar(10) NOT NULL default '',
  `ville` varchar(30) NOT NULL default '',
  `pays` mediumint(9) NOT NULL default '0',
  `telfixe` text NOT NULL,
  `telport` text NOT NULL,
  `email` text NOT NULL,
  `motdepasse` text NOT NULL,
  `parrain` int(11) NOT NULL default '0',
  `type` smallint(6) NOT NULL default '0',
  `pourcentage` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `client`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `commande`
-- 

CREATE TABLE `commande` (
  `id` int(11) NOT NULL auto_increment,
  `client` int(11) NOT NULL default '0',
  `adresse` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `datefact` date NOT NULL default '0000-00-00',
  `ref` text NOT NULL,
  `transaction` text NOT NULL,
  `livraison` text NOT NULL,
  `facture` text NOT NULL,
  `transport` int(11) NOT NULL default '0',
  `port` float NOT NULL default '0',
  `affilie` int(11) NOT NULL default '0',
  `datelivraison` date NOT NULL default '0000-00-00',
  `remise` float NOT NULL default '0',
  `envoi` int(11) NOT NULL default '0',
  `paiement` int(11) NOT NULL default '0',
  `statut` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `commande`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contenu`
-- 

CREATE TABLE `contenu` (
  `id` int(11) NOT NULL auto_increment,
  `boutique` int(11) NOT NULL default '0',
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  `dossier` int(11) NOT NULL default '0',
  `ligne` smallint(6) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `contenu`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contenuassoc`
-- 

CREATE TABLE `contenuassoc` (
  `id` int(11) NOT NULL auto_increment,
  `objet` int(11) NOT NULL default '0',
  `type` int(11) NOT NULL default '0',
  `contenu` int(11) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `contenuassoc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `contenudesc`
-- 

CREATE TABLE `contenudesc` (
  `id` int(11) NOT NULL auto_increment,
  `contenu` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `lang` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `contenudesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `declidisp`
-- 

CREATE TABLE `declidisp` (
  `id` int(11) NOT NULL auto_increment,
  `declinaison` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `declidisp`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `declidispdesc`
-- 

CREATE TABLE `declidispdesc` (
  `id` int(11) NOT NULL auto_increment,
  `declidisp` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `declidispdesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `declinaison`
-- 

CREATE TABLE `declinaison` (
  `id` int(11) NOT NULL auto_increment,
  `boutique` int(11) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `declinaison`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `declinaisondesc`
-- 

CREATE TABLE `declinaisondesc` (
  `id` int(11) NOT NULL auto_increment,
  `declinaison` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `declinaisondesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `devise`
-- 

CREATE TABLE `devise` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `code` text NOT NULL,
  `symbole` text NOT NULL,
  `taux` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `devise`
-- 

INSERT INTO `devise` VALUES (1, 'dollar', '&#36;', '', 24);
INSERT INTO `devise` VALUES (2, 'livre', '&#163;', '', 1.5);

-- --------------------------------------------------------

-- 
-- Structure de la table `document`
-- 

CREATE TABLE `document` (
  `id` int(11) NOT NULL auto_increment,
  `produit` int(11) NOT NULL default '0',
  `rubrique` int(11) NOT NULL default '0',
  `contenu` int(11) NOT NULL default '0',
  `dossier` int(11) NOT NULL default '0',
  `fichier` text NOT NULL,
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `document`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `documentdesc`
-- 

CREATE TABLE `documentdesc` (
  `id` int(11) NOT NULL auto_increment,
  `document` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `documentdesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `dossier`
-- 

CREATE TABLE `dossier` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default '0',
  `lien` text NOT NULL,
  `boutique` int(11) NOT NULL default '0',
  `ligne` smallint(6) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `dossier`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `dossierdesc`
-- 

CREATE TABLE `dossierdesc` (
  `id` int(11) NOT NULL auto_increment,
  `dossier` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `dossierdesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `exdecprod`
-- 

CREATE TABLE `exdecprod` (
  `id` int(11) NOT NULL auto_increment,
  `produit` int(11) NOT NULL default '0',
  `declidisp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `exdecprod`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `image`
-- 

CREATE TABLE `image` (
  `id` int(11) NOT NULL auto_increment,
  `produit` int(11) NOT NULL default '0',
  `rubrique` int(11) NOT NULL default '0',
  `contenu` int(11) NOT NULL default '0',
  `dossier` int(11) NOT NULL default '0',
  `fichier` text NOT NULL,
  `classement` int(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `image`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `imagedesc`
-- 

CREATE TABLE `imagedesc` (
  `id` int(11) NOT NULL auto_increment,
  `image` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `imagedesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `lang`
-- 

CREATE TABLE `lang` (
  `id` int(11) NOT NULL auto_increment,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `lang`
-- 

INSERT INTO `lang` VALUES (1, 'Français');
INSERT INTO `lang` VALUES (2, 'English');
INSERT INTO `lang` VALUES (3, 'Espanol');

-- --------------------------------------------------------

-- 
-- Structure de la table `message`
-- 

CREATE TABLE `message` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `message`
-- 

INSERT INTO `message` VALUES (1, 'mdpmodif');
INSERT INTO `message` VALUES (2, 'mdpnonvalide');
INSERT INTO `message` VALUES (3, 'nouveaumdp1');
INSERT INTO `message` VALUES (4, 'nouveaumdp2');
INSERT INTO `message` VALUES (5, 'sujetcommande');
INSERT INTO `message` VALUES (6, 'corpscommande1');
INSERT INTO `message` VALUES (7, 'corpscommande2');

-- --------------------------------------------------------

-- 
-- Structure de la table `messagedesc`
-- 

CREATE TABLE `messagedesc` (
  `id` int(11) NOT NULL auto_increment,
  `message` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- 
-- Contenu de la table `messagedesc`
-- 

INSERT INTO `messagedesc` VALUES (1, 1, 1, 'Mot de passe modifié', '', 'Vous allez recevoir votre mot de passe par email.');
INSERT INTO `messagedesc` VALUES (2, 2, 1, 'Adresse E-Mail non valide', '', 'Cette adresse E-Mail n''existe pas');
INSERT INTO `messagedesc` VALUES (3, 3, 1, 'Nouveau mot de passe', '', 'Votre nouveau mot de passe');
INSERT INTO `messagedesc` VALUES (4, 4, 1, 'Nouveau mot de passe', '', 'Votre nouveau mot de passe est : ');
INSERT INTO `messagedesc` VALUES (5, 5, 1, 'Sujet Commande', '', 'Commande : ');
INSERT INTO `messagedesc` VALUES (6, 6, 1, 'Corps commande', '', 'Votre commande a été enregistrée.\r\nVous pouvez consulter votre facture dans la rubrique mon compte sur ...');
INSERT INTO `messagedesc` VALUES (7, 7, 1, 'Nouvelle commande', '', 'Nouvelle commande');
INSERT INTO `messagedesc` VALUES (8, 1, 2, 'Password modified', '', 'Vous allez recevoir votre mot de passe par email.');

-- --------------------------------------------------------

-- 
-- Structure de la table `moddoc`
-- 

CREATE TABLE `moddoc` (
  `id` int(11) NOT NULL auto_increment,
  `facture` text NOT NULL,
  `livraison` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `moddoc`
-- 

INSERT INTO `moddoc` VALUES (1, 'simplefact', 'simplelivr');
INSERT INTO `moddoc` VALUES (2, 'simplefact', 'simplelivr');

-- --------------------------------------------------------

-- 
-- Structure de la table `paiement`
-- 

CREATE TABLE `paiement` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(100) NOT NULL default '',
  `type` smallint(6) NOT NULL default '0',
  `devise` int(11) NOT NULL default '0',
  `logo` varchar(30) NOT NULL default '',
  `active` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `paiement`
-- 

INSERT INTO `paiement` VALUES (1, 'client/paiement/banquepop/call_request.php', 0, 0, 'logos_cartes.gif', 0);
INSERT INTO `paiement` VALUES (2, 'carte.php', 0, 0, 'logos_cartes.gif', 1);
INSERT INTO `paiement` VALUES (3, 'cheque.php', 1, 0, 'cheque.gif', 1);
INSERT INTO `paiement` VALUES (4, 'virement.php', 1, 0, '', 1);


-- --------------------------------------------------------

-- 
-- Structure de la table `paiementdesc`
-- 

CREATE TABLE `paiementdesc` (
  `id` int(11) NOT NULL auto_increment,
  `paiement` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `paiementdesc`
-- 

INSERT INTO `paiementdesc` VALUES (1, 1, 1, 'Je règle par Carte Bancaire', '', '');
INSERT INTO `paiementdesc` VALUES (2, 2, 1, 'Je règle par Carte Bancaire', '', '');
INSERT INTO `paiementdesc` VALUES (3, 3, 1, 'Je règle par chèque', '', '');
INSERT INTO `paiementdesc` VALUES (4, 4, 1, 'Je règle par virement', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `pays`
-- 

CREATE TABLE `pays` (
  `id` int(11) NOT NULL auto_increment,
  `lang` int(11) NOT NULL default '0',
  `zone` int(11) NOT NULL default '0',
  `default` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=268 ;

-- 
-- Contenu de la table `pays`
-- 

INSERT INTO `pays` VALUES (1, 0, -1, 0);
INSERT INTO `pays` VALUES (2, 0, -1, 0);
INSERT INTO `pays` VALUES (3, 0, -1, 0);
INSERT INTO `pays` VALUES (4, 0, -1, 0);
INSERT INTO `pays` VALUES (5, 0, -1, 0);
INSERT INTO `pays` VALUES (6, 0, -1, 0);
INSERT INTO `pays` VALUES (7, 0, -1, 0);
INSERT INTO `pays` VALUES (8, 0, -1, 0);
INSERT INTO `pays` VALUES (9, 0, -1, 0);
INSERT INTO `pays` VALUES (10, 0, -1, 0);
INSERT INTO `pays` VALUES (11, 0, -1, 0);
INSERT INTO `pays` VALUES (12, 0, -1, 0);
INSERT INTO `pays` VALUES (13, 0, -1, 0);
INSERT INTO `pays` VALUES (14, 0, -1, 0);
INSERT INTO `pays` VALUES (15, 0, -1, 0);
INSERT INTO `pays` VALUES (16, 0, -1, 0);
INSERT INTO `pays` VALUES (17, 0, -1, 0);
INSERT INTO `pays` VALUES (18, 0, -1, 0);
INSERT INTO `pays` VALUES (19, 0, -1, 0);
INSERT INTO `pays` VALUES (20, 0, -1, 0);
INSERT INTO `pays` VALUES (21, 0, -1, 0);
INSERT INTO `pays` VALUES (22, 0, -1, 0);
INSERT INTO `pays` VALUES (23, 0, -1, 0);
INSERT INTO `pays` VALUES (24, 0, -1, 0);
INSERT INTO `pays` VALUES (25, 0, -1, 0);
INSERT INTO `pays` VALUES (26, 0, -1, 0);
INSERT INTO `pays` VALUES (27, 0, -1, 0);
INSERT INTO `pays` VALUES (28, 0, -1, 0);
INSERT INTO `pays` VALUES (29, 0, -1, 0);
INSERT INTO `pays` VALUES (30, 0, -1, 0);
INSERT INTO `pays` VALUES (31, 0, -1, 0);
INSERT INTO `pays` VALUES (32, 0, -1, 0);
INSERT INTO `pays` VALUES (33, 0, -1, 0);
INSERT INTO `pays` VALUES (34, 0, -1, 0);
INSERT INTO `pays` VALUES (35, 0, -1, 0);
INSERT INTO `pays` VALUES (246, 1, -1, 0);
INSERT INTO `pays` VALUES (37, 0, -1, 0);
INSERT INTO `pays` VALUES (38, 0, -1, 0);
INSERT INTO `pays` VALUES (39, 0, -1, 0);
INSERT INTO `pays` VALUES (40, 0, -1, 0);
INSERT INTO `pays` VALUES (41, 0, -1, 0);
INSERT INTO `pays` VALUES (42, 0, -1, 0);
INSERT INTO `pays` VALUES (43, 0, -1, 0);
INSERT INTO `pays` VALUES (44, 0, -1, 0);
INSERT INTO `pays` VALUES (45, 0, -1, 0);
INSERT INTO `pays` VALUES (46, 0, -1, 0);
INSERT INTO `pays` VALUES (47, 0, -1, 0);
INSERT INTO `pays` VALUES (48, 0, -1, 0);
INSERT INTO `pays` VALUES (49, 0, -1, 0);
INSERT INTO `pays` VALUES (50, 0, -1, 0);
INSERT INTO `pays` VALUES (51, 0, -1, 0);
INSERT INTO `pays` VALUES (52, 0, -1, 0);
INSERT INTO `pays` VALUES (53, 0, -1, 0);
INSERT INTO `pays` VALUES (54, 0, -1, 0);
INSERT INTO `pays` VALUES (55, 0, -1, 0);
INSERT INTO `pays` VALUES (56, 0, -1, 0);
INSERT INTO `pays` VALUES (57, 0, -1, 0);
INSERT INTO `pays` VALUES (58, 0, -1, 0);
INSERT INTO `pays` VALUES (59, 0, -1, 0);
INSERT INTO `pays` VALUES (195, 1, -1, 0);
INSERT INTO `pays` VALUES (61, 0, -1, 0);
INSERT INTO `pays` VALUES (62, 0, -1, 0);
INSERT INTO `pays` VALUES (63, 0, -1, 0);
INSERT INTO `pays` VALUES (64, 0, 1, 0);
INSERT INTO `pays` VALUES (65, 0, -1, 0);
INSERT INTO `pays` VALUES (66, 0, -1, 0);
INSERT INTO `pays` VALUES (67, 0, -1, 0);
INSERT INTO `pays` VALUES (68, 0, -1, 0);
INSERT INTO `pays` VALUES (69, 0, -1, 0);
INSERT INTO `pays` VALUES (70, 0, -1, 0);
INSERT INTO `pays` VALUES (71, 0, -1, 0);
INSERT INTO `pays` VALUES (72, 0, -1, 0);
INSERT INTO `pays` VALUES (73, 0, -1, 0);
INSERT INTO `pays` VALUES (74, 0, -1, 0);
INSERT INTO `pays` VALUES (75, 0, -1, 0);
INSERT INTO `pays` VALUES (76, 0, -1, 0);
INSERT INTO `pays` VALUES (77, 0, -1, 0);
INSERT INTO `pays` VALUES (78, 0, -1, 0);
INSERT INTO `pays` VALUES (79, 0, -1, 0);
INSERT INTO `pays` VALUES (80, 0, -1, 0);
INSERT INTO `pays` VALUES (81, 0, -1, 0);
INSERT INTO `pays` VALUES (82, 0, -1, 0);
INSERT INTO `pays` VALUES (83, 0, -1, 0);
INSERT INTO `pays` VALUES (84, 0, -1, 0);
INSERT INTO `pays` VALUES (85, 0, -1, 0);
INSERT INTO `pays` VALUES (86, 0, -1, 0);
INSERT INTO `pays` VALUES (87, 0, -1, 0);
INSERT INTO `pays` VALUES (88, 0, -1, 0);
INSERT INTO `pays` VALUES (89, 0, -1, 0);
INSERT INTO `pays` VALUES (90, 0, -1, 0);
INSERT INTO `pays` VALUES (91, 0, -1, 0);
INSERT INTO `pays` VALUES (92, 0, -1, 0);
INSERT INTO `pays` VALUES (93, 0, -1, 0);
INSERT INTO `pays` VALUES (94, 0, -1, 0);
INSERT INTO `pays` VALUES (95, 0, -1, 0);
INSERT INTO `pays` VALUES (96, 0, -1, 0);
INSERT INTO `pays` VALUES (97, 0, -1, 0);
INSERT INTO `pays` VALUES (98, 0, -1, 0);
INSERT INTO `pays` VALUES (99, 0, -1, 0);
INSERT INTO `pays` VALUES (100, 0, -1, 0);
INSERT INTO `pays` VALUES (101, 0, -1, 0);
INSERT INTO `pays` VALUES (102, 0, -1, 0);
INSERT INTO `pays` VALUES (103, 0, -1, 0);
INSERT INTO `pays` VALUES (104, 0, -1, 0);
INSERT INTO `pays` VALUES (105, 0, -1, 0);
INSERT INTO `pays` VALUES (106, 0, -1, 0);
INSERT INTO `pays` VALUES (107, 0, -1, 0);
INSERT INTO `pays` VALUES (108, 0, -1, 0);
INSERT INTO `pays` VALUES (109, 0, -1, 0);
INSERT INTO `pays` VALUES (110, 0, -1, 0);
INSERT INTO `pays` VALUES (111, 0, -1, 0);
INSERT INTO `pays` VALUES (112, 0, -1, 0);
INSERT INTO `pays` VALUES (113, 0, -1, 0);
INSERT INTO `pays` VALUES (114, 0, -1, 0);
INSERT INTO `pays` VALUES (115, 0, -1, 0);
INSERT INTO `pays` VALUES (116, 0, -1, 0);
INSERT INTO `pays` VALUES (117, 0, -1, 0);
INSERT INTO `pays` VALUES (118, 0, -1, 0);
INSERT INTO `pays` VALUES (119, 0, -1, 0);
INSERT INTO `pays` VALUES (120, 0, -1, 0);
INSERT INTO `pays` VALUES (121, 0, -1, 0);
INSERT INTO `pays` VALUES (122, 0, -1, 0);
INSERT INTO `pays` VALUES (123, 0, -1, 0);
INSERT INTO `pays` VALUES (124, 0, -1, 0);
INSERT INTO `pays` VALUES (125, 0, -1, 0);
INSERT INTO `pays` VALUES (126, 0, -1, 0);
INSERT INTO `pays` VALUES (127, 0, -1, 0);
INSERT INTO `pays` VALUES (128, 0, -1, 0);
INSERT INTO `pays` VALUES (129, 0, -1, 0);
INSERT INTO `pays` VALUES (130, 0, -1, 0);
INSERT INTO `pays` VALUES (131, 0, -1, 0);
INSERT INTO `pays` VALUES (132, 0, -1, 0);
INSERT INTO `pays` VALUES (133, 0, -1, 0);
INSERT INTO `pays` VALUES (134, 0, -1, 0);
INSERT INTO `pays` VALUES (135, 0, -1, 0);
INSERT INTO `pays` VALUES (136, 0, -1, 0);
INSERT INTO `pays` VALUES (137, 0, -1, 0);
INSERT INTO `pays` VALUES (138, 0, -1, 0);
INSERT INTO `pays` VALUES (139, 0, -1, 0);
INSERT INTO `pays` VALUES (140, 0, -1, 0);
INSERT INTO `pays` VALUES (141, 0, -1, 0);
INSERT INTO `pays` VALUES (142, 0, -1, 0);
INSERT INTO `pays` VALUES (143, 0, -1, 0);
INSERT INTO `pays` VALUES (144, 0, -1, 0);
INSERT INTO `pays` VALUES (145, 0, -1, 0);
INSERT INTO `pays` VALUES (146, 0, -1, 0);
INSERT INTO `pays` VALUES (147, 0, -1, 0);
INSERT INTO `pays` VALUES (148, 0, -1, 0);
INSERT INTO `pays` VALUES (149, 0, -1, 0);
INSERT INTO `pays` VALUES (150, 0, -1, 0);
INSERT INTO `pays` VALUES (151, 0, -1, 0);
INSERT INTO `pays` VALUES (152, 0, -1, 0);
INSERT INTO `pays` VALUES (153, 0, -1, 0);
INSERT INTO `pays` VALUES (154, 0, -1, 0);
INSERT INTO `pays` VALUES (155, 0, -1, 0);
INSERT INTO `pays` VALUES (156, 0, -1, 0);
INSERT INTO `pays` VALUES (157, 0, -1, 0);
INSERT INTO `pays` VALUES (158, 0, -1, 0);
INSERT INTO `pays` VALUES (159, 0, -1, 0);
INSERT INTO `pays` VALUES (160, 0, -1, 0);
INSERT INTO `pays` VALUES (161, 0, -1, 0);
INSERT INTO `pays` VALUES (162, 0, -1, 0);
INSERT INTO `pays` VALUES (163, 0, -1, 0);
INSERT INTO `pays` VALUES (164, 0, -1, 0);
INSERT INTO `pays` VALUES (165, 0, -1, 0);
INSERT INTO `pays` VALUES (166, 0, -1, 0);
INSERT INTO `pays` VALUES (167, 0, -1, 0);
INSERT INTO `pays` VALUES (168, 0, -1, 0);
INSERT INTO `pays` VALUES (169, 0, -1, 0);
INSERT INTO `pays` VALUES (170, 0, -1, 0);
INSERT INTO `pays` VALUES (171, 0, -1, 0);
INSERT INTO `pays` VALUES (172, 0, -1, 0);
INSERT INTO `pays` VALUES (173, 0, -1, 0);
INSERT INTO `pays` VALUES (174, 0, -1, 0);
INSERT INTO `pays` VALUES (175, 0, -1, 0);
INSERT INTO `pays` VALUES (176, 0, -1, 0);
INSERT INTO `pays` VALUES (177, 0, -1, 0);
INSERT INTO `pays` VALUES (178, 0, -1, 0);
INSERT INTO `pays` VALUES (179, 0, -1, 0);
INSERT INTO `pays` VALUES (180, 0, -1, 0);
INSERT INTO `pays` VALUES (181, 0, -1, 0);
INSERT INTO `pays` VALUES (182, 0, -1, 0);
INSERT INTO `pays` VALUES (183, 0, -1, 0);
INSERT INTO `pays` VALUES (184, 0, -1, 0);
INSERT INTO `pays` VALUES (185, 0, -1, 0);
INSERT INTO `pays` VALUES (186, 0, -1, 0);
INSERT INTO `pays` VALUES (187, 0, -1, 0);
INSERT INTO `pays` VALUES (188, 0, -1, 0);
INSERT INTO `pays` VALUES (189, 0, -1, 0);
INSERT INTO `pays` VALUES (190, 0, -1, 0);
INSERT INTO `pays` VALUES (191, 0, -1, 0);
INSERT INTO `pays` VALUES (192, 0, -1, 0);
INSERT INTO `pays` VALUES (193, 0, -1, 0);
INSERT INTO `pays` VALUES (247, 1, -1, 0);
INSERT INTO `pays` VALUES (196, 1, -1, 0);
INSERT INTO `pays` VALUES (197, 1, -1, 0);
INSERT INTO `pays` VALUES (198, 1, -1, 0);
INSERT INTO `pays` VALUES (199, 1, -1, 0);
INSERT INTO `pays` VALUES (200, 1, -1, 0);
INSERT INTO `pays` VALUES (201, 1, -1, 0);
INSERT INTO `pays` VALUES (202, 1, -1, 0);
INSERT INTO `pays` VALUES (203, 1, -1, 0);
INSERT INTO `pays` VALUES (204, 1, -1, 0);
INSERT INTO `pays` VALUES (205, 1, -1, 0);
INSERT INTO `pays` VALUES (206, 1, -1, 0);
INSERT INTO `pays` VALUES (207, 1, -1, 0);
INSERT INTO `pays` VALUES (208, 1, -1, 0);
INSERT INTO `pays` VALUES (209, 1, -1, 0);
INSERT INTO `pays` VALUES (210, 1, -1, 0);
INSERT INTO `pays` VALUES (211, 1, -1, 0);
INSERT INTO `pays` VALUES (212, 1, -1, 0);
INSERT INTO `pays` VALUES (213, 1, -1, 0);
INSERT INTO `pays` VALUES (214, 1, -1, 0);
INSERT INTO `pays` VALUES (215, 1, -1, 0);
INSERT INTO `pays` VALUES (216, 1, -1, 0);
INSERT INTO `pays` VALUES (217, 1, -1, 0);
INSERT INTO `pays` VALUES (218, 1, -1, 0);
INSERT INTO `pays` VALUES (219, 1, -1, 0);
INSERT INTO `pays` VALUES (220, 1, -1, 0);
INSERT INTO `pays` VALUES (221, 1, -1, 0);
INSERT INTO `pays` VALUES (222, 1, -1, 0);
INSERT INTO `pays` VALUES (223, 1, -1, 0);
INSERT INTO `pays` VALUES (224, 1, -1, 0);
INSERT INTO `pays` VALUES (225, 1, -1, 0);
INSERT INTO `pays` VALUES (226, 1, -1, 0);
INSERT INTO `pays` VALUES (227, 1, -1, 0);
INSERT INTO `pays` VALUES (228, 1, -1, 0);
INSERT INTO `pays` VALUES (229, 1, -1, 0);
INSERT INTO `pays` VALUES (230, 1, -1, 0);
INSERT INTO `pays` VALUES (231, 1, -1, 0);
INSERT INTO `pays` VALUES (232, 1, -1, 0);
INSERT INTO `pays` VALUES (233, 1, -1, 0);
INSERT INTO `pays` VALUES (234, 1, -1, 0);
INSERT INTO `pays` VALUES (235, 1, -1, 0);
INSERT INTO `pays` VALUES (236, 1, -1, 0);
INSERT INTO `pays` VALUES (237, 1, -1, 0);
INSERT INTO `pays` VALUES (238, 1, -1, 0);
INSERT INTO `pays` VALUES (239, 1, -1, 0);
INSERT INTO `pays` VALUES (240, 1, -1, 0);
INSERT INTO `pays` VALUES (241, 1, -1, 0);
INSERT INTO `pays` VALUES (242, 1, -1, 0);
INSERT INTO `pays` VALUES (243, 1, -1, 0);
INSERT INTO `pays` VALUES (244, 1, -1, 0);
INSERT INTO `pays` VALUES (245, 1, -1, 0);
INSERT INTO `pays` VALUES (248, 1, -1, 0);
INSERT INTO `pays` VALUES (249, 1, -1, 0);
INSERT INTO `pays` VALUES (250, 1, -1, 0);
INSERT INTO `pays` VALUES (251, 1, -1, 0);
INSERT INTO `pays` VALUES (252, 1, -1, 0);
INSERT INTO `pays` VALUES (253, 1, -1, 0);
INSERT INTO `pays` VALUES (254, 1, -1, 0);
INSERT INTO `pays` VALUES (255, 1, -1, 0);
INSERT INTO `pays` VALUES (256, 1, -1, 0);
INSERT INTO `pays` VALUES (257, 1, -1, 0);
INSERT INTO `pays` VALUES (258, 1, -1, 0);
INSERT INTO `pays` VALUES (259, 0, -1, 0);
INSERT INTO `pays` VALUES (260, 0, -1, 0);
INSERT INTO `pays` VALUES (261, 0, -1, 0);
INSERT INTO `pays` VALUES (262, 0, -1, 0);
INSERT INTO `pays` VALUES (263, 0, -1, 0);
INSERT INTO `pays` VALUES (264, 0, -1, 0);
INSERT INTO `pays` VALUES (265, 0, -1, 0);
INSERT INTO `pays` VALUES (266, 0, -1, 0);
INSERT INTO `pays` VALUES (267, 0, -1, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `paysdesc`
-- 

CREATE TABLE `paysdesc` (
  `id` int(11) NOT NULL auto_increment,
  `pays` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=835 ;

-- 
-- Contenu de la table `paysdesc`
-- 

INSERT INTO `paysdesc` VALUES (1, 1, 1, 'Afghanistan', '', '');
INSERT INTO `paysdesc` VALUES (2, 2, 1, 'Afrique du Sud', '', '');
INSERT INTO `paysdesc` VALUES (3, 3, 1, 'Albanie', '', '');
INSERT INTO `paysdesc` VALUES (4, 4, 1, 'Algérie', '', '');
INSERT INTO `paysdesc` VALUES (5, 5, 1, 'Allemagne', '', '');
INSERT INTO `paysdesc` VALUES (6, 6, 1, 'Andorre', '', '');
INSERT INTO `paysdesc` VALUES (7, 7, 1, 'Angola', '', '');
INSERT INTO `paysdesc` VALUES (8, 8, 1, 'Antigua-et-Barbuda', '', '');
INSERT INTO `paysdesc` VALUES (9, 9, 1, 'Arabie saoudite', '', '');
INSERT INTO `paysdesc` VALUES (10, 10, 1, 'Argentine', '', '');
INSERT INTO `paysdesc` VALUES (11, 11, 1, 'Arménie', '', '');
INSERT INTO `paysdesc` VALUES (12, 12, 1, 'Australie', '', '');
INSERT INTO `paysdesc` VALUES (13, 13, 1, 'Autriche', '', '');
INSERT INTO `paysdesc` VALUES (14, 14, 1, 'Azerbaïdjan', '', '');
INSERT INTO `paysdesc` VALUES (15, 15, 1, 'Bahamas', '', '');
INSERT INTO `paysdesc` VALUES (16, 16, 1, 'Bahreïn', '', '');
INSERT INTO `paysdesc` VALUES (17, 17, 1, 'Bangladesh', '', '');
INSERT INTO `paysdesc` VALUES (18, 18, 1, 'Barbade', '', '');
INSERT INTO `paysdesc` VALUES (19, 19, 1, 'Belau', '', '');
INSERT INTO `paysdesc` VALUES (20, 20, 1, 'Belgique', '', '');
INSERT INTO `paysdesc` VALUES (21, 21, 1, 'Belize', '', '');
INSERT INTO `paysdesc` VALUES (22, 22, 1, 'Bénin', '', '');
INSERT INTO `paysdesc` VALUES (23, 23, 1, 'Bhoutan', '', '');
INSERT INTO `paysdesc` VALUES (24, 24, 1, 'Biélorussie', '', '');
INSERT INTO `paysdesc` VALUES (25, 25, 1, 'Birmanie', '', '');
INSERT INTO `paysdesc` VALUES (26, 26, 1, 'Bolivie', '', '');
INSERT INTO `paysdesc` VALUES (27, 27, 1, 'Bosnie-Herzégovine', '', '');
INSERT INTO `paysdesc` VALUES (28, 28, 1, 'Botswana', '', '');
INSERT INTO `paysdesc` VALUES (29, 29, 1, 'Brésil', '', '');
INSERT INTO `paysdesc` VALUES (30, 30, 1, 'Brunei', '', '');
INSERT INTO `paysdesc` VALUES (31, 31, 1, 'Bulgarie', '', '');
INSERT INTO `paysdesc` VALUES (32, 32, 1, 'Burkina', '', '');
INSERT INTO `paysdesc` VALUES (33, 33, 1, 'Burundi', '', '');
INSERT INTO `paysdesc` VALUES (34, 34, 1, 'Cambodge', '', '');
INSERT INTO `paysdesc` VALUES (35, 35, 1, 'Cameroun', '', '');
INSERT INTO `paysdesc` VALUES (37, 37, 1, 'Cap-Vert', '', '');
INSERT INTO `paysdesc` VALUES (38, 38, 1, 'Chili', '', '');
INSERT INTO `paysdesc` VALUES (39, 39, 1, 'Chine', '', '');
INSERT INTO `paysdesc` VALUES (40, 40, 1, 'Chypre', '', '');
INSERT INTO `paysdesc` VALUES (41, 41, 1, 'Colombie', '', '');
INSERT INTO `paysdesc` VALUES (42, 42, 1, 'Comores', '', '');
INSERT INTO `paysdesc` VALUES (43, 43, 1, 'Congo', '', '');
INSERT INTO `paysdesc` VALUES (44, 44, 1, 'Cook', '', '');
INSERT INTO `paysdesc` VALUES (45, 45, 1, 'Corée du Nord', '', '');
INSERT INTO `paysdesc` VALUES (46, 46, 1, 'Corée du Sud', '', '');
INSERT INTO `paysdesc` VALUES (47, 47, 1, 'Costa Rica', '', '');
INSERT INTO `paysdesc` VALUES (48, 48, 1, 'Côte d''Ivoire', '', '');
INSERT INTO `paysdesc` VALUES (49, 49, 1, 'Croatie', '', '');
INSERT INTO `paysdesc` VALUES (50, 50, 1, 'Cuba', '', '');
INSERT INTO `paysdesc` VALUES (51, 51, 1, 'Danemark', '', '');
INSERT INTO `paysdesc` VALUES (52, 52, 1, 'Djibouti', '', '');
INSERT INTO `paysdesc` VALUES (53, 53, 1, 'Dominique', '', '');
INSERT INTO `paysdesc` VALUES (54, 54, 1, 'Égypte', '', '');
INSERT INTO `paysdesc` VALUES (55, 55, 1, 'Émirats arabes unis', '', '');
INSERT INTO `paysdesc` VALUES (56, 56, 1, 'Équateur', '', '');
INSERT INTO `paysdesc` VALUES (57, 57, 1, 'Érythrée', '', '');
INSERT INTO `paysdesc` VALUES (58, 58, 1, 'Espagne', '', '');
INSERT INTO `paysdesc` VALUES (59, 59, 1, 'Estonie', '', '');
INSERT INTO `paysdesc` VALUES (582, 198, 1, 'USA - Arkansas', '', '');
INSERT INTO `paysdesc` VALUES (61, 61, 1, 'Éthiopie', '', '');
INSERT INTO `paysdesc` VALUES (62, 62, 1, 'Fidji', '', '');
INSERT INTO `paysdesc` VALUES (63, 63, 1, 'Finlande', '', '');
INSERT INTO `paysdesc` VALUES (64, 64, 1, 'France', '', '');
INSERT INTO `paysdesc` VALUES (65, 65, 1, 'Gabon', '', '');
INSERT INTO `paysdesc` VALUES (66, 66, 1, 'Gambie', '', '');
INSERT INTO `paysdesc` VALUES (67, 67, 1, 'Géorgie', '', '');
INSERT INTO `paysdesc` VALUES (68, 68, 1, 'Ghana', '', '');
INSERT INTO `paysdesc` VALUES (69, 69, 1, 'Grèce', '', '');
INSERT INTO `paysdesc` VALUES (70, 70, 1, 'Grenade', '', '');
INSERT INTO `paysdesc` VALUES (71, 71, 1, 'Guatemala', '', '');
INSERT INTO `paysdesc` VALUES (72, 72, 1, 'Guinée', '', '');
INSERT INTO `paysdesc` VALUES (73, 73, 1, 'Guinée-Bissao', '', '');
INSERT INTO `paysdesc` VALUES (74, 74, 1, 'Guinée équatoriale', '', '');
INSERT INTO `paysdesc` VALUES (75, 75, 1, 'Guyana', '', '');
INSERT INTO `paysdesc` VALUES (76, 76, 1, 'Haïti', '', '');
INSERT INTO `paysdesc` VALUES (77, 77, 1, 'Honduras', '', '');
INSERT INTO `paysdesc` VALUES (78, 78, 1, 'Hongrie', '', '');
INSERT INTO `paysdesc` VALUES (79, 79, 1, 'Inde', '', '');
INSERT INTO `paysdesc` VALUES (80, 80, 1, 'Indonésie', '', '');
INSERT INTO `paysdesc` VALUES (81, 81, 1, 'Iran', '', '');
INSERT INTO `paysdesc` VALUES (82, 82, 1, 'Iraq', '', '');
INSERT INTO `paysdesc` VALUES (83, 83, 1, 'Irlande', '', '');
INSERT INTO `paysdesc` VALUES (84, 84, 1, 'Islande', '', '');
INSERT INTO `paysdesc` VALUES (85, 85, 1, 'Israël', '', '');
INSERT INTO `paysdesc` VALUES (86, 86, 1, 'Italie', '', '');
INSERT INTO `paysdesc` VALUES (87, 87, 1, 'Jamaïque', '', '');
INSERT INTO `paysdesc` VALUES (88, 88, 1, 'Japon', '', '');
INSERT INTO `paysdesc` VALUES (89, 89, 1, 'Jordanie', '', '');
INSERT INTO `paysdesc` VALUES (90, 90, 1, 'Kazakhstan', '', '');
INSERT INTO `paysdesc` VALUES (91, 91, 1, 'Kenya', '', '');
INSERT INTO `paysdesc` VALUES (92, 92, 1, 'Kirghizistan', '', '');
INSERT INTO `paysdesc` VALUES (93, 93, 1, 'Kiribati', '', '');
INSERT INTO `paysdesc` VALUES (94, 94, 1, 'Koweït', '', '');
INSERT INTO `paysdesc` VALUES (95, 95, 1, 'Laos', '', '');
INSERT INTO `paysdesc` VALUES (96, 96, 1, 'Lesotho', '', '');
INSERT INTO `paysdesc` VALUES (97, 97, 1, 'Lettonie', '', '');
INSERT INTO `paysdesc` VALUES (98, 98, 1, 'Liban', '', '');
INSERT INTO `paysdesc` VALUES (99, 99, 1, 'Liberia', '', '');
INSERT INTO `paysdesc` VALUES (100, 100, 1, 'Libye', '', '');
INSERT INTO `paysdesc` VALUES (101, 101, 1, 'Liechtenstein', '', '');
INSERT INTO `paysdesc` VALUES (102, 102, 1, 'Lituanie', '', '');
INSERT INTO `paysdesc` VALUES (103, 103, 1, 'Luxembourg', '', '');
INSERT INTO `paysdesc` VALUES (104, 104, 1, 'Macédoine', '', '');
INSERT INTO `paysdesc` VALUES (105, 105, 1, 'Madagascar', '', '');
INSERT INTO `paysdesc` VALUES (106, 106, 1, 'Malaisie', '', '');
INSERT INTO `paysdesc` VALUES (107, 107, 1, 'Malawi', '', '');
INSERT INTO `paysdesc` VALUES (108, 108, 1, 'Maldives', '', '');
INSERT INTO `paysdesc` VALUES (109, 109, 1, 'Mali', '', '');
INSERT INTO `paysdesc` VALUES (110, 110, 1, 'Malte', '', '');
INSERT INTO `paysdesc` VALUES (111, 111, 1, 'Maroc', '', '');
INSERT INTO `paysdesc` VALUES (112, 112, 1, 'Marshall', '', '');
INSERT INTO `paysdesc` VALUES (113, 113, 1, 'Maurice', '', '');
INSERT INTO `paysdesc` VALUES (114, 114, 1, 'Mauritanie', '', '');
INSERT INTO `paysdesc` VALUES (115, 115, 1, 'Mexique', '', '');
INSERT INTO `paysdesc` VALUES (116, 116, 1, 'Micronésie', '', '');
INSERT INTO `paysdesc` VALUES (117, 117, 1, 'Moldavie', '', '');
INSERT INTO `paysdesc` VALUES (118, 118, 1, 'Monaco', '', '');
INSERT INTO `paysdesc` VALUES (119, 119, 1, 'Mongolie', '', '');
INSERT INTO `paysdesc` VALUES (120, 120, 1, 'Mozambique', '', '');
INSERT INTO `paysdesc` VALUES (121, 121, 1, 'Namibie', '', '');
INSERT INTO `paysdesc` VALUES (122, 122, 1, 'Nauru', '', '');
INSERT INTO `paysdesc` VALUES (123, 123, 1, 'Népal', '', '');
INSERT INTO `paysdesc` VALUES (124, 124, 1, 'Nicaragua', '', '');
INSERT INTO `paysdesc` VALUES (125, 125, 1, 'Niger', '', '');
INSERT INTO `paysdesc` VALUES (126, 126, 1, 'Nigeria', '', '');
INSERT INTO `paysdesc` VALUES (127, 127, 1, 'Niue', '', '');
INSERT INTO `paysdesc` VALUES (128, 128, 1, 'Norvège', '', '');
INSERT INTO `paysdesc` VALUES (129, 129, 1, 'Nouvelle-Zélande', '', '');
INSERT INTO `paysdesc` VALUES (130, 130, 1, 'Oman', '', '');
INSERT INTO `paysdesc` VALUES (131, 131, 1, 'Ouganda', '', '');
INSERT INTO `paysdesc` VALUES (132, 132, 1, 'Ouzbékistan', '', '');
INSERT INTO `paysdesc` VALUES (133, 133, 1, 'Pakistan', '', '');
INSERT INTO `paysdesc` VALUES (134, 134, 1, 'Panama', '', '');
INSERT INTO `paysdesc` VALUES (135, 135, 1, 'Papouasie', '', '');
INSERT INTO `paysdesc` VALUES (136, 136, 1, 'Paraguay', '', '');
INSERT INTO `paysdesc` VALUES (137, 137, 1, 'Pays-Bas', '', '');
INSERT INTO `paysdesc` VALUES (138, 138, 1, 'Pérou', '', '');
INSERT INTO `paysdesc` VALUES (139, 139, 1, 'Philippines', '', '');
INSERT INTO `paysdesc` VALUES (140, 140, 1, 'Pologne', '', '');
INSERT INTO `paysdesc` VALUES (141, 141, 1, 'Portugal', '', '');
INSERT INTO `paysdesc` VALUES (142, 142, 1, 'Qatar', '', '');
INSERT INTO `paysdesc` VALUES (143, 143, 1, 'République centrafricaine', '', '');
INSERT INTO `paysdesc` VALUES (144, 144, 1, 'République dominicaine', '', '');
INSERT INTO `paysdesc` VALUES (145, 145, 1, 'République tchèque', '', '');
INSERT INTO `paysdesc` VALUES (146, 146, 1, 'Roumanie', '', '');
INSERT INTO `paysdesc` VALUES (147, 147, 1, 'Royaume-Uni', '', '');
INSERT INTO `paysdesc` VALUES (148, 148, 1, 'Russie', '', '');
INSERT INTO `paysdesc` VALUES (149, 149, 1, 'Rwanda', '', '');
INSERT INTO `paysdesc` VALUES (150, 150, 1, 'Saint-Christophe-et-Niévès', '', '');
INSERT INTO `paysdesc` VALUES (151, 151, 1, 'Sainte-Lucie', '', '');
INSERT INTO `paysdesc` VALUES (152, 152, 1, 'Saint-Marin', '', '');
INSERT INTO `paysdesc` VALUES (153, 153, 1, 'Saint-Vincent-et-les Grenadines', '', '');
INSERT INTO `paysdesc` VALUES (154, 154, 1, 'Salomon', '', '');
INSERT INTO `paysdesc` VALUES (155, 155, 1, 'Salvador', '', '');
INSERT INTO `paysdesc` VALUES (156, 156, 1, 'Samoa occidentales', '', '');
INSERT INTO `paysdesc` VALUES (157, 157, 1, 'Sao Tomé-et-Principe', '', '');
INSERT INTO `paysdesc` VALUES (158, 158, 1, 'Sénégal', '', '');
INSERT INTO `paysdesc` VALUES (159, 159, 1, 'Seychelles', '', '');
INSERT INTO `paysdesc` VALUES (160, 160, 1, 'Sierra Leone', '', '');
INSERT INTO `paysdesc` VALUES (161, 161, 1, 'Singapour', '', '');
INSERT INTO `paysdesc` VALUES (162, 162, 1, 'Slovaquie', '', '');
INSERT INTO `paysdesc` VALUES (163, 163, 1, 'Slovénie', '', '');
INSERT INTO `paysdesc` VALUES (164, 164, 1, 'Somalie', '', '');
INSERT INTO `paysdesc` VALUES (165, 165, 1, 'Soudan', '', '');
INSERT INTO `paysdesc` VALUES (166, 166, 1, 'Sri Lanka', '', '');
INSERT INTO `paysdesc` VALUES (167, 167, 1, 'Suède', '', '');
INSERT INTO `paysdesc` VALUES (168, 168, 1, 'Suisse', '', '');
INSERT INTO `paysdesc` VALUES (169, 169, 1, 'Suriname', '', '');
INSERT INTO `paysdesc` VALUES (170, 170, 1, 'Swaziland', '', '');
INSERT INTO `paysdesc` VALUES (171, 171, 1, 'Syrie', '', '');
INSERT INTO `paysdesc` VALUES (172, 172, 1, 'Tadjikistan', '', '');
INSERT INTO `paysdesc` VALUES (173, 173, 1, 'Tanzanie', '', '');
INSERT INTO `paysdesc` VALUES (174, 174, 1, 'Tchad', '', '');
INSERT INTO `paysdesc` VALUES (175, 175, 1, 'Thaïlande', '', '');
INSERT INTO `paysdesc` VALUES (176, 176, 1, 'Togo', '', '');
INSERT INTO `paysdesc` VALUES (177, 177, 1, 'Tonga', '', '');
INSERT INTO `paysdesc` VALUES (178, 178, 1, 'Trinité-et-Tobago', '', '');
INSERT INTO `paysdesc` VALUES (179, 179, 1, 'Tunisie', '', '');
INSERT INTO `paysdesc` VALUES (180, 180, 1, 'Turkménistan', '', '');
INSERT INTO `paysdesc` VALUES (181, 181, 1, 'Turquie', '', '');
INSERT INTO `paysdesc` VALUES (182, 182, 1, 'Tuvalu', '', '');
INSERT INTO `paysdesc` VALUES (183, 183, 1, 'Ukraine', '', '');
INSERT INTO `paysdesc` VALUES (184, 184, 1, 'Uruguay', '', '');
INSERT INTO `paysdesc` VALUES (185, 185, 1, 'Vatican', '', '');
INSERT INTO `paysdesc` VALUES (186, 186, 1, 'Vanuatu', '', '');
INSERT INTO `paysdesc` VALUES (187, 187, 1, 'Venezuela', '', '');
INSERT INTO `paysdesc` VALUES (188, 188, 1, 'Viêt Nam', '', '');
INSERT INTO `paysdesc` VALUES (189, 189, 1, 'Yémen', '', '');
INSERT INTO `paysdesc` VALUES (190, 190, 1, 'Yougoslavie', '', '');
INSERT INTO `paysdesc` VALUES (191, 191, 1, 'Zaïre', '', '');
INSERT INTO `paysdesc` VALUES (192, 192, 1, 'Zambie', '', '');
INSERT INTO `paysdesc` VALUES (193, 193, 1, 'Zimbabwe', '', '');
INSERT INTO `paysdesc` VALUES (194, 1, 2, 'Afghanistan', '', '');
INSERT INTO `paysdesc` VALUES (195, 2, 2, 'South Africa', '', '');
INSERT INTO `paysdesc` VALUES (196, 3, 2, 'Albania', '', '');
INSERT INTO `paysdesc` VALUES (197, 4, 2, 'Algeria', '', '');
INSERT INTO `paysdesc` VALUES (198, 5, 2, 'Germany', '', '');
INSERT INTO `paysdesc` VALUES (199, 6, 2, 'Andorra', '', '');
INSERT INTO `paysdesc` VALUES (200, 7, 2, 'Angola', '', '');
INSERT INTO `paysdesc` VALUES (201, 8, 2, 'Antigua and Barbuda', '', '');
INSERT INTO `paysdesc` VALUES (202, 9, 2, 'Saudi Arabia', '', '');
INSERT INTO `paysdesc` VALUES (203, 10, 2, 'Argentina', '', '');
INSERT INTO `paysdesc` VALUES (204, 11, 2, 'Armenia', '', '');
INSERT INTO `paysdesc` VALUES (205, 12, 2, 'Australia', '', '');
INSERT INTO `paysdesc` VALUES (206, 13, 2, 'Austria', '', '');
INSERT INTO `paysdesc` VALUES (207, 14, 2, 'Azerbaijan', '', '');
INSERT INTO `paysdesc` VALUES (208, 15, 2, 'Bahamas', '', '');
INSERT INTO `paysdesc` VALUES (209, 16, 2, 'Bahrain', '', '');
INSERT INTO `paysdesc` VALUES (210, 17, 2, 'Bangladesh', '', '');
INSERT INTO `paysdesc` VALUES (211, 18, 2, 'Barbados', '', '');
INSERT INTO `paysdesc` VALUES (212, 19, 2, 'Belarus', '', '');
INSERT INTO `paysdesc` VALUES (213, 20, 2, 'Belgium', '', '');
INSERT INTO `paysdesc` VALUES (214, 21, 2, 'Belize', '', '');
INSERT INTO `paysdesc` VALUES (215, 22, 2, 'Benin', '', '');
INSERT INTO `paysdesc` VALUES (216, 23, 2, 'Bhutan', '', '');
INSERT INTO `paysdesc` VALUES (217, 24, 2, 'Bielorussia', '', '');
INSERT INTO `paysdesc` VALUES (218, 25, 2, 'Burma', '', '');
INSERT INTO `paysdesc` VALUES (219, 26, 2, 'Bolivia', '', '');
INSERT INTO `paysdesc` VALUES (220, 27, 2, 'Bosnia and Herzegovina', '', '');
INSERT INTO `paysdesc` VALUES (221, 28, 2, 'Botswana', '', '');
INSERT INTO `paysdesc` VALUES (222, 29, 2, 'Brazil', '', '');
INSERT INTO `paysdesc` VALUES (223, 30, 2, 'Brunei', '', '');
INSERT INTO `paysdesc` VALUES (224, 31, 2, 'Bulgaria', '', '');
INSERT INTO `paysdesc` VALUES (225, 32, 2, 'Burkina', '', '');
INSERT INTO `paysdesc` VALUES (226, 33, 2, 'Burundi', '', '');
INSERT INTO `paysdesc` VALUES (227, 34, 2, 'Cambodia', '', '');
INSERT INTO `paysdesc` VALUES (228, 35, 2, 'Cameroon', '', '');
INSERT INTO `paysdesc` VALUES (230, 37, 2, 'Cape Verde', '', '');
INSERT INTO `paysdesc` VALUES (231, 38, 2, 'Chile', '', '');
INSERT INTO `paysdesc` VALUES (232, 39, 2, 'China', '', '');
INSERT INTO `paysdesc` VALUES (233, 40, 2, 'Cyprus', '', '');
INSERT INTO `paysdesc` VALUES (234, 41, 2, 'Colombia', '', '');
INSERT INTO `paysdesc` VALUES (235, 42, 2, 'Comoros', '', '');
INSERT INTO `paysdesc` VALUES (236, 43, 2, 'Congo', '', '');
INSERT INTO `paysdesc` VALUES (237, 44, 2, 'Cook Islands', '', '');
INSERT INTO `paysdesc` VALUES (238, 45, 2, 'North Korea', '', '');
INSERT INTO `paysdesc` VALUES (239, 46, 2, 'South Korea', '', '');
INSERT INTO `paysdesc` VALUES (240, 47, 2, 'Costa Rica', '', '');
INSERT INTO `paysdesc` VALUES (241, 48, 2, 'Ivory Coast', '', '');
INSERT INTO `paysdesc` VALUES (242, 49, 2, 'Croatia', '', '');
INSERT INTO `paysdesc` VALUES (243, 50, 2, 'Cuba', '', '');
INSERT INTO `paysdesc` VALUES (244, 51, 2, 'Denmark', '', '');
INSERT INTO `paysdesc` VALUES (245, 52, 2, 'Djibouti', '', '');
INSERT INTO `paysdesc` VALUES (246, 53, 2, 'Dominica', '', '');
INSERT INTO `paysdesc` VALUES (247, 54, 2, 'Egypt', '', '');
INSERT INTO `paysdesc` VALUES (248, 55, 2, 'United Arab Emirates', '', '');
INSERT INTO `paysdesc` VALUES (249, 56, 2, 'Ecuador', '', '');
INSERT INTO `paysdesc` VALUES (250, 57, 2, 'Eritrea', '', '');
INSERT INTO `paysdesc` VALUES (251, 58, 2, 'Spain', '', '');
INSERT INTO `paysdesc` VALUES (252, 59, 2, 'Estonia', '', '');
INSERT INTO `paysdesc` VALUES (581, 197, 1, 'USA - Arizona', '', '');
INSERT INTO `paysdesc` VALUES (254, 61, 2, 'Ethiopia', '', '');
INSERT INTO `paysdesc` VALUES (255, 62, 2, 'Fiji', '', '');
INSERT INTO `paysdesc` VALUES (256, 63, 2, 'Finland', '', '');
INSERT INTO `paysdesc` VALUES (257, 64, 2, 'France', '', '');
INSERT INTO `paysdesc` VALUES (258, 65, 2, 'Gabon', '', '');
INSERT INTO `paysdesc` VALUES (259, 66, 2, 'Gambia', '', '');
INSERT INTO `paysdesc` VALUES (260, 67, 2, 'Georgia', '', '');
INSERT INTO `paysdesc` VALUES (261, 68, 2, 'Ghana', '', '');
INSERT INTO `paysdesc` VALUES (262, 69, 2, 'Greece', '', '');
INSERT INTO `paysdesc` VALUES (263, 70, 2, 'Grenada', '', '');
INSERT INTO `paysdesc` VALUES (264, 71, 2, 'Guatemala', '', '');
INSERT INTO `paysdesc` VALUES (265, 72, 2, 'Guinea', '', '');
INSERT INTO `paysdesc` VALUES (266, 73, 2, 'Guinea-Bissau', '', '');
INSERT INTO `paysdesc` VALUES (267, 74, 2, 'Equatorial Guinea', '', '');
INSERT INTO `paysdesc` VALUES (268, 75, 2, 'Guyana', '', '');
INSERT INTO `paysdesc` VALUES (269, 76, 2, 'Haiti', '', '');
INSERT INTO `paysdesc` VALUES (270, 77, 2, 'Honduras', '', '');
INSERT INTO `paysdesc` VALUES (271, 78, 2, 'Hungary', '', '');
INSERT INTO `paysdesc` VALUES (272, 79, 2, 'India', '', '');
INSERT INTO `paysdesc` VALUES (273, 80, 2, 'Indonesia', '', '');
INSERT INTO `paysdesc` VALUES (274, 81, 2, 'Iran', '', '');
INSERT INTO `paysdesc` VALUES (275, 82, 2, 'Iraq', '', '');
INSERT INTO `paysdesc` VALUES (276, 83, 2, 'Ireland', '', '');
INSERT INTO `paysdesc` VALUES (277, 84, 2, 'Iceland', '', '');
INSERT INTO `paysdesc` VALUES (278, 85, 2, 'Israel', '', '');
INSERT INTO `paysdesc` VALUES (279, 86, 2, 'Italy', '', '');
INSERT INTO `paysdesc` VALUES (280, 87, 2, 'Jamaica', '', '');
INSERT INTO `paysdesc` VALUES (281, 88, 2, 'Japan', '', '');
INSERT INTO `paysdesc` VALUES (282, 89, 2, 'Jordan', '', '');
INSERT INTO `paysdesc` VALUES (283, 90, 2, 'Kazakhstan', '', '');
INSERT INTO `paysdesc` VALUES (284, 91, 2, 'Kenya', '', '');
INSERT INTO `paysdesc` VALUES (285, 92, 2, 'Kyrgyzstan', '', '');
INSERT INTO `paysdesc` VALUES (286, 93, 2, 'Kiribati', '', '');
INSERT INTO `paysdesc` VALUES (287, 94, 2, 'Kuwait', '', '');
INSERT INTO `paysdesc` VALUES (288, 95, 2, 'Laos', '', '');
INSERT INTO `paysdesc` VALUES (289, 96, 2, 'Lesotho', '', '');
INSERT INTO `paysdesc` VALUES (290, 97, 2, 'Latvia', '', '');
INSERT INTO `paysdesc` VALUES (291, 98, 2, 'Lebanon', '', '');
INSERT INTO `paysdesc` VALUES (292, 99, 2, 'Liberia', '', '');
INSERT INTO `paysdesc` VALUES (293, 100, 2, 'Libya', '', '');
INSERT INTO `paysdesc` VALUES (294, 101, 2, 'Liechtenstein', '', '');
INSERT INTO `paysdesc` VALUES (295, 102, 2, 'Lithuania', '', '');
INSERT INTO `paysdesc` VALUES (296, 103, 2, 'Luxembourg', '', '');
INSERT INTO `paysdesc` VALUES (297, 104, 2, 'Macedonia', '', '');
INSERT INTO `paysdesc` VALUES (298, 105, 2, 'Madagascar', '', '');
INSERT INTO `paysdesc` VALUES (299, 106, 2, 'Malaysia', '', '');
INSERT INTO `paysdesc` VALUES (300, 107, 2, 'Malawi', '', '');
INSERT INTO `paysdesc` VALUES (301, 108, 2, 'Maldives', '', '');
INSERT INTO `paysdesc` VALUES (302, 109, 2, 'Mali', '', '');
INSERT INTO `paysdesc` VALUES (303, 110, 2, 'Malta', '', '');
INSERT INTO `paysdesc` VALUES (304, 111, 2, 'Morocco', '', '');
INSERT INTO `paysdesc` VALUES (305, 112, 2, 'Marshall Islands', '', '');
INSERT INTO `paysdesc` VALUES (306, 113, 2, 'Mauritius', '', '');
INSERT INTO `paysdesc` VALUES (307, 114, 2, 'Mauritania', '', '');
INSERT INTO `paysdesc` VALUES (308, 115, 2, 'Mexico', '', '');
INSERT INTO `paysdesc` VALUES (309, 116, 2, 'Micronesia', '', '');
INSERT INTO `paysdesc` VALUES (310, 117, 2, 'Moldova', '', '');
INSERT INTO `paysdesc` VALUES (311, 118, 2, 'Monaco', '', '');
INSERT INTO `paysdesc` VALUES (312, 119, 2, 'Mongolia', '', '');
INSERT INTO `paysdesc` VALUES (313, 120, 2, 'Mozambique', '', '');
INSERT INTO `paysdesc` VALUES (314, 121, 2, 'Namibia', '', '');
INSERT INTO `paysdesc` VALUES (315, 122, 2, 'Nauru', '', '');
INSERT INTO `paysdesc` VALUES (316, 123, 2, 'Nepal', '', '');
INSERT INTO `paysdesc` VALUES (317, 124, 2, 'Nicaragua', '', '');
INSERT INTO `paysdesc` VALUES (318, 125, 2, 'Niger', '', '');
INSERT INTO `paysdesc` VALUES (319, 126, 2, 'Nigeria', '', '');
INSERT INTO `paysdesc` VALUES (320, 127, 2, 'Niue', '', '');
INSERT INTO `paysdesc` VALUES (321, 128, 2, 'Norway', '', '');
INSERT INTO `paysdesc` VALUES (322, 129, 2, 'New Zealand', '', '');
INSERT INTO `paysdesc` VALUES (323, 130, 2, 'Oman', '', '');
INSERT INTO `paysdesc` VALUES (324, 131, 2, 'Uganda', '', '');
INSERT INTO `paysdesc` VALUES (325, 132, 2, 'Uzbekistan', '', '');
INSERT INTO `paysdesc` VALUES (326, 133, 2, 'Pakistan', '', '');
INSERT INTO `paysdesc` VALUES (327, 134, 2, 'Panama', '', '');
INSERT INTO `paysdesc` VALUES (328, 135, 2, 'Papua Nueva Guinea', '', '');
INSERT INTO `paysdesc` VALUES (329, 136, 2, 'Paraguay', '', '');
INSERT INTO `paysdesc` VALUES (330, 137, 2, 'Netherlands', '', '');
INSERT INTO `paysdesc` VALUES (331, 138, 2, 'Peru', '', '');
INSERT INTO `paysdesc` VALUES (332, 139, 2, 'Philippines', '', '');
INSERT INTO `paysdesc` VALUES (333, 140, 2, 'Poland', '', '');
INSERT INTO `paysdesc` VALUES (334, 141, 2, 'Portugal', '', '');
INSERT INTO `paysdesc` VALUES (335, 142, 2, 'Qatar', '', '');
INSERT INTO `paysdesc` VALUES (336, 143, 2, 'Central African Republic', '', '');
INSERT INTO `paysdesc` VALUES (337, 144, 2, 'Dominican Republic', '', '');
INSERT INTO `paysdesc` VALUES (338, 145, 2, 'Czech Republic', '', '');
INSERT INTO `paysdesc` VALUES (339, 146, 2, 'Romania', '', '');
INSERT INTO `paysdesc` VALUES (340, 147, 2, 'United Kingdom', '', '');
INSERT INTO `paysdesc` VALUES (341, 148, 2, 'Russia', '', '');
INSERT INTO `paysdesc` VALUES (342, 149, 2, 'Rwanda', '', '');
INSERT INTO `paysdesc` VALUES (343, 150, 2, 'Saint Kitts and Nevis', '', '');
INSERT INTO `paysdesc` VALUES (344, 151, 2, 'Saint Lucia', '', '');
INSERT INTO `paysdesc` VALUES (345, 152, 2, 'San Marino', '', '');
INSERT INTO `paysdesc` VALUES (346, 153, 2, 'Saint Vincent and the Grenadines', '', '');
INSERT INTO `paysdesc` VALUES (347, 154, 2, 'Solomon Islands', '', '');
INSERT INTO `paysdesc` VALUES (348, 155, 2, 'El Salvador', '', '');
INSERT INTO `paysdesc` VALUES (349, 156, 2, 'Western Samoa', '', '');
INSERT INTO `paysdesc` VALUES (350, 157, 2, 'Sao Tome and Principe', '', '');
INSERT INTO `paysdesc` VALUES (351, 158, 2, 'Senegal', '', '');
INSERT INTO `paysdesc` VALUES (352, 159, 2, 'Seychelles', '', '');
INSERT INTO `paysdesc` VALUES (353, 160, 2, 'Sierra Leone', '', '');
INSERT INTO `paysdesc` VALUES (354, 161, 2, 'Singapore', '', '');
INSERT INTO `paysdesc` VALUES (355, 162, 2, 'Slovakia', '', '');
INSERT INTO `paysdesc` VALUES (356, 163, 2, 'Slovenia', '', '');
INSERT INTO `paysdesc` VALUES (357, 164, 2, 'Somalia', '', '');
INSERT INTO `paysdesc` VALUES (358, 165, 2, 'Sudan', '', '');
INSERT INTO `paysdesc` VALUES (359, 166, 2, 'Sri Lanka', '', '');
INSERT INTO `paysdesc` VALUES (360, 167, 2, 'Sweden', '', '');
INSERT INTO `paysdesc` VALUES (361, 168, 2, 'Switzerland', '', '');
INSERT INTO `paysdesc` VALUES (362, 169, 2, 'Suriname', '', '');
INSERT INTO `paysdesc` VALUES (363, 170, 2, 'Swaziland', '', '');
INSERT INTO `paysdesc` VALUES (364, 171, 2, 'Syria', '', '');
INSERT INTO `paysdesc` VALUES (365, 172, 2, 'Tajikistan', '', '');
INSERT INTO `paysdesc` VALUES (366, 173, 2, 'Tanzania', '', '');
INSERT INTO `paysdesc` VALUES (367, 174, 2, 'Chad', '', '');
INSERT INTO `paysdesc` VALUES (368, 175, 2, 'Thailand', '', '');
INSERT INTO `paysdesc` VALUES (369, 176, 2, 'Togo', '', '');
INSERT INTO `paysdesc` VALUES (370, 177, 2, 'Tonga', '', '');
INSERT INTO `paysdesc` VALUES (371, 178, 2, 'Trinidad and Tobago', '', '');
INSERT INTO `paysdesc` VALUES (372, 179, 2, 'Tunisia', '', '');
INSERT INTO `paysdesc` VALUES (373, 180, 2, 'Turkmenistan', '', '');
INSERT INTO `paysdesc` VALUES (374, 181, 2, 'Turkey', '', '');
INSERT INTO `paysdesc` VALUES (375, 182, 2, 'Tuvalu', '', '');
INSERT INTO `paysdesc` VALUES (376, 183, 2, 'Ukraine', '', '');
INSERT INTO `paysdesc` VALUES (377, 184, 2, 'Uruguay', '', '');
INSERT INTO `paysdesc` VALUES (378, 185, 2, 'The Vatican', '', '');
INSERT INTO `paysdesc` VALUES (379, 186, 2, 'Vanuatu', '', '');
INSERT INTO `paysdesc` VALUES (380, 187, 2, 'Venezuela', '', '');
INSERT INTO `paysdesc` VALUES (381, 188, 2, 'Vietnam', '', '');
INSERT INTO `paysdesc` VALUES (382, 189, 2, 'Yemen', '', '');
INSERT INTO `paysdesc` VALUES (383, 190, 2, 'Yougoslavia', '', '');
INSERT INTO `paysdesc` VALUES (384, 191, 2, 'Zaire', '', '');
INSERT INTO `paysdesc` VALUES (385, 192, 2, 'Zambia', '', '');
INSERT INTO `paysdesc` VALUES (386, 193, 2, 'Zimbabwe', '', '');
INSERT INTO `paysdesc` VALUES (387, 1, 3, 'Afganistán', '', '');
INSERT INTO `paysdesc` VALUES (388, 2, 3, 'Sudáfrica', '', '');
INSERT INTO `paysdesc` VALUES (389, 3, 3, 'Albania', '', '');
INSERT INTO `paysdesc` VALUES (390, 4, 3, 'Argelia', '', '');
INSERT INTO `paysdesc` VALUES (391, 5, 3, 'Alemania', '', '');
INSERT INTO `paysdesc` VALUES (392, 6, 3, 'Andorra', '', '');
INSERT INTO `paysdesc` VALUES (393, 7, 3, 'Angola', '', '');
INSERT INTO `paysdesc` VALUES (394, 8, 3, 'Antigua y Barbuda', '', '');
INSERT INTO `paysdesc` VALUES (395, 9, 3, 'Arabia Saudita', '', '');
INSERT INTO `paysdesc` VALUES (396, 10, 3, 'Argentina', '', '');
INSERT INTO `paysdesc` VALUES (397, 11, 3, 'Armenia', '', '');
INSERT INTO `paysdesc` VALUES (398, 12, 3, 'Australia', '', '');
INSERT INTO `paysdesc` VALUES (399, 13, 3, 'Austria', '', '');
INSERT INTO `paysdesc` VALUES (400, 14, 3, 'Azerbaiyán', '', '');
INSERT INTO `paysdesc` VALUES (401, 15, 3, 'Bahamas', '', '');
INSERT INTO `paysdesc` VALUES (402, 16, 3, 'Bahrein', '', '');
INSERT INTO `paysdesc` VALUES (403, 17, 3, 'Bangladesh', '', '');
INSERT INTO `paysdesc` VALUES (404, 18, 3, 'Barbados', '', '');
INSERT INTO `paysdesc` VALUES (405, 19, 3, 'Belarús', '', '');
INSERT INTO `paysdesc` VALUES (406, 20, 3, 'Bélgica', '', '');
INSERT INTO `paysdesc` VALUES (407, 21, 3, 'Belice', '', '');
INSERT INTO `paysdesc` VALUES (408, 22, 3, 'Benin', '', '');
INSERT INTO `paysdesc` VALUES (409, 23, 3, 'Bhután', '', '');
INSERT INTO `paysdesc` VALUES (410, 24, 3, 'Bielorusia', '', '');
INSERT INTO `paysdesc` VALUES (411, 25, 3, 'Birmania', '', '');
INSERT INTO `paysdesc` VALUES (412, 26, 3, 'Bolivia', '', '');
INSERT INTO `paysdesc` VALUES (413, 27, 3, 'Bosnia y Herzegovina', '', '');
INSERT INTO `paysdesc` VALUES (414, 28, 3, 'Botswana', '', '');
INSERT INTO `paysdesc` VALUES (415, 29, 3, 'Brasil', '', '');
INSERT INTO `paysdesc` VALUES (416, 30, 3, 'Brunei', '', '');
INSERT INTO `paysdesc` VALUES (417, 31, 3, 'Bulgaria', '', '');
INSERT INTO `paysdesc` VALUES (418, 32, 3, 'Burkina', '', '');
INSERT INTO `paysdesc` VALUES (419, 33, 3, 'Burundi', '', '');
INSERT INTO `paysdesc` VALUES (420, 34, 3, 'Camboya', '', '');
INSERT INTO `paysdesc` VALUES (421, 35, 3, 'Camerún', '', '');
INSERT INTO `paysdesc` VALUES (730, 246, 1, 'Colombie-Britannique', '', '');
INSERT INTO `paysdesc` VALUES (423, 37, 3, 'Cabo Verde', '', '');
INSERT INTO `paysdesc` VALUES (424, 38, 3, 'Chile', '', '');
INSERT INTO `paysdesc` VALUES (425, 39, 3, 'China', '', '');
INSERT INTO `paysdesc` VALUES (426, 40, 3, 'Chipre', '', '');
INSERT INTO `paysdesc` VALUES (427, 41, 3, 'Colombia', '', '');
INSERT INTO `paysdesc` VALUES (428, 42, 3, 'Comoras', '', '');
INSERT INTO `paysdesc` VALUES (429, 43, 3, 'Congo', '', '');
INSERT INTO `paysdesc` VALUES (430, 44, 3, 'Cook', '', '');
INSERT INTO `paysdesc` VALUES (431, 45, 3, 'Corea del Norte', '', '');
INSERT INTO `paysdesc` VALUES (432, 46, 3, 'Corea del Sur', '', '');
INSERT INTO `paysdesc` VALUES (433, 47, 3, 'Costa Rica', '', '');
INSERT INTO `paysdesc` VALUES (434, 48, 3, 'Costa de Marfil', '', '');
INSERT INTO `paysdesc` VALUES (435, 49, 3, 'Croacia', '', '');
INSERT INTO `paysdesc` VALUES (436, 50, 3, 'Cuba', '', '');
INSERT INTO `paysdesc` VALUES (437, 51, 3, 'Dinamarca', '', '');
INSERT INTO `paysdesc` VALUES (438, 52, 3, 'Djibouti', '', '');
INSERT INTO `paysdesc` VALUES (439, 53, 3, 'Dominica', '', '');
INSERT INTO `paysdesc` VALUES (440, 54, 3, 'Egipto', '', '');
INSERT INTO `paysdesc` VALUES (441, 55, 3, 'Emiratos Árabes Unidos', '', '');
INSERT INTO `paysdesc` VALUES (442, 56, 3, 'Ecuador', '', '');
INSERT INTO `paysdesc` VALUES (443, 57, 3, 'Eritrea', '', '');
INSERT INTO `paysdesc` VALUES (444, 58, 3, 'España', '', '');
INSERT INTO `paysdesc` VALUES (445, 59, 3, 'Estonia', '', '');
INSERT INTO `paysdesc` VALUES (580, 196, 1, 'USA - Alaska', '', '');
INSERT INTO `paysdesc` VALUES (447, 61, 3, 'Etiopía', '', '');
INSERT INTO `paysdesc` VALUES (448, 62, 3, 'Fiji', '', '');
INSERT INTO `paysdesc` VALUES (449, 63, 3, 'Finlandia', '', '');
INSERT INTO `paysdesc` VALUES (450, 64, 3, 'Francia', '', '');
INSERT INTO `paysdesc` VALUES (451, 65, 3, 'Gabón', '', '');
INSERT INTO `paysdesc` VALUES (452, 66, 3, 'Gambia', '', '');
INSERT INTO `paysdesc` VALUES (453, 67, 3, 'Georgia', '', '');
INSERT INTO `paysdesc` VALUES (454, 68, 3, 'Ghana', '', '');
INSERT INTO `paysdesc` VALUES (455, 69, 3, 'Grecia', '', '');
INSERT INTO `paysdesc` VALUES (456, 70, 3, 'Granada', '', '');
INSERT INTO `paysdesc` VALUES (457, 71, 3, 'Guatemala', '', '');
INSERT INTO `paysdesc` VALUES (458, 72, 3, 'Guinea', '', '');
INSERT INTO `paysdesc` VALUES (459, 73, 3, 'Guinea-Bissau', '', '');
INSERT INTO `paysdesc` VALUES (460, 74, 3, 'Guinea Ecuatorial', '', '');
INSERT INTO `paysdesc` VALUES (461, 75, 3, 'Guyana', '', '');
INSERT INTO `paysdesc` VALUES (462, 76, 3, 'Haití', '', '');
INSERT INTO `paysdesc` VALUES (463, 77, 3, 'Honduras', '', '');
INSERT INTO `paysdesc` VALUES (464, 78, 3, 'Hungría', '', '');
INSERT INTO `paysdesc` VALUES (465, 79, 3, 'India', '', '');
INSERT INTO `paysdesc` VALUES (466, 80, 3, 'Indonesia', '', '');
INSERT INTO `paysdesc` VALUES (467, 81, 3, 'Irán', '', '');
INSERT INTO `paysdesc` VALUES (468, 82, 3, 'Iraq', '', '');
INSERT INTO `paysdesc` VALUES (469, 83, 3, 'Irlanda', '', '');
INSERT INTO `paysdesc` VALUES (470, 84, 3, 'Islandia', '', '');
INSERT INTO `paysdesc` VALUES (471, 85, 3, 'Israel', '', '');
INSERT INTO `paysdesc` VALUES (472, 86, 3, 'Italia', '', '');
INSERT INTO `paysdesc` VALUES (473, 87, 3, 'Jamaica', '', '');
INSERT INTO `paysdesc` VALUES (474, 88, 3, 'Japón', '', '');
INSERT INTO `paysdesc` VALUES (475, 89, 3, 'Jordania', '', '');
INSERT INTO `paysdesc` VALUES (476, 90, 3, 'Kazajstán', '', '');
INSERT INTO `paysdesc` VALUES (477, 91, 3, 'Kenia', '', '');
INSERT INTO `paysdesc` VALUES (478, 92, 3, 'Kirguistán', '', '');
INSERT INTO `paysdesc` VALUES (479, 93, 3, 'Kiribati', '', '');
INSERT INTO `paysdesc` VALUES (480, 94, 3, 'Kuwait', '', '');
INSERT INTO `paysdesc` VALUES (481, 95, 3, 'Laos', '', '');
INSERT INTO `paysdesc` VALUES (482, 96, 3, 'Lesotho', '', '');
INSERT INTO `paysdesc` VALUES (483, 97, 3, 'Letonia', '', '');
INSERT INTO `paysdesc` VALUES (484, 98, 3, 'Líbano', '', '');
INSERT INTO `paysdesc` VALUES (485, 99, 3, 'Liberia', '', '');
INSERT INTO `paysdesc` VALUES (486, 100, 3, 'Libia', '', '');
INSERT INTO `paysdesc` VALUES (487, 101, 3, 'Liechtenstein', '', '');
INSERT INTO `paysdesc` VALUES (488, 102, 3, 'Lituania', '', '');
INSERT INTO `paysdesc` VALUES (489, 103, 3, 'Luxemburgo', '', '');
INSERT INTO `paysdesc` VALUES (490, 104, 3, 'Macedonia', '', '');
INSERT INTO `paysdesc` VALUES (491, 105, 3, 'Madagascar', '', '');
INSERT INTO `paysdesc` VALUES (492, 106, 3, 'Malasia', '', '');
INSERT INTO `paysdesc` VALUES (493, 107, 3, 'Malawi', '', '');
INSERT INTO `paysdesc` VALUES (494, 108, 3, 'Maldivas', '', '');
INSERT INTO `paysdesc` VALUES (495, 109, 3, 'Malí', '', '');
INSERT INTO `paysdesc` VALUES (496, 110, 3, 'Malta', '', '');
INSERT INTO `paysdesc` VALUES (497, 111, 3, 'Marruecos', '', '');
INSERT INTO `paysdesc` VALUES (498, 112, 3, 'Marshall', '', '');
INSERT INTO `paysdesc` VALUES (499, 113, 3, 'Mauricio', '', '');
INSERT INTO `paysdesc` VALUES (500, 114, 3, 'Mauritania', '', '');
INSERT INTO `paysdesc` VALUES (501, 115, 3, 'Méjico', '', '');
INSERT INTO `paysdesc` VALUES (502, 116, 3, 'Micronesia', '', '');
INSERT INTO `paysdesc` VALUES (503, 117, 3, 'Moldova', '', '');
INSERT INTO `paysdesc` VALUES (504, 118, 3, 'Mónaco', '', '');
INSERT INTO `paysdesc` VALUES (505, 119, 3, 'Mongolia', '', '');
INSERT INTO `paysdesc` VALUES (506, 120, 3, 'Mozambique', '', '');
INSERT INTO `paysdesc` VALUES (507, 121, 3, 'Namibia', '', '');
INSERT INTO `paysdesc` VALUES (508, 122, 3, 'Nauru', '', '');
INSERT INTO `paysdesc` VALUES (509, 123, 3, 'Nepal', '', '');
INSERT INTO `paysdesc` VALUES (510, 124, 3, 'Nicaragua', '', '');
INSERT INTO `paysdesc` VALUES (511, 125, 3, 'Níger', '', '');
INSERT INTO `paysdesc` VALUES (512, 126, 3, 'Nigeria', '', '');
INSERT INTO `paysdesc` VALUES (513, 127, 3, 'Niue', '', '');
INSERT INTO `paysdesc` VALUES (514, 128, 3, 'Noruega', '', '');
INSERT INTO `paysdesc` VALUES (515, 129, 3, 'Nueva Zelandia', '', '');
INSERT INTO `paysdesc` VALUES (516, 130, 3, 'Omán', '', '');
INSERT INTO `paysdesc` VALUES (517, 131, 3, 'Uganda', '', '');
INSERT INTO `paysdesc` VALUES (518, 132, 3, 'Uzbekistán', '', '');
INSERT INTO `paysdesc` VALUES (519, 133, 3, 'Pakistán', '', '');
INSERT INTO `paysdesc` VALUES (520, 134, 3, 'Panamá', '', '');
INSERT INTO `paysdesc` VALUES (521, 135, 3, 'Papua Nueva Guinea', '', '');
INSERT INTO `paysdesc` VALUES (522, 136, 3, 'Paraguay', '', '');
INSERT INTO `paysdesc` VALUES (523, 137, 3, 'Países Bajos', '', '');
INSERT INTO `paysdesc` VALUES (524, 138, 3, 'Perú', '', '');
INSERT INTO `paysdesc` VALUES (525, 139, 3, 'Filipinas', '', '');
INSERT INTO `paysdesc` VALUES (526, 140, 3, 'Polonia', '', '');
INSERT INTO `paysdesc` VALUES (527, 141, 3, 'Portugal', '', '');
INSERT INTO `paysdesc` VALUES (528, 142, 3, 'Qatar', '', '');
INSERT INTO `paysdesc` VALUES (529, 143, 3, 'República Centroafricana', '', '');
INSERT INTO `paysdesc` VALUES (530, 144, 3, 'República Dominicana', '', '');
INSERT INTO `paysdesc` VALUES (531, 145, 3, 'República Checa', '', '');
INSERT INTO `paysdesc` VALUES (532, 146, 3, 'Rumania', '', '');
INSERT INTO `paysdesc` VALUES (533, 147, 3, 'Reino Unido', '', '');
INSERT INTO `paysdesc` VALUES (534, 148, 3, 'Rusia', '', '');
INSERT INTO `paysdesc` VALUES (535, 149, 3, 'Ruanda', '', '');
INSERT INTO `paysdesc` VALUES (536, 150, 3, 'San Cristóbal', '', '');
INSERT INTO `paysdesc` VALUES (537, 151, 3, 'Santa Lucía', '', '');
INSERT INTO `paysdesc` VALUES (538, 152, 3, 'San Marino', '', '');
INSERT INTO `paysdesc` VALUES (539, 153, 3, 'San Vicente y las Granadinas', '', '');
INSERT INTO `paysdesc` VALUES (540, 154, 3, 'Salomón', '', '');
INSERT INTO `paysdesc` VALUES (541, 155, 3, 'El Salvador', '', '');
INSERT INTO `paysdesc` VALUES (542, 156, 3, 'Samoa', '', '');
INSERT INTO `paysdesc` VALUES (543, 157, 3, 'Santo Tomé y Príncipe', '', '');
INSERT INTO `paysdesc` VALUES (544, 158, 3, 'Senegal', '', '');
INSERT INTO `paysdesc` VALUES (545, 159, 3, 'Seychelles', '', '');
INSERT INTO `paysdesc` VALUES (546, 160, 3, 'Sierra Leona', '', '');
INSERT INTO `paysdesc` VALUES (547, 161, 3, 'Singapur', '', '');
INSERT INTO `paysdesc` VALUES (548, 162, 3, 'Eslovaquia', '', '');
INSERT INTO `paysdesc` VALUES (549, 163, 3, 'Eslovenia', '', '');
INSERT INTO `paysdesc` VALUES (550, 164, 3, 'Somalia', '', '');
INSERT INTO `paysdesc` VALUES (551, 165, 3, 'Sudán', '', '');
INSERT INTO `paysdesc` VALUES (552, 166, 3, 'Sri Lanka', '', '');
INSERT INTO `paysdesc` VALUES (553, 167, 3, 'Suecia', '', '');
INSERT INTO `paysdesc` VALUES (554, 168, 3, 'Suiza', '', '');
INSERT INTO `paysdesc` VALUES (555, 169, 3, 'Suriname', '', '');
INSERT INTO `paysdesc` VALUES (556, 170, 3, 'Swazilandia', '', '');
INSERT INTO `paysdesc` VALUES (557, 171, 3, 'Siria', '', '');
INSERT INTO `paysdesc` VALUES (558, 172, 3, 'Tayikistán', '', '');
INSERT INTO `paysdesc` VALUES (559, 173, 3, 'Tanzanía', '', '');
INSERT INTO `paysdesc` VALUES (560, 174, 3, 'Chad', '', '');
INSERT INTO `paysdesc` VALUES (561, 175, 3, 'Tailandia', '', '');
INSERT INTO `paysdesc` VALUES (562, 176, 3, 'Togo', '', '');
INSERT INTO `paysdesc` VALUES (563, 177, 3, 'Tonga', '', '');
INSERT INTO `paysdesc` VALUES (564, 178, 3, 'Trinidad y Tabago', '', '');
INSERT INTO `paysdesc` VALUES (565, 179, 3, 'Túnez', '', '');
INSERT INTO `paysdesc` VALUES (566, 180, 3, 'Turkmenistán', '', '');
INSERT INTO `paysdesc` VALUES (567, 181, 3, 'Turquía', '', '');
INSERT INTO `paysdesc` VALUES (568, 182, 3, 'Tuvalu', '', '');
INSERT INTO `paysdesc` VALUES (569, 183, 3, 'Ucrania', '', '');
INSERT INTO `paysdesc` VALUES (570, 184, 3, 'Uruguay', '', '');
INSERT INTO `paysdesc` VALUES (571, 185, 3, 'El Vatican', '', '');
INSERT INTO `paysdesc` VALUES (572, 186, 3, 'Vanuatu', '', '');
INSERT INTO `paysdesc` VALUES (573, 187, 3, 'Venezuela', '', '');
INSERT INTO `paysdesc` VALUES (574, 188, 3, 'Viet Nam', '', '');
INSERT INTO `paysdesc` VALUES (575, 189, 3, 'Yemen', '', '');
INSERT INTO `paysdesc` VALUES (576, 190, 3, 'Yugoslavia', '', '');
INSERT INTO `paysdesc` VALUES (577, 191, 3, 'Zaire', '', '');
INSERT INTO `paysdesc` VALUES (578, 192, 3, 'Zambia', '', '');
INSERT INTO `paysdesc` VALUES (579, 193, 3, 'Zimbabwe', '', '');
INSERT INTO `paysdesc` VALUES (583, 199, 1, 'USA - California', '', '');
INSERT INTO `paysdesc` VALUES (584, 200, 1, 'USA - Colorado', '', '');
INSERT INTO `paysdesc` VALUES (585, 201, 1, 'USA - Connecticut', '', '');
INSERT INTO `paysdesc` VALUES (586, 202, 1, 'USA - Delaware', '', '');
INSERT INTO `paysdesc` VALUES (587, 203, 1, 'USA - District Of Columbia', '', '');
INSERT INTO `paysdesc` VALUES (588, 204, 1, 'USA - Florida', '', '');
INSERT INTO `paysdesc` VALUES (589, 205, 1, 'USA - Georgia', '', '');
INSERT INTO `paysdesc` VALUES (590, 206, 1, 'USA - Hawaii', '', '');
INSERT INTO `paysdesc` VALUES (591, 207, 1, 'USA - Idaho', '', '');
INSERT INTO `paysdesc` VALUES (592, 208, 1, 'USA - Illinois', '', '');
INSERT INTO `paysdesc` VALUES (593, 209, 1, 'USA - Indiana', '', '');
INSERT INTO `paysdesc` VALUES (594, 210, 1, 'USA - Iowa', '', '');
INSERT INTO `paysdesc` VALUES (595, 211, 1, 'USA - Kansas', '', '');
INSERT INTO `paysdesc` VALUES (596, 212, 1, 'USA - Kentucky', '', '');
INSERT INTO `paysdesc` VALUES (597, 213, 1, 'USA - Louisiana', '', '');
INSERT INTO `paysdesc` VALUES (598, 214, 1, 'USA - Maine', '', '');
INSERT INTO `paysdesc` VALUES (599, 215, 1, 'USA - Maryland', '', '');
INSERT INTO `paysdesc` VALUES (600, 216, 1, 'USA - Massachusetts', '', '');
INSERT INTO `paysdesc` VALUES (601, 217, 1, 'USA - Michigan', '', '');
INSERT INTO `paysdesc` VALUES (602, 218, 1, 'USA - Minnesota', '', '');
INSERT INTO `paysdesc` VALUES (603, 219, 1, 'USA - Mississippi', '', '');
INSERT INTO `paysdesc` VALUES (604, 220, 1, 'USA - Missouri', '', '');
INSERT INTO `paysdesc` VALUES (605, 221, 1, 'USA - Montana', '', '');
INSERT INTO `paysdesc` VALUES (606, 222, 1, 'USA - Nebraska', '', '');
INSERT INTO `paysdesc` VALUES (607, 223, 1, 'USA - Nevada', '', '');
INSERT INTO `paysdesc` VALUES (608, 224, 1, 'USA - New Hampshire', '', '');
INSERT INTO `paysdesc` VALUES (609, 225, 1, 'USA - New Jersey', '', '');
INSERT INTO `paysdesc` VALUES (610, 226, 1, 'USA - New Mexico', '', '');
INSERT INTO `paysdesc` VALUES (611, 227, 1, 'USA - New York', '', '');
INSERT INTO `paysdesc` VALUES (612, 228, 1, 'USA - North Carolina', '', '');
INSERT INTO `paysdesc` VALUES (613, 229, 1, 'USA - North Dakota', '', '');
INSERT INTO `paysdesc` VALUES (614, 230, 1, 'USA - Ohio', '', '');
INSERT INTO `paysdesc` VALUES (615, 231, 1, 'USA - Oklahoma', '', '');
INSERT INTO `paysdesc` VALUES (616, 232, 1, 'USA - Oregon', '', '');
INSERT INTO `paysdesc` VALUES (617, 233, 1, 'USA - Pennsylvania', '', '');
INSERT INTO `paysdesc` VALUES (618, 234, 1, 'USA - Rhode Island', '', '');
INSERT INTO `paysdesc` VALUES (619, 235, 1, 'USA - South Carolina', '', '');
INSERT INTO `paysdesc` VALUES (620, 236, 1, 'USA - South Dakota', '', '');
INSERT INTO `paysdesc` VALUES (621, 237, 1, 'USA - Tennessee', '', '');
INSERT INTO `paysdesc` VALUES (622, 238, 1, 'USA - Texas', '', '');
INSERT INTO `paysdesc` VALUES (623, 239, 1, 'USA - Utah', '', '');
INSERT INTO `paysdesc` VALUES (624, 240, 1, 'USA - Vermont', '', '');
INSERT INTO `paysdesc` VALUES (625, 241, 1, 'USA - Virginia', '', '');
INSERT INTO `paysdesc` VALUES (626, 242, 1, 'USA - Washington', '', '');
INSERT INTO `paysdesc` VALUES (627, 243, 1, 'USA - West Virginia', '', '');
INSERT INTO `paysdesc` VALUES (628, 244, 1, 'USA - Wisconsin', '', '');
INSERT INTO `paysdesc` VALUES (629, 245, 1, 'USA - Wyoming', '', '');
INSERT INTO `paysdesc` VALUES (630, 196, 2, 'USA - Alaska', '', '');
INSERT INTO `paysdesc` VALUES (631, 197, 2, 'USA - Arizona', '', '');
INSERT INTO `paysdesc` VALUES (632, 198, 2, 'USA - Arkansas', '', '');
INSERT INTO `paysdesc` VALUES (633, 199, 2, 'USA - California', '', '');
INSERT INTO `paysdesc` VALUES (634, 200, 2, 'USA - Colorado', '', '');
INSERT INTO `paysdesc` VALUES (635, 201, 2, 'USA - Connecticut', '', '');
INSERT INTO `paysdesc` VALUES (636, 202, 2, 'USA - Delaware', '', '');
INSERT INTO `paysdesc` VALUES (637, 203, 2, 'USA - District Of Columbia', '', '');
INSERT INTO `paysdesc` VALUES (638, 204, 2, 'USA - Florida', '', '');
INSERT INTO `paysdesc` VALUES (639, 205, 2, 'USA - Georgia', '', '');
INSERT INTO `paysdesc` VALUES (640, 206, 2, 'USA - Hawaii', '', '');
INSERT INTO `paysdesc` VALUES (641, 207, 2, 'USA - Idaho', '', '');
INSERT INTO `paysdesc` VALUES (642, 208, 2, 'USA - Illinois', '', '');
INSERT INTO `paysdesc` VALUES (643, 209, 2, 'USA - Indiana', '', '');
INSERT INTO `paysdesc` VALUES (644, 210, 2, 'USA - Iowa', '', '');
INSERT INTO `paysdesc` VALUES (645, 211, 2, 'USA - Kansas', '', '');
INSERT INTO `paysdesc` VALUES (646, 212, 2, 'USA - Kentucky', '', '');
INSERT INTO `paysdesc` VALUES (647, 213, 2, 'USA - Louisiana', '', '');
INSERT INTO `paysdesc` VALUES (648, 214, 2, 'USA - Maine', '', '');
INSERT INTO `paysdesc` VALUES (649, 215, 2, 'USA - Maryland', '', '');
INSERT INTO `paysdesc` VALUES (650, 216, 2, 'USA - Massachusetts', '', '');
INSERT INTO `paysdesc` VALUES (651, 217, 2, 'USA - Michigan', '', '');
INSERT INTO `paysdesc` VALUES (652, 218, 2, 'USA - Minnesota', '', '');
INSERT INTO `paysdesc` VALUES (653, 219, 2, 'USA - Mississippi', '', '');
INSERT INTO `paysdesc` VALUES (654, 220, 2, 'USA - Missouri', '', '');
INSERT INTO `paysdesc` VALUES (655, 221, 2, 'USA - Montana', '', '');
INSERT INTO `paysdesc` VALUES (656, 222, 2, 'USA - Nebraska', '', '');
INSERT INTO `paysdesc` VALUES (657, 223, 2, 'USA - Nevada', '', '');
INSERT INTO `paysdesc` VALUES (658, 224, 2, 'USA - New Hampshire', '', '');
INSERT INTO `paysdesc` VALUES (659, 225, 2, 'USA - New Jersey', '', '');
INSERT INTO `paysdesc` VALUES (660, 226, 2, 'USA - New Mexico', '', '');
INSERT INTO `paysdesc` VALUES (661, 227, 2, 'USA - New York', '', '');
INSERT INTO `paysdesc` VALUES (662, 228, 2, 'USA - North Carolina', '', '');
INSERT INTO `paysdesc` VALUES (663, 229, 2, 'USA - North Dakota', '', '');
INSERT INTO `paysdesc` VALUES (664, 230, 2, 'USA - Ohio', '', '');
INSERT INTO `paysdesc` VALUES (665, 231, 2, 'USA - Oklahoma', '', '');
INSERT INTO `paysdesc` VALUES (666, 232, 2, 'USA - Oregon', '', '');
INSERT INTO `paysdesc` VALUES (667, 233, 2, 'USA - Pennsylvania', '', '');
INSERT INTO `paysdesc` VALUES (668, 234, 2, 'USA - Rhode Island', '', '');
INSERT INTO `paysdesc` VALUES (669, 235, 2, 'USA - South Carolina', '', '');
INSERT INTO `paysdesc` VALUES (670, 236, 2, 'USA - South Dakota', '', '');
INSERT INTO `paysdesc` VALUES (671, 237, 2, 'USA - Tennessee', '', '');
INSERT INTO `paysdesc` VALUES (672, 238, 2, 'USA - Texas', '', '');
INSERT INTO `paysdesc` VALUES (673, 239, 2, 'USA - Utah', '', '');
INSERT INTO `paysdesc` VALUES (674, 240, 2, 'USA - Vermont', '', '');
INSERT INTO `paysdesc` VALUES (675, 241, 2, 'USA - Virginia', '', '');
INSERT INTO `paysdesc` VALUES (676, 242, 2, 'USA - Washington', '', '');
INSERT INTO `paysdesc` VALUES (677, 243, 2, 'USA - West Virginia', '', '');
INSERT INTO `paysdesc` VALUES (678, 244, 2, 'USA - Wisconsin', '', '');
INSERT INTO `paysdesc` VALUES (679, 245, 2, 'USA - Wyoming', '', '');
INSERT INTO `paysdesc` VALUES (680, 196, 3, 'USA - Alaska', '', '');
INSERT INTO `paysdesc` VALUES (681, 197, 3, 'USA - Arizona', '', '');
INSERT INTO `paysdesc` VALUES (682, 198, 3, 'USA - Arkansas', '', '');
INSERT INTO `paysdesc` VALUES (683, 199, 3, 'USA - California', '', '');
INSERT INTO `paysdesc` VALUES (684, 200, 3, 'USA - Colorado', '', '');
INSERT INTO `paysdesc` VALUES (685, 201, 3, 'USA - Connecticut', '', '');
INSERT INTO `paysdesc` VALUES (686, 202, 3, 'USA - Delaware', '', '');
INSERT INTO `paysdesc` VALUES (687, 203, 3, 'USA - District Of Columbia', '', '');
INSERT INTO `paysdesc` VALUES (688, 204, 3, 'USA - Florida', '', '');
INSERT INTO `paysdesc` VALUES (689, 205, 3, 'USA - Georgia', '', '');
INSERT INTO `paysdesc` VALUES (690, 206, 3, 'USA - Hawaii', '', '');
INSERT INTO `paysdesc` VALUES (691, 207, 3, 'USA - Idaho', '', '');
INSERT INTO `paysdesc` VALUES (692, 208, 3, 'USA - Illinois', '', '');
INSERT INTO `paysdesc` VALUES (693, 209, 3, 'USA - Indiana', '', '');
INSERT INTO `paysdesc` VALUES (694, 210, 3, 'USA - Iowa', '', '');
INSERT INTO `paysdesc` VALUES (695, 211, 3, 'USA - Kansas', '', '');
INSERT INTO `paysdesc` VALUES (696, 212, 3, 'USA - Kentucky', '', '');
INSERT INTO `paysdesc` VALUES (697, 213, 3, 'USA - Louisiana', '', '');
INSERT INTO `paysdesc` VALUES (698, 214, 3, 'USA - Maine', '', '');
INSERT INTO `paysdesc` VALUES (699, 215, 3, 'USA - Maryland', '', '');
INSERT INTO `paysdesc` VALUES (700, 216, 3, 'USA - Massachusetts', '', '');
INSERT INTO `paysdesc` VALUES (701, 217, 3, 'USA - Michigan', '', '');
INSERT INTO `paysdesc` VALUES (702, 218, 3, 'USA - Minnesota', '', '');
INSERT INTO `paysdesc` VALUES (703, 219, 3, 'USA - Mississippi', '', '');
INSERT INTO `paysdesc` VALUES (704, 220, 3, 'USA - Missouri', '', '');
INSERT INTO `paysdesc` VALUES (705, 221, 3, 'USA - Montana', '', '');
INSERT INTO `paysdesc` VALUES (706, 222, 3, 'USA - Nebraska', '', '');
INSERT INTO `paysdesc` VALUES (707, 223, 3, 'USA - Nevada', '', '');
INSERT INTO `paysdesc` VALUES (708, 224, 3, 'USA - New Hampshire', '', '');
INSERT INTO `paysdesc` VALUES (709, 225, 3, 'USA - New Jersey', '', '');
INSERT INTO `paysdesc` VALUES (710, 226, 3, 'USA - New Mexico', '', '');
INSERT INTO `paysdesc` VALUES (711, 227, 3, 'USA - New York', '', '');
INSERT INTO `paysdesc` VALUES (712, 228, 3, 'USA - North Carolina', '', '');
INSERT INTO `paysdesc` VALUES (713, 229, 3, 'USA - North Dakota', '', '');
INSERT INTO `paysdesc` VALUES (714, 230, 3, 'USA - Ohio', '', '');
INSERT INTO `paysdesc` VALUES (715, 231, 3, 'USA - Oklahoma', '', '');
INSERT INTO `paysdesc` VALUES (716, 232, 3, 'USA - Oregon', '', '');
INSERT INTO `paysdesc` VALUES (717, 233, 3, 'USA - Pennsylvania', '', '');
INSERT INTO `paysdesc` VALUES (718, 234, 3, 'USA - Rhode Island', '', '');
INSERT INTO `paysdesc` VALUES (719, 235, 3, 'USA - South Carolina', '', '');
INSERT INTO `paysdesc` VALUES (720, 236, 3, 'USA - South Dakota', '', '');
INSERT INTO `paysdesc` VALUES (721, 237, 3, 'USA - Tennessee', '', '');
INSERT INTO `paysdesc` VALUES (722, 238, 3, 'USA - Texas', '', '');
INSERT INTO `paysdesc` VALUES (723, 239, 3, 'USA - Utah', '', '');
INSERT INTO `paysdesc` VALUES (724, 240, 3, 'USA - Vermont', '', '');
INSERT INTO `paysdesc` VALUES (725, 241, 3, 'USA - Virginia', '', '');
INSERT INTO `paysdesc` VALUES (726, 242, 3, 'USA - Washington', '', '');
INSERT INTO `paysdesc` VALUES (727, 243, 3, 'USA - West Virginia', '', '');
INSERT INTO `paysdesc` VALUES (728, 244, 3, 'USA - Wisconsin', '', '');
INSERT INTO `paysdesc` VALUES (729, 245, 3, 'USA - Wyoming', '', '');
INSERT INTO `paysdesc` VALUES (783, 247, 2, 'Canada - Alberta', '', '');
INSERT INTO `paysdesc` VALUES (782, 246, 2, 'Canada - Colombie-Britannique', '', '');
INSERT INTO `paysdesc` VALUES (781, 258, 1, 'Canada - Nunavut', '', '');
INSERT INTO `paysdesc` VALUES (780, 257, 1, 'Canada - Territoires-du-Nord-Ouest', '', '');
INSERT INTO `paysdesc` VALUES (779, 256, 1, 'Canada - Yukon', '', '');
INSERT INTO `paysdesc` VALUES (778, 255, 1, 'Canada - Terre-Neuve-et-Labrador	', '', '');
INSERT INTO `paysdesc` VALUES (777, 254, 1, 'Canada - Île-du-Prince-Édouard	', '', '');
INSERT INTO `paysdesc` VALUES (776, 253, 1, 'Canada - Nouvelle-Écosse', '', '');
INSERT INTO `paysdesc` VALUES (775, 252, 1, 'Canada - Nouveau-Brunswick', '', '');
INSERT INTO `paysdesc` VALUES (774, 251, 1, 'Canada - Québec', '', '');
INSERT INTO `paysdesc` VALUES (773, 250, 1, 'Canada - Ontario', '', '');
INSERT INTO `paysdesc` VALUES (772, 249, 1, 'Canada - Manitoba', '', '');
INSERT INTO `paysdesc` VALUES (771, 248, 1, 'Canada - Saskatchewan', '', '');
INSERT INTO `paysdesc` VALUES (770, 247, 1, 'Canada - Alberta', '', '');
INSERT INTO `paysdesc` VALUES (769, 246, 1, 'Canada - Colombie-Britannique', '', '');
INSERT INTO `paysdesc` VALUES (790, 254, 2, 'Canada - Île-du-Prince-Édouard	', '', '');
INSERT INTO `paysdesc` VALUES (789, 253, 2, 'Canada - Nouvelle-Écosse', '', '');
INSERT INTO `paysdesc` VALUES (788, 252, 2, 'Canada - Nouveau-Brunswick', '', '');
INSERT INTO `paysdesc` VALUES (787, 251, 2, 'Canada - Québec', '', '');
INSERT INTO `paysdesc` VALUES (786, 250, 2, 'Canada - Ontario', '', '');
INSERT INTO `paysdesc` VALUES (785, 249, 2, 'Canada - Manitoba', '', '');
INSERT INTO `paysdesc` VALUES (784, 248, 2, 'Canada - Saskatchewan', '', '');
INSERT INTO `paysdesc` VALUES (791, 255, 2, 'Canada - Terre-Neuve-et-Labrador	', '', '');
INSERT INTO `paysdesc` VALUES (792, 256, 2, 'Canada - Yukon', '', '');
INSERT INTO `paysdesc` VALUES (793, 257, 2, 'Canada - Territoires-du-Nord-Ouest', '', '');
INSERT INTO `paysdesc` VALUES (794, 258, 2, 'Canada - Nunavut', '', '');
INSERT INTO `paysdesc` VALUES (795, 246, 3, 'Canada - Colombie-Britannique', '', '');
INSERT INTO `paysdesc` VALUES (796, 247, 3, 'Canada - Alberta', '', '');
INSERT INTO `paysdesc` VALUES (797, 248, 3, 'Canada - Saskatchewan', '', '');
INSERT INTO `paysdesc` VALUES (798, 249, 3, 'Canada - Manitoba', '', '');
INSERT INTO `paysdesc` VALUES (799, 250, 3, 'Canada - Ontario', '', '');
INSERT INTO `paysdesc` VALUES (800, 251, 3, 'Canada - Québec', '', '');
INSERT INTO `paysdesc` VALUES (801, 252, 3, 'Canada - Nouveau-Brunswick', '', '');
INSERT INTO `paysdesc` VALUES (802, 253, 3, 'Canada - Nouvelle-Écosse', '', '');
INSERT INTO `paysdesc` VALUES (803, 254, 3, 'Canada - Île-du-Prince-Édouard	', '', '');
INSERT INTO `paysdesc` VALUES (804, 255, 3, 'Canada - Terre-Neuve-et-Labrador	', '', '');
INSERT INTO `paysdesc` VALUES (805, 256, 3, 'Canada - Yukon', '', '');
INSERT INTO `paysdesc` VALUES (806, 257, 3, 'Canada - Territoires-du-Nord-Ouest', '', '');
INSERT INTO `paysdesc` VALUES (807, 258, 3, 'Canada - Nunavut', '', '');
INSERT INTO `paysdesc` VALUES (808, 259, 1, 'Guadeloupe', '', '');
INSERT INTO `paysdesc` VALUES (809, 260, 1, 'Guyane Française', '', '');
INSERT INTO `paysdesc` VALUES (810, 261, 1, 'Martinique', '', '');
INSERT INTO `paysdesc` VALUES (811, 262, 1, 'Mayotte', '', '');
INSERT INTO `paysdesc` VALUES (812, 263, 1, 'Réunion(La)', '', '');
INSERT INTO `paysdesc` VALUES (813, 264, 1, 'St Pierre et Miquelon', '', '');
INSERT INTO `paysdesc` VALUES (814, 265, 1, 'Nouvelle-Calédonie', '', '');
INSERT INTO `paysdesc` VALUES (815, 259, 2, 'Guadeloupe', '', '');
INSERT INTO `paysdesc` VALUES (816, 260, 2, 'Guyane Française', '', '');
INSERT INTO `paysdesc` VALUES (817, 261, 2, 'Martinique', '', '');
INSERT INTO `paysdesc` VALUES (818, 262, 2, 'Mayotte', '', '');
INSERT INTO `paysdesc` VALUES (819, 263, 2, 'Réunion(La)', '', '');
INSERT INTO `paysdesc` VALUES (820, 264, 2, 'St Pierre et Miquelon', '', '');
INSERT INTO `paysdesc` VALUES (821, 265, 2, 'Nouvelle-Calédonie', '', '');
INSERT INTO `paysdesc` VALUES (822, 259, 3, 'Guadeloupe', '', '');
INSERT INTO `paysdesc` VALUES (823, 260, 3, 'Guyane Française', '', '');
INSERT INTO `paysdesc` VALUES (824, 261, 3, 'Martinique', '', '');
INSERT INTO `paysdesc` VALUES (825, 262, 3, 'Mayotte', '', '');
INSERT INTO `paysdesc` VALUES (826, 263, 3, 'Réunion(La)', '', '');
INSERT INTO `paysdesc` VALUES (827, 264, 3, 'St Pierre et Miquelon', '', '');
INSERT INTO `paysdesc` VALUES (828, 265, 3, 'Nouvelle-Calédonie', '', '');
INSERT INTO `paysdesc` VALUES (829, 266, 1, 'Polynésie française', '', '');
INSERT INTO `paysdesc` VALUES (830, 266, 2, 'Polynésie française', '', '');
INSERT INTO `paysdesc` VALUES (831, 266, 3, 'Polynésie française', '', '');
INSERT INTO `paysdesc` VALUES (832, 267, 1, 'Wallis-et-Futuna', '', '');
INSERT INTO `paysdesc` VALUES (833, 267, 2, 'Wallis-et-Futuna', '', '');
INSERT INTO `paysdesc` VALUES (834, 267, 3, 'Wallis-et-Futuna', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `produit`
-- 

CREATE TABLE `produit` (
  `id` int(11) NOT NULL auto_increment,
  `boutique` int(11) NOT NULL default '0',
  `ref` text NOT NULL,
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  `prix` float NOT NULL default '0',
  `promo` smallint(6) NOT NULL default '0',
  `prix2` float NOT NULL default '0',
  `reappro` smallint(6) NOT NULL default '0',
  `rubrique` int(11) NOT NULL default '0',
  `nouveaute` smallint(6) NOT NULL default '0',
  `perso` int(11) NOT NULL default '0',
  `stock` int(11) NOT NULL default '0',
  `quantite` int(11) NOT NULL default '0',
  `appro` date NOT NULL default '0000-00-00',
  `ligne` smallint(6) NOT NULL default '0',
  `garantie` int(11) NOT NULL default '0',
  `poids` float NOT NULL default '0',
  `unitetr` int(11) NOT NULL default '0',
  `tva` float NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `produit`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `produitdesc`
-- 

CREATE TABLE `produitdesc` (
  `id` int(11) NOT NULL auto_increment,
  `produit` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `lang` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `produitdesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `promo`
-- 

CREATE TABLE `promo` (
  `id` int(11) NOT NULL auto_increment,
  `code` text NOT NULL,
  `type` smallint(6) NOT NULL default '0',
  `valeur` float NOT NULL default '0',
  `mini` float NOT NULL default '0',
  `utilise` smallint(6) NOT NULL default '0',
  `illimite` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `promo`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rubcaracteristique`
-- 

CREATE TABLE `rubcaracteristique` (
  `id` int(11) NOT NULL auto_increment,
  `rubrique` int(11) NOT NULL default '0',
  `caracteristique` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `rubcaracteristique`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rubdeclinaison`
-- 

CREATE TABLE `rubdeclinaison` (
  `id` int(11) NOT NULL auto_increment,
  `rubrique` int(11) NOT NULL default '0',
  `declinaison` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `rubdeclinaison`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rubrique`
-- 

CREATE TABLE `rubrique` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default '0',
  `lien` text NOT NULL,
  `boutique` int(11) NOT NULL default '0',
  `ligne` smallint(6) NOT NULL default '0',
  `classement` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `rubrique`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rubriquedesc`
-- 

CREATE TABLE `rubriquedesc` (
  `id` int(11) NOT NULL auto_increment,
  `rubrique` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `rubriquedesc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `statut`
-- 

CREATE TABLE `statut` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `statut`
-- 

INSERT INTO `statut` VALUES (1);
INSERT INTO `statut` VALUES (2);
INSERT INTO `statut` VALUES (3);
INSERT INTO `statut` VALUES (4);
INSERT INTO `statut` VALUES (5);

-- --------------------------------------------------------

-- 
-- Structure de la table `statutdesc`
-- 

CREATE TABLE `statutdesc` (
  `id` int(11) NOT NULL auto_increment,
  `statut` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- 
-- Contenu de la table `statutdesc`
-- 

INSERT INTO `statutdesc` VALUES (23, 1, 1, 'Non payé', '', '');
INSERT INTO `statutdesc` VALUES (24, 2, 1, 'payé', '', '');
INSERT INTO `statutdesc` VALUES (25, 3, 1, 'Traitement', '', '');
INSERT INTO `statutdesc` VALUES (26, 4, 1, 'Envoyé', '', '');
INSERT INTO `statutdesc` VALUES (27, 5, 1, 'Annulé', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `stock`
-- 

CREATE TABLE `stock` (
  `id` int(11) NOT NULL auto_increment,
  `declidisp` int(11) NOT NULL default '0',
  `produit` int(11) NOT NULL default '0',
  `valeur` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `stock`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `transport`
-- 

CREATE TABLE `transport` (
  `id` int(11) NOT NULL auto_increment,
  `actif` smallint(6) NOT NULL default '0',
  `classe` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `transport`
-- 

INSERT INTO `transport` VALUES (1, 1, 'Colissimo');

-- --------------------------------------------------------

-- 
-- Structure de la table `transportdesc`
-- 

CREATE TABLE `transportdesc` (
  `id` int(11) NOT NULL auto_increment,
  `transport` int(11) NOT NULL default '0',
  `lang` int(11) NOT NULL default '0',
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `transportdesc`
-- 

INSERT INTO `transportdesc` VALUES (1, 1, 1, 'Colissimo', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `transproduit`
-- 

CREATE TABLE `transproduit` (
  `id` int(11) NOT NULL auto_increment,
  `transport` int(11) NOT NULL default '0',
  `produit` int(11) NOT NULL default '0',
  `actif` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `transproduit`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `transzone`
-- 

CREATE TABLE `transzone` (
  `id` int(11) NOT NULL auto_increment,
  `transport` int(11) NOT NULL default '0',
  `zone` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `transzone`
-- 

INSERT INTO `transzone` VALUES (1, 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `variable`
-- 

CREATE TABLE `variable` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `valeur` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `variable`
-- 

INSERT INTO `variable` VALUES (1, 'emailcontact', 'commande@monsite.com');
INSERT INTO `variable` VALUES (2, 'nomsite', 'Mon Site');
INSERT INTO `variable` VALUES (3, 'urlsite', 'http://www.monsite.com');
INSERT INTO `variable` VALUES (4, 'photorubw', '200');
INSERT INTO `variable` VALUES (5, 'photoprodw', '200');
INSERT INTO `variable` VALUES (6, 'tva', '19.6');
INSERT INTO `variable` VALUES (7, 'style_chem', '/style_editeur.css');
INSERT INTO `variable` VALUES (8, 'rsspass', '');
INSERT INTO `variable` VALUES (9, 'rssadmin', 'http://www.octolys.fr/rss.php');
-- --------------------------------------------------------

-- 
-- Structure de la table `venteprod`
-- 

CREATE TABLE `venteprod` (
  `id` int(11) NOT NULL auto_increment,
  `ref` text NOT NULL,
  `titre` text NOT NULL,
  `chapo` text NOT NULL,
  `description` text NOT NULL,
  `quantite` int(11) NOT NULL default '0',
  `prixu` float NOT NULL default '0',
  `tva` float NOT NULL default '0',
  `commande` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `venteprod`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `zone`
-- 

CREATE TABLE `zone` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `unite` float NOT NULL default '0',
  `moddoc` int(11) NOT NULL default '0',
  `devise` int(11) NOT NULL default '0',
  `tva` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=49 ;

-- 
-- Contenu de la table `zone`
-- 

INSERT INTO `zone` VALUES (1, 'France', 0, 1, 0, 1);

-- 
-- Table structure for table `modules`
-- 

CREATE TABLE `modules` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `type` smallint(6) NOT NULL,
  `actif` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `modules`
-- 

INSERT INTO `modules` VALUES (1, 'cheque', 1, 1);
INSERT INTO `modules` VALUES (2, 'virement', 1, 1);
INSERT INTO `modules` VALUES (3, 'cic', 1, 1);
INSERT INTO `modules` VALUES (4, 'paybox', 1, 1);
INSERT INTO `modules` VALUES (5, 'spplus', 1, 1);
INSERT INTO `modules` VALUES (6, 'atos', 1, 1);


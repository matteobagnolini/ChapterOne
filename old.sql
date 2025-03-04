-- *********************************************
-- * Standard SQL generation                   
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Mon Aug 12 08:59:31 2024 
-- * LUN file: C:\Users\Giuseppe\OneDrive - Alma Mater Studiorum Università di Bologna\università\Basi di Dati\Progetto\PROJECT3.lun 
-- * Schema: SCHEMARel/SQL 
-- ********************************************* 


-- Database Section
-- ________________ 

create database SCHEMARel;


-- DBSpace Section
-- _______________


-- Tables Section
-- _____________ 

create table ADDETTO_LINEA (
     ID numeric(1) not null,
     constraint ID_ADDET_DIPEN_ID primary key (ID));

create table CAPANNONE (
     ID numeric(1) not null,
     NumeroCapannone numeric(1) not null,
     Numero_Linee numeric(2) not null,
     MetriQuadrati numeric(5) not null,
     ID_Sede numeric(1) not null,
     constraint ID_CAPANNONE_ID primary key (ID),
     constraint SID_CAPANNONE_ID unique (ID_Sede, NumeroCapannone));

create table CICLO_PRODUTTIVO (
     ID numeric(1) not null,
     ID_Prodotto numeric(1) not null,
     ID_Commessa numeric(1) not null,
     Data date not null,
     Orario_Inizio varchar(255) not null,
     Orario_fine varchar(255),
     Kilowattora numeric(3) not null,
     NumeroAllarmi numeric(3) not null,
     TempoStop numeric(3) not null,
     Scarti numeric(3) not null,
     Pezzi_Prodotti numeric(3) not null,
     Quantita_Richiesta char(1) not null,
     ID_Linea numeric(1) not null,
     constraint ID_CICLO_PRODUTTIVO_ID primary key (ID),
     constraint SID_CICLO_PRODUTTIVO_ID unique (ID_Prodotto, ID_Commessa));

create table CLIENTE (
     P.IVA varchar(255) not null,
     Nome varchar(10) not null,
     Telefono varchar(255) not null,
     Email varchar(20) not null,
     Ind_Citta varchar(255) not null,
     Ind_Stato varchar(255) not null,
     Ind_Via varchar(255) not null,
     constraint ID_CLIENTE_ID primary key (P.IVA));

create table COMMESSA (
     ID numeric(1) not null,
     Prezzo numeric(4) not null,
     Data_Ordine date not null,
     Data_Consegna date,
     P_IVA varchar(255) not null,
     constraint ID_COMMESSA_ID primary key (ID));

create table COMPOSIZIONE (
     ID_Prodotto numeric(1) not null,
     ID_MateriaPrima numeric(1) not null,
     Quantita numeric(3) not null,
     constraint ID_COMPOSIZIONE_ID primary key (ID_Prodotto, ID_MateriaPrima));

create table CREAZIONE (
     ID_Linea numeric(1) not null,
     ID_Prodotto numeric(1) not null,
     constraint ID_CREAZIONE_ID primary key (ID_Prodotto, ID_Linea));

create table DEPOSITO (
     ID_Sede numeric(1) not null,
     ID_Ricambio numeric(1) not null,
     Quantita numeric(3) not null,
     constraint ID_DEPOSITO_ID primary key (ID_Ricambio, ID_Sede));

create table DETTAGLIOACQUISTO (
     ID_Commessa numeric(1) not null,
     ID_Prodotto numeric(1) not null,
     Quantita numeric(3) not null,
     constraint ID_DETTAGLIOACQUISTO_ID primary key (ID_Prodotto, ID_Commessa));

create table DIPENDENTE (
     ID numeric(1) not null,
     CodiceFiscale varchar(255) not null,
     Nome varchar(255) not null,
     Cognome varchar(255) not null,
     Telefono numeric(1) not null,
     DataInizioContratto date not null,
     Stipendio numeric(4) not null,
     ID_Sede numeric(1) not null,
     constraint ID_DIPENDENTE_ID primary key (ID),
     constraint SID_DIPENDENTE_ID unique (CodiceFiscale));

create table LINEA (
     ID numeric(1) not null,
     Numero_Linea numeric(2) not null,
     ID_Capannone numeric(1) not null,
     Numero_Macchinari numeric(2) not null,
     Occupata char not null,
     ID_Sede numeric(1) not null,
     constraint ID_LINEA_ID primary key (ID),
     constraint SID_LINEA_ID unique (ID_Capannone, Numero_Linea));

create table MACCHINARIO (
     ID char(1) not null,
     Posizione_Macchina numeric(2),
     ID_Linea numeric(1),
     Dat_Marca varchar(255) not null,
     Dat_Modello varchar(255) not null,
     Dat_Anno_Acquisto numeric(1) not null,
     constraint ID_MACCHINARIO_ID primary key (ID),
     constraint SID_MACCHINARIO_ID unique (ID_Linea, Posizione_Macchina));

create table MANUTENTORE (
     ID numeric(1) not null,
     constraint ID_MANUT_DIPEN_ID primary key (ID));

create table MANUTENZIONE (
     ID numeric(1) not null,
     Data date not null,
     Orario_Inizio varchar(255) not null,
     ID_Macchinario char(1) not null,
     Orario_Fine varchar(255) not null,
     DettaglioManutenzione varchar(255) not null,
     ID_Manutentore numeric(1) not null,
     constraint ID_MANUTENZIONE_ID primary key (ID),
     constraint SID_MANUTENZIONE_ID unique (ID_Macchinario, Data, Orario_Inizio));

create table MATERIA_PRIMA (
     ID numeric(1) not null,
     Nome varchar(20) not null,
     Costo numeric(3) not null,
     constraint ID_MATERIA_PRIMA_ID primary key (ID));

create table PERFORMANCE_MACCHINA (
     Kilowattora numeric(3) not null,
     NumeroAllarmi numeric(3) not null,
     TempoStop numeric(3) not null,
     Scarti numeric(3) not null,
     Pezzi_Prodotti numeric(3) not null,
     ID_Macchinario char(1) not null,
     ID_Ciclo numeric(1) not null);

create table PRODOTTO (
     ID numeric(1) not null,
     Nome varchar(20) not null,
     Prezzo_Vendita numeric(3) not null,
     Costo numeric(3) not null,
     constraint ID_PRODOTTO_ID primary key (ID));

create table RICAMBIO (
     ID numeric(1) not null,
     Nome varchar(20) not null,
     Costo numeric(1) not null,
     constraint ID_RICAMBIO_ID primary key (ID));

create table SEDE (
     ID numeric(1) not null,
     MetriQuadratiMagazzinoRisorse numeric(5) not null,
     MetriQuadratiMagazzinoRicambi numeric(5) not null,
     Ind_Citta varchar(255) not null,
     Ind_Stato varchar(255) not null,
     Ind_Via varchar(255) not null,
     constraint ID_SEDE_ID primary key (ID),
     constraint SID_SEDE_ID unique (Ind_Citta, Ind_Stato, Ind_Via));

create table STOCCAGGIO (
     ID_MateriaPrima numeric(1) not null,
     ID_Sede numeric(1) not null,
     Quantita numeric(3) not null,
     constraint ID_STOCCAGGIO_ID primary key (ID_MateriaPrima, ID_Sede));

create table TURNO (
     ID numeric(1) not null,
     Data date not null,
     Orario_Inizio varchar(255) not null,
     ID_Linea numeric(1) not null,
     Orario_Fine varchar(255) not null,
     ID_AddettoLinea numeric(1) not null,
     constraint ID_TURNO_ID primary key (ID),
     constraint SID_TURNO_ID unique (ID_Linea, Data, Orario_Inizio));

create table UTILIZZO (
     Quantita numeric(255) not null,
     ID_Manutenzione numeric(1) not null,
     ID_Ricambio numeric(1) not null);


-- Constraints Section
-- ___________________ 

alter table ADDETTO_LINEA add constraint ID_ADDET_DIPEN_FK
     foreign key (ID)
     references DIPENDENTE;

alter table CAPANNONE add constraint REF_CAPAN_SEDE
     foreign key (ID_Sede)
     references SEDE;

alter table CICLO_PRODUTTIVO add constraint ID_CICLO_PRODUTTIVO_CHK
     check(exists(select * from PERFORMANCE_MACCHINA
                  where PERFORMANCE_MACCHINA.ID_Ciclo = ID)); 

alter table CICLO_PRODUTTIVO add constraint REF_CICLO_LINEA_FK
     foreign key (ID_Linea)
     references LINEA;

alter table CICLO_PRODUTTIVO add constraint REF_CICLO_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table CICLO_PRODUTTIVO add constraint REF_CICLO_COMME_FK
     foreign key (ID_Commessa)
     references COMMESSA;

alter table CLIENTE add constraint ID_CLIENTE_CHK
     check(exists(select * from COMMESSA
                  where COMMESSA.P_IVA = P.IVA)); 

alter table COMMESSA add constraint EQU_COMME_CLIEN_FK
     foreign key (P_IVA)
     references CLIENTE;

alter table COMPOSIZIONE add constraint EQU_COMPO_MATER_FK
     foreign key (ID_MateriaPrima)
     references MATERIA_PRIMA;

alter table COMPOSIZIONE add constraint EQU_COMPO_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table CREAZIONE add constraint EQU_CREAZ_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table CREAZIONE add constraint EQU_CREAZ_LINEA_FK
     foreign key (ID_Linea)
     references LINEA;

alter table DEPOSITO add constraint REF_DEPOS_RICAM
     foreign key (ID_Ricambio)
     references RICAMBIO;

alter table DEPOSITO add constraint REF_DEPOS_SEDE_FK
     foreign key (ID_Sede)
     references SEDE;

alter table DETTAGLIOACQUISTO add constraint REF_DETTA_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table DETTAGLIOACQUISTO add constraint REF_DETTA_COMME_FK
     foreign key (ID_Commessa)
     references COMMESSA;

alter table DIPENDENTE add constraint REF_DIPEN_SEDE_FK
     foreign key (ID_Sede)
     references SEDE;

alter table LINEA add constraint ID_LINEA_CHK
     check(exists(select * from CREAZIONE
                  where CREAZIONE.ID_Linea = ID)); 

alter table LINEA add constraint REF_LINEA_SEDE_FK
     foreign key (ID_Sede)
     references SEDE;

alter table LINEA add constraint REF_LINEA_CAPAN
     foreign key (ID_Capannone)
     references CAPANNONE;

alter table MACCHINARIO add constraint ID_MACCHINARIO_CHK
     check(exists(select * from PERFORMANCE_MACCHINA
                  where PERFORMANCE_MACCHINA.ID_Macchinario = ID)); 

alter table MACCHINARIO add constraint REF_MACCH_LINEA
     foreign key (ID_Linea)
     references LINEA;

alter table MANUTENTORE add constraint ID_MANUT_DIPEN_FK
     foreign key (ID)
     references DIPENDENTE;

alter table MANUTENZIONE add constraint REF_MANUT_MANUT_FK
     foreign key (ID_Manutentore)
     references MANUTENTORE;

alter table MANUTENZIONE add constraint REF_MANUT_MACCH
     foreign key (ID_Macchinario)
     references MACCHINARIO;

alter table MATERIA_PRIMA add constraint ID_MATERIA_PRIMA_CHK
     check(exists(select * from COMPOSIZIONE
                  where COMPOSIZIONE.ID_MateriaPrima = ID)); 

alter table PERFORMANCE_MACCHINA add constraint EQU_PERFO_MACCH_FK
     foreign key (ID_Macchinario)
     references MACCHINARIO;

alter table PERFORMANCE_MACCHINA add constraint EQU_PERFO_CICLO_FK
     foreign key (ID_Ciclo)
     references CICLO_PRODUTTIVO;

alter table PRODOTTO add constraint ID_PRODOTTO_CHK
     check(exists(select * from CREAZIONE
                  where CREAZIONE.ID_Prodotto = ID)); 

alter table PRODOTTO add constraint ID_PRODOTTO_CHK
     check(exists(select * from COMPOSIZIONE
                  where COMPOSIZIONE.ID_Prodotto = ID)); 

alter table STOCCAGGIO add constraint REF_STOCC_SEDE_FK
     foreign key (ID_Sede)
     references SEDE;

alter table STOCCAGGIO add constraint REF_STOCC_MATER
     foreign key (ID_MateriaPrima)
     references MATERIA_PRIMA;

alter table TURNO add constraint REF_TURNO_ADDET_FK
     foreign key (ID_AddettoLinea)
     references ADDETTO_LINEA;

alter table TURNO add constraint REF_TURNO_LINEA
     foreign key (ID_Linea)
     references LINEA;

alter table UTILIZZO add constraint REF_UTILI_MANUT_FK
     foreign key (ID_Manutenzione)
     references MANUTENZIONE;

alter table UTILIZZO add constraint REF_UTILI_RICAM_FK
     foreign key (ID_Ricambio)
     references RICAMBIO;


-- Index Section
-- _____________ 

create unique index ID_ADDET_DIPEN_IND
     on ADDETTO_LINEA (ID);

create unique index ID_CAPANNONE_IND
     on CAPANNONE (ID);

create unique index SID_CAPANNONE_IND
     on CAPANNONE (ID_Sede, NumeroCapannone);

create unique index ID_CICLO_PRODUTTIVO_IND
     on CICLO_PRODUTTIVO (ID);

create unique index SID_CICLO_PRODUTTIVO_IND
     on CICLO_PRODUTTIVO (ID_Prodotto, ID_Commessa);

create index REF_CICLO_LINEA_IND
     on CICLO_PRODUTTIVO (ID_Linea);

create index REF_CICLO_COMME_IND
     on CICLO_PRODUTTIVO (ID_Commessa);

create unique index ID_CLIENTE_IND
     on CLIENTE (P.IVA);

create unique index ID_COMMESSA_IND
     on COMMESSA (ID);

create index EQU_COMME_CLIEN_IND
     on COMMESSA (P_IVA);

create unique index ID_COMPOSIZIONE_IND
     on COMPOSIZIONE (ID_Prodotto, ID_MateriaPrima);

create index EQU_COMPO_MATER_IND
     on COMPOSIZIONE (ID_MateriaPrima);

create unique index ID_CREAZIONE_IND
     on CREAZIONE (ID_Prodotto, ID_Linea);

create index EQU_CREAZ_LINEA_IND
     on CREAZIONE (ID_Linea);

create unique index ID_DEPOSITO_IND
     on DEPOSITO (ID_Ricambio, ID_Sede);

create index REF_DEPOS_SEDE_IND
     on DEPOSITO (ID_Sede);

create unique index ID_DETTAGLIOACQUISTO_IND
     on DETTAGLIOACQUISTO (ID_Prodotto, ID_Commessa);

create index REF_DETTA_COMME_IND
     on DETTAGLIOACQUISTO (ID_Commessa);

create unique index ID_DIPENDENTE_IND
     on DIPENDENTE (ID);

create unique index SID_DIPENDENTE_IND
     on DIPENDENTE (CodiceFiscale);

create index REF_DIPEN_SEDE_IND
     on DIPENDENTE (ID_Sede);

create unique index ID_LINEA_IND
     on LINEA (ID);

create unique index SID_LINEA_IND
     on LINEA (ID_Capannone, Numero_Linea);

create index REF_LINEA_SEDE_IND
     on LINEA (ID_Sede);

create unique index ID_MACCHINARIO_IND
     on MACCHINARIO (ID);

create unique index SID_MACCHINARIO_IND
     on MACCHINARIO (ID_Linea, Posizione_Macchina);

create unique index ID_MANUT_DIPEN_IND
     on MANUTENTORE (ID);

create unique index ID_MANUTENZIONE_IND
     on MANUTENZIONE (ID);

create unique index SID_MANUTENZIONE_IND
     on MANUTENZIONE (ID_Macchinario, Data, Orario_Inizio);

create index REF_MANUT_MANUT_IND
     on MANUTENZIONE (ID_Manutentore);

create unique index ID_MATERIA_PRIMA_IND
     on MATERIA_PRIMA (ID);

create index EQU_PERFO_MACCH_IND
     on PERFORMANCE_MACCHINA (ID_Macchinario);

create index EQU_PERFO_CICLO_IND
     on PERFORMANCE_MACCHINA (ID_Ciclo);

create unique index ID_PRODOTTO_IND
     on PRODOTTO (ID);

create unique index ID_RICAMBIO_IND
     on RICAMBIO (ID);

create unique index ID_SEDE_IND
     on SEDE (ID);

create unique index SID_SEDE_IND
     on SEDE (Ind_Citta, Ind_Stato, Ind_Via);

create unique index ID_STOCCAGGIO_IND
     on STOCCAGGIO (ID_MateriaPrima, ID_Sede);

create index REF_STOCC_SEDE_IND
     on STOCCAGGIO (ID_Sede);

create unique index ID_TURNO_IND
     on TURNO (ID);

create unique index SID_TURNO_IND
     on TURNO (ID_Linea, Data, Orario_Inizio);

create index REF_TURNO_ADDET_IND
     on TURNO (ID_AddettoLinea);

create index REF_UTILI_MANUT_IND
     on UTILIZZO (ID_Manutenzione);

create index REF_UTILI_RICAM_IND
     on UTILIZZO (ID_Ricambio);


BEGIN;

CREATE TYPE xplankonverter.enum_konvertierungsstatus AS ENUM ('erstellt','Angaben vollständig','validiert','in Arbeit','fertig');
CREATE TYPE xplankonverter.enum_geometrietyp AS ENUM ('Point','Line','Polygon');
CREATE TYPE xplankonverter.enum_factory AS ENUM ('sql','form','default');

CREATE TABLE xplankonverter.konvertierungen
(
  id serial NOT NULL,
  bezeichnung character varying,
  beschreibung text,
  status xplankonverter.enum_konvertierungsstatus NOT NULL DEFAULT 'erstellt',
  stelle_id integer,
  CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.konvertierungen.bezeichnung IS 'Bezeichnung der Konvertierung. (Freitext)';
COMMENT ON COLUMN xplankonverter.konvertierungen.bescheibung IS 'Nähere Angaben zur Konvertierung, bzw. zum Plan, der mit den dazugehörigen Regeln konvertiert werden soll. (Freitext)';
COMMENT ON COLUMN xplankonverter.konvertierungen.status IS 'Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.';
COMMENT ON COLUMN xplankonverter.konvertierungen.stelle_id IS 'Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.';

CREATE TABLE xplankonverter.regeln
(
  id integer,
  class_name character varying,
  factory xplankonverter.enum_factory NOT NULL DEFAULT 'sql',
  sql text,
  name character varying,
  beschreibung text,
  geometrietyp xplankonverter.enum_geometrietyp,
  epsg_code integer,
  konvertierung_id integer,
  stelle_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
  updated_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
  CONSTRAINT regeln_id_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.regeln.id IS 'Id der Konvertierungsregel.';
COMMENT ON COLUMN xplankonverter.regeln.class_name IS 'Name der Klassse im XPlan-Datenmodell, die mit dieser Regel befüllt werden soll.';
COMMENT ON COLUMN xplankonverter.regeln.factory IS 'Art der Befüllung der Klasse mit Werten. SQL ... Daten werden über ein SQL-Statement abgefragt. form ... Daten werden über ein Web-Formular vom Nutzer eingegeben. default ... Daten werden aus einer Tabelle mit Default-Werten übernommen.';
COMMENT ON COLUMN xplankonverter.regeln.sql IS 'Das SQL-Statement mit dem die Objekte der Klasse bestückt werden sollen.';
COMMENT ON COLUMN xplankonverter.regeln.name IS 'Name der Regel.';
COMMENT ON COLUMN xplankonverter.regeln.beschreibung IS 'Beschreibung der Regel.';
COMMENT ON COLUMN xplankonverter.regeln.geometrietyp IS 'Typ der Geometrie, die zur Klasse gehört. Point, Line, Polyline';
COMMENT ON COLUMN xplankonverter.regeln.epsg_code IS 'EPSG-Code in dem die Geometrien für diese Klasse vorliegen.';
COMMENT ON COLUMN xplankonverter.regeln.konvertierung_id IS 'Id der Konvertierung zu dem diese Regel gehört.';
COMMENT ON COLUMN xplankonverter.regeln.stelle_id IS 'Id der Stelle in der die Konvertierungsregel erstellt und angewendet werden kann.';
COMMIT;
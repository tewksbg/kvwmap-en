<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 # 
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  # 
# published by the Free Software Foundation; either version 2 of  # 
# the License, or (at your option) any later version.             # 
#                                                                 #   
# This program is distributed in the hope that it will be useful, #  
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #  
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  # 
# MA 02111-1307, USA.                                             # 
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
#############################
# Klasse PgObject #
#############################

class PgObject {
  
  function PgObject($gui, $schema, $tableName) {
    global $debug;
    $this->debug=$debug;
    $this->gui = $gui;
    $this->database = $gui->pgdatabase;
    $this->schema = $schema;
    $this->tableName = $tableName;
    $this->qualifiedTableName = $schema . '.' . $tableName;
    $this->data = array();
    $this->debug = false;
  }

  function find_by($attribute, $value) {
    $sql = "
      SELECT
        *
      FROM
        \"" . $this->schema . "\".\"" . $this->tableName . "\"
      WHERE
        \"" . $attribute . "\" = '" . $value . "'
    ";
    $this->debug('<p>find_by sql: ' . $sql);
    $query = pg_query($this->database->dbConn, $sql);
    $this->data = pg_fetch_assoc($query);
  }

  /*
  * Search for an record in the database
  * by the given where clause
  * @ return an object with this record
  */
  function find_where($where) {
    $sql = "
      SELECT
        *
      FROM
        " . $this->schema . '.' . $this->tableName . "
      WHERE
        " . $where . "
    ";
    $this->debug('<p>sql: ' . $sql);
    $query = pg_query($this->database->dbConn, $sql);
    if (pg_num_rows($query) == 0)
      $this->data = array();
    else
      $this->data = pg_fetch_assoc($query);
    return $this;
  }

  function getAttributes() {
    return array_keys($this->data);
  }

  function getValues() {
    return array_values($this->data);
  }

  function getKVP() {
    $kvp = array();
    foreach($this->data AS $key => $value) {
      $kvp[] = "\"" . $key . "\" = '" . $value . "'";
    }
    return $kvp;
  }

  function get($attribute) {
    return $this->data[$attribute];
  }

  function set($attribute, $value) {
    $this->data[$attribute] = $value;
    return $value;
  }

  function create($data) {
    if (!empty($data))
      $this->data = $data;
    $sql = "
      INSERT INTO " . $this->qualifiedTableName . " (
        " . implode(', ', $this->getAttributes()) . "
      )
      VALUES (
        '" . implode("', '", $this->getValues()) . "'
      )
      RETURNING id
    ";
    $this->debug('<p>Insert into pg table sql: ' . $sql);
    $query = pg_query($this->database->dbConn, $sql);
    $row = pg_fetch_assoc($query);
    $this->set('id', $row['id']);
    return $this->get('id');
  }

  function update() {
    $sql = "
      UPDATE
        \"" . $this->schema . "\".\"" . $this->tableName . "\"
      SET
        " . implode(', ', $this->getKVP()) . "
      WHERE
        id = " . $this->get('id') . "
    ";
    $this->debug('<p>Update in pg table sql: ' . $sql);
    $query = pg_query($this->database->dbConn, $sql);
  }

  function delete() {
    $sql = "
      DELETE
      FROM
        " . $this->qualifiedTableName . "
      WHERE
        id = " . $this->get('id') . "
    ";
    $this->debug('<p>Delete in pg table sql: ' . $sql);
    $result = pg_query($this->database->dbConn, $sql);
    return $result;
  }

  function getSQLResults($sql) {
    $query = pg_query($this->database->dbConn, $sql);
    $results = array();
    while ($rs = pg_fetch_assoc($query)) {
      $results[] = $rs
    }
    return $result;
  }

  function debug($msg) {
    if ($this->debug)
      echo $msg;
  }
}
?>
<?php

  $this->goNotExecutedInPlugins = false;

  include_once(CLASSPATH . 'PgObject.php');
  include_once(CLASSPATH . 'data_import_export.php');
  include(PLUGINS . 'xplankonverter/model/konvertierung.php');
  include(PLUGINS . 'xplankonverter/model/shapefiles.php');

  switch($this->go){

    case 'say_hallo' : {
      include(PLUGINS . 'xplankonverter/model/xplan.php');

      // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
      //$this->pgdatabase->dbConn);
      $this->xplan = new xplan($this->pgdatabase);
      
      // Einbindung des Views
      $this->main=PLUGINS . 'xplankonverter/view/xplan.php';

      $this->output();

    } break;
    
    case 'build_gml' : {
      include(PLUGINS . 'xplankonverter/model/build_gml.php');
      
      // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
      //$this->pgdatabase->dbConn);
      $this->gml_builder = new gml_builder($this->pgdatabase);
      
      // Einbindung des Views
      $this->main=PLUGINS . 'xplankonverter/view/build_gml.php';
      
      $this->output();
      
    } break;

    case 'convert' : {
      include(PLUGINS . 'xplankonverter/model/converter.php');
      include(PLUGINS . 'xplankonverter/model/constants.php');

      // Die Verbindung zur Datenbank kvwmapsp ist verfügbar in
      //$this->pgdatabase->dbConn);
      $this->converter = new Converter($this->pgdatabase, PG_CONNECTION);
      
      // Einbindung des Views
      $this->main = PLUGINS . 'xplankonverter/view/convert.php';
      
      $this->initialData = array(
        'config' => array(
          'active' => 'step1',
          'step1' => array(
              'disabled' => false
          ),
          'step2' => array(
              'disabled' => true
          ),
          'step3' => array(
              'disabled' => true
          ),
          'step4' => array(
              'disabled' => true
          )
        )
      );
      
      $this->initialData['step1']['konvertierungen'] = $this->converter->getConversions();
      
      $this->output();
      
    } break;

    case 'xplankonverter_konvertierungen_index' : {
      $this->main = '../../plugins/xplankonverter/view/konvertierungen.php';
      $this->output();
    } break;

    case 'xplankonverter_shapefiles_index' : {
      if ($this->formvars['konvertierung_id'] == '') {
        $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
        $this->main = 'Hinweis.php';
      }
      else {
        $this->konvertierung = new Konvertierung($this->pgdatabase, 'xplankonverter', 'konvertierungen');
        $this->konvertierung->find_by_id($this->formvars['konvertierung_id']);
        if (isInStelleAllowed($this->Stelle->id, $this->konvertierung->get('stelle_id'))) {
          if (isset($_FILES['shape_files']) and $_FILES['shape_files']['name'][0] != '') {
            $upload_path = XPLANKONVERTER_SHAPE_PATH . $this->formvars['konvertierung_id'] . '/';

            # create upload dir if not exists
            if (!is_dir($upload_path)) {
              $old = umask(0);
              mkdir($upload_path, 0770, true);
              umask($old);
            }

            # unzip and copy files to upload folder
            $uploaded_files = xplankonverter_unzip_and_copy($_FILES['shape_files'], $upload_path);

            # load data to database, register shape files and create layer
            foreach($uploaded_files AS $uploaded_file) {
              if ($uploaded_file['extension'] == 'dbf' and $uploaded_file['state'] != 'ignoriert') {
                $shapeFile = new ShapeFile($this->database, $this->pgdatabase, $this->Stelle, $this->user, 'xplankonverter', 'shapefiles', 25832);
                $shapeFile->create(
                  array(
                    'filename' => $uploaded_file['filename'],
                    'konvertierung_id' => $this->konvertierung->get('id'),
                    'stelle_id' => $this->Stelle->id
                  )
                );
              }
            }
          } # end of upload files
          $this->main = '../../plugins/xplankonverter/view/shapefiles.php';
        }
      }
      $this->output();
    } break;

    case 'xplankonverter_shapefiles_delete' : {
      if ($this->formvars['shapefile_id'] == '') {
        $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher ein Shape Datei ausgewählt wurde.';
        $this->main = 'Hinweis.php';
      }
      else {
        $shapefile = new Shapefile($this->database, $this->pgdatabase, $this->Stelle, $this->user, 'xplankonverter', 'shapefiles', 25832);
        $shapefile->find_by_id($this->formvars['shapefile_id']);
        if (isInStelleAllowed($this->Stelle->id, $shapefile->get('stelle_id'))) {
          $shapefile->deleteShape();
          $this->main = '../../plugins/xplankonverter/view/shapefiles.php';
        }
      }
      $this->output();
    } break;

    case 'home' : {
      // Einbindung des Views
      $this->main=PLUGINS . 'xplankonverter/view/home.php';

      $this->output();

    } break;

    default : {
      $this->goNotExecutedInPlugins = true;    // in diesem Plugin wurde go nicht ausgeführt
    }
  }
function isInStelleAllowed($guiStelleId, $requestStelleId) {
  if ($guiStelleId == $requestStelleId)
    return true;
  else {
    echo '<br>(Diese Aktion kann nur von der Stelle ' . $this->Stelle->Bezeichnung . ' aus aufgerufen werden';
    return false;
  }
}

/*
* extract zip files if necessary and copy files to upload folder
*/
function xplankonverter_unzip_and_copy($shape_files, $dest_dir) {
  $uploaded_files = array();
  # extract zip files if necessary and copy files to upload folder
  foreach($shape_files['name'] AS $i => $shape_file_name) {
    $path_parts = pathinfo($shape_file_name);

    if (strtolower($path_parts['extension']) == 'zip') {
      # extract files if the extension is zip
      $temp_files = extract_uploaded_zip_file($shape_files['tmp_name'][$i]);
    }
    else {
      # set data from single file
      $path_parts = pathinfo($shape_file_name);
      $temp_files = array(
        array(
          'basename' => $path_parts['basename'],
          'filename' => $path_parts['filename'],
          'extension' => strtolower($path_parts['extension']),
          'tmp_name' => $shape_files['tmp_name'][$i],
          'unziped' => false
        )
      );
    }

    # copy temp shape files to destination
    foreach($temp_files AS $temp_file) {
      $uploaded_files[] = xplankonverter_copy_uploaded_shp_file($temp_file, $dest_dir);
    }
  }
  return $uploaded_files;
}

/*
* Packt die angegebenen Zip-Datei im sys_temp_dir Verzeichnis aus
* und gibt die ausgepackten Dateien in der Struktur von
* hochgeladenen Dateien aus
*/
function extract_uploaded_zip_file($zip_file) {
  $sys_temp_dir = sys_get_temp_dir();
  $extracted_files = array_map(
    function($extracted_file) {
      $path_parts = pathinfo($extracted_file);
      return array(
        'basename' => $path_parts['basename'],
        'filename' => $path_parts['filename'],
        'extension' => $path_parts['extension'],
        'tmp_name' => sys_get_temp_dir() . '/' . $extracted_file,
        'unziped' => true
      );
    },
    unzip($zip_file, false, false, true)
  );
  return $extracted_files;
}

/*
* Copy files from sys_temp_dir to upload directory and mark if
* files are new, override older or are ignored
*/
function xplankonverter_copy_uploaded_shp_file($file, $dest_dir) {
  $messages = array();
  if (in_array($file['extension'], array('dbf', 'shx', 'shp'))) {
    if (file_exists($dest_dir . $file['basename'])) {
      $file['state'] = 'geändert';
    }
    else {
      $file['state'] = 'neu';
    }
    if ($file['unziped']) {
      rename($file['tmp_name'], $dest_dir . $file['basename']);
    }
    else {
      move_uploaded_file($file['tmp_name'], $dest_dir . $file['basename']);
    }
  }
  else {
    if ($file['unziped'])
      unlink($file['tmp_name']);
    $file['state'] = 'ignoriert';
  }
  return $file;
}
?>
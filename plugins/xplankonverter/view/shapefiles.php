<?php include('header.php'); ?>

<h2>Hochgeladene Dateien</h2>
<br>
<script language="javascript" type="text/javascript">
  function shapeFileFunctionsFormatter(value, row) {
    output = '<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=10&operator_shapefile_id==&value_shapefile_id=' + value + '"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
    output += '<a href="index.php?go=xplankonverter_shapefiles_delete&konvertierung_id=' + row.konvertierung_id + '&shapefile_id=' + row.shapefile_id + '&layer_id=' + row.layer_id + '"><i class="fa fa-trash"></i></a>&nbsp;';
    return output;
  }
</script>
<table
  id="shapefiles_table"
  data-toggle="table"
  data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo XPLANKONVERTER_SHAPEFILES_LAYER_ID; ?>&mime_type=formatter&format=json"
  data-height="100%"
  data-click-to-select="false"
  data-sort-name="filename"
  data-sort-order="asc"
  data-search="false"
  data-show-refresh="false"
  data-show-toggle="false"
  data-show-columns="true"
  data-query-params="queryParams"
  data-pagination="true"
  data-page-size="25"
  data-show-export="false"
  data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
>
  <thead>
    <tr>
      <th
        data-field="shapefiles_oid"
        data-visible="false"
        data-switchable="true"
        class="text-right"
      >DB OID</th>
      <th
        data-field="shapefile_id"
        data-visible="false"
        data-switchable="true"
        class="text-right"
      >Shapefile ID</th>
      <th
        data-field="filename"
        data-sortable="true"
        data-visible="true"
      >Shapedatei Dateiname</th>
      <th
        data-field="shapefile_id"
        data-visible="true"
        data-formatter="shapeFileFunctionsFormatter"
        data-switchable="false"
        class="text-right"
      ></th>
    </tr>
  </thead>
</table>
<div style="clear:both"></div>
<form action="index.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="go" value="xplankonverter_shapefiles_index">
  <input type="hidden" name="konvertierung_id" value="<?php echo $this->formvars['konvertierung_id']; ?>">
  <div class="file-upload">
    <input type="file" name="shape_files[]" multiple>
    <select name="epsg_code">
      <option value="25832" selected>25832</option>
      <option value="25833">25833</option>
      <option value="35833">35833</option>
      <option value="31467">31467</option>
      <option value="31468">31468</option>
      <option value="31469">31469</option>
    </select>
    <input type="submit" value="Upload">
  </div>
</form>
<p>
<?php
  if (!empty($uploaded_files)) {
    echo '<p><b>Hochgeladene Dateien</b>';
    array_map(
      function($uploaded_file) {
        echo '<br>Datei ' . $uploaded_file['state'] . ': ' . $uploaded_file['basename'];
      },
      $uploaded_files
    );
  }
?>
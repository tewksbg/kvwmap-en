<?
 
	include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 
 	include(SNIPPETS.'generic_functions.php'); 
	
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];
	if($this->currentform == 'document.GUI2')$size = 40;
	else $size = 61;
	$linksize = $this->user->rolle->fontsize_gle - 1;
	$select_width = ''; 
	if($layer['alias'] != '' AND $this->Stelle->useLayerAliases){
		$layer['Name'] = $layer['alias'];
	}
?>
<div id="layer">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="95%" align="center"><h2>&nbsp;&nbsp;<? echo $layer['Name']; ?></h2></td>
		<td align="right">			
			<a href="javascript:scrollbottom();"><img title="nach unten" src="<? echo GRAPHICSPATH; ?>pfeil.gif" width="11" height="11" border="0"></a>&nbsp;
		</td>		
	</tr>
</table>
<?
	$doit = false;
  $anzObj = count($layer['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($this->new_entry == true){
  	$anzObj = 1;
  	$doit = true;
  }
  if($doit == true){
?>
<table border="0" cellspacing="0" cellpadding="2">
<?
	for ($k=0;$k<$anzObj;$k++) {
		$datapart = '';
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].'|';
?>
	<tr>
	  <td>
			<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
	    <div id="datensatz" 
			<? if($this->new_entry != true AND $this->user->rolle->querymode == 1){ ?>
			onmouseenter="ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=tooltip_query&querylayer_id=<? echo $layer['Layer_ID']; ?>&oid=<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', new Array(top.document.GUI.result, ''), new Array('setvalue', 'execute_function'));"
			<? } ?>
			>
	    <input type="hidden" value="" name="changed_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
	    <table id="dstable" class="tgle" <? if($attributes['group'][0] != ''){echo 'border="0" cellpadding="6" cellspacing="0"';}else{echo 'border="1"';} ?>>
				<? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
	      <thead class="gle">
	        <th colspan="2" style="background-color:<? echo BG_GLEHEADER; ?>;">			  
			  <table width="100%">
			    <tr>
			      <td>
			        <input id="<? echo $layer['Layer_ID'].'_'.$k; ?>" type="checkbox" name="check;<? echo $attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>">&nbsp;
			        <span style="color:<? echo TXT_GLEHEADER; ?>;"><? echo $strSelectThisDataset; ?></span>
			      </td>
			      <td align="right">
							<table cellspacing="0" cellpadding="0">
								<tr>
			      	<? if($layer['privileg'] > '0'){ ?>
									<td style="padding: 0 0 0 10;"><a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);use_for_new_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>)" title="<? echo $strUseForNewDataset; ?>"><div class="emboss use_for_dataset"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
			        <? } ?>
							<? if($layer['export_privileg'] == '1'){ ?>
									<td style="padding: 0 0 0 10;"><a id="uko_<? echo $layer['Layer_ID'].'_'.$k; ?>" style="visibility:hidden" href="" title="<? echo $strUKOExportThis; ?>"><div class="emboss datensatz_exportieren_uko"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<td style="padding: 0 0 0 10;"><a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);csv_export(<? echo $layer['Layer_ID']; ?>);" title="<? echo $strCSVExportThis; ?>"><div class="emboss datensatz_exportieren_csv"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
			        <? } if($layer['privileg'] == '2'){ ?>
									<td style="padding: 0 0 0 10;"><a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);delete_datasets(<?php echo $layer['Layer_ID']; ?>);" title="<? echo $strDeleteThisDataset; ?>"><div class="emboss datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
			        <? } ?>
								</tr>
							</table>
			      </td>
			    </tr>
			  </table>
		    </th>
		  </thead>
			<? } ?>
          <tbody <? if($attributes['group'][0] == '')echo 'class="gle"'; ?>>
<?		$trans_oid = explode('|', $layer['shape'][$k]['lock']);
			if($layer['shape'][$k]['lock'] == 'bereits �bertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$layer['maintable'].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits �bertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($attributes['name']); $j++){
				if($layer['shape'][$k][$attributes['name'][$j]] == ''){
					$layer['shape'][$k][$attributes['name'][$j]] = $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j]];
				}
				if($this->new_entry == true AND $attributes['default'][$j] != '' AND $layer['shape'][$k][$attributes['name'][$j]] == ''){		# Default-Werte setzen
					$layer['shape'][$k][$attributes['name'][$j]] = $attributes['default'][$j];
				}
				if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					$attributes['form_element_type'][$j] .= '_not_saveable';
				}
				
				if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Tabelle beginnen
					$explosion = explode(';', $attributes['group'][$j]);
					if($explosion[1] != '')$collapsed = true;else $collapsed = false;
					$groupname = $explosion[0];
					$datapart .= '<tr>
									<td colspan="2" width="100%">
										<table width="100%" id="colgroup'.$layer['Layer_ID'].'_'.$j.'_'.$k.'" class="tgle" '; if(!$collapsed)$datapart .= 'style="display:none"'; $datapart .= ' border="2"><tbody width="100%" class="gle">
											<tr>
												<td width="100%" bgcolor="'.BG_GLEATTRIBUTE.'" colspan="2">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'none\';"><img border="0" src="'.GRAPHICSPATH.'/plus.gif"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span></td>
											</tr>
										</table>
										<table width="100%" class="tgle" id="group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'" '; if($collapsed)$datapart .= 'style="display:none"'; $datapart .= 'border="2"><tbody class="gle">
											<tr>
												<td bgcolor="'.BG_GLEATTRIBUTE.'" colspan="2">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'none\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'\';"><img border="0" src="'.GRAPHICSPATH.'/minus.gif"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span></td>
											</tr>';
				}
				
				if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $attributes['name'][$j] != 'lock'){
					if($attributes['type'][$j] != 'geometry'){
							$datapart .= '<tr><td ';
							if($attributes['group'][0] != '')$datapart .= 'width="10%"';
							$datapart .= ' valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
							if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
								$this->editable = 'true';
							}
							if($attributes['alias'][$j] == ''){
								$attributes['alias'][$j] = $attributes['name'][$j];
							}
							$datapart .= '<table ';
							if($attributes['group'][0] != '')$datapart .= 'width="200px"';
							else $datapart .= 'width="100%";';
							$datapart .= '><tr style="border: none"><td>';
							if(!in_array($attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink'))){
								$datapart .= '<a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer['Layer_ID'].');">
							 					'.$attributes['alias'][$j].'</a>';
							}
							else{
								$datapart .= '<span style="font-size: '.$this->user->rolle->fontsize_gle.'px; color:#222222;">'.$attributes['alias'][$j].'</span>';
							}
							if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
								$datapart .= '<span title="Eingabe erforderlich">*</span>';
							}
							if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time'){
							  $datapart .= '<td align="right"><a href="javascript:void(0);" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
							}
							if($attributes['type'][$j] == 'date'){
							  $datapart .= '<td align="right"><a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
							  if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
							  	$datapart .= 'onclick="new CalendarJS().init(\''.$attributes['name'][$j].'_'.$k.'\');"';
							  }
							  $datapart .= '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div></td>';
							}
							$datapart .= '</td></tr></table>';
							$datapart .= '</td><td>';
			  			if($attributes['constraints'][$j] != '' AND $attributes['constraints'][$j] != 'PRIMARY KEY'){
			  				if($attributes['privileg'][$j] == '0' OR $lock[$k]){
			  					$size1 = 1.3*strlen($layer['shape'][$k][$attributes['name'][$j]]);
									$datapart .= '<input readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$size1.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
								}
								else{
			  					$datapart .= '<select onchange="set_changed_flag(currentform.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" title="'.$attributes['alias'][$j].'"  style="font-size: '.$this->user->rolle->fontsize_gle.'px" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
									for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
										$datapart .= '<option ';
										if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
											$datapart .= 'selected ';
										}
										$datapart .= 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
									}
									$datapart .= '</select>';
			  				}
			  			}
			  			else{
								include(SNIPPETS.'generic_formelements.php');
			  			}
			  			$datapart .= '
									</td>
								</tr>
							';

			  			
			  			
							if($attributes['privileg'][$j] >= '0'){
								$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
							}
			  		}
			  		else {
			  			$columnname = $attributes['name'][$j];
			  			$tablename = $attributes['table_name'][$attributes['name'][$j]];
			  			$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
			  			$dimension = $attributes['dimension'][$j];
			  			$privileg = $attributes['privileg'][$j];
			  			$nullable = $attributes['nullable'][$j];
			  			$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';Geometrie;'.$attributes['nullable'][$j].'|';
			  		}
					}
					if($attributes['group'][$j] != $attributes['group'][$j+1]){		# wenn die n�chste Gruppe anders ist, Tabelle schliessen
						$datapart .= '</table></td></tr>';
					}
				}
				
				if($this->new_entry != true)echo $datapart;
				
				if(($columnname != '' OR $layer['shape'][$k]['geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					if($attributes['group'][0] != ''){ ?>
						<tr><td colspan="2"><table width="100%" class="tgle" border="2" cellpadding="0" cellspacing="0"><tbody class="gle">
					<? } ?>
				 
					<tr>
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;" valign="middle">
<?						
							if($layer['shape'][$k][$attributes['the_geom']]){
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
									if($layer['export_privileg'] == '1'){ ?>
			    					<script type="text/javascript">
			    						document.getElementById('uko_<? echo $layer['Layer_ID'].'_'.$k; ?>').href = 'index.php?go=UKO_Export&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>';
			    						document.getElementById('uko_<? echo $layer['Layer_ID'].'_'.$k; ?>').style.visibility = 'visible';
			    					</script>
									<? } ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="Geometrie bearbeiten" href="index.php?go=PolygonEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} ?>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly');"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt und Objekt hervorheben" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt und andere Objekte ausblenden" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=true');"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
										</tr>
									</table>
<?							}
								elseif($geomtype == 'POINT'){ ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="Geometrie bearbeiten" href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} ?>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly')"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt und Objekt hervorheben" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false')"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><!--a title="Zoom auf Objekt und andere Objekte ausblenden" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? #echo $dimension; ?>&oid=<?php #echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? #echo $tablename; ?>&layer_columnname=<? #echo $columnname; ?>&layer_id=<? #echo $layer['Layer_ID'];?>&selektieren=true')"><div class="emboss zoom_select"><img src="<? #echo GRAPHICSPATH.'leer.gif'; ?>"></div></a--></td>
										</tr>
									</table>
<?	    				}
								elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') { ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="Geometrie bearbeiten" href="index.php?go=LineEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} ?>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly')"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt und Objekt hervorheben" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false')"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="Zoom auf Objekt und andere Objekte ausblenden" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=true')"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
										</tr>
									</table>
<?
			    				}
						}
						elseif($layer['shape'][$k]['geom']){		# bei WFS-Layern
?>
							&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:zoom2wkt('<? echo $layer['shape'][$k]['geom']; ?>', '<? echo $layer['epsg_code']; ?>');"><? echo $strMapZoom; ?></a>&nbsp;&nbsp;&nbsp;
<?															
						}							
?>								
							</td>
			    </tr>
			    
			    <? if($attributes['group'][0] != ''){ ?>
								</table></td></tr>
					<? }		    
	}

				
				if($this->new_entry == true){
					if($privileg == 1){
						if(!$this->user->rolle->geom_edit_first)echo $datapart;
						if($nullable === '0'){ ?>
							<script type="text/javascript">
    						geom_not_null = true;
    					</script>
<?					}
						$this->titel=$strTitleGeometryEditor;
						if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/PolygonEditor.php');
							echo'
								</td>
							</tr>';
						} elseif($geomtype == 'POINT') {
							$this->formvars['dimension'] = $dimension;
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/PointEditor.php');
							echo'
								</td>
							</tr>';
						} elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/LineEditor.php');
							echo'
								</td>
							</tr>';
						}
						if($this->user->rolle->geom_edit_first)echo $datapart;
					}
					else echo $datapart;
				}
 ?>
			</tbody>
			</table>
			</div>
			<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
		</td>
	</tr>
<?
	}
	if($this->formvars['printversion'] == ''){
?>
	<tr>
		<td colspan="2"align="left">
		<? if($this->new_entry != true){ ?>
			<table width="100%" border="0" cellspacing="4" cellpadding="0">
				<tr>
					<td colspan="2">
						<i><? echo $layer['Name'] ?></i>:&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:selectall(<? echo $layer['Layer_ID']; ?>);">
						<? if ($layer['count'] > MAXQUERYROWS) {
						    echo $strSelectAllShown;
						   } else {
						    echo $strSelectAll;
						   } ?>
						</a>
					</td>
				</tr>
				<tr>
					<? if($layer['export_privileg'] == '1'){ ?>
					<td style="padding: 5 0 0 0;">
						<select id="all_<? echo $layer['Layer_ID']; ?>" name="all_<? echo $layer['Layer_ID']; ?>">
							<option value=""><? echo $strSelectedDatasets.':'; ?></option>
							<option value="true"><? echo $strAllDatasets.':'; ?><? if ($layer['count'] > MAXQUERYROWS){	echo "&nbsp;(".$layer['count'].")"; } ?></option>
						</select>
					</td>					
					<? }else{ ?>
					<td style="padding: 5 0 0 0;"><? echo $strSelectedDatasets.':'; ?></td>
					<? } ?>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0">
							<tr>
					<? if($layer['privileg'] == '2'){ ?>
								<td style="padding: 5 10 0 0;"><a title="l�schen" href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);"><div class="emboss datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></td>
					<?} if($layer['export_privileg'] == '1'){ ?>
								<td style="padding: 5 10 0 0;"><a id="csv_link" title="in CSV-Datei exportieren" href="javascript:csv_export(<?php echo $layer['Layer_ID']; ?>);"><div class="emboss datensatz_exportieren_csv"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<td style="padding: 5 10 0 0;"><a id="shape_link" title="in Shape-Datei exportieren" href="javascript:shape_export(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);"><div class="emboss datensatz_exportieren_shp"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
					<? } if($layer['layouts']){ ?>
								<td style="padding: 5 10 0 0;"><a id="print_link" title="drucken" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="emboss drucken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
					<? } ?>
					<? if($privileg != ''){ ?>
								<td style="padding: 5 10 0 0;"><a title="in Karte anzeigen" href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $tablename; ?>', '<? echo $columnname; ?>');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<td style="padding: 5 0 0 5;"><select style="width: 130px" name="klass_<?php echo $layer['Layer_ID']; ?>">
										<option value="">klassifiziert nach:</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
					<?}?>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="display:none">
					<td height="23" colspan="3">
						&nbsp;&nbsp;&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:showcharts(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCreateChart; ?></a>
					</td>
				</tr>
				<tr id="charts_<?php echo $layer['Layer_ID']; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $layer['Layer_ID']; ?>" onchange="change_charttype(<?php echo $layer['Layer_ID']; ?>);">
										<option value="bar">Balkendiagramm</option>
										<option value="mirrorbar">doppeltes Balkendiagramm</option>
										<option value="circle">Kreisdiagramm</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Beschriftung:
								</td>
								<td>
									<select style="width:133px" id="" name="chartlabel_<?php echo $layer['Layer_ID']; ?>" >
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Wert:
								</td>
								<td>
									<select style="width:133px" name="chartvalue_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte W�hlen ---</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="split_<?php echo $layer['Layer_ID']; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte W�hlen ---</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<?} ?>
		</td>
	</tr>
	<? } ?>
</table>


<input type="hidden" name="wkt" value=""><!-- f�r den WFS Kartenzoom -->
<input type="hidden" name="epsg" value=""><!-- f�r den WFS Kartenzoom -->
<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['Layer_ID']; ?>" id="orderby<? echo $layer['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$layer['Layer_ID']]; ?>">

<?
  }
  else {
?>
<table border="0" cellspacing="10" cellpadding="2">
  <tr>
	<td>
      <span style="font-size:12px; color:#FF0000;"><? echo $strNoMatch; ?></span>
	</td>
  </tr>
</table>

<?
  }
?>
</div>
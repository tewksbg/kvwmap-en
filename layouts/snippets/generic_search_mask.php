<table width="100%" align="center" border="0" cellspacing="0" cellpadding="3" id="searchmasks">
<?    
			if($searchmask_number > 0){						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
				$prefix = $searchmask_number.'_'; ?>
				<tr>
					<td align="center" width="100%" colspan="5">
						<select name="boolean_operator_<? echo $searchmask_number; ?>">
							<option value="AND" <? if($this->formvars['searchmask_operator'][$searchmask_number] == 'AND')echo 'selected'; ?>>und</option>
							<option value="OR" <? if($this->formvars['searchmask_operator'][$searchmask_number] == 'OR')echo 'selected'; ?>>oder</option>
						</select>
					</td>
				</tr>
				<?
			}
			else{
?>
				<tr>
					<td width="150px"><span class="fett">Attribut</span></td>
					<td>&nbsp;&nbsp;</td>
					<td width="100px" align="center"><span class="fett">Operator</span></td>
					<td>&nbsp;&nbsp;</td>
					<td width="150px" align="left"><span class="fett">&nbsp;&nbsp;Wert</span></td>
				</tr>

<?		}
			for($i = 0; $i < count($this->attributes['name']); $i++){
        if($this->attributes['type'][$i] != 'geometry'){
				
					if($this->attributes['group'][$i] != $this->attributes['group'][$i-1]){		# wenn die vorige Gruppe anders ist, Tabelle beginnen
						$explosion = explode(';', $this->attributes['group'][$i]);
						if($explosion[1] != '')$collapsed = true;else $collapsed = false;
						$groupname = $explosion[0];
						echo '<tr>
										<td colspan="5" width="100%">
											<table cellpadding="3" cellspacing="0" width="100%" id="colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'"  style="'; if(!$collapsed)echo 'display:none;'; echo ' border:1px solid grey">
												<tr>
													<td width="100%" bgcolor="'.BG_GLEATTRIBUTE.'" colspan="2">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'none\';"><img border="0" src="'.GRAPHICSPATH.'/plus.gif"></a>&nbsp;<span class="fett">'.$groupname.'</span></td>
												</tr>
											</table>
											<table cellpadding="3" cellspacing="0" width="100%" id="group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'" style="'; if($collapsed)echo 'display:none;'; echo 'border:1px solid grey">
												<tr>
													<td style="border-bottom:1px dotted grey" bgcolor="'.BG_GLEATTRIBUTE.'" colspan="5">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'none\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'\';"><img border="0" src="'.GRAPHICSPATH.'/minus.gif"></a>&nbsp;<span class="fett">'.$groupname.'</span></td>
												</tr>';
				}
				
          ?><tr>
            <td width="40%"><?
              if($this->attributes['alias'][$i] != ''){
                echo $this->attributes['alias'][$i];
              }
              else{
                echo $this->attributes['name'][$i];
              }
              if(strpos($this->attributes['type'][$i], 'time') !== false OR $this->attributes['type'][$i] == 'date'){
              ?>
                <img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0">
              <?
              }
          ?></td>
            <td>&nbsp;&nbsp;</td>
            <td width="100px">
              <select  style="width:75px" <? if(count($this->attributes['enum_value'][$i]) == 0){ ?>onchange="operatorchange('<? echo $this->attributes['name'][$i]; ?>', <? echo $searchmask_number; ?>);" id="<? echo $prefix; ?>operator_<? echo $this->attributes['name'][$i]; ?>" <? } ?> name="<? echo $prefix; ?>operator_<? echo $this->attributes['name'][$i]; ?>">
                <option title="Der Suchbegriff muss exakt so in der Datenbank stehen" value="=" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == '='){ echo 'selected';} ?> >=</option>
                <option title="Der Suchbegriff kommt so NICHT in der Datenbank vor" value="!=" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == '!='){ echo 'selected';} ?> >!=</option>
                <option title="'kleiner als': nur bei Zahlen verwenden!" value="<" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == '<'){ echo 'selected';} ?> ><</option>
                <option title="'größer als': nur bei Zahlen verwenden!" value=">" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == '>'){ echo 'selected';} ?> >></option>
                <option title="Fügen Sie das %-Zeichen vor und/oder nach dem Suchbegriff für beliebige Zeichen ein" value="LIKE" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == 'LIKE'){ echo 'selected';} ?> >ähnlich</option>
                <option title="Fügen Sie das %-Zeichen vor und/oder nach dem Suchbegriff für beliebige Zeichen ein" value="NOT LIKE" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == 'NOT LIKE'){ echo 'selected';} ?> >nicht ähnlich</option>
                <option title="Sucht nach Datensätzen ohne Eintrag in diesem Attribut" value="IS NULL" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == 'IS NULL'){ echo 'selected';} ?> >ist leer</option>
                <option title="Sucht nach Datensätzen mit beliebigem Eintrag in diesem Attribut" value="IS NOT NULL" <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == 'IS NOT NULL'){ echo 'selected';} ?> >ist nicht leer</option>
                <option title="Sucht nach mehreren exakten Suchbegriffen, zur Trennung '|' verwenden:  [Alt Gr] + [<]" value="IN" <? if (count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == 'IN'){ echo 'selected';} ?> >befindet sich in</option>
                <option title="Sucht zwischen zwei Zahlwerten" value="between" <? if (count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] == 'between'){ echo 'selected';} ?> >zwischen</option>
              </select>
            </td>
            <td>&nbsp;&nbsp;</td>
            <td align="left" width="40%"><?
            	switch ($this->attributes['form_element_type'][$i]) {
            		case 'Auswahlfeld' : {
                  ?><select  
                  <?
                  	if($this->attributes['req_by'][$i] != ''){
											echo 'onchange="update_require_attribute(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['selected_layer_id'].', this.value, '.$searchmask_number.');" ';
										}
									?> 
                  	id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
                      <option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n";
                      if(is_array($this->attributes['enum_value'][$i][0])){
                      	$this->attributes['enum_value'][$i] = $this->attributes['enum_value'][$i][0];
                      	$this->attributes['enum_output'][$i] = $this->attributes['enum_output'][$i][0];
                      }
                    for($o = 0; $o < count($this->attributes['enum_value'][$i]); $o++){
                      ?>
                      <option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == $this->attributes['enum_value'][$i][$o]){ echo 'selected';} ?> value="<? echo $this->attributes['enum_value'][$i][$o]; ?>"><? echo $this->attributes['enum_output'][$i][$o]; ?></option><? echo "\n";
                    } ?>
                    </select>
                    <input size="9" id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="hidden" value="<? echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
                    <?
                }break;
                
                case 'Checkbox' : {
                  ?><select  id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
                      <option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n"; ?>
                      <option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 't'){ echo 'selected';} ?> value="t">ja</option><? echo "\n"; ?>
                      <option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 'f'){ echo 'selected';} ?> value="f">nein</option><? echo "\n"; ?>
                    </select>
                    <input size="9" id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="hidden" value="<? echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
                    <?
                }break;
                
		default : { 
                  ?>
                  <input size="<? if($this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]] != ''){echo '9';}else{echo '24';} ?>" id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" type="text" value="<? echo $this->formvars[$prefix.'value_'.$this->attributes['name'][$i]]; ?>" onkeyup="checknumbers(this, '<? echo $this->attributes['type'][$i]; ?>', '<? echo $this->attributes['length'][$i]; ?>', '<? echo $this->attributes['decimal_length'][$i]; ?>');">
                  <input size="9" id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="<? if($this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]] != ''){echo 'text';}else{echo 'hidden';} ?>" value="<? echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
                  <?
               }
      				}
           ?></td>
          </tr><?					
        }
				if($this->attributes['group'][$i] != $this->attributes['group'][$i+1]){		# wenn die nächste Gruppe anders ist, Tabelle schliessen
					echo '</table></td></tr>';
				}
      }
?>
</table>
<?php
/**
*	This page form the demo.js which is the config scripts of front-end javascript code.
*	
*	@version 0.1
*	@author M.Karminski
*
*
*
*/


/**
*	This function print config scripts of editable grid.
*
*	@param string $editableGridUpdatePageName
*  	@param string $editableGridLoaddataPageName
*	@param string $editableGridDatabaseTableName
*	@return boolean false
*/


function print_conf_scripts_for_editable_grid($editableGridUpdatePageName, $editableGridLoaddataPageName, $editableGridDatabaseTableName){
	print <<<SCRIPTS
		<script type="text/javascript">
			/**
			 *  highlightRow and highlight are used to show a visual feedback. If the row has been successfully modified, it will be highlighted in green. Otherwise, in red
			 */
			function highlightRow(rowId, bgColor, after)
			{
				var rowSelector = $("#" + rowId);
				rowSelector.css("background-color", bgColor);
				rowSelector.fadeTo("normal", 0.5, function() { 
					rowSelector.fadeTo("fast", 1, function() { 
						rowSelector.css("background-color", '');
					});
				});
			}
			
			function highlight(div_id, style) {
				highlightRow(div_id, style == "error" ? "#e5afaf" : style == "warning" ? "#ffcc00" : "#8dc70a");
			}
			        
			/**
			   updateCellValue calls the PHP script that will update the database. 
			 */
			function updateCellValue(editableGrid, rowIndex, columnIndex, oldValue, newValue, row, onResponse)
			{      
				$.ajax({
					url: '$editableGridUpdatePageName',
					type: 'POST',
					dataType: "html",
					data: {
						tablename : editableGrid.name,
						id: editableGrid.getRowId(rowIndex), 
						newvalue: editableGrid.getColumnType(columnIndex) == "boolean" ? (newValue ? 1 : 0) : newValue, 
						colname: editableGrid.getColumnName(columnIndex),
						coltype: editableGrid.getColumnType(columnIndex)			
					},
					success: function (response) 
					{ 
						// reset old value if failed then highlight row
						var success = onResponse ? onResponse(response) : (response == "ok" || !isNaN(parseInt(response))); // by default, a sucessfull reponse can be "ok" or a database id 
						if (!success) editableGrid.setValueAt(rowIndex, columnIndex, oldValue);
					    highlight(row.id, success ? "ok" : "error"); 
					},
					error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure" + errortext); },
					async: true
				});
			   
			}
			   
			
			
			function DatabaseGrid() 
			{ 
				this.editableGrid = new EditableGrid("$editableGridDatabaseTableName", {
					enableSort: true,
			   	    tableLoaded: function() { datagrid.initializeGrid(this); },
					modelChanged: function(rowIndex, columnIndex, oldValue, newValue, row) {
			   	    	updateCellValue(this, rowIndex, columnIndex, oldValue, newValue, row);
			       	}
			 	});
				this.fetchGrid(); 
				
			}
			
			DatabaseGrid.prototype.fetchGrid = function()  {
				// call a PHP script to get the data
				this.editableGrid.loadXML("$editableGridLoaddataPageName");
			};
			
			DatabaseGrid.prototype.initializeGrid = function(grid) {
				grid.renderGrid("tablecontent", "testgrid");
			};     
		</script>			
SCRIPTS;
}

?>
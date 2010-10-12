<table width='100%' cellspacing="0" cellpadding="0" id="questiontable" border="0">
	<?php foreach ($questions as $k=>$q){ ?>
		<tr valign="top" id='<?php echo $k;?>'>
			<td class="draggableHandle" style="background-image:url(images/drag.png); background-position: top center; background-repeat:no-repeat;width:25px;"></td>
			<td><?php echo $q;?></td>
			<td style="width:20px;"><img src="/ui/images/icons/minus.png" alt="delete" class="point" onClick="deleteItem('questions', <?php echo substr($k, 2);?>, 'q_<?php echo substr($k, 2);?>', true);" /></td>
		</tr>
	<?php } ?>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$("#questiontable").tableDnD({
		onDrop: function(table, row) {
			for (var i=0; i < table.tBodies[0].rows.length; i++){
				table.tBodies[0].rows[i].style.opacity = 1.0;
			}
			var info = "controller=questions&action=sort&"+$.tableDnD.serialize();
			$.ajax({
				data: info,
				success: function(data, textStatus){
					if (data['success']){
						$("#"+row.id).show("highlight", 2000);
					} else {
						addErrorBox($("#addquestionform"), "Error arranging question", data.errors);
					}
				}
			});
	    },
		onDragStart: function(table, row) {
			for (var i=0; i < table.tBodies[0].rows.length; i++){
				if (row.id == table.tBodies[0].rows[i].id) continue;
				table.tBodies[0].rows[i].style.opacity = .3;
			}
		}
	});
});
</script>  
<div class="savebutton">
<?php Partial::Render_Partial("iconbutton", array("id" => "saveeval", "linked" => "/evaluations", "icon" => "save", "text" => "Save Evaluation")); ?>    
</div>
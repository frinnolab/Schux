

<div class="btn bg-black btn-xs sms_alltag s-tag" id="[name]">[name]</div>
<div class="btn bg-black btn-xs sms_alltag s-tag" id="[class]">[class]</div>

<textarea class="txtDropTarget"></textarea>
<textarea class="txtDropTarget"></textarea>




<script type="text/javascript">
  	
	$('.txtDropTarget').jqte();


	$(".sms_alltag").draggable({
		helper : 'clone'
	});

	$(".jqte_editor").droppable({
		drop : function(event, ui) {
			var dragValue = $(ui.draggable).attr('id');
			var parsentDropValue = $(this).html();
			$(this).html(parsentDropValue + dragValue);
		}
	});
</script>






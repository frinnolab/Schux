







<input class="btn bg-black btn-xs sms_alltag s-tag" id="[name]" value="[name]" type="button">
<input class="btn bg-black btn-xs sms_alltag s-tag" id="[class/department]" value="[class/department]" type="button">


<ul id="DragWordList">
	<li>[klfkfja]</li>
	<li>flafjlaf</li>
</ul>


<textarea class="txtDropTarget"></textarea>
<textarea class="txtDropTarget"></textarea>


<hr>



<script type="text/javascript">
  	
	$('.txtDropTarget').jqte();


	$("#DragWordList li").draggable({helper: 'clone'});

	$(".jqte_editor").droppable();


  	$(document).ready( function() {
	  	$('#ClickWordList li').click(function() { 
	    	$("#txtMessage").insertAtCaret($(this).text());
	    	return false
	  	});

	  	$("#DragWordList li").draggable({helper: 'clone'});

	  	$(".jqte").droppable({
	    	accept: "#DragWordList li",
	    	drop: function(ev, ui) {
	    		// $('.txtDropTarget').jqte().jqteVal("New article!");
	      		$(this).insertAtCaret(ui.draggable.text());
	    	}
	  	});
	});

	$.fn.insertAtCaret = function (myValue) {
		return this.each(function(){
			if (document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				sel.jqteVal(myValue);
				this.focus();
			} else if (this.selectionStart || this.selectionStart == '0') {
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
				$(this).jqteVal(this.value)
				this.focus();
				this.selectionStart = startPos + myValue.length;
				this.selectionEnd = startPos + myValue.length;
				this.scrollTop = scrollTop;
			} else {
				if(this.value === undefined) {
					this.value = '';
				}
				this.value += myValue;
				this.focus();
				$(this).jqteVal(this.value)
			}
		});
	};
</script>







<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<textarea class="texta" id="textareaid"></textarea>
<a href="#" id="link" >Click Here to Insert</a>


<script type="text/javascript">

	$('#link').click(function() {
		// alert('ok');
		insertAtCaret('texta', 'lol');
	});

	// insertAtCaret('textareaid', 'text to insert');

	function insertAtCaret(areaClass, text) {
		var txtarea = document.querySelector('.'+areaClass);
		if (!txtarea) { return; }

		var scrollPos = txtarea.scrollTop;
		var strPos = 0;
		var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
			"ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") {
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			strPos = range.text.length;
		} else if (br == "ff") {
			strPos = txtarea.selectionStart;
		}

		var front = (txtarea.value).substring(0, strPos);
		var back = (txtarea.value).substring(strPos, txtarea.value.length);
		txtarea.value = front + text + back;
		strPos = strPos + text.length;
		if (br == "ie") {
			txtarea.focus();
			var ieRange = document.selection.createRange();
			ieRange.moveStart ('character', -txtarea.value.length);
			ieRange.moveStart ('character', strPos);
			ieRange.moveEnd ('character', 0);
			ieRange.select();
		} else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd = strPos;
			txtarea.focus();
		}

		txtarea.scrollTop = scrollPos;
	}
	
</script>
<script type="application/javascript">
    $(function() {
        $('#calendar').fullCalendar({
			theme: true,
            customButtons: {
                reload: {
                    text: 'Reload',
                    click: function() {
                    }
                }
            },
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listMonth'
			},
			navLinks: true,
			editable: false,
			eventLimit: true,
			events: [
                <?php
                    foreach ($events as $event) {
                        echo '{';
                            echo "title: '".str_replace("'", "\'", $event->title)."', ";
                            echo "start: '".$event->fdate."T".$event->ftime."', ";
                            echo "end: '".$event->tdate."T".$event->ttime."', ";
			                echo "url:'".base_url('event/view/'.$event->eventID)."', ";
                            echo "color  : '#5C6BC0'";
                        echo '},';
                    }

                    foreach ($holidays as $holiday) {
                        echo '{';
                            echo "title: '".str_replace("'", "\'", $holiday->title)."', ";
                            echo "start: '".$holiday->fdate."', ";
                            echo "end: '".$holiday->tdate."', ";
			                echo "url:'".base_url('holiday/view/'.$holiday->holidayID)."', ";
                            echo "color  : '#C24984'";
                        echo '},';
                    }

                ?>
			]
		});
    });
</script>

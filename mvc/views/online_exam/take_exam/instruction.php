<div class="col-sm-12 do-not-refresh">
    <div class="callout callout-danger">
        <h4><?=$this->lang->line('take_exam_warning')?></h4>
        <p><?=$this->lang->line('take_exam_page_refresh')?></p>
    </div>
</div>

<section class="panel">
    <div class="panel-body bio-graph-info">
        <div id="printablediv" class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h3><?=$this->lang->line('take_exam_exam_name')?> : <?=$onlineExam->name?></h3>
                    <h3><?=$instruction->title?> :</h3>
                    <p><?=$instruction->content?></p>
                </div>
                <div class="text-center">
                    <a href="<?=base_url('take_exam/show/'.$onlineExam->onlineExamID)?>" >
                        <button class="btn btn-primary">
                            <?=$this->lang->line('take_exam_start_exam')?>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $('.sidebar-menu li a').css('pointer-events', 'none');
    function disableF5(e) {
        if ( ( (e.which || e.keyCode) == 116 ) || ( e.keyCode == 82 && e.ctrlKey ) ) {
            e.preventDefault();
        }
    }

    $(document).bind("keydown", disableF5);

    function Disable(event) {
        if (event.button == 2)
        {
            window.oncontextmenu = function () {
                return false;
            }
        }
    }
    document.onmousedown = Disable;
</script>
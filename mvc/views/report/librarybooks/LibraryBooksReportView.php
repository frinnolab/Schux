<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-librarybooksreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_librarybooksreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="booknameDiv">
                    <label><?=$this->lang->line("librarybooksreport_bookname")?></label>
                    <?php
                    $bookArray['0'] = $this->lang->line("librarybooksreport_please_select");
                    if(count($books)) {
                        foreach($books as $book) {
                            $bookArray[$book->bookID] = $book->book.' - ('.$book->author.')';
                        }
                    }
                    echo form_dropdown("bookname", $bookArray, set_value("bookname"), "id='bookname' class='form-control select2'");
                    ?>
                </div>
                
                <div class="form-group col-sm-4" id="subjectcodeDiv">
                    <label for="subjectcode" ><?=$this->lang->line("librarybooksreport_subjectcode")?></label>
                    <input type="text" name="subjectcode" class="form-control" id="subjectcode">
                </div>
                <div class="form-group col-sm-4" id="rackNoDiv">
                    <label for="rackNo" ><?=$this->lang->line("librarybooksreport_rackNo")?></label>
                    <input type="text" name="rackNo" class="form-control" id="rackNo">
                </div>
                <div class="form-group col-sm-4" id="statusDiv">
                    <label><?=$this->lang->line("librarybooksreport_status")?></label>
                    <?php
                    $typeArray['0'] = $this->lang->line("librarybooksreport_please_select");
                    $typeArray['1'] = $this->lang->line("librarybooksreport_available");
                    $typeArray['2'] = $this->lang->line("librarybooksreport_unavailable");
                    echo form_dropdown("status", $typeArray, set_value("status"), "id='status' class='form-control select2'");
                    ?>
                </div>

                <div class="col-sm-4">
                    <button id="get_librarybooksreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("librarybooksreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_librarybooksreport"></div>


<script type="text/javascript">
    $('.select2').select2();

    $(document).on('change', "#bookname", function() {
        $('#load_librarybooksreport').html("");
    });

    $(document).on('change', "#author", function() {
        $('#load_librarybooksreport').html("");
    });

    $(document).on('change', "#status", function() {
        $('#load_librarybooksreport').html("");
    });

    $(document).on('click','#get_librarybooksreport', function() {
        $('#load_librarybooksreport').html("");
        var bookname = $('#bookname').val();
        var author   = $('#author').val();
        var subjectcode = $('#subjectcode').val();
        var rackNo = $('#rackNo').val();
        var status = $('#status').val();

        var error = 0;

        var field = {
            "bookname" : bookname,
            "author"   : author,
            "subjectcode" : subjectcode,
            "rackNo"       : rackNo,
            "status"    : status
        }

        if(error == 0 ) {
            makingPostDataPreviousofAjaxCall(field);
        }

    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('librarybooksreport/getLibrarybooksReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_librarybooksreport').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }
</script>



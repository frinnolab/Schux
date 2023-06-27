
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
            <li class="active"><?=$this->lang->line('add_payment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <?php 
                    $usertypeID = $this->session->userdata("usertypeID"); 
                    if($usertypeID == 1 || $usertypeID == 5) { 
                ?>
                    <form class="form-horizontal" role="form" method="post">
                        <?php 
                            if(form_error('amount')) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                        ?>
                            <label for="amount" class="col-sm-2 control-label">
                                <?=$this->lang->line("invoice_amount")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount', $dueamount)?>" >
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('amount'); ?>
                            </span>
                        </div>

                        <?php 
                            if(form_error('payment_method')) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                        ?>
                            <label for="payment_method" class="col-sm-2 control-label">
                                <?=$this->lang->line("invoice_paymentmethod")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <?php
                                    $array = $array = array('0' => $this->lang->line("invoice_select_paymentmethod"));
                                    $array['Cash'] = $this->lang->line('Cash');
                                    $array['Cheque'] = $this->lang->line('Cheque');
                                    if ($payment_settings['paypal_status'] == true) {
                                        $array['Paypal'] = $this->lang->line('Paypal');
                                    }
                                    if ($payment_settings['stripe_status'] == true) {
                                        $array['Stripe'] = $this->lang->line('Stripe');
                                    }
                                    if ($payment_settings['payumoney_status'] == true) {
                                        $array['Payumoney'] = $this->lang->line('PayUmoney');
                                    }
                                    if ($payment_settings['voguepay_status'] == true) {
                                        $array['Voguepay'] = $this->lang->line('Voguepay');
                                    }

                                    echo form_dropdown("payment_method", $array, set_value("payment_method"), "id='payment_method' class='form-control select2' onchange='CheckType()'");
                                ?>
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('payment_method'); ?>
                            </span>
                        </div>

                        <!-- Card Options fields -->
                        <div id="cardOption" style="display: none;">
                            <div class="form-group <?=form_error('card_number') ? 'has-error' : ''; ?>" >
                                <label for="amount" class="col-sm-2 control-label">
                                    <?=$this->lang->line("card_number")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="card_number" name="card_number" value="<?=set_value('card_number', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('card_number'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('cvv') ? 'has-error' : ''; ?>" >
                                <label for="amount" class="col-sm-2 control-label">
                                    <?=$this->lang->line("cvv")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="cvv" name="cvv" value="<?=set_value('cvv', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('cvv'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=(form_error('expire_month') || form_error('expire_year')) ? 'has-error' : ''; ?>" >
                                <label for="amount" class="col-sm-2 control-label">
                                    <?=$this->lang->line("expire")?>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="expire_month" name="expire_month" value="<?=set_value('expire_month', null)?>" >
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="expire_year" name="expire_year" value="<?=set_value('expire_year', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('expire_month'); ?>
                                    <?php echo form_error('expire_year'); ?>
                                </span>
                            </div>
                        </div>
                        <!-- Card options end-->
                        <!-- PayUOptions Options fields -->
                        <div id="payuInputs" style="display: none;">
                            <div class="form-group <?=form_error('first_name') ? 'has-error' : ''; ?>" >
                                <label for="first_name" class="col-sm-2 control-label">
                                    <?=$this->lang->line("first_name")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?=set_value('first_name', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('first_name'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('email') ? 'has-error' : ''; ?>" >
                                <label for="email" class="col-sm-2 control-label">
                                    <?=$this->lang->line("email")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('email'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('phone') ? 'has-error' : ''; ?>" >
                                <label for="phone" class="col-sm-2 control-label">
                                    <?=$this->lang->line("phone")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('phone'); ?>
                                </span>
                            </div>
                        </div>
                        <!-- PayUOptions options end-->

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_payment")?>" >
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form class="form-horizontal" role="form" method="post">
                        <?php 
                            if(form_error('amount')) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                        ?>
                            <label for="amount" class="col-sm-2 control-label">
                                <?=$this->lang->line("invoice_amount")?>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount', $dueamount)?>" >
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('amount'); ?>
                            </span>
                        </div>

                        <?php
                            if(form_error('payment_method'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                            ?>
                            <label for="payment_method" class="col-sm-2 control-label">
                                <?=$this->lang->line("invoice_paymentmethod")?>
                            </label>
                            <div class="col-sm-6">
                                <?php
                                $array = $array = array('0' => $this->lang->line("invoice_select_paymentmethod"));
                                if ($payment_settings['paypal_status'] == true) {
                                    $array['Paypal'] = $this->lang->line('Paypal');
                                }
                                if ($payment_settings['stripe_status'] == true) {
                                    $array['Stripe'] = $this->lang->line('Stripe');
                                }
                                if ($payment_settings['payumoney_status'] == true) {
                                    $array['Payumoney'] = $this->lang->line('PayUmoney');
                                }
                                if ($payment_settings['voguepay_status'] == true) {
                                    $array['Voguepay'] = $this->lang->line('Voguepay');
                                }

                                echo form_dropdown("payment_method", $array, set_value("payment_method"), "id='payment_method' class='form-control select2' onchange='CheckType()'");
                                ?>
                            </div>

                            <span class="col-sm-4 control-label">
                                <?php echo form_error('payment_method'); ?>
                            </span>
                        </div>

                        <!-- Card Options fields -->
                        <div id="cardOption" style="display: none;">
                            <div class="form-group <?=form_error('card_number') ? 'has-error' : ''; ?>" >
                                <label for="amount" class="col-sm-2 control-label">
                                    <?=$this->lang->line("card_number")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="card_number" name="card_number" value="<?=set_value('card_number', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('card_number'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('cvv') ? 'has-error' : ''; ?>" >
                                <label for="amount" class="col-sm-2 control-label">
                                    <?=$this->lang->line("cvv")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="cvv" name="cvv" value="<?=set_value('cvv', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('cvv'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('expire_month') || form_error('expire_year') ? 'has-error' : ''; ?>" >
                                <label for="amount" class="col-sm-2 control-label">
                                    <?=$this->lang->line("expire")?>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="expire_month" name="expire_month" value="<?=set_value('expire_month', null)?>" >
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="expire_year" name="expire_year" value="<?=set_value('expire_year', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php 
                                        echo form_error('expire_month'); 
                                        echo form_error('expire_year');
                                    ?>
                                </span>
                            </div>
                        </div>
                        <!-- Card options end-->
                        <!-- PayUOptions Options fields -->
                        <div id="payuInputs" style="display: none;">
                            <div class="form-group <?=form_error('first_name') ? 'has-error' : ''; ?>" >
                                <label for="first_name" class="col-sm-2 control-label">
                                    <?=$this->lang->line("first_name")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?=set_value('first_name', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('first_name'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('email') ? 'has-error' : ''; ?>" >
                                <label for="email" class="col-sm-2 control-label">
                                    <?=$this->lang->line("email")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('email'); ?>
                                </span>
                            </div>
                            <div class="form-group <?=form_error('phone') ? 'has-error' : ''; ?>" >
                                <label for="phone" class="col-sm-2 control-label">
                                    <?=$this->lang->line("phone")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', null)?>" >
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('phone'); ?>
                                </span>
                            </div>
                        </div>
                        <!-- PayUOptions options end-->

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_payment")?>" >
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if($setPaymentMethod == 'Stripe') { ?>
    <script type="text/javascript">
        $('#cardOption').show();
        $('#payuInputs').hide();
    </script>
<?php } elseif($setPaymentMethod == 'Payumoney') { ?>
    <script type="text/javascript">
        $('#payuInputs').show();
        $('#cardOption').hide();
    </script>
<?php } ?>

<script type="text/javascript">
// $('.select2').select2();
$("#expire_month").datepicker( {
    format: "mm",
    viewMode: "months",
    minViewMode: "months"
});
$("#expire_year").datepicker( {
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years"
});
function CheckType() {
    var payment_method = $('#payment_method').val();

    if (payment_method=="Stripe") {
        $('#cardOption').show();
        $('#payuInputs').hide();
    } else if (payment_method=="Payumoney") {
        $('#payuInputs').show();
        $('#cardOption').hide();
    } else{
        $('#cardOption').hide();
        $('#payuInputs').hide();
    }
}
</script>
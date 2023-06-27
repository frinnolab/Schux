<html xmlns="http://www.w3.org/1999/html">
<head>
    <script>
        /*
         script is made to check the hash posted
         */
        var hash = '<?php echo $hash ?>';
        function submitPayuForm() {
            if(hash == '') {
                console.log('empty hash');
            }
            var payuForm = document.forms.payuForm;
            payuForm.submit();
        }
        document.cookie = 'userdata' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
    </script>
</head>
<body onload="submitPayuForm()">
<form action="<?=$action?>" name="payuForm"></form>
<input type="hidden" name="key" value="<?=$payukey?>">
<input type="hidden" name="surl" value="<?=$success_url?>">
<input type="hidden" name="furl" value="<?=$failure_url?>">
<input type="hidden" name="txnid" value="<?=$transactionId?>">
<input type="hidden" name="amount" value="80.00">
<input type="hidden" name="productinfo" value="<?=$productinfo?>">
<input type="hidden" name="firstname" value="<?=$firstname?>">
<input type="hidden" name="service_provider" value="payu_paisa">
<input type="hidden" name="email" value="<?=$email?>">
<input type="hidden" name="phone" value="<?=$phone?>">
<input type="hidden" name="hash" value="<?=$hash?>">
</form>
</body>
</html>

Redirecting to Voguepay....
<html>
<head>
    <script>

        function submitVoguePayForm() {
            var voguepayForm = document.forms.voguepayForm;
            voguepayForm.submit();
        }
    </script>
</head>
<body onload="submitVoguePayForm()">
<form action="<?=$action?>" method="post" name="voguepayForm">
    <input type='hidden' name='v_merchant_id' value='<?=$v_merchant_id?>' />
    <input type='hidden' name='merchant_ref' value='<?=$merchant_ref?>' />
    <input type='hidden' name='memo' value='<?=$memo?>' />
    <input type='hidden' name='notify_url' value='<?=$notify_url;?>' />
    <input type='hidden' name='success_url' value='<?=$success_url;?>' />
    <input type='hidden' name='fail_url' value='<?=$fail_url;?>' />
    <input type='hidden' name='developer_code' value='<?=$developer_code?>' />
    <input type='hidden' name='store_id' value='<?=$store_id?>' />
    <input type='hidden' name='total' value='<?=$total?>' />
    <input type='hidden' name='cur' value='<?=$cur?>' />
</form>
</body>
</html>

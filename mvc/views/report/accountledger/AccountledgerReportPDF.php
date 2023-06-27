<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div style="margin-bottom: 50px;">
        <div class="row">
            <div class="reportPage-header">
                <span class="header"><img class="logo" src="<?=base_url('uploads/images/'.$siteinfos->photo)?>"></span>
                <p class="title"><?=$siteinfos->sname?></p>
                <p class="title-desc"><?=$siteinfos->address?></p>
                <p class="title-desc"><?=$this->lang->line('accountledgerreport_academicyear'). ' : '.$schoolyearName?></p>
            </div>
            <div style="margin-bottom: -5px">
                <h3><?=$this->lang->line('accountledgerreport_report_for')?> - <?=$this->lang->line('accountledgerreport_accountledger')?></h3>
            </div>
            <?php if($fromdate !='' && $todate !='') { ?>
                <div>
                    <h5 class="pull-left"><?=$this->lang->line('accountledgerreport_fromdate')?> : <?=date('d M Y',$fromdate)?></h5>  
                    <h5 class="pull-right"><?=$this->lang->line('accountledgerreport_todate')?> : <?=date('d M Y',$todate)?></h5>
                </div>
            <?php } ?>
            <div class="accountledgerreport">

                <div class="singleaccountledger">
                    <table class="ledgertable">
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('accountledgerreport_income')?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?=$this->lang->line('accountledgerreport_income_des')?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_total')?> <span class="text-bold"><?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></span></td>
                            <td><?=number_format($totalincome,2)?></td>
                        </tr>
                    </table>

                    <table class="ledgertable">
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('accountledgerreport_total_balance')?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_income')?> (+)</td>
                            <td><?=number_format($totalincome,2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_fees_collections')?> (+)</td>
                            <td><?=number_format($totalcollection,2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_fines')?> (+)</td>
                            <td><?=number_format($totalfine,2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_expense')?> (-)</td>
                            <td><?=number_format($totalexpense,2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_salary')?> (-)</td>
                            <td><?=number_format($totalsalarypayment,2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_grand_total')?> <span class="text-bold"><?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></span></td>
                            <td>
                                <?php 
                                    $mainincome  = ($totalincome + $totalcollection + $totalfine);
                                    $mainexpense = ($totalexpense + $totalsalarypayment);
                                    $mainbalance  = ($mainincome - $mainexpense);
                                    echo number_format($mainbalance,2);
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="singleaccountledger marginledger">
                
                    <table class="ledgertable">
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('accountledgerreport_fees_collections')?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?=$this->lang->line('accountledgerreport_fees_collections_des')?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_total')?> <span class="text-bold"><?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></span></td>
                            <td><?=number_format($totalcollection,2)?></td>
                        </tr>
                    </table>
                
               
                    <table class="ledgertable">
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('accountledgerreport_fines')?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?=$this->lang->line('accountledgerreport_fines_des')?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_total')?> <span class="text-bold"><?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></span></td>
                            <td><?=number_format($totalfine,2)?></td>
                        </tr>
                    </table>
                    <table class="ledgertable">
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('accountledgerreport_expense')?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?=$this->lang->line('accountledgerreport_expense_des')?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_total')?> <span class="text-bold"><?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></span></td>
                            <td><?=number_format($totalexpense,2)?></td>
                        </tr>
                    </table>
                    
                    <table class="ledgertable">
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('accountledgerreport_salary')?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?=$this->lang->line('accountledgerreport_salary_des')?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('accountledgerreport_total')?> <span class="text-bold"><?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></span></td>
                            <td><?=number_format($totalsalarypayment,2)?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</body>
</html>
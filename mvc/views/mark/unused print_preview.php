<div class="profileArea">
    <?php featureheader($siteinfos);?>
    <div class="mainArea">
        <div class="areaTop">
            <div class="studentImage">
                <img class="studentImg" src="<?=pdfimagelink($student->photo)?>" alt="">
            </div>
            <div class="studentProfile">
                <div class="singleItem">
                    <div class="single_label"><?=$this->lang->line('mark_name')?></div>
                    <div class="single_value">: <?=$student->srname?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label"><?=$this->lang->line('mark_registerNO')?></div>
                    <div class="single_value">: <?=$student->srregisterNO?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label"><?=$this->lang->line('mark_roll')?></div>
                    <div class="single_value">: <?=$student->srroll?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label"><?=$this->lang->line('mark_classes')?></div>
                    <div class="single_value">: <?=count($classes) ? $classes->classes : ''?></div>
                </div>
                <div class="singleItem">
                    <div class="single_label"><?=$this->lang->line('mark_section')?></div>
                    <div class="single_value">: <?=count($section) ? $section->section : '' ?></div>
                </div>
            </div>
        </div>
        <div class="markArea">
            <?php
                $text = '';
                $optionalsubjectID = $student->sroptionalsubjectID;
                if(count($marksettings)) {
                    foreach ($marksettings as $examID => $marksetting) {
                        $text .= '<div style="" class="box" id="e'.$examID.'">';
                            $headerColor = ['bg-sky', 'bg-purple-shipu','bg-sky-total-grade', 'bg-sky-light', 'bg-sky-total' ];
                            $text .= '<div class="box-header" style="background-color:#FFFFFF;">';
                                $text .= '<h3 style="color:#23292F;padding:5px" class="text-center">'; 
                                    $text .= (isset($exams[$examID]) ? $exams[$examID] : '');
                                $text .= '</h3>';
                            $text .= '</div>';
                            $text .= '<div class="box-body mark-bodyID" style="border-top:1px solid #23292F;">';
                                $text .= "<table class=\"table table-striped table-bordered\" >";
                                        $text .= "<tr>";
                                            $text .= "<th class='text-center' rowspan='2' style='background-color:#395C7F;color:#fff;'>";
                                                $text .= $this->lang->line("mark_subject");
                                            $text .= "</th>";
                                            foreach ($marksetting as $subjectID => $markpercentageArr) {
                                                foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                                                    $text .= "<th colspan='2' class=' text-center' style='background-color:#395C7F;color:#fff;'>";
                                                        $text .= (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->markpercentagetype : '');
                                                    $text .= "</th>";
                                                }
                                                break;
                                            }
                                            $text .= "<th colspan='3' class='text-center ' style='background-color:#395C7F;color:#fff;'>";
                                                $text .= $this->lang->line("mark_total");
                                            $text .= "</th>";
                                        $text .= "</tr>";
                                        $text .= "<tr>";
                                        foreach ($marksetting as $subjectID => $markpercentageArr) {
                                            foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                                                $text .= "<th>";
                                                    $text .= $this->lang->line("mark_obtained_mark");
                                                $text .= "</th>";

                                                $text .= "<th>";
                                                    $text .= $this->lang->line("mark_highest_mark");
                                                $text .= "</th>";
                                            }
                                            $text .= "<th>";
                                                $text .= $this->lang->line("mark_mark");
                                            $text .= "</th>";
                                            // $text .= "<th>";
                                            //     $text .= $this->lang->line("mark_point");
                                            // $text .= "</th>";
                                            $text .= "<th>";
                                                $text .= $this->lang->line("mark_grade");
                                            $text .= "</th>";
                                            break;
                                        }
                                        $text .= "</tr>";



                                        $totalMark           = 0;
                                        $totalFinalMark      = 0;
                                        $totalSubject        = 0;
                                        // $averagePoint        = 0;
                                        $opmarkpercentageArr = [];
                                        foreach ($marksetting as $subjectID => $markpercentageArr) {
                                            if($subjectID == $optionalsubjectID) {
                                                $opmarkpercentageArr = $markpercentageArr;
                                            }
                                            if(!in_array($subjectID, $optionalsubjectArr)) {
                                                $totalSubject++;
                                                $text.= "<tr>";
                                                    $text.= "<td>";
                                                        $text.= isset($subjects[$subjectID]) ? $subjects[$subjectID]->subject : '';
                                                    $text.= "</td>";

                                                    $subjectfinalmark = isset($subjects[$subjectID]) ? (int)$subjects[$subjectID]->finalmark : 0;
                                                    $totalSubjectMark = 0;
                                                    $percentageMark   = 0;
                                                    foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                                                        $f = false;
                                                        if(isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                                                            $f = true;
                                                            $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                                        }

                                                        $text.= "<td>";
                                                            if(isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                                                                $text .= $marks[$examID][$subjectID][$markpercentageID];
                                                                $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                                                            } else {
                                                                if($f) {
                                                                    $text .= 'N/A';
                                                                }
                                                            }
                                                        $text.= "</td>";

                                                        $text.= "<td>";
                                                            if(isset($highestmarks[$examID][$subjectID][$markpercentageID]) && ($highestmarks[$examID][$subjectID][$markpercentageID] != -1) && $f) {
                                                                $text .= $highestmarks[$examID][$subjectID][$markpercentageID];
                                                            } else {
                                                                    if($f) {
                                                                    $text .= 'N/A';
                                                                }
                                                            }
                                                        $text.= "</td>";
                                                    }
                                                    $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                                                    $text.= "<td>";
                                                        $text.= $totalSubjectMark;

                                                        $totalMark        += $totalSubjectMark;
                                                        $totalFinalMark   += $finalpercentageMark;
                                                        $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                                    $text.= "</td>";


                                                    if(count($grades)) {
                                                        foreach ($grades as $grade) {
                                                            if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                                                // $text.= "<td>";
                                                                //     $text.= $grade->point;
                                                                //     $averagePoint += $grade->point;
                                                                // $text.= "</td>";
                                                                $text.= "<td>";
                                                                    $text.= $grade->grade;
                                                                $text.= "</td>";
                                                            }
                                                        }
                                                    } else {
                                                        $text.= "<td>";
                                                            $text.= 'N/A';
                                                        $text.= '</td>';
                                                        $text.= "<td>";
                                                            $text.= 'N/A';
                                                        $text.= '</td>';
                                                    }
                                                $text.= '</tr>';
                                            }
                                        }

                                        if(($optionalsubjectID > 0) && count($opmarkpercentageArr)) {
                                            $totalSubject++;
                                            $text.= "<tr>";
                                                $text.= "<td>";
                                                    $text.= isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->subject : '';
                                                $text.= "</td>";
                                                $subjectfinalmark  = isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->finalmark : 0;

                                                $totalSubjectMark = 0;
                                                $percentageMark   = 0;
                                                foreach ($opmarkpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                                                    $f = false;
                                                    if(isset($opmarkpercentageArr['own']) && in_array($markpercentageID, $opmarkpercentageArr['own'])) {
                                                        $f = true;
                                                        $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                                    } 
                                                    
                                                    $text .=  "<td>";
                                                        if(isset($marks[$examID][$optionalsubjectID][$markpercentageID]) && $f) {
                                                            $text .= $marks[$examID][$optionalsubjectID][$markpercentageID];
                                                            $totalSubjectMark += $marks[$examID][$optionalsubjectID][$markpercentageID];
                                                        } else {
                                                            if($f) {
                                                                $text .= 'N/A';
                                                            }
                                                        }
                                                    $text .=  "</td>";
                                                    $text .=  "<td>";
                                                        if(isset($highestmarks[$examID][$optionalsubjectID][$markpercentageID]) && ($highestmarks[$examID][$optionalsubjectID][$markpercentageID] != -1) && $f) {
                                                            $text .= $highestmarks[$examID][$optionalsubjectID][$markpercentageID];
                                                        } else {
                                                            if($f) {
                                                                $text .= 'N/A';
                                                            }
                                                        }
                                                    $text .=  "</td>";
                                                }
                                                $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                                                $text .=  "<td>";
                                                    $text             .= $totalSubjectMark;
                                                    $totalMark        += $totalSubjectMark;
                                                    $totalFinalMark   += $finalpercentageMark;

                                                    $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                                $text .=  "</td>";

                                                if(count($grades)) {
                                                    foreach ($grades as $grade) {
                                                        if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                                            // $text .=  "<td>";
                                                            //     $text .=  $grade->point;
                                                            // $text .=  "</td>";
                                                            $text .=  "<td>";
                                                                $text .=  $grade->grade;
                                                            $text .=  "</td>";
                                                        }
                                                    }
                                                } else {
                                                    $text .=  "<td>";
                                                        $text .=  'N/A';
                                                    $text .=  '</td>';
                                                    $text .=  "<td>";
                                                        $text .=  'N/A';
                                                    $text .=  '</td>';
                                                }
                                            $text .= '</tr>';
                                        }
                                $text .= '</table>';
                                $text .= '<div style="padding-left:0px;">';
                                    $text .= '<p style="font-size:14px">'. $this->lang->line('mark_total_marks').' : <span>'. ini_round($totalFinalMark).'</span>';
                                    $text .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_total_obtained_marks').' : <span>'. ini_round($totalMark).'</span>';
                                    
                                    $totalAverageMark = $totalMark / $totalSubject;
                                    $text .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_total_average_marks').' : <span>'. ini_round($totalAverageMark).'</span>';

                                    $totalmarkpercentage  = markCalculationView($totalMark, $totalFinalMark);
                                    $text .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_total_average_marks_percetage').' : <span>'. ini_round($totalmarkpercentage).'</span>';

                                    // $gpaAveragePoint = $averagePoint / $totalSubject;
                                    // $text .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('mark_total_average_marks_percetage').' : <span>'. ini_round($gpaAveragePoint).'</span>';
                                    
                                    $text .= '</p>';
                                $text .= '</div>';
                            $text .= '</div>';
                        $text .= '</div><br>';
                    }
                }
                echo $text;
            ?>
        </div>
    </div>
</div>
<?php featurefooter($siteinfos);?>
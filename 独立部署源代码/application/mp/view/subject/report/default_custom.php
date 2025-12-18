<?php
use app\Defs;
if(empty($source)){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "header.php";
}else if($source == 'dian'){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "header_dian.php";
}
?>
<div class="report-content-section">
    <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
        <ion-card-header color="light">
            <ion-card-subtitle color="dark"><?=$subject['name']?></ion-card-subtitle>
        </ion-card-header>
    </ion-card>
    <?php echo $reportBody; ?>
 <!----------------------------------------------------------------------------------------->
<?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_VIDEO_AUDIO, $subject['report_elements'])){ ?>
    <!--视频-->
    <?php if($subject['video_url']){ ?>
        <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
            <ion-card-header color="light">
                <ion-card-subtitle color="dark">视频解说</ion-card-subtitle>
            </ion-card-header>
            <ion-card-content>
                <div class="text-center">
                    <video controls="controls" style="width:100%;" src="<?=$subject['video_url']?>">
                        your browser does not support the video tag
                    </video>
                </div>
            </ion-card-content>
        </ion-card>
    <?php } ?>
    <!--音频-->
    <?php if($subject['audio_url']){ ?>
        <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
            <ion-card-header color="light">
                <ion-card-subtitle color="dark">音频解说</ion-card-subtitle>
            </ion-card-header>
            <ion-card-content>
                <div class="text-center">
                    <audio controls="controls" src="<?=$subject['audio_url']?>">
                        your browser does not support the audio tag
                    </audio>
                </div>
            </ion-card-content>
        </ion-card>
    <?php } ?>
<?php } ?>
<?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_STORY, $subject['report_elements'])){ ?>
    <!--专家建议-->
    <?php if($subject['report_story1']){ ?>
        <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
            <ion-card-header color="light">
                <ion-card-subtitle color="dark">专家建议</ion-card-subtitle>
            </ion-card-header>
            <ion-card-content class="ion-no-padding">
                <ion-list>
                <?php for ($i=1; $i<=6; $i++){ if($subject['report_story'.$i]){ ?>
                    <ion-item detail="false" lines="none">
                        <?php if (!empty($subject['report_image'.$i])){ ?>
                            <ion-avatar aria-hidden="true" slot="start">
                                <img src="<?=generateThumbnailUrl($subject['report_image'.$i], 100)?>">
                            </ion-avatar>
                        <?php } ?>
                        <ion-note class="ion-padding-bottom">
                            <?php echo htmlspecialchars_decode($subject['report_story'.$i]); ?>
                        </ion-note>
                    </ion-item>
                <?php }} ?>
                </ion-list>
            </ion-card-content>
        </ion-card>
    <?php } ?>
<?php } ?>
</div>
<?php
if(empty($source)){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "footer.php";
}else if($source == 'dian'){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "footer_dian.php";
}
?>
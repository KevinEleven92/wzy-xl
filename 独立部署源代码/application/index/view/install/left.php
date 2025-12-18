<?php
use app\index\controller\Install;
?>
<div class="d-flex flex-column">
    <h5 class="m-1">《为之易心理健康系统》安装程序</h5>
    <div class="m-1"><div class="easyui-progressbar" data-options="text:'',value:<?=$stepValue?>" style="width:100%;"></div></div>
    <div class="m-1">
    <?php 
    foreach(Install::STEP_DEFS as $index=>$stepDef){
        if($index == $step){ 
            echo '<span class="font-weight-bold">' . $stepDef . '</span>';
        }else{
            echo '<span>' . $stepDef . '</span>';
        }
        if($index != array_key_last(Install::STEP_DEFS)){
            echo "&gt;&gt;";
        }
    }    
    ?>
    </div>
</div>
<script type="text/javascript">
function dotFormatter(row){
    if(row.value == 2){
        return '<span class="fa fa-circle text-success"></span>';
    }else{
        return null;
    }
}
function labelFormatter(row){
    if(row['label'] && row.value == 2){
        return '<strong>' + row['label'] + '</strong>';
    }else{
        return row['label'];
    }
}
function formatter(row){
    if(row['content'] && row.value == 2){
        return '<strong>' + row['content'] + '</strong>';
    }else{
        return row['content'];
    }
}
</script>
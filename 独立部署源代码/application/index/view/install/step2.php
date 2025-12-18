<div class="form-container">
    <form class="form-body" method="post">
        <table class="table-form" cellpadding="5">
            <tr><td colspan="3" class="form-caption">服务器环境检查</td></tr>
            <?php foreach($_serverResult as $name=>$result){ ?>
                <tr>
                    <td class="field-label" style="width: 200px;"><?=$name?></td>
                    <td class="field-input">
                        <?=$result[0]?>
                    </td>
                    <td class="field-input">
                        <?=$result[1]?'<span class="badge badge-success"><i class="fa fa-check"></i></span>':'<span class="badge badge-danger"><i class="fa fa-close"></i></span>'?>
                    </td>
                </tr>
            <?php } ?>
            <tr><td colspan="3" class="form-caption">组件支持检查</td></tr>
            <?php foreach($_extensionResult as $name=>$result){ ?>
                <tr>
                    <td class="field-label" style="width: 200px;"><?=$name?></td>
                    <td class="field-input">
                        <?=$result[0]?>
                    </td>
                    <td class="field-input">
                        <?=$result[1]?'<span class="badge badge-success"><i class="fa fa-check"></i></span>':'<span class="badge badge-danger"><i class="fa fa-close"></i></span>'?>
                    </td>
                </tr>
            <?php } ?>
            <tr><td colspan="3" class="form-caption">权限检查(以下文件/文件夹必须确保服务器进程有读写权限)</td></tr>
            <?php foreach($_privilegeResult as $name=>$result){ ?>
                <tr>
                    <td class="field-label" style="width: 200px;"><?=$name?></td>
                    <td class="field-input">
                        <?=$result[0]?>
                    </td>
                    <td class="field-input">
                        <?=$result[1]?'<span class="badge badge-success"><i class="fa fa-check"></i></span>':'<span class="badge badge-danger"><i class="fa fa-close"></i></span>'?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </form>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:'fa fa-arrow-circle-right',
                    onClick:function(){
                        stepModule2.next();
                    },disabled:<?=$checkResultOk?'false':'true'?>">下一步
        </a>
    </div>
</div>
<script>
    let stepModule2 = {
        next:function(){
            window.mainModule.goNext();
        }
    };
</script>
<div class="form-container">
    <form id="db-config-form" class="form-body" method="post">
        <table class="table-form" cellpadding="5">
            <tr>
                <td class="field-label" style="width: 200px;">数据库类型</td>
                <td class="field-input">MySql</td>
            </tr>
            <tr>
                <td class="field-label">数据库地址</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="hostname"
                           data-options="required:true,width:200,validType:['length[1,60]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">数据库端口</td>
                <td class="field-input">
                    <input class="easyui-numberbox" name="hostport" value="3306"
                           data-options="required:true,width:200,min:1,max:65536">
                </td>
            </tr>
            <tr>
                <td class="field-label">数据库名</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="database"
                           data-options="required:true,width:200,validType:['length[1,60]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">数据库用户名</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="username"
                           data-options="required:true,width:200,validType:['length[1,60]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">数据库密码</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="password"
                           data-options="required:true,width:200,validType:['length[1,60]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">表存储引擎</td>
                <td class="field-input">
                    InnoDB
                </td>
            </tr>
            <tr>
                <td class="field-label">数据库驱动</td>
                <td class="field-input">
                    pdo_mysql
                </td>
            </tr>
        </table>
    </form>
    <div class="form-toolbar">
        <a id="step-next-btn" class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:'fa fa-arrow-circle-right',
                    onClick:function(){
                        stepModule3.next();
                    }">下一步
        </a>
    </div>
</div>
<script>
    let stepModule3 = {
        next:function(){
            var isValid = $('#db-config-form').form('validate');
            if(!isValid){
                return;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Install/step3')?>', $("#db-config-form").serialize(), function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    window.mainModule.goNext();
                }
            }, 'json');
        }
    };
</script>
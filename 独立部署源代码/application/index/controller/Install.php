<?php
// +----------------------------------------------------------------------
// | WZYCODING [ SIMPLE SOFTWARE IS THE BEST ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2025 wzycoding All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://spdx.org/licenses/GPL-2.0.html )
// +----------------------------------------------------------------------
// | Author: wzycoding <wzycoding@qq.com>
// +----------------------------------------------------------------------
namespace app\index\controller;
use think\Controller;
use think\Log;
use think\Debug;
use think\Request;
use think\Db;
use think\Config;
use app\index\logic\Install as InstallLogic;

class Install extends Controller{
    protected function _initialize(){
        if(!$this->request->isAjax()){
            if(checkInstalled()){
                //系统已经安装
                $this->redirect('index/Index/index');
            }
        }
    }
    public function index(){
        return $this->fetch();
    }
    const STEP_DEFS = [
        '1'=>'安装协议',
        '2'=>'环境检查',
        '3'=>'数据库建立与设置',
        '4'=>'安装结果'
    ];    
    /**
     * left
     *
     * @param  mixed $step
     * @return void
     */
    public function left($step = 1){
        $this->assign('step', $step);
        $this->assign('stepValue', ($step-1)/3*100);
        return $this->fetch();
    }
    public function right($step = 1){
        if($this->request->isGet()){
            return call_user_func([$this, 'step' . $step]);
        }
    }    
    /**
     * 安装协议
     *
     * @return void
     */
    public function step1(){
        if($this->request->isGet()){
            return $this->fetch('step1');
        }
    }    
    /**
     * 环境检查
     *
     * @return void
     */
    public function step2(){
        if($this->request->isGet()){
            $_serverResult = InstallLogic::I()->checkServer();
            $_extensionResult = InstallLogic::I()->checkExtension();
            $_privilegeResult = InstallLogic::I()->checkPrivilege();
            $this->assign([
                '_serverResult'=>$_serverResult,
                '_extensionResult'=>$_extensionResult,
                '_privilegeResult'=>$_privilegeResult
            ]);
            $serverResult = array_all(function($item){
                return empty($item[1])?false:true;
            }, $_serverResult);
            $extensionResult = array_all(function($item){
                return empty($item[1])?false:true;
            }, $_extensionResult);
            $privilegeResult = array_all(function($item){
                return empty($item[1])?false:true;
            }, $_privilegeResult);
            $checkResultOk = false;
            if($serverResult && $extensionResult && $privilegeResult){
                $checkResultOk = true;
            }
            $this->assign('checkResultOk', $checkResultOk);
            return $this->fetch('step2');
        }
    }    
    /**
     * 数据库建立与设置
     *
     * @return void
     */
    public function step3(){
        if($this->request->isGet()){
            return $this->fetch('step3');
        }
        $formData = input('post.');
        foreach($formData as $key=>&$value){
            $value = htmlspecialchars_decode($value);
        }
        try{
            InstallLogic::I()->importDb($formData);
            return ajaxSuccess();
        }catch(\Exception $e){
            return ajaxError($e->getMessage());
        }
    }    
    /**
     * 安装结果
     *
     * @return void
     */
    public function step4(){
        if($this->request->isGet()){
            return $this->fetch('step4');
        }
    }
}
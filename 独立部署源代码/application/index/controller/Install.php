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

class Install extends Controller{
    protected $_serverResult = [
        'server'  => [null, null],
        'phpver'  => [null, null],
        'path' => [null, null],
    ];
    protected $_extensionResult = [
        'curl'=>[null, null],
        'gd2'=>[null, null],
        'openssl'=>[null, null],
        'pdo_mysql'=>[null, null],
        'pdo_sqlite'=>[null, null],
        'zip'=>[null, null],
        'mbstring'=>[null, null],
        'fileinfo'=>[null, null]
    ];
    protected $_privilegeResult = [
        'local'=>[null, null],
        'report'=>[null, null],
        'temp'=>[null, null],
        'runtime'=>[null, null],
        'public/upload'=>[null, null],
        'public/export'=>[null, null],
        'public/qrcode'=>[null, null],
        'public/import'=>[null, null]
    ];
    protected $_functionResult = [
        'curl'=>[null, null]
    ];
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
            $this->checkServer();
            $this->checkExtension();
            $this->checkPrivilege();
            $this->assign([
                '_serverResult'=>$this->_serverResult,
                '_extensionResult'=>$this->_extensionResult,
                '_privilegeResult'=>$this->_privilegeResult
            ]);
            $serverResult = array_all(function($item){
                return empty($item[1])?false:true;
            }, $this->_serverResult);
            $extensionResult = array_all(function($item){
                return empty($item[1])?false:true;
            }, $this->_extensionResult);
            $privilegeResult = array_all(function($item){
                return empty($item[1])?false:true;
            }, $this->_privilegeResult);
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
         try{
            foreach($formData as $key=>&$value){
                $value = htmlspecialchars_decode($value);
            }
            Config::set('database', array_merge(Config::get('database'), [
                // 服务器地址
                'hostname' => $formData['hostname'],
                // 数据库名
                'database' => $formData['database'],
                // 数据库用户名
                'username' => $formData['username'],
                // 数据库密码
                'password' => $formData['password'],
                // 数据库连接端口
                'hostport' => $formData['hostport'],
            ]));
            Db::connect();
            $sqlFile = ROOT_PATH . 'db' . DS . 'db.sql';
            if(!file_exists($sqlFile)){
                exception("初始化sql文件不存在");
            }
            $templine = '';
            $lines = file($sqlFile);
            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    Db::execute($templine);
                    $templine = '';
                }
            }
            //创建配置文件
            $config = [
                'hostname'        => $formData['hostname'],
                'database'        => $formData['database'],
                'username'        => $formData['username'],
                'password'        => $formData['password'],
                'hostport'        => $formData['hostport'],
            ];
            $configContent = "<?php\r\nreturn ";
            $configContent .= var_export($config, true);
            $configContent .= ";";
            $result = file_put_contents(ROOT_PATH . "local" . DS . "database.php", $configContent);
            if(!$result){
                exception("数据库配置文件写入失败");
            }
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
    protected function checkServer(){
        global $_SERVER;
        //server
        $this->_serverResult['server'][0] = $_SERVER['SERVER_SOFTWARE'];
        $this->_serverResult['server'][1] = true;
        //phpver
        $this->_serverResult['phpver'][0] = PHP_VERSION;
        if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
            $this->_serverResult['phpver'][1] = true;
        } else {
            $this->_serverResult['phpver'][1] = false;
        }
        //path
        $this->_serverResult['path'][0] = ROOT_PATH;
        $this->_serverResult['path'][1] = true;
    }
    protected function checkExtension(){
        //curl
        if (function_exists("curl_version")) {
            $info = curl_version();
            $this->_extensionResult['curl'][0] = $info['version'];
            $this->_extensionResult['curl'][1] = true;
        }
        //zip
        if (class_exists('ZipArchive')) {
            $this->_extensionResult['zip'][0] = ' ';
            $this->_extensionResult['zip'][1] = true;
        }
        //fileinfo
        if (extension_loaded('fileinfo')) {
            $this->_extensionResult['fileinfo'][0] = ' ';
            $this->_extensionResult['fileinfo'][1] = true;
        }
        //gd2
        if (function_exists("gd_info")) {
            $info = gd_info();
            $this->_extensionResult['gd2'][0] = $info['GD Version'];
            $this->_extensionResult['gd2'][1] = true;
        }
        //mbstring
        if (function_exists("mb_language")) {
            $this->_extensionResult['mbstring'][0] = mb_language();
            $this->_extensionResult['mbstring'][1] = true;
        }
        //pdo_mysql, pdo_sqlite
        if (class_exists("PDO", false)) {
            if (extension_loaded('pdo_mysql')) {
                $this->_extensionResult['pdo_mysql'][0] = ' ';
                $this->_extensionResult['pdo_mysql'][1] = true;
            }
            if (extension_loaded('pdo_sqlite')) {
                $a = \PDO::getAvailableDrivers();
                $v = '';
                if (in_array('sqlite', $a)) {
                    $v .= 'sqlite3';
                }
                if (in_array('sqlite2', $a)) {
                    $v .= ' sqlite2';
                }
                $this->_extensionResult['pdo_sqlite'][0] = $v;
                $this->_extensionResult['pdo_sqlite'][1] = true;
            }
        }
        //openssl
        if (defined('OPENSSL_VERSION_TEXT')) {
            $this->_extensionResult['openssl'][0] = OPENSSL_VERSION_TEXT;
            $this->_extensionResult['openssl'][1] = true;
        }
    }
    protected function checkPrivilege(){
        foreach($this->_privilegeResult as $path=>&$result){
            $s = $this->GetFilePerms(ROOT_PATH . $path);
            $o = $this->GetFilePermsOct(ROOT_PATH . $path);
            $isWritable = is_writable(ROOT_PATH . $path);
            $result[0] = $s . ' | ' . $o;

            if (substr($s, 0, 1) == '-') {
                $result[1] = ($isWritable && (substr($s, 1, 1) == 'r' && substr($s, 2, 1) == 'w' && substr($s, 4, 1) == 'r' && substr($s, 7, 1) == 'r')) ? true : false;
            } else {
                $result[1] = ($isWritable && (substr($s, 1, 1) == 'r' && substr($s, 2, 1) == 'w' && substr($s, 3, 1) == 'x' && substr($s, 4, 1) == 'r' && substr($s, 7, 1) == 'r' && substr($s, 6, 1) == 'x' && substr($s, 9, 1) == 'x')) ? true : false;
            }
        }
    }
    /**
     * 获取文件权限.
     *
     * @param string $f 文件名
     *
     * @return string|null 返回文件权限，数值格式，如0644
     */
    private function GetFilePermsOct($f){
        if (!file_exists($f)) {
            return '';
        }
        return substr(sprintf('%o', fileperms($f)), -4);
    }

    /**
     * 获取文件权限.
     *
     * @param string $f 文件名
     *
     * @return string|null 返回文件权限，字符表达格式，如-rw-r--r--
     */
    private function GetFilePerms($f){
        if (!file_exists($f)) {
            return '';
        }

        $perms = fileperms($f);
        switch ($perms & 0xF000) {
            case 0xC000: // socket
                $info = 's';
                break;
            case 0xA000: // symbolic link
                $info = 'l';
                break;
            case 0x8000: // regular
                $info = '-';
                break;
            case 0x6000: // block special
                $info = 'b';
                break;
            case 0x4000: // directory
                $info = 'd';
                break;
            case 0x2000: // character special
                $info = 'c';
                break;
            case 0x1000: // FIFO pipe
                $info = 'p';
                break;
            default: // unknown
                $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

        // Other
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }
}
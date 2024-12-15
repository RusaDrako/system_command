<?php

namespace RusaDrako\system_command;

class system_command{
    /** @var int Не запускалась */
    const SC_STATUS__NOT_USE=0;
    /** @var int Выполнено */
    const SC_STATUS__OK=1;
    /** @var int Ошибка */
    const SC_STATUS__ERROR=2;

    /** @var string Команда */
    protected $command="";
    /** @var array Отчёт */
    protected $report=[];
    /** @var int Статус */
    protected $status=system_command::SC_STATUS__NOT_USE;

    protected $in_charset="IBM866";
    protected $out_charset="UTF-8";

    /**  */
    public function __construct($command, $in_charset=null, $out_charset=null){
        $this->command=$command;
        $this->in_charset=$in_charset?:$this->in_charset;
        $this->out_charset=$out_charset?:$this->out_charset;
    }

    /** Настройки перенаправления */
    public function run($time=30) {
        set_time_limit($time);
        exec($this->command, $this->report, $retval);
        if($retval){
            $this->status=system_command::SC_STATUS__OK;
        }else{
            $this->status=system_command::SC_STATUS__ERROR;
        }
        return $this;
    }

    /** Возвращает статус команды */
    public function getStatus() {
        return $this->status;
    }

    /** Возвращает результат команды */
    public function getReport() {
        return $this->report;
    }

    /** Возвращает результат команды (декодированный) */
    public function getReportDecode() {
        $report=[];
        foreach($this->getReport() as $v){
            $report[]=$this->decode_exec_report($v);
        }
        return $report;
    }

    public function decode_exec_report(string $str){
        return iconv($this->in_charset, $this->out_charset, $str);
    }
}

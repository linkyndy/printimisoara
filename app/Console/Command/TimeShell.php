<?php

class TimeShell extends AppShell {
	public $uses = array('Config', 'Time', 'Maintenance', 'StationLine');
	
	public function main(){
		$this->Config->getConfig();
		
		$times = $this->Time->fetchLineTimes();
		if ($times !== false) {
			CakeLog::write('cron', 'Cron job-ul <code>Time</code> a fost finalizat cu succes');
		} else {
			CakeLog::write('cron', 'Cron job-ul <code>Time</code> nu a putut fi finalizat. Verifica log-ul pentru mai multe detalii');
		}
	}
}

?>
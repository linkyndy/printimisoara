<?php

class TimeShell extends AppShell {
	public $uses = array('Config', 'Time', 'Maintenance', 'StationLine');
	
	public function main(){
		$this->Config->getConfig();
		
		// Get the time for an uncovered line,
		// any station
		for ($i = 1; $i <= 5; $i++) {
			$uncovered_lines = $this->Maintenance->get('uncovered_lines');
			if (!empty($uncovered_lines)) {
				$line_ids = explode(', ', $uncovered_lines);
				$station_line_id = $this->StationLine->field('id', array('line_id' => array_rand($line_ids)), 'rand()');
			} else {
				$station_line_id = $this->StationLine->field('id', array(), 'rand()');
			}
			
			if ($this->Time->fetchTimes($station_line_id)) {
				$times = $this->Time->saveTimes();
				if ($times !== false) {
					CakeLog::write('cron', 'Cron job-ul <code>Time</code> a fost finalizat cu succes');
				} else {
					CakeLog::write('cron', 'Cron job-ul <code>Time</code> nu a putut fi finalizat. Verifica log-ul pentru mai multe detalii');
				}
			} else {
				CakeLog::write('cron', 'Cron job-ul <code>Time</code> nu a putut fi finalizat. Verifica log-ul pentru mai multe detalii');
			}

			sleep(5);
		}
	}
}

?>
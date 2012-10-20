<?php

class MaintenanceShell extends AppShell {
	public $uses = array('Maintenance', 'Time');
	
	public function main(){
		$this->Maintenance->id = 1;
		
		// Compute the uncovered_lines key in
		// the maintenance table. Save 5 of the
		// most uncovered lines.
		$coverage = $this->Time->coverage();
		CakeLog::write('info', '1');
		$short_coverage = Set::classicExtract($coverage, '{n}.Line.id');
		CakeLog::write('info', '2');
		$sorted_coverage = Set::sort($short_coverage, '{n}.Line.coverage_score.general', 'asc');
		CakeLog::write('info', '3');
		$ids_coverage = array_slice($sorted_coverage, 0, 5);
		CakeLog::write('info', '4');
		$string_coverage = implode(',', $ids_coverage);
		CakeLog::write('info', '5');
		CakeLog::write('info', '6');
		CakeLog::write('info', $string_coverage);
		
		if ($this->Maintenance->saveField('uncovered_lines', $string_coverage)){
			CakeLog::write('cron', 'Cron job-ul <code>Maintenance</code> a fost finalizat cu succes');
		} else {
			CakeLog::write('cron', 'Cron job-ul <code>Maintenance</code> nu a putut fi finalizat. Verifica log-ul pentru mai multe detalii');
		}
	}
}

?>
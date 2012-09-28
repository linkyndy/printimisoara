<div class="modal fade" id="modal-<?php echo $id; ?>">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Raporteaza un bug</h3>
	</div>
	<div class="modal-body">
		<?php echo $this->Form->create('Bug', array('class' => 'form-vertical')); ?>
			<?php
				echo $this->Form->hidden('type', array('value' => $type));
				echo $this->Form->hidden('title', array('value' => $title));
				
				echo $title;
				
				echo $this->Form->input('bug', array('div' => 'control-group', 'label' => array('text' => 'Bug', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
			?>	
			<?php echo $this->Js->submit('Trimite', array(
				'url' => array('superuser' => true, 'controller' => 'bugs', 'action' => 'report'),
				'type' => 'json',
				'success' => '
					if(data === true){
						$("#modal-'.$id.' .modal-body").html("Bug-ul a fost raportat. Multumim!");	
					} else if(data === false){
						$("#modal-'.$id.' .modal-body").html("Eroare");	
					} else {
						$.each(data, function(field, error){
							$input = $("#modal-'.$id.' .modal-body #Bug" + field.charAt(0).toUpperCase() + field.slice(1));
							$input.after("<p class=\"help-block\">" + error + "</span>");
							$input.closest(".control-group").addClass("error");
						});	
					}
				',
				'div' => false
			)); ?>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="modal-footer">
		<a href="#" id="send" class="btn btn-primary">Trimite</a>
		<a href="#" class="btn" data-dismiss="modal">Renunta</a>
	</div>
</div>
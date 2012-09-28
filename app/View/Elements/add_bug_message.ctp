<?php echo $this->Form->create('BugMessage', array('url' => array('action' => 'add', $bug['Bug']['id']))); ?>
	<?php echo $this->Form->input('message', array('div' => 'control-group', 'label' => false, 'placeholder' => 'Răspunde la acest bug...', 'class' => 'span12', 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')))); ?>
	<?php /*echo $this->Js->submit('Posteaza', array(
		'url' => array('controller' => 'bug_messages', 'action' => 'add', $bug['Bug']['id']),
		'type' => 'json',
		'success' => '
			if(data.message){
				$("#messages").append("
					<tr>
						<td>
							<p>" + data.message.BugMessage.message + "</p>
							<small class=\'muted\'>
								Postat de " + data.message.User.username + " la " + data.message.BugMessage.created + "
							</small>
						</td>
					</tr>
				");
				$("#BugMessageMessage").val("");	
			} else if(data.errors){
				$.each(data.errors, function(field, error){
					$input = $("#BugMessage" + field.charAt(0).toUpperCase() + field.slice(1));
					$input.after("<p class=\"help-block\">" + error + "</span>");
					$input.closest(".control-group").addClass("error");
				});	
			} else {
				alert("Mesajul nu a putut fi postat din cauza unei erori.");
			}
		',
		'div' => false,
		'class' => 'btn btn-primary'
	));*/ ?>
	<?php echo $this->Form->button('Posteaza', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
<?php echo $this->Form->end(); ?>
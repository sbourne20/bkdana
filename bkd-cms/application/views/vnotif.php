<script type="text/javascript" src="<?php echo $this->config->item('template_uri'); ?>plugins/gritter/js/jquery.gritter.js"></script>
<?php if($this->session->userdata('message')): ?>
<?php
	$type = $this->session->userdata('message_type');
	$msg  = $this->session->userdata('message');
	if ($type == 'error') {
		$img   = $this->config->item('images_uri') . 'error_icon.png';
		$title = 'Oh snap!';
		$class = 'class_name: \'gritter-red\',';
	} elseif ($type == 'success') {
		$img = $this->config->item('images_uri') . 'success_icon.png';
		$title = 'Success!';
		$class = 'class_name: \'gritter-blue\',';
	} elseif ($type == 'warning') {
		$img = $this->config->item('images_uri') . 'warning_icon.png';
		$title = 'Heads up!';
		$class = '';
	} elseif ($type == 'info') {
		$img = $this->config->item('images_uri') . 'info_icon.png';
		$title = 'Well done!';
		$class = '';
	}

	$this->session->unset_userdata('message_type');
	$this->session->unset_userdata('message');
?>

<script type="text/javascript">
    $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: '<?php echo $title ?>',
        // (string | mandatory) the text inside the notification
        text: '<?php echo $msg ?>',
        // (string | optional) the image to display on the left
        image: '<?php echo $img ?>',
        // (bool | optional) if you want it to fade out on its own or just sit there
        sticky: false,
        // (int | optional) the time you want it to be alive for before fading out
        <?php echo $class ?> 
        time: '5000'
    });
</script>

<?php endif; ?>
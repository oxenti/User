<h3><?php echo sprintf(__('You requested a password change at ') . $serviceName);?></h3>
<p><?php echo __('Following the link below you can change your password:');?></p>
<p><?php
echo $this->Html->link(__('Click here to change your password'), $url . '/' . $code);?>
</p>
<p><?php echo sprintf(__('Verification code: %s'), $code);?></p>
<p><?php echo __("If you don't request this change, no action is required. Your password will remain the same until you don't activate this code.");?></p>
<p>
	<?php echo __('Best regards,');?><br/>
	<?= $serviceName ?>
</p>
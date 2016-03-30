<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php
$content = explode("\n", $content);

?>
<h3><?= __('Your e-mail at ') . $serviceName . __(' has been changed.')?></h3>
<p>
	<?= __('You changed you email.');?> <?= __('To ensure that this e-mail is valid, please follow this link:');?>
</p>
<p>
<?php
	echo $this->Html->link(__('Click here to verify'), $url . '/' . $code);
?>
</p>
<p><?= sprintf(__('Verification code: %s'), $code);?></p>
<p><?= __('Best regards');?></p>
<p><?= $serviceName ?></p>
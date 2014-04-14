<?php
/*** FILE_DIRECT_ACCESS_HEADER ***/
defined("GCORE_SITE") or die;
?>
<?php $this->Html->active_set('div'); ?>
<?php echo $this->Html->formSecStart('original_element'); ?>
<?php echo $this->Html->formLine('__wizard_'.$fdata['name'], $fdata); ?>
<?php echo $this->Html->formSecEnd(); ?>
<?php
/*** FILE_DIRECT_ACCESS_HEADER ***/
defined("GCORE_SITE") or die;
?>
<?php echo $this->Html->formStart(); ?>
<?php echo $this->Html->formSecStart(); ?>
<?php echo $this->Html->formLine('Page[params][ccfname]', array('type' => 'text', 'label' => 'Form name', 'class' => 'L')); ?>
<?php echo $this->Html->formLine('Page[params][ccfevent]', array('type' => 'text', 'label' => 'Form event', 'class' => 'L')); ?>
<?php echo $this->Html->formSecEnd(); ?>
<?php echo $this->Html->formEnd(); ?>
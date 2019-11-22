<?php

$this->load->model("setting/modification");
$old_mod = $this->model_setting_modification->getModificationByCode("ctppay");

if (isset($old_mod['modification_id'])) {
    $this->model_setting_modification->deleteModification($old_mod['modification_id']);
}

if (version_compare(VERSION, '3.0') < 0) {
    throw new Exception('The module is designed for version 3.x!');
}

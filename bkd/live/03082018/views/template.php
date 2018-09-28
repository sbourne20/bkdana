<?php
$this->load->view('common/header');
$this->load->view($this->config->item('template_frontend').'/'.$pages);
$this->load->view('common/footer');
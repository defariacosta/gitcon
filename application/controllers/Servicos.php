<?php 
defined('BASEPATH') OR exit('No	direct	script	access	allowed');

class Servicos extends CI_Controller{
    public function Servicos(){
        $this->load->view('commons/header');
        $this->load->view('servico');
        $this->load->view('commons/footer');
    }
}
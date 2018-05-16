<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contato extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->library(array('form_validation','session'));
        $this->load->helper('form');
    }

    public function FaleConosco(){
        $data['title'] = "LCI | Fale Conosco";
        $data['description'] = "LCI | Exercício de exemplo do capítulo 5 do livro CodeIgniter";
        $data['formErrors'] = null;

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('assunto', 'Assunto', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required|min_length[30]');

        if($this->form_validation->run() == FALSE){
            $data['formErrors'] = validation_errors();
        }else{
            $formData = $this->input->post();
            $emailStatus = $this->SendEmailToAdmin($formData['email'], $formData['nome'],"to@domain.com","To Name",
                            $formData['assunto'],$formData['mensagem'],$formData['email'],$formData['nome']);
            if($emailStatus){
                $this->session->set_flashdata('success_msg','Contato recebido com sucesso!');
            }else{
                $data['formErrors'] = "Desculpe não foi possível enviar seu contato, tenta mais tarde!";
            }
            
        }

        $this->load->view('commons/header');
        $this->load->view('fale-conosco', $data);
        $this->load->view('commons/footer');
    }

    public function TrabalheConosco(){
 
        $data['title'] = "LCI | Trabalhe Conosco";
        $data['description'] = "LCI | Exercício de exemplo do capítulo 5 do livro CodeIgniter";
        $data['formErrors'] = null;

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('telefone', 'Telefone', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('mensagem', 'Mensagem', 'trim|required|min_length[30]');

        
        if($this->form_validation->run() == FALSE){
            $data['formErrors'] = validation_errors();
        }else{
            $uploadCurriculo = $this->UploadFile('curriculo');
            $emailStatus = $this->SendEmailToAdmin($formData['email'], $formData['nome'],"to@domain.com","To Name",$formData['assunto'],$formData['mensagem'],$formData['email'],$formData['nome']);
            if($emailStatus){
                $this->session->set_flashdata('success_msg','Arquivo recebido com sucesso!');
            }else{
                $data['formErrors'] = "Desculpe não foi possível receber seu arquivo, tenta mais tarde!";
            }
            
        }
        
        $this->load->view('commons/header');
        $this->load->view('trabalhe-conosco', $data);
        $this->load->view('commons/footer');
    }

    private function SendEmailToAdmin($from, $fromName, $to, $toName, $subject, $message, $reply = NULL, $replyName = NULL){
        $this->load->library('email');

        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.seudominio.com.br'; //configuração de domínio
        $config['smtp_user'] = 'user@seudominio.com.br'; //configuração de usuário
        $config['smtp_pass'] = 'suasenha'; //Configuração da senha de acesso ao e-mail
        $config['newline'] = '\r\n';

        $this->email->initialize($config);
        $this->email>from($from, $fromName);
        $this->emailto($to, $toName);

        if($reply){
            $this->email->reply_to($reply, $replyName);
            $this->email->subject($subject);
            $this->email->message($message);

            if($this->email->send()){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }

    private function UploadFile($inputFileName){

        $this->load->library('upload');

        $path = "../curriculos";

        $config['upload_path'] = $path;
        $config['allowed_types'] = 'doc|docx|pdf|zip|rar';
        $config['max_size'] = '5120';
        $config['encrypt_name'] = TRUE;

        if(!is_dir($path)){
            mkdir ($path, 0777, $recursive = TRUE);
        }

        $this->upload->inicialize($config);

        if(!$this->upload->do_upload($inputFileName)){
            $data['error'] = TRUE;
            $data['message'] = $this->upload->display_errors();
        }else{
            $data['error'] = FALSE;
            $data['fileData'] = $this->upload-data();
        }

        return $data;
    }
}
?>
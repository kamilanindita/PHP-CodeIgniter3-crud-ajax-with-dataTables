<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Buku extends CI_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model','buku');
    }
 
    public function index()
    {
        $this->load->view('buku');
    }
 
    public function ajax_list()
    {
        $list = $this->buku->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $buku) {
            $no++;
            $row = array();
            $row['penulis'] = $buku->penulis;
            $row['judul'] = $buku->judul;
            $row['kota'] = $buku->kota;
            $row['penerbit'] = $buku->penerbit;
            $row['tahun'] = $buku->tahun;
 
            //add html for action
            $row['action'] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_buku('."'".$buku->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_buku('."'".$buku->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
         
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->buku->count_all(),
                        "recordsFiltered" => $this->buku->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->buku->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $data = array(
                'penulis' => $this->input->post('penulis'),
                'judul' => $this->input->post('judul'),
                'kota' => $this->input->post('kota'),
                'penerbit' => $this->input->post('penerbit'),
                'tahun' => $this->input->post('tahun'),
            );
        $insert = $this->buku->save($data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $this->_validate();
        $data = array(
                'penulis' => $this->input->post('penulis'),
                'judul' => $this->input->post('judul'),
                'kota' => $this->input->post('kota'),
                'penerbit' => $this->input->post('penerbit'),
                'tahun' => $this->input->post('tahun'),
            );
        $this->buku->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_delete($id)
    {
        $this->buku->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
 
 
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('penulis') == '')
        {
            $data['inputerror'][] = 'penulis';
            $data['error_string'][] = 'Penulis is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('judul') == '')
        {
            $data['inputerror'][] = 'judul';
            $data['error_string'][] = 'Judul is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('kota') == '')
        {
            $data['inputerror'][] = 'kota';
            $data['error_string'][] = 'Kota is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('penerbit') == '')
        {
            $data['inputerror'][] = 'penerbit';
            $data['error_string'][] = 'Penerbit is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('tahun') == '')
        {
            $data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Tahun is required';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
 
}
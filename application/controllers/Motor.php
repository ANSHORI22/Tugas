<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Motor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
    }

    //manajemen motor
    public function index()
    {
        $data['judul'] = 'Ubah Data motor';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['motor'] = $this->ModelMotor->getMotor()->result_array();
        $data['kategori'] = $this->ModelMotor->getKategori()->result_array();

        $this->form_validation->set_rules('nm_motor', 'Nama motor', 'required|min_length[3]', [
            'required' => 'Nama motor harus diisi',
            'min_length' => 'Nama motor terlalu pendek'
        ]);
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required', [
            'required' => 'Nama kategori harus diisi',
        ]);
        $this->form_validation->set_rules('pengarang', 'Kategori', 'required|min_length[3]', [
            'required' => 'Merk harus diisi',
            'min_length' => 'Merk terlalu pendek'
        ]);
        $this->form_validation->set_rules('penerbit', 'Merk', 'required|min_length[3]', [
            'required' => 'warna harus diisi',
            'min_length' => 'warna terlalu pendek'
        ]);
        $this->form_validation->set_rules('tahun', 'Tahun Terbit', 'required|min_length[3]|max_length[4]|numeric', [
            'required' => 'Tahun terbit harus diisi',
            'min_length' => 'Tahun terbit terlalu pendek',
            'max_length' => 'Tahun terbit terlalu panjang',
            'numeric' => 'Hanya boleh diisi angka'
        ]);
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric', [
            'required' => 'Stok harus diisi',
            'numeric' => 'Yang anda masukan bukan angka'
        ]);

        //konfigurasi sebelum gambar diupload
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1000';
        $config['file_name'] = 'img' . time();

        $this->load->library('upload', $config);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('motor/index', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data();
                $gambar = $image['file_name'];
            } else {
                $gambar = '';
            }

            $data = [
                'nm_motor' => $this->input->post('nm_motor', true),
                'id_kategori' => $this->input->post('id_kategori', true),
                'pengarang' => $this->input->post('merk', true),
                'penerbit' => $this->input->post('warna', true),
                'tahun' => $this->input->post('tahun', true),
                'stok' => $this->input->post('stok', true),
                'image' => $gambar
            ];

            $this->ModelMotor->simpanMotor($data);
            redirect('motor');
        }
    }

    public function hapusMotor()
    {
        $where = ['id' => $this->uri->segment(3)];
        $this->ModelMotor->hapusMotor($where);
        redirect('motor');
    }

    public function ubahMotor()
    {
        $data['judul'] = 'Ubah Data motor';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['motor'] = $this->ModelMotor->motorWhere(['id' => $this->uri->segment(3)])->result_array();
        $kategori = $this->ModelMotor->joinKategoriMotor(['motor.id' => $this->uri->segment(3)])->result_array();
        foreach ($kategori as $k) {
            $data['id'] = $k['id_kategori'];
            $data['k'] = $k['kategori'];
        }
        $data['kategori'] = $this->ModelMotor->getKategori()->result_array();

        $this->form_validation->set_rules('judul_motor', 'Judul motor', 'required|min_length[3]', [
            'required' => 'Judul motor harus diisi',
            'min_length' => 'Judul motor terlalu pendek'
        ]);
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required', [
            'required' => 'Nama pengarang harus diisi',
        ]);
        $this->form_validation->set_rules('pengarang', 'Nama Pengarang', 'required|min_length[3]', [
            'required' => 'Nama pengarang harus diisi',
            'min_length' => 'Nama pengarang terlalu pendek'
        ]);
        $this->form_validation->set_rules('penerbit', 'Nama Penerbit', 'required|min_length[3]', [
            'required' => 'Nama penerbit harus diisi',
            'min_length' => 'Nama penerbit terlalu pendek'
        ]);
        $this->form_validation->set_rules('tahun', 'Tahun Terbit', 'required|min_length[3]|max_length[4]|numeric', [
            'required' => 'Tahun terbit harus diisi',
            'min_length' => 'Tahun terbit terlalu pendek',
            'max_length' => 'Tahun terbit terlalu panjang',
            'numeric' => 'Hanya boleh diisi angka'
        ]);
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric', [
            'required' => 'Stok harus diisi',
            'numeric' => 'Yang anda masukan bukan angka'
        ]);

        //konfigurasi sebelum gambar diupload
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1000';
        $config['file_name'] = 'img' . time();

        //memuat atau memanggil library upload
        $this->load->library('upload', $config);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('motor/ubah_motor', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data();
                unlink('assets/img/upload/' . $this->input->post('old_pict', TRUE));
                $gambar = $image['file_name'];
            } else {
                $gambar = $this->input->post('old_pict', TRUE);
            }

            $data = [
                'nm_motor' => $this->input->post('nm-motor', true),
                'id_kategori' => $this->input->post('id_kategori', true),
                'merk' => $this->input->post('merk', true),
                'warna' => $this->input->post('warna', true),
                'tahun' => $this->input->post('tahun', true),
                'stok' => $this->input->post('stok', true),
                'image' => $gambar
            ];

            $this->ModelMotor->updatemotor($data, ['id' => $this->input->post('id')]);
            redirect('motor');
        }
    }

    //manajemen kategori
    public function kategori()
    {
        $data['judul'] = 'Kategori motor';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['kategori'] = $this->ModelMotor->getKategori()->result_array();

        $this->form_validation->set_rules('kategori', 'Kategori', 'required', [
            'required' => 'Judul motor harus diisi'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('motor/kategori', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'kategori' => $this->input->post('kategori', TRUE)
            ];

            $this->ModelMotor->simpanKategori($data);
            redirect('motor/kategori');
        }
    }

    public function ubahKategori()
    {
        $data['judul'] = 'Ubah Data Kategori';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['kategori'] = $this->ModelMotor->kategoriWhere(['id' => $this->uri->segment(3)])->result_array();


        $this->form_validation->set_rules('kategori', 'Nama Kategori', 'required|min_length[3]', [
            'required' => 'Nama Kategori harus diisi',
            'min_length' => 'Nama Kategori terlalu pendek'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('motor/ubah_kategori', $data);
            $this->load->view('templates/footer');
        } else {

            $data = [
                'kategori' => $this->input->post('kategori', true)
            ];

            $this->ModelMotor->updateKategori(['id' => $this->input->post('id')], $data);
            redirect('motor/kategori');
        }
    }

    public function hapusKategori()
    {
        $where = ['id' => $this->uri->segment(3)];
        $this->ModelMotor->hapusKategori($where);
        redirect('motor/kategori');
    }
}

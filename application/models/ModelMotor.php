<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelmotor extends CI_Model
{
    //manajemen motor
    public function getMotor()
    {
        return $this->db->get('motor');
    }

    public function motorWhere($where)
    {
        return $this->db->get_where('motor', $where);
    }

    public function simpanMotor($data = null)
    {
        $this->db->insert('motor',$data);
    }

    public function updatemotor($data = null, $where = null)
    {
        $this->db->update('motor', $data, $where);
    }

    public function hapusmotor($where = null)
    {
        $this->db->delete('motor', $where);
    }

    public function total($field, $where)
    {
        $this->db->select_sum($field);
        if(!empty($where) && count($where) > 0){
            $this->db->where($where);
        }
        $this->db->from('motor');
        return $this->db->get()->row($field);
    }
    
    //manajemen kategori
    public function getKategori()
    {
        return $this->db->get('kategori');
    }

    public function kategoriWhere($where)
    {
        return $this->db->get_where('kategori', $where);
    }

    public function simpanKategori($data = null)
    {
        $this->db->insert('kategori', $data);
    }

    public function hapusKategori($where = null)
    {
        $this->db->delete('kategori', $where);
    }

    public function updateKategori($where = null, $data = null)
    {
        $this->db->update('kategori', $data, $where);
    }

    //join
    public function joinKategoriMotor($where)
    {
        $this->db->select('motor.id_kategori,kategori.kategori');
        $this->db->from('motor');
        $this->db->join('kategori','kategori.id = motor.id_kategori');
        $this->db->where($where);
        return $this->db->get();
    }
}
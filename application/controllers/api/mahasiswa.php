<?php
// agar bisa konek ke Library api nya maka wajib menuliskan ini
use Restserver\Libraries\REST_Controller;
use Restserver\Libraries\REST_Controller_Definitions;

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mahasiswa extends CI_Controller {
    // agar bisa konek ke REST_Controller bawaan library
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }
    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('Mahasiswa_model', 'mahasiswa');
        // atur limit req api, bawaan library
        // per method diberi limit
        // per 1 key boleh konek kemethod apa dalam 1 jam hanya boleh 2 kali
        $this->methods['index_get']['limit'] = 140;
        // $this->methods['index_delete']['limit'] = 2;
    }

    // buat end poin, kita ingin minta data berarti requeat method nya get, gunakan index_get() - bawaan library
    // tampilkan semua data mahasiswa dalam bentuk json
    public function index_get()
    {
        // id di params yang dikirimkan client
        $id = $this->get('id');
        // cek di request method GET tidak ada params ID
        if ( $id === null ) {
            // maka ambil semua mahasiswa saja
            $mahasiswa = $this->mahasiswa->getMahasiswa();
        } else {
            // jika ada params ID yang dikirimkan client di method GET
            // maka kirimkan $id ke model nya
            $mahasiswa = $this->mahasiswa->getMahasiswa($id);
        }
        // var_dump($mahasiswa);
        // jika $mahasiswa ada isi nya
        if ( $mahasiswa ) {
            $this->set_response([
                'status' => true,
                'data' => $mahasiswa
            ], REST_Controller_Definitions::HTTP_OK);
        } 
        // jika kosong
        else {
            $this->set_response([
                'status' => false,
                'data' => 'id Not Found!'
            ], REST_Controller_Definitions::HTTP_NOT_FOUND);
        }
    }
    // agar client bisa menghapus data
    public function index_delete()
    {
        // hapus data, ambil id nya
        $id = $this->delete('id');
        // cek jika id nya tidak dikirimkan client
        if ( $id === null ){
            $this->set_response([
                'status' => false,
                'data' => 'provided an id'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
        // jika ada id nya
        else {
            // cek apakah id itu ada di database atau tidak
            // jika ada, maka hapus
            if ( $this->mahasiswa->deleteMahasiswa($id) > 0 ){
                //  ok
                $this->set_response([
                    'status' => true,
                    'data' => $id,
                    'message' => 'deleted.'
                ], REST_Controller_Definitions::HTTP_NO_CONTENT);
            } 
            // jika id tidak ada didatabase
            else {
                $this->set_response([
                    'status' => false,
                    'data' => 'id Not Found!'
                ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
            }
        }
    }
    // tambah data mahasiswa
    public function index_post()
    {
        // terima data yang sudah valid, validasinya diclient
        // values untuk diinsert ke database, PASTIKAN URUTANNYA SESUAI DENGAN DI TABEL
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan')
        ];
        // masukan data ke database
        // jika berhasil
        if ( $this->mahasiswa->createMahasiswa($data) > 0 ){
            // beri response
            $this->set_response([
                'status' => true,
                'data' => 'new mahasiswa has been created.'
            ], REST_Controller_Definitions::HTTP_CREATED);
        } 
        // jika gagal beri rsponse
        else {
            // 
            $this->set_response([
                'status' => false,
                'data' => 'failed to create new data!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }
    // ubah data mahasiswa
    public function index_put()
    {
        // perlu $id karna agar masuk ke where nya
        $id = $this->put('id');
        // terima data yang sudah valid, validasinya diclient
        // values untuk diinsert ke database, PASTIKAN URUTANNYA SESUAI DENGAN DI TABEL
        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan')
        ];
        // ubah data di database
        // jika berhasil
        if ( $this->mahasiswa->updateMahasiswa($data, $id) > 0 ){
            // beri response
            $this->set_response([
                'status' => true,
                'data' => 'data mahasiswa has been updated.'
            ], REST_Controller_Definitions::HTTP_NO_CONTENT);
        } 
        // jika gagal beri rsponse
        else {
            // 
            $this->set_response([
                'status' => false,
                'data' => 'failed to update data!'
            ], REST_Controller_Definitions::HTTP_BAD_REQUEST);
        }
    }


}
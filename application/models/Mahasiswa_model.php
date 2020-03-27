<?php
// 
class Mahasiswa_model extends CI_Model{
    // tampilkan semua data mahasiswa
    public function getMahasiswa($id = null) {
        // jika id null
        if ($id === null) {
            // kembalikan nilai menjadi array assoc
            return $this->db->get('mahasiswa')->result_array();
        } else {
            return $this->db->get_where('mahasiswa', ['id' => $id])->result_array();
        }
    }
    // hapus mahasiswa
    public function deleteMahasiswa($id)
    {
        // hapus berdasarkan id
        $this->db->delete('mahasiswa', ['id' => $id]);
        // affected_rows - ada berapa baris yang dipengaruhi di dalam tabel
        // kembalikan nilai 1 jika berhasil dan -1 jika gagal
        return $this->db->affected_rows();
    }
    // tambah data mahasiswa ke database
    public function createMahasiswa($data)
    {
        // insert data ke database
        $this->db->insert('mahasiswa', $data);

        // KURANG VALIDASI, USER TIDAK BISA MENGUBAH DATA MELALUI FORM INTERFACE(KARENA ADA VALIDASI DARI CI) TETAPI BISA MENGGUNAKAN POSTMANT

        // kembalikan nilai baris yang terpengaruhi
        return $this->db->affected_rows();
    }
    // ubah data mahasiswa di database
    public function updateMahasiswa($data, $id)
    {
        // update ke database berdasatkan id
        // update('namaTabel', $variabelValues, ['idFromTabel'] => $idFromClient)
        $this->db->update('mahasiswa', $data, ['id' => $id]);
        // kembalikan nilai baris yang terpengaruh, 1 jika berhasil dan -1jika gagal
        return $this->db->affected_rows();
    }

}
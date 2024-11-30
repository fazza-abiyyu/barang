<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');

        $login_session = $this->session->userdata('login_session');
        log_message('debug', 'Login session: ' . print_r($login_session, true));

        if (isset($login_session['user'])) {
            $this->user = $this->admin->get('user', ['id_user' => $login_session['user']]);
            log_message('debug', 'User data retrieved: ' . print_r($this->user, true));
        }
        
        if (!$this->user) {
            log_message('error', 'User not found or user data is null: ' . print_r($login_session, true));
            show_error('User not found', 404);
        }
    }

    public function index()
    {
        $data['title'] = "Profile";
        $data['user'] = $this->user;
        $this->template->load('templates/dashboard', 'profile/user', $data);
    }

    private function _validasi()
    {
        $username = $this->input->post('username', true);
        $email = $this->input->post('email', true);
        
        $uniq_username = isset($this->user['username']) && $this->user['username'] == $username ? '' : '|is_unique[user.username]';
        $uniq_email = isset($this->user['email']) && $this->user['email'] == $email ? '' : '|is_unique[user.email]';

        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $uniq_email);
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
    }

    private function _config()
    {
        $config['upload_path']      = "./assets/img/avatar";
        $config['allowed_types']    = 'gif|jpg|jpeg|png';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = '2048';

        $this->load->library('upload', $config);
    }

    public function setting()
    {
        $this->_validasi();
        $this->_config();
    
        if ($this->form_validation->run() == false) {
            $data['title'] = "Profile";
            $data['user'] = $this->user;
            $this->template->load('templates/dashboard', 'profile/setting', $data);
        } else {
            $input = $this->input->post(null, true);
            log_message('error', 'Data input: ' . print_r($input, true)); // Tambahkan log ini untuk cek data input
    
            if (empty($_FILES['foto']['name'])) {
                $update = $this->admin->update('user', 'id_user', $input['id_user'], $input);
                if ($update) {
                    set_pesan('Perubahan berhasil disimpan.');
                } else {
                    set_pesan('Perubahan tidak disimpan.');
                }
                redirect('profile/setting');
            } else {
                if ($this->upload->do_upload('foto') == false) {
                    echo $this->upload->display_errors();
                    die;
                } else {
                    $old_image = FCPATH . 'assets/img/avatar/' . $this->user['foto'];
                    log_message('error', 'Jalur file untuk dihapus: ' . $old_image); // Tambahkan log ini
    
                    if ($this->user['foto'] != 'user.png') {
                        if (file_exists($old_image)) {
                            if (!unlink($old_image)) {
                                log_message('error', 'Gagal menghapus file: ' . $old_image . ' - ' . print_r(error_get_last(), true));
                                set_pesan('Gagal hapus foto lama.');
                            }
                        } else {
                            log_message('error', 'File tidak ditemukan: ' . $old_image);
                            set_pesan('File tidak ditemukan.');
                        }
                    }
    
                    $input['foto'] = $this->upload->data('file_name');
                    $update = $this->admin->update('user', 'id_user', $input['id_user'], $input);
                    if ($update) {
                        set_pesan('Perubahan berhasil disimpan.');
                    } else {
                        set_pesan('Gagal menyimpan perubahan.');
                    }
                    redirect('profile/setting');
                }
            }
        }
    }

    public function ubahpassword()
    {
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|trim|min_length[3]|differs[password_lama]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'matches[password_baru]');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Ubah Password";
            $this->template->load('templates/dashboard', 'profile/ubahpassword', $data);
        } else {
            $input = $this->input->post(null, true);
            if (isset($this->user['password']) && password_verify($input['password_lama'], $this->user['password'])) {
                $new_pass = ['password' => password_hash($input['password_baru'], PASSWORD_DEFAULT)];
                $query = $this->admin->update('user', 'id_user', $this->user['id_user'], $new_pass);

                if ($query) {
                    set_pesan('Password berhasil diubah.');
                } else {
                    set_pesan('Gagal ubah password.', false);
                }
            } else {
                set_pesan('Password lama salah.', false);
            }
            redirect('profile/ubahpassword');
        }
    } 
}

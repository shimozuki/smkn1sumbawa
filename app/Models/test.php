<?phpdefined('BASEPATH') OR exit('No direct script access allowed');
class Pembelian extends CI_Controller {		
    function __construct() 
    {		parent:: __construct();		
        $this->load->model('M_pembelian');		
        $this->load->model('M_inventory');		
        $this->load->model('M_penjualan');	
    }	
    public function index() 
    {		
        if($this->session->has_userdata('username')) 
        {		$where = array(		'sess_id' => session_id()		 );		
            $data = array(		'nonota' 	=> $this->getnonota(),		
            'brg' => $this->M_pembelian->tampil_brg()->result(),		
            'sidebar'	=> "Pembelian",		
            'kategori'	=> $this->M_inventory->tampil_kategori()->result(),		'menu'		=> "active",		 );		
            $this->load->view('template/header',$data);		$this->load->view('template/sidebar',$data);		
            $this->load->view('pembelian',$data);		
            $this->load->view('template/footer');	
        } else {		$this->load->view('login');	}}	
        public function view_beli() 
        {		$where = array(		'sess_id' => session_id()		);		
            $data = array('cart' => $this->M_pembelian->tampil_cart($where,'tbl_temp_beli')->result(),			
            'total' 	=> $this->get_total(),		);		
            $this->load->view('v_beli',$data);	}	
            public function search_brg() 
            { 		$data = array(			'barang' => $this->M_inventory->tampil()->result(),			
                'kategori'	=> $this->M_inventory->tampil_kategori()->result()		);		
                $this->load->view('v_cr_brg_beli',$data);	}	
            public function getnonota() 
            {		
                $cek 	= $this->M_pembelian->getnonota()->num_rows();		
                $last 	= $this->M_pembelian->notaterakhir()->result();		
                foreach ($last as $l) {			$lastnota = $l->no_beli;		}		
                if($cek==0) {			$nomor 	= '1000';		} else {			$nomor 	= $l->no_beli + 1;		}			return $nomor;		
            }	
            public function add_cart() {
//CEK BARANG SUDAH DI ORDER ATAU BELUM berdasarkan KODE BARANG		$where = array(		'id' 		=> $this->input->post('kd_brg',TRUE),		'sess_id'	=> session_id()		);		$cek = $this->M_pembelian->tampil_cart($where,'tbl_temp_beli')->num_rows();		$data = array(			'id' 		=> $this->input->post('kd_brg',TRUE),			'qty' 		=> $this->input->post('qty',TRUE),			'price' 	=> str_replace(".", "", $this->input->post('harga',TRUE)),			'name' 		=> $this->input->post('nama',TRUE),			'subtotal'	=> $this->input->post('qty',TRUE) * str_replace(".", "", $this->input->post('harga',TRUE)),			'sess_id' 	=> session_id()			);		//jika kosong 		if($cek==0) {			$this->db->insert('tbl_temp_beli',$data);		} else { // jika ada 		$this->db->set('qty','qty+'.$data['qty'],FALSE);		$this->db->set('subtotal','price*qty',FALSE);		$this->db->where('id',$data['id']);		$this->db->update('tbl_temp_beli');		}		// redirect('pembelian');	}	public function del_cart($id) {		$where = array(			'no' 		=> $id		);		$this->M_pembelian->delcart($where,'tbl_temp_beli');		redirect('pembelian');	}	public function add_so($where) {		//INPUT STOK MASUK 		$beli 	= $this->M_pembelian->tampil_cart($where,'tbl_temp_beli')->result();		foreach ($beli as $b) {		$data[] = array(			'id_brg' 	 => $b->id,			'nama_brg'	 => $b->name,			'beli' 	 	 => $b->qty,			'tanggal'	 => date('Y-m-d')		);				}		$this->db->insert_batch('tbl_so',$data);	}	public function del_pers_akhir() {		$this->M_penjualan->del_pers_akhir();	}	public function savecart() {		$this->del_pers_akhir();		$where = array(			'sess_id' => session_id(), 		);		$beli 	= $this->M_pembelian->tampil_cart($where,'tbl_temp_beli')->result();		//SAVE DETAIL pembelian		foreach ($beli as $b) {		$data[] = array(			'nonota' 	 => $this->getnonota(),			'id_brg' 	 => $b->id,			'nama_brg'	 => $b->name,			'jml_brg' 	 => $b->qty,			'harga_brg'	 => $b->price, 		);				}		$this->M_pembelian->savecart($data,'tbl_detail_pembelian');		$this->add_so($where);		// SAVE ORDER TO DATABASE 		$order = array(		'no_beli' 	=> $this->getnonota(),		'total'	 	=> $this->get_total(),		'waktu'		=> date('Y-m-d H:i:s'),		'tanggal'	=> date('Y-m-d')		);			$this->updatestok();		$this->M_pembelian->saveorder($order,'tbl_pembelian');		$this->delallcart(); 		redirect('pembelian/success');	}	public function b_tunai() {		$where = array(		'sess_id'	=> session_id()		);		$cek 	= $this->M_pembelian->cek_beli($where,'tbl_temp_beli')->num_rows();		if($cek>0) { 			$this->save_ju_tunai();			$this->savecart();			} else {				$data = array(					'content' => 'Anda belum melakukan pembelian',					'url' 	  => 'pembelian'				);				$this->load->view('notification/error',$data);			}	}	public function b_kredit() {		$where = array(		'sess_id'	=> session_id()		);		$cek 	= $this->M_pembelian->cek_beli($where,'tbl_temp_beli')->num_rows();		if ($cek>0) { 			$this->save_ju_kredit();			$this->savecart();		} else {		$data = array(			'content' => 'Anda belum melakukan pembelian',			'url' 	  => 'pembelian'		);		$this->load->view('notification/error',$data);		}	}	public function get_total() {		$this->db->select_sum('subtotal');		$this->db->from('tbl_temp_beli');		$this->db->where('sess_id',session_id());		$query = $this->db->get();		return $query->row()->subtotal;	}	public function delallcart() {		$where = array(		'sess_id' => session_id() 		);		$this->M_pembelian->delcart($where,'tbl_temp_beli');	}	public function updatestok() {	$where = array(	'sess_id' => session_id()	);	$tampil = $this->M_pembelian->tampil_cart($where,'tbl_temp_beli')->result();	foreach ($tampil as $t) {		$stok 	= $t->qty;		$id 	= $t->id;		$this->M_pembelian->updatestock($stok,$id,'tbl_barang');	}	}	public function updatecart() {		$data = array(		'id' 	=> $this->input->post('id',TRUE),		'qty'	=> $this->input->post('editval',TRUE) 		);		$this->db->set('qty',$data['qty']);		$this->db->set('subtotal','qty*price',FALSE);		$this->db->where('sess_id',session_id());		$this->db->where('id',$data['id']);		$this->db->update('tbl_temp_beli');	}	public function success() {		$this->load->view('notification/success');		echo "<meta http-equiv='refresh' content='2,URL=index'>";	}	public function save_ju_tunai() {		$where	= date('Y-m-d');		$cek 	= $this->M_pembelian->cek_ju($where,'tbl_jurnal_umum')->num_rows();		if($cek==0) { 		$ju 	= array(			'tanggal' 		=>	date('Y-m-d'),			'nama_perkiraan'=> 	'Pembelian',			'debet' 		=>	$this->get_total(),			'kredit'		=>	0,			'keterangan'	=> 'Tunai'		);		$this->db->insert('tbl_jurnal_umum',$ju);		$ju = array(			'tanggal' 		=>	date('Y-m-d'),			'nama_perkiraan'=> 	'Kas',			'kredit' 		=>	$this->get_total(),			'debet'			=>	0,			'keterangan' 	=> 	'Pembelian'		 );		$this->db->insert('tbl_jurnal_umum',$ju);	} else {		//UPDATE FOR KAS KREDIT		$where = array(			'keterangan' 	=> 'Pembelian',			'nama_perkiraan'=> 'Kas'		);		$this->db->set('kredit','kredit+'.$this->get_total(),FALSE);		$this->db->where($where);		$this->db->update('tbl_jurnal_umum');		//UPDATE FOR PEMBELIAN DEBET		$this->db->set('debet','debet+'.$this->get_total(),FALSE);		$this->db->where('nama_perkiraan','Pembelian');		$this->db->where('keterangan','Tunai');		$this->db->update('tbl_jurnal_umum');	}	} 	public function save_ju_kredit() {		$ju = array(			'tanggal' 		=>	date('Y-m-d'),			'nama_perkiraan'=> 	'Pembelian',			'debet' 		=>	$this->get_total(),			'kredit'		=>	0,		);		$this->db->insert('tbl_jurnal_umum',$ju);		$ju = array(			'tanggal' 		=>	date('Y-m-d'),			'nama_perkiraan'=> 	'Hutang Dagang',			'kredit' 		=>	$this->input->post('dp',TRUE),			'debet'			=>	0,			'keterangan' 	=> 	$this->input->post('keterangan')		 );		$this->db->insert('tbl_jurnal_umum',$ju);		$ju = array(			'tanggal' 		=>	date('Y-m-d'),			'nama_perkiraan'=> 	'Kas',			'kredit' 		=>	$this->get_total() - $this->input->post('dp'),			'debet'			=>	0,			'keterangan' 	=> 	'Hutang Dagang '.$this->input->post('keterangan',TRUE)		 );		$this->db->insert('tbl_jurnal_umum',$ju);	}}
            }	
        }
    }	
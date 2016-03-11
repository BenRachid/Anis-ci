<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ooredoo extends CI_Controller {

	private function log_action ($performer, $action)
	{
		
		
	}
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$config=array();
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.googlemail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'ooredootest12345@gmail.com';
		$config['smtp_pass'] = '@123456789a@';
		$config['charset'] = 'iso-8859-1';
		$this->load->library('email',$config);
		$this->load->library(array('session'));
		$this->load->model('user_model');
	}

	public function _example_output($output = null)
	{
		$this->load->view('ooredoo.php',$output);
	}

	public function index ()
	{  
	redirect('login');
	//redirect('ooredoo/user_management');
	}

	

	public function pdv_management ()
	{ if ($_SESSION ["user_type"])
		{ 
	$this->load->view('header');
	//var_dump ($_SESSION);
	//var_dump($_SESSION);
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('nouveaupdv');
			$crud->set_subject('Point de Vente');
			$crud->required_fields('pdv','raison_sociale', 'type_pdv','msisdn', 'wilaya_pdv', 'commune_pdv', 'email_pdv', 'code_vendeur', 'code_vendeur', 'Statut','canalVente','nom','prenom','rcnum');
			$crud->unique_fields('pdv','msisdn', 'code_vendeur','email_pdv');
			$crud->columns('pdv','raison_sociale', 'type_pdv', 'adresse_pdv', 'wilaya_pdv', 'commune_pdv', 'msisdn', 'autre_telephone_pdv', 'email_pdv', 'code_vendeur','canalVente','Statut','date_creation', 'date_modification','nom','prenom','rcnum');
			$crud->fields('pdv','raison_sociale', 'type_pdv', 'adresse_pdv', 'wilaya_pdv', 'commune_pdv', 'msisdn', 'autre_telephone_pdv', 'email_pdv','code_vendeur','canalVente', 'Statut','nom','prenom','rcnum');
			$crud->unset_texteditor('adresse_pdv');
		
			
			$crud->set_rules('email_pdv','Email','valid_email');			
			$crud->display_as('pdv','PDV');
			$crud->display_as('raison_sociale','Raison sociale');
			$crud->display_as('msisdn','MSISDNs Storm');
			$crud->display_as('type_pdv','Type PDV');
			$crud->display_as('adresse_pdv','Adresse');
			$crud->display_as('autre_telephone_pdv','T&eacute;lephone PDV');
			$crud->display_as('email_pdv','Email');
			$crud->display_as('canalVente','Canal de vente');
			$crud->display_as('Statut','Statut PDV');
			$crud->display_as('commune_pdv','Commune');
			$crud->display_as('code_vendeur','Code vendeur');
			$crud->display_as('wilaya_pdv','Wilaya');	
			$crud->display_as('nom','Nom');
			$crud->display_as('prenom','Prenom');
			$crud->display_as('rcnum','Registre commerce');			
			$crud->display_as('pdv','PDV');
			$crud->callback_after_insert(array($this, 'log_user_after_insert'));
			$crud->callback_after_update(array($this, 'log_user_after_update'));
			$crud->callback_before_update(array($this,'encrypt_password_callback'));
			$crud->field_type('password','hidden');
			$crud->field_type('wilaya_pdv','dropdown', $this->get_Wilaya(),'Adrar');
			$crud->field_type('Statut','dropdown',array('Inactif'=>'Inactif','Actif' => 'Actif'),'Inactif');
			
			if((! isset($_SESSION['username']) || $_SESSION['logged_in'] === False) ){
			$crud->unset_operations();
			}
			
			$crud->unset_export();
			$crud->unset_print();
			$output = $crud->render();
			$this->_example_output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
		}
	else {
		redirect ('/');
	}
	}

	
	
	public function user_management ()
	{ $this->load->view('header');
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('users2');
			$crud->set_subject('un utilisateur');
			$crud->required_fields('username', 'email','password','user_type');
			$crud->unique_fields('username', 'email','password');
			$crud->columns('username', 'email','user_type');
			$crud->fields('username', 'email','password','user_type');
			$crud->field_type('user_type','dropdown',array('User'=>'User','Admin' => 'Admin'),'User');
			//$crud->unset_texteditor('adresse_pdv');
		
			
			$crud->set_rules('email','Email','valid_email');			
			//$crud->display_as('id','ID');
			$crud->display_as('username','Username');
			$crud->display_as('password','Password');
			$crud->display_as('user_type', 'Type');
			
			
			$crud->callback_after_insert(array($this, 'log_user2_after_insert'));
			//$crud->callback_after_update(array($this, 'log_user_after_update'));
			$crud->callback_before_update(array($this,'encrypt_password_callback'));
	        //$crud->field_type('password','hidden');
			
			
			if((! isset($_SESSION['username']) || $_SESSION['logged_in'] === False) ){
			$crud->unset_operations();
			}
			$crud->unset_export();
			$crud->unset_print();
			$output = $crud->render();
			$this->_example_output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	function log_user2_after_insert($post_array,$primary_key)
	{
		$password = $post_array['password'];
		$user_logs_update = array(
			
			"date_creation" => date('Y-m-d H:i:s'),
			"date_modification" => date('Y-m-d H:i:s'),
			"password" => $this->user_model->hash_login($password)
			
			
		);
		$this->db->update('users2',$user_logs_update,array('id' => $primary_key));
		
		//$this->load->database();
		//$this->db->get_where('nouveaupdv',array('pdv' => $primary_key));
		$data = array();
		$this->db->where('id',$primary_key);
		$this->db->limit(1);
		$Q = $this->db->get('users2');
		if ($Q->num_rows() < 1){
			show_Error("No Result");
			return false;
		}
		$data = $Q->row_array();
		$this->send_Create_Mail($primary_key, $data['email'],$password);
		$Q->free_result();
		return true;
	}
	
	function encrypt_password_callback($post_array, $primary_key) {
		$this->load->library('encrypt');
		//Encrypt password only if is not empty. Else don't change the password to an empty field
		if(!empty($post_array['password']))
		{
			$key = 'super-secret-key';
			$post_array['password'] = $this->encrypt->encode($post_array['password'], $key);
		}
		else
		{
			unset($post_array['password']);
		}
		return $post_array;
	}
	public function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}
	
	function log_user_after_insert($post_array,$primary_key)
	{
		//$_SESSION['dumping']=$post_array;
		$password=$this->generatePwd();
		$user_logs_update = array(
			
			"date_creation" => date('Y-m-d H:i:s'),
			"date_modification" => date('Y-m-d H:i:s'),
			"password" => $password,
			
		);
		
		$this->db->update('nouveaupdv',$user_logs_update,array('pdv' => $post_array['pdv']));
		
		//$this->load->database();
		//$this->db->get_where('nouveaupdv',array('pdv' => $primary_key));
		$data = array();
		

		$hist = array(
			'action'   => $_SESSION['username']." a cree le compte ".$post_array['pdv'],
			'performer'   => 0,
			'date' => date('Y-m-j H:i:s')
		);
		
		$this->send_Create_Mail($post_array['pdv'], $post_array['email_pdv'],$password);
		$this->db->insert('logs', $hist);
		return true;
	}
	function log_user_after_update($post_array,$primary_key)
	{
		$user_logs_update = array(
			"pdv" => $primary_key,
			"date_modification" => date('Y-m-d H:i:s')
		);
		$this->db->update('nouveaupdv',$user_logs_update,array('pdv' => $primary_key));
		return true;
	}
	
	protected function generatePwd(){
		$chars = "azertyuiopqsdfghjklmwxcvbn0123456789";
		$lenght = strlen($chars);
		$chars = str_split($chars,1);
		$pwd = "";
		for($i=0;$i<9;$i++){
			shuffle($chars);
			$pwd .= $chars[rand(0,($lenght-1))];
		}
		return $pwd;
	}
	protected function configure_Mail($from, $from_code, $to, $subject, $message, $alt)
	{
		$this->email->set_newline("\r\n");
		$this->email->from($from, $from_code);
		$this->email->to($to); 
		//$this->email->cc('Manager@Ooredoo.com'); 
		//$this->email->bcc('them@their-example.com'); 
		$this->email->subject($subject);
		$this->email->message($message);	
		$this->email->set_alt_message($alt);
		if(!$this->email->send())
		{
			return false;
		}
		return true;
	}
	protected function send_Create_Mail($pdv, $email, $password){
		$message="Bonjour,\r\nVotre Compte d'accés au point de vente est actif, votre mot de passe est: ".$password."\r\nBien à vous\nOoredoo.";
		$alt='Bonjour, votre mot de passe pour accéder au point de vente est : '.$password;
		$this->configure_Mail('ooredoo@gmail.com', 'Ooredoo',$email,'Activation de compte PDV',$message, $alt);
	}
	protected function send_Reset_Pdv($pdv, $email, $password){
		$message="Bonjour,\r\L'accés au point de vente a été réenitialisé, votre nouveau mot de passe est: ".$password."\r\n Bien à vous\nOoredoo.";
		$alt='Bonjour, votre mot de passe pour accéder au point de vente est : '.$password;
		$this->configure_Mail('ooredoo@gmail.com', 'Ooredoo',$email,'Réactivation du compte PDV',$message, $alt);
	}
	protected function send_Reset_Password($email, $password){
		$message="Bonjour,\r\nVotre nouveau mot de passe est: ".$password."\r\nBien à vous\nOoredoo.";
		$alt='Bonjour, votre mot de passe pour accéder a l\'application est : '.$password;
		$this->configure_Mail('ooredoo@gmail.com', 'Ooredoo',$email,'Réinitialisation',$message, $alt);
	}
	// public function date_Start($value, $row)
	// {
		// $this->load->helper('date');
		// if(!empty($post_array['password']))
		// {}
		// $value= mdate('%d/%m/%Y - %h:%i %a',now());
		// echo $value;
		// return $value;
	 // }
	
	function get_Wilaya(){
		$i=0;
		$arr= array();
		$arr ["Adrar"]="Adrar";
		$arr ["Chlef"]="Chlef";
		$arr ["Laghouat"]="Laghouat";
		$arr ["Oum El Bouaghi"]="Oum El Bouaghi";
		$arr ["Batna"]="Batna";
		$arr ["B&eacute;ja&Iuml;a"]="B&eacute;ja&Iuml;a";
		$arr ["Biskra"]="Biskra";
		$arr ["B&eacute;char"]="B&eacute;char";
		$arr ["Blida"]="Blida";
		$arr ["Bouira"]="Bouira";
		$arr ["Tamanrasset"]="Tamanrasset";
		$arr ["T&eacute;bessa"]="T&eacute;bessa";
		$arr ["Tlemcen"]="Tlemcen";
		$arr ["Tiaret"]="Tiaret";
		$arr ["Tizi Ouzou"]="Tizi Ouzou";
		$arr ["Alger"]="Alger";
		$arr ["Djelfa"]="Djelfa";
		$arr ["Jijel"]="Jijel";
		$arr ["S&eacute;tif"]="S&eacute;tif";
		$arr ["Sa&Iuml;da"]="Sa&Iuml;da";
		$arr ["Skikda"]="Skikda";
		$arr ["Sidi Bel Abb&egrave;s"]="Sidi Bel Abb&egrave;s";
		$arr ["Annaba"]="Annaba";
		$arr ["Guelma"]="Guelma";
		$arr ["Constantine"]="Constantine";
		$arr ["M&eacute;d&eacute;a"]="M&eacute;d&eacute;a";
		$arr ["Mostaganem"]="Mostaganem";
		$arr ["M'Sila"]="M'Sila";
		$arr ["Mascara"]="Mascara";
		$arr ["Ouargla"]="Ouargla";
		$arr ["Oran"]="Oran";
		$arr ["El Bayadh"]="El Bayadh";
		$arr ["Illizi"]="Illizi";
		$arr ["Bordj Bou Arreridj"]="Bordj Bou Arreridj";
		$arr ["Boumerd&egrave;s"]="Boumerd&egrave;s";
		$arr ["El Tarf"]="El Tarf";
		$arr ["Tindouf"]="Tindouf";
		$arr ["Tissemsilt"]="Tissemsilt";
		$arr ["El Oued"]="El Oued";
		$arr ["Khenchela"]="Khenchela";
		$arr ["Souk Ahras"]="Souk Ahras";
		$arr ["Tipaza"]="Tipaza";
		$arr ["Mila"]="Mila";
		$arr ["A&Iuml;n Defla"]="A&Iuml;n Defla";
		$arr ["Na&acirc;ma"]="Na&acirc;ma";
		$arr ["A&Iuml;n T&eacute;mouchent"]="A&Iuml;n T&eacute;mouchent";
		$arr ["Gharda&Iuml;a"]="Gharda&Iuml;a";
		$arr ["Relizane"]="Relizane";
		return $arr;
	}

/**
	 * register function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register() {
		
		// create the data object
		$data = new stdClass();
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');
		
		if ($this->form_validation->run() === false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('user/register/register', $data);
			$this->load->view('footer');
			
		} else {
			
			// set variables from the form
			$username = $this->input->post('username');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			
			if ($this->user_model->create_user($username, $email, $password)) {
				
				// user creation ok
				$this->load->view('header');
				$this->load->view('user/register/register_success', $data);
				$this->load->view('footer');
				
			} else {
				
				// user creation failed, this should never happen
				$data->error = 'There was a problem creating your new account. Please try again.';
				
				// send error to the view
				$this->load->view('header');
				$this->load->view('user/register/register', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
		
	/**
	 * login function.
	 * 
	 * @access public
	 * @return void
	 */
	public function login() {
		
		// create the data object
		$data = new stdClass();
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('user/login/login');
			$this->load->view('footer');
			
		} else {
			
			// set variables from the form
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			if ($this->user_model->resolve_user_login($username, $password)) {
				
				$user_id = $this->user_model->get_user_id_from_username($username);
				$user    = $this->user_model->get_user($user_id);
		
				
				// set session user datas
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;
				$_SESSION['user_type']     = ($user->user_type==='Admin');
				
				
				redirect('/ooredoo/pdv_management');
				
				// user login ok
				// $this->load->view('header');
				// $this->load->view('user/login/index.php', $data);
				// $this->load->view('footer');
				
			} else {
				
				// login failed
				$data->error = 'Wrong username or password.';
				
				// send error to the view
				$this->load->view('header');
				$this->load->view('user/login/login', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
	
	/**
	 * logout function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logout() {
		
		// create the data object
		$data = new stdClass();
		
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			
			// remove session datas
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
			
			// user logout ok
			$this->load->view('header');
			$this->load->view('user/logout/logout_success', $data);
			$this->load->view('footer');
			
			
		} else {
			
			// there user was not logged in, we cannot logged him out,
			// redirect him to site root
			redirect('/');
			
		}
		
	}
	
	public function forgot() {
		// create the data object
		$data = Array();
		
		if (isset($_GET['info'])) {
               $data['info'] = $_GET['info'];
              }
		if (isset($_GET['error'])) {
              $data['error'] = $_GET['error'];
              }
		$this->load->view('header');
		$this->load->view('login-forget',$data);
	}
		public function doforgot()
	{ 
	
		$email= $_POST['email'];
		//$q = $this->db->query("select * from users where email='" . $email . "'");
		$this->db->select('id,email'); 
        $this->db->where('email', $email); 
		$q= $this->db->get('users2');
		$row = $q->row();

        if (isset($row))
       // if ($q->num_rows > 0)
		   {
            $r = $q->result();
            $user=$r[0];
			$this->resetpassword($user);
			$info= "Password has been reset and has been sent to email id: ". $email;
			redirect('ooredoo/forgot?info=' . $info, 'refresh');
        }
		$error= "The email id you entered not found on our database ";
		//var_dump ( $q );
		redirect('ooredoo/forgot?error=' . $error, 'refresh');
		
		
	} 
	 function resetpassword($user)
	{
		date_default_timezone_set('GMT');
		$this->load->helper('string');
		$password = $this->generatePwd();
		 //var_dump ( $this );
		$this->user_model->reset_password($user->id, $password);
		$this->send_Reset_Password($user->email, $password);
		//$this->email->from('cantreply@youdomain.com', 'Your name');
		//$this->email->to($user->email); 	
		//$this->email->subject('Password reset');
		//$this->email->message('You have requested the new password, Here is you new password:'. $password);	
		//$this->email->send();
	} 
	public function reinit()
	{  

	$data = Array();
		
		if (isset($_GET['info'])) {
               $data['info'] = $_GET['info'];
              }
		if (isset($_GET['error'])) {
              $data['error'] = $_GET['error'];
              }
		$this->load->view('header');
		$this->load->view('reinit',$data);
	
	}
	
	public function doreinit()
	{ 
	
		$email= $_POST['email'];
		//$q = $this->db->query("select * from users where email_pdv='" . $email . "'");
		$this->db->select('pdv,email_pdv'); 
        $this->db->where('email_pdv', $email); 
		$q= $this->db->get('nouveaupdv');
		$row = $q->row();

        if (isset($row))
       // if ($q->num_rows > 0)
		   {
            $r = $q->result();
            $user=$r[0];
			
			$password=$this->generatePwd();
		$user_logs_update = array(
			"pdv" => $user->pdv,
			"date_modification" => date('Y-m-d H:i:s'),
			"password" => $password
		);
		$this->db->update('nouveaupdv',$user_logs_update,array('pdv' => $user->pdv));
		$this->send_Reset_Pdv($user->pdv, $user->email_pdv, $password);
			$info= "Password has been reset and has been sent to email pdv: ". $email;
			redirect('ooredoo/reinit?info=' . $info, 'refresh');
        }
		$error= "The email pdv you entered not found on our database ";
		//var_dump ( $q );
		redirect('ooredoo/reinit?error=' . $error, 'refresh');
		
		
	} 
}

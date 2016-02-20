<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examples extends CI_Controller {

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
		$config['smtp_user'] = 'XXX@gmail.com';
		$config['smtp_pass'] = 'XXX';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$this->load->library('email',$config);
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	public function offices()
	{
		$output = $this->grocery_crud->render();

		$this->_example_output($output);
	}

	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	public function pdv_management()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('nouveaupdv');
			$crud->set_subject('Point de Vente');
			$crud->required_fields('pdv','raison_sociale', 'type_pdv','msisdn', 'wilaya_pdv', 'commune_pdv', 'email_pdv', 'code_vendeur', 'code_vendeur', 'Statue');
			$crud->unique_fields('pdv','msisdn', 'code_vendeur');
			$crud->columns('code_vendeur', 'raison_sociale', 'type_pdv', 'adresse_pdv', 'wilaya_pdv', 'commune_pdv', 'msisdn', 'autre_telephone_pdv', 'email_pdv', 'Statue','date_creation', 'date_modification');
			$crud->fields('code_vendeur', 'raison_sociale', 'type_pdv', 'adresse_pdv', 'wilaya_pdv', 'commune_pdv', 'msisdn', 'autre_telephone_pdv', 'email_pdv', 'Statue');
			$crud->unset_texteditor('adresse_pdv');
			
			$crud->set_rules('email_pdv','Email','required|valid_email|is_unique[nouveaupdv.email_pdv]');			
			
			$crud->display_as('code_vendeur','Code du vendeur');
			$crud->display_as('raison_sociale','Raison sociale');
			$crud->display_as('msisdn','MSISDN PDV');
			$crud->display_as('type_pdv','Type PDV');
			$crud->display_as('adresse_pdv','Adresse PDV');
			$crud->display_as('autre_telephone_pdv','Autre t&eacute;lephone PDV');
			$crud->display_as('email_pdv','Email PDV');
			$crud->display_as('Statue','Statue PDV');
			$crud->display_as('commune_pdv','Commune PDV');
			$crud->display_as('wilaya_pdv','Wilaya PDV');
			$crud->display_as('pdv','PDV');
			$crud->callback_after_insert(array($this, 'log_user_after_insert'));
			$crud->callback_after_update(array($this, 'log_user_after_update'));
			$crud->callback_before_update(array($this,'encrypt_password_callback'));
			
			$crud->field_type('wilaya_pdv','dropdown', $this->get_Wilaya(),1);
			$crud->field_type('Statue','dropdown',array('0'=>'inactif','1' => 'actif'),0);

			$output = $crud->render();
			$this->_example_output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
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
		echo 'SAVGARDE';
		$this->send_Mail("rbe4242@gmail.com","XXDDXX");
		echo 'ENVOYE';
		return $post_array;
	}
	public function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}
	
	function log_user_after_insert($post_array,$primary_key)
	{
		$password=$this->generatePwd();
		$user_logs_update = array(
			"pdv" => $primary_key,
			"date_creation" => date('Y-m-d H:i:s'),
			"date_modification" => date('Y-m-d H:i:s'),
			"password" => $password,
		);
		$this->db->update('nouveaupdv',$user_logs_update,array('pdv' => $primary_key));
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
	
	function get_Wilaya(){
		$i=0;
		$arr= array();
		$arr [$i++]="Adrar";
		$arr [$i++]="Chlef";
		$arr [$i++]="Laghouat";
		$arr [$i++]="Oum El Bouaghi";
		$arr [$i++]="Batna";
		$arr [$i++]="Béjaïa";
		$arr [$i++]="Biskra";
		$arr [$i++]="Béchar";
		$arr [$i++]="Blida";
		$arr [$i++]="Bouira";
		$arr [$i++]="Tamanrasset";
		$arr [$i++]="Tébessa";
		$arr [$i++]="Tlemcen";
		$arr [$i++]="Tiaret";
		$arr [$i++]="Tizi Ouzou ";
		$arr [$i++]="Alger";
		$arr [$i++]="Djelfa";
		$arr [$i++]="Jijel";
		$arr [$i++]="Sétif";
		$arr [$i++]="Saïda";
		$arr [$i++]="Skikda";
		$arr [$i++]="Sidi Bel Abbès";
		$arr [$i++]="Annaba";
		$arr [$i++]="Guelma";
		$arr [$i++]="Constantine";
		$arr [$i++]="Médéa";
		$arr [$i++]="Mostaganem";
		$arr [$i++]="M'Sila";
		$arr [$i++]="Mascara";
		$arr [$i++]="Ouargla";
		$arr [$i++]="Oran";
		$arr [$i++]="El Bayadh";
		$arr [$i++]="Illizi";
		$arr [$i++]="Bordj Bou Arreridj";
		$arr [$i++]="Boumerdès";
		$arr [$i++]="El Tarf";
		$arr [$i++]="Tindouf";
		$arr [$i++]="Tissemsilt";
		$arr [$i++]="El Oued";
		$arr [$i++]="Khenchela";
		$arr [$i++]="Souk Ahras";
		$arr [$i++]="Tipaza";
		$arr [$i++]="Mila";
		$arr [$i++]="Aïn Defla";
		$arr [$i++]="Naâma";
		$arr [$i++]="Aïn Témouchent";
		$arr [$i++]="Ghardaïa";
		$arr [$i++]="Relizane";
		return $arr;
	}
	protected function send_Mail($email, $password){
		$this->email->set_newline("\r\n");
		$this->email->from('XXX@gmail.com', 'Ooredoo');
		$this->email->to($email); 
		//$this->email->cc('Manager@Ooredoo.com'); 
		//$this->email->bcc('them@their-example.com'); 
		$this->email->subject('Activation de compte PDV');
		$this->email->message('Bonjour,\nVotre Compte d\'accès au point de vente est actif, votre mot de passe est: .'.$password.'\nBien à vous\nOoredoo.');	
		$this->email->set_alt_message('Bonjour, votre mot de passe pour accéder au point de vente est : '.$password);
		if($this->email->send())
		{
			echo "SENDED";
		}
		else
		{
			show_Error($this->email->print_debugger());
		}

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
	


}
<?php
	if(!session_id()){ session_start(); }	
	if(!class_exists('Utils')) require 'Utils.php';
	if(!class_exists('Sql')) require 'Sql.php';

	class User{
		private $id;
		private $nome;
		private $email;
		private $senha;
		private $login;
		private $perfil;

		public function __construct($id=null){
			$Sql = new Sql();
//SELECT u.codUser, u.nome as codnome, u.perfil, p.* FROM sm_usuarios u INNER JOIN sm_pessoas p ON (p.cpf = u.login) WHERE u.codUser > 0;
			$data = $id!=null && $id>0 ? $Sql->select1("SELECT * FROM sm_usuarios WHERE ativado=1 AND codUser = {$id} ORDER BY 1 DESC LIMIT 1;") : array();
			foreach(($data!=null ? $data : array()) as $key => $val){
				switch($key){
					case 'codUser':{
						$this->id = $val;
						break;
					}
					case 'nome':{
						$this->nome = $val;
						break;
					}
					case 'email':{
						$this->email = $val;
						break;
					}
					case 'senha':{
						$this->senha = $val;
						break;
					}
					case 'login':{
						$this->login = $val;
						break;
					}
					case 'perfil':{
						$this->perfil = $val;
						break;
					}
				}
			}
		}

		static function getURL($route=''){
			require dirname(__FILE__).DIRECTORY_SEPARATOR.'Config.php';

			$URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://');
			$HOST = substr($Config['URL'], strpos($Config['URL'], '://')+3);

			return $URL.$HOST.$route;
		}
		static function getPATH(){
			return dirname(dirname(dirname(__FILE__)));
		}

		static function login($origemFile, $login, $senha){
			$Sql = new Sql();

			$login = Utils::soNumeros($login);
			$cPanel = User::getURL('panel');

			if($login!='' && $senha!=''){
				if(Utils::isCPF($login)||Utils::isCNPJ($login)){
					$senha = Utils::antiSQL($senha);

					$querySql = "SELECT * FROM sm_usuarios WHERE ativado=1 AND login = '{$login}' AND senha = md5('{$senha}');";
					$rs = $Sql->select1($querySql);
					if(!empty($rs)){
						$_SESSION['SM_UID'] = $rs['codUser'];
						$_SESSION['Login'] = $rs['login'];
						$_SESSION['SM_Secret'] = md5($rs['senha']);

						return true;
					} else{
						$msgError = "Login ou senha incorretos !";
						setCookie("erro",$msgError);
						header("Location: {$cPanel}/Login.php");
					}
				} else{
					$msgError = "Login inválido !";
					setCookie("erro",$msgError);
					header("Location: {$cPanel}/Login.php");
				}
			} else{
				$msgError = "Login ou senha inválidos.";
				setCookie("erro",$msgError);
				header("Location: {$cPanel}/Login.php");
			}
		}

		static function auth($origemFile, $visitante=false){
			$UID = isset($_SESSION['SM_UID']) && !empty($_SESSION['SM_UID']) ? $_SESSION['SM_UID'] : null;
			$user = $UID!=null ? new User($UID) : array();
			$cPanel = User::getURL('panel');

			if(!empty($user)){
				if(isset($_SESSION['SM_Secret']) && $_SESSION['SM_Secret'] === md5($user->getSenha())){
					if(in_array(basename(dirname($origemFile)), array('panel','classes'))){
						return $user;
					}
				}else{
					setCookie("erro","Sua senha foi alterada. Faça seu login!");
					if(!$visitante) header("Location: {$cPanel}/Login.php");
				}
			} else{
			   setCookie("erro",'Bem-vindo !');
			   if(!$visitante) header("Location: {$cPanel}/Login.php");
			}
		}
		
		static function obterUsuarios($id=null, $perfil=null){
			$Sql = new Sql();

			$whereAdd = $id!=null && $id>0 ? " AND codUser = {$id}" : '';
			$whereAdd.= $perfil!=null && $perfil>'' ? " AND perfil = '{$perfil}'" : '';
			$querySql = "SELECT * FROM sm_usuarios WHERE codUser>0 {$whereAdd} ORDER BY data_cadastro DESC;";

			$Usuarios = array();
			foreach(Utils::array_get($Sql->select($querySql)) as $data){
				$Usuarios[] = $data;
			}
			return $Usuarios;
		}
		
		static function novoUsuario($PUT, $user){
			$Sql = new Sql();
			$Usuario=array();
			$rs = false;

			if($user->getId()>0){
			  if($user->getPerfil()=='TI'){
				$Usuario['nome'] = isset($PUT['nome']) ? $PUT['nome'] : null;
				$Usuario['email'] = isset($PUT['email']) ? $PUT['email'] : null;
				$Usuario['login'] = isset($PUT['login']) ? Utils::soNumeros($PUT['login']): null;
				$Usuario['perfil'] = isset($PUT['Administrador']) && $PUT['Administrador']==1 ? 'TI' : (strlen($Usuario['login'])==11 ? 'PF' : 'PJ');
				$Usuario['senha'] = isset($PUT['senha']) ? md5($PUT['senha']) : md5($Usuario['email']);
				$Usuario['ativado'] = isset($PUT['ativado']) ? boolval($PUT['ativado']) : true;

				if($Usuario['nome']!=null && $Usuario['email']!=null){
					if(Utils::isCPF($Usuario['login']) || Utils::isCNPJ($Usuario['login'])){
						$rs = $Sql->newInstance('sm_usuarios', $Usuario);

						return $rs>0 ? intval($rs) : "Erro ao salvar usuario.";
					}
					else{ return "CPF ou CNPJ invalidos !"; }
				}
				else{ return "Campos obrigatórios necessários."; }
			  }
			  else{ return "Sem permissão para isso."; }
			}
			else{ return "Usuario não identificado"; }

			return $rs===true ? $rs : 'Erro ao salvar os dados.';
		}
		static function updateUsuario($PUT, $user){
			$Sql = new Sql();
			$Usuario=array();
			$idUsuario = isset($PUT['id'])&&$PUT['id']!='' ? intval($PUT['id']) : 0;
			$rs = false;

			if($user->getId()>0){
			  if($user->getPerfil()=='TI' || ($idUsuario>0 && $idUsuario == $user->getId())){
				//$Usuario['login'] = isset($PUT['login']) ? Utils::soNumeros($PUT['login']): null;
				$Usuario['nome'] = isset($PUT['nome']) ? $PUT['nome'] : null;
				$Usuario['email'] = isset($PUT['email']) ? $PUT['email'] : null;
				//$Usuario['perfil'] = isset($PUT['Administrador']) && $PUT['Administrador']==1 ? 'TI' : (strlen($Usuario['login'])==11 ? 'PF' : 'PJ');
				$Usuario['ativado'] = isset($PUT['ativado']) ? boolval($PUT['ativado']) : null;

				if($Usuario['nome']!=null && $Usuario['email']!=null){
					$rs = $Sql->updateInstance('sm_usuarios', array('codUser'=>$idUsuario), $Usuario);

					return $rs>0 ? intval($idUsuario) : "Erro ao salvar usuario.";
				}
				else{ return "Campos obrigatórios necessários."; }
			 }
			 else{ return "Permissões inválidas!"; }
			}
			else{ return "Usuario não identificado"; }

			return $rs===true ? $rs : 'Erro ao salvar os dados.';
		}

		public function alterarSenha($data, $manterSessao=false){
			$Sql = new Sql();

			$senha1 = isset($data['password1']) ? md5($data['password1']) : null;
			$senha2 = isset($data['password2']) ? md5($data['password2']) : null;
			$senhaAtual = isset($data['password']) ? md5($data['password']) : null;

			if($senha1!=null && $senha1 == $senha2){
				if($this->getSenha() == $senhaAtual){
					$id = $this->getId();

					$querySql ="UPDATE sm_usuarios SET senha = '{$senha1}' WHERE codUser = {$id}";

					$rs = $Sql->update($querySql);

					if($rs && $manterSessao) $_SESSION['SM_Secret'] = md5($senha1);
					return $rs;
				}
				else return 'Senha antiga não confere.';
			}
			else return 'As novas senhas devem ser iguais.';
		}

		public function getId(){
				return $this->id;
		}

		public function setId($id){
				$this->id = $id;
		}

		public function getNome(){
				return $this->nome;
		}

		public function setNome($nome){
				$this->nome = $nome;
		}

		public function getEmail(){
				return $this->email;
		}

		public function setEmail($email){
				$this->email = $email;
		}
		
		public function getSenha(){
				return $this->senha;
		}

		public function setSenha($senha){
				$this->senha = $senha;
		}

		public function getLogin(){
				return $this->login;
		}

		public function setLogin($login){
				$this->login = $login;
		}

		public function getPerfil(){
				return $this->perfil;
		}

		public function setPerfil($perfil){
				$this->perfil = $perfil;
		}
	}
switch($_SERVER['REQUEST_METHOD']){
    case 'PUT':{}
	case 'POST':{
		$arrResponse =  array('rs'=>false, 'msg'=>'');
		$params = Utils::receiveAjaxData('GET');

		if(isset($params['token']) && $params['token'] > time()){
			$u = User::auth(__FILE__, true);
			if(!empty($u)){
				$rs = false; 	$err = false;

				switch($params['a']){
					case 'alteraSenha':
						$rs = $u->alterarSenha(Utils::receiveAjaxData('POST'), true);
						break;
					case 'saveUsuario':
						$DATA = Utils::receiveAjaxData('POST');
						$isEdit = isset($DATA['id'])&&$DATA['id']>0;

						$rs = $isEdit ? User::updateUsuario(Utils::receiveAjaxData('POST'), $u) : User::novoUsuario(Utils::receiveAjaxData('POST'), $u);
						break;
					default:
						$err = true;
				}

				$arrResponse['rs'] = is_bool($rs) && $rs===true;
				$arrResponse['msg'] = is_string($rs) ? $rs : ($arrResponse['rs'] ? 'Salvo com Sucesso !' : 'Error: User');
			}
			else{ $arrResponse['rs'] = -1; }

			if(!$err) echo json_encode($arrResponse, JSON_NUMERIC_CHECK);
		}
		break;
	}
	default:{}
}
?>
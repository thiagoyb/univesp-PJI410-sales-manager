<?php
class Utils{
	static function fromCharCode() {
        return array_reduce(func_get_args(),function($a,$b){$a.=chr($b);return $a;});
    }
	static function geraToken(){
		return md5(uniqid(rand(), true));
	}
	static function semNumeros($str){
		return preg_replace("/[0-9]/", '', $str);
	}
	static function soNumeros($str){
		return preg_replace("/[^0-9]/", '', $str);
	}
	static function semEspacos($str){
		return preg_replace("/\s+/", '', $str);
	}
	static function toFloat($value){
		return strtr(preg_replace("/\s+/", '', $value), array('.'=>'', ','=>'.'));
	}
	static function truncar($val, $f='0'){
		if(($p = strpos($val, '.')) !== false){
			$val = floatval(substr($val, 0, $p + 1 + $f));
		}
		return $val;
	}
	static function antiSQL($s){
		$s = str_ireplace(";",'',$s);
		$s = str_ireplace("--",'',$s);
		$s = str_ireplace("'",'',$s);
		$s = str_ireplace('"','',$s);
		return addslashes(strip_tags($s));
	}
	static function isCPF($cpf){
			$iguais=1;
			$cpf = self::soNumeros($cpf);
			if(strlen($cpf) != 11){
				return false;
			}
			for($i = 0; $i < strlen($cpf) - 1; $i++){
				if($cpf[$i] != $cpf[$i+1]){
					$iguais = 0;
					break;
				}
			}
			if($iguais){
				return false;
			}
			$soma = 0;
			for($i=0;$i<9;$i++){
				$soma += $cpf[$i] *(10-$i);
			}
			$rev = 11 - ($soma % 11);
			$rev = $rev == 10 || $rev == 11 ? 0 : $rev;
			if($rev != $cpf[9]){
				return false;
			}
			$soma = 0;
			for($i = 0; $i < 10; $i++){
				$soma += $cpf[$i] * (11 - $i);
			}
			$rev = 11 - ($soma % 11);
			$rev = $rev == 10 || $rev == 11 ? 0 : $rev;
			if($rev != $cpf[10]){
				return false;
			}
		  return true;
		}
	static function isCNPJ($cnpj){
			$iguais=1;
			$cnpj = self::soNumeros($cnpj);
			if(strlen($cnpj) != 14){
				return false;
			}
			for($i = 0; $i < strlen($cnpj) - 1; $i++){
				if($cnpj[$i] != $cnpj[$i+1]){
					$iguais = 0;
					break;
				}
			}
			if($iguais){
				return false;
			}
			$tamanho = strlen($cnpj) - 2;
			$num = substr($cnpj,0,$tamanho);
			$dv = substr($cnpj,-2,1);
			$soma = 0;	$pos = $tamanho - 7;
			for($i = $tamanho; $i >= 1; $i--){
				$soma += intval($num[$tamanho - $i]) * $pos--;
				if($pos < 2){
					$pos = 9;
				}
			}
			$rev = $soma % 11 < 2 ? 0 : 11 - $soma % 11; 
			if($rev != $dv){
				return false;
			}
			$tamanho++;
			$num = substr($cnpj,0,$tamanho);
			$dv = substr($cnpj,-1,1);
			$soma = 0;	$pos = $tamanho - 7;
			for($i = $tamanho; $i >= 1; $i--){
				$soma += intval($num[$tamanho - $i]) * $pos--;
				if($pos < 2){
					$pos = 9;
				}
			} 
			$rev = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
			if($rev != $dv){
				return false;
			}
			return true;
		}
	static function isEmail($str){
		$user = substr($str, 0,strrpos($str, "@"));
		$domain = substr($str, strrpos($str, "@")+ 1, strlen($str));
		if(	(strlen($user) >=1) &&
			(strlen($domain) >=3) &&
			(!strrpos($user, "@")) &&
			(!strrpos($domain, "@")) &&
			(!strrpos($user, " ")) &&
			(!strrpos($domain, " ")) &&
			(strrpos($domain, ".")) &&
			(strrpos($domain, ".") < strlen($domain) - 1)
		){	return true;	}
		return false;
	}
	static function isURL($domain){
		if(	(strlen($domain) >=3) &&
			(!strrpos($domain, " ")) &&
			(strrpos($domain, ".")) &&
			(strrpos($domain, ".") < strlen($domain) - 1)
		){	return true;	}
		return false;
	}
	static function setMask($val, $mask){
		$maskared = '';
		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++){
			if($mask[$i] == '#'){
				if(isset($val[$k])){
					$maskared .= $val[$k++];
				}
			} else{
				if(isset($mask[$i])){
					$maskared .= $mask[$i];
				}
			}
		}
		return $maskared;
	}
	static function array_get($data){
		return $data!=null && is_array($data) ? $data : array();
	}
	static function receiveAjaxData($method=null){
		$_RECV = array();
		$INPUT = file_get_contents('php://input');

		switch($method){
			case 'GET':{
				$_RECV = isset($_GET) && $_GET!=null ? $_GET : array();
				break;
			}
			case 'POST':{
				$_RECV = isset($_POST) && $_POST!=null ? $_POST : array();
				break;
			}
			case 'DELETE':
			case 'PUT':{
				if(strpos($INPUT,'=')!==false){
					parse_str($INPUT,$_RECV);
				} else{
					$_RECV = json_decode($INPUT,JSON_NUMERIC_CHECK);
				}
				break;
			}
		}
		return $_RECV;
	}
}
?>
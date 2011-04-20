<?php defined('SYSPATH') or die('No direct access allowed.');

class Kohana_Log_Watchy extends Log_Writer{
		
	protected $project;
	protected $emails;
	protected $from_email;
	protected $email_content = '';
	
	function __construct($name = 'Kohana Watchy' , $emails = array() , $from_email = 'Kohana Watchy <noreply@example.com>'){
		$this->project = $name;
		$this->from_email = $from_email;

		if(is_array($emails)){
			$this->emails = $emails;
		}else{
			$this->emails = array($emails);
		}
	}
	

	public function write(array $messages){
		foreach($messages as $message){
			foreach($message as $title => $value){
				$this->email_content .= '<b>'.$title.'</b>: '.$value.'<br />';
			}
			$this->email_content .= "<br /><br /><hr /><br /><br />";
		}
	}
	
	private function send_email($content = NULL){
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "X-mailer: php\n"; 
		$headers .= "From: ".$this->from_email."\n";
		$subject = 'Watchy - '.$this->project.' - '.date('l jS \of F Y h:i:s A');
		
		if($content == NULL){
			$content = $this->email_content;
		}
		
		foreach($this->emails as $email){
			@mail($email , $subject , $content , $headers);
		}
	}
		
	function __destruct(){
		if($this->email_content != ''){
			$this->send_email();
		}
	}

}

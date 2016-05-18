<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */
require_once('dompdf/dompdf_config.inc.php');
spl_autoload_register('DOMPDF_autoload');

class Pdf extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper('file');
	}	

	function index(){}


	private function delete_dir($dir) {
		$handle=opendir($dir);
		while (($file = readdir($handle))!==false) {
			@unlink($dir.'/'.$file);
		}
		closedir($handle);
		rmdir($dir);
	}

	function html_form($content,$orientation,$paper,$file_name,$folder,$auto_clear=TRUE){
		$document = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title></title>';
	//	$document .= '<link type="text/css" href="'.base_url().'css/pdf.css" rel="stylesheet" />';
	
	$document .= '<style type="text/css">
        *,body,html{margin:0;padding:0}.container:after,.editor_body .clearfix:after,.editor_body .container:after{clear:both}
        *{font-family:Arial,Helvetica,sans-serif;font-size:10px} h1{ font-size:15px !important	; }  h2{ font-size:13px !important	; }
        table{margin:30px 10px 10px !important; width:100%; } table tr { margin:0 !important; padding: 0 !important; } table th{ background: #ccc; border:1px solid #555; padding:3px; }
        table td{ margin:0 !important; padding:5px 2px !important; border-bottom:1px solid #555; font-size: 8px;} body{margin-top:50px !important;}
        tbody tr:nth-child(odd) {   background-color: #F2F2F2; }</style>';


		$document .= '</head><body>';
		

		$document .= $content;
		$document .= '</body></html>';
		return $this->pdf_create($document,$orientation,$paper,$file_name,$folder,$auto_clear);
	}

	public function pdf($content='',$file_name){

		if($content != ''){
			$post_value = $content;
		}else{
			$post_value  = $this->input->post('content');
		}

		$formatted = str_replace('pdf_mrkp_styl="','style="',$post_value);

		$content = '<div class="editor_body"><div class="clearfix">'.$formatted.'</div></div>';
		$my_pdf = $this->html_form($content,'portrait','A4',$file_name,'inv_jbs',FALSE);

		//echo $post_value;
		return $my_pdf;
	}


	private function pdf_create($html, $orientation='portrait', $paper='A4',$filename='report',$folder_type='general' ,$auto_clear=TRUE,$stream=TRUE){
		$dompdf = new DOMPDF();
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

		$dompdf->set_paper($paper,$orientation);
		$dompdf->load_html($html);
		$dompdf->render();

		$canvas = $dompdf->get_canvas();
		$date_gen = date("jS F, Y");

	//	$user_prepared = ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name'));

		if($orientation == 'portrait'){
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(535,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(15, 810, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen", $font, 8, array(0,0,0));
			
		}else{
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(780,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(20, 570, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen", $font, 8, array(0,0,0));
		}

		$output = $dompdf->output();

		$filename .= '-'.date("d-m-Y-His");


		if($auto_clear){
			$this->delete_dir('docs/'.$folder_type);  #remove folder and contents
		}

		//create the folder if it's not already exists
		if(!is_dir('docs/'.$folder_type)){
			mkdir('docs/'.$folder_type,0755,TRUE);
		}
		//create the folder if it's not already exists

		write_file('docs/'.$folder_type.'/'.$filename.'.pdf','');

		file_put_contents('docs/'.$folder_type.'/'.$filename.'.pdf', $output);

		return $filename;


		//unlink('docs/'.$folder_type.'/'.$filename.'.pdf');

		//$dompdf->stream($filename.'.pdf');
	}
}
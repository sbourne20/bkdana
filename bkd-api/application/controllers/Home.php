<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		//$this->load->model('Product_model', 'pmodel');
		$this->load->model('Content_model');
	}

	function index(){
		echo 'index';
	}

	function slider($limit)
	{
		$limit = (empty($limit))? '0' : (int)(antiInjection($limit));
		$data = $this->Content_model->get_slide($limit);
        
        if (count($data)>0) {
            $json_format = json_encode($data) ;
            echo "{ \"response\": \"success\" , \"Slider\":" . $json_format. "}";
        }
        else
        {
            echo "{ \"response\": \"fail\" , \"Slider\":[\"kosong\" ]}";
        }
//		echo json_encode($data);
	}

	function get_data_merchant()
	{
		//$id   = (empty($id))? '1' : (int)(antiInjection($id));
		$alldata = $this->Content_model->get_merchant();

		$data   = array();
		$output = array();
		foreach ($alldata as $key) {			
			$ins['merchant_id']   = $key['merchant_id'];
            $ins['merchant_name'] = $key['merchant_name'];
            $ins['merchant_slug'] = $key['merchant_slug'];
            $ins['merchant_description'] = $key['merchant_description'];
            $ins['merchant_position']    = $key['merchant_position'];
			$ins['image_link']    = $this->config->item('url_tiro') . 'images-data/merchant/'.$key['merchant_id'].'/'.$key['merchant_image'];
		    $data[] = array_merge($output, $ins);
		}
        
        if (count($data)>0) {
            $json_format = json_encode($data) ;
            echo "{ \"response\": \"success\" , \"content\":" . $json_format. "}";
        }
        else
        {
            echo "{ \"response\": \"fail\" , \"content\":[\"kosong\" ]}";
        }
	}

	function get_str_rss()
	{
		// RSS

		$limit = (int)(antiInjection($this->input->get('total', TRUE)));

		if ($limit)
		{
			if ($limit > 10) { $limit = 10; }

			include (APPPATH . 'libraries/simplepie-1.5/autoloader.php');
			
			if ($_SERVER['SERVER_ADDR'] == '10.5.5.46') {
				$rss_url = 'http://www.bisnis.com/rss';
			}else{
				$rss_url = 'http://www.sementigaroda.com/rss';	
			}

			$feed = new SimplePie();
			$feed->set_feed_url($rss_url);
			$feed->enable_cache(false);
			$feed->init();		 
			// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
			$feed->handle_content_type();
			$rss_feed = $feed->get_items(0,$limit);

			$i=0;
			$data_rss = '';

			foreach ($rss_feed as $item) {
				$data_rss[$i]['url']  = $item->get_permalink();
				$data_rss[$i]['title'] = $item->get_title();
				$data_rss[$i]['image'] = $item->get_enclosure()->link;

				$i = $i+1;
			}

			if (is_array($data_rss))
			{
				$resp = 'success';
				$data = json_encode($data_rss);
			}else{
				$resp = 'fail';
				$data = '["kosong" ]';
			}

			echo '{ "response": "'.$resp.'" , "content": '.$data.'}';

		}else{
			//echo 'not ok';
		}
		
	}
}

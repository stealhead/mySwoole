<?php
class CountItem {
	private $url = "http://list.jd.com/list.html";
	public $cat = '1215,1343,9719';
	public $end = "trans=1&JL=6_0_0&ms=6#J_main";

	public function getItems ($page) {
		
		$url = $this->url . '?cat=' . $this->cat . '&page=' . $page;
		$url .= '&' . $this->end;
		
		$result = $this->request($url);
		$result = $this->getsku($result);
		return implode('-', $result);
		
	}
	
 	function request ($uri, array $data = array()) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$uri);
                // curl_setopt($ch, CURLOPT_POST, 1);  //常规POST请求
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    //request data
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                curl_close($ch);
                $json = json_decode($result);
                if (!$json) return $result;
                return $json;
        }
        function getsku ($str) {
                $preg = '/data-sku="(\d+)"/';
                preg_match_all($preg, $str, $matches);
                return array_values(array_unique($matches[1]));
        }

}

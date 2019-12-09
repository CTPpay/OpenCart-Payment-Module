<?php
class ControllerExtensionPaymentCtpPay extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $data['action'] = 'https://merchant.ctppay.com/merchant/';

        $data['key'] = $this->config->get('ctppay_merchant_key');
		$data['order'] = $this->session->data['order_id'];
		$data['pay'] = $order_info['currency_code'];
		$data['volume'] = $order_info['total'];
		$data['ref'] = $this->url->link('extension/payment/ctppay/callback', '', true);

		return $this->load->view('extension/payment/ctppay.tpl', $data);
    }

    public function callback() {

		if (isset($this->request->post['data'])) {

			$data = ($this->request->post['data']);
			$securkey = $this->config->get('ctppay_secure_key');
			
			$str = $this->decode($data, $securkey);
			$confirm = json_decode($str);
			
			$order_id = ($confirm["0"]);
			$status = ($confirm["1"]);
			$total = ($confirm["2"]);
			$currency = ($confirm["3"]);
			
			if ($status == "Confirmid"){
				$this->load->model('checkout/order');

				$order_info = $this->model_checkout_order->getOrder($order_id);

				if($order_info['total']==$total && $order_info['currency_code'] == $currency) {
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('ctppay_order_status_id'));
				}
			}
			
		} else {
			
			$this->response->redirect($this->url->link('checkout/success'));

		}
	}

    private function decode($data, $key) {

        $c = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $cipherdata_raw = substr($c, $ivlen+$sha2len);
        $data = openssl_decrypt($cipherdata_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $cipherdata_raw, $key, $as_binary=true);
        
        if (hash_equals($hmac, $calcmac)) {
        return $data; } 
    }

}

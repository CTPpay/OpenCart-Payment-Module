<?php
class ControllerExtensionPaymentCtpPay extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/ctppay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
            $this->model_setting_setting->editSetting('ctppay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token='.$this->session->data['token'].'&type=payment', true));
        }

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant_key'])) {
			$data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$data['error_merchant_key'] = '';
		}

		if (isset($this->error['secure_key'])) {
			$data['error_secure_key'] = $this->error['secure_key'];
		} else {
			$data['error_secure_key'] = '';
        }
        
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');

        $data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
        $data['entry_secure_key'] = $this->language->get('entry_secure_key');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_currencies'] = $this->language->get('entry_currencies');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], true)
        );

		$data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/extension', 'token='.$this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/ctppay', 'token='.$this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/ctppay', 'token='.$this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], true);


        if (isset($this->request->post['ctppay_merchant_key'])) {
			$data['ctppay_merchant_key'] = $this->request->post['ctppay_merchant_key'];
		} else {
			$data['ctppay_merchant_key'] = $this->config->get('ctppay_merchant_key');
		}

		if (isset($this->request->post['ctppay_secure_key'])) {
			$data['ctppay_secure_key'] = $this->request->post['ctppay_secure_key'];
		} else {
			$data['ctppay_secure_key'] = $this->config->get('ctppay_secure_key');
		}

		if (isset($this->request->post['ctppay_currencies'])) {
			$data['ctppay_currencies'] = $this->request->post['ctppay_currencies'];
		} elseif($this->config->get('ctppay_currencies')) {
			$data['ctppay_currencies'] = $this->config->get('ctppay_currencies');
		} else {
			$data['ctppay_currencies'] = "ALL";
		}

		$data['currencies'] = array('ALL','RUB','USD','EUR');


		if (isset($this->request->post['ctppay_order_status_id'])) {
			$data['ctppay_order_status_id'] = $this->request->post['ctppay_order_status_id'];
		} else {
			$data['ctppay_order_status_id'] = $this->config->get('ctppay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['ctppay_geo_zone_id'])) {
			$data['ctppay_geo_zone_id'] = $this->request->post['ctppay_geo_zone_id'];
		} else {
			$data['ctppay_geo_zone_id'] = $this->config->get('ctppay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['ctppay_status'])) {
			$data['ctppay_status'] = $this->request->post['ctppay_status'];
		} else {
			$data['ctppay_status'] = $this->config->get('ctppay_status');
		}

		if (isset($this->request->post['ctppay_sort_order'])) {
			$data['ctppay_sort_order'] = $this->request->post['ctppay_sort_order'];
		} else {
			$data['ctppay_sort_order'] = $this->config->get('ctppay_sort_order');
		}

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
        $this->response->setOutput($this->load->view('extension/payment/ctppay.tpl', $data));
    }

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/ctppay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['ctppay_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}

		if (!$this->request->post['ctppay_secure_key']) {
			$this->error['secure_key'] = $this->language->get('error_secure_key');
		}

		return !$this->error;
    }
    
}

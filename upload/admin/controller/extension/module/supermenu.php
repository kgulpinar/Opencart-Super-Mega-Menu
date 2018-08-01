<?php
class ControllerExtensionModuleSupermenu extends Controller {

	private $error = array(); 
	private $sets = array();
	private function getSetting($i) {
		return isset($this->sets[$i]) ? $this->sets[$i] : null;
	}
	
	public function index() {   

		$this->load->language('extension/module/supermenu');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('localisation/language');
		$this->load->model('tool/image');
        $this->load->model('setting/store');

        if (!empty($this->request->get['registerEvents'])) {
        	$this->uninstall();
        	$this->install();
        }

		if (isset($this->request->get['deleteOldVersion']) && $this->validate()) {
			@unlink(DIR_APPLICATION . 'controller/module/supermenu.php');
			@unlink(DIR_APPLICATION . 'language/english/module/supermenu.php');
			@unlink(DIR_APPLICATION . 'language/en-gb/module/supermenu.php');
			@unlink(DIR_APPLICATION . 'view/template/module/supermenu.tpl');
			$this->session->data['success'] = 'You succesfully deleted the old version of supermenu!';
			$this->response->redirect($this->url->link('extension/module/supermenu', 'user_token=' . $this->session->data['user_token'], true));
		} elseif (isset($this->request->get['deleteOldVersion'])) {
			$this->response->redirect($this->url->link('extension/module/supermenu', 'nopermissiontodelete=1&user_token=' . $this->session->data['user_token'], true));
		}
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$redirect = '';
			$sid = 0;
			
			if (isset($this->request->get['store']) && (int)$this->request->get['store']) {
				$redirect = '&store=' . (int)$this->request->get['store'];
				$sid = (int)$this->request->get['store'];
			}
			
			$this->model_setting_setting->editSetting('supermenu', $this->request->post, $sid);		
					
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->cache->delete('supermenu');
						
			$this->response->redirect($this->url->link('extension/module/supermenu', 'user_token=' . $this->session->data['user_token'] . $redirect, true));
		}

		$data['store'] = (isset($this->request->get['store']) && (int)$this->request->get['store']) ? (int)$this->request->get['store'] : 0;

		$this->sets = $this->model_setting_setting->getSetting('supermenu', $data['store']); 
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['stores'] = $this->model_setting_store->getStores();

		$data['text_stores'] = $this->language->get('text_stores');
		$data['text_fbrands'] = $this->language->get('text_fbrands');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_expando'] = $this->language->get('text_expando');
		$data['text_sorder'] = $this->language->get('text_sorder');
		$data['text_tlcolor'] = $this->language->get('text_tlcolor');
		$data['text_tlstyle'] = $this->language->get('text_tlstyle');
		$data['text_justadd'] = $this->language->get('text_justadd');
		$data['text_alldrop'] = $this->language->get('text_alldrop');
		$data['text_overdrop'] = $this->language->get('text_overdrop');
		$data['text_supermenuisresponsive'] = $this->language->get('text_supermenuisresponsive');
		$data['text_or'] = $this->language->get('text_or');
		$data['text_no'] = $this->language->get('text_no');
		$data['tab_items'] = $this->language->get('tab_items');
		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_html'] = $this->language->get('tab_html');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_add'] = $this->language->get('entry_add');
		$data['text_slist'] = $this->language->get('text_slist');
		$data['text_sgrid'] = $this->language->get('text_sgrid');
		$data['text_sview'] = $this->language->get('text_sview');
		$data['text_dwidth'] = $this->language->get('text_dwidth');
		$data['text_iwidth'] = $this->language->get('text_iwidth');
		$data['text_chtml'] = $this->language->get('text_chtml');
		$data['text_cchtml'] = $this->language->get('text_cchtml');
		$data['text_whatproducts'] = $this->language->get('text_whatproducts');
		$data['text_productlatest'] = $this->language->get('text_productlatest');
		$data['text_productspecial'] = $this->language->get('text_productspecial');
		$data['text_productfeatured'] = $this->language->get('text_productfeatured');
		$data['text_productbestseller'] = $this->language->get('text_productbestseller');
		$data['text_productlimit'] = $this->language->get('text_productlimit');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_custom'] = $this->language->get('entry_custom');
		$data['entry_information'] = $this->language->get('entry_information');
		$data['entry_advanced'] = $this->language->get('entry_advanced');
		$data['custom_name'] = $this->language->get('custom_name');
		$data['custom_url'] = $this->language->get('custom_url');
		$data['type_cat'] = $this->language->get('type_cat');
		$data['type_mand'] = $this->language->get('type_mand');
		$data['type_infol'] = $this->language->get('type_infol');
		$data['type_products'] = $this->language->get('type_products');
		$data['type_catprods'] = $this->language->get('type_catprods');
		$data['type_catcatprods'] = $this->language->get('type_catcatprods');
		$data['type_infod'] = $this->language->get('type_infod');
		$data['entry_iset'] = $this->language->get('entry_iset');
		$data['type_custom'] = $this->language->get('type_custom');
		$data['type_more'] = $this->language->get('type_more');
		$data['type_more2'] = $this->language->get('type_more2');
		$data['type_login'] = $this->language->get('type_login');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_count'] = $this->language->get('entry_count');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_add_item'] = $this->language->get('button_add_item');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['more_name'] = $this->language->get('more_name');
		$data['more2_name'] = $this->language->get('more2_name');
		$data['more_status'] = $this->language->get('more_status');
		$data['entry_image_size'] = $this->language->get('entry_image_size');
		$data['entry_show_description'] = $this->language->get('entry_show_description');
		$data['text_general'] = $this->language->get('text_general');
		$data['text_more_dropdown'] = $this->language->get('text_more_dropdown');
		$data['text_more2_dropdown'] = $this->language->get('text_more2_dropdown');
		$data['text_languagerelated'] = $this->language->get('text_languagerelated');
		$data['text_customization'] = $this->language->get('text_customization');
		$data['text_settings_isresponsive'] = $this->language->get('text_settings_isresponsive');
		$data['text_settings_dropdowntitle'] = $this->language->get('text_settings_dropdowntitle');
		$data['text_settings_topitemlink'] = $this->language->get('text_settings_topitemlink');
		$data['text_settings_flyoutwidth'] = $this->language->get('text_settings_flyoutwidth');
		$data['text_settings_bspacewidth'] = $this->language->get('text_settings_bspacewidth');
		$data['text_settings_mobilemenuname'] = $this->language->get('text_settings_mobilemenuname');
		$data['text_settings_infodname'] = $this->language->get('text_settings_infodname');
		$data['text_settings_brandsdname'] = $this->language->get('text_settings_brandsdname');
		$data['text_settings_latestpname'] = $this->language->get('text_settings_latestpname');
		$data['text_settings_specialpname'] = $this->language->get('text_settings_specialpname');
		$data['text_settings_featuredpname'] = $this->language->get('text_settings_featuredpname');
		$data['text_settings_bestpname'] = $this->language->get('text_settings_bestpname');
		$data['text_subcatdisplay'] = $this->language->get('text_subcatdisplay');
		$data['text_subcatdisplay_all'] = $this->language->get('text_subcatdisplay_all');
		$data['text_subcatdisplay_level1'] = $this->language->get('text_subcatdisplay_level1');
		$data['text_subcatdisplay_none'] = $this->language->get('text_subcatdisplay_none');
		$data['text_3dlevellimit'] = $this->language->get('text_3dlevellimit');
		$data['text_settings_viewallname'] = $this->language->get('text_settings_viewallname');
		$data['text_settings_viewmorename'] = $this->language->get('text_settings_viewmorename');
		$data['text_settings_dropeffect'] = $this->language->get('text_settings_dropeffect');
		$data['text_settings_hoverintent'] = $this->language->get('text_settings_hoverintent');
		$data['text_settings_tophomelink'] = $this->language->get('text_settings_tophomelink');
		$data['text_settings_menuskin'] = $this->language->get('text_settings_menuskin');
		$data['text_dflist'] = $this->language->get('text_dflist');
		$data['text_dfgrid'] = $this->language->get('text_dfgrid');
		$data['text_settings_supercache'] = $this->language->get('text_settings_supercache');
		$data['text_settings_scontainer'] = $this->language->get('text_settings_scontainer');
		$data['text_settings_wrapclass'] = $this->language->get('text_settings_wrapclass');
		$data['text_settings_fproduct'] = $this->language->get('text_settings_fproduct');
		$data['text_editfor'] = $this->language->get('text_editfor');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_linknewtab'] = $this->language->get('text_linknewtab');
		$data['text_mini_flyout'] = $this->language->get('text_mini_flyout');
		$data['text_flyout_left'] = $this->language->get('text_flyout_left');
		
		
		$data['user_token'] = $this->session->data['user_token'];
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['nopermissiontodelete']) && $this->request->get['nopermissiontodelete']) {
			$data['error_warning']  = $this->language->get('error_permission');
		}

		if (is_file(DIR_APPLICATION . 'controller/module/supermenu.php')) {
			$data['error_warning']  = $this->language->get('error_old_version');
			$data['error_warning'] .= '<a href="' . $this->url->link('extension/module/supermenu', 'deleteOldVersion=1&user_token=' . $this->session->data['user_token'], true) . '" class="btn btn-xs btn-danger">' . $this->language->get('delete_old_version') . '</a>';
		}
		if (!$this->validate()) {
			$data['error_warning']  = $this->language->get('error_permission_instructions');
		}

		if (!$this->checkSupermenuEvents()) {
			$data['error_warning']  = 'The supermenu events dont seem to be registered. Please <a href="'.$this->url->link('extension/module/supermenu', 'registerEvents=1&user_token=' . $this->session->data['user_token'], true).'">click here</a> to rezolve the issue';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/extension', 'type=module&user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/supermenu', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);
		
		if ($data['store']) {
		  $data['action'] = $this->url->link('extension/module/supermenu', 'user_token=' . $this->session->data['user_token'] . '&store=' . $data['store'], true);
		} else {
		  $data['action'] = $this->url->link('extension/module/supermenu', 'user_token=' . $this->session->data['user_token'], true);
		}

		$data['module_url'] = $this->url->link('extension/module/supermenu', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'type=module&user_token=' . $this->session->data['user_token'], true);
		
		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['http_catalog_url'] = HTTP_CATALOG;

		$data['modules'] = array();
		$data['items'] = array();
		$data['categories'] = array();
		$data['informations'] = array();
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC'
		);
		$categ = $this->model_catalog_category->getCategories($filter_data);

		foreach ($categ as $cate) {
							
			$data['categories'][$cate['category_id']] = array(
				'category_id' => $cate['category_id'],
				'name'        => $cate['name']
			);
		}
		
		$infos = $this->model_catalog_information->getInformations();
		
		foreach ($infos as $info) {
							
			$data['informations'][] = array(
				'information_id' => $info['information_id'],
				'name'           => $info['title']
			);
		}
			
		if (isset($this->request->post['supermenu_item'])) {
			$data['items'] = $this->request->post['supermenu_item'];
		} elseif ($this->getSetting('supermenu_item')) { 
			$data['items'] = $this->getSetting('supermenu_item');
		}
		if (isset($this->request->post['supermenu_settings'])) {
			$data['settings'] = $this->request->post['supermenu_settings'];
		} elseif ($this->getSetting('supermenu_settings')) { 
			$data['settings'] = $this->getSetting('supermenu_settings');
		}
		if (isset($this->request->post['supermenu_settings_status'])) {
			$data['supermenu_settings_status'] = $this->request->post['supermenu_settings_status'];
		} elseif ($this->getSetting('supermenu_settings_status')) { 
			$data['supermenu_settings_status'] = $this->getSetting('supermenu_settings_status');
		} else {
		    $data['supermenu_settings_status'] = '';
		}
		if (isset($this->request->post['supermenu_supermenuisresponsive'])) {
			$data['supermenu_supermenuisresponsive'] = $this->request->post['supermenu_supermenuisresponsive'];
		} elseif ($this->getSetting('supermenu_supermenuisresponsive')) { 
			$data['supermenu_supermenuisresponsive'] = $this->getSetting('supermenu_supermenuisresponsive');
		} else {
		    $data['supermenu_supermenuisresponsive'] = 0;
		}
		
		if (isset($this->request->post['supermenu_more_view'])) {
			$data['supermenu_more_view'] = $this->request->post['supermenu_more_view'];
		} elseif ($this->getSetting('supermenu_more_view')) {
			$data['supermenu_more_view'] = $this->getSetting('supermenu_more_view');
		} else {
			$data['supermenu_more_view'] = '';
		}
		
		if (isset($this->request->post['supermenu_more_title'])) {
			$data['supermenu_more_title'] = $this->request->post['supermenu_more_title'];
		} else {
			$data['supermenu_more_title'] = $this->getSetting('supermenu_more_title');
		}
		if (isset($this->request->post['supermenu_more2_title'])) {
			$data['supermenu_more2_title'] = $this->request->post['supermenu_more2_title'];
		} else {
			$data['supermenu_more2_title'] = $this->getSetting('supermenu_more2_title');
		}
		
		if (isset($this->request->post['supermenu_more_status'])) {
			$data['supermenu_more_status'] = $this->request->post['supermenu_more_status'];
		} elseif ($this->getSetting('supermenu_more_status')) {
			$data['supermenu_more_status'] = $this->getSetting('supermenu_more_status');
		} else {
		    $data['supermenu_more_status'] = '';
		}
		if (isset($this->request->post['supermenu_image_width'])) {
			$data['supermenu_image_width'] = $this->request->post['supermenu_image_width'];
		} elseif ($this->getSetting('supermenu_image_width')) {
			$data['supermenu_image_width'] = $this->getSetting('supermenu_image_width');
		} else {
			$data['supermenu_image_width'] = 120;
		}
		if (isset($this->request->post['supermenu_image_height'])) {
			$data['supermenu_image_height'] = $this->request->post['supermenu_image_height'];
		} elseif ($this->getSetting('supermenu_image_height')) {
			$data['supermenu_image_height'] = $this->getSetting('supermenu_image_height');
		} else {
			$data['supermenu_image_height'] = 120;
		}
		if (isset($this->request->post['supermenu_show_description'])) {
			$data['supermenu_show_description'] = $this->request->post['supermenu_show_description'];
		} elseif ($this->getSetting('supermenu_show_description')) {
			$data['supermenu_show_description'] = $this->getSetting('supermenu_show_description');
		} else {
			$data['supermenu_show_description'] = 'no';
		}
		if (isset($this->request->post['supermenu_dropdowntitle'])) {
			$data['supermenu_dropdowntitle'] = $this->request->post['supermenu_dropdowntitle'];
		} elseif ($this->getSetting('supermenu_dropdowntitle')) { 
			$data['supermenu_dropdowntitle'] = $this->getSetting('supermenu_dropdowntitle');
		} else {
		    $data['supermenu_dropdowntitle'] = 0;
		}
		if (isset($this->request->post['supermenu_showstatus'])) {
			$data['supermenu_showstatus'] = $this->request->post['supermenu_showstatus'];
		} elseif ($this->getSetting('supermenu_showstatus')) { 
			$data['supermenu_showstatus'] = $this->getSetting('supermenu_showstatus');
		} else {
		    $data['supermenu_showstatus'] = 0;
		}
		if (isset($this->request->post['supermenu_topitemlink'])) {
			$data['supermenu_topitemlink'] = $this->request->post['supermenu_topitemlink'];
		} elseif ($this->getSetting('supermenu_topitemlink')) { 
			$data['supermenu_topitemlink'] = $this->getSetting('supermenu_topitemlink');
		} else {
		    $data['supermenu_topitemlink'] = 'bottom';
		}
		if (isset($this->request->post['supermenu_skin'])) {
			$data['supermenu_skin'] = $this->request->post['supermenu_skin'];
		} elseif ($this->getSetting('supermenu_skin')) { 
			$data['supermenu_skin'] = $this->getSetting('supermenu_skin');
		} else {
		    $data['supermenu_skin'] = 'default';
		}
		if (isset($this->request->post['supermenu_flyout_width'])) {
			$data['supermenu_flyout_width'] = $this->request->post['supermenu_flyout_width'];
		} elseif ($this->getSetting('supermenu_flyout_width')) {
			$data['supermenu_flyout_width'] = $this->getSetting('supermenu_flyout_width');
		} else {
			$data['supermenu_flyout_width'] = '';
		}
		if (isset($this->request->post['supermenu_mobilemenuname'])) {
			$data['supermenu_mobilemenuname'] = $this->request->post['supermenu_mobilemenuname'];
		} elseif ($this->getSetting('supermenu_mobilemenuname')) {
			$data['supermenu_mobilemenuname'] = $this->getSetting('supermenu_mobilemenuname');
		} else {
		    $data['supermenu_mobilemenuname'] = array();
		}
		if (isset($this->request->post['supermenu_infodname'])) {
			$data['supermenu_infodname'] = $this->request->post['supermenu_infodname'];
		} elseif ($this->getSetting('supermenu_infodname')) {
			$data['supermenu_infodname'] = $this->getSetting('supermenu_infodname');
		} else {
		    $data['supermenu_infodname'] = array();
		}
		if (isset($this->request->post['supermenu_brandsdname'])) {
			$data['supermenu_brandsdname'] = $this->request->post['supermenu_brandsdname'];
		} elseif ($this->getSetting('supermenu_brandsdname')) {
			$data['supermenu_brandsdname'] = $this->getSetting('supermenu_brandsdname');
		} else {
		    $data['supermenu_brandsdname'] = array();
		}
		if (isset($this->request->post['supermenu_latestpname'])) {
			$data['supermenu_latestpname'] = $this->request->post['supermenu_latestpname'];
		} elseif ($this->getSetting('supermenu_latestpname')) {
			$data['supermenu_latestpname'] = $this->getSetting('supermenu_latestpname');
		} else {
		    $data['supermenu_latestpname'] = array();
		}
		if (isset($this->request->post['supermenu_specialpname'])) {
			$data['supermenu_specialpname'] = $this->request->post['supermenu_specialpname'];
		} elseif ($this->getSetting('supermenu_specialpname')) {
			$data['supermenu_specialpname'] = $this->getSetting('supermenu_specialpname');
		} else {
		    $data['supermenu_specialpname'] = array();
		}
		if (isset($this->request->post['supermenu_featuredpname'])) {
			$data['supermenu_featuredpname'] = $this->request->post['supermenu_featuredpname'];
		} elseif ($this->getSetting('supermenu_featuredpname')) {
			$data['supermenu_featuredpname'] = $this->getSetting('supermenu_featuredpname');
		} else {
		    $data['supermenu_featuredpname'] = array();
		}
		if (isset($this->request->post['supermenu_bestpname'])) {
			$data['supermenu_bestpname'] = $this->request->post['supermenu_bestpname'];
		} elseif ($this->getSetting('supermenu_bestpname')) {
			$data['supermenu_bestpname'] = $this->getSetting('supermenu_bestpname');
		} else {
		    $data['supermenu_bestpname'] = array();
		}
		if (isset($this->request->post['supermenu_3dlevellimit'])) {
			$data['supermenu_3dlevellimit'] = $this->request->post['supermenu_3dlevellimit'];
		} elseif ($this->getSetting('supermenu_3dlevellimit')) {
			$data['supermenu_3dlevellimit'] = $this->getSetting('supermenu_3dlevellimit');
		} else {
			$data['supermenu_3dlevellimit'] = '';
		}
		if (isset($this->request->post['supermenu_viewallname'])) {
			$data['supermenu_viewallname'] = $this->request->post['supermenu_viewallname'];
		} elseif ($this->getSetting('supermenu_viewallname')) {
			$data['supermenu_viewallname'] = $this->getSetting('supermenu_viewallname');
		} else {
		    $data['supermenu_viewallname'] = array();
		}
		if (isset($this->request->post['supermenu_viewmorename'])) {
			$data['supermenu_viewmorename'] = $this->request->post['supermenu_viewmorename'];
		} elseif ($this->getSetting('supermenu_viewmorename')) {
			$data['supermenu_viewmorename'] = $this->getSetting('supermenu_viewmorename');
		} else {
		    $data['supermenu_viewmorename'] = array();
		}
		if (isset($this->request->post['supermenu_dropdowneffect'])) {
			$data['supermenu_dropdowneffect'] = $this->request->post['supermenu_dropdowneffect'];
		} elseif ($this->getSetting('supermenu_dropdowneffect')) {
			$data['supermenu_dropdowneffect'] = $this->getSetting('supermenu_dropdowneffect');
		} else {
		    $data['supermenu_dropdowneffect'] = 'drop';
		}
		if (isset($this->request->post['supermenu_usehoverintent'])) {
			$data['supermenu_usehoverintent'] = $this->request->post['supermenu_usehoverintent'];
		} elseif ($this->getSetting('supermenu_usehoverintent')) {
			$data['supermenu_usehoverintent'] = $this->getSetting('supermenu_usehoverintent');
		} else {
			$data['supermenu_usehoverintent'] = '';
		}
		if (isset($this->request->post['supermenu_tophomelink'])) {
			$data['supermenu_tophomelink'] = $this->request->post['supermenu_tophomelink'];
		} elseif ($this->getSetting('supermenu_tophomelink')) { 
			$data['supermenu_tophomelink'] = $this->getSetting('supermenu_tophomelink');
		} else {
		    $data['supermenu_tophomelink'] = 'none';
		}
		if (isset($this->request->post['supermenu_htmlarea1'])) {
			$data['supermenu_htmlarea1'] = $this->request->post['supermenu_htmlarea1'];
		} elseif ($this->getSetting('supermenu_htmlarea1')) {
			$data['supermenu_htmlarea1'] = $this->getSetting('supermenu_htmlarea1');
		} else {
		    $data['supermenu_htmlarea1'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea2'])) {
			$data['supermenu_htmlarea2'] = $this->request->post['supermenu_htmlarea2'];
		} elseif ($this->getSetting('supermenu_htmlarea2')) {
			$data['supermenu_htmlarea2'] = $this->getSetting('supermenu_htmlarea2');
		} else {
		    $data['supermenu_htmlarea2'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea3'])) {
			$data['supermenu_htmlarea3'] = $this->request->post['supermenu_htmlarea3'];
		} elseif ($this->getSetting('supermenu_htmlarea3')) {
			$data['supermenu_htmlarea3'] = $this->getSetting('supermenu_htmlarea3');
		} else {
		    $data['supermenu_htmlarea3'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea4'])) {
			$data['supermenu_htmlarea4'] = $this->request->post['supermenu_htmlarea4'];
		} elseif ($this->getSetting('supermenu_htmlarea4')) {
			$data['supermenu_htmlarea4'] = $this->getSetting('supermenu_htmlarea4');
		} else {
		    $data['supermenu_htmlarea4'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea5'])) {
			$data['supermenu_htmlarea5'] = $this->request->post['supermenu_htmlarea5'];
		} elseif ($this->getSetting('supermenu_htmlarea5')) {
			$data['supermenu_htmlarea5'] = $this->getSetting('supermenu_htmlarea5');
		} else {
		    $data['supermenu_htmlarea5'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea6'])) {
			$data['supermenu_htmlarea6'] = $this->request->post['supermenu_htmlarea6'];
		} elseif ($this->getSetting('supermenu_htmlarea6')) {
			$data['supermenu_htmlarea6'] = $this->getSetting('supermenu_htmlarea6');
		} else {
		    $data['supermenu_htmlarea6'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea7'])) {
			$data['supermenu_htmlarea7'] = $this->request->post['supermenu_htmlarea7'];
		} elseif ($this->getSetting('supermenu_htmlarea7')) {
			$data['supermenu_htmlarea7'] = $this->getSetting('supermenu_htmlarea7');
		} else {
		    $data['supermenu_htmlarea7'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea8'])) {
			$data['supermenu_htmlarea8'] = $this->request->post['supermenu_htmlarea8'];
		} elseif ($this->getSetting('supermenu_htmlarea8')) {
			$data['supermenu_htmlarea8'] = $this->getSetting('supermenu_htmlarea8');
		} else {
		    $data['supermenu_htmlarea8'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea9'])) {
			$data['supermenu_htmlarea9'] = $this->request->post['supermenu_htmlarea9'];
		} elseif ($this->getSetting('supermenu_htmlarea8')) {
			$data['supermenu_htmlarea9'] = $this->getSetting('supermenu_htmlarea9');
		} else {
		    $data['supermenu_htmlarea9'] = array();
		}
		if (isset($this->request->post['supermenu_htmlarea10'])) {
			$data['supermenu_htmlarea10'] = $this->request->post['supermenu_htmlarea10'];
		} elseif ($this->getSetting('supermenu_htmlarea10')) {
			$data['supermenu_htmlarea10'] = $this->getSetting('supermenu_htmlarea10');
		} else {
		    $data['supermenu_htmlarea10'] = array();
		}
		if (isset($this->request->post['supermenu_bannerspace_width'])) {
			$data['supermenu_bannerspace_width'] = $this->request->post['supermenu_bannerspace_width'];
		} elseif ($this->getSetting('supermenu_bannerspace_width')) {
			$data['supermenu_bannerspace_width'] = $this->getSetting('supermenu_bannerspace_width');
		} else {
			$data['supermenu_bannerspace_width'] = '';
		}
		if (isset($this->request->post['supermenu_cache'])) {
			$data['supermenu_cache'] = $this->request->post['supermenu_cache'];
		} elseif ($this->getSetting('supermenu_cache')) { 
			$data['supermenu_cache'] = $this->getSetting('supermenu_cache');
		} else {
		    $data['supermenu_cache'] = 0;
		}
		if (isset($this->request->post['supermenu_container'])) {
			$data['supermenu_container'] = $this->request->post['supermenu_container'];
		} elseif ($this->getSetting('supermenu_container')) { 
			$data['supermenu_container'] = $this->getSetting('supermenu_container');
		} else {
		    $data['supermenu_container'] = 0;
		}
		if (isset($this->request->post['supermenu_wrapper_class'])) {
			$data['supermenu_wrapper_class'] = $this->request->post['supermenu_wrapper_class'];
		} elseif ($this->getSetting('supermenu_wrapper_class')) { 
			$data['supermenu_wrapper_class'] = $this->getSetting('supermenu_wrapper_class');
		} else {
		    $data['supermenu_wrapper_class'] = '';
		}
		
		$this->load->model('catalog/product');
		
		$data['products'] = array();
		
		if (isset($this->request->post['supermenu_fproduct'])) {
			$products = $this->request->post['supermenu_fproduct'];
		} elseif ($this->getSetting('supermenu_fproduct')) {
			$products = $this->getSetting('supermenu_fproduct');
		} else {
			$products = array();
		}	
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}

		$data['featcats'] = $data['feat2cats'] = array();

		if (isset($this->request->post['supermenu_more'])) {
			$supermenu_more = $this->request->post['supermenu_more'];
		} elseif ($this->getSetting('supermenu_more')) {
			$supermenu_more = $this->getSetting('supermenu_more');
		} else {
			$supermenu_more = array();
		}
		if (isset($this->request->post['supermenu_more2'])) {
			$supermenu_more2 = $this->request->post['supermenu_more2'];
		} elseif ($this->getSetting('supermenu_more2')) {
			$supermenu_more2 = $this->getSetting('supermenu_more2');
		} else {
			$supermenu_more2 = array();
		}

		foreach ($supermenu_more as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['featcats'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		foreach ($supermenu_more2 as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['feat2cats'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/supermenu', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/supermenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
        $this->load->model('setting/event');
		$this->model_setting_event->addEvent('so_supermenu', 'catalog/controller/common/header/before', 'extension/module/supermenu/beforeHeader');
		$this->model_setting_event->addEvent('so_supermenu', 'catalog/view/common/header/before', 'extension/module/supermenu/afterHeader');
	}

	public function uninstall() {
        $this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('so_supermenu');
	}

	private function checkSupermenuEvents() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` where code = 'so_supermenu'");
		return $query->num_rows != 2 ? false : true;
	}
}
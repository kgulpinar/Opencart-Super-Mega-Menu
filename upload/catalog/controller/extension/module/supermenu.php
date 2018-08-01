<?php  
class ControllerExtensionModuleSupermenu extends Controller {

	public function index() {
	
		$this->load->model('catalog/category');
	
		$this->load->model('tool/image'); 
		
		$this->load->model('catalog/information');
		
		$this->load->model('catalog/manufacturer');
		
		$this->load->model('catalog/product');
		
		$this->language->load('common/footer');
      
	    $this->language->load('extension/module/category');
		
		$data['skin'] = $this->config->get('supermenu_skin');

		$data['direction'] = $this->language->get('direction');

		$vmname = $this->config->get('supermenu_viewmorename');

		$viewmorecategoriestext = !empty($vmname[$this->config->get('config_language_id')]) ? $vmname[$this->config->get('config_language_id')] : 'view more';	
		
		$vaname = $this->config->get('supermenu_viewallname');

		$data['viewalltext'] = !empty($vaname[$this->config->get('config_language_id')]) ? $vaname[$this->config->get('config_language_id')] : "View All";

		$baname = $this->config->get('supermenu_brandsdname');

		$data['brands_text'] = $newbranddname = !empty($baname[$this->config->get('config_language_id')]) ? $baname[$this->config->get('config_language_id')] : $this->language->get('text_manufacturer');

		$caname = $this->config->get('supermenu_mobilemenuname');

		$data['categ_text'] = !empty($caname[$this->config->get('config_language_id')]) ? $caname[$this->config->get('config_language_id')] : $this->language->get('heading_title');

		$infodrname = $this->config->get('supermenu_infodname');

		$infodrnamenew = !empty($infodrname[$this->config->get('config_language_id')]) ? $infodrname[$this->config->get('config_language_id')] : false;

		$subcatslimit = $this->config->get('supermenu_3dlevellimit') ? $this->config->get('supermenu_3dlevellimit') : false;

		$data['tophomelink'] = $this->config->get('supermenu_tophomelink') ? $this->config->get('supermenu_tophomelink') : 'none';

		$data['linkoftopitem'] = $this->config->get('supermenu_topitemlink') ? $this->config->get('supermenu_topitemlink') : 'topitem';

		$data['dropdowntitle'] = $this->config->get('supermenu_dropdowntitle');

		$data['dropdowneffect'] = $this->config->get('supermenu_dropdowneffect') ? $this->config->get('supermenu_dropdowneffect') : 'fade';

		$data['usehoverintent'] = $this->config->get('supermenu_usehoverintent') ? false : true;

		$data['bootstrap_container'] = $this->config->get('supermenu_container') ? false : true;

		$data['wrapper_class'] = $this->config->get('supermenu_wrapper_class');

		$data['flyout_width'] = $this->config->get('supermenu_flyout_width');

		$data['bspace_width'] = $this->config->get('supermenu_bannerspace_width');
		
		$this->language->load('account/login');

		$this->language->load('extension/module/account');
		
	    $data['mitems'] = array();

		$mitems = array();
		
		$items = $this->config->get('supermenu_item');

		$supermenu_showstatus = $this->config->get('supermenu_showstatus');
		
		if ($items) {
		 	foreach ($items as $iorder) {
				if (isset($iorder['sorder'])) {
            	    if(is_numeric($iorder['sorder'])) { $itemsorder[] = $iorder['sorder']; } else { $itemsorder[] = 99; }
				} else {
					$itemsorder[] = 99;
				}
         	}
		 	array_multisort($itemsorder,SORT_NUMERIC,$items);
		}
		
		$supercache = $this->config->get('supermenu_cache') ? true : false;
		
		$c_items = false;
		
		if ($items && !$supermenu_showstatus) { /*check for items*/
		
			$increaseid = 0;

			if ($supercache) {
                $c_items = $this->cache->get('supermenu.items.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
            } else {
                $c_items= false;
            }
		
		 	if (!$c_items) { /* check for cache */
		
		 	  foreach ($items as $item) { /* loop trough items */
			 
		  		$increaseid++;
		 
		  		$item_name = '';
		 
		  		if ($item['type'] == 'cat') {
		 
           			$katid = $item['category_id']; 
		   
		   			$cssid = 'supcat' . $item['category_id'];
		 
           			$kat_info = $this->model_catalog_category->getCategory($katid);	
		   
		    		if ($kat_info) {
			
		     			$item_name = $kat_info['name'];
			 
			 			if (isset($item['customname'][$this->config->get('config_language_id')]) && strlen($item['customname'][$this->config->get('config_language_id')]) > 2) $item_name = $item['customname'][$this->config->get('config_language_id')];

			 			$item_view = $item['view'];
			 
			 			$item_id = $kat_info['category_id'];
			 
			 			$item_url = $this->url->link('product/category', 'path=' . $item_id);
			 
			 			$firstkids_data = array();
			 
			 			if($item['subcatdisplay'] != 'none') {
			
			 				$firstkids = $this->model_catalog_category->getCategories($item_id);
			 
			 				foreach ($firstkids as $firstkid) {
			 
			  					$secondkids_data = array();
			  
			  					if ($firstkid['image']) {
									$image = $this->model_tool_image->resize($firstkid['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			  					} else {
									$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			  					}

								if ($item['subcatdisplay'] != 'none' && $item['subcatdisplay'] != 'level1') {

			  						$secondkids = $this->model_catalog_category->getCategories($firstkid['category_id']);
			  
			  						$countingsubcats = 0;
			  
			  						foreach ($secondkids as $secondkid) {

										$countingsubcats++;
				
										if ($secondkid['image']) {
											$secondimage = $this->model_tool_image->resize($secondkid['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
										} else {
											$secondimage = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
										}

										if (!$subcatslimit) {
											
											$secondkids_data[] = array(
												'category_id' => $secondkid['category_id'],
												'cssid'       => "supcat" . $secondkid['category_id'],
												'name'        => $secondkid['name'],
												'thumb'	      => $secondimage,
												'href'        => $this->url->link('product/category', 'path=' . $item_id . '_' . $firstkid['category_id'] . '_' . $secondkid['category_id'])	
											);	
	
										} elseif ($countingsubcats <= $subcatslimit) {

											$secondkids_data[] = array(
												'category_id' => $secondkid['category_id'],
												'cssid'       => "supcat" . $secondkid['category_id'],
												'name'        => $secondkid['name'],
												'thumb'	      => $secondimage,
												'href'        => $this->url->link('product/category', 'path=' . $item_id . '_' . $firstkid['category_id'] . '_' . $secondkid['category_id'])	
											);	

										}	
			  						} 

									if ($subcatslimit && $item_view != 'f0' && $item_view != 'f1') {

				 						if ($subcatslimit < $countingsubcats) {

				    						$secondkids_data[] = array(
												'category_id' => '',
												'cssid'       => "supcat-more-button",
												'name'        => $viewmorecategoriestext,
												'thumb'	      => '',
												'href'        => $this->url->link('product/category', 'path=' . $item_id . '_' . $firstkid['category_id'])	
											);	

				 						}
									}
								}
			   
			  					$firstkids_data[] = array(
									'category_id' => $firstkid['category_id'],
									'cssid'       => "supcat" . $firstkid['category_id'],
									'name'        => $firstkid['name'],
									'thumb'       => $image,
									'gchildren'   => $secondkids_data,
									'href'        => $this->url->link('product/category', 'path=' . $item_id . '_' . $firstkid['category_id'])	
								);						
			 				}
			 			}
			 
			 			if ($kat_info['image']) {
							$item_image = $this->model_tool_image->resize($kat_info['image'], 100, 100);
			 			} else {
							$item_image = $this->model_tool_image->resize('no_image.png', 100, 100);
			 			}
		   
		    		}
		
		  		} elseif ($item['type'] == 'more' || $item['type'] == 'more2') {
		     
			 		$itm = ($item['type'] == 'more2') ? $this->config->get('supermenu_more2_title') : $this->config->get('supermenu_more_title');

				 	$item_name = !empty($itm[$this->config->get('config_language_id')]) ? $itm[$this->config->get('config_language_id')] : 'More';
			 
			 		$cssid = 'notcat' . $increaseid;
			 
			 		$item_view = $item['view'];
			 
			 		$item_id = '';
			 
			 		$item_url = '';
			 
			 		$firstkids_data = array();
			 
			 		$order = array();

			 		$firstkids = ($item['type'] == 'more2') ? $this->config->get('supermenu_more2') : $this->config->get('supermenu_more');

			 		if (!is_array($firstkids)) $firstkids = array();

			 		if ($subcatslimit && $item_view != 'f0' && $item_view != 'f1') $scatslimit = $subcatslimit;  else  $scatslimit = false;
			 
					foreach ($firstkids as $kid) {
			 
			 			$firstkid = $this->model_catalog_category->getCategory($kid);

			 			if($firstkid) {

			  				$secondkids_data = array();
			
			  				if ($firstkid['image']) {
								$image = $this->model_tool_image->resize($firstkid['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
             				} else {
								$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			  				}

			  				if($item['subcatdisplay'] != 'none') {

			  					$secondkids = $this->model_catalog_category->getCategories($firstkid['category_id']);
			  					
			  					$countingsubcatsk = 0;
			  
			  					foreach ($secondkids as $secondkid) {
			   
			   						$countingsubcatsk++;
			    					
			    					$thirdkids_data = array();
			   						
			   						if ($item['subcatdisplay'] != 'none' && $item['subcatdisplay'] != 'level1') {
			    						
			    						$thirdkids = $this->model_catalog_category->getCategories($secondkid['category_id']);
				 						
				 						$countingsubcats2 = 0;
										
										foreach ($thirdkids as $thirdkid) {
				 					
				 							$countingsubcats2++;
											
											if (!$subcatslimit) {
											
												$thirdkids_data[] = array(
													'category_id' => $thirdkid['category_id'],
													'cssid'       => "morecatc" . $thirdkid['category_id'],
													'name'        => $thirdkid['name'],
													'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'] . '_' . $secondkid['category_id'] . '_' . $thirdkid['category_id'])	
												);

											} elseif ($countingsubcats2 <= $subcatslimit) {
												
												$thirdkids_data[] = array(
													'category_id' => $thirdkid['category_id'],
													'cssid'       => "morecatc" . $thirdkid['category_id'],
													'name'        => $thirdkid['name'],
													'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'] . '_' . $secondkid['category_id'] . '_' . $thirdkid['category_id'])	
												);

											}

										}
										
										if ($subcatslimit && ($subcatslimit < $countingsubcats2)) {	
												
											$thirdkids_data[] = array(
												'category_id' => '',
												'cssid'       => "supcat-more-button",
												'name'        => $viewmorecategoriestext,
												'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'] . '_' . $secondkid['category_id'])	
											);
										
										}
					
			   						}

			   						if ($secondkid['image']) {
										$secondimage = $this->model_tool_image->resize($secondkid['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
               						} else {
										$secondimage = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			   						}

			   						if (!$scatslimit) {
			    						$secondkids_data[] = array(
											'category_id' => $secondkid['category_id'],
											'cssid'       => "morecat" . $secondkid['category_id'],
											'name'        => $secondkid['name'],
											'thumb'       => $secondimage,
											'ggchildren'  => $thirdkids_data,
											'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'] . '_' . $secondkid['category_id'])	
										);	
			   						} elseif ($countingsubcatsk <= $scatslimit) {
			     						$secondkids_data[] = array(
											'category_id' => $secondkid['category_id'],
											'cssid'       => "morecat" . $secondkid['category_id'],
											'name'        => $secondkid['name'],
											'thumb'       => $secondimage,
											'ggchildren'  => $thirdkids_data,
											'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'] . '_' . $secondkid['category_id'])	
										);	
			   						}
			  
			  					}
			  
								if ($scatslimit && ($scatslimit < $countingsubcatsk)) {	
			     					$secondkids_data[] = array(
										'category_id' => '',
										'cssid'       => "supcat-more-button",
										'name'        => $viewmorecategoriestext,
										'thumb'       => '',
										'ggchildren'  => '',
										'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'])	
									);	

				 				}
							}
			   
			  				$firstkids_data[] = array(
								'category_id' => $firstkid['category_id'],
								'cssid'       => "morecat" . $firstkid['category_id'],
								'name'        => $firstkid['name'],
								'thumb'       => $image,
								'order'       => $firstkid['sort_order'],
								'gchildren'   => $secondkids_data,
								'href'        => $this->url->link('product/category', 'path=' . $firstkid['category_id'])	
							);						
			 			}
					}

			 		foreach ($firstkids_data as $itemsmore) {
                		$order[] = $itemsmore['order'];
             		}
				
					array_multisort($order,SORT_NUMERIC,$firstkids_data);
			 
			 		$item_image = false;
		   
		
		  		} elseif ($item['type'] == 'infol') {
		  
		   			$info_id = $item['information_id']; 
		   
		   			$cssid = 'notcat' . $increaseid;
		   
		   			$item_view = '';
		 
           			$info_info = $this->model_catalog_information->getInformation($info_id);
		   
		   			if ($info_info) {
		    
			 			$item_name = $info_info['title'];
			 
			 			$item_id = $info_info['information_id'];
			 
			 			$item_url = $this->url->link('information/information', 'information_id=' . $item_id);
			 
			 			$firstkids_data = array();
			 
			 			$item_image = false;
			
		   			}
			
				} elseif ($item['type'] == 'infod') {
					
					$item_name = $infodrnamenew ? $infodrnamenew : $this->language->get('text_information');
			
					$item_view = '';
			 
			 		$cssid = 'notcat' . $increaseid;
			 
			 		$item_id = '';
			 
			 		$item_url = '';
			 
			 		$firstkids_data = array();
			 
			 		foreach ($this->model_catalog_information->getInformations() as $infolinks) {
			 
						$firstkids_data[] = array(
							'category_id' => false,
							'cssid'       => "supinfo" . $infolinks['information_id'],
							'name'        => $infolinks['title'],
							'gchildren'   => false,
							'href'        => $this->url->link('information/information', 'information_id=' . $infolinks['information_id'])
						);
					
					}
			 
			 		$item_image = false;
		
		  		} elseif ($item['type'] == 'mand') {

		  			$item_name = $newbranddname;
			 
			 		$item_view = $item['view'];
			 
			 		$cssid = 'notcat' . $increaseid;
			 
					$item_id = '';
			 
			 		$item_url = '';
			 
			 		$firstkids_data = array();
			 
			 		foreach ($this->model_catalog_manufacturer->getManufacturers() as $brandlinks) {
			 
			     		if ($brandlinks['image']) {
				  			$image = $this->model_tool_image->resize($brandlinks['image'],  $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
                 		} else {
			 	  			$image = $this->model_tool_image->resize('no_image.png',  $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			     		}

			    		
                    	$firstkids_data[] = array(
							'category_id' => false,
							'name'        => $brandlinks['name'],
							'cssid'       => "supbrand" . $brandlinks['manufacturer_id'],
							'thumb'       => $image,
							'gchildren'   => false,
							'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $brandlinks['manufacturer_id'])
						);				
    	     		}	
			 
			 		$item_image = false;
			 
			 		$item_description = false;
		
		  		} elseif ($item['type'] == 'products') {
		  
			 		$item_view = $item['view'];
			 
			 		$item_id = '';
			 
					$cssid = 'notcat' . $increaseid;
			 
			 		$item_url = '';
			 
			 		$firstkids_data = array();
			
					if ($item['products'] == 'special') {
			
						$itm = $this->config->get('supermenu_specialpname');
			 			$item_name = !empty($itm[$this->config->get('config_language_id')]) ? $itm[$this->config->get('config_language_id')] : 'Special Offers';
			 
			 			$sdata = array(
							'sort'  => 'pd.name',
							'order' => 'ASC',
							'start' => 0,
							'limit' => $item['productlimit']
		     			);

			 			$productresults = $this->model_catalog_product->getProductSpecials($sdata);
			
					} elseif ($item['products'] == 'featured') {

			 			$itm = $this->config->get('supermenu_featuredpname');
						$item_name = !empty($itm[$this->config->get('config_language_id')]) ? $itm[$this->config->get('config_language_id')] : 'Featured Products';

						$productresults = $this->config->get('supermenu_fproduct') ? array_slice($this->config->get('supermenu_fproduct'), 0, (int)$item['productlimit']) : array();

					} elseif ($item['products'] == 'bestseller') {

			 			$itm = $this->config->get('supermenu_bestpname');
						$item_name = !empty($itm[$this->config->get('config_language_id')]) ? $itm[$this->config->get('config_language_id')] : 'BestSellers';

			 			$productresults = $this->model_catalog_product->getBestSellerProducts($item['productlimit']);

					} else {

			 			$itm = $this->config->get('supermenu_latestpname');
						$item_name = !empty($itm[$this->config->get('config_language_id')]) ? $itm[$this->config->get('config_language_id')] : 'Latest Products';

			 			$sdata = array(
							'sort'  => 'p.date_added',
							'order' => 'DESC',
							'start' => 0,
							'limit' => $item['productlimit']
						);
			 			
			 			$productresults = $this->model_catalog_product->getProducts($sdata);
			
					}

					if ($item['products'] == 'featured') {
			
						foreach ($productresults as $product_id) {
			
							$product_info = $this->model_catalog_product->getProduct($product_id);
			
							if ($product_info) {
								
								if ($product_info['image']) {
									$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
								} else {
									$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
								}

								if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
									if (version_compare(VERSION, '2.2.0.0') >= 0) {
										$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
									} else {
										$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
									}
								} else {
									$price = false;
								}
						
								if ((float)$product_info['special']) {
									if (version_compare(VERSION, '2.2.0.0') >= 0) {
										$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
									} else {
										$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
									}
								} else {
									$special = false;
								}
				
								$secondkids_data = array();

								$firstkids_data[] = array(
									'category_id' => $product_info['product_id'],
									'cssid'       => "morecat" . $product_info['product_id'],
									'name'        => $product_info['name'],
									'thumb'       => $image,
									'price'   	  => $price,
									'special' 	  => $special,
									'gchildren'   => $secondkids_data,
									'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])	
								);
							}
						}
					} else {
			
						foreach ($productresults as $product_info) {
							
							if ($product_info['image']) {
								$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
							} else {
								$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
							}

							if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
								if (version_compare(VERSION, '2.2.0.0') >= 0) {
									$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
									$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
								}
							} else {
								$price = false;
							}
						
							if ((float)$product_info['special']) {
								if (version_compare(VERSION, '2.2.0.0') >= 0) {
									$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
									$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
								}
							} else {
								$special = false;
							}
				
							$secondkids_data = array();

							$firstkids_data[] = array(
								'category_id' => $product_info['product_id'],
								'cssid'       => "morecat" . $product_info['product_id'],
								'name'        => $product_info['name'],
								'thumb'       => $image,
								'price'   	  => $price,
								'special' 	  => $special,
								'gchildren'   => $secondkids_data,
								'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])	
							);
						}
					}
			 
			 		$item_image = false;
		   
				} elseif ($item['type'] == 'catprods') {
		 
		   			$katid = $item['category_id']; 
		   
		   			$cssid = 'supcat' . $item['category_id'];
		 
           			$kat_info = $this->model_catalog_category->getCategory($katid);	
		   
		  			if ($kat_info) {
			
		     			$item_name = $kat_info['name'];
			 
			 			$item_view = $item['view'];
			 
			 			$item_id = $kat_info['category_id'];
			 
			 			$item_url = $this->url->link('product/category', 'path=' . $item_id);
			 
			 			$firstkids_data = array();
			
			 			$sdata = array(
							'sort'  => 'p.date_added',
							'filter_category_id' => $item_id,
							'order' => 'DESC',
							'start' => 0,
							'limit' => $item['productlimit']
			 			);

			 			$productresults = $this->model_catalog_product->getProducts($sdata);
			
						foreach ($productresults as $product_info) {
				
							if ($product_info['image']) {
								$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
							} else {
								$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
							}

							if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
								if (version_compare(VERSION, '2.2.0.0') >= 0) {
									$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
									$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
								}
							} else {
								$price = false;
							}
						
							if ((float)$product_info['special']) {
								if (version_compare(VERSION, '2.2.0.0') >= 0) {
									$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
									$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
								}
							} else {
								$special = false;
							}
				
							$secondkids_data = array();
				
							$firstkids_data[] = array(
								'category_id' => $product_info['product_id'],
								'cssid'       => "morecat" . $product_info['product_id'],
								'name'        => $product_info['name'],
								'thumb'       => $image,
								'price'   	  => $price,
								'special' 	  => $special,
								'gchildren'   => $secondkids_data,
								'href'        => $this->url->link('product/product', 'path=' . $item_id . '&product_id=' . $product_info['product_id'])	
							);
						}
			 
			 			if ($kat_info['image']) {
							$item_image = $this->model_tool_image->resize($kat_info['image'], 100, 100);
			 			} else {
							$item_image = $this->model_tool_image->resize('no_image.png', 100, 100);
			 			}
		   
		  			}
				} elseif ($item['type'] == 'catcatprods') {
		 
		   			$katid = $item['category_id']; 
		   
		   			$cssid = 'supcat' . $item['category_id'];
		 
           			$kat_info = $this->model_catalog_category->getCategory($katid);	
		   
		  			if ($kat_info) {
			
		     			$item_name = $kat_info['name'];
			 
			 			$item_view = $item['view'];
			 
			 			$item_id = $kat_info['category_id'];
			 
			 			$item_url = $this->url->link('product/category', 'path=' . $item_id);
			 
			 			$firstkids_data = array();

			 			$firstkids = $this->model_catalog_category->getCategories($item_id);
			 
			 			foreach ($firstkids as $firstkid) {
			 
			  				$secondkids_data = array();
			  
			  				if ($firstkid['image']) {
								$image = $this->model_tool_image->resize($firstkid['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			  				} else {
								$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
			  				}

							//products fetch
							$sdata = array(
								'sort'  => 'p.date_added',
								'filter_category_id' => $firstkid['category_id'],
								'order' => 'DESC',
								'start' => 0,
								'limit' => $item['productlimit']
			 				);

			 				$productresults = $this->model_catalog_product->getProducts($sdata);
			
							foreach ($productresults as $product_info) {
				
								if ($product_info['image']) {
									$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
								} else {
									$image = $this->model_tool_image->resize('no_image.png', $this->config->get('supermenu_image_width'), $this->config->get('supermenu_image_height'));
								}

								if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
									if (version_compare(VERSION, '2.2.0.0') >= 0) {
										$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
									} else {
										$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
									}
								} else {
									$price = false;
								}
						
								if ((float)$product_info['special']) {
									if (version_compare(VERSION, '2.2.0.0') >= 0) {
										$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
									} else {
										$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
									}
								} else {
									$special = false;
								}
				
								$secondkids_data[] = array(
									'category_id' => $product_info['product_id'],
									'cssid'       => "morecat" . $product_info['product_id'],
									'name'        => $product_info['name'],
									'thumb'       => $image,
									'price'   	  => $price,
									'special' 	  => $special,
									'ggchildren'   => array(),
									'href'        => $this->url->link('product/product', 'path=' . $item_id . '_' . $firstkid['category_id'] . '&product_id=' . $product_info['product_id'])	
								);
							}
			   
			  				$firstkids_data[] = array(
								'category_id' => $firstkid['category_id'],
								'cssid'       => "supcat" . $firstkid['category_id'],
								'name'        => $firstkid['name'],
								'thumb'       => $image,
								'gchildren'   => $secondkids_data,
								'href'        => $this->url->link('product/category', 'path=' . $item_id . '_' . $firstkid['category_id'])	
							);						
			 			}
			 
			 			if ($kat_info['image']) {
							$item_image = $this->model_tool_image->resize($kat_info['image'], 100, 100);
			 			} else {
							$item_image = $this->model_tool_image->resize('no_image.png', 100, 100);
			 			}
		   
		  			}
		
		 		} elseif($item['type'] == 'login') {
			
		     		$item_name = $this->language->get('button_login');
			 
			 		$item_view = '';
			 
			 		$item_id = '';
			 
			 		$cssid = 'login_drop';
			  
			 		if ($this->customer->isLogged()) {
			     		$item_url = $this->url->link('account/account', '', 'SSL');
				 		$item_name = $this->language->get('heading_title');
			 		} else {
			 			$item_url = $this->url->link('account/login', '', 'SSL');
			 		}
			  
			 		$firstkids_data = array();
			 
			 		$item_image = false;
			 
			
		  		} else {
		  
		     		$item_name = $item['customname'][$this->config->get('config_language_id')];
			 
			 		$item_view = '';
			 
			 		$item_id = '';
			 
			 		$cssid = 'notcat' . $increaseid;
			 
			 		$item_url = $item['customurl'][$this->config->get('config_language_id')];
			 
			 		$firstkids_data = array();
			 
			 		$item_image = false;
		  
		  		}
		  
		  		$item_addurl = $item['addurl'][$this->config->get('config_language_id')];
			 
		  		$item_topimg = '';
		  
		  		if ($item_name) {

			   		if (!$item_url && isset($item['customurl'][$this->config->get('config_language_id')]) && $item['customurl'][$this->config->get('config_language_id')]) $item_url = $item['customurl'][$this->config->get('config_language_id')];
		  
		  			$mitems[] = array(
						'name'        => $item_name,
						'id'          => $item_id,
						'cssid'       => $cssid,
						'children'    => $firstkids_data,
						'image'       => $item_image,
						'view'        => $item_view,
						'add'         => $item['image'],
						'addurl'      => $item_addurl,
						'href'        => $item_url,
						'tlcolor'     => $item['tlcolor'],
						'tlstyle'     => $item['tlstyle'],
						'chtml'       => $item['chtml'],
						'dwidth'      => $item['dwidth'],
						'iwidth'      => $item['iwidth'],
						'fbrands'     => $item['fbrands'],
						'tntab'       => !empty($item['tntab']) ? true : false,
						'mfly'        => !empty($item['mfly']) ? true : false,
						'mflyl'       => !empty($item['mflyl']) ? true : false,
						'item_topimg' => $item_topimg,
						'type' 		  => $item['type'],
						'cchtml'      => $item['cchtml']
			 		);
			
		  		}	

			  } /* end loop trough items */
			  if ($supercache && $mitems) { /* set cache if enabled */
                $this->cache->set('supermenu.items.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $mitems);
              }

	   		} /* end check for cache */

        } /* end check for items */
		
		$mitems = $c_items ? $c_items : $mitems;
		
		foreach ($mitems as $item) { /* loop again trough items to not include html areas in cache */
			
			if ($item['cchtml'] && $item['chtml']) { 

				$itemarea = $this->config->get('supermenu_html'.$item['cchtml']);

				if (isset($itemarea[$this->config->get('config_language_id')])) {

					$cchtml = html_entity_decode($itemarea[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

				} else {

					$cchtml = '';

				}

			} else {

				$cchtml = '';

			}
			
			$brandsinitem = array();
			
			if ($item['fbrands']) {
				$brandsids = explode(',', $item['fbrands']);
			} else {
				$brandsids  = array();
			}
			
		    foreach ($brandsids as $brandsid) {
					$brand_info = $this->model_catalog_manufacturer->getManufacturer($brandsid);
					if ($brand_info) {
							$brandsinitem[] = array(
								'name' => $brand_info['name'],
								'manufacturer_id' => $brand_info['manufacturer_id'],
								'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $brand_info['manufacturer_id'])
							);
					}
			}
			
			if ($item['dwidth']) {
				$dwidth = $item['dwidth'];
			} else {
				$dwidth = '';
			}
			
			if($data['skin'] == "imgmenu") {
				$item_add = '';
				$item_topimg_pre = isset($item['add'][$this->config->get('config_language_id')]) ? $item['add'][$this->config->get('config_language_id')] : '';
				if($item_topimg_pre) {
					$item_topimg = $this->model_tool_image->resize($item_topimg_pre, 75, 75);
				} else {
					$item_topimg = $this->model_tool_image->resize('no_image.png', 75, 75);
				}
			} else {
				$item_add = isset($item['add'][$this->config->get('config_language_id')]) ? $item['add'][$this->config->get('config_language_id')] : '';
				$item_topimg = '';
			}
				
			$data['mitems'][] = array(
				'name'        => $item['name'],
				'id'          => $item['id'],
				'cssid'       => $item['cssid'],
				'children'    => $item['children'],
				'image'       => $item['image'],
				'view'        => $item['view'],
				'add'         => $item_add,
				'addurl'      => $item['addurl'],
				'href'        => $item['href'],
				'tlcolor'     => $item['tlcolor'],
				'tlstyle'     => $item['tlstyle'],
				'chtml'       => $item['chtml'],
				'dwidth'      => $dwidth,
				'iwidth'      => $item['iwidth'],
				'fbrands'     => $brandsinitem,
				'tntab'       => !empty($item['tntab']) ? true : false,
				'mfly'        => !empty($item['mfly']) ? true : false,
				'mflyl'       => !empty($item['mflyl']) ? true : false,
				'item_topimg' => $item_topimg,
				'cchtml'      => $cchtml
			);
		}
		
		 /* in case the account dropdown is enabled */	
		$data['text_register'] = $this->language->get('text_register');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['button_login'] = $this->language->get('button_login');
		$data['actiond'] = $this->url->link('account/login', '', 'SSL');
		$data['registerd'] = $this->url->link('account/register', '', 'SSL');
		$data['forgottend'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['text_logout'] = $this->language->get('text_logout');
	    $data['text_account'] = $this->language->get('text_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['logged'] = $this->customer->isLogged();
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['edit'] = $this->url->link('account/edit', '', 'SSL');
		$data['password'] = $this->url->link('account/password', '', 'SSL');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['return'] = $this->url->link('account/return', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
		
		return $this->load->view('extension/module/supermenu', $data);
  	}

  	public function css() { 
        header("Content-Type: text/css");
		$skin = $this->config->get('supermenu_skin') != 'default' ? $this->config->get('supermenu_skin') : '';
		$supermenu_settings = $this->config->get('supermenu_settings');
		$output = '';

		if ($this->config->get('supermenu_settings_status')) {
			$main_selector = '#supermenu' . ($skin ? ('.'.$skin) : '');
			$mobile_selector = '#supermenu.respsmall' . ($skin ? ('.'.$skin) : '');

			if ($supermenu_settings['fontf']) {  
 				$output .=  $main_selector . "{ font-family: " . $supermenu_settings['fontf'] . "; } ";
			} 
			if ($supermenu_settings['topfont']) { 
 				$output .= $main_selector . "ul li a.tll { font-size: " . $supermenu_settings['topfont'] . "; } ";
			} 
			if ($supermenu_settings['dropfont']) { 
 				$output .= $main_selector . "  ul li div a { font-size: ". $supermenu_settings['dropfont'] . " !important; } ";
			} 
			if ($supermenu_settings['bg'] && $supermenu_settings['bg2']) { 
 				$output .= $main_selector . " { 
    				background-color: " . $supermenu_settings['bg'] . "; 
					background-image: linear-gradient(to bottom,  " . $supermenu_settings['bg'] . " ,  " . $supermenu_settings['bg2']. " );
					background-repeat: repeat-x;
					border: none;
					-moz-box-shadow: none;
					-webkit-box-shadow: none;
					box-shadow: none; } ";
			} elseif ($supermenu_settings['bg'] && !$supermenu_settings['bg2']) { 
 				$output .= $main_selector . " { background: " . $supermenu_settings['bg'] . ";
					border: none;
					-moz-box-shadow: none;
					-webkit-box-shadow: none;
					box-shadow: none; }";
			} elseif (!$supermenu_settings['bg'] && $supermenu_settings['bg2']) { 
 				$output .= $main_selector . "  { background: " . $supermenu_settings['bg2'] . ";
				border: none;
				-moz-box-shadow: none;
				-webkit-box-shadow: none;
				box-shadow: none; } ";
			} 
			if ($supermenu_settings['tmborderpx'] && $supermenu_settings['tmborders'] && $supermenu_settings['tmbordero'] && $supermenu_settings['tmborderc'] && $supermenu_settings['tmborderpx'] != 'default') { 
				if ($supermenu_settings['tmbordero'] == 'all-around') { 
					$output .= $main_selector . " { border: " . $supermenu_settings['tmborderpx'] . " " . $supermenu_settings['tmborders'] . " " . $supermenu_settings['tmborderc'] . ";} "; 
				} else {
					$output .= $main_selector . " { border-" . $supermenu_settings['tmbordero'] .": " . $supermenu_settings['tmborderpx'] . " " . $supermenu_settings['tmborders'] . " " . $supermenu_settings['tmborderc'] . ";} "; 	
				} 
			}
			if ($supermenu_settings['tlc']) { 
 				$output .= $main_selector . " ul li a.tll { color: " . $supermenu_settings['tlc'] . ";} ";
			} 
			if ($supermenu_settings['tlch']) { 
 				$output .= $main_selector . " ul li.tlli:hover a.tll { color: " . $supermenu_settings['tlch']. ";} ";
 			} 
			if ($supermenu_settings['tlcts']) { 
 				$output .= $main_selector . " ul li a.tll { text-shadow: 0px 1px 1px " . $supermenu_settings['tlcts'] . ";} ";
			} 
			if ($supermenu_settings['tlchts']) { 
 				$output .= $main_selector . " ul li.tlli:hover a.tll { text-shadow: 0px 1px 1px " . $supermenu_settings['tlchts'] . ";} ";
			} 
			if ($supermenu_settings['tlb']) { 
 				$output .= $main_selector . " ul li.tlli:hover a.tll { background: " . $supermenu_settings['tlb'] . ";} ";
			} 
			if ($supermenu_settings['dbg']) { 
 				$output .= $main_selector . " ul li div.bigdiv { background: " . $supermenu_settings['dbg'] . ";}";
				if (!$supermenu_settings['fybg']) { 
 					$output .= $main_selector . " ul li div.bigdiv.withflyout > .withchildfo > .flyouttoright { background: " . $supermenu_settings['dbg'] . ";}";
				} 
			} 
			if ($supermenu_settings['slborderpx'] && $supermenu_settings['slborders'] && $supermenu_settings['slbordero'] && $supermenu_settings['slborderc'] && $supermenu_settings['slborderpx'] != 'default') { 
				if ($supermenu_settings['slbordero'] == 'all-around') { 
					$output .= $main_selector . "  ul li div.bigdiv { border: " . $supermenu_settings['slborderpx'] . " " . $supermenu_settings['slborders'] . " " . $supermenu_settings['slborderc'] . ";} "; 
				} else {
					$output .= $main_selector . "  ul li div.bigdiv { border: none; border-" . $supermenu_settings['slbordero'] . ": " . $supermenu_settings['slborderpx'] . " " . $supermenu_settings['slborders'] . " " . $supermenu_settings['slborderc'] . ";} "; 		
				} 
			}

			if ($supermenu_settings['dic']) { 
 				$output .= $main_selector . " ul li div .withchild a.theparent, " . $main_selector . " ul li div .dropbrands ul li a, " . $main_selector . " ul li div .withimage .name a { color: " . $supermenu_settings['dic'] . ";} ";
			} 
			if ($supermenu_settings['dich']) { 
 				$output .= $main_selector . " ul li div .withchild a.theparent:hover, " . $main_selector . " ul li div .withimage .name a:hover, " . $main_selector . " ul li div .dropbrands ul li a:hover { color: " . $supermenu_settings['dich'] . ";} ";
			} 
			if ($supermenu_settings['dib']) { 
 				$output .= $main_selector . " ul li div .withchild a.theparent { background: " . $supermenu_settings['dib'] . ";} ";
			} 
			if ($supermenu_settings['dibh']) { 
 				$output .= $main_selector . " ul li div .withchild a.theparent:hover { background: " . $supermenu_settings['dibh'] . ";} ";
			} 
			if ($supermenu_settings['diborderpx'] && $supermenu_settings['diborders'] && $supermenu_settings['dibordero'] && $supermenu_settings['diborderc'] && $supermenu_settings['diborderpx'] != 'default') { 
				if ($supermenu_settings['dibordero'] == 'all-around') { 
					$output .= $main_selector . " ul li div .withchild a.theparent { border: " . $supermenu_settings['diborderpx'] . " " . $supermenu_settings['diborders'] . " " . $supermenu_settings['diborderc'] . ";} ";
				} else {
					$output .= $main_selector . " ul li div .withchild a.theparent { border-" . $supermenu_settings['dibordero'] . ": " . $supermenu_settings['diborderpx'] . " " . $supermenu_settings['diborders'] . " " . $supermenu_settings['diborderc'] . ";} ";		
				} 
			}

			if ($supermenu_settings['slc']) { 
			 	$output .= $main_selector . " ul li div .withchild ul.child-level li a, " .  $main_selector . " ul li div .withimage .name ul a { color: " . $supermenu_settings['slc'] . ";} ";
			} 
			if ($supermenu_settings['slch']) { 
			 	$output .= $main_selector . " ul li div .withchild ul.child-level li a:hover, " .  $main_selector . " ul li div .withimage .name ul a:hover { color: " . $supermenu_settings['slch'] . ";} ";
			} 
			if ($supermenu_settings['slb']) { 
			 	$output .= $main_selector . " ul li div .withchild ul.child-level li a { background: " . $supermenu_settings['slb'] . ";} ";
			} 
			if ($supermenu_settings['slbh']) { 
			 	$output .= $main_selector . " ul li div .withchild ul.child-level li a:hover { background: " . $supermenu_settings['slbh'] . ";} ";
			} 
			if ($supermenu_settings['fybg']) { 
			 	$output .= $main_selector . " ul li div.bigdiv.withflyout > .withchildfo > .flyouttoright { background: " . $supermenu_settings['fybg'] . ";} ";
			} 
			if ($supermenu_settings['flyic']) { 
			 	$output .= $main_selector . " .withchildfo > a.theparent { color: " . $supermenu_settings['flyic'] . ";} ";
			} 
			if ($supermenu_settings['flyich']) { 
			 	$output .= $main_selector . " .withchildfo:hover > a.theparent { color: " . $supermenu_settings['flyich'] . ";} ";
			} 
			if ($supermenu_settings['flyiborderpx'] && $supermenu_settings['flyiborders'] && $supermenu_settings['flyibordero'] && $supermenu_settings['flyiborderc'] && $supermenu_settings['flyiborderpx'] != 'default') { 
				if ($supermenu_settings['flyibordero'] == 'all-around') { 
					$output .= $main_selector . " .withchildfo { border: " . $supermenu_settings['flyiborderpx'] . " " . $supermenu_settings['flyiborders'] . " " . $supermenu_settings['flyiborderc'] . ";} "; 
				} else {
					$output .= $main_selector . " .withchildfo { border-" . $supermenu_settings['flyibordero'] . ": " . $supermenu_settings['flyiborderpx'] . " " . $supermenu_settings['flyiborders'] . " " . $supermenu_settings['flyiborderc'] . ";} "; 		
				} 
			}
			if (!empty($supermenu_settings['expbm'])) { 
				$output .= $mobile_selector . " .superdropper span, ". $mobile_selector ." .withchildfo.hasflyout .superdropper span { background-color: " . $supermenu_settings['expbm'] . ";} ";
			} 
			if (!empty($supermenu_settings['expbmc'])) { 
				$output .= $mobile_selector . " .superdropper span, ". $mobile_selector ." .withchildfo.hasflyout .superdropper span { color:" . $supermenu_settings['expbmc'] . ";} ";
			}  
 			if (!empty($supermenu_settings['expbme'])) { 
				$output .= $mobile_selector . " .superdropper span + span, ". $mobile_selector ." .withchildfo.hasflyout.exped .superdropper span + span { background-color: " . $supermenu_settings['expbme'] . ";} ";
 			} 
 			if (!empty($supermenu_settings['expbmec'])) { 
				$output .= $mobile_selector . " .superdropper span + span, ". $mobile_selector ." .withchildfo.hasflyout.exped .superdropper span + span { color: " . $supermenu_settings['expbmec'] . ";} ";
 			} 
 			if ($supermenu_settings['flyib']) { 
 				$output .= $main_selector . "  .withchildfo:hover { background: " . $supermenu_settings['flyib'] . ";} ";
 			} 
 			if ($supermenu_settings['drtc']) { 
 				$output .= $main_selector . "  ul li div.bigdiv .headingoftopitem h2 a, " . $main_selector . "  ul li div.bigdiv .headingoftopitem h2, " . $main_selector . "  ul li div .dropbrands span { color: " . $supermenu_settings['drtc'] . ";} ";
 			} 
 			if ($supermenu_settings['drtborderpx'] && $supermenu_settings['drtborders'] && $supermenu_settings['drtbordero'] && $supermenu_settings['drtborderc'] && $supermenu_settings['drtborderpx'] != 'default') {  
		 		if ($supermenu_settings['drtbordero'] == 'all-around') { 
					$output .= $main_selector . "  ul li div.bigdiv .headingoftopitem, " . $main_selector . "  ul li div .dropbrands span {border: " . $supermenu_settings['drtborderpx'] . " " . $supermenu_settings['drtborders'] . " " . $supermenu_settings['drtborderc'] . ";} ";
		 		} else {
					$output .= $main_selector . "  ul li div.bigdiv .headingoftopitem, " . $main_selector . "  ul li div .dropbrands span {border-" . $supermenu_settings['drtbordero'] . ": " . $supermenu_settings['drtborderpx'] . " " . $supermenu_settings['drtborders'] . " " . $supermenu_settings['drtborderc'] . ";} ";		
		 		} 
			}

 			if ($supermenu_settings['pricec']) { 
				$output .= $main_selector . " ul li div .withimage .dropprice { color: " . $supermenu_settings['pricec'] . ";} ";
 			} 
 			if ($supermenu_settings['pricech']) { 
				$output .= $main_selector . " ul li div .withimage .dropprice span { color: " . $supermenu_settings['pricech'] . ";} ";
 			} 
 			if ($supermenu_settings['valc']) { 
				$output .= $main_selector . " ul li div.bigdiv .linkoftopitem a { color: " . $supermenu_settings['valc'] . ";} ";
 			} 
 			if ($supermenu_settings['valch']) { 
				$output .= $main_selector . " ul li div.bigdiv .linkoftopitem a:hover { color: " . $supermenu_settings['valch'] . ";} ";
 			} 
 			if ($supermenu_settings['valb'] && $supermenu_settings['valb2']) { 
				$output .= $main_selector . " ul li div.bigdiv .linkoftopitem a {
				    background-color: " . $supermenu_settings['valb'] . " ;
				    background-image: linear-gradient(to bottom, " . $supermenu_settings['valb'] . ", " . $supermenu_settings['valb2'] . ");
					background-repeat: repeat-x; } ";
				$output .= $main_selector . "  ul li div.bigdiv .linkoftopitem a:hover {
    				background-color: " . $supermenu_settings['valb2'] . ";
    				background-image: linear-gradient(to bottom, " . $supermenu_settings['valb2'] . ", " . $supermenu_settings['valb'] . ");
					background-repeat: repeat-x; }";
 			} elseif ($supermenu_settings['valb'] && !$supermenu_settings['valb2']) { 
				$output .= $main_selector . " ul li div.bigdiv .linkoftopitem a, " . $main_selector . " ul li div.bigdiv .linkoftopitem a:hover { background-image: none; background-color: " . $supermenu_settings['valb'] . ";} ";
 			} elseif (!$supermenu_settings['valb'] && $supermenu_settings['valb2']) { 
				$output .= $main_selector . " ul li div.bigdiv .linkoftopitem a, " . $main_selector . " ul li div.bigdiv .linkoftopitem a:hover { background-image: none; background-color: " . $supermenu_settings['valb2'] . ";} ";
 			} 
 			if ($supermenu_settings['valborderpx'] && $supermenu_settings['valborders'] && $supermenu_settings['valbordero'] && $supermenu_settings['valborderc'] && $supermenu_settings['valborderpx'] != 'default') { 
	 			if ($supermenu_settings['valbordero'] == 'all-around') { 
					$output .= $main_selector . "  ul li div.bigdiv .linkoftopitem a { border: " . $supermenu_settings['valborderpx'] . " " . $supermenu_settings['valborders'] . " " . $supermenu_settings['valborderc'] . ";} ";
		 		} else {
					$output .= $main_selector . "  ul li div.bigdiv .linkoftopitem a { border: none; border-" . $supermenu_settings['valbordero'] . ": " . $supermenu_settings['valborderpx'] . " " . $supermenu_settings['valborders'] . " " . $supermenu_settings['valborderc'] . ";} ";		
		    	} 
			}
 			if ($supermenu_settings['mmbtc']) { 
				$output .= $mobile_selector . " a.mobile-trigger { color: " . $supermenu_settings['mmbtc'] . ";} ";
 			} 
 			if ($supermenu_settings['mmbtcb']) { 
				$output .= $mobile_selector . " a.mobile-trigger .mt-bars span { background: " . $supermenu_settings['mmbtcb'] . ";} ";
 			} 
 			$output .= $supermenu_settings['custom_css'];

        	echo $output;
        }
    }

    public function beforeHeader(&$route, &$data) {

		if ($this->language->get('direction') == 'rtl') {
			$this->document->addStyle('catalog/view/javascript/supermenu/supermenu-rtl.css?v=30');
			$this->document->addScript('catalog/view/javascript/supermenu/supermenu-responsive-rtl.js?v=30');
		} else {
			$this->document->addStyle('catalog/view/javascript/supermenu/supermenu.css?v=30');
			$this->document->addScript('catalog/view/javascript/supermenu/supermenu-responsive.js?v=30');
		}
		if (!$this->config->get('supermenu_usehoverintent')) {
			$this->document->addScript('catalog/view/javascript/supermenu/jquery.hoverIntent.minified.js');
		}
		if ($this->config->get('supermenu_settings_status')) {
			$this->document->addStyle('index.php?route=extension/module/supermenu/css');
		}
    	
    }

    public function afterHeader(&$route, &$data) {
    	if (!$this->config->get('supermenu_showstatus') && $this->config->get('supermenu_item')) {
    		$data['menu'] = $this->load->controller('extension/module/supermenu');
    	}
    }
}
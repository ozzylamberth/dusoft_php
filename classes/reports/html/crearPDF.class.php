<?php
	class CrearPDF
	{
		function CrearPDF($directorio,$g_baseurl,$destino)
		{
			global $HTML2PS_DIR;
			error_reporting(E_ALL);
			ini_set("display_errors","1");
			@set_time_limit(10000);

			require_once($directorio.'config.inc.php');
			require_once($HTML2PS_DIR.'pipeline.factory.class.php');

			ini_set("user_agent", DEFAULT_USER_AGENT);
			
			global $g_config;
			// Add HTTP protocol if none specified
			if (!preg_match("/^https?:/",$g_baseurl)) {
			  $g_baseurl = 'http://'.$g_baseurl;
			}

			$g_css_index = 0;

			// Title of styleshee to use (empty if no preferences are set)
			$g_stylesheet_title = "";

			// ========== Entry point
			parse_config_file($HTML2PS_DIR.'html2ps.config');

			// validate input data
			if ($g_config['pagewidth'] == 0) {
			  die("Please specify non-zero value for the pixel width!");
			};

			// begin processing

			$g_media = Media::predefined($g_config['media']);
			$g_media->set_landscape($g_config['landscape']);
			$g_media->set_margins($g_config['margins']);
			//$g_media->set_pixels($g_config['pagewidth']);

			// Initialize the coversion pipeline
			$pipeline = new Pipeline();

			// Configure the fetchers
			$pipeline->fetchers[] = new FetcherURL();

			// Configure the data filters
			$pipeline->data_filters[] = new DataFilterDoctype();
			$pipeline->data_filters[] = new DataFilterUTF8($g_config['encoding']);
			if ($g_config['html2xhtml']) {
			  $pipeline->data_filters[] = new DataFilterHTML2XHTML();
			} else {
			  $pipeline->data_filters[] = new DataFilterXHTML2XHTML();
			};

			$pipeline->parser = new ParserXHTML();

			// "PRE" tree filters

			$pipeline->pre_tree_filters = array();

			$header_html    = $g_config['header_html'];
			$footer_html    = $g_config['footer_html'];
			$filter = new PreTreeFilterHeaderFooter($header_html, $footer_html);
			$pipeline->pre_tree_filters[] = $filter;

			if ($g_config['renderfields']) {
			  $pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
			};

			if ($g_config['method'] === 'ps') {
			  $pipeline->layout_engine = new LayoutEnginePS();
			} else {
			  $pipeline->layout_engine = new LayoutEngineDefault();
			};

			$pipeline->post_tree_filters = array();

			// Configure the output format
			if ($g_config['pslevel'] == 3) {
			  $image_encoder = new PSL3ImageEncoderStream();
			} else {
			  $image_encoder = new PSL2ImageEncoderStream();
			};

			switch ($g_config['method']) {
			 case 'fastps':
			   if ($g_config['pslevel'] == 3) {
			     $pipeline->output_driver = new OutputDriverFastPS($image_encoder);
			   } else {
			     $pipeline->output_driver = new OutputDriverFastPSLevel2($image_encoder);
			   };
			   break;
			 case 'pdflib':
			   $pipeline->output_driver = new OutputDriverPDFLIB16($g_config['pdfversion']);
			   break;
			 case 'fpdf':
			   $pipeline->output_driver = new OutputDriverFPDF();
			   break;
			 case 'png':
			   $pipeline->output_driver = new OutputDriverPNG();
			   break;
			 case 'pcl':
			   $pipeline->output_driver = new OutputDriverPCL();
			   break;
			 default:
			   die("Unknown output method");
			};

			// Setup watermark
			$watermark_text = "";
			$pipeline->output_driver->set_watermark($watermark_text);
			//$pipeline->output_driver->setfont("helveltica", "iso-8859-5", "12");
			if ($watermark_text != "") {
			  $dispatcher =& $pipeline->getDispatcher();
			  // @TODO: render watermark in observer
			  $dispatcher->addObserver("after-page", new Observer());
			};

			if ($g_config['debugbox']) {
			  $pipeline->output_driver->set_debug_boxes(true);
			}

			if ($g_config['draw_page_border']) {
			  $pipeline->output_driver->set_show_page_border(true);
			}

			if ($g_config['ps2pdf']) {
			  $pipeline->output_filters[] = new OutputFilterPS2PDF($g_config['pdfversion']);
			}

			if ($g_config['compress'] && $g_config['method'] == 'fastps') {
			  $pipeline->output_filters[] = new OutputFilterGZip();
			}

		  $filename = $g_baseurl;

			$pipeline->destination = new DestinationFile($destino);

			// Start the conversion

			$time = time();
		  $status = $pipeline->process($g_baseurl, $g_media);

			error_log(sprintf("Processing of '%s' completed in %u seconds", $g_baseurl, time() - $time));

			if ($status == null) {
			  print($pipeline->error_message());
			  error_log("Error in conversion pipeline");
			  die();
			}
		}
	}

?>
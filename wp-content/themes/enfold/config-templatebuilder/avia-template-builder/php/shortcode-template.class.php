<?php
/**
* Central Shortcode Template Class
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) { die('-1'); }

if ( ! class_exists( 'aviaShortcodeTemplate' ) ) 
{

	abstract class aviaShortcodeTemplate
	{
		/**
		 *
		 * @var AviaBuilder 
		 */
		public $builder;
		
		/**
		 *
		 * @var array 
		 */
		public $config;
		
		/**
		 *
		 * @var array 
		 */
		public $elements;
		
		/**
		 * Prefix for dynamic popup templates id's
		 * 
		 * @since 4.6.4
		 * @var string 
		 */
		protected $popup_prefix;
		
		
		/**
		 * Stores popup editor element templates for better readability in shortcode element.
		 * Can be used in addition to AviaPopupTemplates()->register_dynamic_template.
		 * 
		 * @since 4.6.4
		 * @var array 
		 */
		protected $popup_templates;

		/**
		 * 
		 * @param AviaBuilder $builder
		 */
		public function __construct( $builder ) 
		{
			$this->builder = $builder;
			$this->elements = array();
			$this->popup_templates = array();
			
			/**
			 * Needed to check and repair shortcode structure
			 * We define all defaults for content elements here, which are the most common elements.
			 * 
			 * first_in_row:		set a string that is added/removed from the element attributes if the element is the first/not the first in a line, e.g. first or foo='bar'
			 * layout_children:		shortcodes that must be included to have a valid element when we need to repair the content of a page. 
			 *						Nested shortcodes must be added, if they are part of the empty element. 
			 *						E.g. tab_section need tab_sub_sections but these are not nested !
			 * forced_load_objects	array of string: e.g. layerslider must be loaded right after init hook, but when we cannot know if we need it because an element is loaded dynamically
			 *						(e.g. postcontent) we can add a unique string to tell layerslider to load, when this shortcode is found in content
			 * id_name				name of id for custom id attribute value - some elements use id for e.g.image ids, so we need to be able to redefine
			 * id_show				'never' | 'yes' | 'always'
			 * custom_css_show		'never' | 'yes' | 'always'
			 * aria_label			'yes' | 'no'		allow user to add a named label for accessibility tree and screen readers
			 * alb_desc_id			name of text field for backend where user can add a description to identify the element
			 */
			$this->config = array(			
							'type'					=>	'content',		//		'layout' | 'content'   needed in syntax error correction
							'self_closing'			=>	'',				//		'yes' | 'no'	if empty base class scans for id="content" in first level of $elements (a fallback for third party elements)
							'contains_text'			=>	'yes',			//		'yes' | 'no'	is plain text allowed in content area of shortcode
							'contains_layout'		=>	'no',			//		'yes' | 'no'	are layout elements allowed in content area of shortcode
							'contains_content'		=>	'no',			//		'yes' | 'no'	are content elements allowed in content area of shortcode
							'first_in_row'			=>	'',				//		'' | attribute to add/remove if first element in a layout line
							'auto_repair'			=>	'yes',			//		'yes' | 'no'	disable for nested parent element if structure of element complex (more than 1 subelement and nested again like av_table)
							'layout_children'		=>	array(),		
							'shortcode_nested'		=>	array(),
							'forced_load_objects'	=>	array(),		//		"name" of external objects that must be included when we find this shortcode in content
							'id_name'				=> 'el_id',
							'id_show'				=> 'never',
							'custom_css_show'		=> 'yes',
							'aria_label'			=> 'no',
							'alb_desc_id'			=> '',
							'linkpickers'			=> array()			//		id's containing links to translate
						);
			
			
			$this->shortcode_insert_button();
			$this->popup_prefix = $this->config['shortcode'] . '_';
			$this->extra_config();
			
			
			
			/**
			 * Add the unique ID field
			 */
			$this->elements[] = array(	
							"name" 	=> __("Unique ID for ALB element",'avia_framework' ),
							"desc" 	=> __("stores the unique ID for the element",'avia_framework' ),
							"id" 	=> aviaElementManager::ELEMENT_UID,
							"type" 	=> "hidden",
							"std" 	=> ''
						);
		
		}
		
		/**
		 * @since 4.2
		 */
		public function __destruct()
		{
			unset( $this->builder );
			unset( $this->config );
			unset( $this->elements );
			unset( $this->popup_templates );
		}

		//init function is executed in AviaBuilder::createShortcode if the shortcode is allowed
		public function init()
		{
			$this->create_asset_array();
			$this->actions_and_filters();
			
			if(is_admin() || AviaHelper::is_ajax())
			{
				$this->admin_assets();
			}
			
			$this->register_shortcodes(); 
			
			//set up loading of assets. wait until post id is known
			add_action('wp', array(&$this, 'extra_asset_check') , 10 );
		}
		
		/**
		 * Returns the version number of the shortcode element implementation
		 * With 4.6.4 visual appearance was changed - allows backward comp for older elements
		 * 
		 * @since 4.6.4
		 * @return string
		 */
		public function get_version()
		{
			return ! empty( $this->config['version'] ) ? $this->config['version'] : '0.0';
		}
		

		/**
		*   shortcode_insert_button: creates the shortcode button for the backend canvas

		*	create the config array. eg:

		*	$this->config['name'] = __('Text', 'avia_framework' ); //defines the name of the button that is displayed below the icon

		*   $this->config['tab'] = __('Layout Elements', 'avia_framework' ); //tab that should hold the button

		*   $this->config['icon'] = $this->builder->imagesURL."full.png"; //icon for the button

		*   $this->config['shortcode'] = 'one_full'; //the shortcode name. this would be the [one_full] shortcode

		*   $this->config['tooltip'] = __('This is a tooltip', 'avia_framework' ); //the tooltip that appears when hovering above the shortcode icon

		*	$this->config['order'] = 40; //order of the button. higher numbers are displayed first

		*	$this->config['target'] = "avia-target-insert"; //if target mode is "avia-target-insert" item will not be added instantly when clicked. other option is avia-section-drop which allos dropping on sections

		*   $this->config['modal_data'] = array('modal_class' => 'mediumscreen'); // data that gets passed to the modal window. eg the class that controlls the modal size

		*	$this->config['modal_on_load'] = array("js", "functions"); //javascript function that should be executed once the modal window has opened

        *   $this->config['shortcode_nested'] = array('av_tab'); // nested shortcodes. needs to be defined if a modal group is used as popup element

		*	$this->config['tinyMCE'] = array('tiny_only'=>true,'instantInsert' => "[asdf]1[/asdf]", 'disable' => true); // show only in tiny mce / do an instant insert instead of modal / disable element in tinymce

		*	$this->config['invisible'] = true; // used to hide the element in builder tab. used for columns eg: 2/5, 3/5 etc

		*	$this->config['html_renderer'] 	= false; //function that renderes the backend editor element.
													 //if set to false no function is used and the output has to be passed by the
													 //"editor_element" function. if not set at all the default function
													 //"create_sortable_editor_element" is used

		* $this->config['drag-level'] = 2; // sets the drag level for an element. drag level must be higher than the drop level of the target, otherwise you cant drop the element onto the other

		* $this->config['drop-level'] = 2; // set the drop level for an element. set drop level to -1 if element shouldnt be dropable


		*/

		abstract function shortcode_insert_button();





		/**
		* holds the function that generates the html code for the frontend
		*/
		abstract function shortcode_handler($atts, $content = '', $shortcodename = '', $meta = '');





		/**
		* function that gets executed if the shortcode is allowed. allows shortcode to load extra assets like css or javascript files in the admin area
		*/

		public function admin_assets(){}
		
		
		/**
		* function that checks if an asset is disabled and if not loads all extra assets
		*/

		public function extra_asset_check()
		{
			//generic check if the assets for this element should be loaded
			if(!is_admin() && ( empty( $this->builder->disabled_assets[ $this->config['shortcode'] ]) || empty( $this->config['disabling_allowed'] ) ) )
			{
				$this->extra_assets();
			}
		}
		
		
		
		/**
		* function that gets executed if the shortcode is allowed. allows shortcode to load extra assets like css or javascript files
		*/

		public function extra_assets(){}


		/**
		 * Scans the $this->elements array recursivly and returns the first element where key = "$element_id"
		 * 
		 * @param string $element_id
		 * @param array $source
		 * @return array|false
		 * @since 4.1.3
		 */
		public function get_popup_element_by_id( $element_id, array &$source = null )
		{
			if( empty( $source ) )
			{
				$source = &$this->elements;
			}
			
			foreach( $source as &$element )
			{
				if( isset( $element['id'] ) && ( $element_id == $element['id'] ) )
				{
					return $element;
				}
				
				if( isset( $element['subelements'] ) && ! empty( $element['subelements'] ) )
				{
					$found = $this->get_popup_element_by_id( $element_id, $element['subelements'] );
					if( false !== $found )
					{
						return $found;
					}
				}
			}
			
			return false;
		}


		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 *
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		public function editor_element( $params )
		{
		    $params['innerHtml'] = '';
		    if(isset($this->config['icon']))
		    {
                $params['innerHtml'] .= "<img src='{$this->config['icon']}' title='{$this->config['name']}' alt='' />";
			}
			
			
			$inner = '';
			
			if( empty( $this->config['alb_desc_id'] ) )
			{
				$inner .=	"<div class='avia-element-label'>{$this->config['name']}</div>";
			}
			else
			{
				$desc = ! empty( $params['args'][ $this->config['alb_desc_id'] ] ) ? $params['args'][ $this->config['alb_desc_id'] ] : '';
				
				$inner .=	"<div class='avia-element-label'>{$this->config['name']} ";
				$inner .=		"<span class='avia-element-label-description' data-update_with='{$this->config['alb_desc_id']}'>";
				$inner .=			html_entity_decode( $desc );
				$inner .=		'</span>';
				$inner .=	'</div>';
			}
				
			$params['innerHtml'] .= $inner;
			return $params;
		}


		/**
		 * function that creates the sets the elements for the popup editor. Not defined by this class but executed if the child class got it
		 * 
		 * Starting with 4.6.4 redesign of layout structure:
		 * 
		 * Main Tabs:
		 *		- Content
		 *		- Layout
		 *		- Styling
		 *		- Advanced (including developer tab)
		 * 
		 * New elements:
		 *		- toggle_container, toggle
		 *		- icon_switcher_container, icon_switcher
		 */
		
		/*example:
			
			function popup_elements()
			{
				$this->elements = array(

					array(
							"type" 	=> "tab_container", 'nodescription' => true
						),
						
					array(
							"type" 	=> "tab",
							"name"	=> __("Content",'avia_framework' ),
							'nodescription' => true
						),
						
						######################################################################
						# Content Tab Content
						######################################################################
						
						
						
						
					array(
							"type" 	=> "tab_close", //close Content tab
							'nodescription' => true
						),
						
						
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Styling",'avia_framework' ),
							'nodescription' => true
						),
							
						######################################################################
						# Styling Tab Content
						######################################################################
						
							array(
								"type" 	=> "toggle_container",
								'nodescription' => true
							),
					
									array(
											"type" 	=> "toggle",
											"name"  => __("Background Colors" , 'avia_framework'),
											'nodescription' => true
										),
									
									array(
											"type" 	=> "toggle",
											"name"  => __("Background Image" , 'avia_framework'),
											'nodescription' => true
										),
										
									array(
											"type" 	=> "toggle",
											"name"  => __("Background Video" , 'avia_framework'),
											'nodescription' => true
										),	
										
									array(
											"type" 	=> "toggle",
											"name"  => __("Background Overlay" , 'avia_framework'),
											'nodescription' => true
										),	
										
									array(
											"type" 	=> "close_div", //close last toggle
											'nodescription' => true
										),
						
							array(
									"type" 	=> "close_div", //close toggle container
									'nodescription' => true
								),
								
					array(
							"type" 	=> "close_div", //close tab
							'nodescription' => true
						),
										
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Advanced",'avia_framework' ),
							'nodescription' => true
						),
							
						######################################################################
						# Advanced Tab Content
						######################################################################
						
						
						array(
								"type" 	=> "toggle_container",
								'nodescription' => true
							),
					
									array(
											"type" 	=> "toggle",
											"name"  => __("Responsive" , 'avia_framework'),
											'nodescription' => true
										),
									
									array(
											"type" 	=> "toggle",
											"name"  => __("Developer" , 'avia_framework'),
											'nodescription' => true
										),
										
									array(
											"type" 	=> "close_div", //close last toggle
											'nodescription' => true
										),
						
							array(
									"type" 	=> "close_div", //close toggle container
									'nodescription' => true
								),
					
					
					array(
							"type" 	=> "close_div", //close last tab
							'nodescription' => true
						),
						
					array(
							"type" 	=> "close_div", //close last tab container
							'nodescription' => true
						),
						
						
						
					array(	"id" 	=> "av_element_hidden_in_editor",
				            "type" 	=> "hidden",
				            "std" => "0"
						),
                );
			}

			
			
		*/

		/**
		 * Returns a key with the shortcode prefix
		 * 
		 * @since 4.6.4
		 * @param string $key
		 * @return string
		 */
		protected function popup_key( $key )
		{
			return $this->popup_prefix . $key;
		}

		/**
		* function that creates the popup editor. only used in classes that have a config array defined by the set_elements class
		* a child class that has the function declared automaticaly gets an edit button in the admin section
		*/
		public function popup_editor( $var )
		{
			if( empty( $this->elements ) )	{ die(); }
			
			if( ( 1 == count( $this->elements ) ) && isset( $this->elements[0]['id'] ) && ( aviaElementManager::ELEMENT_UID == $this->elements[0]['id'] ) )
			{
				die();
			}
			
			/**
			 * We add this field by default and hide it, if option is not activated. Helps to create an element with a custom class and
			 * keep it when this option is deactivated later to avoid modifing it
			 * 
			 * @since 4.5.6.1
			 */
			if( version_compare( $this->get_version(), '1.0', '<' ) )
			{
				$this->get_developer_elements( $this->elements );
			}
			
			if( ! empty( $this->config['preview'] ) )
			{
				$this->elements = $this->avia_custom_preview_bg($this->elements);
			}
			
			$elements = apply_filters( 'avf_template_builder_shortcode_elements', $this->elements );

			//if the ajax request told us that we are fetching the subfunction iterate over the array elements and
			if(!empty($_POST['params']['subelement']))
			{
				foreach($elements as $element)
				{
					if(isset($element['subelements']))
					{
						$elements = $element['subelements'];
						break;
					}
				}
			}
			

			$elements = $this->set_default_values($elements);
			echo AviaHtmlHelper::render_multiple_elements($elements, $this);
			
			die();
		}
		
		/**
		 * Returns a default meta array to avoid undefined index notices in shortcode processing
		 * Make sure to add all needed array elements initialised with default values.
		 * 
		 * @since 4.5.6
		 * @param array|string $atts
		 * @return array
		 */
		protected function default_shortcode_meta( $atts )
		{
			if( ! is_array( $atts ) )
			{
				$atts = array();
			}
			
			$meta = array(
							'el_class'			=> '',
							'custom_el_id'		=> '',
							'custom_id_val'		=> '',
							'aria_label'		=> '',
							'custom_class'		=> '',
							'custom_markup'		=> '',
							'index'				=> -1,
							'this'				=> array(),
							'siblings'			=> array(
														'next'	=> array(),
														'prev'	=> array()
													)
						);
			
			$css_show = ! empty( $this->config['custom_css_show'] ) ? $this->config['custom_css_show'] : 'yes';
			if( 'never' != $css_show )
			{
				if( ! empty( $atts['custom_class'] ) )
				{
					$setting = Avia_Builder()->get_developer_settings( 'custom_css' );
					if( ( 'always' == $css_show ) ||  in_array( $setting, array( 'developer_options', 'hide' ) ) )
					{
						$meta['custom_class'] = AviaHelper::save_classes_string( $atts['custom_class'], '-', 'invalid-custom-class-found' );
					}
				}
			}
			
			$id_show = ! empty( $this->config['id_show'] ) ? $this->config['id_show'] : 'never';
			if( 'never' != $id_show )
			{
				$id_name = ! empty( $this->config['id_name'] ) ? $this->config['id_name'] : 'el_id';
				
				if( ! empty( $atts[ $id_name ] ) )
				{
					$setting = Avia_Builder()->get_developer_settings( 'custom_id' );
					
					if( ( 'always' == $id_show ) || in_array( $setting, array( 'developer_id_attribute', 'hide' ) ) )
					{
						$meta['custom_id_val'] = AviaHelper::save_string( $atts[ $id_name ], '-', '', 'id' );
						$meta['custom_el_id'] = ' id="' . $meta['custom_id_val'] . '" ';
					}
				}
			}
			
			$meta['aria_label'] = ! empty( $atts['aria_label'] ) ? esc_attr( $atts['aria_label'] ) : '';
			
			/**
			 * @since 4.5.6
			 * @param array $meta
			 * @param aviaShortcodeTemplate $this
			 * @param array $atts					@added 4.5.7.2
			 * @return array
			 */
			return apply_filters( 'avf_default_shortcode_meta', $meta, $this, $atts );
		}
		

		/**
		 * Sets some internal variables and counters, then calls the actual shortcode handling function
		 * 
		 * @since < 4.0
		 * @param array $atts
		 * @param string $content
		 * @param string $shortcodename
		 * @param boolean $fake					true if called directly
		 * @return string
		 */
		public function shortcode_handler_prepare( $atts, $content = '', $shortcodename = '', $fake = false )
		{
			global $post;
			
			$current_post = $post instanceof WP_Post ? $post : null;

			/**
			 * Allows to return base page/post in case a second query alters global $post
			 * 
			 * @used_by					config-woocommerce\config.php avia_woocommerce_shortcode_current_post		10
			 * @since 4.5.6
			 * @return null|WP_Post
			 */
			$current_post = apply_filters( 'avf_shortcode_handler_prepare_current_post', $current_post );

			/**
			 * 
			 * @used_by				aviaElementManager					10
			 * @since 4.5.1
			 * @return array
			 */
			$args = array( true, $this, $atts, $content, $shortcodename, $fake );
			apply_filters_ref_array( 'avf_in_shortcode_handler_prepare_start', array( &$args ) );
			if( true !== $args[0] )
			{
				return '';
			}
			
			if( ! empty( $this->config['linkpickers'] ) )
			{
				if( ! is_array( $this->config['linkpickers'] ) )
				{
					$this->config['linkpickers'] = explode( ',', $this->config['linkpickers'] );
				}
				
				foreach( $this->config['linkpickers'] as $linkpicker_id ) 
				{
					$linkpicker_id = trim( $linkpicker_id );
					if( empty( $atts[ $linkpicker_id ] ) )
					{
						continue;
					}
					
					/**
					 * 
					 * @used_by			config-wpml\config.php		avia_translate_alb_linkpicker		10
					 * 
					 * @since 4.6
					 * @added_by Günter
					 * @param string $linkpicker_id
					 * @param array $atts
					 * @param string $shortcode
					 * @param aviaShortcodeTemplate $shortcode_class
					 * @return string
					 */
					$atts[ $linkpicker_id ] = apply_filters( 'avf_alb_linkpicker_value', $atts[ $linkpicker_id ], $linkpicker_id, $atts, $shortcodename, $this );
				}
			}
			
			/**
			 * In modal popup preview mode in backend we only need to execute the shortcode.
			 * Allow hook and force execution of shortcodes e.g. for plugins via an ajax call
			 * 
			 * @since 4.5.7.2
			 * @param boolean
			 * @param aviaShortcodeTemplate $this
			 * @param array $atts
			 * @param string $content
			 * @param string $shortcodename
			 * @param boolean $fake
			 * @return boolean				true if sc should be executd regardless of page structure - same as popup preview
			 */
			$exec_sc_only = Avia_Builder()->in_text_to_preview_mode();
			$exec_sc_only = apply_filters( 'avf_alb_exec_sc_only', $exec_sc_only, $this, $atts, $content, $shortcodename, $fake );
			
			if( $exec_sc_only !== true )
			{
				/**
				 * In frontend we ignore requests to shortcodes before header is finished
				 * Fixes problems with plugins that run shortcodes in header (like All In One SEO)
				 * Running shortcodes twice might break the behaviour and layout.
				 * 
				 * But there are frontend requests that do not need of a header. To allow 3rd party plugins to hook
				 * we add a filter (e.g. GraphQL).
				 * 
				 * @used_by					currently unused
				 * @since 4.5.6
				 * @param boolean
				 * @param aviaShortcodeTemplate $this
				 * @param array $atts
				 * @param string $content
				 * @param string $shortcodename
				 * @param boolean $fake
				 * @return boolean
				 */
				$no_header_request = ( defined( 'REST_REQUEST' ) && true === REST_REQUEST ) || is_feed() || is_comment_feed();
				$no_header_request = apply_filters( 'avf_shortcode_no_header_request', $no_header_request, $this, $atts, $content, $shortcodename, $fake );
				
				if( ! is_admin() && ! Avia_Builder()->wp_head_done && ! $no_header_request )
				{
					$meta = $this->default_shortcode_meta( $atts );
					$out = '';
					
					/**
					 * Allow SEO plugins to get content of shortcodes - Styling might be broken, also elements that use filter 'avia_builder_precompile'
					 * SEO plugins can filter shortcodes that make problems by returning anything != 'preprocess_shortcodes_in_header'.
					 * To create their own output they can return a custom value and use 'avf_shortcode_handler_prepare_in_wp_head' later.
					 * 
					 * @used_by					framework\php\function-set-avia-frontend.php  handler_avia_frontend_preprocess_sc_header()		10
					 * @since 4.5.6
					 * @param string
					 * @param aviaShortcodeTemplate $this
					 * @param array $atts
					 * @param string $content
					 * @param string $shortcodename
					 * @param boolean $fake
					 * @return string						'' | 'preprocess_shortcodes_in_header'
					 */
					$preprocess = apply_filters( 'avf_preprocess_shortcode_in_header', avia_get_option( 'preprocess_shortcodes_in_header', '' ), $this, $atts, $content, $shortcodename, $fake );
					
					if( 'preprocess_shortcodes_in_header' == $preprocess )
					{
						$out .= avia_targeted_link_rel( $this->shortcode_handler( $atts, $content, $shortcodename, $meta ) );
					}
					
					/**
					 * Allow SEO plugins to manipulate the shortcodes to get their own content
					 * 
					 * @used_by					currently unused
					 * @since 4.5.5
					 * @param string
					 * @param array $atts
					 * @param string $content
					 * @param string $shortcodename
					 * @param boolean $fake
					 * @param aviaShortcodeTemplate $this		@added 4.5.6
					 * @param string $preprocess				@added 4.5.6
					 * @return string
					 */
					return apply_filters( 'avf_shortcode_handler_prepare_in_wp_head', $out, $atts, $content, $shortcodename, $fake, $this, $preprocess );
				}
				
				/**
				 * First we have to check if post for shortcode tree has changed (happens in loops like archive, REST API calls, ...).
				 * In this case we invalidate the tree and reset index. 
				 * Only needed for frontend.
				 */
				if( ! is_admin() && $current_post instanceof WP_Post )
				{
					if( ! ShortcodeHelper::$current_post_in_tree instanceof WP_Post )
					{
						ShortcodeHelper::$current_post_in_tree = $current_post;
					}
					else if( $current_post->ID != ShortcodeHelper::$current_post_in_tree->ID )
					{
						/**
						 * When finished a loop posts might have been reset to 1st post
						 */
						if( ! ( Avia_Builder()->wp_sidebar_started || Avia_Builder()->wp_footer_started ) )
						{
							ShortcodeHelper::$current_post_in_tree = $current_post;
							ShortcodeHelper::$tree = array();
							ShortcodeHelper::$shortcode_index = 0;
						}
					}
				}
				
				/**
				 * Fixes problems with $meta['index] = undefined notices because shortcode tree is not initialised
				 * 
				 *	- WP5.0 with Gutenberg make REST_API calls to save post content and activates shortcodes (via content filter)
				 *	- A fallback situation for 3-rd party plugins like YOAST which call shortcodes to execute for analysis and
				 *	  no shortcode tree has been initialised
				 *	- API calls to fetch content like REST API, GraphQL
				 * 
				 * @since 4.5.4
				 */
				if( ! is_array( ShortcodeHelper::$tree ) || empty( ShortcodeHelper::$tree ) )
				{
					$return = false;

					if( ! is_admin() )
					{
						$out = '';

						/**
						 * In front we try to init if we have a post id
						 */
						if( $current_post instanceof WP_Post )
						{
							$tree = Avia_Builder()->get_shortcode_tree( $current_post->ID );
							if( ! empty( $tree ) )
							{
								ShortcodeHelper::$tree = $tree;
								ShortcodeHelper::$shortcode_index = 0;
							}
							else 
							{
								/**
								 * To allow manual usage of ALB shortcode structures on non ALB pages we allow to build a temporary shortcode tree
								 * e.g. for Tribe Events Calendar
								 * 
								 * @since 4.5.5
								 */
								if( false === apply_filters( 'avf_shortcode_handler_prepare_no_tree', false, $current_post->ID, $current_post ) )
								{
									Avia_Builder()->get_shortcode_parser()->set_builder_save_location( 'content' );
									$temp_cont = ShortcodeHelper::clean_up_shortcode( trim( $current_post->post_content ), 'balance_only' );
									ShortcodeHelper::$tree = ShortcodeHelper::build_shortcode_tree( $temp_cont );
									ShortcodeHelper::$shortcode_index = 0;
								}
							}
						}
						else
						{
							/**
							 * return nothing if we have not finished header - should allow plugins to preprocess page content in header 
							 * (without executing shortcodes - might not work for some shortcodes like codeblock).
							 */
							if( ! Avia_Builder()->wp_head_done )
							{
								$return = true; 
							}
						}
					}
					else
					{
						/**
						 * In backend we return the shortcode as self closing shortcode only as we might have no reliable info about content
						 * (e.g. YOAST calls shortcodes without content in ajax call wpseo_filter_shortcodes)
						 * 
						 * As a beta trial we allow processing. If this makes trouble we add a filter to disable this.
						 * 
						 * @used_by					Avia_Relevanssi::handler_shortcode_handler_prepare_fallback							10
						 * @used_by					config-wordpress-seo\config.php  avia_wpseo_shortcode_handler_prepare_fallback		20
						 * 
						 * @since 4.5.7.1
						 * @param string
						 * @param aviaShortcodeTemplate $this
						 * @param array $atts
						 * @param string $content
						 * @param string $shortcodename
						 * @param boolean $fake
						 * @return string						'' | 'process_shortcode_in_backend'
						 */
						$process = apply_filters( 'avf_process_shortcode_in_backend', 'process_shortcode_in_backend', $this, $atts, $content, $shortcodename, $fake );
					
						if( 'process_shortcode_in_backend' !== $process )
						{
							$args = array();
							if( is_array( $atts ) )
							{
								foreach( $atts as $key => $value ) 
								{
									$args[] = is_numeric( $key ) ? $value : "{$key}='{$value}'";
								}
							}

							$args = ! empty( $args ) ? ' ' . implode( ' ', $args ) : '';
							$out = "[{$shortcodename}{$args}]";
						}
						else
						{
							$meta = $this->default_shortcode_meta( $atts );
							$out = avia_targeted_link_rel( $this->shortcode_handler( $atts, $content, $shortcodename, $meta ) );
						}
						
						$return = true; 
					}

					if( $return )
					{
						$meta = $this->default_shortcode_meta( $atts );
						
						/**
						 * 
						 * @since 4.5.4
						 * @param string
						 * @param array $atts
						 * @param string $content
						 * @param string $shortcodename
						 * @param boolean $fake
						 * @param array $meta						@added 4.5.7.1
						 * @param aviaShortcodeTemplate $this		@added 4.5.7.1
						 * @return string
						 */
						return apply_filters( 'avf_shortcode_handler_prepare_fallback', $out, $atts, $content, $shortcodename, $fake, $meta, $this );
					}
				}
			}
			
			//don't use any shortcodes in backend
			$meta = $this->default_shortcode_meta( $atts );
			
			$tree_item = ShortcodeHelper::find_tree_item( ShortcodeHelper::$shortcode_index );
			
			$is_valid = false;
			if( is_array( $tree_item ) && isset( $tree_item['tag'] ) && $tree_item['tag'] == $shortcodename && ! ShortcodeHelper::$is_direct_call && ! $fake )
			{
				$is_valid = true;
			}

			/**
			 * inline shortcodes like dropcaps are basically nested shortcodes and should therefore not be counted
			 * Also shortcodes that are before or after content and when called directly (e.g. codeblock)
			 */
			if( empty( $this->config['inline'] ) )
			{
				if( $is_valid )
				{
					$meta['el_class'] .= ' avia-builder-el-' . ShortcodeHelper::$shortcode_index . ' ';
					$meta['index'] = ShortcodeHelper::$shortcode_index;
					$meta['this'] = $tree_item;
					$meta['siblings'] = array(
												'next'	=> ShortcodeHelper::find_tree_item( ShortcodeHelper::$shortcode_index, 1 ),
												'prev'	=> ShortcodeHelper::find_tree_item( ShortcodeHelper::$shortcode_index, -1 )
											);
							
				}
				
				if( ! empty( $meta['siblings']['prev']['tag'] ) )
				{
					$meta['el_class'] .= " el_after_{$meta['siblings']['prev']['tag']} ";
				}
				
				if( ! empty( $meta['siblings']['next']['tag'] ) )
				{
					$meta['el_class'] .= " el_before_{$meta['siblings']['next']['tag']} ";
				}


				$fullwidth = AviaBuilder::$full_el;

				if( ! empty( $meta['this']['tag'] ) && ! in_array( $meta['this']['tag'] , $fullwidth ) )
				{
					if( ! empty( $meta['siblings']['next']['tag'] ) && in_array( $meta['siblings']['next']['tag'] , $fullwidth ) )
					{
						$meta['siblings']['next'] = false;
					}

					if( ! empty( $meta['siblings']['prev']['tag'] ) &&  in_array( $meta['siblings']['prev']['tag'] , $fullwidth ) )
					{
						$meta['siblings']['prev'] = false;
					}
				}


				//single element without siblings

				if( empty( $meta['siblings']['next'] ) && empty( $meta['siblings']['prev'] ) )
				{
					 $meta['el_class'] .= " avia-builder-el-no-sibling ";
				}
				else if( empty( $meta['siblings']['next'] ) ) //last element within section, column or page
				{
					$meta['el_class'] .= " avia-builder-el-last ";
				}
				else if( empty( $meta['siblings']['prev'] ) ) //first element within section, column or page
				{
					$meta['el_class'] .= " avia-builder-el-first ";
				}

				//if the shortcode was added without beeing a builder element (eg button within layerslider) reset all styles for that shortcode and make sure it is marked as a fake element
				if( empty($meta['this']['tag'] ) || $shortcodename != $meta['this']['tag'] || ShortcodeHelper::$is_direct_call || $fake )
				{
						//	increment theme shortcodes only, because these are in the shorcode tree
					if( in_array( $shortcodename, ShortcodeHelper::$allowed_shortcodes ) && ShortcodeHelper::$is_direct_call )
					{
						ShortcodeHelper::$direct_calls++;
					}
					
					$fake = true;
					$meta['el_class'] = '';
				}
				
				//fake is set when we manually call one shortcode inside another
				if( ! $fake ) 
				{
					ShortcodeHelper::$shortcode_index ++;
				}
			}
			
			if( ( ! $current_post instanceof WP_Post ) || ( ! in_array( $current_post->post_type, Avia_Builder()->get_supported_post_types() ) ) || $fake || ! $is_valid )
			{
				/**
				 * Reset as prior to version 4.5.3 to avoid breaking layout
				 */
				$meta = $this->default_shortcode_meta( $atts );
			}
			
			if( ! empty( $meta['custom_class'] ) ) 
			{
				$meta['el_class'] .= ' ' . $meta['custom_class'];
			}
			
			if( ! isset( $meta['custom_markup'] ) ) 
			{
				$meta['custom_markup'] = '';
			}
			
			/**
			 * a fallback only to avoid undefined index notice
			 */
			if( ! isset( $meta['index'] ) )
			{
				$meta['index'] = ( $fake || ! is_valid ) ? -1 : ShortcodeHelper::$shortcode_index;
			}
			
			$meta = apply_filters( 'avf_template_builder_shortcode_meta', $meta, $atts, $content, $shortcodename );
			
			/**
			 * if the element is disabled do load a notice for admins but do not show the info for other visitors)
			 */
			if( empty( $this->builder->disabled_assets[ $this->config['shortcode'] ] ) || empty( $this->config['disabling_allowed'] ) )
			{
				$out = avia_targeted_link_rel( $this->shortcode_handler( $atts, $content, $shortcodename, $meta ) );
			}
			else if( current_user_can( 'edit_posts' ) )
			{
				$default_msg = 	'<strong>'.__( 'Admin notice for:', 'avia_framework' )."</strong><br>".
								$this->config['name']."<br><br>".
								__( 'This element was disabled in your theme settings. You can activate it here:', 'avia_framework' )."<br>".
							   '<a target="_blank" href="'.admin_url( 'admin.php?page=avia#goto_performance' ).'">'.__( 'Performance Settings', 'avia_framework' )."</a>";
				
				$msg 		= isset( $this->config['shortcode_disabled_msg'] ) ? $this->config['shortcode_disabled_msg'] : $default_msg;
				$out		= "<span class='av-shortcode-disabled-notice'>{$msg}</span>";
			}
			else
			{
				$out		= '';
			}
			
			/**
			 * Also allows to manipulate internal variables if necessary
			 * 
			 * @since 4.5.4
			 * @return array
			 */
			$args = array( $out, $this, $atts, $content, $shortcodename, $fake );
			apply_filters_ref_array( 'avf_in_shortcode_handler_prepare_content', array( &$args ) );

			return $args[0];
		}



		/**
		* additional config vars that are set automatically
		*/
		protected function extra_config()
		{
			$this->config['php_class'] = get_class($this);

			if(empty($this->config['drag-level'])) $this->config['drag-level'] = 3;
			if(empty($this->config['drop-level'])) $this->config['drop-level'] = -1;


			//if we got elements for the popup editor activate it
			if( method_exists( $this, 'popup_elements' ) && is_admin() )
			{
				if( method_exists( $this, 'register_dynamic_templates' ) )
				{
					$this->register_dynamic_templates();
					
					/**
					 * Allows to override registered dynamic templates or add new ones for the shortcode
					 * 
					 * @since 4.6.4
					 * @param aviaShortcodeTemplate $this			is rendered by reference
					 */
					do_action( 'ava_after_register_dynamic_templates_sc', $this );
				}
				
				$this->popup_elements();
				$this->elements = AviaPopupTemplates()->replace_templates( $this->elements );
				
				/**
				 * Allows to extend option fields for shortcode
				 * 
				 * @since 4.5.6.1
				 * @param aviaShortcodeTemplate $this			is rendered by reference
				 */
				do_action( 'ava_popup_elements_loaded', $this );

				if( ! empty( $this->elements ) )
				{
					$this->config['popup_editor'] = true;

					$this->extra_config_element_iterator($this->elements);

					if(!empty($this->config['modal_on_load']))
					{
						//remove any duplicate values
						$this->config['modal_on_load'] = array_unique($this->config['modal_on_load']);
					}
				}
			}

		}

		/**
		 * register shortcode and if available nested shortcode
		 */
		protected function register_shortcodes()
		{
			if(isset($_REQUEST['params']['_ajax_nonce'])) $_REQUEST['_ajax_nonce'] = $_REQUEST['params']['_ajax_nonce'];
			
			//the check is only necessary when $_REQUEST['text'] is set which means we want to show a preview that could be manipulated from outside
			if(!is_admin() || empty($_REQUEST['text']) || ( !empty($_POST['avia_request']) && check_ajax_referer('avia_nonce_loader', '_ajax_nonce' ) ) )
			{
				add_shortcode( $this->config['shortcode'], array(&$this, 'shortcode_handler_prepare'));
				
				if(!empty($this->config['shortcode_nested']))
				{
					foreach($this->config['shortcode_nested'] as $nested)
					{
						if( method_exists($this, $nested) )
						{
							add_shortcode( $nested, array(&$this, $nested));
						}
						else if(!shortcode_exists($nested))
						{
							add_shortcode( $nested, '__return_false'); /*wordpress 4.0.1 fix that. without the shortcode registered to a function the attributes get messed up*/
						}
						
					}
				}
			}
		}



		/**
		 * helper function to iterate recursively over element and subelement trees.
		 * 
		 * @param array $elements
		 */
		protected function extra_config_element_iterator( $elements )
		{
			//check for js functions that need to be executed on popup window load
			foreach ($elements as $element )
			{
				switch($element['type'])
				{
					case "mailchimp_list":  $this->config['modal_on_load'][] = 'modal_load_mailchimp'; break;
					case "multi_input": 	$this->config['modal_on_load'][] = 'modal_load_multi_input'; break;
					case "tab_container": 	$this->config['modal_on_load'][] = 'modal_load_tabs'; break;
					case "toggle_container":$this->config['modal_on_load'][] = 'modal_load_toggles'; break;
					case "icon_switcher_container":$this->config['modal_on_load'][] = 'modal_load_iconswitcher'; break;
					case "tiny_mce": 		$this->config['modal_on_load'][] = 'modal_load_tiny_mce'; break;
					case "colorpicker": 	$this->config['modal_on_load'][] = 'modal_load_colorpicker'; break;
					case "datepicker": 		$this->config['modal_on_load'][] = 'modal_load_datepicker'; break;
					case "table": 			$this->config['modal_on_load'][] = 'modal_load_tablebuilder'; break;
					case "modal_group":
											$this->config['modal_on_load'][] = 'modal_start_sorting';
											$this->config['modal_on_load'][] = 'modal_tab_functions';
											$this->config['modal_on_load'][] = 'modal_hotspot_helper';
											$this->extra_config_element_iterator($element['subelements']);
											break;
				}
				
				if(!empty($element['modal_on_load'])) //manually load a script
				{
					$this->config['modal_on_load'][] = $element['modal_on_load'];
				}
			}
		}



		/**
		* filter and action hooks
		*/
		protected function actions_and_filters()
		{
			add_filter( 'avia_show_shortcode_button', array( $this, 'add_backend_button' ), 10, 1 );

			//ajax action for elements with modal window editor
			if( ! empty( $this->config['popup_editor'] ) )
			{
				add_action( 'wp_ajax_avia_ajax_' . $this->config['shortcode'], array( $this, 'popup_editor' ) );

				if(!empty($this->config['shortcode_nested']))
				{
					foreach($this->config['shortcode_nested'] as $sc)
					{
						add_action('wp_ajax_avia_ajax_'.$sc, array($this,'popup_editor'));
					}
				}
			}


		}


		/**
		* function that checks the popup_elements configuration array of a shortcode and sets an array that tells the builder class which resources to load
		*/
		protected function create_asset_array()
		{
			if(!empty($this->elements))
			{
				foreach ($this->elements as $element)
				{
					if( $element['type'] == 'iconfont')
					{
						AviaBuilder::$resources_to_load['font'] = $element;
					}
				}
			}
		}

		/**
		* add buttons for the backend
		*/
		public function add_backend_button($buttons)
		{
			$buttons[] = $this->config;
			return $buttons;
		}


		/**
		* function that sets the default values and passes them to the user defined editor element
		* which in turn returns the array with the properties to render a new AviaBuilder Canvas Element
		*/
		public function prepare_editor_element($content = false, $args = array())
		{
			//set the default content unless it was already passed
			if($content === false)
			{
				$content = $this->get_default_content($content);
			}

			//set the default arguments unless they were already passed
			if(empty($args))
			{
				$args = $this->get_default_args($args);
			}

			if(isset($args['content'])) unset($args['content']);

			$params['content']   = $content;
			$params['args']      = $args;
			$params['data']      = isset($this->config['modal_data']) ? $this->config['modal_data'] : array();
			
		
			/**
			 * Fetch the parameter array from the child classes editor_element function which should describe the html code.
			 * Some elements can return a string value
			 */
			$params =  $this->editor_element( $params );

			/**
			 * Since 4.2.1 we have $this->config['self_closing'] = 'yes'|'no'
			 * Now we can use this to remove any content here and do not need to do this in each element seperatly in $this->editor_element 
			 */
			if( is_array( $params ) && $this->is_self_closing() )
			{
				$params['content'] = null;
			}

			// pass the parameters to the create_sortable_editor_element unless a different function for execution was set.
			// if the function is set to "false" we asume that the output is final
			if(!isset($this->config['html_renderer']))
			{
				$this->config['html_renderer'] = "create_sortable_editor_element";
			}

			if($this->config['html_renderer'] != false)
			{
				$output = call_user_func(array($this, $this->config['html_renderer']) , $params );
			}
			else
			{
				$output = $params;
			}

			return $output;
		}
		
		/**
		 * Add a custom css class to each element
		 * 
		 * @since < 4.0
		 * @deprecated since 4.5.7.2
		 * @param array $elements
		 * @return array
		 */
		public function avia_custom_class_for_element( array $elements )
		{
			_deprecated_function( 'aviaShortcodeTemplate::avia_custom_class_for_element()', '4.5.7.2', 'aviaShortcodeTemplate::get_developer_elements()' );
			
			$this->get_developer_elements( $elements );
			return $elements;
		}
		
		/**
		 * Add developer elements to each element 
		 *		- Short description for backend
		 *		- custom id attribute input field
		 *		- custom css class input field
		 *		- Aria Label Text
		 * 
		 * @since 4.5.7.2
		 * @param array $elements
		 * @param string $nested_sc
		 * @return string			'' | 'avia-hidden'
		 */
		public function get_developer_elements( array &$elements, $nested_sc = '' )
		{
			$developer = array();
			$visible = 0;
			
			$config = $this->config;
			
			if( ! empty( $nested_sc ) && method_exists( $this, 'get_nested_developer_elements' ) )
			{
				$config = $this->get_nested_developer_elements( $nested_sc );
			}
			
			$desc_id_show = ! empty( $config['alb_desc_id'] ) ? 'yes' : 'no';
			$setting = Avia_Builder()->get_developer_settings( 'alb_desc_id' );
			
			switch( $desc_id_show )
			{
				case 'no':
					$class = 'avia-hidden';
					break;
				default:
					$class = in_array( $setting, array( 'deactivate', 'hide' ) ) ? 'avia-hidden' : '';
					break;
			}
			
			if( $class == '' )
			{
				$visible++;
			}
			
			if( 'yes' == $desc_id_show )
			{
				$desc  = __( 'Add a short content description to identify the element in backend when editing ALB content', 'avia_framework' );
				
				$developer[] = array(	
									'name'				=> __( 'Short description for backend', 'avia_framework' ),
									'desc'				=> $desc,
									'id'				=> $config['alb_desc_id'],
									'container_class'	=> $class,
									'type'				=> 'input',
									'std'				=> ''
								);
			}
			
			$id_name = ! empty( $config['id_name'] ) ? $config['id_name'] : 'el_id';
			$id_show = ! empty( $config['id_show'] ) ? $config['id_show'] : 'never';
			$setting_id = Avia_Builder()->get_developer_settings( 'custom_id' );
			
			switch( $id_show )
			{
				case 'always':
					$class = '';
					break;
				case 'never':
					$class = 'avia-hidden';
					break;
				default:
					$class = in_array( $setting_id, array( 'deactivate', 'hide' ) ) ? 'avia-hidden' : '';
					break;
			}
			
			if( $class == '' )
			{
				$visible++;
			}
			
			if( $id_show != 'never' )
			{
				$desc  = __( 'Apply a custom id attribute to this element. Useful to apply individual styling via CSS or if you want to use anchor links to scroll to the element when a link is clicked.', 'avia_framework' ) . '<br /><br />';
				$desc .= __( 'Use with caution, be sure to have a unique value on the page and also make sure to only use allowed characters (latin characters, underscores, dashes and numbers, no special characters can be used).', 'avia_framework' );
				
				$developer[] = array(	
									'name'				=> __( 'Custom ID Attribute', 'avia_framework' ),
									'desc'				=> $desc,
									'id'				=> $id_name,
									'container_class'	=> $class,
									'type'				=> 'input',
									'std'				=> ''
								);
			}
			
			$css_show = ! empty( $config['custom_css_show'] ) ? $config['custom_css_show'] : 'yes';
			$setting = Avia_Builder()->get_developer_settings( 'custom_css' );
			
			switch( $css_show )
			{
				case 'always':
					$class = '';
					break;
				case 'never':
					$class = 'avia-hidden';
					break;
				default:
					$class = in_array( $setting, array( 'deactivate', 'hide' ) ) ? 'avia-hidden' : '';
					break;
			}
			
			if( $class == '' )
			{
				$visible++;
			}
			
			if( $css_show != 'never' )
			{
				$developer[] = array(	
									'name'				=> __( 'Custom CSS Class', 'avia_framework' ),
									'desc'				=> __( 'Add a custom css class for the element here. Make sure to only use allowed characters (latin characters, underscores, dashes and numbers, no special characters can be used)', 'avia_framework' ),
									'id'				=> 'custom_class',
									'container_class'	=> $class,
									'type'				=> 'input',
									'std'				=> ''
								);
			}
			
			
			$aria_label_show = ! empty( $config['aria_label'] ) ? $config['aria_label'] : 'no';
			$setting = Avia_Builder()->get_developer_settings( 'aria_label' );
			
			switch( $aria_label_show )
			{
				case 'no':
					$class = 'avia-hidden';
					break;
				default:
					$class = in_array( $setting, array( 'deactivate', 'hide' ) ) ? 'avia-hidden' : '';
					break;
			}
			
			if( $class == '' )
			{
				$visible++;
			}
			
			if( 'yes' == $aria_label_show )
			{
				$developer[] = array(	
									'name'				=> __( 'Aria Label Text', 'avia_framework' ),
									'desc'				=> __( 'Add a custom text for the element here. This text will be added to the aria-label attribute and can be used by accessibility tree and screen readers. Leave empty if not needed.', 'avia_framework' ),
									'id'				=> 'aria_label',
									'container_class'	=> $class,
									'type'				=> 'input',
									'std'				=> ''
								);
			}
			
			
			if( 0 == count( $developer ) )
			{
				return 'avia-hidden';
			}
			
			$class = ( $visible > 0 ) ? '' : 'avia-hidden';
			
			if( version_compare( $this->get_version(), '1.0', '>=' ) )
			{
				$elements = array_merge( $elements, $developer );
				return $class;
			}
			
			$tab_open = array( array(
								'type'				=> 'tab',
								'container_class'	=> $class,
								'name'				=> __( 'Developer' , 'avia_framework' ),
								'nodescription'		=> true
							));
					
			$tab_close = array( array(
								'type'				=> 'close_div',
								'container_class'	=> $class,
								'nodescription'		=> true
							) );
			
			$last_div = -1;
			$index = 0;
			foreach( $elements as $key => $el ) 
			{
				if( 'close_div' == $el['type'] )
				{
					$last_div = $index;
				}
				
				$index++;
			}
			
			if( ( $last_div >= 0 ) && ( $visible > 0 ) )
			{
				$tab = array_merge( $tab_open, $developer, $tab_close );
				array_splice( $elements, $last_div, 0, $tab );
			}
			else
			{
				$elements = array_merge( $elements, $developer );
			}
			
			return $class;
		}
		
		/**
		 * Checks if we can use the selected setting depending on system options
		 * 
		 * @since 4.5.7.2
		 * @param array $atts
		 * @param array|string $meta
		 * @return array
		 */
		static public function set_frontend_developer_heading_tag( $atts = array(), $meta = array() )
		{
			if( ! is_array( $atts ) )
			{
				$atts = array();
			}
			
			if( ! is_array( $meta ) )
			{
				$meta = array();
			}
			
			$setting = Avia_Builder()->get_developer_settings( 'heading_tags' );
			if( in_array( $setting, array( 'deactivate' ) ) )
			{
				$meta['heading_tag'] = '';
				$meta['heading_class'] = '';
			}
			else
			{
				
				$meta['heading_tag'] = isset( $atts['heading_tag'] ) ? $atts['heading_tag'] : '';
				$meta['heading_class'] = isset( $atts['heading_class'] ) ? AviaHelper::save_classes_string( $atts['heading_class'], '-' ) : '';
			}
			
			return $meta;
		}

		/**
		 * Add a custom field for the background of the preview
		 * @param array $elements
		 * @return array
		 */
		public function avia_custom_preview_bg( array $elements )
		{
			$elements[] = array(	
								'id' 	=> 'admin_preview_bg',
								'type' 	=> 'hidden',
								'std' 	=> ''
							);
		
			return $elements;
		}
		

		/**
		* default code to create a sortable item for your editor
		*/
		public function create_sortable_editor_element( $params )
		{

			$extraClass = '';
			$defaults = array('class'=>'avia_default_container', 'innerHtml'=>'');
			$params = array_merge($defaults, $params);
			extract($params);
			
			if(empty($data)) $data = array();
			
			$data['shortcodehandler'] 	= $this->config['shortcode'];
			$data['modal_title'] 		= $this->config['name'];
			$data['modal_ajax_hook'] 	= $this->config['shortcode'];
			$data['dragdrop-level']		= $this->config['drag-level'];
			$data['allowed-shortcodes'] = $this->config['shortcode'];
            $data['preview'] 			= ! empty( $this->config['preview'] ) ? $this->config['preview'] : 0;
            $data['preview_scale'] 		= ! empty( $this->config['preview_scale'] ) ? $this->config['preview_scale'] : 'noscale';
			$data['closing_tag']		= $this->is_self_closing() ? 'no' : 'yes';
			
			if(isset($this->config['shortcode_nested']))
			{
				$data['allowed-shortcodes']	= $this->config['shortcode_nested'];
				$data['allowed-shortcodes'][] = $this->config['shortcode'];
				$data['allowed-shortcodes'] = implode(',',$data['allowed-shortcodes']);
			}

			if(!empty($this->config['modal_on_load']))
			{
				$data['modal_on_load'] 	= $this->config['modal_on_load'];
			}

			$data		 = apply_filters('avb_backend_editor_element_data_filter', $data);
			$dataString  = AviaHelper::create_data_string($data);

			$output  = "<div class='avia_sortable_element avia_pop_class ".$class." ".$this->config['shortcode']." av_drag' ".$dataString.">";
			$output .= "<div class='avia_sorthandle menu-item-handle'>";

			if(!empty($this->config['popup_editor']))
			{
				$extraClass = 'avia-edit-element';
				$output .= "<a class='$extraClass'  href='#edit-element' title='".__('Edit Element','avia_framework' )."'>edit</a>";
			}

			$output .= "<a class='avia-save-element'  href='#save-element' title='".__('Save Element as Template','avia_framework' )."'>+</a>";
			$output .= "<a class='avia-delete'  href='#delete' title='".__('Delete Element','avia_framework' )."'>x</a>";
			$output .= "<a class='avia-clone'  href='#clone' title='".__('Clone Element','avia_framework' )."' >".__('Clone Element','avia_framework' )."</a></div>";

			$output .= "<div class='avia_inner_shortcode $extraClass'>";
			$output .= $innerHtml;
			
			
			
			$output .= "<textarea data-name='text-shortcode' cols='20' rows='4'>".ShortcodeHelper::create_shortcode_by_array($this->config['shortcode'], $content, $args)."</textarea>";
			$output .= "</div></div>";

			return $output;
		}



		/**
		 * helper function executed by aviaShortcodeTemplate::popup_editor that extracts the attributes from the shortcode and then merges the values into the options array
		 *
		 * @param array $elements
		 * @return array $elements
		 */
		public function set_default_values($elements)
		{
			$shortcode = !empty($_POST['params']['shortcode']) ? $_POST['params']['shortcode'] : '';
			

			if($shortcode)
			{
				//will extract the shortcode into $_POST['extracted_shortcode']
				$this->builder->text_to_interface($shortcode);
				
				//the main shortcode (which is always the last array item) will be stored in $extracted_shortcode
				$extracted_shortcode = end($_POST['extracted_shortcode']);

				//if the $_POST['extracted_shortcode'] has more than one items we are dealing with nested shortcodes
				$multi_content = count($_POST['extracted_shortcode']);

				//proceed if the main shortcode has either arguments or content
				if(!empty($extracted_shortcode['attr']) || !empty($extracted_shortcode['content']))
				{
					if(empty($extracted_shortcode['attr'])) $extracted_shortcode['attr'] = array();
					if(isset($extracted_shortcode['content'])) $extracted_shortcode['attr']['content'] = $extracted_shortcode['content'];

					//iterate over each array item and check if we already got a value
					foreach($elements as &$element)
					{
						if(isset($element['id']) && isset($extracted_shortcode['attr'][$element['id']]))
						{
							//make sure that each element of the popup can access the other values of the shortcode. necessary for hidden elements
							$element['shortcode_data'] = $extracted_shortcode['attr'];
						
							//if the item has subelements the std value has to be an array
							if(isset($element['subelements']))
							{
								$element['std'] = array();

								for ($i = 0; $i < $multi_content - 1; $i++)
								{
									$element['std'][$i] = $_POST['extracted_shortcode'][$i]['attr'];
									$element['std'][$i]['content'] = $_POST['extracted_shortcode'][$i]['content'];
								}
							}
							else
							{
								$element['std'] = stripslashes($extracted_shortcode['attr'][$element['id']]);
							}



						}
						else
						{
							if($element['type'] == "checkbox") $element['std'] = '';
						}
					}
				}
			}

			return $elements;
		}


		/**
		 * helper function executed that extracts the std values from the options array and creates a shortcode argument array
		 *
		 * @param array $args
		 * @return array $args
		 */
		public function get_default_args($args = array())
		{
			/**
			 * PHP 7.0 fix: ensure we have an array, otherwise we recieve notices
			 * if shortcode is used without params (e.g. for a fallback situation) we get an empty string and not an array
			 */
			if( ! is_array( $args) )
			{
				$args = array();
			}
			
			if(!empty($this->elements))
			{
				foreach($this->elements as $element)
				{
					if(isset($element['std']) && isset($element['id']))
					{
						$args[$element['id']] = $element['std'];
					}
				}

				$this->default_args = $args;
			}
			return $args;
		}

		/**
		 * helper function that gets the default value of the content element
		 *
		 * @param array $content
		 * @return array $args
		 */
		public function get_default_content($content = '')
		{
			if(!empty($this->elements))
			{
				//if we didnt iterate over the arguments array yet do it now
				if(empty($this->default_args))
				{
					$this->get_default_args();
				}

				//if there is a content element already thats the value. if not try to fetch the std value
				if(!isset($this->default_args['content']))
				{
					foreach($this->elements as $element)
					{
						if(isset($element['std']) && isset($element['id']) && $element['id'] == "content")
						{
							$content = $element['std'];
						}
					}
				}
				else
				{
					$content = $this->default_args['content'];
				}
			}

			//if the content is an array we got a nested shortcode
			if(is_array($content))
			{
				$string_content = '';

				foreach($content as $c)
				{
					$content = $this->is_nested_self_closing( $this->config['shortcode_nested'][0] ) ? null : '';
					$string_content .= trim( ShortcodeHelper::create_shortcode_by_array( $this->config['shortcode_nested'][0], $content, $c ) )."\n";
				}

				$content =  $string_content;
			}


			return $content;
		}
		
		/**
		 * Returns the width of the element.
		 * Override in derived class if the element does not have fullwidth (currently only supported for layout elements avia_sc_columns and avia_sc_cell.
		 * All other elements are full screen.
		 * 
		 * @since 4.2.1
		 * @return float
		 */
		public function get_element_width()
		{
			return 1.0;
		}
		
		
		/**
		 * Returns if an element needs a closing tag or is self closing.
		 * The implementation is for backwards compatibility and also for third party elements.
		 *  
		 * @since 4.2.1
		 * @return boolean
		 */
		public function is_self_closing()
		{
			/**
			 * If property is set return this value
			 */
			if( ! empty( $this->config['self_closing'] ) && in_array( $this->config['self_closing'], array( 'yes', 'no' ) ) )
			{
				return $this->config['self_closing'] == 'yes';
			}
			
			/**
			 * Elements should return null for content when self closing
			 */
			$params = $this->editor_element( array() );
			$this->config['self_closing'] =  array_key_exists( 'content',  $params ) && is_null( $params['content'] )  ? 'yes' : 'no';
			
			return $this->config['self_closing'] == 'yes';
		}
		
		/**
		 * Returns false by default.
		 * Override in a child class if you need to change this behaviour.
		 * 
		 * @since 4.2.1
		 * @param string $shortcode
		 * @return boolean
		 */
		public function is_nested_self_closing( $shortcode )
		{
			return false;
		}

		/**
		 * helper function for the editor_element function that creates the correct classnames
		 * and data attributes for an AviaBuilder Canvas element in your backend
		 *
		 * @param string $classNames a string with classnames separated by coma
		 * @param array $args
		 * @return string
		 */
		public function class_by_arguments($classNames, $args, $classNamesOnly = false)
		{
			$classNames = str_replace(" ",'',$classNames);
			$dataString = "data-update_class_with='$classNames' ";
			$classNames = explode(',',$classNames);
			$classString = "class='";
			$classes = '';

			foreach($classNames as $class)
			{
				$replace  = is_array($args) ? $args[$class] : $args;
				$classes .= "avia-$class-".str_replace(" ","_",$replace)." ";
			}

			if($classNamesOnly) return $classes;
			return $classString .$classes."' ".$dataString;
		}



		/**
		 * helper function for the editor_element function that tells the javascript were to insert the returned content
		 * you need to provide a "key" and a template
		 *
		 * @param string $key a string with argument or content key eg: img_src
		 * @param string $template a template that tells which content to insert. eg: <img src='{{img_src}}' />
		 * @return string
		 */

		function update_template($key, $template)
		{
			$data = "data-update_with='$key' data-update_template='".htmlentities($template, ENT_QUOTES, get_bloginfo( 'charset' ))."'";
			return $data;
		}


	} // end class

} // end if !class_exists


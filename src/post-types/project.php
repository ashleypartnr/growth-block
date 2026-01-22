<?php
/**
 * Project Custom Post Type and Service Area Taxonomy
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Project custom post type.
 */
function gg_register_project_post_type() {
	$labels = array(
		'name'                  => _x( 'Projects', 'Post Type General Name', 'greengrowth-impact-showcase' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'greengrowth-impact-showcase' ),
		'menu_name'             => __( 'Projects', 'greengrowth-impact-showcase' ),
		'name_admin_bar'        => __( 'Project', 'greengrowth-impact-showcase' ),
		'archives'              => __( 'Project Archives', 'greengrowth-impact-showcase' ),
		'attributes'            => __( 'Project Attributes', 'greengrowth-impact-showcase' ),
		'parent_item_colon'     => __( 'Parent Project:', 'greengrowth-impact-showcase' ),
		'all_items'             => __( 'All Projects', 'greengrowth-impact-showcase' ),
		'add_new_item'          => __( 'Add New Project', 'greengrowth-impact-showcase' ),
		'add_new'               => __( 'Add New', 'greengrowth-impact-showcase' ),
		'new_item'              => __( 'New Project', 'greengrowth-impact-showcase' ),
		'edit_item'             => __( 'Edit Project', 'greengrowth-impact-showcase' ),
		'update_item'           => __( 'Update Project', 'greengrowth-impact-showcase' ),
		'view_item'             => __( 'View Project', 'greengrowth-impact-showcase' ),
		'view_items'            => __( 'View Projects', 'greengrowth-impact-showcase' ),
		'search_items'          => __( 'Search Project', 'greengrowth-impact-showcase' ),
		'not_found'             => __( 'Not found', 'greengrowth-impact-showcase' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'greengrowth-impact-showcase' ),
		'featured_image'        => __( 'Featured Image', 'greengrowth-impact-showcase' ),
		'set_featured_image'    => __( 'Set featured image', 'greengrowth-impact-showcase' ),
		'remove_featured_image' => __( 'Remove featured image', 'greengrowth-impact-showcase' ),
		'use_featured_image'    => __( 'Use as featured image', 'greengrowth-impact-showcase' ),
		'insert_into_item'      => __( 'Insert into project', 'greengrowth-impact-showcase' ),
		'uploaded_to_this_item' => __( 'Uploaded to this project', 'greengrowth-impact-showcase' ),
		'items_list'            => __( 'Projects list', 'greengrowth-impact-showcase' ),
		'items_list_navigation' => __( 'Projects list navigation', 'greengrowth-impact-showcase' ),
		'filter_items_list'     => __( 'Filter projects list', 'greengrowth-impact-showcase' ),
	);

	$args = array(
		'label'               => __( 'Project', 'greengrowth-impact-showcase' ),
		'description'         => __( 'GreenGrowth sustainability and reforestation projects', 'greengrowth-impact-showcase' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'taxonomies'          => array( 'gg_service_area' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-site-alt3',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
		'rest_base'           => 'projects',
		'rewrite'             => array( 'slug' => 'projects' ),
	);

	register_post_type( 'gg_project', $args );
}
add_action( 'init', 'gg_register_project_post_type' );

/**
 * Register the Service Area taxonomy.
 */
function gg_register_service_area_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Service Areas', 'Taxonomy General Name', 'greengrowth-impact-showcase' ),
		'singular_name'              => _x( 'Service Area', 'Taxonomy Singular Name', 'greengrowth-impact-showcase' ),
		'menu_name'                  => __( 'Service Areas', 'greengrowth-impact-showcase' ),
		'all_items'                  => __( 'All Service Areas', 'greengrowth-impact-showcase' ),
		'parent_item'                => __( 'Parent Service Area', 'greengrowth-impact-showcase' ),
		'parent_item_colon'          => __( 'Parent Service Area:', 'greengrowth-impact-showcase' ),
		'new_item_name'              => __( 'New Service Area Name', 'greengrowth-impact-showcase' ),
		'add_new_item'               => __( 'Add New Service Area', 'greengrowth-impact-showcase' ),
		'edit_item'                  => __( 'Edit Service Area', 'greengrowth-impact-showcase' ),
		'update_item'                => __( 'Update Service Area', 'greengrowth-impact-showcase' ),
		'view_item'                  => __( 'View Service Area', 'greengrowth-impact-showcase' ),
		'separate_items_with_commas' => __( 'Separate service areas with commas', 'greengrowth-impact-showcase' ),
		'add_or_remove_items'        => __( 'Add or remove service areas', 'greengrowth-impact-showcase' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'greengrowth-impact-showcase' ),
		'popular_items'              => __( 'Popular Service Areas', 'greengrowth-impact-showcase' ),
		'search_items'               => __( 'Search Service Areas', 'greengrowth-impact-showcase' ),
		'not_found'                  => __( 'Not Found', 'greengrowth-impact-showcase' ),
		'no_terms'                   => __( 'No service areas', 'greengrowth-impact-showcase' ),
		'items_list'                 => __( 'Service Areas list', 'greengrowth-impact-showcase' ),
		'items_list_navigation'      => __( 'Service Areas list navigation', 'greengrowth-impact-showcase' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_in_rest'      => true,
		'rest_base'         => 'service-areas',
		'rewrite'           => array( 'slug' => 'service-area' ),
	);

	register_taxonomy( 'gg_service_area', array( 'gg_project' ), $args );
}
add_action( 'init', 'gg_register_service_area_taxonomy' );

/**
 * Add custom REST API fields for projects.
 */
function gg_register_rest_fields() {
	// Add featured image data to REST API.
	register_rest_field(
		'gg_project',
		'featured_image_data',
		array(
			'get_callback' => function ( $post ) {
				$image_id = get_post_thumbnail_id( $post['id'] );
				if ( ! $image_id ) {
					return null;
				}

				$image = wp_get_attachment_image_src( $image_id, 'medium_large' );
				if ( ! $image ) {
					return null;
				}

				return array(
					'url'    => $image[0],
					'width'  => $image[1],
					'height' => $image[2],
					'alt'    => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
				);
			},
			'schema'       => array(
				'description' => __( 'Featured image data', 'greengrowth-impact-showcase' ),
				'type'        => 'object',
			),
		)
	);

	// Add service areas data to REST API.
	register_rest_field(
		'gg_project',
		'service_areas_data',
		array(
			'get_callback' => function ( $post ) {
				$terms = get_the_terms( $post['id'], 'gg_service_area' );
				if ( ! $terms || is_wp_error( $terms ) ) {
					return array();
				}

				return array_map(
					function ( $term ) {
						return array(
							'id'   => $term->term_id,
							'name' => $term->name,
							'slug' => $term->slug,
						);
					},
					$terms
				);
			},
			'schema'       => array(
				'description' => __( 'Service areas', 'greengrowth-impact-showcase' ),
				'type'        => 'array',
			),
		)
	);
}
add_action( 'rest_api_init', 'gg_register_rest_fields' );

/**
 * Download an image from a URL and attach it to a post.
 *
 * @param string $image_url The URL of the image to download.
 * @param int    $post_id   The post ID to attach the image to.
 * @param string $title     The title for the image.
 * @return int|WP_Error The attachment ID on success, WP_Error on failure.
 */
function gg_download_and_attach_image( $image_url, $post_id, $title ) {
	// Require WordPress file functions.
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Download the image.
	$tmp = download_url( $image_url );

	if ( is_wp_error( $tmp ) ) {
		return $tmp;
	}

	// Set up the file array.
	$file_array = array(
		'name'     => sanitize_file_name( $title ) . '.png',
		'tmp_name' => $tmp,
	);

	// Upload the image to the media library.
	$attachment_id = media_handle_sideload( $file_array, $post_id, $title );

	// Clean up temp file.
	if ( file_exists( $tmp ) ) {
		@unlink( $tmp );
	}

	if ( is_wp_error( $attachment_id ) ) {
		return $attachment_id;
	}

	// Set alt text.
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

	return $attachment_id;
}

/**
 * Create sample projects on plugin activation.
 */
function gg_create_sample_projects() {
	// Create service area terms first.
	$reforestation_term = wp_insert_term(
		__( 'Reforestation', 'greengrowth-impact-showcase' ),
		'gg_service_area',
		array( 'slug' => 'reforestation' )
	);

	$carbon_capture_term = wp_insert_term(
		__( 'Carbon Capture', 'greengrowth-impact-showcase' ),
		'gg_service_area',
		array( 'slug' => 'carbon-capture' )
	);

	$sustainable_farming_term = wp_insert_term(
		__( 'Sustainable Farming', 'greengrowth-impact-showcase' ),
		'gg_service_area',
		array( 'slug' => 'sustainable-farming' )
	);

	// Get term IDs.
	$reforestation_id       = is_array( $reforestation_term ) ? $reforestation_term['term_id'] : 0;
	$carbon_capture_id      = is_array( $carbon_capture_term ) ? $carbon_capture_term['term_id'] : 0;
	$sustainable_farming_id = is_array( $sustainable_farming_term ) ? $sustainable_farming_term['term_id'] : 0;

	// Sample project data.
	$projects = array(
		// Reforestation projects.
		array(
			'title'   => __( 'Amazon Rainforest Restoration', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Working with indigenous communities to restore 10,000 hectares of degraded rainforest in the Brazilian Amazon through native species planting.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This comprehensive restoration project focuses on rehabilitating degraded areas of the Amazon rainforest through strategic native species planting and community engagement.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => '52C41A',
		),
		array(
			'title'   => __( 'Mangrove Coastal Protection', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Restoring vital mangrove ecosystems along the Philippine coastline to protect communities from storm surges and provide habitat for marine life.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our mangrove restoration initiative combines coastal protection with biodiversity conservation, creating resilient ecosystems that benefit both people and wildlife.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => '1890FF',
		),
		array(
			'title'   => __( 'Urban Tree Canopy Expansion', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Increasing Portland\'s urban forest through strategic tree planting in underserved neighborhoods to combat heat islands and improve air quality.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This urban greening project addresses environmental justice by bringing the benefits of trees to communities that need them most, reducing temperatures and improving public health.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => '13C2C2',
		),
		array(
			'title'   => __( 'Highland Forest Regeneration', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Supporting natural regeneration and active planting of native Scots pine and oak in the Scottish Highlands to restore ancient Caledonian forest.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our Highland forest project combines rewilding principles with targeted planting to restore one of Europe\'s most unique forest ecosystems.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => '722ED1',
		),
		array(
			'title'   => __( 'Tropical Dry Forest Recovery', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Restoring Madagascar\'s critically endangered dry forest ecosystem through community-led conservation and native species propagation.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Working with local communities to protect and restore this unique ecosystem, home to species found nowhere else on Earth.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => 'EB2F96',
		),
		array(
			'title'   => __( 'Boreal Forest Restoration', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Rehabilitating Canada\'s boreal forest after logging and fires through natural regeneration support and strategic planting of native conifers.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This large-scale restoration initiative supports the recovery of one of the world\'s most important carbon sinks.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => '2F54EB',
		),
		array(
			'title'   => __( 'Native Species Reforestation', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Replacing invasive species with native eucalyptus and acacia varieties across Australia to support wildlife habitat and ecosystem health.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our Australian program focuses on restoring the balance of native forests while removing aggressive invasive species.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => 'FA8C16',
		),
		array(
			'title'   => __( 'Community Forest Management', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Empowering Kenyan communities to manage and restore local forests through sustainable agroforestry practices and native tree planting.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This community-centered initiative combines forest restoration with livelihood support through sustainable forest management.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => 'FAAD14',
		),
		array(
			'title'   => __( 'Watershed Reforestation', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Protecting Nepal\'s water resources through strategic forest restoration in critical watershed areas to prevent erosion and ensure clean water supply.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our watershed protection program demonstrates how reforestation directly benefits communities through improved water quality and availability.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => '52C41A',
		),
		array(
			'title'   => __( 'Fire-Damaged Area Recovery', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Accelerating forest recovery in California\'s wildfire-affected areas through native species planting and erosion control measures.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This rapid response restoration initiative helps forests recover from catastrophic wildfires while building resilience to future climate challenges.', 'greengrowth-impact-showcase' ),
			'term'    => $reforestation_id,
			'color'   => 'FA541C',
		),
		// Carbon Capture projects.
		array(
			'title'   => __( 'Soil Carbon Sequestration', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Implementing regenerative agriculture practices across Argentine farmland to increase soil organic matter and capture atmospheric carbon.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our soil carbon program demonstrates how farming practices can transform agricultural land into powerful carbon sinks.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '8B4513',
		),
		array(
			'title'   => __( 'Biochar Production Initiative', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Creating sustainable biochar from agricultural waste in Tanzania to improve soil health while permanently sequestering carbon.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This innovative project turns agricultural byproducts into a stable form of carbon that enriches soils and reduces greenhouse gas emissions.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '2C3E50',
		),
		array(
			'title'   => __( 'Ocean Kelp Forest Expansion', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Restoring and expanding kelp forests along Norway\'s coastline to sequester blue carbon while supporting marine biodiversity.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our marine carbon sequestration project harnesses the power of kelp forests to capture CO2 while creating habitat for fish and marine life.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '006994',
		),
		array(
			'title'   => __( 'Grassland Carbon Storage', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Restoring degraded grasslands in Mongolia through rotational grazing and native grass reseeding to maximize carbon storage in prairie soils.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This grassland restoration initiative demonstrates the carbon sequestration potential of well-managed prairie ecosystems.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '7CB342',
		),
		array(
			'title'   => __( 'Wetland Restoration', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Restoring Louisiana\'s coastal wetlands to sequester carbon, protect against storm surges, and provide critical wildlife habitat.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our wetland restoration project creates powerful carbon sinks while protecting coastal communities from climate impacts.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '00695C',
		),
		array(
			'title'   => __( 'Direct Air Capture Pilot', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Testing innovative direct air capture technology in Iceland to remove CO2 from the atmosphere and store it permanently in basalt rock.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This cutting-edge pilot project explores the potential of technology-based carbon removal to complement nature-based solutions.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '37474F',
		),
		array(
			'title'   => __( 'Peatland Conservation', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Protecting and restoring Indonesia\'s peatland forests to prevent carbon release and maintain one of Earth\'s largest terrestrial carbon stores.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our peatland protection initiative safeguards massive carbon reserves while supporting local communities and biodiversity.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '5D4037',
		),
		array(
			'title'   => __( 'Blue Carbon Coastal Project', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Restoring seagrass meadows and mangroves in Vietnam to sequester blue carbon while protecting coastlines and supporting fisheries.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This integrated coastal ecosystem restoration project demonstrates the carbon sequestration power of marine and coastal habitats.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '0277BD',
		),
		array(
			'title'   => __( 'Agricultural Carbon Credits', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Supporting Indian farmers to adopt carbon-sequestering practices like cover cropping and reduced tillage while generating carbon credits.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This program creates economic incentives for farmers to adopt practices that capture carbon and improve soil health.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => 'F57C00',
		),
		array(
			'title'   => __( 'Mycorrhizal Network Enhancement', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Strengthening forest carbon storage in Germany by enhancing mycorrhizal fungal networks that help trees capture and store more carbon.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This innovative project works with natural fungal networks to enhance forests\' carbon capture capacity.', 'greengrowth-impact-showcase' ),
			'term'    => $carbon_capture_id,
			'color'   => '424242',
		),
		// Sustainable Farming projects.
		array(
			'title'   => __( 'Regenerative Agriculture Training', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Training Ghanaian farmers in regenerative practices including composting, crop rotation, and agroforestry to build soil health and resilience.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our farmer training program transforms agricultural practices to benefit both productivity and environmental sustainability.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => '558B2F',
		),
		array(
			'title'   => __( 'Agroforestry Systems', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Integrating trees into Costa Rican coffee and cacao farms to diversify income, improve soil health, and support biodiversity.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This agroforestry initiative demonstrates how trees and crops can work together for more productive and sustainable farming.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => '6D4C41',
		),
		array(
			'title'   => __( 'Vertical Farming Technology', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Establishing high-tech vertical farms in Singapore to produce fresh vegetables year-round with minimal water and no pesticides.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our vertical farming project showcases how technology can enable sustainable food production in urban environments.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => '00897B',
		),
		array(
			'title'   => __( 'Permaculture Demonstration', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Creating demonstration permaculture farms in New Zealand to teach regenerative design principles that work with natural ecosystems.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This living classroom demonstrates how permaculture principles can create abundant, self-sustaining food production systems.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => '689F38',
		),
		array(
			'title'   => __( 'Organic Rice Cultivation', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Supporting Thai farmers to transition to organic rice farming using natural pest management and composting to reduce chemical inputs.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our organic rice program helps farmers improve soil health and product quality while reducing environmental impact.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => 'FDD835',
		),
		array(
			'title'   => __( 'Sustainable Livestock Management', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Implementing rotational grazing and silvopasture systems in Uruguay to improve animal welfare while regenerating grassland ecosystems.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This livestock management project demonstrates how grazing animals can be part of ecosystem restoration.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => '8D6E63',
		),
		array(
			'title'   => __( 'Water-Smart Irrigation', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Installing efficient drip irrigation systems in Morocco to help farmers maintain productivity while reducing water consumption by 60%.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our water conservation program helps farmers adapt to water scarcity through smart irrigation technology.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => '0097A7',
		),
		array(
			'title'   => __( 'Indigenous Farming Techniques', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Reviving and scaling traditional Peruvian agricultural methods including terrace farming and crop diversity to build climate resilience.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This project honors and scales time-tested indigenous farming wisdom for modern sustainable agriculture.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => 'D84315',
		),
		array(
			'title'   => __( 'Community Supported Agriculture', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Connecting Dutch consumers directly with local organic farmers through CSA programs that support sustainable farming and reduce food miles.', 'greengrowth-impact-showcase' ),
			'content' => __( 'Our CSA network creates direct connections between farmers and eaters, supporting sustainable local food systems.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => 'FFA726',
		),
		array(
			'title'   => __( 'Pollinator-Friendly Farming', 'greengrowth-impact-showcase' ),
			'excerpt' => __( 'Creating wildflower corridors and reducing pesticide use on UK farms to support declining pollinator populations while maintaining crop yields.', 'greengrowth-impact-showcase' ),
			'content' => __( 'This pollinator support program demonstrates how farms can be havens for beneficial insects while producing abundant food.', 'greengrowth-impact-showcase' ),
			'term'    => $sustainable_farming_id,
			'color'   => 'FFD600',
		),
	);

	// Create projects and assign featured images.
	foreach ( $projects as $project_data ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $project_data['title'],
				'post_excerpt' => $project_data['excerpt'],
				'post_content' => $project_data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'gg_project',
			)
		);

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			// Assign service area term.
			if ( $project_data['term'] ) {
				wp_set_object_terms( $post_id, (int) $project_data['term'], 'gg_service_area' );
			}

			// Generate a placeholder image URL.
			$image_url = 'https://placehold.co/800x600/' . $project_data['color'] . '/FFFFFF/png?text=' . rawurlencode( wp_strip_all_tags( $project_data['title'] ) );

			// Download and attach the image.
			$attachment_id = gg_download_and_attach_image( $image_url, $post_id, $project_data['title'] );

			if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, $attachment_id );
			} else {
				// Fallback: store URL as meta field if download fails.
				update_post_meta( $post_id, '_gg_placeholder_image', $image_url );
			}
		}
	}
}

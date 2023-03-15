<?php

function thegem_add_product_attribute_color( $attrs ) {
	return array_merge(
		$attrs,
		array(
			'color' => __( 'Color Swatches', 'thegem' ),
			'label' => __( 'Label Swatches', 'thegem' ),
		)
	);
}
add_filter( 'product_attributes_type_selector', 'thegem_add_product_attribute_color', 10, 1 );

function thegem_init_swatches( $attrs ) {
	if ( thegem_is_plugin_active('woocommerce/woocommerce.php') && $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
		$added_action = false;
		foreach ( $attribute_taxonomies as $tax ) {
			if ( 'color' === $tax->attribute_type || 'label' === $tax->attribute_type ) {
				add_action( wc_attribute_taxonomy_name( $tax->attribute_name ) . '_add_form_fields', 'thegem_add_product_attribute_' . $tax->attribute_type . '_fields', 100, 1 );
				add_action( wc_attribute_taxonomy_name( $tax->attribute_name ) . '_edit_form_fields', 'thegem_edit_product_attribute_' . $tax->attribute_type . '_fields', 100, 2 );

				if ( ! $added_action ) {
					add_action( 'edit_term', 'thegem_save_product_extra_attribute_values', 100, 3 );
					add_action( 'delete_term', 'thegem_delete_product_extra_attribute_values', 10, 5 );
					add_action( 'created_term', 'thegem_save_product_extra_attribute_values', 100, 3 );
					$added_action = true;
				}
			}
		}
	}
}
add_action( 'init', 'thegem_init_swatches');

function thegem_save_product_extra_attribute_values( $term_id, $tt_id, $taxonomy ) {
	if ( strpos( $taxonomy, 'pa_' ) === false ) {
		return;
	}
	if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( 'color' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name )) {
				update_term_meta( $term_id, 'thegem_color', $_POST[ 'thegem_color' ]);
			}
			if ( 'label' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name )) {
				update_term_meta( $term_id, 'thegem_label', $_POST[ 'thegem_label' ]);
			}
		}
	}
}
function thegem_delete_product_extra_attribute_values( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
	if ( strpos( $taxonomy, 'pa_' ) === false ) {
		return;
	}
	if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( 'color' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				delete_term_meta( $term_id, 'thegem_color' );
			}
			if ( 'label' === $tax->attribute_type && $taxonomy === wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
				delete_term_meta( $term_id, 'thegem_label' );
			}
		}
	}
}

function thegem_get_product_attribute_color_field() {
	return array(
		'name'  => 'thegem_color',
		'title' => __( 'Color', 'thegem' ),
	);
}
function thegem_get_product_attribute_label_field() {
	return array(
		'name'  => 'thegem_label',
		'title' => __( 'Label', 'thegem' ),
		'desc'  => __( 'Input short label to be displayed instead of title such as "XS".', 'thegem' ),
	);
}

function thegem_add_product_attribute_color_fields( $taxonomy ) {
	wp_enqueue_script('color-picker');
	wp_enqueue_style('color-picker');
?>
<table class="form-field">
	<tr class="form-field">
		<th scope="row" valign="top"><label for="thegem_color"><?php esc_html_e( 'Color', 'thegem' ); ?></label></th>
		<td class="thegem-meta-color">
			<input type="text" id="thegem_color" name="thegem_color" value="" class="color-select" style="width: auto;" />
		</td>
	</tr>
</table>
<?php
}

function thegem_edit_product_attribute_color_fields( $tag, $taxonomy ) {
	wp_enqueue_script('color-picker');
	wp_enqueue_style('color-picker');
	$value = get_term_meta( $tag->term_id, 'thegem_color', true );
?>
<tr class="form-field">
	<th scope="row" valign="top"><label for="thegem_color"><?php esc_html_e( 'Color', 'thegem' ); ?></label></th>
	<td class="thegem-meta-color">
		<input type="text" id="thegem_color" name="thegem_color" value="<?php echo esc_attr($value); ?>" class="color-select" style="width: auto;" />
	</td>
</tr>
<?php
}

function thegem_add_product_attribute_label_fields( $taxonomy ) {
?>
<table class="form-field">
	<tr class="form-field">
		<th scope="row" valign="top"><label for="thegem_label"><?php esc_html_e( 'Label', 'thegem' ); ?></label></th>
		<td class="thegem-meta-color">
			<input type="text" id="thegem_label" name="thegem_label" value="" size="50%" class="text" />
		</td>
	</tr>
</table>
<?php
}

function thegem_edit_product_attribute_label_fields( $tag, $taxonomy ) {
	$value = get_term_meta( $tag->term_id, 'thegem_label', true );
?>
<tr class="form-field">
	<th scope="row" valign="top"><label for="thegem_label"><?php esc_html_e( 'Label', 'thegem' ); ?></label></th>
	<td class="thegem-meta-color">
		<input type="text" id="thegem_label" name="thegem_label" value="<?php echo esc_attr($value); ?>" class="text" />
	</td>
</tr>
<?php
}

function thegem_variation_attribute_options_html( $html, $args ) {
	$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name($args['attribute']));
	if(!empty($attribute_data) && ($attribute_data->type == 'color' || $attribute_data->type == 'label')) {
		// Get selected value.
		if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
			$selected_key = 'attribute_' . sanitize_title( $args['attribute'] );
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			$args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] );
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
		}

		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = 'gem-attribute-selector type-'.$attribute_data->type;
		$show_option_none      = (bool) $args['show_option_none'];
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}
		$html = '<div class="field-input">' . $html . '</div>';
		$html = '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">' . $html;
		//$html .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . sanitize_title( $args['selected'] ) . '" >';
		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms(
					$product->get_id(),
					$attribute,
					array(
						'fields' => 'all',
					)
				);

				$html .= '<ul class="styled gem-attribute-options">';
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$html .= '<li data-value="' . esc_attr( $term->slug ) . '"' . (sanitize_title( $args['selected'] ) == $term->slug ? ' class="selected"' : '') . '>';
						if ($attribute_data->type == 'color') {
							$color = get_term_meta( $term->term_id, 'thegem_color', true );
							$html .= '<span class="color"' . (!empty($color) ? ' style="background-color: ' . esc_attr($color).';"' : '') . '></span>';
						} else {
							$label = get_term_meta( $term->term_id, 'thegem_label', true );
							$label = empty($label) ? $term->name : $label;
							$html .= '<span class="label">' . esc_html($label) . '</span>';
						}
						$html .= '<span class="text">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</span>';
						$html .= '</li>';
					}
				}
				$html .= '</ul>';
			}
		}
		$html .= '</div>';
	}
	return $html;
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'thegem_variation_attribute_options_html', 10, 2 );

function thegem_woocommerce_product_option_terms( $attribute_taxonomy, $i, $attribute ) {
	if ( 'color' !== $attribute_taxonomy->attribute_type && 'label' !== $attribute_taxonomy->attribute_type ) {
		return;
	}
?>
<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woocommerce' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
	<?php
	$args      = array(
		'orderby'    => ! empty( $attribute_taxonomy->attribute_orderby ) ? $attribute_taxonomy->attribute_orderby : 'name',
		'hide_empty' => 0,
	);
	$all_terms = get_terms( $attribute->get_taxonomy(), apply_filters( 'woocommerce_product_attribute_terms', $args ) );
	if ( $all_terms ) {
		foreach ( $all_terms as $term ) {
			$options = $attribute->get_options();
			$options = ! empty( $options ) ? $options : array();
			echo '<option value="' . esc_attr( $term->term_id ) . '"' . wc_selected( $term->term_id, $options ) . '>' . esc_html( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
		}
	}
	?>
</select>
<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woocommerce' ); ?></button>
<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woocommerce' ); ?></button>
<button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'woocommerce' ); ?></button>
<?php
}
add_filter( 'woocommerce_product_option_terms', 'thegem_woocommerce_product_option_terms', 10, 3 );
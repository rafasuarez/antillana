<?php
/**
 * Underscore.js template.
 *
 * @since 2.0
 * @package fusion-library
 */

?>
<#
var fieldId = 'undefined' === typeof param.param_name ? param.id : param.param_name;
var value 		= option_value || param.value || param.default || '';
let position = '';

if ( !value.includes( ' ' ) ) {
	// in this case get the value from other element setting or parent setting.
	if ( value.startsWith( 'parent_' ) ) {
		let key = value.replace( 'parent_', '' );
		value = atts.parentValues ? atts.parentValues[key] || '' : '';
	} else {
		value = atts.parentValues ? atts.parentValues[value] || '' : '';
	}
}

position 	= value ? value.split( ' ' ) : [];
var left 		= position[0] ? `${position[0]}`: '50%';
var top 		= position[1] ? `${position[1]}`: '50%';
var pointStyle	= `top:${top}; left:${left};`;
#>
<div class="fusion-image-focus-point" data-lazy="{{ param.lazy }}">
	<div class="preview">
		<div class="image" data-image="{{ param.image }}">
			<!-- image should be here -->
		</div>
		<span class="fusiona-image no-image-holder"></span>
		<span class="point" style="{{ pointStyle }}"></span>
	</div>
	<input type="hidden" id="{{ fieldId }}" name="{{ fieldId }}" class="regular-text fusion-builder-focus-point-field" value='{{ option_value }}' />
</div>

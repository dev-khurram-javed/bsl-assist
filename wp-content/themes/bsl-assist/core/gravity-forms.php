<?php
/**
* Filters submit button.
* Replaces the form's <input> buttons with <button> while maintaining attributes from original <input>.
*
* @param string $button Contains the <input> tag to be filtered.
* @param object $form Contains all the properties of the current form.
*
* @return string The filtered button.
*/
add_filter( 'gform_next_button', 'input_to_button', 10, 2 );
add_filter( 'gform_previous_button', 'input_to_button', 10, 2 );
add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
function input_to_button( $button, $form ) {

	// Parse button element and get values
	$dom = new DOMDocument();
	$dom->loadHTML( $button );
	$input = $dom->getElementsByTagName( 'input' )->item(0);
	$input_label = $input->getAttribute( 'value' );
	$class = $input->getAttribute( 'class' );
	$onclick = $input->getAttribute( 'onclick' );
	$onkeypress = $input->getAttribute( 'onkeypress' );
	$id = $input->getAttribute( 'id' );

	$button_title = ($form['button']['text']) ? $form['button']['text'] : 'Submit' ;
	$button_title = (isset($form['button_params']) && $form['button_params']['label']) ? $form['button_params']['label'] : $button_title ;

	ob_start();
	
	// If it is a NEXT or PREVIOUS button
	if (str_contains($button, 'gform_next_button') || str_contains($button, 'gform_previous_button')) {
		// Render button inside form with values from GF for next and previous navigation
        component('button', [
            'link' => ['title' => $input_label],
            'type' => 'button'
        ], $class, [
            'id' => $id,
            'onclick' => $onclick,
            'onkeypress' => $onkeypress,
            'data-form-id' => $form['id']
        ]);
		// component('button', [
		// 	'link' => [
		// 		'title' => $input_label,
		// 	],
		// 	'style' => $form['button_params']['style'],
		// 	'type' => 'button'
		// ])
		// 	->add_attribute('id', $id)
		// 	->add_attribute('onclick', $onclick)
		// 	->add_attribute('onkeypress', $onkeypress)
		// 	->add_attribute('data-form-id', $form['id'])
		// 	->add_class($class)
		// 	->render();
	} else {
		// Render Submit button
        component('button', [
            'link' => ['title' => $button_title],
            'type' => 'button'
        ], '', [
            'id' => $id,
            'data-form-id' => $form['id']
        ]);
		// component('button', [
		// 	'link' => [
		// 		'title' => $button_title,
		// 	],
		// 	'style' => $form['button_params']['style'],
		// 	'type' => 'submit',
		// ])
		// 	->add_attribute('id', $id)
		// 	->add_attribute('data-form-id', $form['id'])
		// 	->render();
	}

	$new_button = ob_get_contents();
	ob_end_clean();

	// Return button
	return $new_button;
}

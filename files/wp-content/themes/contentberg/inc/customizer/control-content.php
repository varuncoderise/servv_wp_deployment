<?php
/**
 * Support dynamic content as a control
 */
class Bunyad_Customizer_Control_Content extends WP_Customize_Control
{

	public $type = 'content';
	public $text = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() 
	{
		parent::to_json();
		$this->json['text'] = $this->text;
	}

	/**
	 * Render a JS template
	 */
	public function content_template() 
	{ 
	
	?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.text ) { #>
			{{{ data.text }}}
		<# } #>

	<?php 
	
	}
}
<?php

class E_Register_NowAsset__Factory {
	/**
	 * @param string $name
	 *
	 * @return E_Register_NowAsset__Abstract_Asset|false Either a new instance of the asset class or false.
	 */
	public function make_for_name( $name ) {
		// `jquery-resize` to `Jquery_Resize`
		$class_name = $this->get_asset_class_name( $name );

		// `Jquery_Resize` to `E_Register_NowAsset__Jquery_Resize`
		$full_class_name = $this->get_asset_full_class_name( $class_name );

		return class_exists( $full_class_name ) ? new $full_class_name() : false;
	}

	protected function get_asset_class_name( $name ) {
		// `jquery-resize` to `Jquery_Resize`
		$class_name = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $name ) ) );

		return $class_name;
	}

	/**
	 * @param string $class_name
	 *
	 * @return string
	 */
	private function get_asset_full_class_name( $class_name ) {
		// `Jquery_Resize` to `E_Register_NowAsset__Jquery_Resize`
		$full_class_name = $this->get_asset_class_name_prefix() . $class_name;

		return $full_class_name;
	}

	/**
	 * @return string
	 */
	protected function get_asset_class_name_prefix() {
		return 'E_Register_NowAsset__';
	}

	/**
	 * @return E_Register_NowAsset__Factory
	 */
	public static function instance() {
		static $instance;

		if ( ! $instance ) {
			$instance = new self;
		}

		return $instance;
	}
}

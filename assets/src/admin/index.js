/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { render } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './index.scss';

wp.element.render(
  <h1>{__("I hate react", "my-textdomain")}</h1>,
  document.getElementById("wc_mnm_bulk_discount_data")
);
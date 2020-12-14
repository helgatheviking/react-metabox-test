/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { render } from '@wordpress/element';
import { Table } from '@woocommerce/components';

/**
 * Interal dependencies
 */
import './style.scss';

function App() {

    const headers = [ { label: 'Month' }, { label: 'Orders' }, { label: 'Revenue' } ];
    const rows = [
        [
            { display: 'January', value: 1 },
            { display: 10, value: 10 },
            { display: '$530.00', value: 530 },
        ],
        [
            { display: 'February', value: 2 },
            { display: 13, value: 13 },
            { display: '$675.00', value: 675 },
        ],
        [
            { display: 'March', value: 3 },
            { display: 9, value: 9 },
            { display: '$460.00', value: 460 },
        ],
    ]

  return (
    <Table
        caption="Revenue Last Week"
        rows={ rows }
        headers={ headers }
    />
  );
}

render(
  <App />,
  document.getElementById( "react-metabox-example-root" )
);

/**
 * External dependencies
 */
import { WooNavigationItem } from '@woocommerce/navigation';
import { registerPlugin } from '@wordpress/plugins';

const MyExtenstionNavItem = () => (
	<WooNavigationItem item="my-extension">Hello</WooNavigationItem>
);

registerPlugin( 'my-extension', {
	render: MyExtenstionNavItem,
	scope: 'woocommerce-admin',
} );

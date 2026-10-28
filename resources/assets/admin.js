// these JS + SCSS will be automatically available after installing the package
import { registerNajaExtensions } from './core/base.js';
import { initAdminTheme } from './core/admin-theme.js';
import Spinner from './naja/spinner.js';
import PermissionToggle from './naja/permission-toggle.js';
import GalleryCopy from './naja/gallery.js';

// drago-ex extensions
import { SubmitButtonDisable, TomSelectHandler } from 'drago-form';
import { BootstrapComponents, BootstrapDropdowns } from 'drago-component';
import { ToastHandler } from 'drago-application';
import DataGrid from 'drago-datagrid';
import TexyToolbar from 'drago-texy-toolbar';

// page styles
import './admin.scss';

document.addEventListener('DOMContentLoaded', () => {
	initAdminTheme();
});

new DataGrid().initialize(naja);
registerNajaExtensions(
	Spinner,
	PermissionToggle,
	SubmitButtonDisable,
	TomSelectHandler,
	BootstrapComponents,
	BootstrapDropdowns,
	ToastHandler,
	TexyToolbar,
	GalleryCopy
);

import './bootstrap';
import './bootstrap/bootstrap';
import './bootstrap/bootstrap.bundle';
import './bootstrap/bootstrap-select';
import './bootstrap/bootstrap-table.min';
import './bootstrap/bootstrap-datepicker';
import './data-table.min';
import './treegrid/jquery.treegrid.min'
import './cropme/cropme';
import './chartjs/chart';
import './script';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();

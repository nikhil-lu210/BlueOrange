<?php

if (!function_exists('getColor')) {
    /**
     * Get the appropriate color class based on the status or priority.
     *
     * @param string $status The status or priority value
     * @return string The color class name
     */
    function getColor($status) {
        switch ($status) {
            case 'Active':
            case 'Low':
                return 'info';

            case 'Running':
            case 'Medium':
                return 'primary';

            case 'Completed':
                return 'success';

            case 'Average':
                return 'warning';

            case 'Cancelled':
            case 'High':
                return 'danger';

            default:
                return 'dark';
        }
    }
}

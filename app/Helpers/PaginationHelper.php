<?php

if (!function_exists('pagination')) {

    /**
     * Generate the pagination HTML for paginated data.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $datas The paginated data
     * @param string $position The position of the pagination controls (can be 'start', 'center', or 'end')
     * @param string $color The color of the pagination controls (can be 'info', 'primary', 'warning', 'danger', 'success', 'dark')
     * @return string The HTML for pagination links
     */
    function pagination($datas, $position = 'center', $color = 'primary')
    {
        // Ensure that the position is one of the allowed values: 'start', 'center', 'end'
        if (!in_array($position, ['start', 'center', 'end'])) {
            $position = 'center'; // Default to 'center' if an invalid position is provided
        }
        
        // Ensure that the color is one of the allowed values: 'info', 'primary', 'warning', 'danger', 'success', 'dark'
        if (!in_array($color, ['info', 'primary', 'warning', 'danger', 'success', 'dark'])) {
            $color = 'primary'; // Default to 'primary' if an invalid color is provided
        }

        // If there's only one page, don't show pagination controls
        if ($datas->lastPage() <= 1) {
            return ''; // Return an empty string if only one page
        }

        // Start building the pagination HTML
        $paginationHtml = '<div class="col-12"><div class="demo-inline-spacing">';
        $paginationHtml .= '<nav aria-label="Page navigation"><ul class="pagination pagination-' . $color . ' justify-content-' . $position . '">';

        // Previous Page Link
        if ($datas->onFirstPage()) {
            $paginationHtml .= '<li class="page-item prev disabled">
                                    <a class="page-link" href="javascript:void(0);">
                                        <i class="ti ti-chevrons-left ti-xs"></i>
                                    </a>
                                  </li>';
        } else {
            $paginationHtml .= '<li class="page-item prev">
                                    <a class="page-link" href="' . $datas->previousPageUrl() . '">
                                        <i class="ti ti-chevrons-left ti-xs"></i>
                                    </a>
                                  </li>';
        }

        // Page Number Links
        for ($i = 1; $i <= $datas->lastPage(); $i++) {
            $activeClass = ($datas->currentPage() == $i) ? 'active' : '';
            $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                    <a class="page-link" href="' . $datas->url($i) . '">' . $i . '</a>
                                  </li>';
        }

        // Next Page Link
        if ($datas->hasMorePages()) {
            $paginationHtml .= '<li class="page-item next">
                                    <a class="page-link" href="' . $datas->nextPageUrl() . '">
                                        <i class="ti ti-chevrons-right ti-xs"></i>
                                    </a>
                                  </li>';
        } else {
            $paginationHtml .= '<li class="page-item next disabled">
                                    <a class="page-link" href="javascript:void(0);">
                                        <i class="ti ti-chevrons-right ti-xs"></i>
                                    </a>
                                  </li>';
        }

        $paginationHtml .= '</ul></nav></div></div>';

        return $paginationHtml;
    }
}

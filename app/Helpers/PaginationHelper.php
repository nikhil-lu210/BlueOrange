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

        // Page Number Links with ellipsis
        $currentPage = $datas->currentPage();
        $lastPage = $datas->lastPage();
        
        // Always show first page
        $activeClass = ($currentPage == 1) ? 'active' : '';
        $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                <a class="page-link" href="' . $datas->url(1) . '">1</a>
                              </li>';
        
        if ($lastPage <= 7) {
            // If total pages <= 7, show all pages
            for ($i = 2; $i <= $lastPage; $i++) {
                $activeClass = ($currentPage == $i) ? 'active' : '';
                $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                        <a class="page-link" href="' . $datas->url($i) . '">' . $i . '</a>
                                      </li>';
            }
        } else {
            // Show ellipsis and smart pagination
            if ($currentPage <= 4) {
                // Show pages 2-5, then ellipsis, then last page
                for ($i = 2; $i <= 5; $i++) {
                    $activeClass = ($currentPage == $i) ? 'active' : '';
                    $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                            <a class="page-link" href="' . $datas->url($i) . '">' . $i . '</a>
                                          </li>';
                }
                $paginationHtml .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                $activeClass = ($currentPage == $lastPage) ? 'active' : '';
                $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                        <a class="page-link" href="' . $datas->url($lastPage) . '">' . $lastPage . '</a>
                                      </li>';
            } elseif ($currentPage >= $lastPage - 3) {
                // Show first page, ellipsis, then last 4 pages
                $paginationHtml .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                for ($i = $lastPage - 4; $i <= $lastPage; $i++) {
                    $activeClass = ($currentPage == $i) ? 'active' : '';
                    $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                            <a class="page-link" href="' . $datas->url($i) . '">' . $i . '</a>
                                          </li>';
                }
            } else {
                // Show first page, ellipsis, current-1, current, current+1, ellipsis, last page
                $paginationHtml .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                
                for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                    $activeClass = ($currentPage == $i) ? 'active' : '';
                    $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                            <a class="page-link" href="' . $datas->url($i) . '">' . $i . '</a>
                                          </li>';
                }
                
                $paginationHtml .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                $activeClass = ($currentPage == $lastPage) ? 'active' : '';
                $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                        <a class="page-link" href="' . $datas->url($lastPage) . '">' . $lastPage . '</a>
                                      </li>';
            }
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

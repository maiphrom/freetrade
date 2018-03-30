<?php

    class Pagination_center
    {
        function paginating($page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20){
            $pages = ((int) ceil($row_total / $per_page));
            $max = min($pages, $page_limit);
            $limit = ((int) floor($max / 2));
            $leading = $limit;
            for ($x = 0; $x < $limit; ++$x) {
                if ($page_now === ($x + 1)) {
                    $leading = $x;
                    break;
                }
            }
            for ($x = $pages - $limit; $x < $pages; ++$x) {
                if ($page_now === ($x + 1)) {
                    $leading = $max - ($pages - $x);
                    break;
                }
            }
            $trailing = $max - $leading - 1;
            $paginating = '';
            $paginating .= '<ul class="clearfix pagination">';
            if($page_now == 1){
                $prev_class = 'disabled';
                $href = '#';
            }else{
                $prev_class = '';
                $href = '?page='.($page_now-1);
            }
            $paginating .= '<li class="copy previous '.$prev_class.'"><a href="'.$href.'">&laquo; Previous</a></li>';
            for ($x = 0; $x < $leading; ++$x) {
                $x_page = ($page_now + $x - $leading);
                $paginating .= '<li class="number"><a data-pagenumber="'.$x_page.'" href="?page='.$x_page.'">'.$x_page.'</a></li>';
            }
            $paginating .= '<li class="number active"><a data-pagenumber="'.$page_now.'" href="#">'.$page_now.'</a></li>';
            for ($x = 0; $x < $trailing; ++$x) {
                $x_page = ($page_now + $x + 1);
                $paginating .= '<li class="number"><a data-pagenumber="'.$x_page.'" href="?page='.$x_page.'">'.$x_page.'</a></li>';
            }
            if(($page_now+1) > $pages){
                $next_class = 'disabled';
                $href = '#';
            }else{
                $next_class = '';
                $href = '?page='.($page_now+1);
            }
            $paginating .= '<li class="copy next '.$next_class.'"><a href="'.$href.'">Next &raquo;</a></li>';
            $paginating .= '</ul>';
            return $paginating;
        }
    }

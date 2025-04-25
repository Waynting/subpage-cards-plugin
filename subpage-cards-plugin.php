<?php
/*
Plugin Name: Subpage Cards Shortcode
Description: 顯示特定主頁面（以 slug camera-drift-project 為基準）底下所有子頁面為縮圖卡片
Version: 1.0
Author: Wayn Liu
*/

function wayn_subpage_cards_shortcode($atts) {
    // 抓取主頁面 ID（用 slug 找頁面）
    $parent_page = get_page_by_path('camera-drift-project');
    if (!$parent_page) {
        return '<p>找不到主頁面 camera-drift-project。</p>';
    }

    $child_pages = get_pages([
        'child_of' => $parent_page->ID,
        'sort_column' => 'menu_order',
    ]);

    if (empty($child_pages)) {
        return '<p>目前尚無子頁面內容。</p>';
    }

    $html = '<div class="subpage-card-container">';

    foreach ($child_pages as $page) {
        $title = esc_html($page->post_title);
        $link = get_permalink($page->ID);
        $thumb = get_the_post_thumbnail_url($page->ID, 'medium');

        // 若無特色圖則使用預設圖片
        if (!$thumb) {
            $thumb = 'https://waynspace.com/wp-content/uploads/default.jpg'; // 你可以改成自己的預設圖
        }

        $html .= '<a class="subpage-card" href="' . esc_url($link) . '">';
        $html .= '<img src="' . esc_url($thumb) . '" alt="' . $title . '">';
        $html .= '<p>' . $title . '</p>';
        $html .= '</a>';
    }

    $html .= '</div>';

    return $html;
}
add_shortcode('subpage_cards', 'wayn_subpage_cards_shortcode');

// 插入前端樣式
function wayn_subpage_cards_styles() {
    echo '
    <style>
    .subpage-card-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin: 2em 0;
    }
    .subpage-card {
        width: 200px;
        text-align: center;
        text-decoration: none;
        color: inherit;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        transition: 0.3s ease;
    }
    .subpage-card img {
        width: 100%;
        height: auto;
        display: block;
    }
    .subpage-card p {
        padding: 10px;
        font-weight: 500;
    }
    .subpage-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    </style>';
}
add_action('wp_head', 'wayn_subpage_cards_styles');


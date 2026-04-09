<?php

/**
 * @var $changelog_link
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly

if (!function_exists('plugins_api')) {
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
}

// Ensure oEmbed is loaded
if (!function_exists('wp_oembed_get')) {
    include_once ABSPATH . 'wp-includes/embed.php';
}

$api = plugins_api(
    'plugin_information',
    array(
        'slug' => wp_unslash('webinar-ignition'),
    )
);
// Add this after including embed.php but before processing the changelog
// if (!function_exists('wp_oembed_get')) {
function manual_youtube_embed($content)
{
    $pattern = '/\[youtube\s+(https?:\/\/www\.youtube\.com\/watch\?v=([^\s\]]+))[^\]]*\]/i';
    $replacement = '<iframe width="560" height="315" src="https://www.youtube.com/embed/$2" frameborder="0" allowfullscreen></iframe>';
    return preg_replace($pattern, $replacement, $content);
}

// }


if (!is_wp_error($api) && !empty((array) $api->sections)) {
    $latest_plugin_version = $api->version;
    $changelog_version = get_option('wi_changelog_version');
    $current_version = WEBINARIGNITION_VERSION;

    update_option('wi_changelog_version', $latest_plugin_version);
	$plugins_allowedtags = array(
        'a'          => array(
            'href'   => array(),
            'title'  => array(),
            'target' => array(),
        ),
        'abbr'       => array('title' => array()),
        'acronym'    => array('title' => array()),
        'code'       => array(),
        'pre'        => array(),
        'em'         => array(),
        'strong'     => array(),
        'div'        => array('class' => array()),
        'span'       => array('class' => array()),
        'p'          => array(),
        'br'         => array(),
        'ul'         => array(),
        'ol'         => array(),
        'li'         => array(),
        'h1'         => array(),
        'h2'         => array(),
        'h3'         => array(),
        'h4'         => array(),
        'h5'         => array(),
        'h6'         => array(),
        'img'        => array(
            'src'   => array(),
            'class' => array(),
            'alt'   => array(),
        ),
        'blockquote' => array('cite' => true),
        'iframe'     => array(
            'src'               => array(),
            'width'             => array(),
            'height'            => array(),
            'title'             => array(),
            'frameborder'       => array(),
            'allow'             => array(),
            'allowfullscreen'   => array(),
            'loading'           => array(),
            'referrerpolicy'    => array(),
        ),
    );
    $all_html_tags = array(
		'a' => true,
		'abbr' => true,
		'address' => true,
		'area' => true,
		'article' => true,
		'aside' => true,
		'audio' => true,
		'b' => true,
		'base' => true,
		'bdi' => true,
		'bdo' => true,
		'blockquote' => true,
		'body' => true,
		'br' => true,
		'button' => true,
		'canvas' => true,
		'caption' => true,
		'cite' => true,
		'code' => true,
		'col' => true,
		'colgroup' => true,
		'data' => true,
		'datalist' => true,
		'dd' => true,
		'del' => true,
		'details' => true, 
		'dfn' => true,
		'dialog' => true,
		'div' => true,
		'dl' => true,
		'dt' => true,
		'em' => true,
		'embed' => true,
		'fieldset' => true,
		'figcaption' => true,
		'figure' => true,
		'footer' => true,
		'form' => true,
		'h1' => true,
		'h2' => true,
		'h3' => true,
		'h4' => true,
		'h5' => true,
		'h6' => true,
		'head' => true,
		'header' => true,
		'hgroup' => true,
		'hr' => true,
		'html' => true,
		'i' => true,
		'iframe' => true,
		'img' => true,
		'input' => true,
		'ins' => true,
		'kbd' => true,
		'keygen' => true,
		'label' => true,
		'legend' => true,
		'li' => true,
		'link' => true,
		'main' => true,
		'map' => true,
		'mark' => true,
		'menu' => true,
		'menuitem' => true,
		'meta' => true,
		'meter' => true,
		'nav' => true,
		'noscript' => true,
		'object' => true,
		'ol' => true,
		'optgroup' => true,
		'option' => true,
		'output' => true,
		'p' => true,
		'param' => true,
		'picture' => true,
		'pre' => true,
		'progress' => true,
		'q' => true,
		'rp' => true,
		'rt' => true,
		'ruby' => true,
		's' => true,
		'samp' => true,
		'script' => true,
		'section' => true,
		'select' => true,
		'small' => true,
		'source' => true,
		'span' => true,
		'strong' => true,
		'style' => true,
		'sub' => true,
		'summary' => true,
		'sup' => true,
		'table' => true,
		'tbody' => true,
		'td' => true,
		'textarea' => true,
		'tfoot' => true,
		'th' => true,
		'thead' => true,
		'time' => true,
		'title' => true,
		'tr' => true,
		'track' => true,
		'u' => true,
		'ul' => true,
		'var' => true,
		'video' => true,
		'wbr' => true
	);
	
	foreach ($all_html_tags as $tag => $attributes) {
		$all_html_tags[$tag] = array_fill_keys(['class', 'id', 'style', 'src', 'href', 'alt', 'title', 'type', 'value', 'name', 'target', 'action', 'method', 'checked', 'selected', 'placeholder', 'width', 'height', 'border', 'align', 'valign', 'lang', 'xml:lang', 'aria-label', 'role', 'data-*', 'aria-hidden', 'aria-labelledby', 'aria-describedby', 'rel', 'media', 'accept', 'accept-charset', 'charset', 'async', 'defer', 'property', 'http-equiv', 'content', 'viewBox', 'd', 'x', 'y', 'viewbox', 'preserveAspectRatio', 'xmlns', 'version', 'baseProfile', 'required', 'readonly'], true);
	}

    $changelog = $api->sections['changelog'];
    $description = $api->sections['description'];

    ob_start();
    echo wp_kses($changelog, array_merge(
		wp_kses_allowed_html('post'), // Allow default WordPress post tags and attributes.
		array(
			'*' => array( // Allow all tags.
				'style' => true, // Allow inline CSS on all tags.
				'class' => true, // Allow CSS classes.
				'id'    => true, // Allow IDs.
				'data-*' => true, // Allow data attributes.
				'required' => true, // Allow required attribute on all tags.
				'readonly' => true, // Allow required attribute on all tags.
			),
		),
		$all_html_tags));
    $changelog_html = ob_get_clean();
} else {
    ob_start();
?>
    <iframe scrolling="no" width="100%" height="188200px" src="<?php echo esc_attr($changelog_link); ?>" title="<?php esc_attr_e('WebinarIgnition Support', 'webinar-ignition'); ?>" style="border:none;"></iframe>

    <style>
        iframe {
            overflow: hidden;
        }
    </style>
<?php
    $changelog_html = ob_get_clean();
} //end if

?>

<div id="wi-plugin-information" class="wrap">
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <h1><?php echo esc_html__('Changelog', 'webinar-ignition'); ?></h1>
            <?php if (isset($changelog_version) && $changelog_version !== $current_version) : ?>
                <div class="notice notice-info">
                    <p><?php esc_html_e('New updates available!', 'webinar-ignition'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="wi-changelog-content">
        <?php
        // Output the sanitized changelog HTML with optional YouTube embed handling
        $changelog_html = preg_replace('/<a\s+(.*?)>/', '<a $1 target="_blank">', $changelog_html);
        echo wp_kses(manual_youtube_embed($changelog_html, $plugins_allowedtags),array_merge(
			wp_kses_allowed_html('post'), // Allow default WordPress post tags and attributes.
			array(
				'*' => array( // Allow all tags.
					'style' => true, // Allow inline CSS on all tags.
					'class' => true, // Allow CSS classes.
					'id'    => true, // Allow IDs.
					'data-*' => true, // Allow data attributes.
					'required' => true, // Allow required attribute on all tags.
					'readonly' => true, // Allow required attribute on all tags.
				),
			),
			$all_html_tags
		));
        ?>
    </div>

</div>
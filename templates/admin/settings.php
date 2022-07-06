<?php
// get upload directory
global $wp_filesystem;
$upload_dir = wp_upload_dir();
$audit_dir = ($upload_dir['basedir'] . '/content-audits/');
$audits = glob($audit_dir . '*.xlsx');
usort($audits, function ($a, $b) {
    return filemtime($b) - filemtime($a);
});

// get post types
$built_in_post_types = get_post_types(array(
    'public' => true,
    '_builtin' => true
), 'names', 'and');
$post_types = get_post_types(array(
    'public' => true,
    '_builtin' => false
), 'names', 'and');

// get taxonomies
$built_in_taxonomies = get_taxonomies(array(
    'public' => true,
    '_builtin' => true
), 'names', 'and');
$taxonomies = get_taxonomies(array(
    'public' => true,
    '_builtin' => false
), 'names', 'and');
?>

<?php if (isset($_GET['message']) && $_GET['message'] == '1') { ?>
    <div id='message' class='updated fade'>
        <p><strong><?php _e('Content Audit Generated', 'content-audit-exporter'); ?></strong></p>
    </div>
<?php } else if (isset($_GET['message']) && $_GET['message'] == '2') { ?>
    <div id='message' class='updated fade'>
        <p><strong><?php _e('Content Audit Removed', 'content-audit-exporter'); ?></strong></p>
    </div>
<?php } else if (isset($_GET['message']) && $_GET['message'] == '3') { ?>
    <div id='message' class='error fade'>
        <p><strong><?php _e('No Post Types Were Selected', 'content-audit-exporter'); ?></strong></p>
    </div>
<?php } ?>

<div class="wrap">
    <h1><?php _e('Content Audit Exporter', 'content-audit-exporter'); ?></h1>
    <!-- FORM -->
    <form method="post" action="admin-post.php">
        <!-- POST TYPES -->
        <p><?php _e('Post types found on WordPress.', 'content-audit-exporter'); ?></p>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <?php _e('Post Types', 'content-audit-exporter'); ?>
                </th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span><?php _e('Post Types', 'content-audit-exporter'); ?></span>
                        </legend>
                        <?php
                        $i = 0;
                        $len = count($built_in_post_types);

                        // if there are post types
                        if ($built_in_post_types) {
                            // for each post type
                            foreach ($built_in_post_types as $post_type) {
                                ?>
                                <label for="post_type_option_<?php echo $post_type; ?>">
                                    <input name="post_type_option[]" type="checkbox"
                                           id="post_type_option_<?php echo $post_type; ?>"
                                           value="<?php echo $post_type; ?>">
                                    <?php echo $post_type; ?>
                                </label>
                                <br>
                                <?php
                                $i++;
                            }
                        }

                        $i = 0;
                        $len = count($post_types);

                        // if there are post types
                        if ($post_types) {
                            // for each post type
                            foreach ($post_types as $post_type) {
                                ?>
                                <label for="post_type_option_<?php echo $post_type; ?>">
                                    <input name="post_type_option[]" type="checkbox"
                                           id="post_type_option_<?php echo $post_type; ?>"
                                           value="<?php echo $post_type; ?>">
                                    <?php echo $post_type; ?>
                                </label>
                                <br>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- TAXONOMIES -->
        <p><?php _e('Taxonomies found on WordPress.', 'content-audit-exporter'); ?></p>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <?php _e('Taxonomies', 'content-audit-exporter'); ?>
                </th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span><?php _e('Taxonomies', 'content-audit-exporter'); ?></span>
                        </legend>
                        <?php
                        $i = 0;
                        $len = count($built_in_taxonomies);

                        // if there are post types
                        if ($built_in_taxonomies) {
                            // for each post type
                            foreach ($built_in_taxonomies as $taxonomy) {
                                ?>
                                <label for="taxonomy_option_<?php echo $taxonomy; ?>">
                                    <input name="taxonomy_option[]" type="checkbox"
                                           id="taxonomy_option_<?php echo $taxonomy; ?>"
                                           value="<?php echo $taxonomy; ?>">
                                    <?php echo $taxonomy; ?>
                                </label>
                                <br>
                                <?php
                                $i++;
                            }
                        }

                        $i = 0;
                        $len = count($taxonomies);

                        // if there are post types
                        if ($taxonomies) {
                            // for each post type
                            foreach ($taxonomies as $taxonomy) {
                                ?>
                                <label for="taxonomy_option_<?php echo $taxonomy; ?>">
                                    <input name="taxonomy_option[]" type="checkbox"
                                           id="taxonomy_option_<?php echo $taxonomy; ?>"
                                           value="<?php echo $taxonomy; ?>">
                                    <?php echo $taxonomy; ?>
                                </label>
                                <br>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('ca_create_content_audit_nonce'); ?>
        <input type="hidden" name="action" value="create_content_audit"/>
        <?php submit_button(__('Generate New Audit', 'content-audit-exporter')); ?>
    </form>
    <!-- AUDITS -->
    <h2 class="title"><?php _e('Content Audits', 'content-audit-exporter'); ?></h2>
    <table class="audit-list striped">
        <tbody>
        <?php
        foreach ($audits as $audit) {
            $filename = str_replace($audit_dir, '', $audit);
            $upload_url = wp_get_upload_dir();
            $file = $upload_url['baseurl'] . '/content-audits/' . $filename;
            ?>
            <tr>
                <td>
                    <span class="audit-date">
                        <?php
                        echo date("F d Y h:iA", filemtime($audit));
                        ?>
                    </span>
                </td>
                <td>
                    <a href="<?php echo $file; ?>"><?php _e('Download', 'content-audit-exporter'); ?></a> |
                    <!-- FORM -->
                    <form method="post" action="admin-post.php" class="delete-form">
                        <?php wp_nonce_field('ca_delete_content_audit_nonce'); ?>
                        <input type="hidden" name="action" value="delete_content_audit"/>
                        <input type="hidden" name="file_path" value="<?php echo $audit; ?>"/>
                        <input class="delete-audit" type="submit"
                               value="<?php _e('Delete', 'content-audit-exporter'); ?>"/>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
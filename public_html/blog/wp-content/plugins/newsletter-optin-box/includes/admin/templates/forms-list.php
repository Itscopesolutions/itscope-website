<?php
/**
 * View forms
 *
 *
 * @since             1.0.0
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    die;
}

$link  = esc_url( add_query_arg( 'action', 'new' ) );
$stats = noptin_get_optin_stats();

?>
<div class="noptin-popup-designer noptin-list">
    <div class="noptin-list-inner">
    <header class="noptin-list-header">
        <div class="noptin-list-action">
            <a href="<?php echo $link; ?>" class="noptin-add-button"><?php _e( 'Add New Form',  'newsletter-optin-box'); ?></a>
        </div>
        <div class="noptin-list-filter">
            <span class="dashicons dashicons-search"></span>
            <input type="search" placeholder="<?php _e( 'Search Forms',  'newsletter-optin-box' )?>">
        </div>
    </header>
    <main class="content">
        <table class="noptin-list-table">
            <thead>
                <tr>
                    <th><?php _e( 'Name',  'newsletter-optin-box' )?></th>
					<th><?php _e( 'Status',  'newsletter-optin-box' )?></th>
					<th><?php _e( 'Subscribers',  'newsletter-optin-box' )?></th>
                    <th><?php _e( 'Created',  'newsletter-optin-box' )?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $forms as $form ){
                        $status         = ( 'draft' == $form->post_status ) ? __('Inactive',  'newsletter-optin-box') : __('Active',  'newsletter-optin-box');
                        $url            = esc_url( admin_url( 'admin.php?page=noptin-forms&form_id=' ) . $form->ID );
                        $delete         = esc_url( admin_url( 'admin.php?page=noptin-forms&action=delete&delete=' ) . $form->ID );
                        $duplicate      = esc_url( admin_url( 'admin.php?page=noptin-forms&action=duplicate&duplicate=' ) . $form->ID );
						$subscribers	= 0;

						$duplicate_text = __( 'Duplicate',  'newsletter-optin-box' );
						$edit_text      = __( 'Edit',  'newsletter-optin-box' );
						$delete_text    = __( 'Delete',  'newsletter-optin-box' );
						$delete_prompt  = esc_attr__( 'This will permanently delete the form. Are you sure?',  'newsletter-optin-box' );

						if(! empty( $stats[$form->ID] ) ) {
							$subscribers	= absint( $stats[$form->ID] );
						}
                        printf(
                            '<tr><td><a title="%s" href="%s">%s<strong style="color: #555"> &mdash; %s</strong></a><div class="noptin-form-actions"><span>%s | </span><span>%s | </span><span>%s</span></div></td><td  class="status-%s">%s</td><td>%s</td><td>%s</td></tr>',
                            esc_attr( __('Click To Edit Form ',  'newsletter-optin-box') . $form->post_title ),
                            $url,
                            esc_html( $form->post_title ),
                            esc_html( get_post_meta( $form->ID, '_noptin_optin_type', true ) ),
                            "<a onClick=\"return confirm('$delete_prompt')\" href='$delete' class='noptin-delete'>$delete_text</a>",
                            "<a href='$duplicate'>$duplicate_text</a>",
                            "<a href='$url'>$edit_text</a>",
                            $status,
							$status,
							$subscribers,
                            date("l, dS M", strtotime( $form->post_date ) )
                        );

                    }

                ?>
            </tbody>
        </table>
    </main>
</div>
</div>

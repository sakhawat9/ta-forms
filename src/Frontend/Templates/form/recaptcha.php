<?php
$options                 = get_option('dfs-opt');
$ta_forms_recaptcha_version = ! empty($options['ta_forms_recaptcha_version']) ? $options['ta_forms_recaptcha_version'] : 'v2';
$captcha_site_key_v2   = isset($options['dfs-recaptcha-sitekey']) ? $options['dfs-recaptcha-sitekey'] : '';
$captcha_site_key_v3   = isset($options['dfs-recaptcha-sitekey_v3']) ? $options['dfs-recaptcha-sitekey_v3'] : '';

// Enqueue reCAPTCHA scripts if enabled
if ($captcha_site_key_v3 || $captcha_site_key_v2) {
    if ('v2' === $ta_forms_recaptcha_version) {
        wp_enqueue_script('dfs-recaptcha', '//www.google.com/recaptcha/api.js', array('jquery'), DOMAIN_FOR_SALE_PRO_VERSION, true);
    } else {
        wp_enqueue_script('dfs-recaptcha-v3', '//www.google.com/recaptcha/api.js?render=' . $captcha_site_key_v3, array(), DOMAIN_FOR_SALE_PRO_VERSION, true);
        wp_add_inline_script(
            'dfs-recaptcha-v3',
            'grecaptcha.ready(function() {
                    grecaptcha.execute("' . $captcha_site_key_v3 . '", {action: "submit"}).then(function(token) {
                        document.getElementById("token").value = token;
                    });
                });'
        );
    }
}


if ('v2' === $ta_forms_recaptcha_version) {
    echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($captcha_site_key_v2) . '"></div>';
} elseif ('v3' === $ta_forms_recaptcha_version) {
?>
    <input type="hidden" id="token" name="token">
<?php }

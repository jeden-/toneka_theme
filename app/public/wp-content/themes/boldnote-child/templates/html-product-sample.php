<?php
if (!defined("ABSPATH")) {
    exit;
}
?>
<tr>
    <td class="sort"></td>
    <td>
        <input type="text" class="input_text" placeholder="<?php esc_attr_e("Nazwa pliku", "boldnote-child"); ?>" name="_product_sample_names[]" value="<?php echo isset($file["name"]) ? esc_attr($file["name"]) : ""; ?>" />
    </td>
    <td>
        <input type="text" class="input_text" placeholder="<?php esc_attr_e("http://", "boldnote-child"); ?>" name="_product_sample_files[]" value="<?php echo isset($file["file"]) ? esc_attr($file["file"]) : ""; ?>" />
    </td>
    <td class="file_url_choose">
        <button class="button upload_sample_file_button" data-choose="<?php esc_attr_e("Wybierz plik", "boldnote-child"); ?>" data-update="<?php esc_attr_e("Wstaw plik", "boldnote-child"); ?>"><?php echo esc_html__("Wybierz plik", "boldnote-child"); ?></button>
    </td>
    <td>
        <button class="button delete"><?php _e("Usuń", "boldnote-child"); ?></button>
    </td>
</tr>

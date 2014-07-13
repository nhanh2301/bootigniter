<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     BootIgniter Pack
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 *
 */

// -----------------------------------------------------------------------------

/**
 * BootIgniter pack Languages
 *
 * @subpackage  Translation
 * @category    lLanguage
 */

// -----------------------------------------------------------------------------
// Buttons
// -----------------------------------------------------------------------------
$lang['submit_btn']     = 'Simpan';
$lang['reset_btn']      = 'Batal';
$lang['print_btn']      = 'Cetak';
$lang['backup_btn']     = 'Backup sekarang';
$lang['restore_btn']    = 'Restore sekarang';

// -----------------------------------------------------------------------------
// Statuses
// -----------------------------------------------------------------------------
$lang['status_pending']     = 'Tertunda';
$lang['status_approved']    = 'Disetujui';
$lang['status_printed']     = 'Dicetak';
$lang['status_done']        = 'Selesai';
$lang['status_deleted']     = 'Dihapus';

// -----------------------------------------------------------------------------
// Another Statuse
// -----------------------------------------------------------------------------
$lang['pending']    = 'Tertunda';
$lang['approved']   = 'Disetujui';
$lang['printed']    = 'Dicetak';
$lang['done']       = 'Selesai';
$lang['deleted']    = 'Dihapus';

// -----------------------------------------------------------------------------
// Bitheme
// -----------------------------------------------------------------------------
$lang['error_browser_jadul']    = 'Web browser anda jadul!';

// -----------------------------------------------------------------------------
// File
// -----------------------------------------------------------------------------
$lang['file_not_found']     = 'Berkas %s tidak ada.';

// -----------------------------------------------------------------------------
// Authen
// -----------------------------------------------------------------------------
$lang['auth_incorrect_captcha']     = 'Kode validasi anda salah!.';
$lang['auth_username_blacklisted']  = 'Anda tidak dapat menggunakan username tersebut!.';
$lang['auth_incorrect_login']       = 'Login yang anda masukan salah.';
$lang['auth_incorrect_username']    = 'Username yang anda masukan salah.';
$lang['auth_incorrect_password']    = 'Password yang anda masukan salah.';
$lang['auth_banned_account']        = 'Akun anda sedang dicekal dengan alasan %s.';
$lang['auth_deleted_account']       = 'Akun tersebut sudah dihapus beberapa waktu yang lalu.';
$lang['auth_inactivated_account']   = 'Akun anda belum aktif.';
$lang['auth_login_success']         = 'Login berhasil.';
$lang['auth_username_in_use']       = 'Username tersebut sudah digunakan.';
$lang['auth_username_not_exists']   = 'Username tersebut tidak terdaftar.';
$lang['auth_email_in_use']          = 'Email tersebut sudah digunakan.';
$lang['auth_email_not_exists']      = 'Email tersebut tidak terdaftar.';
$lang['auth_current_email']         = 'Saat ini Anda tengah menggunakan email tersebut.';
$lang['auth_current_password']      = 'Saat ini Anda tengah menggunakan password tersebut.';
$lang['auth_inapproved_account']    = 'Akun anda belum disetujui.';
$lang['auth_registration_success']  = 'Proses registrasi pengguna berhasil, mendaftarkan akun baru.';
$lang['auth_registration_failed']   = 'Proses registrasi pengguna gagal.';
$lang['auth_username_length_min']   = 'Username harus lebih dari %s karakter';
$lang['auth_username_length_max']   = 'Username tidak boleh lebih dari %s karakter';
$lang['auth_password_length_min']   = 'Password harus lebih dari %s karakter';
$lang['auth_password_length_max']   = 'Password tidak boleh lebih dari %s karakter';
$lang['auth_login_by_login']        = 'Username atau Email';
$lang['auth_login_by_username']     = 'Username';
$lang['auth_login_by_email']        = 'Email';

// -----------------------------------------------------------------------------
// Database Utility
// -----------------------------------------------------------------------------
$lang['utily_backup_folder_not_exists']    = 'Direktori %s belum ada pada server anda.';
$lang['utily_backup_folder_not_writable']  = 'Anda tidak memiliki ijin untuk menulis pada direktori %s.';
$lang['utily_backup_process_failed']       = 'Proses backup database gagal.';
$lang['utily_backup_process_success']      = 'Proses backup database berhasil.';
$lang['utily_restore_success']             = 'Proses restorasi database berhasil.';
$lang['utily_upload_failed']               = 'Proses upload gagal.';

// -----------------------------------------------------------------------------
// Email Subjects
// -----------------------------------------------------------------------------
$lang['email_subject_forgot_password']      = 'Email Konfirmasi: Lupa password.';
$lang['email_subject_welcome']              = 'Email Konfirmasi: Selamat bergabung!';
$lang['email_subject_activate']             = 'Email Aktifasi: Selamat bergabung!';
$lang['email_subject_reset_password']       = 'Email Konfirmasi: Password baru anda telah siap';
$lang['email_subject_change_email']         = 'Email Aktifasi: Email baru anda telah siap diaktifkan';

// -----------------------------------------------------------------------------
// Bimedia Library
// -----------------------------------------------------------------------------
$lang['median_upload_policy']                     = '. Batas jumlah upload adalah: <i class="bold">%s</i> berkas dan hanya berkas dengan extensi: %s yang diijinkan.';
$lang['median_drop_area_selector_text']           = 'Drop files here to upload';
$lang['median_drop_processing_selector_text']     = 'Processing dropped files...';
$lang['median_upload_button_selector_text']       = 'Upload files';
$lang['median_file_type_not_allowed_text']        = 'Tipe berkas tidak diijinkan';
$lang['median_file_size_too_large_text']          = 'Ukuran berkas terlalu besar';
$lang['median_directory_not_writable_text']       = 'Uploads directory isn\'t writable';
$lang['median_text_auto_retry_note']              = 'Mencoba kembali {retryNum}/{maxAuto} ...';
$lang['median_text_fail_upload']                  = 'Upload gagal';
$lang['median_text_format_progress']              = '{percent}% dari {total_size}';
$lang['median_text_paused']                       = 'Tertunda';
$lang['median_text_waiting_response']             = 'Dalam proses...';
$lang['median_error_empty']                       = '{file} is empty, please select files again without it.';
$lang['median_error_max_height_image']            = 'Image is too tall.';
$lang['median_error_max_width_image']             = 'Image is too wide.';
$lang['median_error_min_height_image']            = 'Image is not tall enough.';
$lang['median_error_min_width_image']             = 'Image is not wide enough.';
$lang['median_error_min_size']                    = '{file} is too small, minimum file size is {minSizeLimit}.';
$lang['median_error_no_files']                    = 'No files to upload.';
$lang['median_error_on_leave']                    = 'The files are being uploaded, if you leave now the upload will be canceled.';
$lang['median_error_retry_fail_too_many_items']   = 'Retry failed - you have reached your file limit.';
$lang['median_error_size']                        = '{file} terlalu besar, ukuran maksimum adalah {sizeLimit}.';
$lang['median_error_too_many_items']              = 'Too many items ({netItems}) would be uploaded. Item limit is {itemLimit}.';
$lang['median_error_type']                        = '{file} has an invalid extension. Valid extension(s): {extensions}.';
$lang['median_client_name']                       = 'Original File Name:';
$lang['median_file_name']                         = 'Uploaded File Name:';
$lang['median_file_size']                         = 'File Size:';
$lang['median_file_type']                         = 'File Type:';
$lang['median_file_path']                         = 'Upload Destination:';

// -----------------------------------------------------------------------------
// Notice Message
// -----------------------------------------------------------------------------
// Registration
$lang['notice_404_title']                     = '404 Halaman tidak ditemukan';
$lang['notice_404_message']                   = 'The page you requested was not found.';

// Registration
$lang['notice_registration_success_title']    = 'Successful Registration';
$lang['notice_registration_success_message']  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim, at animi error porro alias nesciunt rem explicabo a vitae voluptas officiis sint delectus blanditiis repellat velit voluptatum natus dolor amet! ';
$lang['notice_registration_disabled_title']   = 'Registration Disabled';
$lang['notice_registration_disabled_message'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis, suscipit, dicta sint tempore accusantium unde assumenda autem fugiat adipisci molestiae sequi praesentium soluta consequatur facilis similique blanditiis non et perferendis. ';

// Activation
$lang['notice_activation_sent_title']         = 'Activation Email Sent';
$lang['notice_activation_sent_message']       = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, distinctio, accusamus, aut repellendus odio numquam est quos incidunt quaerat magni facere labore mollitia qui natus asperiores beatae quas sed ut. ';
$lang['notice_activation_complete_title']     = 'Activation Complete';
$lang['notice_activation_complete_message']   = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus, voluptates, laboriosam, in, quae quos excepturi nobis non aperiam tempora labore reiciendis temporibus a rem eos explicabo? Distinctio voluptatibus voluptas recusandae. ';
$lang['notice_activation_failed_title']       = 'Activation Failed';
$lang['notice_activation_failed_message']     = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi voluptatem facere repudiandae aliquam saepe. Quidem, omnis, cum excepturi autem alias iusto fugit ea similique ad sed necessitatibus veniam odit laboriosam. ';

// Password
$lang['notice_password_changed_title']        = 'Password Changed';
$lang['notice_password_changed_message']      = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, rem, expedita, error excepturi a quis velit sunt tempore omnis illum quisquam facilis. Veritatis, aspernatur, fugit voluptatibus eum alias est aliquam! ';
$lang['notice_password_sent_title']           = 'New Password Sent';
$lang['notice_password_sent_message']         = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae, repellat, quidem, accusantium cupiditate alias corrupti deleniti tempora aliquid impedit vel rem porro sapiente pariatur nesciunt doloribus dolores harum? Doloribus, magnam! ';
$lang['notice_password_reset_title']          = 'Password Reset';
$lang['notice_password_reset_message']        = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum, quidem, ad vel rerum dolorem alias consequatur dolorum quisquam voluptatibus officiis excepturi neque optio ea reiciendis temporibus nemo dignissimos voluptas unde. ';
$lang['notice_password_failed_title']         = 'Password Failed';
$lang['notice_password_failed_message']       = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis, accusamus, vitae, voluptates ea nostrum maxime tenetur dolores cupiditate perspiciatis perferendis nobis facere accusantium totam incidunt optio. Repudiandae id beatae praesentium. ';

// Email
$lang['notice_email_sent_title']              = 'Confirmation Email Sent';
$lang['notice_email_sent_message']            = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, voluptatum, voluptatibus mollitia ex blanditiis obcaecati debitis laudantium odio ipsam aut rem minima quod tenetur nostrum quisquam facilis voluptatem architecto fuga. ';
$lang['notice_email_activated_title']         = 'Your Email has been Activated';
$lang['notice_email_activated_message']       = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sint, minus dignissimos ipsa consequuntur praesentium dolor qui placeat doloremque reprehenderit voluptatum neque fuga facilis accusantium velit laborum eveniet asperiores quod id. ';
$lang['notice_email_failed_title']            = 'Email Sending Failed';
$lang['notice_email_failed_message']          = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui, vel, ratione, accusamus, ex similique iste dolores officiis recusandae omnis quas odit debitis quaerat sit magnam numquam consequuntur deserunt? Autem, repudiandae. ';

// User + Account
$lang['notice_user_banned_title']             = 'You have been Banned.';
$lang['notice_user_banned_message']           = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio, similique vitae deleniti iure natus beatae dolorum minus officia maxime libero possimus praesentium quos atque aperiam recusandae unde velit culpa assumenda. ';
$lang['notice_user_deleted_title']            = 'Your account has been Deleted.';
$lang['notice_user_deleted_message']          = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, reprehenderit, commodi, mollitia nemo sequi esse consectetur vitae tenetur autem minus alias deleniti saepe et tempore cum sunt at dolorem iure! ';
$lang['notice_acct_unapproved_title']         = 'Account not yet Approved';
$lang['notice_acct_unapproved_message']       = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad, sit quo laborum perspiciatis magnam placeat fugiat sed eligendi ipsa dolorem. Quo, in minus sint delectus necessitatibus alias nesciunt incidunt natus? ';
$lang['notice_logout_success_title']          = 'Logged Out';
$lang['notice_logout_success_message']        = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere commodi amet odit velit obcaecati ullam accusantium quia minima! Voluptatibus mollitia tempora veniam nihil quos quis explicabo quia deserunt asperiores cupiditate. ';

$lang['notice_access_denied_title']           = 'Oops! Anda tidak diperbolehkan mengakses halaman ini.';
$lang['notice_access_denied_message']         = 'Maaf, sepertinya administrator tidak memperbolehkan anda mengakses halaman ini. Jika ini merupakan suatu kesalahan, silahkan hubungi administrator terkait.';
$lang['notice_no_data_accessible_title']      = 'Oops! Tidak ada data untuk anda.';
$lang['notice_no_data_accessible_message']    = 'Maaf, sepertinya administrator tidak memperbolehkan anda melihat satu data-pun dihalaman ini. Jika ini merupakan suatu kesalahan, silahkan hubungi administrator terkait.';


/* End of file BootIgniter_lang.php */
/* Location: ./application/language/indonesian/BootIgniter_lang.php */

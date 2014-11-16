/**
 * @category    BlueFramework
 * @package     js
 * @subpackage  bootstrap
 * @author      Michał Adamiak    <chajr@bluetree.pl>
 * @copyright   chajr/bluetree
 * @version     0.2.0
 */

/**
 * hide element after time
 */
(function ($, window, undefined)
{
    $.fn.autoClose = function()
    {
        var time = $(this).data('auto-close');
        $(this).delay(time).slideUp('slow');
    }

    $(document).ready(function()
    {
        $('[data-auto-close]').autoClose();
    });
})(jQuery, this);

$(document).on('click', '#make_deposit', function () {
    var amount = $('#deposit').val();
    $.post('/make_deposit', {value: amount}, function (data) {
        var response = $.parseJSON(data);

        if (response.status === 'success') {
            $('.alert-success').text(response.message);
            $('.alert-success').data('auto-close', 5000);
            $('.alert-success').autoClose();
            $('.alert-success').show();

            var total_balance   = $('#total_balance').text();
            var real_money      = $('#real_money').text();

            $('#total_balance').text(parseFloat(total_balance) + parseFloat(amount));
            $('#real_money').text(parseFloat(real_money) + parseFloat(amount));
        } else {
            $('.alert-danger').text(response.message);
            $('.alert-danger').data('auto-close', 5000);
            $('.alert-danger').autoClose();
            $('.alert-danger').show();
        }
    })
});

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
    $.fn.autoClose = function() {
        var time = $(this).data('auto-close');
        $(this).delay(time).slideUp('slow');
    };

    $(document).ready(function() {
        $('[data-auto-close]').autoClose();
    });
})(jQuery, this);

$(document).on('click', '#make_deposit', function () {
    var amount = $('#deposit').val();
    $.post('/make_deposit', {value: amount}, function (data) {
        var response = $.parseJSON(data);

        if (response.status === 'success') {
            showMessage(response.message, 'success');

            var total_balance   = $('#total_balance').text();
            var real_money      = $('#real_money').text();

            $('#total_balance').text(parseFloat(total_balance) + parseFloat(amount));
            $('#real_money').text(parseFloat(real_money) + parseFloat(amount));
        } else {
            showMessage(response.message, 'danger');
        }
    })
});

$(document).on('click', '#spin', function () {
    var betAmount = $('#bet').val();

    $.post('/make_bet', {value: betAmount}, function (data) {
        var response = $.parseJSON(data);

        if (response.status !== 'success') {
            showMessage(response.message, 'danger');
            return null;
        }

        if (response.message === 'win') {
            showMessage(response.data.message, 'success');
            $('#win').val(response.data.value);
        } else {
            showMessage(response.data.message, 'danger');
            $('#win').val(response.data.value);
        }
    });
});

/**
 * generic function to show system message
 * 
 * @param message
 * @param type
 */
function showMessage(message, type) {
    var element = '.alert-' + type;

    $(element).text(message);
    $(element).data('auto-close', 5000);
    $(element).autoClose();
    $(element).show();
}

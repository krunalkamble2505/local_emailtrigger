/**
 * Course Completion report page.
 *
 * @package     local_emailtrigger
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([
    'jquery',
    'core/ajax',
], function($, ajax) {

    function init() {

        $("#et_send_rand_email_btn").on('click', function(event) {

            console.log('CLICK');

            /**
             * Promise lists.
             */
            var promise = ajax.call([{
                methodname: "local_emailtrigger_send_email_to_users",
                args: '',
            }, true]);
    
            promise[0]
                .done(function(response) {
                    if (response.success) {
                        $('#et_send_rand_emails .alert-success').css('display', 'block');
                    } else {
                        $('#et_send_rand_emails .alert-danger').css('display', 'block');
                    } 
                })
                .fail(function(ex) {
                    $('#et_send_rand_emails .alert-danger').css('display', 'block');
                });
        });

    }


    return {
        init: init
    };


});

import result from "postcss/lib/result";

require('./bootstrap');
// ES6 Modules or TypeScript
import Swal from 'sweetalert2'
require('alpinejs');

$(document).ready(function () {
    let ajaxForm = $('form.ajax-form');

    $(ajaxForm).each(function () {
        $(this).on('submit', (e)=>{
            e.preventDefault();
            let method = $(this).find('input[name="_method"]').val() || $(this).attr('method');
            let data = $(this).serialize();
            $.ajax({
                type:method,
                url:$(this).attr('action'),
                data:data,
                dataType:'json',
                success:(response) => {
                    console.log(response);
                    let redirect = response.redirect || null
                    if (response.success){
                        handleSuccess(response.success, redirect);
                    }
                },
                error:(xhr, status, err) => {
                    handleErrors(xhr);
                }
            })
        })
    })

    function handleSuccess(success, redirect) {
        Swal.fire({
            icon: 'success',
            html: success,
            title: 'ok',
            allowOutsideClick: false,
        }).then((result)=>{
            if (result.value){
                if (redirect){
                    window.location = redirect;
                }
            }
        })
    }

    function handleErrors(xhr)
    {
        switch (xhr.status) {
            case 422: //erreur de validation
                let errorString = '';
                $.each(xhr.responseJSON.errors, function (key, value) {
                    errorString += "<p class='text-left text-danger'>"+value+"</p>";
                })
                Swal.fire({
                    title: 'Error!',
                    html: errorString,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                break;
            default:
                break;
        }
    }
})


(($) => {
    document.querySelector('.item-quantity').change((e) =>{
        $.ajax({
            method: "PUT",
            url: "/cart/" + $(this).data('id'),
            data: {
                quantity: $(this).val(),
                _token: csrfToken
            }
        });
    })
})(jQuery)
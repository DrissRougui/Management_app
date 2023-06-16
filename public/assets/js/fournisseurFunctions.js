$(".removable").click(function(e) {
    e.currentTarget.parentNode.parentNode.remove()
    let id=e.currentTarget.getAttribute("data-id")
    
    $.ajax({
        type: "POST",
        url: "http://localhost:8000/fournisseur/remove",
        data: {id:id},
        success: function (response) {
        alert( response );
        }
        });
})



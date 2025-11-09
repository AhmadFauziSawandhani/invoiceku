function showError(msg, callback = null){
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: msg,
    }).then(function(res){
        if(res && callback){
            callback();
        }
    })
}

function showSuccess(msg, callback = null){
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: msg,
    }).then(function(res){
        if(res && callback){
            callback();
        }
    })
}

function number_format(amount) {
    return amount.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
}
function sleep(ms, callback) {
    setTimeout(callback, ms);
}

function copy(button) {
    const password = button.dataset.ip;
    navigator.clipboard.writeText(password)
        .then(() => {
            // Thông báo SweetAlert2 khi copy thành công
            Swal.fire({
                icon: 'success',
                title: 'Copy Thành Công!',
                text: 'Địa chỉ IP đã được sao chép',
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            // Thông báo lỗi nếu copy thất bại
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không thể sao chép, thử lại!',
            });
            console.error('Failed to copy: ', error);
        });
}

function togglePassword(button) {
    const passwordElement = button.previousElementSibling;
    const password = button.dataset.passwd;
    const action = button.dataset.action;

    if (action === 'show') {
        passwordElement.textContent = password;
        button.dataset.action = 'hide';
        button.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        passwordElement.textContent = '***********'
        button.dataset.action = 'show';
        button.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

function encodeData(data, key) {
    var iv;
    window.crypto.subtle.generateKey(
        {
            name: "AES-CBC",
            length: 256
        },
        false,
        ["encrypt", "decrypt"]
    )
    .then(function (aesKey) {
        iv = window.crypto.getRandomValues(new Uint8Array(16));
        return window.crypto.subtle.encrypt(
            {
                name: "AES-CBC",
                iv: iv
            },
            aesKey,
            new TextEncoder().encode(JSON.stringify(data))
        );
    })
    .then(function (encrypted) {
        var encodedData = new Uint8Array(iv.byteLength + encrypted.byteLength);
        encodedData.set(iv, 0);
        encodedData.set(new Uint8Array(encrypted), iv.byteLength);
        var encodedString = btoa(String.fromCharCode.apply(null, encodedData));
        return encodedString;
    })
    .catch(function (err) {
        console.error(err);
    });
}
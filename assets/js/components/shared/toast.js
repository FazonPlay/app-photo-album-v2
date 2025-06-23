export const showToast = (message, color) => {
    const toastElement = document.querySelector('#toast');
    const toastBody = toastElement.querySelector('.toast-body');

    const toast = new bootstrap.Toast(toastElement, {
        delay: 5000
    });

    toastElement.classList.remove('bg-danger', 'bg-success');

    toastElement.classList.add(color === 'bg-danger' ? 'bg-danger' : 'bg-success');
    toastBody.classList.add('text-white');

    toastBody.innerHTML = message;
    toast.show();
};
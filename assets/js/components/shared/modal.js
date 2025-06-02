export const showModal = (message, title = 'Information') => {
    const modalElement = document.querySelector('#myModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: false
    });

    modalElement.querySelector('.modal-title').textContent = title;
    modalElement.querySelector('.modal-body').innerHTML = message;

    modal.show();
}
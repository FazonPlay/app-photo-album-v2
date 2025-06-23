export const createAccount = async (username, email, password) => {
    const formData = new URLSearchParams();
    formData.append('username', username);
    formData.append('password', password);
    formData.append('email', email);
    formData.append('action', 'create');

    const response = await fetch('index.php?component=create_user', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        method: 'POST',
        body: formData
    });
    return await response.json();
};

export const updateUser = async (form, id) =>  {

    const data = new FormData(form)

    const response = await fetch(`index.php?component=user&id=${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        method: 'POST',
        body: data
    })

    return response.json()
}

export const getUsers = async (currentPage = 1) => {
    const response = await fetch(`index.php?component=users&page=${currentPage}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    return await response.json()
}



export const removeUser = async (id) => {
    const response = await fetch("index.php?component=users", {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        body: new URLSearchParams({ action: "delete", id })
    });

    return await response.json();
};
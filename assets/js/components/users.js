import { createAccount, getUsers, removeUser, updateUser } from "../services/user.js";
import { showToast } from "./shared/toast.js";

export const refreshList = async (page) => {
    const spinner = document.querySelector('#spinner');
    const listElement = document.querySelector('#users-list'); // fixed selector

    spinner.classList.remove('d-none');

    const data = await getUsers(page);

    const listContent = [];

    for (let i = 0; i < data.results.length; i++) {
        const user = data.results[i];
        listContent.push(`<tr>
            <td>${user.user_id}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>${user.registration_date ?? ''}</td>
            <td>${user.last_login ?? ''}</td>
            <td>${user.roles}</td>
            <td>${user.is_active == 1 ? 'Active' : 'Inactive'}</td>
            <td>
                <a href="index.php?component=user&id=${user.user_id}">
                    <i class="fa fa-edit text-success"></i>
                </a>
                <a href="#" class="delete-user" data-id="${user.user_id}">
                    <i class="fa fa-trash text-danger"></i>
                </a>
            </td>
        </tr>`);
    }

    listElement.innerHTML = listContent.join('');

    document.querySelector('#pagination').innerHTML = getPagination(data.count);

    handlePaginationNavigation(page);

    spinner.classList.add('d-none');

    setupDeleteButtons();
};


const setupDeleteButtons = () => {
    document.querySelectorAll(".delete-user").forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();
            const userId = e.target.closest("a").dataset.id;

            if (!confirm("Are you sure you want to delete this user?")) return;

            const result = await removeUser(userId);
            if (result.success) {
                showToast("User deleted successfully!");
                await refreshList(1);
            } else {
                showToast("Failed to delete user.");
            }
        });
    });
};
const getPagination = (total) => {
    const countPages = Math.ceil(total / 20);
    let paginationButton = [];
    paginationButton.push(` <li class="page-item"><a class="page-link" href="#" id="previous-link">Previous</a></li>`);

    for (let i = 1; i <= countPages; i++) {
        paginationButton.push(`<li class="page-item"><a data-page="${i}" class="page-link pagination-btn" href="#">${i}</a></li>`);
    }

    paginationButton.push(` <li class="page-item"><a class="page-link" href="#" id="next-link">Next</a></li>`);

    return paginationButton.join('');
};

const handlePaginationNavigation = (page) => {
    const previousLink = document.querySelector('#previous-link');
    const nextLink = document.querySelector('#next-link');
    const paginationBtns = document.querySelectorAll('.pagination-btn');

    if (page === 1) {
        previousLink.classList.add('disabled');
    } else {
        previousLink.classList.remove('disabled');
    }


    previousLink.addEventListener('click', async () => {

        if (page > 1) {
            page--;
            await refreshList(page);


        }
    });

    for (let i = 0; i < paginationBtns.length; i++) {
        paginationBtns[i].addEventListener('click', async (e) => {
            const pageNumber = e.target.getAttribute('data-page');
            await refreshList(pageNumber);
        });
    }

    nextLink.addEventListener('click', async () => {
        page++;
        await refreshList(page);
    });
};

export const handleUserForm = () => {
    const validBtn = document.querySelector('#valid-form-user');
    let result, message;

    validBtn.addEventListener('click', async (e) => {
        const form = document.querySelector('#user-form');
        e.preventDefault();


        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }

        if (e.target.name === 'create_button') {
            result = await createAccount(form);
            message = 'The user has been created successfully';
        } else {
            result = await updateUser(form, e.target.getAttribute('data-id'));
            message = 'The user has been updated successfully';
        }

        if (result.hasOwnProperty('success')) {
            showToast(message, 'bg-success');
            if (e.target.name === 'create_button') form.reset();
        } else if (result.hasOwnProperty('error')) {
            showToast(`An error occurred: ${result.error}`, 'bg-danger');
        }
    });
};




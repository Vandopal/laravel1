<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Profiles</title>
</head>
<body>
<div class="container">
    <div class="row my-5">
        <div class="col-lg-12">
            <div class="rounded shadow p-4">
                <h2>Зарегистрированные пользователи</h2>
                <div class="row justify-content-between">
                    <div class="col-8">
                    </div>
                    <div class="col-4 text-end">
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addProfile" id="addUserBtn"><i>Добавить пользователя</i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-5">
        <div class="col-lg-12">
            <div class="rounded shadow p-4">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Почта</th>
                        <th>Дата рождения</th>
                        <th>Пол</th>
                        <th>Редактирование</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Регистрация</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <div class="mb-3">
                            <label class="form-label">Фио</label>
                            <input type="text" name="fio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Почта</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Дата рождения</label>
                            <input type="date" name="birthday" class="form-control" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
                            <label class="form-check-label" for="exampleRadios1">Мужской</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
                            <label class="form-check-label" for="exampleRadios2">Женский</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </div>
                        <input type="hidden" name="id">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

let state = {
    data: []
};

const render = (state) => {
  tbody.innerHTML = '';
  state.data.forEach((objData) => {
    const tr = document.createElement('tr');
    tr.dataset.id = objData.id;
    const id = document.createElement('td');
    id.textContent = objData.id;
    const fio = document.createElement('td');
    fio.textContent = objData.fio;
    const email = document.createElement('td');
    email.textContent = objData.email;
    const birthday = document.createElement('td');
    birthday.textContent = objData.birthday.split('-').reverse().join('.');
    const gender = document.createElement('td');
    gender.textContent = objData.exampleRadios === 'option1' ? 'Мужской' : 'Женский';

    const buttons = document.createElement('td');
    buttons.innerHTML = `
        <button class="btn btn-light" name="edit" data-id="${objData.id}">редактировать</button>
        <button class="btn btn-light" name="delete" data-id="${objData.id}">удалить</button>
    `;

    tr.appendChild(id);
    tr.appendChild(fio);
    tr.appendChild(email);
    tr.appendChild(birthday);
    tr.appendChild(gender);
    tr.appendChild(buttons);
    tbody.appendChild(tr);
  });
};

const form = document.querySelector('#userForm');
const tbody = document.querySelector('tbody');
const modal = document.querySelector('#addProfile');

let count = 0;

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const objData = Object.fromEntries(formData);
    objData.id = objData.id ? parseInt(objData.id) : ++count;
    if (!state.data.some(user => user.id === objData.id)) {
        state.data.push(objData);
    } else {
        const index = state.data.findIndex(item => item.id === objData.id);
        state.data[index] = objData;
    }
    render(state);
    form.reset();
    form.elements['id'].value = '';
    bootstrap.Modal.getInstance(modal).hide();
});

tbody.addEventListener('click', (e) => {
    if (e.target.name === 'delete') {
        const id = parseInt(e.target.closest('tr').dataset.id);
        state.data = state.data.filter((objData) => objData.id !== id);
        render(state);

        count = state.data.length ? Math.max(...state.data.map(user => user.id)) : 0;
    } else if (e.target.name === 'edit') {
        const id = parseInt(e.target.closest('tr').dataset.id);
        const objData = state.data.find((objData) => objData.id === id);
        for (let key in objData) {
            if (form.elements[key]) {
                form.elements[key].value = objData[key];
            }
        }
        bootstrap.Modal.getOrCreateInstance(modal).show();
    }
});

document.getElementById('addUserBtn').addEventListener('click', () => {
    form.reset();
    form.elements['id'].value = '';
});

render(state);

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>

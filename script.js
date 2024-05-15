const form = document.getElementById("form");
const tableBody = document.getElementById("tableBody");

let hasError = false;

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(form);
  const username = formData.get("username");

  if (username.trim().length === 0) {
    hasError = true;
    // print a error msg in the dom
    return;
  }

  const resp = await fetch("controller.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username }),
  });
  const data = await resp.json();

  console.log(data);

  revalidateUsers();

  form.reset();
});

// load users
addEventListener("load", async () => {
  const users = await fetchUsers();

  renderUsers(users);
});

const fetchUsers = async () => {
  const resp = await fetch("controller.php");
  const data = await resp.json();
  return data;
};

const renderUsers = (users) => {
  const fragment = document.createDocumentFragment();
  for (const user of users) {
    const tr = document.createElement("tr");

    const btnTextContent = ["Eliminar", "Actualizar"];

    const deleteUser = async (userId) => {
      const resp = await fetch(`controller.php/${userId}`, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
        },
      });
      const data = await resp.json();
      console.log(data);
    };

    const btns = btnTextContent.map((btnText) => {
      const form = document.createElement("form");
      form.setAttribute("method", "post");

      const btn = document.createElement("button");
      btn.setAttribute("type", "submit");
      btn.textContent = btnText;

      if (btnText === "Eliminar") {
        form.addEventListener("submit", async (e) => {
          e.preventDefault();
          const isConfirm = confirm("Estas seguro?");
          if (!isConfirm) return;

          await deleteUser(user.id);
          revalidateUsers();
        });
      }
      if (btnText === "Actualizar") {
        form.addEventListener("submit", (e) => {
          e.preventDefault();
          alert("Actualizar: " + user.id);
        });
      }

      form.append(btn);
      return form;
    });
    // btn.classList.add('')
    // btn.textContent = "Eliminar";

    // data by column
    // const data = [user.id, user.username, btn];
    const userValues = [...Object.values(user), btns];

    const elements = userValues.map((d) => {
      const td = document.createElement("td");

      if (d instanceof Array) {
        for (const btn of d) {
          td.append(btn);
        }
        return td;
      }
      td.append(d);
      return td;
    });

    for (const element of elements) {
      tr.append(element);
    }

    fragment.append(tr);
  }

  tableBody.append(fragment);
};

const revalidateUsers = async () => {
  const users = await fetchUsers();

  // remove all the previous users
  while (tableBody.firstChild) {
    tableBody.firstChild.remove();
  }

  // render new users
  renderUsers(users);
};

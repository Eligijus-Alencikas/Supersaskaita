$(document).ready(() => {
  get_my_bills();
});

const get_my_bills = () => {
  data = {
    supersaskaita: true,
    use: "get_my_bills",
  };
  $.post("", data, (data, textStatus, jqXHR) => {
    console.log(data);
    console.log(textStatus);
    insert_into_table(data);
  })
};

const insert_into_table = (table_data) => {
  table_data.forEach((row) => {
    let row_html = /*html*/ `
    <tr>
        <td>${row["doc_num"]}</td>
        <td>${row["doc_name"]}</td>
        <td>${row["date_of_issue"]}</td>
        <td>${row["customer"]}</td>
        <td><button class="edit" doc_id="${row["id"]}">Redaguoti</button></td>
        <td><button class="delete" doc_id="${row["id"]}">Ištrinti</button></td>
    </tr>
    `;
    $("#bill_table tbody").append(row_html);
  });
};

$(document).on("click", ".edit", (e) => {
  e.preventDefault;

  const data = {
    use: "edit_bill",
    supersaskaita: true,
    id: $(e.target).attr("doc_id"),
  };

  $.post("", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);

    window.location.href = "/supersaskaita/edit-bill";
  });
});

$(document).on("click", ".delete", (e) => {
  e.preventDefault;

  const data = {
    use: "delete_bill",
    supersaskaita: true,
    id: $(e.target).attr("doc_id"),
  };

  $.post("", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);

    window.location.href = "/supersaskaita/mano-saskaitos-serijos";
  });
});

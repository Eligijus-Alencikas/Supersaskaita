let prod_table_id = 1;
let deleted_prod_table_rows = [];

$("#expand-prod-table").click((e) => {
  e.preventDefault();

  prod_table_id++;

  const table_row = /*html*/ `
    <tr id="row-${prod_table_id}" row="${prod_table_id}">
      <td>
        <input
          type="text"
          id="prod-name-${prod_table_id}"
          name="prod-name-${prod_table_id}"
        />
      </td>
      <td>
        <input
          type="text"
          id="prod-ammo-${prod_table_id}"
          name="prod-ammo-${prod_table_id}"
          class="prod-amount"
        />
      </td>
      <td>
        <input
          type="text"
          id="prod-price-${prod_table_id}"
          name="prod-price-${prod_table_id}"
          class="prod-price"
        />
      </td>
      <td>
        <p><span id="prod-total-${prod_table_id}">0</span> Eur</p>
      </td>
      <td>
        <button class="del_row" row="${prod_table_id}">Ištrinti eilę</button>
      </td>
    </tr>
  `;
  $("#prod-table tbody").append(table_row);
});

$("#save").click((e) => {
  e.preventDefault();

  console.log("doing stuff");

  let img = document.getElementById("doc-img");
  const reader = new FileReader();

  if (img.files && img.files[0]) {
    reader.readAsDataURL(img.files[0]);

    reader.onload = (e) => {
      post_data(reader.result);
    };
  } else {
    post_data(null);
  }
});

$(document).on("click", ".del_row", (e) => {
  e.preventDefault;

  // Find the closest 'tr' element
  let row = $(e.target).closest("tr");

  // Get the values of the attributes
  let buttonRow = $(e.target).attr("row");

  deleted_prod_table_rows.push(buttonRow);

  // code for deleting a row
  row.remove();
  updateTotalSum();
});

$("#doc-img").change(function (e) {
  const fileInput = e.target;
  const imagePreview = $("#image-preview");

  if (fileInput.files && fileInput.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      imagePreview.attr("src", e.target.result);
    };

    reader.readAsDataURL(fileInput.files[0]);
  }
});

$(document).ready(() => {
  $("table").on("input", ".prod-amount, .prod-price", (e) => {
    // Get the current row number

    let rowNumber = $(e.target).closest("tr").attr("row");

    // Get values of amount and price for the current row
    let amount = parseFloat($(`#prod-ammo-${rowNumber}`).val()) || 0;
    let price = parseFloat($(`#prod-price-${rowNumber}`).val()) || 0;

    // Calculate total and update the span for the current row
    let total = (amount * price).toFixed(2);
    $(`#prod-total-${rowNumber}`).text(total);
    updateTotalSum();
  });

  $("#PVM").on("input", () => {
    updateTotalSum();
  });

  let currentDate = new Date().toISOString().split("T")[0];
  $("#date-of-issue").val(currentDate);
});

const updateTotalSum = () => {
  let totalSum = 0;
  $("tbody > tr").each((index, tr) => {
    let rowNumber = $(tr).attr("row");
    let amount = parseFloat($(`#prod-ammo-${rowNumber}`).val()) || 0;
    let price = parseFloat($(`#prod-price-${rowNumber}`).val()) || 0;
    totalSum += amount * price;
  });

  let PVM = totalSum * ($("#PVM").val() / 100);

  $("#PVM-eur").text(PVM.toFixed(2));

  $("#PVM-total").text((totalSum + PVM).toFixed(2));
};

const post_data = (img) => {
  data = {
    supersaskaita: true,
    use: "add_bill_1",

    doc_name: $("#doc-name").val(),
    doc_num: $("#doc-num").val(),
    doc_img: img,
    customer: $("#customer").val(),
    sender: $("#sender").val(),
    date_of_issue: $("#date-of-issue").val(),
    PVM: $("#PVM").val(),
    products: [],
  };

  for (let i = 1; i < prod_table_id + 1; i++) {
    if (deleted_prod_table_rows.includes(i)) {
      continue;
    }
    let prod_obj = {
      name: $("#prod-name-" + i.toString()).val(),
      ammount: $("#prod-ammo-" + i.toString()).val(),
      price: $("#prod-price-" + i.toString()).val(),
    };
    data.products.push(prod_obj);
  }

  console.log(data);

  $(document.body).css({
    opacity: 0.5,
    cursor: "wait",
  });
  $(".wrapper").css({
    "pointer-events": "none",
  });

  $.post("", data, function (data, textStatus, jqXHR) {
    console.log(data);
    console.log(textStatus);

    window.location.href = "/supersaskaita/mano-saskaitos-serijos";
  });
};

$("#submit-signup").click(function (e) {
  e.preventDefault();

  data = {
    use: "signup",
    email: $("#email").val(),
    password: $("#password").val(),
    supersaskaita: true,
  };

  $.post("/", data, (data, textStatus, jqXHR) => {
    console.log(data);
    console.log(textStatus);
    console.log(jqXHR);

    window.location.href = "/supersaskaita/submit-signup";
  }).fail((data, textStatus, jqXHR) => {
    $("#warning").text(data.responseText);
  });
});

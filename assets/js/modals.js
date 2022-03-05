import $ from "jquery"

$(".create-list").on("click", function () {
  $("#create-list").show()
})

$(".modal-content .close").on("click", function () {
  $("#create-list").hide()
})

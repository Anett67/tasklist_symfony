import $ from "jquery"

$("#tasklist-delete").on("submit", function (e) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cette liste?")) {
        e.preventDefault()
    }
})

$("#task-delete").on("submit", function (e) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cette liste?")) {
        e.preventDefault()
    }
})

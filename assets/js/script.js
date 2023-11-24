
function closeAlert(element) {
    var alert = new bootstrap.Alert(element.closest('.alert'));
    alert.close();
}
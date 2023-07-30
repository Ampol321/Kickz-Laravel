
document.getElementById("shipment").addEventListener("change", function () {
    if (this.value === "1") {
        document.getElementById("shipment_cost").value = "₱35.25";
    } else if (this.value === "2") {
        document.getElementById("shipment_cost").value = "₱45.67";
    } else if (this.value === "3") {
        document.getElementById("shipment_cost").value = "₱55.89";
    } else {
        document.getElementById("shipment_cost").value = "";
    }
});

document.getElementById("mySelect").addEventListener("change", function () {
    if (this.value === "2" || this.value === "3") {
        document.getElementById("otherOption").style.display = "block";
        document.getElementById("otherInput").required = true;

    } else {
        document.getElementById("otherOption").style.display = "none";
        document.getElementById("otherInput").required = false;
        document.getElementById("otherInput").name = null;
        document.getElementById("otherInput").value = "x";
    }
});

$('.alert').fadeOut(5000, function () {
    $(this).remove();
});

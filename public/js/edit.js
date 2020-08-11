function updateLost(lostVal, value) {
    if (value == lostVal)
        $("#lost-input").removeClass("d-none");
    else if (!$("#lost-input").hasClass("d-none"))
        $("#lost-input").addClass("d-none");
}
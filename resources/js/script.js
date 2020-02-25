window.showHideDiv = () => {
    var srcElements = document.getElementsByClassName("export-item");
    srcElements = Array.from(srcElements);
    if (srcElements != null) {
        srcElements.map((srcElement) => {
            if (window.getComputedStyle(srcElement).display != "none") {
                srcElement.style.display = 'none';
            }
            else {
                srcElement.style.display = 'flex';
            }
        });
    }
}

window.deleteFunction = (id, title, author) => {
    if (confirm("\nDelete book \n"+title+" by "+author+"?")) {
        axios.delete('/delete/'+id)
        .then(function (response) {
            window.location = "/";
        })
        .catch(function (error) {
            alert(error);
        });
    }
}

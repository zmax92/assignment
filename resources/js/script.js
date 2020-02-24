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
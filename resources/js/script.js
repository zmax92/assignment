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
    if (confirm("\nDelete book \n" +title+" by "+author+"?")) {
        axios.delete('/delete/'+id)
        .then(function () {
            window.location = "/";
        })
        .catch(function (error) {
            alert(error);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    var penItems = document.querySelectorAll('.edit-icon');
    penItems = Array.from(penItems);
    if (penItems != null) {
        penItems.map((srcElement) => {
            // attach event click on all pen icons
            srcElement.addEventListener('click', (element) => {
                // find closest cell
                var td = element.target.closest('td');
                var titleWrapper = element.target.closest('.title-wrapper');
                var inputWrapper = td.querySelector('.input-wrapper');
                var input = inputWrapper.querySelector('input');

                titleWrapper.style.display = 'none';
                inputWrapper.style.display = 'block';
                input.focus();
            });
        });
    }

    var inputItems = document.querySelectorAll('.input-wrapper input');
    inputItems = Array.from(inputItems);
    if (inputItems != null) {
        inputItems.map((srcElement) => {
            srcElement.addEventListener('blur', (element) => {
                element = element.target;
                var inputWrapper = element.closest('.input-wrapper');
                var td = element.closest('td');

                if(!element.value){
                    if(!td.querySelector('.alert-danger')){
                        var errorEl = document.createElement('div');
                        errorEl.classList.add('alert');
                        errorEl.classList.add('alert-danger');
                        errorEl.innerHTML = 'The author field is required.';

                        td.append(errorEl);
                        setTimeout(() => {
                            errorEl.remove();
                        }, 2500);
                    }

                    return false;
                }

                var titleWrapper = td.querySelector('.title-wrapper');
                var id = element.getAttribute('data-id');

                axios.patch('/'+id, {
                    author: element.value,
                })
                .then(function () {
                    inputWrapper.style.display = 'none';
                    titleWrapper.style.display = 'block';
                    titleWrapper.querySelector('.text-static').innerHTML = element.value;
                })
                .catch(function (error) {
                    alert(error.message);
                });
            });
        });
    }
})

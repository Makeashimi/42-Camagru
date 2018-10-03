var img;
var id;

//if images
    pictures = document.getElementsByTagName('img');
    for (i = 0; i < pictures.length; i++) {
        pictures[i].addEventListener("click", getId);
    }

function hide_div() {
    document.getElementById('transparant').style.visibility = "hidden";
    document.getElementById('hide').style.visibility = "hidden";
}

function getId(event) {
    id = event.currentTarget.alt;
}

function showdialog() {
    hide = document.getElementById('hide').style.visibility = "visible";
    transparant = document.getElementById('transparant').style.visibility = "visible";
}

function askedDelete() {
    answer = confirm('Are you sure you want to remove your picture ?');
    console.log(id);

    if (answer) {
        var req = new XMLHttpRequest();
        req.open("POST", "dialogbox.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                document.location.href = "index.php";
            }
        };
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send("id="+id);
    }
}
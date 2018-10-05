var img;
var id;
var src;
var div_delete = false;
var req = new XMLHttpRequest();
var comment = document.getElementById('comment');
// var popup = document.getElementById('popup');
var div_like;

//if images
pictures = document.getElementsByTagName('img');
for (i = 0; i < pictures.length; i++) {
    pictures[i].addEventListener("click", showdialog);
}

function hide_div() {
    document.getElementById('transparant').style.visibility = "hidden";
    document.getElementById('hide').style.visibility = "hidden";
}

function check_id_user() {
    req.open("POST", "ajax.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            ret = req.responseText;
            console.log(ret);
            str = ret.split(" ");
            // console.log(str);
            if (div_like)
                popup.removeChild(div_like);
            div_like = document.createElement('div');
            div_like_text = document.createTextNode(parseInt(str[0]));
            div_like.appendChild(div_like_text);
            popup.insertBefore(div_like, comment);
            if (!str[1]) {
                if (!div_delete) {
                    div_delete = document.createElement('div');
                    div_delete.setAttribute('class', 'remove');
                    div_delete.setAttribute('onClick', 'askedDelete()');
                    div_delete_text = document.createTextNode('Delete the picture ?');
                    div_delete.appendChild(div_delete_text);
                    popup.insertBefore(div_delete, div_null);
                    // console.log('Ce nom d user est bon, div creee');
                }
                // else
                    // console.log('Ce nom user est bon mais la div a pas besoin detre cree');
            }
            else {
                if (div_delete) {
                    popup.removeChild(div_delete);
                    div_delete = false;
                    // console.log('Mauvais user name, div existante, delete la');
                }
            }
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // console.log(id);
    req.send("picture_id="+id);
}

function showdialog(event) {
    id = event.currentTarget.alt;
    // console.log(id);
    if (id) {
        src = event.currentTarget.src;

        hide = document.getElementById('hide').style.visibility = "visible";
        transparant = document.getElementById('transparant').style.visibility = "visible";
        popup = document.getElementById('popup');
        div_null = document.getElementById('null');

        //console.log(src);
        img = document.getElementById('image_gallery');
        img.setAttribute('src', src);
        //show_first_like();
        check_id_user();
    }
}

function askedLike() {
    // console.log(id);
    req.open("POST", "ajax.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            if (req.responseText != 'Fail') {
                popup.removeChild(div_like);
                div_like = document.createElement('span');
                div_like_text = document.createTextNode(parseInt(req.responseText));
                div_like.appendChild(div_like_text);
                popup.insertBefore(div_like, comment);
            }
            else
                alert('Sorry, you need to sign it to like this picture ! (;');
            // echo "<div onClick='askedLike()'>Like : </div>";
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("like=true&id="+id);
}

function askedDelete() {
    answer = confirm('Are you sure you want to remove your picture ?');
    console.log(id);

    if (answer) {
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
